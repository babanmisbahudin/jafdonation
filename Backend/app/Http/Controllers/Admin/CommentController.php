<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index(Request $request)
    {
        $query = Comment::with('article:id,title,slug')->latest();

        // Filter per artikel
        $currentArticle = null;
        if ($request->filled('article_id')) {
            $query->where('article_id', $request->article_id);
            $currentArticle = Article::select('id', 'title')->find($request->article_id);
        }

        if ($request->filled('filter')) {
            match ($request->filter) {
                'spam'     => $query->where('is_spam', true),
                'pending'  => $query->where('is_spam', false)->where('is_approved', false),
                'approved' => $query->where('is_approved', true)->where('is_spam', false),
                default    => null,
            };
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('guest_name', 'like', '%' . $request->search . '%')
                  ->orWhere('content', 'like', '%' . $request->search . '%');
            });
        }

        $comments = $query->paginate(20)->withQueryString();

        $baseQuery = $request->filled('article_id')
            ? Comment::where('article_id', $request->article_id)
            : Comment::query();

        $stats = [
            'total'    => (clone $baseQuery)->count(),
            'spam'     => (clone $baseQuery)->where('is_spam', true)->count(),
            'pending'  => (clone $baseQuery)->where('is_spam', false)->where('is_approved', false)->count(),
            'approved' => (clone $baseQuery)->where('is_approved', true)->where('is_spam', false)->count(),
        ];

        $articles = Article::select('id', 'title')->orderByDesc('created_at')->get();

        return view('admin.comments.index', compact('comments', 'stats', 'articles', 'currentArticle'));
    }

    public function unspam(Comment $comment)
    {
        $comment->update(['is_spam' => false, 'is_approved' => false, 'spam_score' => 0]);
        return redirect()->back()->with('success', 'Komentar dipulihkan dari spam.');
    }

    public function approve(Comment $comment)
    {
        $comment->update(['is_approved' => true, 'is_spam' => false]);
        return redirect()->back()->with('success', 'Komentar disetujui.');
    }

    public function markSpam(Comment $comment)
    {
        $comment->update(['is_spam' => true, 'is_approved' => false]);
        return redirect()->back()->with('success', 'Komentar ditandai spam.');
    }

    public function destroy(Comment $comment)
    {
        $comment->delete();
        return redirect()->back()->with('success', 'Komentar dihapus.');
    }

    public function destroySpam()
    {
        Comment::where('is_spam', true)->delete();
        return redirect()->back()->with('success', 'Semua komentar spam berhasil dihapus.');
    }
}
