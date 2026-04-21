@extends('admin.layouts.app')
@section('title', 'Edit Kampanye')
@section('page-title', 'Edit Kampanye')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
  <h5 class="fw-bold mb-0">Edit: {{ $program->name }}</h5>
  <div class="d-flex gap-2">
    <a href="{{ route('admin.programs.show', $program) }}" class="btn btn-outline-info btn-sm" style="border-radius:8px;">
      <i class="bi bi-eye me-1"></i>Lihat Detail
    </a>
    <a href="{{ route('admin.programs.index') }}" class="btn btn-outline-secondary btn-sm" style="border-radius:8px;">
      <i class="bi bi-arrow-left me-1"></i>Kembali
    </a>
  </div>
</div>

<form action="{{ route('admin.programs.update', $program) }}" method="POST" enctype="multipart/form-data">
@csrf @method('PUT')
<div class="row g-4">
  <div class="col-lg-8">
    {{-- Main Info --}}
    <div class="card shadow-sm mb-4" style="border:none;border-radius:14px;">
      <div class="card-header bg-white border-bottom py-3 px-4" style="border-radius:14px 14px 0 0;">
        <h6 class="mb-0 fw-bold" style="font-size:.875rem;"><i class="bi bi-megaphone-fill me-2 text-primary"></i>Informasi Kampanye</h6>
      </div>
      <div class="card-body p-4">
        <div class="mb-3">
          <label class="form-label fw-semibold">Judul Kampanye <span class="text-danger">*</span></label>
          <input type="text" name="name" class="form-control" required value="{{ old('name', $program->name) }}" style="border-radius:8px;" />
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold">Deskripsi Singkat</label>
          <textarea name="description" class="form-control" rows="3" style="border-radius:8px;">{{ old('description', $program->description) }}</textarea>
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold">Deskripsi Lengkap</label>
          <textarea name="content" class="form-control" rows="7" style="border-radius:8px;">{{ old('content', $program->content) }}</textarea>
        </div>
      </div>
    </div>

    {{-- Target Type --}}
    <div class="card shadow-sm mb-4" style="border:none;border-radius:14px;">
      <div class="card-header bg-white border-bottom py-3 px-4" style="border-radius:14px 14px 0 0;">
        <h6 class="mb-0 fw-bold" style="font-size:.875rem;"><i class="bi bi-bullseye me-2 text-primary"></i>Tipe Donasi</h6>
      </div>
      <div class="card-body p-4">
        @php $hasTarget = old('has_target', $program->target_amount > 0 ? '1' : '0'); @endphp
        <div class="row g-3 mb-3">
          <div class="col-md-6">
            <label class="d-flex align-items-start gap-3 p-3 rounded-3 target-option"
                   style="border:2px solid {{ $hasTarget === '1' ? '#1A5276' : '#e2e8f0' }};background:{{ $hasTarget === '1' ? '#f0f6ff' : '' }};cursor:pointer;" for="typeTarget">
              <input type="radio" name="has_target" value="1" id="typeTarget"
                     {{ $hasTarget === '1' ? 'checked' : '' }}
                     class="form-check-input mt-1 flex-shrink-0" />
              <div>
                <div class="fw-semibold" style="font-size:.875rem;">Dengan Target Dana</div>
                <div class="text-muted" style="font-size:.78rem;">Progress bar ditampilkan ke publik</div>
              </div>
            </label>
          </div>
          <div class="col-md-6">
            <label class="d-flex align-items-start gap-3 p-3 rounded-3 target-option"
                   style="border:2px solid {{ $hasTarget === '0' ? '#1A5276' : '#e2e8f0' }};background:{{ $hasTarget === '0' ? '#f0f6ff' : '' }};cursor:pointer;" for="typeOpen">
              <input type="radio" name="has_target" value="0" id="typeOpen"
                     {{ $hasTarget === '0' ? 'checked' : '' }}
                     class="form-check-input mt-1 flex-shrink-0" />
              <div>
                <div class="fw-semibold" style="font-size:.875rem;">Donasi Terbuka</div>
                <div class="text-muted" style="font-size:.78rem;">Tanpa batas target dana</div>
              </div>
            </label>
          </div>
        </div>
        <div id="targetField" style="{{ $hasTarget === '1' ? '' : 'display:none;' }}">
          <label class="form-label fw-semibold">Jumlah Target Dana (Rp)</label>
          <div class="input-group">
            <span class="input-group-text" style="border-radius:8px 0 0 8px;background:#f8fafc;">Rp</span>
            <input type="number" name="target_amount" class="form-control" min="100000"
                   value="{{ old('target_amount', $program->target_amount > 0 ? $program->target_amount : '') }}"
                   style="border-radius:0 8px 8px 0;" />
          </div>
        </div>
      </div>
    </div>

    {{-- Period --}}
    <div class="card shadow-sm" style="border:none;border-radius:14px;">
      <div class="card-header bg-white border-bottom py-3 px-4" style="border-radius:14px 14px 0 0;">
        <h6 class="mb-0 fw-bold" style="font-size:.875rem;"><i class="bi bi-calendar3 me-2 text-primary"></i>Periode</h6>
      </div>
      <div class="card-body p-4">
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label fw-semibold" style="font-size:.875rem;">Tanggal Mulai</label>
            <input type="date" name="start_date" class="form-control" value="{{ old('start_date', $program->start_date?->format('Y-m-d')) }}" style="border-radius:8px;" />
          </div>
          <div class="col-md-6">
            <label class="form-label fw-semibold" style="font-size:.875rem;">Tanggal Berakhir</label>
            <input type="date" name="end_date" class="form-control" value="{{ old('end_date', $program->end_date?->format('Y-m-d')) }}" style="border-radius:8px;" />
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-4">
    <div class="card shadow-sm mb-4" style="border:none;border-radius:14px;">
      <div class="card-header bg-white border-bottom py-3 px-4" style="border-radius:14px 14px 0 0;">
        <h6 class="mb-0 fw-bold" style="font-size:.875rem;">Pengaturan</h6>
      </div>
      <div class="card-body p-4">
        <div class="mb-3">
          <label class="form-label fw-semibold" style="font-size:.875rem;">Status</label>
          <select name="status" class="form-select form-select-sm" style="border-radius:8px;">
            <option value="active"    {{ old('status', $program->status) === 'active'    ? 'selected' : '' }}>✅ Aktif</option>
            <option value="paused"    {{ old('status', $program->status) === 'paused'    ? 'selected' : '' }}>⏸ Dijeda</option>
            <option value="completed" {{ old('status', $program->status) === 'completed' ? 'selected' : '' }}>🏁 Selesai</option>
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold" style="font-size:.875rem;">Kategori</label>
          <select name="category_id" class="form-select form-select-sm" style="border-radius:8px;">
            <option value="">-- Tanpa Kategori --</option>
            @foreach($categories as $cat)
              <option value="{{ $cat->id }}" {{ old('category_id', $program->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold" style="font-size:.875rem;">Wilayah</label>
          <select name="region" class="form-select form-select-sm" style="border-radius:8px;">
            <option value="">Seluruh Indonesia</option>
            @foreach(\App\Models\Article::REGIONS as $val => $label)
              <option value="{{ $val }}" {{ old('region', $program->region) === $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
          </select>
        </div>
        <div class="form-check form-switch mb-4">
          <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="isFeatured"
                 {{ old('is_featured', $program->is_featured) ? 'checked' : '' }}>
          <label class="form-check-label" for="isFeatured" style="font-size:.875rem;">
            <i class="bi bi-star-fill text-warning me-1"></i>Kampanye Unggulan
          </label>
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold" style="font-size:.875rem;">Teks Tombol CTA</label>
          <input type="text" name="cta_text" class="form-control" value="{{ old('cta_text', $program->cta_text) }}" placeholder="Dukung" style="border-radius:8px;" />
        </div>
        <div class="mb-4">
          <label class="form-label fw-semibold" style="font-size:.875rem;">URL Tombol CTA</label>
          <input type="text" name="cta_url" class="form-control" value="{{ old('cta_url', $program->cta_url) }}" placeholder="pages/donasi.html" style="border-radius:8px;" />
          <div class="form-text">Kosongkan untuk default ke halaman donasi</div>
        </div>

        <button type="submit" class="btn btn-primary w-100" style="border-radius:10px;">
          <i class="bi bi-floppy me-2"></i>Simpan Perubahan
        </button>
      </div>
    </div>
    <div class="card shadow-sm" style="border:none;border-radius:14px;">
      <div class="card-header bg-white border-bottom py-3 px-4" style="border-radius:14px 14px 0 0;">
        <h6 class="mb-0 fw-bold" style="font-size:.875rem;"><i class="bi bi-image me-2"></i>Foto Kampanye</h6>
      </div>
      <div class="card-body p-4">
        @if($program->thumbnail_url)
          <img src="{{ $program->thumbnail_url }}" class="img-fluid rounded-3 mb-3 w-100" style="height:150px;object-fit:cover;" />
        @endif
        <input type="file" name="thumbnail" class="form-control form-control-sm" accept="image/*"
               style="border-radius:8px;" onchange="previewThumb(this)" />
        <div class="form-text">Upload baru untuk mengganti</div>
      </div>
    </div>
  </div>
</div>
</form>

@push('scripts')
<script>
document.querySelectorAll('input[name="has_target"]').forEach(radio => {
  radio.addEventListener('change', function () {
    const field = document.getElementById('targetField');
    const input = field.querySelector('input[name="target_amount"]');
    field.style.display = this.value === '1' ? '' : 'none';
    input.required = this.value === '1';
    if (this.value !== '1') input.value = '';
  });
});

document.querySelectorAll('.target-option').forEach(label => {
  const radio = label.querySelector('input[type="radio"]');
  radio.addEventListener('change', () => {
    document.querySelectorAll('.target-option').forEach(l => {
      l.style.borderColor = '#e2e8f0';
      l.style.background  = '';
    });
    label.style.borderColor = '#1A5276';
    label.style.background  = '#f0f6ff';
  });
});

function previewThumb(input) {
  if (input.files && input.files[0]) {
    const reader = new FileReader();
    reader.onload = e => {
      let img = document.querySelector('.card-body img.img-fluid');
      if (!img) {
        img = document.createElement('img');
        img.className = 'img-fluid rounded-3 mb-3 w-100';
        img.style.height = '150px';
        img.style.objectFit = 'cover';
        input.parentNode.insertBefore(img, input);
      }
      img.src = e.target.result;
    };
    reader.readAsDataURL(input.files[0]);
  }
}
</script>
@endpush
@endsection
