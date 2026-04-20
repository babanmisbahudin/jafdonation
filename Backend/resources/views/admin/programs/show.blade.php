@extends('admin.layouts.app')
@section('title', $program->name)
@section('page-title', 'Detail Kampanye')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h5 class="fw-bold mb-1">{{ $program->name }}</h5>
    <div class="d-flex align-items-center gap-2">
      <span class="badge {{ $program->status === 'active' ? 'bg-success-subtle text-success' : ($program->status === 'completed' ? 'bg-primary-subtle text-primary' : 'bg-warning-subtle text-warning') }}"
            style="font-size:.75rem;">{{ ucfirst($program->status) }}</span>
      @if($program->is_featured)
        <span class="badge bg-warning text-dark" style="font-size:.65rem;">⭐ Unggulan</span>
      @endif
      @if($program->target_amount > 0)
        <span class="badge bg-info-subtle text-info" style="font-size:.65rem;">Ada Target</span>
      @else
        <span class="badge bg-secondary-subtle text-secondary" style="font-size:.65rem;">Donasi Terbuka</span>
      @endif
    </div>
  </div>
  <div class="d-flex gap-2">
    <button onclick="copyLink()" class="btn btn-outline-success btn-sm" style="border-radius:8px;" id="copyBtn">
      <i class="bi bi-link-45deg me-1"></i>Salin Link
    </button>
    <a href="{{ route('admin.programs.edit', $program) }}" class="btn btn-primary btn-sm" style="border-radius:8px;">
      <i class="bi bi-pencil me-1"></i>Edit
    </a>
    <a href="{{ route('admin.programs.index') }}" class="btn btn-outline-secondary btn-sm" style="border-radius:8px;">
      <i class="bi bi-arrow-left me-1"></i>Kembali
    </a>
  </div>
</div>

<div class="row g-4 mb-4">
  {{-- Stats --}}
  <div class="col-sm-6 col-lg-3">
    <div class="card shadow-sm" style="border:none;border-radius:12px;border-left:4px solid #22c55e;">
      <div class="card-body py-3 px-4">
        <div class="text-muted" style="font-size:.72rem;font-weight:600;text-transform:uppercase;">Total Terkumpul</div>
        <div class="fw-bold" style="font-size:1.15rem;color:#16a34a;">Rp {{ number_format($stats['total_raised'], 0, ',', '.') }}</div>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-lg-3">
    <div class="card shadow-sm" style="border:none;border-radius:12px;border-left:4px solid #1A5276;">
      <div class="card-body py-3 px-4">
        <div class="text-muted" style="font-size:.72rem;font-weight:600;text-transform:uppercase;">Jumlah Donatur</div>
        <div class="fw-bold" style="font-size:1.15rem;color:#1A5276;">{{ number_format($stats['donor_count']) }} orang</div>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-lg-3">
    <div class="card shadow-sm" style="border:none;border-radius:12px;border-left:4px solid #f59e0b;">
      <div class="card-body py-3 px-4">
        <div class="text-muted" style="font-size:.72rem;font-weight:600;text-transform:uppercase;">Pembayaran Pending</div>
        <div class="fw-bold" style="font-size:1.15rem;color:#d97706;">{{ $stats['pending'] }} transaksi</div>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-lg-3">
    @if($program->target_amount > 0)
    <div class="card shadow-sm" style="border:none;border-radius:12px;border-left:4px solid #8b5cf6;">
      <div class="card-body py-3 px-4">
        <div class="text-muted" style="font-size:.72rem;font-weight:600;text-transform:uppercase;">Progres Target</div>
        <div class="fw-bold" style="font-size:1.15rem;color:#7c3aed;">{{ $program->progress_percentage }}%</div>
        <div class="progress mt-1" style="height:4px;border-radius:2px;">
          <div class="progress-bar" style="width:{{ $program->progress_percentage }}%;background:#7c3aed;border-radius:2px;"></div>
        </div>
      </div>
    </div>
    @else
    <div class="card shadow-sm" style="border:none;border-radius:12px;border-left:4px solid #64748b;">
      <div class="card-body py-3 px-4">
        <div class="text-muted" style="font-size:.72rem;font-weight:600;text-transform:uppercase;">Tipe</div>
        <div class="fw-bold" style="font-size:1rem;color:#475569;">Donasi Terbuka</div>
        <div style="font-size:.72rem;color:#94a3b8;">Tanpa target dana</div>
      </div>
    </div>
    @endif
  </div>
</div>

