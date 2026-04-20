<?php

namespace App\Services;

use App\Models\Donation;

class MidtransService
{
    protected string $serverKey;
    protected string $clientKey;
    protected bool   $isProduction;
    protected string $baseUrl;

    public function __construct()
    {
        $this->serverKey    = config('midtrans.server_key', '');
        $this->clientKey    = config('midtrans.client_key', '');
        $this->isProduction = config('midtrans.is_production', false);
        $this->baseUrl      = $this->isProduction
            ? 'https://app.midtrans.com/snap/v1'
            : 'https://app.sandbox.midtrans.com/snap/v1';
    }

    /**
     * Buat Snap Token untuk pembayaran
     */
    public function createSnapToken(Donation $donation): array
    {
        if (empty($this->serverKey)) {
            // Mode dummy: belum ada server key (menunggu verifikasi)
            return [
                'token'        => 'DUMMY-TOKEN-' . $donation->order_id,
                'redirect_url' => route('admin.dashboard'),
                'dummy'        => true,
            ];
        }

        $payload = [
            'transaction_details' => [
                'order_id'     => $donation->order_id,
                'gross_amount' => (int) $donation->amount,
            ],
            'customer_details' => [
                'first_name' => $donation->donor_name,
                'email'      => $donation->donor_email,
                'phone'      => $donation->donor_phone ?? '',
            ],
            'item_details' => [
                [
                    'id'       => $donation->program_id ?? 'GENERAL',
                    'price'    => (int) $donation->amount,
                    'quantity' => 1,
                    'name'     => $donation->program?->name ?? 'Donasi Umum - Jaf Donation',
                ],
            ],
            'callbacks' => [
                'finish' => config('app.url') . '/donasi/selesai',
            ],
        ];

        try {
            $response = $this->httpPost('/transactions', $payload);
            return [
                'token'        => $response['token'],
                'redirect_url' => $response['redirect_url'],
                'dummy'        => false,
            ];
        } catch (\Exception $e) {
            throw new \RuntimeException('Gagal membuat token Midtrans: ' . $e->getMessage());
        }
    }

    /**
     * Verifikasi signature webhook dari Midtrans
     */
    public function verifySignature(array $payload): bool
    {
        $orderId       = $payload['order_id'] ?? '';
        $statusCode    = $payload['status_code'] ?? '';
        $grossAmount   = $payload['gross_amount'] ?? '';
        $signatureKey  = hash('sha512', $orderId . $statusCode . $grossAmount . $this->serverKey);
        return hash_equals($signatureKey, $payload['signature_key'] ?? '');
    }

    /**
     * Proses status dari callback Midtrans
     */
    public function resolvePaymentStatus(string $transactionStatus, string $fraudStatus = ''): string
    {
        return match (true) {
            $transactionStatus === 'capture' && $fraudStatus === 'accept' => 'paid',
            $transactionStatus === 'settlement'                            => 'paid',
            in_array($transactionStatus, ['cancel', 'deny', 'failure'])   => 'failed',
            $transactionStatus === 'expire'                                => 'expired',
            $transactionStatus === 'refund'                                => 'refund',
            default                                                        => 'pending',
        };
    }

    private function httpPost(string $endpoint, array $payload): array
    {
        $ch = curl_init($this->baseUrl . $endpoint);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => json_encode($payload),
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'Accept: application/json',
                'Authorization: Basic ' . base64_encode($this->serverKey . ':'),
            ],
        ]);
        $result = curl_exec($ch);
        $error  = curl_error($ch);
        curl_close($ch);

        if ($error) throw new \RuntimeException($error);
        return json_decode($result, true);
    }
}
