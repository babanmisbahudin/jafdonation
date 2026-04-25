<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Comment;
use App\Models\Donation;
use App\Models\Program;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_articles'     => Article::count(),
            'published_articles' => Article::published()->count(),
            'draft_articles'     => Article::where('status', 'draft')->count(),
            'total_donations'    => Donation::count(),
            'total_collected'    => Donation::where('payment_status', 'paid')->sum('amount'),
            'count_paid'         => Donation::where('payment_status', 'paid')->count(),
            'pending_donations'  => Donation::where('payment_status', 'pending')->count(),
            'active_programs'    => Program::where('status', 'active')->count(),
            'total_users'        => User::count(),
            'total_comments'     => Comment::count(),
            'spam_comments'      => Comment::where('is_spam', true)->count(),
            'pending_comments'   => Comment::where('is_spam', false)->where('is_approved', false)->count(),
        ];

        // Donasi 30 hari terakhir (daily)
        $donationChart = Donation::where('payment_status', 'paid')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy(DB::raw('DATE(created_at)'))
            ->selectRaw('DATE(created_at) as date, SUM(amount) as total, COUNT(*) as count')
            ->orderBy('date')
            ->get();

        // Artikel per status (untuk donut chart)
        $articleByStatus = [
            'published' => $stats['published_articles'],
            'draft'     => $stats['draft_articles'],
            'archived'  => Article::where('status', 'archived')->count(),
        ];

        // Top donating programs
        $topPrograms = Program::withSum(['donations as total_raised' => fn($q) => $q->where('payment_status', 'paid')], 'amount')
            ->having('total_raised', '>', 0)
            ->orderByDesc('total_raised')
            ->take(5)
            ->get(['id', 'name', 'target_amount']);

        $recentDonations = Donation::with('program')
            ->latest()
            ->take(8)
            ->get();

        $recentArticles = Article::with(['category', 'author'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'stats', 'donationChart', 'articleByStatus',
            'topPrograms', 'recentDonations', 'recentArticles'
        ));
    }
}
