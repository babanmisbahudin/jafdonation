@extends('admin.layouts.app')
@section('title', 'Data Relawan')
@section('page-title', 'Data Relawan')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h5 class="fw-bold mb-1">Data Relawan</h5>
    <p class="text-muted mb-0" style="font-size:.85rem;">Daftar pendaftar relawan dari website</p>
  </div>
  <a href="{{ route('admin.volunteers.export') }}" class="btn btn-outline-success btn-sm d-flex align-items-center gap-2" style="border-radius:8px;">
    <i class="bi bi-download"></i> Export CSV
  </a>
</div>

{{-- Stats --}}
<div class="row g-3 mb-4">
  <div class="col-6 col-md-3">
    <div class="card shadow-sm text-center p-3" style="border:none;border-radius:12px;">
      <div class="fw-bold fs-4">{{ $stats['total'] }}</div>
      <div class="text-muted" style="font-size:.8rem;">Total Pendaftar</div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="card shadow-sm text-center p-3" style="border:none;border-radius:12px;border-left:3px solid #f59e0b !important;">
      <div class="fw-bold fs-4 text-warning">{{ $stats['pending'] }}</div>
      <div class="text-muted" style="font-size:.8rem;">Menunggu Review</div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="card shadow-sm text-center p-3" style="border:none;border-radius:12px;border-left:3px solid #22c55e !important;">
      <div class="fw-bold fs-4 text-success">{{ $stats['approved'] }}</div>
      <div class="text-muted" style="font-size:.8rem;">Diterima</div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="card shadow-sm text-center p-3" style="border:none;border-radius:12px;border-left:3px solid #ef4444 !important;">
      <div class="fw-bold fs-4 text-danger">{{ $stats['rejected'] }}</div>
      <div class="text-muted" style="font-size:.8rem;">Ditolak</div>
    </div>
  </div>
</div>

{{-- Filter --}}
<div class="card shadow-sm mb-4" style="border:none;border-radius:14px;">
  <div class="card-body py-3">
    <form method="GET" class="row g-2 align-items-end">
      <div class="col-md-4">
        <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari nama / email / telepon..." value="{{ request('search') }}" style="border-radius:8px;" />
      </div>
      <div class="col-md-3">
        <select name="status" class="form-select form-select-sm" style="border-radius:8px;">
          <option value="">Semua Status</option>
          <option value="pending"  {{ request('status') === 'pending'  ? 'selected' : '' }}>Menunggu</option>
          <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Diterima</option>
          <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
        </select>
      </div>
      <div class="col-md-3">
        <select name="availability" class="form-select form-select-sm" style="border-radius:8px;">
          <option value="">Semua Ketersediaan</option>
          <option value="weekend"  {{ request('availability') === 'weekend'  ? 'selected' : '' }}>Akhir Pekan</option>
          <option value="weekday"  {{ request('availability') === 'weekday'  ? 'selected' : '' }}>Hari Kerja</option>
          <option value="fulltime" {{ request('availability') === 'fulltime' ? 'selected' : '' }}>Full Time</option>
        </select>
      </div>
      <div class="col-md-2 d-flex gap-2">
        <button type="submit" class="btn btn-primary btn-sm" style="border-radius:8px;">Filter</button>
        <a href="{{ route('admin.volunteers.index') }}" class="btn btn-outline-secondary btn-sm" style="border-radius:8px;">Reset</a>
      </div>
    </form>
  </div>
</div>

{{-- Table --}}
<div class="card shadow-sm" style="border:none;border-radius:14px;">
  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0" style="font-size:.85rem;">
      <thead style="background:#f8fafc;font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;color:#64748b;">
        <tr>
          <th class="px-4 py-3">#</th>
          <th class="py-3">Nama</th>
          <th class="py-3">Kontak</th>
          <th class="py-3">Domisili</th>
          <th class="py-3">Ketersediaan</th>
          <th class="py-3">Status</th>
          <th class="py-3">Tanggal Daftar</th>
          <th class="py-3 text-end px-4">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($volunteers as $v)
        <tr>
          <td class="px-4 text-muted">{{ $v->id }}</td>
          <td>
            <div class="fw-semibold">{{ $v->name }}</div>
            @if($v->occupation)
              <div class="text-muted" style="font-size:.75rem;">{{ $v->occupation }}</div>
            @endif
          </td>
          <td>
            <div>{{ $v->phone }}</div>
            @if($v->email)
              <div class="text-muted" style="font-size:.75rem;">{{ $v->email }}</div>
            @endif
          </td>
          <td>
            {{ $v->city }}{{ $v->city && $v->province ? ', ' : '' }}{{ $v->province }}
          </td>
          <td>
            @if($v->availability)
              <span class="badge bg-light text-dark" style="font-size:.7rem;">
                {{ \App\Models\Volunteer::AVAILABILITIES[$v->availability] ?? $v->availability }}
              </span>
            @else <span class="text-muted">—</span> @endif
          </td>
          <td>
            <span class="badge {{ $v->status_badge }} px-2 py-1" style="font-size:.72rem;border-radius:6px;">
              {{ $v->status_label }}
            </span>
          </td>
          <td class="text-muted" style="font-size:.78rem;">
            {{ $v->created_at->format('d M Y') }}<br>
            <span style="font-size:.72rem;">{{ $v->created_at->format('H:i') }}</span>
          </td>
          <td class="text-end px-4">
            <div class="d-flex gap-1 justify-content-end">
              <a href="{{ route('admin.volunteers.show', $v) }}" class="btn btn-sm btn-outline-primary" style="border-radius:6px;" title="Detail">
                <i class="bi bi-eye"></i>
              </a>
              <form action="{{ route('admin.volunteers.destroy', $v) }}" method="POST" onsubmit="return confirm('Hapus data relawan ini?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-sm btn-outline-danger" style="border-radius:6px;" title="Hapus">
                  <i class="bi bi-trash"></i>
                </button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="8" class="text-center py-5 text-muted">
            <i class="bi bi-person-x fs-1 d-block mb-2 text-light"></i>
            Belum ada pendaftar relawan.
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

@if($volunteers->hasPages())
<div class="d-flex justify-content-center mt-4">{{ $volunteers->links() }}</div>
@endif

@endsection
