<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroSlide;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class SettingController extends Controller
{
    public function index()
    {
        $settings   = Setting::all()->groupBy('group')->map(fn($g) => $g->keyBy('key'));
        $heroSlides = HeroSlide::orderBy('sort_order')->get();
        return view('admin.settings.index', compact('settings', 'heroSlides'));
    }

    public function update(Request $request)
    {
        foreach ($request->except(['_token', '_method']) as $key => $value) {
            $setting = Setting::where('key', $key)->first();
            if (!$setting) continue;

            if ($setting->type === 'image' && $request->hasFile($key)) {
                $file  = $request->file($key);
                $name  = time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
                $dir   = public_path('uploads/settings');
                if (!is_dir($dir)) mkdir($dir, 0755, true);

                // delete old
                if ($setting->value && file_exists(public_path('uploads/settings/' . $setting->value))) {
                    unlink(public_path('uploads/settings/' . $setting->value));
                }
                $file->move($dir, $name);
                $value = $name;
            } elseif ($setting->type === 'image') {
                continue; // no new file uploaded, skip
            }

            $setting->update(['value' => $value]);
            Cache::forget("setting_{$key}");
        }

        return redirect()->route('admin.settings.index')->with('success', 'Pengaturan berhasil disimpan!');
    }
}
