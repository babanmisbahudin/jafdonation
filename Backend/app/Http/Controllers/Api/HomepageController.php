<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Gallery;
use App\Models\HeroSlide;
use App\Models\Program;
use App\Models\Setting;

class HomepageController extends Controller
{
    public function __invoke()
    {
        return response()->json([
            'hero_slides' => HeroSlide::active()->get()->map(fn($s) => [
                'id'          => $s->id,
                'tag'         => $s->tag,
                'tag_color'   => $s->tag_color,
                'title_1'     => $s->title_1,
                'title_2'     => $s->title_2,
                'description' => $s->description,
                'quote'       => $s->quote,
                'author'      => $s->author,
                'bg_color'    => $s->bg_color,
                'image_url'   => $s->image_url,
                'cta_text'    => $s->cta_text,
                'cta_url'     => $s->cta_url,
            ]),

            'settings' => Setting::all()->mapWithKeys(fn($s) => [
                $s->key => $s->type === 'image' && $s->value
                    ? asset('uploads/settings/' . $s->value)
                    : $s->value,
            ]),

            'programs' => Program::with('category')
                ->where('status', 'active')
                ->where('is_featured', true)
                ->orderByDesc('created_at')
                ->take(4)
                ->get()
                ->map(fn($p) => [
                    'id'                 => $p->id,
                    'name'               => $p->name,
                    'description'        => $p->description,
                    'thumbnail_url'      => $p->thumbnail ? asset('uploads/' . $p->thumbnail) : null,
                    'progress_percentage'=> $p->progress_percentage,
                    'collected_amount'   => $p->collected_amount,
                    'target_amount'      => $p->target_amount,
                    'category'           => $p->category?->name,
                    'cta_text'           => $p->cta_text ?: 'Dukung',
                    'cta_url'            => $p->cta_url ?: 'pages/donasi.html',
                ]),

            'latest_articles' => Article::with(['category', 'tags'])
                ->published()
                ->orderByDesc('published_at')
                ->take(3)
                ->get()
                ->map(fn($a) => [
                    'id'            => $a->id,
                    'title'         => $a->title,
                    'slug'          => $a->slug,
                    'excerpt'       => $a->excerpt ?? \Illuminate\Support\Str::limit(strip_tags($a->content), 120),
                    'thumbnail_url' => $a->thumbnail_url,
                    'category'      => $a->category?->name,
                    'tags'          => $a->tags->pluck('name'),
                    'published_at'  => $a->published_at?->translatedFormat('d F Y'),
                ]),

            'latest_videos' => Gallery::where('file_type', 'video')
                ->orderByDesc('created_at')
                ->take(3)
                ->get()
                ->map(fn($g) => [
                    'id'         => $g->id,
                    'title'      => $g->title,
                    'file_path'  => $g->file_path,
                    'created_at' => $g->created_at->translatedFormat('Y-m-d H:i:s'),
                ]),

            'latest_photos' => Gallery::where('file_type', 'image')
                ->orderByDesc('created_at')
                ->take(3)
                ->get()
                ->map(fn($g) => [
                    'id'         => $g->id,
                    'title'      => $g->title,
                    'file_url'   => asset('uploads/gallery/' . $g->file_path),
                    'created_at' => $g->created_at->translatedFormat('d F Y'),
                ]),
        ]);
    }
}
