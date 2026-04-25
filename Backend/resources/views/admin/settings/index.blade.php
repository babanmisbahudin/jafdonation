@extends('admin.layouts.app')
@section('title', 'Pengaturan Website')
@section('page-title', 'Pengaturan Website')

@section('content')

{{-- ================================================================
     Main settings form. Program & Hero delete/toggle buttons use the
     HTML `form` attribute to target standalone forms declared at the
     bottom, so their _method=DELETE/PATCH fields never pollute this
     form's submission.
     ================================================================ --}}
<form id="settingsForm" action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
@csrf

<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h5 class="fw-bold mb-1">Pengaturan Website</h5>
    <p class="text-muted mb-0" style="font-size:.85rem;">Kelola semua konten halaman beranda</p>
  </div>
  <button type="submit" class="btn btn-primary d-flex align-items-center gap-2" style="border-radius:10px;">
    <i class="bi bi-floppy"></i> Simpan Semua
  </button>
</div>

{{-- Tabs --}}
<ul class="nav nav-tabs mb-4" id="settingTabs">
  <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#tab-general">Umum</a></li>
  <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-mission">Misi</a></li>
  <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-campaign">Kampanye</a></li>
  <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-donate">Donasi</a></li>
  <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-video">Video YouTube</a></li>
  <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-program">Program</a></li>
  <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-hero">Hero Slider</a></li>
</ul>

