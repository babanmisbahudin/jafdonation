<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroSlide;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class HeroSlideController extends Controller
{
    public function index()
    {
        $slides = HeroSlide::orderBy('sort_order')->get();
        return view('admin.hero.index', compact('slides'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tag'         => 'nullable|string|max:100',
            'tag_color'   => 'nullable|string|max:20',
            'title_1'     => 'required|string|max:100',
            'title_2'     => 'required|string|max:100',
            'description' => 'nullable|string',
            'quote'       => 'nullable|string',
            'author'      => 'nullable|string|max:100',
            'bg_color'    => 'nullable|string|max:20',
            'cta_text'    => 'nullable|string|max:60',
            'cta_url'     => 'nullable|string|max:255',
            'is_active'   => 'boolean',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $name = time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/hero'), $name);
            $validated['image'] = $name;
        }

        $validated['sort_order'] = HeroSlide::max('sort_order') + 1;
        HeroSlide::create($validated);

        return redirect()->route('admin.hero.index')->with('success', 'Slide berhasil ditambahkan!');
    }

    public function update(Request $request, HeroSlide $heroSlide)
    {
        $validated = $request->validate([
            'tag'         => 'nullable|string|max:100',
            'tag_color'   => 'nullable|string|max:20',
            'title_1'     => 'required|string|max:100',
            'title_2'     => 'required|string|max:100',
            'description' => 'nullable|string',
            'quote'       => 'nullable|string',
            'author'      => 'nullable|string|max:100',
            'bg_color'    => 'nullable|string|max:20',
            'cta_text'    => 'nullable|string|max:60',
            'cta_url'     => 'nullable|string|max:255',
            'is_active'   => 'boolean',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
        ]);

        if ($request->hasFile('image')) {
            if ($heroSlide->image && file_exists(public_path('uploads/hero/' . $heroSlide->image))) {
                unlink(public_path('uploads/hero/' . $heroSlide->image));
            }
            $file = $request->file('image');
            $name = time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/hero'), $name);
            $validated['image'] = $name;
        }

        $heroSlide->update($validated);
        return redirect()->route('admin.hero.index')->with('success', 'Slide berhasil diperbarui!');
    }

    public function destroy(HeroSlide $heroSlide)
    {
        if ($heroSlide->image && file_exists(public_path('uploads/hero/' . $heroSlide->image))) {
            unlink(public_path('uploads/hero/' . $heroSlide->image));
        }
        $heroSlide->delete();
        return redirect()->route('admin.hero.index')->with('success', 'Slide berhasil dihapus!');
    }

    public function reorder(Request $request)
    {
        foreach ($request->order as $id => $order) {
            HeroSlide::where('id', $id)->update(['sort_order' => $order]);
        }
        return response()->json(['success' => true]);
    }

    public function toggleActive(HeroSlide $heroSlide)
    {
        $heroSlide->update(['is_active' => !$heroSlide->is_active]);
        return redirect()->route('admin.hero.index')
            ->with('success', 'Status slide diperbarui!');
    }
}
