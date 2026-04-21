<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;

class GalleryApiController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->get('type', 'image');

        $query = Gallery::where('file_type', $type)
            ->orderByDesc('created_at');

        $items = $query->paginate(12)->withQueryString();

        return response()->json([
            'data' => $items->map(fn($g) => [
                'id'          => $g->id,
                'title'       => $g->title,
                'description' => $g->description,
                'file_url'    => asset('uploads/gallery/' . $g->file_path),
                'file_path'   => $g->file_path,
                'file_type'   => $g->file_type,
                'thumbnail'   => $g->thumbnail ? asset('uploads/gallery/' . $g->thumbnail) : null,
                'region'      => $g->region,
                'created_at'  => $g->created_at->translatedFormat('d F Y'),
            ]),
            'meta' => [
                'current_page' => $items->currentPage(),
                'last_page'    => $items->lastPage(),
                'total'        => $items->total(),
            ],
        ]);
    }
}