<div class="tab-content">

  {{-- GENERAL --}}
  <div class="tab-pane fade show active" id="tab-general">
    <div class="card shadow-sm" style="border:none;border-radius:14px;">
      <div class="card-body p-4">
        <div class="row g-3">
          @foreach($settings['general'] ?? [] as $key => $setting)
            <div class="{{ $setting->type === 'textarea' ? 'col-12' : 'col-md-6' }}">
              @include('admin.settings._field', ['setting' => $setting])
            </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>

  {{-- MISSION --}}
  <div class="tab-pane fade" id="tab-mission">
    <div class="card shadow-sm" style="border:none;border-radius:14px;">
      <div class="card-body p-4">
        <div class="row g-3">
          @foreach($settings['mission'] ?? [] as $key => $setting)
            <div class="{{ in_array($setting->type, ['textarea','image']) ? 'col-12' : 'col-md-6' }}">
              @include('admin.settings._field', ['setting' => $setting])
            </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>

  {{-- CAMPAIGN --}}
  <div class="tab-pane fade" id="tab-campaign">
    <div class="card shadow-sm" style="border:none;border-radius:14px;">
      <div class="card-body p-4">
        <div class="row g-3">
          @foreach($settings['campaign'] ?? [] as $key => $setting)
            <div class="{{ in_array($setting->type, ['textarea','image']) ? 'col-12' : 'col-md-6' }}">
              @include('admin.settings._field', ['setting' => $setting])
            </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>

  {{-- DONATE --}}
  <div class="tab-pane fade" id="tab-donate">
    <div class="card shadow-sm" style="border:none;border-radius:14px;">
      <div class="card-body p-4">
        <div class="row g-3">
          @foreach($settings['donate'] ?? [] as $key => $setting)
            <div class="{{ $setting->type === 'textarea' ? 'col-12' : 'col-md-6' }}">
              @include('admin.settings._field', ['setting' => $setting])
            </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>


  {{-- VIDEO YOUTUBE --}}
  <div class="tab-pane fade" id="tab-video">
    <div class="card shadow-sm" style="border:none;border-radius:14px;">
      <div class="card-body p-4">
        <p class="text-muted mb-3" style="font-size:.85rem;">Masukkan URL YouTube lengkap, contoh: <code>https://www.youtube.com/watch?v=xxxxx</code></p>
        <div class="row g-3">
          @foreach($settings['video'] ?? [] as $key => $setting)
            <div class="col-md-6">
              @include('admin.settings._field', ['setting' => $setting])
            </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>

  {{-- PROGRAM — delete buttons reference standalone forms below via form="..." --}}
  <div class="tab-pane fade" id="tab-program">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <p class="text-muted mb-0" style="font-size:.85rem;">Program yang dicentang <strong>Unggulan</strong> akan tampil di homepage (maks. 4)</p>
      <a href="{{ route('admin.programs.create') }}" class="btn btn-primary btn-sm d-flex align-items-center gap-2" style="border-radius:8px;">
        <i class="bi bi-plus-lg"></i> Tambah Program
      </a>
    </div>
    <div class="d-flex flex-column gap-3">
      @forelse($programs as $program)
      <div class="card shadow-sm" style="border:none;border-radius:14px;border-left:4px solid {{ $program->is_featured ? '#22c55e' : '#94a3b8' }} !important;">
        <div class="card-body p-3">
          <div class="row align-items-center g-3">
            <div class="col-auto">
              <div style="width:60px;height:60px;border-radius:8px;background:#f1f5f9;overflow:hidden;flex-shrink:0;">
                @if($program->thumbnail)
                  <img src="{{ asset('uploads/'.$program->thumbnail) }}" style="width:100%;height:100%;object-fit:cover;" />
                @else
                  <div class="d-flex align-items-center justify-content-center h-100">
                    <i class="bi bi-image text-muted"></i>
                  </div>
                @endif
              </div>
            </div>
            <div class="col">
              <div class="fw-bold" style="font-size:.9rem;">{{ $program->name }}</div>
              <div class="text-muted" style="font-size:.75rem;">{{ Str::limit($program->description, 60) }}</div>
              <div class="mt-1">
                <span class="badge" style="font-size:.65rem;background:{{ $program->status === 'active' ? '#22c55e' : '#94a3b8' }};">{{ $program->status }}</span>
                @if($program->is_featured)
                  <span class="badge bg-warning text-dark ms-1" style="font-size:.65rem;">Unggulan</span>
                @endif
              </div>
            </div>
            <div class="col-auto text-muted" style="font-size:.75rem;">
              <div>Tombol: <strong>{{ $program->cta_text ?: 'Dukung' }}</strong></div>
              <div class="text-truncate" style="max-width:120px;">{{ $program->cta_url ?: 'pages/donasi.html' }}</div>
            </div>
            <div class="col-auto d-flex gap-2">
              <a href="{{ route('admin.programs.edit', $program) }}" class="btn btn-sm btn-outline-primary" style="border-radius:8px;">
                <i class="bi bi-pencil"></i>
              </a>
              {{-- form="prog-del-..." submits the standalone form below, NOT settingsForm --}}
              <button type="submit" form="prog-del-{{ $program->id }}"
                      onclick="return confirm('Hapus program ini?')"
                      class="btn btn-sm btn-outline-danger" style="border-radius:8px;">
                <i class="bi bi-trash"></i>
              </button>
            </div>
          </div>
        </div>
      </div>
      @empty
      <div class="card shadow-sm" style="border:none;border-radius:14px;">
        <div class="card-body text-center py-5 text-muted">
          <i class="bi bi-grid fs-1 d-block mb-2 opacity-25"></i>
          Belum ada program. Klik <strong>Tambah Program</strong> untuk mulai.
        </div>
      </div>
      @endforelse
    </div>
  </div>

  {{-- HERO SLIDER — toggle/delete buttons reference standalone forms below --}}
  <div class="tab-pane fade" id="tab-hero">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <p class="text-muted mb-0" style="font-size:.85rem;">Kelola slide banner utama halaman beranda</p>
      <button type="button" class="btn btn-primary btn-sm d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalAddSlide" style="border-radius:8px;">
        <i class="bi bi-plus-lg"></i> Tambah Slide
      </button>
    </div>
    <div class="d-flex flex-column gap-3">
      @forelse($heroSlides as $slide)
      <div class="card shadow-sm" style="border:none;border-radius:14px;border-left:4px solid {{ $slide->is_active ? '#22c55e' : '#94a3b8' }} !important;">
        <div class="card-body p-3">
          <div class="row align-items-center g-3">
            <div class="col-auto">
              <div style="width:100px;height:60px;border-radius:8px;background:{{ $slide->bg_color }};display:flex;align-items:center;justify-content:center;overflow:hidden;flex-shrink:0;">
                @if($slide->image)
                  <img src="{{ asset('uploads/hero/'.$slide->image) }}" style="width:100%;height:100%;object-fit:cover;" />
                @else
                  <i class="bi bi-image" style="color:rgba(255,255,255,.4);font-size:20px;"></i>
                @endif
              </div>
            </div>
            <div class="col">
              @if($slide->tag)
                <span class="badge mb-1" style="background:{{ $slide->tag_color }};font-size:.65rem;">{{ $slide->tag }}</span>
              @endif
              <div class="fw-bold" style="font-size:.9rem;">{{ $slide->title_1 }} {{ $slide->title_2 }}</div>
              @if($slide->description)
                <div class="text-muted mt-1" style="font-size:.75rem;">{{ Str::limit($slide->description, 80) }}</div>
              @endif
            </div>
            <div class="col-auto text-center">
              <div class="text-muted" style="font-size:.7rem;">Urutan</div>
              <div class="fw-bold">{{ $slide->sort_order + 1 }}</div>
            </div>
            <div class="col-auto">
              {{-- form="hero-toggle-..." submits the standalone toggle form below --}}
              <button type="submit" form="hero-toggle-{{ $slide->id }}"
                      class="btn btn-sm {{ $slide->is_active ? 'btn-success' : 'btn-outline-secondary' }}"
                      style="border-radius:20px;font-size:.72rem;padding:3px 12px;">
                {{ $slide->is_active ? 'Aktif' : 'Nonaktif' }}
              </button>
            </div>
            <div class="col-auto d-flex gap-2">
              <button type="button" class="btn btn-sm btn-outline-primary" style="border-radius:8px;" onclick='openEditSlide({{ json_encode($slide) }})'>
                <i class="bi bi-pencil"></i>
              </button>
              {{-- form="hero-del-..." submits the standalone delete form below --}}
              <button type="submit" form="hero-del-{{ $slide->id }}"
                      onclick="return confirm('Hapus slide ini?')"
                      class="btn btn-sm btn-outline-danger" style="border-radius:8px;">
                <i class="bi bi-trash"></i>
              </button>
            </div>
          </div>
        </div>
      </div>
      @empty
      <div class="card shadow-sm" style="border:none;border-radius:14px;">
        <div class="card-body text-center py-5 text-muted">
          <i class="bi bi-images fs-1 d-block mb-2 opacity-25"></i>
          Belum ada slide. Klik <strong>Tambah Slide</strong> untuk mulai.
        </div>
      </div>
      @endforelse
    </div>
  </div>

