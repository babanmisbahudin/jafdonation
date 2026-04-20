<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ImageService;
use Illuminate\Http\Request;

class ImageUploadController extends Controller
{
    public function store(Request $request, ImageService $imageService)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png,webp,gif,bmp|max:5120',
        ]);

        $path = $imageService->processAndSave(
            $request->file('image'),
            'articles/inline',
            1200,
            82
        );

        return response()->json([
            'success' => true,
            'url'     => asset('uploads/' . $path),
        ]);
    }
}
