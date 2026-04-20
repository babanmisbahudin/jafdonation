<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ArticleApiController extends Controller
{
    public function index(Request $request)
    {
        $query = Article::with(['category', 'tags', 'author'])
            ->published()
            ->orderByDesc('published_at');

        if ($request->filled('category')) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->category));
        }

        if ($request->filled('tag')) {
            $query->whereHas('tags', fn($q) => $q->where('slug', $request->tag));
        }

        if ($request->filled('region')) {
            $query->where('region', $request->region);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('excerpt', 'like', '%' . $request->search . '%');
            });
        }

        $articles = $query->paginate(12)->withQueryString();

        return response()->json([
            'data'  => $articles->map(fn($a) => $this->formatArticle($a)),
            'meta'  => [
                'current_page' => $articles->currentPage(),
                'last_page'    => $articles->lastPage(),
                'total'        => $articles->total(),
            ],
        ]);
    }

    public function show(Article $article)
    {
        if ($article->status !== 'published') {
            return response()->json(['message' => 'Not found'], 404);
        }

        $article->increment('views');
        $article->load(['category', 'tags', 'author']);

        $related = Article::with(['category', 'tags'])
            ->published()
            ->where('id', '!=', $article->id)
            ->where(function ($q) use ($article) {
                if ($article->category_id) {
                    $q->where('category_id', $article->category_id);
                }
            })
            ->orderByDesc('published_at')
            ->take(3)
            ->get();

        return response()->json([
            'article' => $this->formatArticle($article, true),
            'related' => $related->map(fn($a) => $this->formatArticle($a)),
        ]);
    }

    public function byTag(Tag $tag)
    {
        $articles = Article::with(['category', 'tags'])
            ->published()
            ->whereHas('tags', fn($q) => $q->where('tags.id', $tag->id))
            ->orderByDesc('published_at')
            ->paginate(12);

        return response()->json([
            'tag'  => ['name' => $tag->name, 'slug' => $tag->slug],
            'data' => $articles->map(fn($a) => $this->formatArticle($a)),
            'meta' => [
                'current_page' => $articles->currentPage(),
                'last_page'    => $articles->lastPage(),
                'total'        => $articles->total(),
            ],
        ]);
    }

    private function formatArticle(Article $article, bool $full = false): array
    {
        $data = [
            'id'            => $article->id,
            'title'         => $article->title,
            'slug'          => $article->slug,
            'excerpt'       => $article->excerpt ?? Str::limit(strip_tags($article->content), 160),
            'thumbnail_url' => $article->thumbnail_url,
            'category'      => $article->category ? ['name' => $article->category->name, 'slug' => $article->category->slug ?? Str::slug($article->category->name)] : null,
            'tags'          => $article->tags->map(fn($t) => ['name' => $t->name, 'slug' => $t->slug]),
            'region'        => $article->region,
            'region_label'  => $article->region_label,
            'author'        => $article->author?->name,
            'views'         => $article->views,
            'published_at'  => $article->published_at?->translatedFormat('d F Y'),
            'meta'          => [
                'title'       => $article->meta_title       ?: $article->title,
                'description' => $article->meta_description ?: ($article->excerpt ?? Str::limit(strip_tags($article->content), 160)),
                'keywords'    => $article->tags->pluck('name')->implode(', '),
            ],
        ];

        if ($full) {
            $data['content'] = $article->content;
        }

        return $data;
    }
}
