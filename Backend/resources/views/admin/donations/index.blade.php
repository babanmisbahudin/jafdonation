@extends('admin.layouts.app')
@section('title', 'Data Donasi')
@section('page-title', 'Data Donasi')

@section('content')

{{-- Summary Cards --}}
<div class="row g-3 mb-4">
  <div class="col-sm-6 col-xl-3">
    <div class="card shadow-sm" style="border:none;border-radius:12px;border-left:4px solid #27AE60;">
      <div class="card-body py-3 px-4">
        <div class="text-muted" style="font-size:.72rem;font-weight:600;text-transform:uppercase;">Total Terkumpul</div>
        <div class="fw-bold" style="font-size:1.2rem;color:#27AE60;">Rp {{ number_format($summary['paid'], 0, ',', '.') }}</div>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-xl-3">
    <div class="card shadow-sm" style="border:none;border-radius:12px;border-left:4px solid #1A5276;">
      <div class="card-body py-3 px-4">
        <div class="text-muted" style="font-size:.72rem;font-weight:600;text-transform:uppercase;">Donasi Sukses</div>
        <div class="fw-bold" style="font-size:1.2rem;color:#1A5276;">{{ number_format($summary['count_paid']) }} donasi</div>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-xl-3">
    <div class="card shadow-sm" style="border:none;border-radius:12px;border-left:4px solid #F39C12;">
      <div class="card-body py-3 px-4">
        <div class="text-muted" style="font-size:.72rem;font-weight:600;text-transform:uppercase;">Pending</div>
        <div class="fw-bold" style="font-size:1.2rem;color:#F39C12;">{{ $summary['pending'] }} menunggu</div>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-xl-3">
    <div class="card shadow-sm" style="border:none;border-radius:12px;border-left:4px solid #95A5A6;">
      <div class="card-body py-3 px-4">
        <div class="text-muted" style="font-size:.72rem;font-weight:600;text-transform:uppercase;">Total Semua</div>
        <div class="fw-bold" style="font-size:1.2rem;color:#555;">Rp {{ number_format($summary['total'], 0, ',', '.') }}</div>
      </div>
    </div>
  </div>
</div>

{{-- Filter --}}
<div class="card shadow-sm mb-4" style="border:none;border-radius:14px;">
  <div class="card-body py-3">
    <form action="{{ route('admin.donations.index') }}" method="GET" class="row g-2 align-items-end">
      <div class="col-md-3">
        <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari nama, email, order ID..."
               value="{{ request('search') }}" style="border-radius:8px;" />
      </div>
      <div class="col-md-2">
        <select name="status" class="form-select form-select-sm" style="border-radius:8px;">
          <option value="">Semua Status</option>
          @foreach(\App\Models\Donation::STATUSES as $val => $label)
            <option value="{{ $val }}" {{ request('status') === $val ? 'selected' : '' }}>{{ $label }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-2">
        <select name="program" class="form-select form-select-sm" style="border-radius:8px;">
          <option value="">Semua Program</option>
          @foreach($programs as $prog)
            <option value="{{ $prog->id }}" {{ request('program') == $prog->id ? 'selected' : '' }}>{{ $prog->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-2">
        <input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}" style="border-radius:8px;" />
      </div>
      <div class="col-md-2">
        <input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}" style="border-radius:8px;" />
      </div>
      <div class="col-md-1 d-flex gap-1">
        <button type="submit" class="btn btn-primary btn-sm" style="border-radius:8px;">Filter</button>
      </div>
    </form>
  </div>
</div>

{{-- Table --}}
<div class="card shadow-sm" style="border:none;border-radius:14px;">
  <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center"
       style="border-radius:14px 14px 0 0;">
    <span class="fw-semibold" style="font-size:.9rem;">{{ $donations->total() }} data donasi</span>
    <a href="{{ route('admin.donations.export', request()->all()) }}"
       class="btn btn-sm btn-outline-success" style="border-radius:8px;">
      <i class="bi bi-download me-1"></i>Export CSV
    </a>
  </div>
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover mb-0">
        <thead>
          <tr>
            <th class="ps-4">Order ID</th>
            <th>Donatur</th>
            <th>Program</th>
            <th>Nominal</th>
            <th>Metode</th>
            <th>Status</th>
            <th>Tanggal</th>
            <th class="text-end pe-4">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($donations as $donation)
          <tr>
            <td class="ps-4">
              <code style="font-size:.75rem;">{{ $donation->order_id }}</code>
            </td>
            <td>
              <div class="fw-semibold" style="font-size:.85rem;">
                {{ $donation->is_anonymous ? '(Anonim)' : $donation->donor_name }}
              </div>
              <div class="text-muted" style="font-size:.75rem;">{{ $donation->donor_email }}</div>
            </td>
            <td style="font-size:.825rem;">{{ $donation->program?->name ?? 'Umum' }}</td>
            <td class="fw-bold" style="color:#1A5276;font-size:.875rem;">
              Rp {{ number_format($donation->amount, 0, ',', '.') }}
            </td>
            <td class="text-muted" style="font-size:.8rem;">{{ $donation->payment_method ?? '–' }}</td>
            <td>
              <span class="badge badge-status bg-{{ $donation->status_badge }}-subtle text-{{ $donation->status_badge }}">
                {{ $donation->status_label }}
              </span>
            </td>
            <td class="text-muted" style="font-size:.775rem;">{{ $donation->created_at->format('d M Y H:i') }}</td>
            <td class="text-end pe-4">
              <a href="{{ route('admin.donations.show', $donation) }}" class="btn btn-sm btn-outline-primary" style="border-radius:7px;">
                <i class="bi bi-eye"></i>
              </a>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="8" class="text-center text-muted py-5">
              <i class="bi bi-heart fs-1 d-block mb-2 text-light"></i>Belum ada data donasi
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
  @if($donations->hasPages())
  <div class="card-footer bg-white py-3 px-4" style="border-radius:0 0 14px 14px;">{{ $donations->links() }}</div>
  @endif
</div>

@endsection
