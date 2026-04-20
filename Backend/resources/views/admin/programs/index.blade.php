@extends('admin.layouts.app')
@section('title', 'Kampanye Donasi')
@section('page-title', 'Kampanye Donasi')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h5 class="fw-bold mb-1">Kampanye Donasi</h5>
    <p class="text-muted mb-0" style="font-size:.85rem;">Kampanye yang dibuat akan langsung tampil di halaman donasi publik</p>
  </div>
  <a href="{{ route('admin.programs.create') }}" class="btn btn-primary d-flex align-items-center gap-2" style="border-radius:10px;">
    <i class="bi bi-plus-lg"></i> Buat Kampanye Baru
  </a>
</div>

{{-- Filter --}}
<div class="card shadow-sm mb-4" style="border:none;border-radius:14px;">
  <div class="card-body py-3">
    <form method="GET" class="row g-2 align-items-end">
      <div class="col-md-5">
        <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari nama kampanye..." value="{{ request('search') }}" style="border-radius:8px;" />
      </div>
      <div class="col-md-3">
        <select name="status" class="form-select form-select-sm" style="border-radius:8px;">
          <option value="">Semua Status</option>
          <option value="active"    {{ request('status') === 'active'    ? 'selected' : '' }}>Aktif</option>
          <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Selesai</option>
          <option value="paused"    {{ request('status') === 'paused'    ? 'selected' : '' }}>Dijeda</option>
        </select>
      </div>
      <div class="col-md-2 d-flex gap-2">
        <button type="submit" class="btn btn-primary btn-sm" style="border-radius:8px;">Filter</button>
        <a href="{{ route('admin.programs.index') }}" class="btn btn-outline-secondary btn-sm" style="border-radius:8px;">Reset</a>
      </div>
    </form>
  </div>
</div>

<div class="row g-4">
  @forelse($programs as $program)
  <div class="col-md-6 col-xl-4">
    <div class="card shadow-sm h-100" style="border:none;border-radius:14px;transition:box-shadow .2s;">
      {{-- Thumbnail --}}
      @if($program->thumbnail_url)
        <img src="{{ $program->thumbnail_url }}" class="card-img-top"
             style="height:160px;object-fit:cover;border-radius:14px 14px 0 0;" />
      @else
        <div class="d-flex align-items-center justify-content-center"
             style="height:140px;background:linear-gradient(135deg,#e8f2ff,#d0e7ff);border-radius:14px 14px 0 0;">
          <i class="bi bi-heart-fill" style="font-size:2.5rem;color:#1A5276;opacity:.3;"></i>
        </div>
      @endif

      <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-start mb-2">
          <span class="badge {{ $program->status === 'active' ? 'bg-success-subtle text-success' : ($program->status === 'completed' ? 'bg-primary-subtle text-primary' : 'bg-warning-subtle text-warning') }}"
                style="font-size:.7rem;border-radius:6px;">{{ ucfirst($program->status) }}</span>
          <div class="d-flex gap-1">
            @if($program->is_featured)
              <span class="badge bg-warning text-dark" style="font-size:.65rem;">⭐ Unggulan</span>
            @endif
            @if($program->target_amount > 0)
              <span class="badge bg-info-subtle text-info" style="font-size:.65rem;">Ada Target</span>
            @else
              <span class="badge bg-secondary-subtle text-secondary" style="font-size:.65rem;">Terbuka</span>
            @endif
          </div>
        </div>

        <h6 class="fw-bold mb-1" style="line-height:1.4;">{{ $program->name }}</h6>
        @if($program->description)
          <p class="text-muted mb-3" style="font-size:.8rem;line-height:1.5;">{{ Str::limit($program->description, 80) }}</p>
        @endif

        {{-- Progress --}}
        @if($program->target_amount > 0)
        <div class="mb-3">
          <div class="d-flex justify-content-between mb-1" style="font-size:.75rem;">
            <span class="text-success fw-semibold">Rp {{ number_format($program->collected_amount, 0, ',', '.') }}</span>
            <span class="fw-semibold" style="color:#1A5276;">{{ $program->progress_percentage }}%</span>
          </div>
          <div class="progress" style="height:6px;border-radius:3px;">
            <div class="progress-bar" style="width:{{ $program->progress_percentage }}%;background:#1A5276;border-radius:3px;"></div>
          </div>
          <div class="text-muted mt-1" style="font-size:.72rem;">Target: Rp {{ number_format($program->target_amount, 0, ',', '.') }}</div>
        </div>
        @else
        <div class="mb-3">
          <div style="font-size:.75rem;">
            <span class="text-success fw-semibold">Rp {{ number_format($program->collected_amount, 0, ',', '.') }}</span>
            <span class="text-muted ms-1">terkumpul</span>
          </div>
        </div>
        @endif

        <div class="d-flex gap-1 mt-auto">
          <a href="{{ route('admin.programs.show', $program) }}" class="btn btn-sm btn-outline-secondary" style="border-radius:7px;" title="Detail & Donasi">
            <i class="bi bi-eye"></i>
          </a>
          <a href="{{ route('admin.programs.edit', $program) }}" class="btn btn-sm btn-outline-primary flex-grow-1" style="border-radius:7px;">
            <i class="bi bi-pencil me-1"></i>Edit
          </a>
          <form action="{{ route('admin.programs.destroy', $program) }}" method="POST" onsubmit="return confirm('Hapus kampanye ini?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-sm btn-outline-danger" style="border-radius:7px;">
              <i class="bi bi-trash"></i>
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
  @empty
  <div class="col-12">
    <div class="card shadow-sm" style="border:none;border-radius:14px;">
      <div class="card-body text-center py-5 text-muted">
        <i class="bi bi-heart fs-1 d-block mb-2 text-light"></i>
        Belum ada kampanye. <a href="{{ route('admin.programs.create') }}">Buat kampanye pertama →</a>
      </div>
    </div>
  </div>
  @endforelse
</div>

@if($programs->hasPages())
<div class="d-flex justify-content-center mt-4">{{ $programs->links() }}</div>
@endif

@endsection
