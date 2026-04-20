@extends('admin.layouts.app')
@section('title', 'Kategori')
@section('page-title', 'Manajemen Kategori')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h5 class="fw-bold mb-1">Manajemen Kategori</h5>
    <p class="text-muted mb-0" style="font-size:.85rem;">Kelola kategori artikel dan program</p>
  </div>
  <button class="btn btn-primary d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalCreate"
          style="border-radius:10px;">
    <i class="bi bi-plus-lg"></i> Tambah Kategori
  </button>
</div>

<div class="card shadow-sm" style="border:none;border-radius:14px;">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover mb-0">
        <thead>
          <tr>
            <th class="ps-4">Kategori</th>
            <th>Icon</th>
            <th>Warna</th>
            <th>Artikel</th>
            <th>Program</th>
            <th>Status</th>
            <th class="text-end pe-4">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($categories as $cat)
          <tr>
            <td class="ps-4">
              <div class="fw-semibold" style="font-size:.875rem;">{{ $cat->name }}</div>
              <div class="text-muted" style="font-size:.75rem;">{{ $cat->description ?? '–' }}</div>
            </td>
            <td><i class="{{ $cat->icon }}" style="color:{{ $cat->color }};font-size:1.2rem;"></i></td>
            <td>
              <div class="d-flex align-items-center gap-2">
                <div class="rounded" style="width:20px;height:20px;background:{{ $cat->color }};"></div>
                <code style="font-size:.75rem;">{{ $cat->color }}</code>
              </div>
            </td>
            <td><span class="badge bg-primary-subtle text-primary">{{ $cat->articles_count }}</span></td>
            <td><span class="badge bg-success-subtle text-success">{{ $cat->programs_count }}</span></td>
            <td>
              <span class="badge {{ $cat->is_active ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }}">
                {{ $cat->is_active ? 'Aktif' : 'Nonaktif' }}
              </span>
            </td>
            <td class="text-end pe-4">
              <button class="btn btn-sm btn-outline-primary me-1"
                      onclick="editCategory({{ $cat->id }}, '{{ $cat->name }}', '{{ addslashes($cat->description) }}', '{{ $cat->color }}', '{{ $cat->icon }}', {{ $cat->is_active ? 1 : 0 }})"
                      style="border-radius:7px;">
                <i class="bi bi-pencil"></i>
              </button>
              <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST" class="d-inline"
                    onsubmit="return confirm('Hapus kategori ini?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-sm btn-outline-danger" style="border-radius:7px;">
                  <i class="bi bi-trash"></i>
                </button>
              </form>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="7" class="text-center text-muted py-5">Belum ada kategori</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
  @if($categories->hasPages())
  <div class="card-footer bg-white py-3 px-4" style="border-radius:0 0 14px 14px;">{{ $categories->links() }}</div>
  @endif
</div>

{{-- Modal Create --}}
<div class="modal fade" id="modalCreate" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="border-radius:16px;border:none;">
      <form action="{{ route('admin.categories.store') }}" method="POST">
        @csrf
        <div class="modal-header border-bottom px-4 py-3">
          <h6 class="modal-title fw-bold">Tambah Kategori</h6>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body px-4 py-4">
          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:.875rem;">Nama <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" required style="border-radius:8px;" />
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:.875rem;">Deskripsi</label>
            <textarea name="description" class="form-control" rows="2" style="border-radius:8px;"></textarea>
          </div>
          <div class="row g-3">
            <div class="col-6">
              <label class="form-label fw-semibold" style="font-size:.875rem;">Warna</label>
              <input type="color" name="color" class="form-control form-control-color" value="#1A5276" style="height:42px;border-radius:8px;" />
            </div>
            <div class="col-6">
              <label class="form-label fw-semibold" style="font-size:.875rem;">Icon (Bootstrap)</label>
              <input type="text" name="icon" class="form-control" placeholder="bi bi-tag-fill" value="bi bi-tag-fill" style="border-radius:8px;" />
            </div>
          </div>
          <div class="form-check form-switch mt-3">
            <input class="form-check-input" type="checkbox" name="is_active" value="1" checked id="createActive">
            <label class="form-check-label" for="createActive" style="font-size:.875rem;">Aktif</label>
          </div>
        </div>
        <div class="modal-footer border-top px-4 py-3">
          <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal" style="border-radius:8px;">Batal</button>
          <button type="submit" class="btn btn-primary btn-sm" style="border-radius:8px;">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- Modal Edit --}}
<div class="modal fade" id="modalEdit" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="border-radius:16px;border:none;">
      <form id="editForm" method="POST">
        @csrf @method('PUT')
        <div class="modal-header border-bottom px-4 py-3">
          <h6 class="modal-title fw-bold">Edit Kategori</h6>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body px-4 py-4">
          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:.875rem;">Nama</label>
            <input type="text" name="name" id="editName" class="form-control" required style="border-radius:8px;" />
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:.875rem;">Deskripsi</label>
            <textarea name="description" id="editDesc" class="form-control" rows="2" style="border-radius:8px;"></textarea>
          </div>
          <div class="row g-3">
            <div class="col-6">
              <label class="form-label fw-semibold" style="font-size:.875rem;">Warna</label>
              <input type="color" name="color" id="editColor" class="form-control form-control-color" style="height:42px;border-radius:8px;" />
            </div>
            <div class="col-6">
              <label class="form-label fw-semibold" style="font-size:.875rem;">Icon</label>
              <input type="text" name="icon" id="editIcon" class="form-control" style="border-radius:8px;" />
            </div>
          </div>
          <div class="form-check form-switch mt-3">
            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="editActive">
            <label class="form-check-label" for="editActive" style="font-size:.875rem;">Aktif</label>
          </div>
        </div>
        <div class="modal-footer border-top px-4 py-3">
          <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal" style="border-radius:8px;">Batal</button>
          <button type="submit" class="btn btn-primary btn-sm" style="border-radius:8px;">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
  function editCategory(id, name, desc, color, icon, isActive) {
    document.getElementById('editForm').action = `/admin/categories/${id}`;
    document.getElementById('editName').value = name;
    document.getElementById('editDesc').value = desc;
    document.getElementById('editColor').value = color;
    document.getElementById('editIcon').value = icon;
    document.getElementById('editActive').checked = isActive === 1;
    new bootstrap.Modal(document.getElementById('modalEdit')).show();
  }
</script>
@endpush
