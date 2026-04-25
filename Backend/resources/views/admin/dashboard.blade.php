@extends('admin.layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
@endpush

@section('content')

{{-- Stat Cards Row 1 --}}
<div class="row g-3 mb-4">
  <div class="col-sm-6 col-xl-3">
    <div class="card stat-card h-100 shadow-sm" style="border-left:4px solid #1A5276;">
      <div class="card-body d-flex align-items-center gap-3">
        <div class="rounded-3 p-3" style="background:#EBF5FB;">
          <i class="bi bi-newspaper fs-4" style="color:#1A5276;"></i>
        </div>
        <div>
          <div class="text-muted" style="font-size:.72rem;font-weight:600;text-transform:uppercase;">Artikel</div>
          <div class="fw-bold fs-3" style="color:#1A5276;">{{ $stats['total_articles'] }}</div>
          <div style="font-size:.72rem;">
            <span class="text-success">{{ $stats['published_articles'] }} published</span>
            <span class="text-muted ms-1">· {{ $stats['draft_articles'] }} draft</span>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-xl-3">
    <div class="card stat-card h-100 shadow-sm" style="border-left:4px solid #27AE60;">
      <div class="card-body d-flex align-items-center gap-3">
        <div class="rounded-3 p-3" style="background:#EAFAF1;">
          <i class="bi bi-heart-fill fs-4" style="color:#27AE60;"></i>
        </div>
        <div>
          <div class="text-muted" style="font-size:.72rem;font-weight:600;text-transform:uppercase;">Total Donasi</div>
          <div class="fw-bold" style="font-size:1.1rem;color:#27AE60;">Rp {{ number_format($stats['total_collected'], 0, ',', '.') }}</div>
          <div class="text-muted" style="font-size:.72rem;">{{ $stats['count_paid'] }} transaksi sukses</div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-xl-3">
    <div class="card stat-card h-100 shadow-sm" style="border-left:4px solid #8E44AD;">
      <div class="card-body d-flex align-items-center gap-3">
        <div class="rounded-3 p-3" style="background:#F5EEF8;">
          <i class="bi bi-grid-fill fs-4" style="color:#8E44AD;"></i>
        </div>
        <div>
          <div class="text-muted" style="font-size:.72rem;font-weight:600;text-transform:uppercase;">Program</div>
          <div class="fw-bold fs-3" style="color:#8E44AD;">{{ $stats['active_programs'] }}</div>
          <div style="font-size:.72rem;">
            <span class="text-success">Program aktif</span>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-xl-3">
    <div class="card stat-card h-100 shadow-sm" style="border-left:4px solid #E67E22;">
      <div class="card-body d-flex align-items-center gap-3">
        <div class="rounded-3 p-3" style="background:#FEF9E7;">
          <i class="bi bi-chat-left-text-fill fs-4" style="color:#E67E22;"></i>
        </div>
        <div>
          <div class="text-muted" style="font-size:.72rem;font-weight:600;text-transform:uppercase;">Komentar</div>
          <div class="fw-bold fs-3" style="color:#E67E22;">{{ $stats['total_comments'] }}</div>
          <div style="font-size:.72rem;">
            @if($stats['spam_comments'] > 0)
              <span class="text-danger">{{ $stats['spam_comments'] }} spam</span>
            @endif
            @if($stats['pending_comments'] > 0)
              <span class="text-warning {{ $stats['spam_comments'] > 0 ? 'ms-1' : '' }}">{{ $stats['pending_comments'] }} pending</span>
            @endif
            @if($stats['spam_comments'] === 0 && $stats['pending_comments'] === 0)
              <span class="text-success">Bersih</span>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- Charts Row --}}
