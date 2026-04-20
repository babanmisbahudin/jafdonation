@extends('admin.layouts.app')
@section('title', 'Galeri')
@section('page-title', 'Manajemen Galeri')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h5 class="fw-bold mb-1">Manajemen Galeri</h5>
    <p class="text-muted mb-0" style="font-size:.85rem;">Kelola foto dan video dokumentasi</p>
  </div>
  <button class="btn btn-primary d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalUpload"
          style="border-radius:10px;">
    <i class="bi bi-cloud-upload"></i> Upload Media
  </button>
</div>

{{-- Filter Tabs --}}
<div class="card shadow-sm mb-4" style="border:none;border-radius:14px;">
  <div class="card-body py-3">
    <form method="GET" class="row g-2 align-items-center">
      <div class="col-auto">
        <div class="btn-group btn-group-sm" role="group">
          <a href="{{ route('admin.gallery.index') }}"
             class="btn {{ !request('type') ? 'btn-primary' : 'btn-outline-secondary' }}" style="border-radius:8px 0 0 8px;">
            Semua
          </a>
          <a href="{{ route('admin.gallery.index', ['type' => 'image']) }}"
             class="btn {{ request('type') === 'image' ? 'btn-primary' : 'btn-outline-secondary' }}">
            <i class="bi bi-image me-1"></i>Foto
          </a>
          <a href="{{ route('admin.gallery.index', ['type' => 'video']) }}"
             class="btn {{ request('type') === 'video' ? 'btn-primary' : 'btn-outline-secondary' }}" style="border-radius:0 8px 8px 0;">
            <i class="bi bi-play-circle me-1"></i>Video
          </a>
        </div>
      </div>
      <div class="col-md-3 ms-auto">
        <select name="category" class="form-select form-select-sm" onchange="this.form.submit()" style="border-radius:8px;">
          <option value="">Semua Kategori</option>
          @foreach($categories as $cat)
            <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
          @endforeach
        </select>
      </div>
    </form>
  </div>
</div>

{{-- Grid --}}
<div class="row g-3">
  @forelse($galleries as $media)
  <div class="col-md-4 col-lg-3 col-6">
    <div class="card shadow-sm h-100" style="border:none;border-radius:12px;overflow:hidden;">
      <div class="position-relative" style="height:160px;background:#f1f5f9;">
        @if($media->file_type === 'image')
          <img src="{{ asset('uploads/'.$media->file_path) }}" alt="{{ $media->title }}"
               style="width:100%;height:100%;object-fit:cover;" />
        @else
          <div class="d-flex align-items-center justify-content-center h-100">
            <div class="text-center">
              <i class="bi bi-play-circle-fill text-primary" style="font-size:2.5rem;"></i>
              <div class="text-muted mt-1" style="font-size:.7rem;">Video</div>
            </div>
          </div>
        @endif
        @if($media->is_featured)
          <span class="position-absolute top-0 end-0 m-2 badge bg-warning text-dark" style="font-size:.6rem;">⭐</span>
        @endif
        <div class="position-absolute bottom-0 start-0 end-0 p-2"
             style="background:linear-gradient(transparent,rgba(0,0,0,.6));">
          <div class="text-white fw-semibold text-truncate" style="font-size:.75rem;">{{ $media->title }}</div>
        </div>
      </div>
      <div class="card-body p-2 d-flex justify-content-between align-items-center">
        <span class="text-muted" style="font-size:.72rem;">{{ $media->category?->name ?? 'Umum' }}</span>
        <form action="{{ route('admin.gallery.destroy', $media) }}" method="POST"
              onsubmit="return confirm('Hapus media ini?')">
          @csrf @method('DELETE')
          <button type="submit" class="btn btn-sm btn-outline-danger p-1" style="border-radius:6px;line-height:1;">
            <i class="bi bi-trash" style="font-size:.75rem;"></i>
          </button>
        </form>
      </div>
    </div>
  </div>
  @empty
  <div class="col-12">
    <div class="card shadow-sm" style="border:none;border-radius:14px;">
      <div class="card-body text-center py-5 text-muted">
        <i class="bi bi-images fs-1 d-block mb-2 text-light"></i>
        Belum ada media. Klik <strong>Upload Media</strong> untuk mulai.
      </div>
    </div>
  </div>
  @endforelse
</div>

@if($galleries->hasPages())
<div class="d-flex justify-content-center mt-4">{{ $galleries->links() }}</div>
@endif

{{-- Modal Upload --}}
<div class="modal fade" id="modalUpload" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="border-radius:16px;border:none;">
      <form action="{{ route('admin.gallery.store') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
        @csrf
        <div class="modal-header border-bottom px-4 py-3">
          <h6 class="modal-title fw-bold">Upload Media</h6>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body px-4 py-4">
          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:.875rem;">Judul <span class="text-danger">*</span></label>
            <input type="text" name="title" class="form-control" required style="border-radius:8px;" />
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:.875rem;">Tipe Media</label>
            <select name="file_type" id="fileTypeSelect" class="form-select" onchange="toggleMediaType()" style="border-radius:8px;">
              <option value="image">Foto / Gambar</option>
              <option value="video">Video (URL)</option>
            </select>
          </div>
          <div id="imageUploadSection" class="mb-3">
            <label class="form-label fw-semibold" style="font-size:.875rem;">Upload Foto</label>
            <input type="file" name="file" class="form-control" accept="image/*" style="border-radius:8px;" />
            <div class="form-text">JPG, PNG, WebP. Maks. 5MB</div>
          </div>
          <div id="videoUrlSection" class="mb-3 d-none">
            <label class="form-label fw-semibold" style="font-size:.875rem;">URL Video (YouTube/Vimeo)</label>
            <input type="url" name="video_url" class="form-control" placeholder="https://youtube.com/watch?v=..." style="border-radius:8px;" />
          </div>
          <div class="row g-3">
            <div class="col-6">
              <label class="form-label fw-semibold" style="font-size:.875rem;">Kategori</label>
              <select name="category_id" class="form-select form-select-sm" style="border-radius:8px;">
                <option value="">-- Pilih --</option>
                @foreach($categories as $cat)
                  <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-6">
              <label class="form-label fw-semibold" style="font-size:.875rem;">Wilayah</label>
              <select name="region" class="form-select form-select-sm" style="border-radius:8px;">
                <option value="">Semua</option>
                @foreach(\App\Models\Article::REGIONS as $val => $label)
                  <option value="{{ $val }}">{{ $label }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="form-check form-switch mt-3">
            <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="mediaFeatured">
            <label class="form-check-label" for="mediaFeatured" style="font-size:.875rem;">Tampilkan sebagai Unggulan</label>
          </div>
        </div>
        <div class="modal-footer border-top px-4 py-3">
          <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal" style="border-radius:8px;">Batal</button>
          <button type="submit" class="btn btn-primary btn-sm" style="border-radius:8px;">
            <i class="bi bi-cloud-upload me-1"></i>Upload
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
  function toggleMediaType() {
    const type = document.getElementById('fileTypeSelect').value;
    document.getElementById('imageUploadSection').classList.toggle('d-none', type === 'video');
    document.getElementById('videoUrlSection').classList.toggle('d-none', type === 'image');
  }
</script>
@endpush