</div>{{-- /tab-content --}}
</form>{{-- /settingsForm --}}


{{-- ================================================================
     Standalone forms for program delete and hero toggle/delete.
     Declared OUTSIDE #settingsForm so their _method inputs are never
     submitted with the settings save action.
     ================================================================ --}}

@foreach($programs as $program)
<form id="prog-del-{{ $program->id }}"
      action="{{ route('admin.programs.destroy', $program) }}"
      method="POST" style="display:none;">
  @csrf @method('DELETE')
</form>
@endforeach

@foreach($heroSlides as $slide)
<form id="hero-toggle-{{ $slide->id }}"
      action="{{ route('admin.hero.toggle', $slide) }}"
      method="POST" style="display:none;">
  @csrf
</form>
<form id="hero-del-{{ $slide->id }}"
      action="{{ route('admin.hero.destroy', $slide) }}"
      method="POST" style="display:none;">
  @csrf @method('DELETE')
</form>
@endforeach


{{-- Modal Tambah Slide --}}
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

{{-- Modal Edit Slide --}}
<div class="modal fade" id="modalEditSlide" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content" style="border-radius:16px;border:none;">
      <form id="editSlideForm" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        @include('admin.hero._form', ['slide' => null, 'title' => 'Edit Slide'])
      </form>
    </div>
  </div>
</div>

@push('scripts')
<script>
function openEditSlide(slide) {
  const form = document.getElementById('editSlideForm');
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
  form.querySelector('[name="is_active"]').checked = !!slide.is_active;
  const preview = document.getElementById('editImagePreview');
  if (slide.image_url) { preview.src = slide.image_url; preview.style.display = 'block'; }
  else { preview.style.display = 'none'; }
  new bootstrap.Modal(document.getElementById('modalEditSlide')).show();
}
</script>
@endpush

@endsection
