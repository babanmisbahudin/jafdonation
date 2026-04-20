@extends('admin.layouts.app')
@section('title', 'Buat Kampanye')
@section('page-title', 'Buat Kampanye Baru')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
  <h5 class="fw-bold mb-0">Buat Kampanye Baru</h5>
  <a href="{{ route('admin.programs.index') }}" class="btn btn-outline-secondary btn-sm" style="border-radius:8px;">
    <i class="bi bi-arrow-left me-1"></i>Kembali
  </a>
</div>

<div class="alert alert-info d-flex align-items-center gap-2 mb-4" style="border-radius:10px;font-size:.875rem;">
  <i class="bi bi-info-circle-fill text-info"></i>
  Kampanye yang disimpan dengan status <strong>Aktif</strong> akan langsung tampil di halaman donasi publik secara real-time.
</div>

<form action="{{ route('admin.programs.store') }}" method="POST" enctype="multipart/form-data">
@csrf
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
          <input type="text" name="name" class="form-control" required value="{{ old('name') }}" style="border-radius:8px;"
                 placeholder="Cth: Bantu Renovasi Pusat Kesenian Jatiwangi" />
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold">Deskripsi Singkat
            <small class="text-muted fw-normal">(tampil di kartu kampanye)</small>
          </label>
          <textarea name="description" class="form-control" rows="3" style="border-radius:8px;"
                    placeholder="Penjelasan singkat yang menarik perhatian donatur...">{{ old('description') }}</textarea>
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold">Deskripsi Lengkap
            <small class="text-muted fw-normal">(tampil di halaman detail)</small>
          </label>
          <textarea name="content" class="form-control" rows="7" style="border-radius:8px;"
                    placeholder="Ceritakan latar belakang, tujuan, dan rencana penggunaan dana...">{{ old('content') }}</textarea>
        </div>
      </div>
    </div>

    {{-- Target Type --}}
    <div class="card shadow-sm mb-4" style="border:none;border-radius:14px;">
      <div class="card-header bg-white border-bottom py-3 px-4" style="border-radius:14px 14px 0 0;">
        <h6 class="mb-0 fw-bold" style="font-size:.875rem;"><i class="bi bi-bullseye me-2 text-primary"></i>Tipe Donasi</h6>
      </div>
      <div class="card-body p-4">
        <div class="row g-3 mb-3">
          <div class="col-md-6">
            <label class="d-flex align-items-start gap-3 p-3 rounded-3 cursor-pointer target-option"
                   style="border:2px solid #e2e8f0;cursor:pointer;" for="typeTarget">
              <input type="radio" name="has_target" value="1" id="typeTarget"
                     {{ old('has_target', '0') === '1' ? 'checked' : '' }}
                     class="form-check-input mt-1 flex-shrink-0" />
              <div>
                <div class="fw-semibold" style="font-size:.875rem;">Dengan Target Dana</div>
                <div class="text-muted" style="font-size:.78rem;">Progress bar dan persentase akan ditampilkan ke publik</div>
              </div>
            </label>
          </div>
          <div class="col-md-6">
            <label class="d-flex align-items-start gap-3 p-3 rounded-3 cursor-pointer target-option"
                   style="border:2px solid #e2e8f0;cursor:pointer;" for="typeOpen">
              <input type="radio" name="has_target" value="0" id="typeOpen"
                     {{ old('has_target', '0') === '0' ? 'checked' : '' }}
                     class="form-check-input mt-1 flex-shrink-0" />
              <div>
                <div class="fw-semibold" style="font-size:.875rem;">Donasi Terbuka</div>
                <div class="text-muted" style="font-size:.78rem;">Tanpa batas target, tampilkan total terkumpul saja</div>
              </div>
            </label>
          </div>
        </div>

        <div id="targetField" style="{{ old('has_target', '0') === '1' ? '' : 'display:none;' }}">
          <label class="form-label fw-semibold">Jumlah Target Dana (Rp) <span class="text-danger">*</span></label>
          <div class="input-group">
            <span class="input-group-text" style="border-radius:8px 0 0 8px;background:#f8fafc;">Rp</span>
            <input type="number" name="target_amount" class="form-control" min="100000" step="50000"
                   value="{{ old('target_amount') }}" style="border-radius:0 8px 8px 0;"
                   placeholder="10000000" />
          </div>
          <div class="form-text">Minimal Rp 100.000</div>
        </div>
        {{-- Hidden zero when no target --}}
        <input type="hidden" name="target_amount_zero" value="0" />
      </div>
    </div>

    {{-- Period --}}
    <div class="card shadow-sm" style="border:none;border-radius:14px;">
      <div class="card-header bg-white border-bottom py-3 px-4" style="border-radius:14px 14px 0 0;">
        <h6 class="mb-0 fw-bold" style="font-size:.875rem;"><i class="bi bi-calendar3 me-2 text-primary"></i>Periode Kampanye</h6>
      </div>
      <div class="card-body p-4">
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label fw-semibold" style="font-size:.875rem;">Tanggal Mulai</label>
            <input type="date" name="start_date" class="form-control" value="{{ old('start_date', now()->format('Y-m-d')) }}" style="border-radius:8px;" />
          </div>
          <div class="col-md-6">
            <label class="form-label fw-semibold" style="font-size:.875rem;">Tanggal Berakhir
              <small class="text-muted fw-normal">(opsional)</small>
            </label>
            <input type="date" name="end_date" class="form-control" value="{{ old('end_date') }}" style="border-radius:8px;" />
            <div class="form-text">Kosongkan jika kampanye tidak memiliki batas waktu</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-4">
    {{-- Sidebar Settings --}}
    <div class="card shadow-sm mb-4" style="border:none;border-radius:14px;">
      <div class="card-header bg-white border-bottom py-3 px-4" style="border-radius:14px 14px 0 0;">
        <h6 class="mb-0 fw-bold" style="font-size:.875rem;">Pengaturan</h6>
      </div>
      <div class="card-body p-4">
        <div class="mb-3">
          <label class="form-label fw-semibold" style="font-size:.875rem;">Status Kampanye <span class="text-danger">*</span></label>
          <select name="status" class="form-select form-select-sm" style="border-radius:8px;">
            <option value="active">✅ Aktif — Langsung tampil di publik</option>
            <option value="paused">⏸ Dijeda — Disimpan tapi tidak tampil</option>
            <option value="completed">🏁 Selesai</option>
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold" style="font-size:.875rem;">Kategori</label>
          <select name="category_id" class="form-select form-select-sm" style="border-radius:8px;">
            <option value="">-- Tanpa Kategori --</option>
            @foreach($categories as $cat)
              <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold" style="font-size:.875rem;">Wilayah</label>
          <select name="region" class="form-select form-select-sm" style="border-radius:8px;">
            <option value="">Seluruh Indonesia</option>
            @foreach(\App\Models\Article::REGIONS as $val => $label)
              <option value="{{ $val }}" {{ old('region') === $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
          </select>
        </div>
        <div class="form-check form-switch mb-1">
          <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="isFeatured" {{ old('is_featured') ? 'checked' : '' }}>
          <label class="form-check-label" for="isFeatured" style="font-size:.875rem;">
            <i class="bi bi-star-fill text-warning me-1"></i>Tampilkan sebagai Unggulan
          </label>
        </div>
        <div class="form-text mb-4">Kampanye unggulan ditampilkan paling atas di halaman donasi</div>
        <button type="submit" class="btn btn-primary w-100" style="border-radius:10px;">
          <i class="bi bi-floppy me-2"></i>Simpan & Publikasikan
        </button>
      </div>
    </div>

    {{-- Thumbnail --}}
    <div class="card shadow-sm" style="border:none;border-radius:14px;">
      <div class="card-header bg-white border-bottom py-3 px-4" style="border-radius:14px 14px 0 0;">
        <h6 class="mb-0 fw-bold" style="font-size:.875rem;"><i class="bi bi-image me-2"></i>Foto Kampanye</h6>
      </div>
      <div class="card-body p-4">
        <div id="thumbPreview" class="mb-3 d-none">
          <img id="thumbImg" src="" class="img-fluid rounded-3 w-100" style="height:150px;object-fit:cover;" />
        </div>
        <input type="file" name="thumbnail" class="form-control form-control-sm" accept="image/*"
               style="border-radius:8px;" id="thumbInput" onchange="previewThumb(this)" />
        <div class="form-text">JPG, PNG, WebP. Maks. 2MB. Rasio 16:9 terbaik.</div>
      </div>
    </div>
  </div>
</div>
</form>

@push('scripts')
<script>
// Target type toggle
document.querySelectorAll('input[name="has_target"]').forEach(radio => {
  radio.addEventListener('change', function () {
    const field = document.getElementById('targetField');
    const input = field.querySelector('input[name="target_amount"]');
    if (this.value === '1') {
      field.style.display = '';
      input.required = true;
    } else {
      field.style.display = 'none';
      input.required = false;
      input.value = '';
    }
  });
});

// Style selected radio card
document.querySelectorAll('.target-option').forEach(label => {
  const radio = label.querySelector('input[type="radio"]');
  function updateStyle() {
    document.querySelectorAll('.target-option').forEach(l => {
      l.style.borderColor = '#e2e8f0';
      l.style.background  = '';
    });
    if (radio.checked) {
      label.style.borderColor = '#1A5276';
      label.style.background  = '#f0f6ff';
    }
  }
  radio.addEventListener('change', () => {
    document.querySelectorAll('.target-option').forEach(l => {
      l.style.borderColor = '#e2e8f0';
      l.style.background  = '';
    });
    label.style.borderColor = '#1A5276';
    label.style.background  = '#f0f6ff';
  });
  if (radio.checked) updateStyle();
});

// Thumbnail preview
function previewThumb(input) {
  if (input.files && input.files[0]) {
    const reader = new FileReader();
    reader.onload = e => {
      document.getElementById('thumbImg').src = e.target.result;
      document.getElementById('thumbPreview').classList.remove('d-none');
    };
    reader.readAsDataURL(input.files[0]);
  }
}
</script>
@endpush
@endsection
