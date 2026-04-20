@extends('admin.layouts.app')
@section('title', 'Komentar Artikel')
@section('page-title', 'Komentar')

@section('content')

{{-- Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h5 class="fw-bold mb-1">
      Komentar Artikel
      @if($currentArticle)
        <span class="text-primary" style="font-weight:400;font-size:.9rem;">
          — {{ Str::limit($currentArticle->title, 50) }}
        </span>
      @endif
    </h5>
    <p class="text-muted mb-0" style="font-size:.85rem;">Moderasi komentar, deteksi spam otomatis, publish atau hapus</p>
  </div>
  <div class="d-flex gap-2">
    @if($currentArticle)
      <a href="{{ route('admin.articles.edit', $currentArticle->id) }}"
         class="btn btn-outline-secondary btn-sm" style="border-radius:8px;">
        <i class="bi bi-pencil me-1"></i>Edit Artikel
      </a>
      <a href="{{ route('admin.comments.index') }}"
         class="btn btn-outline-secondary btn-sm" style="border-radius:8px;">
        <i class="bi bi-arrow-left me-1"></i>Semua Komentar
      </a>
    @endif
    @if($stats['spam'] > 0)
    <form action="{{ route('admin.comments.destroy-spam') }}" method="POST"
          onsubmit="return confirm('Hapus semua {{ $stats['spam'] }} komentar spam sekarang?')">
      @csrf @method('DELETE')
      <button class="btn btn-danger btn-sm" style="border-radius:8px;">
        <i class="bi bi-trash-fill me-1"></i>Hapus Semua Spam ({{ $stats['spam'] }})
      </button>
    </form>
    @endif
  </div>
</div>

{{-- Stats --}}
<div class="row g-3 mb-4">
  <div class="col-6 col-md-3">
    <div class="card shadow-sm text-center p-3" style="border:none;border-radius:12px;">
      <div class="fw-bold fs-4">{{ $stats['total'] }}</div>
      <div class="text-muted" style="font-size:.8rem;">Total Komentar</div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <a href="{{ route('admin.comments.index', array_filter(['article_id' => request('article_id'), 'filter' => 'approved'])) }}"
       class="text-decoration-none">
      <div class="card shadow-sm text-center p-3 h-100"
           style="border:none;border-radius:12px;border-left:3px solid #22c55e !important;">
        <div class="fw-bold fs-4 text-success">{{ $stats['approved'] }}</div>
        <div class="text-muted" style="font-size:.8rem;">Dipublish</div>
      </div>
    </a>
  </div>
  <div class="col-6 col-md-3">
    <a href="{{ route('admin.comments.index', array_filter(['article_id' => request('article_id'), 'filter' => 'pending'])) }}"
       class="text-decoration-none">
      <div class="card shadow-sm text-center p-3 h-100"
           style="border:none;border-radius:12px;border-left:3px solid #f59e0b !important;">
        <div class="fw-bold fs-4 text-warning">{{ $stats['pending'] }}</div>
        <div class="text-muted" style="font-size:.8rem;">Menunggu Review</div>
      </div>
    </a>
  </div>
  <div class="col-6 col-md-3">
    <a href="{{ route('admin.comments.index', array_filter(['article_id' => request('article_id'), 'filter' => 'spam'])) }}"
       class="text-decoration-none">
      <div class="card shadow-sm text-center p-3 h-100"
           style="border:none;border-radius:12px;border-left:3px solid #ef4444 !important;">
        <div class="fw-bold fs-4 text-danger">{{ $stats['spam'] }}</div>
        <div class="text-muted" style="font-size:.8rem;">Spam Terdeteksi</div>
      </div>
    </a>
  </div>
</div>

{{-- Filter --}}
<div class="card shadow-sm mb-4" style="border:none;border-radius:14px;">
  <div class="card-body py-3">
    <form method="GET" class="row g-2 align-items-end">
      {{-- Filter per artikel --}}
      <div class="col-md-4">
        <label class="form-label fw-semibold" style="font-size:.78rem;">Artikel</label>
        <select name="article_id" class="form-select form-select-sm" style="border-radius:8px;"
                onchange="this.form.submit()">
          <option value="">Semua Artikel</option>
          @foreach($articles as $art)
            <option value="{{ $art->id }}" {{ request('article_id') == $art->id ? 'selected' : '' }}>
              {{ Str::limit($art->title, 60) }}
            </option>
          @endforeach
        </select>
      </div>
      <div class="col-md-3">
        <label class="form-label fw-semibold" style="font-size:.78rem;">Status</label>
        <select name="filter" class="form-select form-select-sm" style="border-radius:8px;">
          <option value="">Semua Status</option>
          <option value="pending"  {{ request('filter') === 'pending'  ? 'selected' : '' }}>Menunggu Review</option>
          <option value="approved" {{ request('filter') === 'approved' ? 'selected' : '' }}>Dipublish</option>
          <option value="spam"     {{ request('filter') === 'spam'     ? 'selected' : '' }}>Spam</option>
        </select>
      </div>
      <div class="col-md-3">
        <label class="form-label fw-semibold" style="font-size:.78rem;">Cari</label>
        <input type="text" name="search" class="form-control form-control-sm"
               placeholder="Nama / isi komentar..."
               value="{{ request('search') }}" style="border-radius:8px;" />
      </div>
      <div class="col-md-2 d-flex gap-2 align-items-end">
        <button type="submit" class="btn btn-primary btn-sm flex-grow-1" style="border-radius:8px;">Filter</button>
        <a href="{{ route('admin.comments.index') }}" class="btn btn-outline-secondary btn-sm" style="border-radius:8px;">Reset</a>
      </div>
    </form>
  </div>
</div>

{{-- Comment List --}}
<div class="d-flex flex-column gap-3">
  @forelse($comments as $comment)
  @php
    $borderColor = $comment->is_spam ? '#ef4444' : ($comment->is_approved ? '#22c55e' : '#f59e0b');
  @endphp
  <div class="card shadow-sm" style="border:none;border-radius:12px;border-left:4px solid {{ $borderColor }} !important;">
    <div class="card-body p-4">

      {{-- Header --}}
      <div class="d-flex justify-content-between align-items-start mb-2 flex-wrap gap-2">
        <div>
          <span class="fw-semibold" style="font-size:.9rem;">{{ $comment->guest_name }}</span>
          @if($comment->guest_email)
            <span class="text-muted ms-2" style="font-size:.78rem;">{{ $comment->guest_email }}</span>
          @endif
          <span class="text-muted ms-2" style="font-size:.72rem;">
            <i class="bi bi-geo-alt me-1"></i>{{ $comment->ip_address }}
          </span>
        </div>
        <div class="d-flex align-items-center gap-2 flex-wrap">
          {{-- Status badge --}}
          @if($comment->is_spam)
            <span class="badge bg-danger-subtle text-danger" style="font-size:.72rem;">
              <i class="bi bi-shield-x me-1"></i>Spam
              @if($comment->spam_score) ({{ $comment->spam_score }}%) @endif
            </span>
          @elseif($comment->is_approved)
            <span class="badge bg-success-subtle text-success" style="font-size:.72rem;">
              <i class="bi bi-check-circle me-1"></i>Dipublish
            </span>
          @else
            <span class="badge bg-warning-subtle text-warning" style="font-size:.72rem;">
              <i class="bi bi-clock me-1"></i>Menunggu Review
            </span>
          @endif
          <span class="text-muted" style="font-size:.72rem;">
            {{ $comment->created_at->format('d M Y H:i') }}
          </span>
        </div>
      </div>

      {{-- Isi komentar --}}
      <div style="font-size:.875rem;background:#f8fafc;border-radius:8px;padding:10px 14px;line-height:1.7;">
        {{ $comment->content }}
      </div>

      {{-- Spam reasons --}}
      @if($comment->is_spam && $comment->spam_reasons && count($comment->spam_reasons))
      <div class="mt-2 d-flex gap-1 flex-wrap">
        <span class="text-muted me-1" style="font-size:.72rem;">Alasan spam:</span>
        @foreach($comment->spam_reasons as $reason)
          <span class="badge bg-danger-subtle text-danger" style="font-size:.7rem;">{{ $reason }}</span>
        @endforeach
      </div>
      @endif

      {{-- Footer --}}
      <div class="mt-3 d-flex align-items-center gap-2 justify-content-between flex-wrap">

        {{-- Artikel link --}}
        @if(!$currentArticle)
        <div style="font-size:.78rem;color:#64748b;">
          <i class="bi bi-newspaper me-1"></i>
          <a href="{{ route('admin.comments.index', ['article_id' => $comment->article_id]) }}"
             class="text-decoration-none text-primary">
            {{ $comment->article?->title ?? 'Artikel #'.$comment->article_id }}
          </a>
        </div>
        @else
          <div></div>
        @endif

        {{-- Action buttons --}}
        <div class="d-flex gap-1 flex-wrap">
          {{-- Setujui / Publish --}}
          @if(!$comment->is_approved || $comment->is_spam)
          <form action="{{ route('admin.comments.approve', $comment) }}" method="POST">
            @csrf @method('PATCH')
            <button class="btn btn-sm btn-success" style="border-radius:6px;font-size:.75rem;">
              <i class="bi bi-check-lg me-1"></i>Publish
            </button>
          </form>
          @endif

          {{-- Unpublish / Pending --}}
          @if($comment->is_approved && !$comment->is_spam)
          <form action="{{ route('admin.comments.unspam', $comment) }}" method="POST">
            @csrf @method('PATCH')
            <button class="btn btn-sm btn-outline-secondary" style="border-radius:6px;font-size:.75rem;">
              <i class="bi bi-eye-slash me-1"></i>Unpublish
            </button>
          </form>
          @endif

          {{-- Tandai Spam --}}
          @if(!$comment->is_spam)
          <form action="{{ route('admin.comments.spam', $comment) }}" method="POST">
            @csrf @method('PATCH')
            <button class="btn btn-sm btn-outline-warning" style="border-radius:6px;font-size:.75rem;">
              <i class="bi bi-shield-x me-1"></i>Spam
            </button>
          </form>
          @endif

          {{-- Batalkan Spam --}}
          @if($comment->is_spam)
          <form action="{{ route('admin.comments.unspam', $comment) }}" method="POST">
            @csrf @method('PATCH')
            <button class="btn btn-sm btn-outline-info" style="border-radius:6px;font-size:.75rem;">
              <i class="bi bi-shield-check me-1"></i>Bukan Spam
            </button>
          </form>
          @endif

          {{-- Hapus --}}
          <form action="{{ route('admin.comments.destroy', $comment) }}" method="POST"
                onsubmit="return confirm('Hapus komentar ini secara permanen?')">
            @csrf @method('DELETE')
            <button class="btn btn-sm btn-outline-danger" style="border-radius:6px;font-size:.75rem;">
              <i class="bi bi-trash me-1"></i>Hapus
            </button>
          </form>
        </div>
      </div>

    </div>
  </div>
  @empty
  <div class="card shadow-sm" style="border:none;border-radius:14px;">
    <div class="card-body text-center py-5 text-muted">
      <i class="bi bi-chat-left-x fs-1 d-block mb-2 opacity-25"></i>
      <div>Belum ada komentar{{ $currentArticle ? ' untuk artikel ini' : '' }}.</div>
    </div>
  </div>
  @endforelse
</div>

@if($comments->hasPages())
<div class="d-flex justify-content-center mt-4">{{ $comments->links() }}</div>
@endif

@endsection
