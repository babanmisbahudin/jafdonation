@extends('admin.layouts.app')
@section('title', 'Manajemen Pengguna')
@section('page-title', 'Manajemen Pengguna')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h5 class="fw-bold mb-1">Pengguna Admin</h5>
    <p class="text-muted mb-0" style="font-size:.85rem;">Kelola akun pengguna yang dapat mengakses CMS</p>
  </div>
  <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addUserModal" style="border-radius:8px;">
    <i class="bi bi-person-plus-fill me-1"></i>Tambah Pengguna
  </button>
</div>

<div class="card shadow-sm" style="border:none;border-radius:14px;">
  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0" style="font-size:.875rem;">
      <thead style="background:#f8fafc;font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;color:#64748b;">
        <tr>
          <th class="px-4 py-3">Pengguna</th>
          <th class="py-3">Email</th>
          <th class="py-3">Role</th>
          <th class="py-3">Status</th>
          <th class="py-3">Terdaftar</th>
          <th class="py-3 text-end px-4">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @foreach($users as $user)
        <tr>
          <td class="px-4">
            <div class="d-flex align-items-center gap-2">
              <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                   style="width:36px;height:36px;background:#e8f2ff;">
                <i class="bi bi-person-fill" style="color:#1A5276;"></i>
              </div>
              <div>
                <div class="fw-semibold">{{ $user->name }}</div>
                @if($user->id === auth()->id())
                  <span class="badge bg-primary-subtle text-primary" style="font-size:.65rem;">Anda</span>
                @endif
              </div>
            </div>
          </td>
          <td>{{ $user->email }}</td>
          <td>
            <span class="badge {{ $user->role === 'admin' ? 'bg-primary-subtle text-primary' : 'bg-secondary-subtle text-secondary' }}"
                  style="font-size:.72rem;border-radius:6px;">
              {{ ucfirst($user->role) }}
            </span>
          </td>
          <td>
            <span class="badge {{ $user->is_active ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }}"
                  style="font-size:.72rem;border-radius:6px;">
              {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
            </span>
          </td>
          <td class="text-muted" style="font-size:.78rem;">{{ $user->created_at->format('d M Y') }}</td>
          <td class="text-end px-4">
            <div class="d-flex gap-1 justify-content-end">
              <button class="btn btn-sm btn-outline-primary" style="border-radius:6px;"
                      onclick="openEdit({{ $user->id }}, '{{ addslashes($user->name) }}', '{{ $user->email }}', '{{ $user->role }}', {{ $user->is_active ? 'true' : 'false' }})"
                      title="Edit">
                <i class="bi bi-pencil"></i>
              </button>
              @if($user->id !== auth()->id())
              <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                    onsubmit="return confirm('Hapus pengguna {{ $user->name }}?')">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-outline-danger" style="border-radius:6px;" title="Hapus">
                  <i class="bi bi-trash"></i>
                </button>
              </form>
              @endif
            </div>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>

{{-- Add User Modal --}}
<div class="modal fade" id="addUserModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content" style="border-radius:16px;border:none;">
      <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf
        <div class="modal-header border-0 pb-0">
          <h5 class="modal-title fw-bold">Tambah Pengguna Baru</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:.85rem;">Nama Lengkap</label>
            <input type="text" name="name" class="form-control" required style="border-radius:8px;" />
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:.85rem;">Email</label>
            <input type="email" name="email" class="form-control" required style="border-radius:8px;" />
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:.85rem;">Password</label>
            <input type="password" name="password" class="form-control" required style="border-radius:8px;" minlength="8" />
            <div class="form-text">Minimal 8 karakter</div>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:.85rem;">Role</label>
            <select name="role" class="form-select" style="border-radius:8px;">
              <option value="editor">Editor</option>
              <option value="admin">Admin</option>
            </select>
          </div>
        </div>
        <div class="modal-footer border-0">
          <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal" style="border-radius:8px;">Batal</button>
          <button type="submit" class="btn btn-primary btn-sm" style="border-radius:8px;">
            <i class="bi bi-person-plus-fill me-1"></i>Tambah
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- Edit User Modal --}}
<div class="modal fade" id="editUserModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content" style="border-radius:16px;border:none;">
      <form id="editForm" method="POST">
        @csrf @method('PUT')
        <div class="modal-header border-0 pb-0">
          <h5 class="modal-title fw-bold">Edit Pengguna</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:.85rem;">Nama Lengkap</label>
            <input type="text" name="name" id="editName" class="form-control" required style="border-radius:8px;" />
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:.85rem;">Email</label>
            <input type="email" name="email" id="editEmail" class="form-control" required style="border-radius:8px;" />
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:.85rem;">Password Baru <span class="text-muted fw-normal">(kosongkan jika tidak diubah)</span></label>
            <input type="password" name="password" class="form-control" style="border-radius:8px;" minlength="8" />
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:.85rem;">Role</label>
            <select name="role" id="editRole" class="form-select" style="border-radius:8px;">
              <option value="editor">Editor</option>
              <option value="admin">Admin</option>
            </select>
          </div>
          <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="is_active" id="editActive" value="1" />
            <label class="form-check-label fw-semibold" for="editActive" style="font-size:.85rem;">Akun Aktif</label>
          </div>
        </div>
        <div class="modal-footer border-0">
          <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal" style="border-radius:8px;">Batal</button>
          <button type="submit" class="btn btn-primary btn-sm" style="border-radius:8px;">
            <i class="bi bi-floppy me-1"></i>Simpan
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

@push('scripts')
<script>
function openEdit(id, name, email, role, isActive) {
  document.getElementById('editName').value  = name;
  document.getElementById('editEmail').value = email;
  document.getElementById('editRole').value  = role;
  document.getElementById('editActive').checked = isActive;
  document.getElementById('editForm').action = `/admin/users/${id}`;
  new bootstrap.Modal(document.getElementById('editUserModal')).show();
}
</script>
@endpush

@endsection