<div class="row g-4 mb-4">

  {{-- Donation Chart --}}
  <div class="col-lg-8">
    <div class="card shadow-sm h-100" style="border:none;border-radius:16px;">
      <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3 px-4"
           style="border-radius:16px 16px 0 0;">
        <h6 class="mb-0 fw-bold">Donasi 30 Hari Terakhir</h6>
        <span class="badge bg-success-subtle text-success" style="font-size:.72rem;">
          Rp {{ number_format($stats['total_collected'], 0, ',', '.') }} total
        </span>
      </div>
      <div class="card-body p-4">
        <canvas id="donationChart" height="90"></canvas>
      </div>
    </div>
  </div>

  {{-- Article Status Donut --}}
  <div class="col-lg-4">
    <div class="card shadow-sm h-100" style="border:none;border-radius:16px;">
      <div class="card-header bg-white border-bottom py-3 px-4" style="border-radius:16px 16px 0 0;">
        <h6 class="mb-0 fw-bold">Status Artikel</h6>
      </div>
      <div class="card-body d-flex flex-column align-items-center justify-content-center p-4">
        <canvas id="articleChart" style="max-height:180px;max-width:180px;"></canvas>
        <div class="mt-3 d-flex gap-3 flex-wrap justify-content-center" style="font-size:.78rem;">
          <span><span class="d-inline-block rounded-circle me-1" style="width:10px;height:10px;background:#22c55e;"></span>Published: {{ $articleByStatus['published'] }}</span>
          <span><span class="d-inline-block rounded-circle me-1" style="width:10px;height:10px;background:#f59e0b;"></span>Draft: {{ $articleByStatus['draft'] }}</span>
          <span><span class="d-inline-block rounded-circle me-1" style="width:10px;height:10px;background:#94a3b8;"></span>Arsip: {{ $articleByStatus['archived'] }}</span>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- Bottom Row --}}
<div class="row g-4">

  {{-- Recent Donations --}}
  <div class="col-lg-6">
    <div class="card shadow-sm h-100" style="border:none;border-radius:16px;">
      <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3 px-4"
           style="border-radius:16px 16px 0 0;">
        <h6 class="mb-0 fw-bold">Donasi Terbaru</h6>
        <a href="{{ route('admin.donations.index') }}" class="btn btn-sm btn-outline-primary" style="border-radius:8px;">Lihat Semua</a>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead>
              <tr>
                <th class="ps-4">Donatur</th>
                <th>Nominal</th>
                <th>Status</th>
                <th>Waktu</th>
              </tr>
            </thead>
            <tbody>
              @forelse($recentDonations as $donation)
              <tr>
                <td class="ps-4">
                  <div class="fw-semibold" style="font-size:.825rem;">
                    {{ $donation->is_anonymous ? 'Anonim' : $donation->donor_name }}
                  </div>
                  <div class="text-muted" style="font-size:.72rem;">{{ $donation->program?->name ?? 'Umum' }}</div>
                </td>
                <td class="fw-semibold" style="color:#1A5276;font-size:.825rem;">
                  Rp {{ number_format($donation->amount, 0, ',', '.') }}
                </td>
                <td>
                  <span class="badge badge-status bg-{{ $donation->status_badge }}-subtle text-{{ $donation->status_badge }}">
                    {{ $donation->status_label }}
                  </span>
                </td>
                <td class="text-muted" style="font-size:.72rem;">{{ $donation->created_at->diffForHumans() }}</td>
              </tr>
              @empty
              <tr><td colspan="4" class="text-center text-muted py-4">Belum ada donasi</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  {{-- Right Column --}}
  <div class="col-lg-6 d-flex flex-column gap-4">

    {{-- Recent Articles --}}
    <div class="card shadow-sm" style="border:none;border-radius:16px;">
      <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3 px-4"
           style="border-radius:16px 16px 0 0;">
        <h6 class="mb-0 fw-bold">Artikel Terbaru</h6>
        <a href="{{ route('admin.articles.index') }}" class="btn btn-sm btn-outline-primary" style="border-radius:8px;">Lihat Semua</a>
      </div>
      <div class="card-body p-0">
        @forelse($recentArticles as $art)
        <div class="d-flex align-items-center gap-3 px-4 py-2 border-bottom">
          <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
               style="width:34px;height:34px;background:#ebf5fb;">
            <i class="bi bi-newspaper" style="color:#1A5276;font-size:.85rem;"></i>
          </div>
          <div class="flex-grow-1 overflow-hidden">
            <div class="fw-semibold text-truncate" style="font-size:.825rem;">{{ $art->title }}</div>
            <div class="text-muted" style="font-size:.72rem;">{{ $art->category?->name ?? 'Umum' }} · {{ $art->created_at->diffForHumans() }}</div>
          </div>
          <span class="badge bg-{{ $art->status === 'published' ? 'success' : ($art->status === 'draft' ? 'warning text-dark' : 'secondary') }}-subtle
                       text-{{ $art->status === 'published' ? 'success' : ($art->status === 'draft' ? 'warning' : 'secondary') }} px-2"
                style="font-size:.68rem;border-radius:6px;">
            {{ $art->status }}
          </span>
        </div>
        @empty
        <div class="text-center text-muted py-3" style="font-size:.85rem;">Belum ada artikel</div>
        @endforelse
      </div>
    </div>

    {{-- Quick Actions --}}
    <div class="card shadow-sm" style="border:none;border-radius:16px;">
      <div class="card-header bg-white border-bottom py-3 px-4" style="border-radius:16px 16px 0 0;">
        <h6 class="mb-0 fw-bold">Aksi Cepat</h6>
      </div>
      <div class="card-body">
        <div class="row g-2">
          <div class="col-6">
            <a href="{{ route('admin.articles.create') }}" class="btn w-100 d-flex align-items-center gap-2 p-2"
               style="background:#EBF5FB;color:#1A5276;border-radius:10px;font-size:.8rem;font-weight:600;">
              <i class="bi bi-plus-circle-fill"></i> Artikel Baru
            </a>
          </div>
          <div class="col-6">
            <a href="{{ route('admin.programs.index') }}" class="btn w-100 d-flex align-items-center gap-2 p-2"
               style="background:#F5EEF8;color:#8E44AD;border-radius:10px;font-size:.8rem;font-weight:600;">
              <i class="bi bi-grid-fill"></i> Program
            </a>
          </div>
          <div class="col-6">
            <a href="{{ route('admin.donations.index') }}" class="btn w-100 d-flex align-items-center gap-2 p-2"
               style="background:#EAFAF1;color:#27AE60;border-radius:10px;font-size:.8rem;font-weight:600;">
              <i class="bi bi-heart-fill"></i> Data Donasi
            </a>
          </div>
          <div class="col-6">
            <a href="{{ route('admin.comments.index') }}" class="btn w-100 d-flex align-items-center gap-2 p-2"
               style="background:#FEF9E7;color:#E67E22;border-radius:10px;font-size:.8rem;font-weight:600;">
              <i class="bi bi-chat-left-text-fill"></i> Moderasi
              @if($stats['pending_comments'] > 0)
                <span class="badge bg-warning text-dark ms-auto" style="font-size:.65rem;">{{ $stats['pending_comments'] }}</span>
              @endif
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
// Donation line chart
const donData = @json($donationChart);
const donLabels = donData.map(d => d.date);
const donValues = donData.map(d => parseInt(d.total));