<div class="row g-4">
  <div class="col-lg-8">
    {{-- Donation Table --}}
    <div class="card shadow-sm" style="border:none;border-radius:14px;">
      <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center"
           style="border-radius:14px 14px 0 0;">
        <h6 class="mb-0 fw-bold">Riwayat Donasi ({{ $donations->total() }})</h6>
        <a href="{{ route('admin.donations.index', ['program' => $program->id]) }}"
           class="btn btn-sm btn-outline-primary" style="border-radius:8px;">Semua Donasi →</a>
      </div>
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" style="font-size:.85rem;">
          <thead style="background:#f8fafc;font-size:.72rem;text-transform:uppercase;letter-spacing:.05em;color:#64748b;">
            <tr>
              <th class="px-4 py-3">Donatur</th>
              <th>Nominal</th>
              <th>Status</th>
              <th>Waktu</th>
            </tr>
          </thead>
          <tbody>
            @forelse($donations as $d)
            <tr>
              <td class="px-4">
                <div class="fw-semibold" style="font-size:.825rem;">{{ $d->is_anonymous ? 'Anonim' : $d->donor_name }}</div>
                <div class="text-muted" style="font-size:.72rem;">{{ $d->donor_email }}</div>
              </td>
              <td class="fw-semibold" style="color:#1A5276;">Rp {{ number_format($d->amount, 0, ',', '.') }}</td>
              <td>
                <span class="badge bg-{{ $d->status_badge }}-subtle text-{{ $d->status_badge }}" style="font-size:.7rem;border-radius:6px;">
                  {{ $d->status_label }}
                </span>
              </td>
              <td class="text-muted" style="font-size:.75rem;">{{ $d->created_at->format('d M Y H:i') }}</td>
            </tr>
            @empty
            <tr><td colspan="4" class="text-center text-muted py-4">Belum ada donasi untuk kampanye ini</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
      @if($donations->hasPages())
      <div class="card-footer bg-white py-3 px-4" style="border-radius:0 0 14px 14px;">
        {{ $donations->links() }}
      </div>
      @endif
    </div>
  </div>

  <div class="col-lg-4">
    {{-- Campaign Info --}}
    <div class="card shadow-sm mb-4" style="border:none;border-radius:14px;">
      <div class="card-header bg-white border-bottom py-3 px-4" style="border-radius:14px 14px 0 0;">
        <h6 class="mb-0 fw-bold" style="font-size:.875rem;">Info Kampanye</h6>
      </div>
      <div class="card-body p-4" style="font-size:.85rem;">
        @if($program->thumbnail_url)
          <img src="{{ $program->thumbnail_url }}" class="img-fluid rounded-3 mb-3 w-100" style="height:160px;object-fit:cover;" />
        @endif
        @if($program->description)
          <p class="text-muted mb-3" style="line-height:1.7;">{{ $program->description }}</p>
        @endif
        <div class="d-flex flex-column gap-2">
          @if($program->target_amount > 0)
          <div class="d-flex justify-content-between">
            <span class="text-muted">Target Dana</span>
            <span class="fw-semibold">Rp {{ number_format($program->target_amount, 0, ',', '.') }}</span>
          </div>
          <div class="d-flex justify-content-between">
            <span class="text-muted">Terkumpul</span>
            <span class="fw-semibold text-success">Rp {{ number_format($program->collected_amount, 0, ',', '.') }}</span>
          </div>
          @endif
          @if($program->start_date)
          <div class="d-flex justify-content-between">
            <span class="text-muted">Mulai</span>
            <span>{{ $program->start_date->format('d M Y') }}</span>
          </div>
          @endif
          @if($program->end_date)
          <div class="d-flex justify-content-between">
            <span class="text-muted">Berakhir</span>
            <span class="{{ $program->days_left === 0 ? 'text-danger' : '' }}">
              {{ $program->end_date->format('d M Y') }}
              @if($program->days_left !== null)
                <span class="text-muted">({{ $program->days_left }} hari lagi)</span>
              @endif
            </span>
          </div>
          @endif
        </div>
      </div>
    </div>

    {{-- Link Publik --}}
    <div class="card shadow-sm" style="border:none;border-radius:14px;">
      <div class="card-body p-4">
        <div class="mb-2 fw-semibold" style="font-size:.85rem;"><i class="bi bi-link-45deg me-1 text-primary"></i>Link Halaman Donasi</div>
        <div class="input-group">
          <input type="text" class="form-control form-control-sm" readonly
                 id="publicLink"
                 value="{{ url('/') }}/donasi-detail.html?slug={{ $program->slug }}"
                 style="border-radius:8px 0 0 8px;font-size:.78rem;background:#f8fafc;" />
          <button class="btn btn-outline-primary btn-sm" onclick="copyLink()" style="border-radius:0 8px 8px 0;" id="copyBtn2">
            <i class="bi bi-clipboard"></i>
          </button>
        </div>
        <div class="form-text">Bagikan link ini kepada calon donatur</div>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
function copyLink() {
  const link = document.getElementById('publicLink').value;
  navigator.clipboard.writeText(link).then(() => {
    const btn = document.getElementById('copyBtn');
    const btn2 = document.getElementById('copyBtn2');
    btn.innerHTML  = '<i class="bi bi-check-lg me-1"></i>Tersalin!';
    btn2.innerHTML = '<i class="bi bi-check-lg"></i>';
    btn.classList.replace('btn-outline-success', 'btn-success');
    setTimeout(() => {
      btn.innerHTML  = '<i class="bi bi-link-45deg me-1"></i>Salin Link';
      btn2.innerHTML = '<i class="bi bi-clipboard"></i>';
      btn.classList.replace('btn-success', 'btn-outline-success');
    }, 2000);
  });
}
</script>
@endpush

@endsection
