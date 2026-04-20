<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\Program;
use Illuminate\Http\Request;

class DonationController extends Controller
{
    public function index(Request $request)
    {
        $query = Donation::with('program')->latest();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('donor_name', 'like', '%' . $request->search . '%')
                  ->orWhere('donor_email', 'like', '%' . $request->search . '%')
                  ->orWhere('order_id', 'like', '%' . $request->search . '%');
            });
        }
        if ($request->filled('status')) {
            $query->where('payment_status', $request->status);
        }
        if ($request->filled('program')) {
            $query->where('program_id', $request->program);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $donations = $query->paginate(20)->withQueryString();
        $programs  = Program::where('status', 'active')->get();

        // Ringkasan
        $summary = [
            'total'        => Donation::sum('amount'),
            'paid'         => Donation::where('payment_status', 'paid')->sum('amount'),
            'pending'      => Donation::where('payment_status', 'pending')->count(),
            'count_paid'   => Donation::where('payment_status', 'paid')->count(),
        ];

        return view('admin.donations.index', compact('donations', 'programs', 'summary'));
    }

    public function show(Donation $donation)
    {
        $donation->load('program');
        return view('admin.donations.show', compact('donation'));
    }

    public function updateStatus(Request $request, Donation $donation)
    {
        $validated = $request->validate([
            'payment_status' => 'required|in:pending,paid,failed,expired,refund',
        ]);

        if ($validated['payment_status'] === 'paid' && $donation->payment_status !== 'paid') {
            $validated['paid_at'] = now();
            if ($donation->program_id) {
                $donation->program->increment('collected_amount', $donation->amount);
            }
        }

        $donation->update($validated);
        return back()->with('success', 'Status donasi berhasil diperbarui!');
    }

    public function export(Request $request)
    {
        $donations = Donation::with('program')
            ->when($request->status, fn($q) => $q->where('payment_status', $request->status))
            ->when($request->date_from, fn($q) => $q->whereDate('created_at', '>=', $request->date_from))
            ->when($request->date_to, fn($q) => $q->whereDate('created_at', '<=', $request->date_to))
            ->get();

        $filename = 'donasi-' . now()->format('Y-m-d') . '.csv';
        $headers  = ['Content-Type' => 'text/csv', 'Content-Disposition' => "attachment; filename=$filename"];

        $callback = function () use ($donations) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Order ID', 'Nama', 'Email', 'Telepon', 'Program', 'Nominal', 'Metode', 'Status', 'Tanggal']);
            foreach ($donations as $d) {
                fputcsv($file, [
                    $d->order_id, $d->donor_name, $d->donor_email, $d->donor_phone,
                    $d->program?->name ?? 'Umum', 'Rp ' . number_format($d->amount, 0, ',', '.'),
                    $d->payment_method, $d->status_label, $d->created_at->format('d/m/Y H:i'),
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
