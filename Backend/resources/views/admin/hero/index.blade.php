@extends('admin.layouts.app')
@section('title', 'Hero Slider')
@section('page-title', 'Manajemen Hero Slider')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h5 class="fw-bold mb-1">Hero Slider</h5>
    <p class="text-muted mb-0" style="font-size:.85rem;">Kelola slide banner utama halaman beranda</p>
  </div>
  <button class="btn btn-primary d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalAddSlide" style="border-radius:10px;">
    <i class="bi bi-plus-lg"></i> Tambah Slide
  </button>
</div>

{{-- Slide List --}}
<div class="row g-3" id="slideList">
  @forelse($slides as $slide)
  <div class="col-12" data-id="{{ $slide->id }}">
    <div class="card shadow-sm" style="border:none;border-radius:14px;border-left:4px solid {{ $slide->is_active ? '#22c55e' : '#94a3b8' }} !important;">
      <div class="card-body p-3">
        <div class="row align-items-center g-3">

          {{-- Preview --}}
          <div class="col-auto">
            <div style="width:100px;height:60px;border-radius:8px;background:{{ $slide->bg_color }};display:flex;align-items:center;justify-content:center;overflow:hidden;flex-shrink:0;">
              @if($slide->image)
                <img src="{{ asset('uploads/hero/'.$slide->image) }}" style="width:100%;height:100%;object-fit:cover;" />
              @else
                <i class="bi bi-image" style="color:rgba(255,255,255,.4);font-size:20px;"></i>
              @endif
            </div>
          </div>

          {{-- Info --}}
          <div class="col">
            @if($slide->tag)
              <span class="badge mb-1" style="background:{{ $slide->tag_color }};font-size:.65rem;">{{ $slide->tag }}</span>
            @endif
            <div class="fw-bold" style="font-size:.9rem;line-height:1.2;">{{ $slide->title_1 }} <span style="color:{{ $slide->bg_color }}">{{ $slide->title_2 }}</span></div>
            @if($slide->description)
              <div class="text-muted mt-1" style="font-size:.75rem;">{{ Str::limit($slide->description, 80) }}</div>
            @endif
          </div>

          {{-- Sort --}}
          <div class="col-auto text-center">
            <div class="text-muted" style="font-size:.7rem;">Urutan</div>
            <div class="fw-bold">{{ $slide->sort_order + 1 }}</div>
          </div>

          {{-- Status --}}
          <div class="col-auto">
            <form action="{{ route('admin.hero.toggle', $slide) }}" method="POST">
              @csrf @method('PATCH')
              <button type="submit" class="btn btn-sm {{ $slide->is_active ? 'btn-success' : 'btn-outline-secondary' }}" style="border-radius:20px;font-size:.72rem;padding:3px 12px;">
                {{ $slide->is_active ? 'Aktif' : 'Nonaktif' }}
              </button>
            </form>
          </div>

          {{-- Actions --}}
          <div class="col-auto d-flex gap-2">
            <button class="btn btn-sm btn-outline-primary" style="border-radius:8px;"
              onclick='openEdit({{ json_encode($slide) }})'>
              <i class="bi bi-pencil"></i>
            </button>
            <form action="{{ route('admin.hero.destroy', $slide) }}" method="POST" onsubmit="return confirm('Hapus slide ini?')">
              @csrf @method('DELETE')
              <button type="submit" class="btn btn-sm btn-outline-danger" style="border-radius:8px;">
                <i class="bi bi-trash"></i>
              </button>
            </form>
          </div>

        </div>
      </div>
    </div>
  </div>
  @empty
  <div class="col-12">
    <div class="card shadow-sm" style="border:none;border-radius:14px;">
      <div class="card-body text-center py-5 text-muted">
        <i class="bi bi-images fs-1 d-block mb-2 text-light"></i>
        Belum ada slide. Klik <strong>Tambah Slide</strong> untuk mulai.
      </div>
    </div>
  </div>
  @endforelse
</div>

{{-- Modal Tambah --}}
<div class="modal fade" id="modalAddSlide" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content" style="border-radius:16px;border:none;">
      <form action="{{ route('admin.hero.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @include('admin.hero._form', ['slide' => null, 'title' => 'Tambah Slide Baru'])
      </form>
    </div>
  </div>
</div>

{{-- Modal Edit --}}
<div class="modal fade" id="modalEditSlide" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content" style="border-radius:16px;border:none;">
      <form id="editForm" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        @include('admin.hero._form', ['slide' => null, 'title' => 'Edit Slide'])
      </form>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
function openEdit(slide) {
  const form = document.getElementById('editForm');
  form.action = '/admin/hero/' + slide.id;
  form.querySelector('[name="tag"]').value         = slide.tag ?? '';
  form.querySelector('[name="tag_color"]').value   = slide.tag_color ?? '#E55A00';
  form.querySelector('[name="title_1"]').value     = slide.title_1;
  form.querySelector('[name="title_2"]').value     = slide.title_2;
  form.querySelector('[name="description"]').value = slide.description ?? '';
  form.querySelector('[name="quote"]').value       = slide.quote ?? '';
  form.querySelector('[name="author"]').value      = slide.author ?? '';
  form.querySelector('[name="bg_color"]').value    = slide.bg_color ?? '#0066cc';
  form.querySelector('[name="cta_text"]').value    = slide.cta_text ?? '';
  form.querySelector('[name="cta_url"]').value     = slide.cta_url ?? '';
  form.querySelector('[name="is_active"]').checked = slide.is_active;

  const preview = document.getElementById('editImagePreview');
  if (slide.image_url) {
    preview.src = slide.image_url;
    preview.style.display = 'block';
  } else {
    preview.style.display = 'none';
  }

  new bootstrap.Modal(document.getElementById('modalEditSlide')).show();
}
</script>
@endpush
