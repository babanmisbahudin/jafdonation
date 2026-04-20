<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Volunteer;
use Illuminate\Http\Request;

class VolunteerApiController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'nullable|email|max:255',
            'phone'        => 'required|string|max:20',
            'birth_date'   => 'nullable|date',
            'gender'       => 'nullable|in:male,female',
            'city'         => 'nullable|string|max:100',
            'province'     => 'nullable|string|max:100',
            'occupation'   => 'nullable|string|max:100',
            'availability' => 'nullable|in:weekend,weekday,fulltime',
            'skills'       => 'nullable|array',
            'skills.*'     => 'string|max:50',
            'motivation'   => 'nullable|string|max:1000',
        ]);

        $volunteer = Volunteer::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Pendaftaran relawan berhasil! Kami akan menghubungi Anda segera.',
            'data'    => ['id' => $volunteer->id, 'name' => $volunteer->name],
        ], 201);
    }
}
