@extends('admin.layouts.app')
@section('title', 'Buat Artikel')
@section('page-title', 'Buat Artikel Baru')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet" />
<style>
  .ql-container { min-height: 340px; font-size: .9rem; font-family: 'Inter', sans-serif; border-radius: 0 0 10px 10px !important; }
  .ql-toolbar { border-radius: 10px 10px 0 0 !important; background: #f8fafc; }
  .ql-editor { min-height: 320px; }
  .ql-editor img { max-width: 100%; border-radius: 8px; margin: 8px 0; }
  .thumbnail-preview { max-height: 200px; object-fit: cover; border-radius: 10px; }
  .form-control, .form-select { border-radius: 8px; }
  #uploadProgress { display:none; }
</style>
@endpush

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h5 class="fw-bold mb-1">Buat Artikel Baru</h5>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-0" style="font-size:.8rem;">
        <li class="breadcrumb-item"><a href="{{ route('admin.articles.index') }}">Artikel</a></li>
        <li class="breadcrumb-item active">Buat Baru</li>
      </ol>
    </nav>
  </div>
  <a href="{{ route('admin.articles.index') }}" class="btn btn-outline-secondary btn-sm" style="border-radius:8px;">
    <i class="bi bi-arrow-left me-1"></i>Kembali
  </a>
</div>

<form action="{{ route('admin.articles.store') }}" method="POST" enctype="multipart/form-data" id="articleForm">
@csrf
<div class="row g-4">

  <div class="col-lg-8">
    <div class="card shadow-sm mb-4" style="border:none;border-radius:14px;">
      <div class="card-body p-4">
        <div class="mb-4">
          <label class="form-label fw-semibold">Judul Artikel <span class="text-danger">*</span></label>
          <input type="text" name="title" class="form-control form-control-lg @error('title') is-invalid @enderror"
                 placeholder="Masukkan judul artikel yang menarik..." value="{{ old('title') }}" required />
          @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-4">
          <label class="form-label fw-semibold">Ringkasan / Excerpt
            <small class="text-muted fw-normal">(tampil di kartu berita)</small>
          </label>
          <textarea name="excerpt" class="form-control" rows="3" maxlength="500"
                    placeholder="Ringkasan singkat yang menarik perhatian pembaca...">{{ old('excerpt') }}</textarea>
        </div>

        <div class="mb-4">
          <label class="form-label fw-semibold">
            Tag / Kata Kunci
            <small class="text-muted fw-normal">(pisahkan dengan koma — membantu SEO)</small>
          </label>
          <input type="text" name="tags" id="tagsInput" class="form-control"
                 placeholder="contoh: zakat, infaq, pendidikan, anak yatim"
                 value="{{ old('tags') }}" />
          @if($popularTags->isNotEmpty())
            <div class="mt-2 d-flex flex-wrap gap-1" id="tagSuggestions">
              @foreach($popularTags as $tag)
                <button type="button" class="btn btn-outline-secondary btn-sm py-0 px-2"
                        style="font-size:.75rem;border-radius:50px;"
                        onclick="addTag('{{ $tag->name }}')">{{ $tag->name }}</button>
              @endforeach
            </div>
          @endif
        </div>

        <div class="mb-2">
          <label class="form-label fw-semibold d-flex align-items-center justify-content-between">
            <span>Konten Artikel <span class="text-danger">*</span></span>
            <span id="uploadProgress" class="badge bg-info text-dark" style="font-size:.72rem;">
              <span class="spinner-border spinner-border-sm me-1"></span>Mengunggah gambar...
            </span>
          </label>
          {{-- Quill editor container --}}
          <div id="editor" style="background:#fff;">{!! old('content') !!}</div>
          <input type="hidden" name="content" id="contentInput" value="{{ old('content') }}" />
          @error('content')<div class="text-danger mt-1" style="font-size:.875rem;">{{ $message }}</div>@enderror
        </div>
      </div>
    </div>

    <div class="card shadow-sm" style="border:none;border-radius:14px;">
      <div class="card-header bg-white border-bottom py-3 px-4" style="border-radius:14px 14px 0 0;">
        <h6 class="mb-0 fw-bold"><i class="bi bi-search me-2 text-primary"></i>SEO Meta</h6>
      </div>
      <div class="card-body p-4">
        <div class="mb-3">
          <label class="form-label fw-semibold" style="font-size:.875rem;">Meta Title</label>
          <input type="text" name="meta_title" class="form-control form-control-sm" value="{{ old('meta_title') }}" />
        </div>
        <div>
          <label class="form-label fw-semibold" style="font-size:.875rem;">Meta Description</label>
          <textarea name="meta_description" class="form-control form-control-sm" rows="2" maxlength="500">{{ old('meta_description') }}</textarea>
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-4">
    <div class="card shadow-sm mb-4" style="border:none;border-radius:14px;">
      <div class="card-header bg-white border-bottom py-3 px-4" style="border-radius:14px 14px 0 0;">
        <h6 class="mb-0 fw-bold"><i class="bi bi-send me-2 text-primary"></i>Publikasi</h6>
      </div>
      <div class="card-body p-4">
        <div class="mb-3">
          <label class="form-label fw-semibold" style="font-size:.875rem;">Status <span class="text-danger">*</span></label>
          <select name="status" class="form-select form-select-sm">
            @foreach($statuses as $val => $label)
              <option value="{{ $val }}" {{ old('status', 'draft') === $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold" style="font-size:.875rem;">Tanggal Publikasi</label>
          <input type="datetime-local" name="published_at" class="form-control form-control-sm" value="{{ old('published_at') }}" />
        </div>
        <div class="form-check form-switch mb-3">
          <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="isFeatured">
          <label class="form-check-label fw-semibold" for="isFeatured" style="font-size:.875rem;">
            <i class="bi bi-star-fill text-warning me-1"></i>Artikel Unggulan
          </label>
        </div>
        <button type="submit" class="btn btn-primary w-100" style="border-radius:10px;">
          <i class="bi bi-send me-2"></i>Simpan Artikel
        </button>
      </div>
    </div>

    <div class="card shadow-sm mb-4" style="border:none;border-radius:14px;">
      <div class="card-header bg-white border-bottom py-3 px-4" style="border-radius:14px 14px 0 0;">
        <h6 class="mb-0 fw-bold"><i class="bi bi-tags me-2 text-primary"></i>Klasifikasi</h6>
      </div>
      <div class="card-body p-4">
        <div class="mb-3">
          <label class="form-label fw-semibold" style="font-size:.875rem;">Kategori</label>
          <select name="category_id" class="form-select form-select-sm">
            <option value="">-- Pilih Kategori --</option>
            @foreach($categories as $cat)
              <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
          </select>
        </div>
        <div>
          <label class="form-label fw-semibold" style="font-size:.875rem;">Wilayah</label>
          <select name="region" class="form-select form-select-sm">
            <option value="">-- Pilih Wilayah --</option>
            @foreach($regions as $val => $label)
              <option value="{{ $val }}" {{ old('region') === $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
          </select>
        </div>
      </div>
    </div>

    <div class="card shadow-sm" style="border:none;border-radius:14px;">
      <div class="card-header bg-white border-bottom py-3 px-4" style="border-radius:14px 14px 0 0;">
        <h6 class="mb-0 fw-bold"><i class="bi bi-image me-2 text-primary"></i>Thumbnail Artikel</h6>
      </div>
      <div class="card-body p-4">
        <div id="thumbPreviewWrap" class="mb-3 d-none">
          <img id="thumbPreview" src="" class="thumbnail-preview w-100" alt="Preview" />
        </div>
        <input type="file" name="thumbnail" id="thumbnailInput" class="form-control form-control-sm"
               accept="image/*" onchange="previewThumb(this)" style="border-radius:8px;" />
        <div class="form-text">Semua format gambar diterima. Otomatis dikonversi ke WebP.</div>
      </div>
    </div>
  </div>

</div>
</form>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
<script>
const UPLOAD_URL  = '{{ route('admin.upload-image') }}';
const CSRF_TOKEN  = '{{ csrf_token() }}';

// Quill setup
const quill = new Quill('#editor', {
  theme: 'snow',
  placeholder: 'Tulis konten artikel di sini... Klik ikon gambar untuk menyisipkan foto.',
  modules: {
    toolbar: {
      container: [
        [{ header: [1, 2, 3, false] }],
        ['bold', 'italic', 'underline', 'strike'],
        [{ color: [] }, { background: [] }],
        [{ list: 'ordered' }, { list: 'bullet' }],
        ['blockquote', 'code-block'],
        ['link', 'image'],
        ['clean'],
      ],
      handlers: { image: imageUploadHandler },
    },
  },
});

// Populate from old() value if any
@if(old('content'))
quill.clipboard.dangerouslyPasteHTML(document.getElementById('editor').innerHTML);
@endif

// Image upload handler
function imageUploadHandler() {
  const input = document.createElement('input');
  input.type = 'file';
  input.accept = 'image/*';
  input.click();

  input.onchange = async () => {
    const file = input.files[0];
    if (!file) return;

    document.getElementById('uploadProgress').style.display = '';
    const fd = new FormData();
    fd.append('image', file);
    fd.append('_token', CSRF_TOKEN);

    try {
      const res  = await fetch(UPLOAD_URL, { method: 'POST', body: fd });
      const data = await res.json();
      if (data.url) {
        const range = quill.getSelection(true);
        quill.insertEmbed(range.index, 'image', data.url);
        quill.setSelection(range.index + 1);
      }
    } catch (e) {
      alert('Gagal mengunggah gambar. Silakan coba lagi.');
    } finally {
      document.getElementById('uploadProgress').style.display = 'none';
    }
  };
}

// On submit: copy Quill HTML to hidden input
document.getElementById('articleForm').addEventListener('submit', function () {
  document.getElementById('contentInput').value = quill.root.innerHTML;
});

// Tag suggestion helper
function addTag(name) {
  const input = document.getElementById('tagsInput');
  const existing = input.value.split(',').map(t => t.trim()).filter(Boolean);
  if (!existing.includes(name)) {
    input.value = existing.length ? existing.join(', ') + ', ' + name : name;
  }
}

// Thumbnail preview
function previewThumb(input) {
  if (input.files && input.files[0]) {
    const reader = new FileReader();
    reader.onload = e => {
      document.getElementById('thumbPreview').src = e.target.result;
      document.getElementById('thumbPreviewWrap').classList.remove('d-none');
    };
    reader.readAsDataURL(input.files[0]);
  }
}
</script>
@endpush
