<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactApiController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:100',
            'email'   => 'required|email|max:150',
            'phone'   => 'nullable|string|max:20',
            'subject' => 'required|string|max:150',
            'message' => 'required|string|max:3000',
        ]);

        ContactMessage::create($validated);

        return response()->json([
            'message' => 'Pesan Anda berhasil dikirim. Kami akan segera menghubungi Anda.',
        ], 201);
    }
}
