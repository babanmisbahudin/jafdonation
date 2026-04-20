<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount(['articles', 'programs'])->latest()->paginate(20);
        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'color'       => 'nullable|string|max:7',
            'icon'        => 'nullable|string|max:100',
            'is_active'   => 'boolean',
        ]);

        Category::create($validated);
        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'color'       => 'nullable|string|max:7',
            'icon'        => 'nullable|string|max:100',
            'is_active'   => 'boolean',
        ]);

        $category->update($validated);
        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil diperbarui!');
    }

    public function destroy(Category $category)
    {
        if ($category->articles()->count() > 0 || $category->programs()->count() > 0) {
            return back()->with('error', 'Kategori tidak dapat dihapus karena masih memiliki artikel atau program.');
        }
        $category->delete();
        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil dihapus!');
    }
}
