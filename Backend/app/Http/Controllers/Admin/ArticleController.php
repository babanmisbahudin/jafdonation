<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $query = Article::with(['category', 'author', 'tags', 'comments'])->latest();

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('region')) {
            $query->where('region', $request->region);
        }

        $articles   = $query->paginate(15)->withQueryString();
        $categories = Category::active()->get();

        return view('admin.articles.index', compact('articles', 'categories'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        $regions    = Article::REGIONS;
        $statuses   = Article::STATUSES;
        $popularTags = Tag::withCount('articles')->orderByDesc('articles_count')->limit(20)->get();
        return view('admin.articles.create', compact('categories', 'regions', 'statuses', 'popularTags'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'excerpt'          => 'nullable|string|max:500',
            'content'          => 'required|string',
            'category_id'      => 'nullable|exists:categories,id',
            'region'           => 'nullable|string',
            'status'           => 'required|in:draft,published,archived',
            'is_featured'      => 'boolean',
            'thumbnail'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'published_at'     => 'nullable|date',
        ]);

        $validated['slug']      = Str::slug($validated['title']);
        $validated['author_id'] = auth()->id();

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = app(ImageService::class)
                ->processAndSave($request->file('thumbnail'), 'articles', 1200, 82);
        }

        if ($validated['status'] === 'published' && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        $article = Article::create($validated);

        $tagNames = array_filter(array_map('trim', explode(',', $request->input('tags', ''))));
        if ($tagNames) {
            $tags = Tag::findOrCreateByNames($tagNames);
            $article->tags()->sync($tags->pluck('id'));
        }

        return redirect()->route('admin.articles.index')
            ->with('success', 'Artikel berhasil dibuat!');
    }

    public function edit(Article $article)
    {
        $categories  = Category::where('is_active', true)->get();
        $regions     = Article::REGIONS;
        $statuses    = Article::STATUSES;
        $popularTags = Tag::withCount('articles')->orderByDesc('articles_count')->limit(20)->get();
        $article->load('tags');
        return view('admin.articles.edit', compact('article', 'categories', 'regions', 'statuses', 'popularTags'));
    }

    public function update(Request $request, Article $article)
    {
        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'excerpt'          => 'nullable|string|max:500',
            'content'          => 'required|string',
            'category_id'      => 'nullable|exists:categories,id',
            'region'           => 'nullable|string',
            'status'           => 'required|in:draft,published,archived',
            'is_featured'      => 'boolean',
            'thumbnail'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'published_at'     => 'nullable|date',
        ]);

        if ($request->hasFile('thumbnail')) {
            if ($article->thumbnail && file_exists(public_path('uploads/' . $article->thumbnail))) {
                @unlink(public_path('uploads/' . $article->thumbnail));
            }
            $validated['thumbnail'] = app(ImageService::class)
                ->processAndSave($request->file('thumbnail'), 'articles', 1200, 82);
        }

        if ($validated['status'] === 'published' && !$article->published_at) {
            $validated['published_at'] = now();
        }

        $article->update($validated);

        $tagNames = array_filter(array_map('trim', explode(',', $request->input('tags', ''))));
        $tags = $tagNames ? Tag::findOrCreateByNames($tagNames) : collect();
        $article->tags()->sync($tags->pluck('id'));

        return redirect()->route('admin.articles.index')
            ->with('success', 'Artikel berhasil diperbarui!');
    }

    public function destroy(Article $article)
    {
        $article->delete();
        return redirect()->route('admin.articles.index')
            ->with('success', 'Artikel berhasil dihapus!');
    }

    public function toggleFeatured(Article $article)
    {
        $article->update(['is_featured' => !$article->is_featured]);
        return back()->with('success', 'Status unggulan diperbarui!');
    }
}
