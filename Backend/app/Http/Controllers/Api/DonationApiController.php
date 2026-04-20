<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\Program;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class DonationApiController extends Controller
{
    public function __construct(protected MidtransService $midtrans) {}

    /**
     * Buat donasi baru & dapatkan Snap Token Midtrans
     */
    public function create(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'donor_name'  => 'required|string|max:255',
            'donor_email' => 'required|email',
            'donor_phone' => 'nullable|string|max:20',
            'program_id'  => 'nullable|exists:programs,id',
            'amount'      => 'required|numeric|min:10000',
            'note'        => 'nullable|string|max:500',
            'is_anonymous'=> 'boolean',
        ]);

        $donation = Donation::create([
            'order_id'      => Donation::generateOrderId(),
            'donor_name'    => $validated['donor_name'],
            'donor_email'   => $validated['donor_email'],
            'donor_phone'   => $validated['donor_phone'] ?? null,
            'program_id'    => $validated['program_id'] ?? null,
            'amount'        => $validated['amount'],
            'payment_status'=> 'pending',
            'note'          => $validated['note'] ?? null,
            'is_anonymous'  => $validated['is_anonymous'] ?? false,
        ]);

        try {
            $snapData = $this->midtrans->createSnapToken($donation);

            $donation->update([
                'midtrans_token'        => $snapData['token'],
                'midtrans_redirect_url' => $snapData['redirect_url'],
            ]);

            return response()->json([
                'success'      => true,
                'order_id'     => $donation->order_id,
                'snap_token'   => $snapData['token'],
                'redirect_url' => $snapData['redirect_url'],
                'is_dummy'     => $snapData['dummy'] ?? false,
                'message'      => $snapData['dummy']
                    ? 'Mode sandbox: Server key Midtrans belum dikonfigurasi.'
                    : 'Token pembayaran berhasil dibuat.',
            ]);
        } catch (\Exception $e) {
            $donation->update(['payment_status' => 'failed']);
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Webhook callback dari Midtrans
     */
    public function callback(Request $request): JsonResponse
    {
        $payload = $request->all();

        if (!$this->midtrans->verifySignature($payload)) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $donation = Donation::where('order_id', $payload['order_id'] ?? '')->first();
        if (!$donation) {
            return response()->json(['message' => 'Donation not found'], 404);
        }

        $newStatus = $this->midtrans->resolvePaymentStatus(
            $payload['transaction_status'] ?? '',
            $payload['fraud_status'] ?? ''
        );

        $updateData = [
            'payment_status'          => $newStatus,
            'payment_method'          => $payload['payment_type'] ?? null,
            'midtrans_transaction_id' => $payload['transaction_id'] ?? null,
            'midtrans_response'       => $payload,
        ];

        if ($newStatus === 'paid') {
            $updateData['paid_at'] = now();
            if ($donation->program_id && $donation->payment_status !== 'paid') {
                $donation->program->increment('collected_amount', $donation->amount);
            }
        }

        $donation->update($updateData);
        return response()->json(['success' => true]);
    }

    /**
     * Cek status donasi berdasarkan order_id
     */
    public function status(string $orderId): JsonResponse
    {
        $donation = Donation::where('order_id', $orderId)->firstOrFail();
        return response()->json([
            'order_id'       => $donation->order_id,
            'status'         => $donation->payment_status,
            'status_label'   => $donation->status_label,
            'amount'         => $donation->amount,
            'paid_at'        => $donation->paid_at?->toIso8601String(),
        ]);
    }
}