new Chart(document.getElementById('donationChart'), {
  type: 'line',
  data: {
    labels: donLabels.length ? donLabels : ['Belum ada data'],
    datasets: [{
      label: 'Total Donasi (Rp)',
      data: donValues,
      borderColor: '#27AE60',
      backgroundColor: 'rgba(39,174,96,.08)',
      fill: true,
      tension: 0.4,
      pointRadius: 3,
    }]
  },
  options: {
    responsive: true,
    plugins: { legend: { display: false } },
    scales: {
      y: { ticks: { callback: v => 'Rp ' + (v/1000).toFixed(0) + 'k', font: { size: 10 } }, grid: { color: '#f1f5f9' } },
      x: { ticks: { font: { size: 10 } }, grid: { display: false } }
    }
  }
});

// Article status donut
const artStatus = @json($articleByStatus);
new Chart(document.getElementById('articleChart'), {
  type: 'doughnut',
  data: {
    labels: ['Published', 'Draft', 'Arsip'],
    datasets: [{
      data: [artStatus.published, artStatus.draft, artStatus.archived],
      backgroundColor: ['#22c55e', '#f59e0b', '#94a3b8'],
      borderWidth: 2,
      borderColor: '#fff',
    }]
  },
  options: {
    responsive: true,
    cutout: '68%',
    plugins: { legend: { display: false } }
  }
});
</script>
@endpush
