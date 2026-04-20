@extends('admin.layouts.app')
@section('title', 'Detail Relawan')
@section('page-title', 'Detail Relawan')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
  <h5 class="fw-bold mb-0">Detail Relawan</h5>
  <a href="{{ route('admin.volunteers.index') }}" class="btn btn-outline-secondary btn-sm" style="border-radius:8px;">
    <i class="bi bi-arrow-left me-1"></i>Kembali
  </a>
</div>

<div class="row g-4">
  <div class="col-lg-8">
    <div class="card shadow-sm" style="border:none;border-radius:14px;">
      <div class="card-header bg-white px-4 py-3 border-bottom">
        <h6 class="mb-0 fw-bold"><i class="bi bi-person-fill me-2 text-primary"></i>Data Pribadi</h6>
      </div>
      <div class="card-body p-4">
        <div class="row g-3" style="font-size:.875rem;">
          <div class="col-md-6">
            <div class="text-muted mb-1" style="font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;">Nama Lengkap</div>
            <div class="fw-semibold">{{ $volunteer->name }}</div>
          </div>
          <div class="col-md-6">
            <div class="text-muted mb-1" style="font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;">Jenis Kelamin</div>
            <div>{{ $volunteer->gender_label }}</div>
          </div>
          <div class="col-md-6">
            <div class="text-muted mb-1" style="font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;">Email</div>
            <div>{{ $volunteer->email ?: '—' }}</div>
          </div>
          <div class="col-md-6">
            <div class="text-muted mb-1" style="font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;">Nomor Telepon / WA</div>
            <div>
              {{ $volunteer->phone }}
              <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $volunteer->phone) }}" target="_blank" class="ms-2 text-success" style="font-size:.75rem;">
                <i class="bi bi-whatsapp"></i> Chat
              </a>
            </div>
          </div>
          <div class="col-md-6">
            <div class="text-muted mb-1" style="font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;">Tanggal Lahir</div>
            <div>{{ $volunteer->birth_date?->format('d M Y') ?: '—' }}</div>
          </div>
          <div class="col-md-6">
            <div class="text-muted mb-1" style="font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;">Pekerjaan</div>
            <div>{{ $volunteer->occupation ?: '—' }}</div>
          </div>
          <div class="col-md-6">
            <div class="text-muted mb-1" style="font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;">Kota</div>
            <div>{{ $volunteer->city ?: '—' }}</div>
          </div>
          <div class="col-md-6">
            <div class="text-muted mb-1" style="font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;">Provinsi</div>
            <div>{{ $volunteer->province ?: '—' }}</div>
          </div>
          <div class="col-md-6">
            <div class="text-muted mb-1" style="font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;">Ketersediaan</div>
            <div>{{ \App\Models\Volunteer::AVAILABILITIES[$volunteer->availability] ?? '—' }}</div>
          </div>
          <div class="col-md-6">
            <div class="text-muted mb-1" style="font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;">Tanggal Daftar</div>
            <div>{{ $volunteer->created_at->format('d M Y, H:i') }}</div>
          </div>
          @if($volunteer->skills && count($volunteer->skills))
          <div class="col-12">
            <div class="text-muted mb-1" style="font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;">Keahlian / Skill</div>
            <div class="d-flex gap-2 flex-wrap">
              @foreach($volunteer->skills as $skill)
                <span class="badge bg-primary-subtle text-primary" style="font-size:.75rem;border-radius:6px;">{{ $skill }}</span>
              @endforeach
            </div>
          </div>
          @endif
          @if($volunteer->motivation)
          <div class="col-12">
            <div class="text-muted mb-1" style="font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;">Motivasi</div>
            <div style="background:#f8fafc;border-radius:8px;padding:12px;line-height:1.7;">{{ $volunteer->motivation }}</div>
          </div>
          @endif
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-4">
    {{-- Status Card --}}
    <div class="card shadow-sm mb-4" style="border:none;border-radius:14px;">
      <div class="card-header bg-white px-4 py-3 border-bottom">
        <h6 class="mb-0 fw-bold"><i class="bi bi-check-circle-fill me-2 text-success"></i>Status Pendaftaran</h6>
      </div>
      <div class="card-body p-4">
        <div class="mb-3">
          <span class="badge {{ $volunteer->status_badge }} px-3 py-2" style="font-size:.8rem;border-radius:8px;">
            {{ $volunteer->status_label }}
          </span>
          @if($volunteer->approved_at)
            <div class="text-muted mt-1" style="font-size:.75rem;">Diproses: {{ $volunteer->approved_at->format('d M Y') }}</div>
          @endif
        </div>

        <form action="{{ route('admin.volunteers.update-status', $volunteer) }}" method="POST">
          @csrf @method('PATCH')
          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:.8rem;">Ubah Status</label>
            <select name="status" class="form-select form-select-sm" style="border-radius:8px;">
              <option value="pending"  {{ $volunteer->status === 'pending'  ? 'selected' : '' }}>Menunggu</option>
              <option value="approved" {{ $volunteer->status === 'approved' ? 'selected' : '' }}>Diterima</option>
              <option value="rejected" {{ $volunteer->status === 'rejected' ? 'selected' : '' }}>Ditolak</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:.8rem;">Catatan Admin</label>
            <textarea name="admin_notes" class="form-control form-control-sm" rows="3" style="border-radius:8px;" placeholder="Catatan internal...">{{ $volunteer->admin_notes }}</textarea>
          </div>
          <button type="submit" class="btn btn-primary w-100 btn-sm" style="border-radius:8px;">
            <i class="bi bi-floppy me-1"></i>Simpan Status
          </button>
        </form>
      </div>
    </div>

    {{-- Quick Actions --}}
    <div class="card shadow-sm" style="border:none;border-radius:14px;">
      <div class="card-body p-3 d-grid gap-2">
        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $volunteer->phone) }}" target="_blank"
           class="btn btn-success btn-sm" style="border-radius:8px;">
          <i class="bi bi-whatsapp me-2"></i>Hubungi via WhatsApp
        </a>
        @if($volunteer->email)
        <a href="mailto:{{ $volunteer->email }}" class="btn btn-outline-primary btn-sm" style="border-radius:8px;">
          <i class="bi bi-envelope me-2"></i>Kirim Email
        </a>
        @endif
        <form action="{{ route('admin.volunteers.destroy', $volunteer) }}" method="POST" onsubmit="return confirm('Hapus data ini?')">
          @csrf @method('DELETE')
          <button type="submit" class="btn btn-outline-danger btn-sm w-100" style="border-radius:8px;">
            <i class="bi bi-trash me-2"></i>Hapus Data
          </button>
        </form>
      </div>
    </div>
  </div>
</div>

@endsection
