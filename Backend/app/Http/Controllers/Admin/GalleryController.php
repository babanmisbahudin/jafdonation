<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        $query = Gallery::with('category')->latest();

        if ($request->filled('type')) {
            $query->where('file_type', $request->type);
        }
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $galleries  = $query->paginate(20)->withQueryString();
        $categories = Category::where('is_active', true)->get();

        return view('admin.gallery.index', compact('galleries', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'file_type'   => 'required|in:image,video',
            'category_id' => 'nullable|exists:categories,id',
            'region'      => 'nullable|string',
            'is_featured' => 'boolean',
            'file'        => 'required_if:file_type,image|nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'video_url'   => 'required_if:file_type,video|nullable|url',
        ]);

        if ($request->hasFile('file') && $validated['file_type'] === 'image') {
            $file = $request->file('file');
            $name = time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/gallery'), $name);
            $validated['file_path'] = 'gallery/' . $name;
        } elseif ($validated['file_type'] === 'video') {
            $validated['file_path'] = $request->video_url;
        }

        unset($validated['file'], $validated['video_url']);
        Gallery::create($validated);

        return redirect()->route('admin.gallery.index')
            ->with('success', 'Media berhasil ditambahkan!');
    }

    public function destroy(Gallery $gallery)
    {
        if ($gallery->file_type === 'image' && file_exists(public_path('uploads/' . $gallery->file_path))) {
            unlink(public_path('uploads/' . $gallery->file_path));
        }
        $gallery->delete();
        return redirect()->route('admin.gallery.index')
            ->with('success', 'Media berhasil dihapus!');
    }

    public function updateOrder(Request $request)
    {
        foreach ($request->order as $id => $order) {
            Gallery::where('id', $id)->update(['sort_order' => $order]);
        }
        return response()->json(['success' => true]);
    }
}
