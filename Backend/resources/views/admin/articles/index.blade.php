@extends('admin.layouts.app')
@section('title', 'Manajemen Artikel')
@section('page-title', 'Artikel')

@section('content')

{{-- Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h5 class="fw-bold mb-1">Manajemen Artikel</h5>
    <p class="text-muted mb-0" style="font-size:.85rem;">Kelola semua konten artikel dan berita</p>
  </div>
  <a href="{{ route('admin.articles.create') }}" class="btn btn-primary d-flex align-items-center gap-2"
     style="border-radius:10px;">
    <i class="bi bi-plus-lg"></i> Artikel Baru
  </a>
</div>

{{-- Filter --}}
<div class="card shadow-sm mb-4" style="border:none;border-radius:14px;">
  <div class="card-body py-3">
    <form action="{{ route('admin.articles.index') }}" method="GET" class="row g-2 align-items-end">
      <div class="col-md-4">
        <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari judul artikel..."
               value="{{ request('search') }}" style="border-radius:8px;" />
      </div>
      <div class="col-md-2">
        <select name="category" class="form-select form-select-sm" style="border-radius:8px;">
          <option value="">Semua Kategori</option>
          @foreach($categories as $cat)
            <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-2">
        <select name="status" class="form-select form-select-sm" style="border-radius:8px;">
          <option value="">Semua Status</option>
          <option value="draft"     {{ request('status') === 'draft'     ? 'selected' : '' }}>Draft</option>
          <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
          <option value="archived"  {{ request('status') === 'archived'  ? 'selected' : '' }}>Archived</option>
        </select>
      </div>
      <div class="col-md-2">
        <select name="region" class="form-select form-select-sm" style="border-radius:8px;">
          <option value="">Semua Wilayah</option>
          @foreach(\App\Models\Article::REGIONS as $key => $label)
            <option value="{{ $key }}" {{ request('region') === $key ? 'selected' : '' }}>{{ $label }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-2 d-flex gap-2">
        <button type="submit" class="btn btn-primary btn-sm flex-grow-1" style="border-radius:8px;">Filter</button>
        <a href="{{ route('admin.articles.index') }}" class="btn btn-outline-secondary btn-sm" style="border-radius:8px;">Reset</a>
      </div>
    </form>
  </div>
</div>

{{-- Table --}}
<div class="card shadow-sm" style="border:none;border-radius:14px;">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover mb-0">
        <thead>
          <tr>
            <th class="ps-4" style="width:40%;">Artikel</th>
            <th>Kategori</th>
            <th>Wilayah</th>
            <th>Status</th>
            <th>Views</th>
            <th>Komentar</th>
            <th>Tanggal</th>
            <th class="text-end pe-4">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($articles as $article)
          <tr>
            <td class="ps-4">
              <div class="d-flex align-items-start gap-3">
                <div class="rounded-2 overflow-hidden flex-shrink-0"
                     style="width:48px;height:48px;background:#EBF5FB;display:flex;align-items:center;justify-content:center;">
                  @if($article->thumbnail)
                    <img src="{{ asset('uploads/'.$article->thumbnail) }}" alt="" style="width:48px;height:48px;object-fit:cover;" />
                  @else
                    <i class="bi bi-image text-muted"></i>
                  @endif
                </div>
                <div>
                  <div class="fw-semibold text-dark" style="font-size:.875rem;">
                    {{ Str::limit($article->title, 55) }}
                    @if($article->is_featured)
                      <span class="badge bg-warning text-dark ms-1" style="font-size:.6rem;">⭐ Unggulan</span>
                    @endif
                  </div>
                  <div class="text-muted" style="font-size:.75rem;">{{ $article->author?->name ?? '–' }}</div>
                </div>
              </div>
            </td>
            <td>
              @if($article->category)
                <span class="badge rounded-pill px-3"
                      style="background:{{ $article->category->color }}20;color:{{ $article->category->color }};font-size:.72rem;font-weight:600;">
                  {{ $article->category->name }}
                </span>
              @else
                <span class="text-muted" style="font-size:.8rem;">–</span>
              @endif
            </td>
            <td class="text-muted" style="font-size:.825rem;">{{ $article->region_label ?? '–' }}</td>
            <td>
              <span class="badge badge-status
                {{ $article->status === 'published' ? 'bg-success-subtle text-success' :
                   ($article->status === 'draft' ? 'bg-warning-subtle text-warning' : 'bg-secondary-subtle text-secondary') }}">
                {{ ucfirst($article->status) }}
              </span>
            </td>
            <td class="text-muted" style="font-size:.825rem;">{{ number_format($article->views) }}</td>
            <td>
              @php
                $comTotal   = $article->comments->count();
                $comPending = $article->comments->where('is_approved', false)->where('is_spam', false)->count();
                $comSpam    = $article->comments->where('is_spam', true)->count();
              @endphp
              <a href="{{ route('admin.comments.index', ['article_id' => $article->id]) }}"
                 class="text-decoration-none d-flex align-items-center gap-1" style="font-size:.82rem;">
                <i class="bi bi-chat-left-text text-muted"></i>
                <span class="text-dark">{{ $comTotal }}</span>
                @if($comPending > 0)
                  <span class="badge bg-warning text-dark" style="font-size:.65rem;">{{ $comPending }} pending</span>
                @endif
                @if($comSpam > 0)
                  <span class="badge bg-danger" style="font-size:.65rem;">{{ $comSpam }} spam</span>
                @endif
              </a>
            </td>
            <td class="text-muted" style="font-size:.775rem;">{{ $article->created_at->format('d M Y') }}</td>
            <td class="text-end pe-4">
              <div class="d-flex gap-1 justify-content-end">
                <form action="{{ route('admin.articles.toggle-featured', $article) }}" method="POST" class="d-inline">
                  @csrf @method('PATCH')
                  <button type="submit" class="btn btn-sm btn-outline-warning" title="Toggle Unggulan" style="border-radius:7px;">
                    <i class="bi bi-star{{ $article->is_featured ? '-fill' : '' }}"></i>
                  </button>
                </form>
                <a href="{{ route('admin.articles.edit', $article) }}" class="btn btn-sm btn-outline-primary" style="border-radius:7px;">
                  <i class="bi bi-pencil"></i>
                </a>
                <form action="{{ route('admin.articles.destroy', $article) }}" method="POST" class="d-inline"
                      onsubmit="return confirm('Hapus artikel ini?')">
                  @csrf @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-outline-danger" style="border-radius:7px;">
                    <i class="bi bi-trash"></i>
                  </button>
                </form>
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="7" class="text-center text-muted py-5">
              <i class="bi bi-newspaper fs-1 d-block mb-2 text-light"></i>
              Belum ada artikel. <a href="{{ route('admin.articles.create') }}">Buat artikel pertama →</a>
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
  @if($articles->hasPages())
  <div class="card-footer bg-white border-top py-3 px-4" style="border-radius:0 0 14px 14px;">
    {{ $articles->links() }}
  </div>
  @endif
</div>
@endsection
