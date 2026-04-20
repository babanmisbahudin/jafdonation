<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Program;

class CampaignController extends Controller
{
    public function index()
    {
        $campaigns = Program::where('status', 'active')
            ->withCount(['donations as donor_count' => fn($q) => $q->where('payment_status', 'paid')])
            ->orderByDesc('is_featured')
            ->orderBy('created_at')
            ->get()
            ->map(fn($p) => $this->formatCampaign($p));

        return response()->json(['data' => $campaigns]);
    }

    public function show(Program $program)
    {
        if ($program->status !== 'active') {
            return response()->json(['message' => 'Kampanye tidak ditemukan atau sudah selesai'], 404);
        }

        $donorCount = $program->donations()->where('payment_status', 'paid')->count();

        $recentDonors = $program->donations()
            ->where('payment_status', 'paid')
            ->latest('paid_at')
            ->take(5)
            ->get()
            ->map(fn($d) => [
                'name'   => $d->is_anonymous ? 'Donatur Anonim' : $d->donor_name,
                'amount' => (float) $d->amount,
                'note'   => $d->note,
                'time'   => $d->paid_at?->diffForHumans() ?? '',
            ]);

        return response()->json([
            'data' => array_merge($this->formatCampaign($program, $donorCount), [
                'content'       => $program->content,
                'start_date'    => $program->start_date?->format('d M Y'),
                'end_date'      => $program->end_date?->format('d M Y'),
                'recent_donors' => $recentDonors,
            ]),
        ]);
    }

    public function config()
    {
        return response()->json([
            'midtrans_client_key' => config('midtrans.client_key', ''),
            'midtrans_env'        => config('midtrans.is_production', false) ? 'production' : 'sandbox',
            'snap_js_url'         => config('midtrans.is_production', false)
                ? 'https://app.midtrans.com/snap/snap.js'
                : 'https://app.sandbox.midtrans.com/snap/snap.js',
        ]);
    }

    private function formatCampaign(Program $p, ?int $donorCount = null): array
    {
        return [
            'id'                  => $p->id,
            'name'                => $p->name,
            'slug'                => $p->slug,
            'description'         => $p->description,
            'thumbnail_url'       => $p->thumbnail_url,
            'has_target'          => $p->target_amount > 0,
            'target_amount'       => (float) $p->target_amount,
            'collected_amount'    => (float) $p->collected_amount,
            'progress_percentage' => $p->progress_percentage,
            'donor_count'         => $donorCount ?? ($p->donor_count ?? 0),
            'is_featured'         => (bool) $p->is_featured,
            'end_date'            => $p->end_date?->format('Y-m-d'),
        ];
    }
}
