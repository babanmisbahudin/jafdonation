@extends('admin.layouts.app')
@section('title', 'Detail Donasi')
@section('page-title', 'Detail Donasi')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h5 class="fw-bold mb-1">Detail Donasi</h5>
    <code style="font-size:.8rem;">{{ $donation->order_id }}</code>
  </div>
  <a href="{{ route('admin.donations.index') }}" class="btn btn-outline-secondary btn-sm" style="border-radius:8px;">
    <i class="bi bi-arrow-left me-1"></i>Kembali
  </a>
</div>

<div class="row g-4">
  <div class="col-lg-7">
    <div class="card shadow-sm" style="border:none;border-radius:14px;">
      <div class="card-header bg-white border-bottom py-3 px-4" style="border-radius:14px 14px 0 0;">
        <h6 class="mb-0 fw-bold">Informasi Donatur</h6>
      </div>
      <div class="card-body px-4 py-4">
        <div class="row g-3">
          <div class="col-sm-6">
            <label class="text-muted" style="font-size:.75rem;text-transform:uppercase;font-weight:600;">Nama</label>
            <div class="fw-semibold">{{ $donation->is_anonymous ? '(Anonim)' : $donation->donor_name }}</div>
          </div>
          <div class="col-sm-6">
            <label class="text-muted" style="font-size:.75rem;text-transform:uppercase;font-weight:600;">Email</label>
            <div>{{ $donation->donor_email }}</div>
          </div>
          <div class="col-sm-6">
            <label class="text-muted" style="font-size:.75rem;text-transform:uppercase;font-weight:600;">Telepon</label>
            <div>{{ $donation->donor_phone ?? '–' }}</div>
          </div>
          <div class="col-sm-6">
            <label class="text-muted" style="font-size:.75rem;text-transform:uppercase;font-weight:600;">Program</label>
            <div>{{ $donation->program?->name ?? 'Donasi Umum' }}</div>
          </div>
          <div class="col-sm-6">
            <label class="text-muted" style="font-size:.75rem;text-transform:uppercase;font-weight:600;">Nominal</label>
            <div class="fw-bold fs-5" style="color:#1A5276;">Rp {{ number_format($donation->amount, 0, ',', '.') }}</div>
          </div>
          <div class="col-sm-6">
            <label class="text-muted" style="font-size:.75rem;text-transform:uppercase;font-weight:600;">Metode Bayar</label>
            <div>{{ $donation->payment_method ?? '–' }}</div>
          </div>
          @if($donation->note)
          <div class="col-12">
            <label class="text-muted" style="font-size:.75rem;text-transform:uppercase;font-weight:600;">Catatan</label>
            <div class="p-3 bg-light rounded">{{ $donation->note }}</div>
          </div>
          @endif
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-5">
    {{-- Status --}}
    <div class="card shadow-sm mb-4" style="border:none;border-radius:14px;">
      <div class="card-header bg-white border-bottom py-3 px-4" style="border-radius:14px 14px 0 0;">
        <h6 class="mb-0 fw-bold">Status Pembayaran</h6>
      </div>
      <div class="card-body px-4 py-4">
        <div class="text-center mb-4">
          <span class="badge fs-6 px-4 py-2 bg-{{ $donation->status_badge }}">{{ $donation->status_label }}</span>
          @if($donation->paid_at)
            <div class="text-success mt-2" style="font-size:.8rem;">
              <i class="bi bi-check-circle me-1"></i>Dibayar: {{ $donation->paid_at->format('d M Y H:i') }}
            </div>
          @endif
        </div>
        <form action="{{ route('admin.donations.update-status', $donation) }}" method="POST">
          @csrf @method('PATCH')
          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:.875rem;">Update Status Manual</label>
            <select name="payment_status" class="form-select form-select-sm" style="border-radius:8px;">
              @foreach(\App\Models\Donation::STATUSES as $val => $label)
                <option value="{{ $val }}" {{ $donation->payment_status === $val ? 'selected' : '' }}>{{ $label }}</option>
              @endforeach
            </select>
          </div>
          <button type="submit" class="btn btn-primary btn-sm w-100" style="border-radius:8px;">
            Update Status
          </button>
        </form>
      </div>
    </div>

    {{-- Midtrans Info --}}
    <div class="card shadow-sm" style="border:none;border-radius:14px;">
      <div class="card-header bg-white border-bottom py-3 px-4" style="border-radius:14px 14px 0 0;">
        <h6 class="mb-0 fw-bold">Info Midtrans</h6>
      </div>
      <div class="card-body px-4 py-3">
        <div class="mb-2">
          <label class="text-muted" style="font-size:.72rem;text-transform:uppercase;font-weight:600;">Transaction ID</label>
          <div style="font-size:.825rem;">{{ $donation->midtrans_transaction_id ?? '–' }}</div>
        </div>
        <div class="mb-2">
          <label class="text-muted" style="font-size:.72rem;text-transform:uppercase;font-weight:600;">Dibuat</label>
          <div style="font-size:.825rem;">{{ $donation->created_at->format('d M Y H:i:s') }}</div>
        </div>
        @if($donation->midtrans_response)
        <div class="mt-3">
          <label class="text-muted" style="font-size:.72rem;text-transform:uppercase;font-weight:600;">Response JSON</label>
          <pre class="bg-dark text-success p-3 rounded mt-1" style="font-size:.72rem;max-height:180px;overflow:auto;">{{ json_encode($donation->midtrans_response, JSON_PRETTY_PRINT) }}</pre>
        </div>
        @endif
      </div>
    </div>

  </div>
</div>
@endsection
