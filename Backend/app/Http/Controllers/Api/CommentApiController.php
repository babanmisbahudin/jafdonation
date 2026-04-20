<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentApiController extends Controller
{
    public function store(Request $request, Article $article)
    {
        $validated = $request->validate([
            'guest_name'  => 'required|string|max:100',
            'guest_email' => 'nullable|email|max:150',
            'content'     => 'required|string|max:2000',
        ]);

        $ip     = $request->ip();
        $spam   = Comment::detectSpam($validated['content'], $ip, $validated['guest_email'] ?? null);

        $comment = Comment::create([
            ...$validated,
            'article_id'   => $article->id,
            'ip_address'   => $ip,
            'is_spam'      => $spam['is_spam'],
            'spam_score'   => $spam['score'],
            'spam_reasons' => $spam['reasons'] ?: null,
            'is_approved'  => !$spam['is_spam'], // auto-approve if not spam
        ]);

        if ($spam['is_spam']) {
            return response()->json([
                'success' => false,
                'message' => 'Komentar Anda terdeteksi sebagai spam dan tidak ditampilkan.',
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'Komentar berhasil dikirim dan menunggu moderasi.',
            'data'    => [
                'id'         => $comment->id,
                'guest_name' => $comment->guest_name,
                'created_at' => $comment->created_at->format('d M Y H:i'),
            ],
        ], 201);
    }

    public function index(Article $article)
    {
        $comments = $article->comments()
            ->where('is_approved', true)
            ->where('is_spam', false)
            ->latest()
            ->get(['id', 'guest_name', 'content', 'created_at']);

        return response()->json(['data' => $comments]);
    }
}
