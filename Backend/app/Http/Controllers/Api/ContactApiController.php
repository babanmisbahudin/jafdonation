<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\ContactNotificationMail;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

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

        $contact = ContactMessage::create($validated);

        try {
            $to = env('CONTACT_NOTIFY_EMAIL', 'jatiwangiartfactory@gmail.com');
            Mail::to($to)->send(new ContactNotificationMail($contact));
        } catch (\Exception $e) {
            // Mail failure doesn't affect the response
        }

        return response()->json([
            'message' => 'Pesan Anda berhasil dikirim. Kami akan segera menghubungi Anda.',
        ], 201);
    }
}
