<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Program;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProgramController extends Controller
{
    public function index(Request $request)
    {
        $query = Program::with('category')->latest();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $programs   = $query->paginate(15)->withQueryString();
        $categories = Category::where('is_active', true)->get();

        return view('admin.programs.index', compact('programs', 'categories'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.programs.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $hasTarget = $request->input('has_target', '0') === '1';

        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'description'   => 'nullable|string',
            'content'       => 'nullable|string',
            'category_id'   => 'nullable|exists:categories,id',
            'target_amount' => $hasTarget ? 'required|numeric|min:100000' : 'nullable',
            'region'        => 'nullable|string',
            'status'        => 'required|in:active,completed,paused',
            'is_featured'   => 'boolean',
            'start_date'    => 'nullable|date',
            'end_date'      => 'nullable|date|after_or_equal:start_date',
            'thumbnail'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if (!$hasTarget) $validated['target_amount'] = 0;
        $validated['slug'] = Str::slug($validated['name']);

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = app(ImageService::class)
                ->processAndSave($request->file('thumbnail'), 'programs', 1200, 82);
        }

        Program::create($validated);
        return redirect()->route('admin.programs.index')
            ->with('success', 'Kampanye berhasil dibuat dan akan segera tampil di halaman publik!');
    }

    public function show(Program $program)
    {
        $donations = $program->donations()->with('program')->latest()->paginate(15);

        $stats = [
            'total_raised' => $program->donations()->where('payment_status', 'paid')->sum('amount'),
            'donor_count'  => $program->donations()->where('payment_status', 'paid')->count(),
            'pending'      => $program->donations()->where('payment_status', 'pending')->count(),
            'failed'       => $program->donations()->whereIn('payment_status', ['failed', 'expired'])->count(),
        ];

        return view('admin.programs.show', compact('program', 'donations', 'stats'));
    }

    public function edit(Program $program)
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.programs.edit', compact('program', 'categories'));
    }

    public function update(Request $request, Program $program)
    {
        $hasTarget = $request->input('has_target', '0') === '1';

        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'description'   => 'nullable|string',
            'content'       => 'nullable|string',
            'category_id'   => 'nullable|exists:categories,id',
            'target_amount' => $hasTarget ? 'required|numeric|min:100000' : 'nullable',
            'region'        => 'nullable|string',
            'status'        => 'required|in:active,completed,paused',
            'is_featured'   => 'boolean',
            'start_date'    => 'nullable|date',
            'end_date'      => 'nullable|date|after_or_equal:start_date',
            'thumbnail'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if (!$hasTarget) $validated['target_amount'] = 0;

        if ($request->hasFile('thumbnail')) {
            if ($program->thumbnail && file_exists(public_path('uploads/' . $program->thumbnail))) {
                @unlink(public_path('uploads/' . $program->thumbnail));
            }
            $validated['thumbnail'] = app(ImageService::class)
                ->processAndSave($request->file('thumbnail'), 'programs', 1200, 82);
        }

        $program->update($validated);
        return redirect()->route('admin.programs.index')
            ->with('success', 'Program berhasil diperbarui!');
    }

    public function destroy(Program $program)
    {
        $program->delete();
        return redirect()->route('admin.programs.index')
            ->with('success', 'Program berhasil dihapus!');
    }
}
