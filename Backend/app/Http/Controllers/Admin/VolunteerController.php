<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Volunteer;
use Illuminate\Http\Request;

class VolunteerController extends Controller
{
    public function index(Request $request)
    {
        $query = Volunteer::latest();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('availability')) {
            $query->where('availability', $request->availability);
        }

        $volunteers = $query->paginate(20)->withQueryString();

        $stats = [
            'total'    => Volunteer::count(),
            'pending'  => Volunteer::where('status', 'pending')->count(),
            'approved' => Volunteer::where('status', 'approved')->count(),
            'rejected' => Volunteer::where('status', 'rejected')->count(),
        ];

        return view('admin.volunteers.index', compact('volunteers', 'stats'));
    }

    public function show(Volunteer $volunteer)
    {
        return view('admin.volunteers.show', compact('volunteer'));
    }

    public function updateStatus(Request $request, Volunteer $volunteer)
    {
        $request->validate([
            'status'      => 'required|in:pending,approved,rejected',
            'admin_notes' => 'nullable|string|max:500',
        ]);

        $volunteer->update([
            'status'      => $request->status,
            'admin_notes' => $request->admin_notes,
            'approved_at' => $request->status === 'approved' ? now() : null,
        ]);

        return redirect()->back()->with('success', 'Status relawan berhasil diperbarui!');
    }

    public function destroy(Volunteer $volunteer)
    {
        $volunteer->delete();
        return redirect()->route('admin.volunteers.index')
            ->with('success', 'Data relawan berhasil dihapus!');
    }

    public function export()
    {
        $volunteers = Volunteer::orderBy('created_at', 'desc')->get();

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="relawan_' . date('Y-m-d') . '.csv"',
        ];

        $callback = function () use ($volunteers) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ID','Nama','Email','Telepon','Kota','Provinsi','Gender','Pekerjaan','Ketersediaan','Status','Tanggal Daftar']);

            foreach ($volunteers as $v) {
                fputcsv($handle, [
                    $v->id,
                    $v->name,
                    $v->email,
                    $v->phone,
                    $v->city,
                    $v->province,
                    $v->gender_label,
                    $v->occupation,
                    Volunteer::AVAILABILITIES[$v->availability] ?? $v->availability,
                    $v->status_label,
                    $v->created_at->format('Y-m-d H:i'),
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
