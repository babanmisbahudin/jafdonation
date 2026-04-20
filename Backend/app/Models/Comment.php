<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'article_id', 'guest_name', 'guest_email', 'content',
        'ip_address', 'is_approved', 'is_spam', 'spam_score', 'spam_reasons',
    ];

    protected $casts = [
        'is_approved'  => 'boolean',
        'is_spam'      => 'boolean',
        'spam_reasons' => 'array',
    ];

    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    public static function detectSpam(string $content, string $ip, ?string $email = null): array
    {
        $score   = 0;
        $reasons = [];

        // Too many URLs
        $linkCount = preg_match_all('/https?:\/\//i', $content);
        if ($linkCount >= 3) { $score += 40; $reasons[] = 'Terlalu banyak link (' . $linkCount . ')'; }
        elseif ($linkCount >= 1) { $score += 15; $reasons[] = 'Mengandung link'; }

        // Spam keywords
        $spamWords = ['casino', 'poker', 'slot', 'togel', 'judi', 'pinjaman', 'kredit cepat',
                      'viagra', 'cialis', 'bitcoin invest', 'free money', 'click here'];
        foreach ($spamWords as $word) {
            if (stripos($content, $word) !== false) {
                $score += 30; $reasons[] = 'Kata terlarang: ' . $word; break;
            }
        }

        // All caps (shouting)
        $upperRatio = strlen(preg_replace('/[^A-Z]/', '', $content)) / max(1, strlen(preg_replace('/[^a-zA-Z]/', '', $content)));
        if ($upperRatio > 0.7 && strlen($content) > 10) { $score += 20; $reasons[] = 'Terlalu banyak huruf kapital'; }

        // Very short or very repetitive
        if (strlen(trim($content)) < 5) { $score += 50; $reasons[] = 'Konten terlalu pendek'; }

        // Recent duplicate IP (last 10 min)
        $recentCount = static::where('ip_address', $ip)
            ->where('created_at', '>=', now()->subMinutes(10))
            ->count();
        if ($recentCount >= 5) { $score += 50; $reasons[] = 'Terlalu banyak komentar dari IP yang sama'; }
        elseif ($recentCount >= 2) { $score += 20; $reasons[] = 'Banyak komentar dari IP yang sama'; }

        return [
            'score'     => min(100, $score),
            'reasons'   => $reasons,
            'is_spam'   => $score >= 60,
        ];
    }
}
