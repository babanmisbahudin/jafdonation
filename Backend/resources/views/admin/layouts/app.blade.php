<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>@yield('title', 'Dashboard') – Jaf Donation CMS</title>

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />

  <style>
    :root {
      --sidebar-bg:       #0f172a;
      --sidebar-active:   #1A5276;
      --sidebar-hover:    #1e293b;
      --sidebar-width:    260px;
      --sidebar-collapsed: 64px;
      --topbar-height:    64px;
      --primary:          #1A5276;
      --secondary:        #2980B9;
      --gold:             #C8A44A;
    }
    * { font-family: 'Inter', sans-serif; }

    /* ---- Sidebar ---- */
    #sidebar {
      position: fixed; top: 0; left: 0;
      width: var(--sidebar-width); height: 100vh;
      background: var(--sidebar-bg);
      overflow: hidden;
      z-index: 1040;
      transition: width .25s ease, transform .25s ease;
      display: flex; flex-direction: column;
    }
    #sidebar-nav {
      flex: 1 1 auto;
      overflow-y: auto; overflow-x: hidden;
    }
    #sidebar .sidebar-brand {
      padding: 1.25rem 1rem 1rem;
      border-bottom: 1px solid rgba(255,255,255,.08);
      text-align: center;
    }
    #sidebar .sidebar-brand img { transition: height .25s; }
    #sidebar .nav-label {
      padding: .5rem 1.5rem .25rem;
      font-size: .65rem; font-weight: 600;
      letter-spacing: .08em; text-transform: uppercase;
      color: rgba(255,255,255,.35);
      white-space: nowrap; overflow: hidden;
      transition: opacity .2s;
    }
    #sidebar .nav-link {
      color: rgba(255,255,255,.65);
      padding: .6rem 1.5rem;
      border-radius: 0; font-size: .875rem;
      display: flex; align-items: center; gap: .75rem;
      transition: background .15s, color .15s;
      white-space: nowrap;
    }
    #sidebar .nav-link:hover { background: var(--sidebar-hover); color: #fff; }
    #sidebar .nav-link.active { background: var(--sidebar-active); color: #fff; }
    #sidebar .nav-link i { width: 18px; flex-shrink: 0; text-align: center; font-size: 1rem; }
    #sidebar .nav-link .badge { margin-left: auto; }
    #sidebar .nav-link .link-text { transition: opacity .2s; }

    /* ---- Collapsed state (desktop) ---- */
    body.sidebar-collapsed #sidebar { width: var(--sidebar-collapsed); }
    body.sidebar-collapsed #main-content { margin-left: var(--sidebar-collapsed); }
    /* Semua elemen yang disembunyikan saat collapse — pakai display:none agar tidak makan ruang */
    body.sidebar-collapsed #sidebar .sidebar-brand img,
    body.sidebar-collapsed #sidebar .nav-label,
    body.sidebar-collapsed #sidebar .link-text,
    body.sidebar-collapsed #sidebar .nav-link .badge,
    body.sidebar-collapsed #sidebar .collapse,
    body.sidebar-collapsed #sidebar .sidebar-user-info { display: none !important; }

    body.sidebar-collapsed #sidebar .sidebar-brand { padding: .25rem 0; }

    /* Setiap nav-link jadi kotak 64×44px, ikon di tengah */
    body.sidebar-collapsed #sidebar .nav-link {
      display:         flex        !important;
      align-items:     center      !important;
      justify-content: center      !important;
      padding:         .65rem 0    !important;
      gap:             0           !important;
      width:           var(--sidebar-collapsed) !important;
      box-sizing:      border-box  !important;
    }
    /* Reset lebar ikon bawaan (18px) dan pusatkan */
    body.sidebar-collapsed #sidebar .nav-link i {
      width:       1.15rem  !important;
      font-size:   1.15rem  !important;
      text-align:  center   !important;
      margin:      0        !important;
      padding:     0        !important;
      flex-shrink: 0        !important;
    }
    body.sidebar-collapsed #sidebar .user-bottom {
      padding:         .6rem 0 !important;
      justify-content: center  !important;
    }
    body.sidebar-collapsed #sidebar .user-bottom .rounded-circle { margin: 0 auto; }

    /* ---- Main ---- */
    #main-content {
      margin-left: var(--sidebar-width);
      min-height: 100vh;
      background: #f1f5f9;
      transition: margin-left .25s ease;
    }
    #topbar {
      height: var(--topbar-height);
      background: #fff;
      border-bottom: 1px solid #e2e8f0;
      position: sticky; top: 0; z-index: 1030;
      padding: 0 1.5rem;
    }
    .page-content { padding: 1.75rem; }
    #sidebarCollapseBtn { transition: transform .25s; }
    body.sidebar-collapsed #sidebarCollapseBtn { transform: rotate(180deg); }

    /* ---- Cards ---- */
    .stat-card { border: none; border-radius: 16px; transition: transform .2s; }
    .stat-card:hover { transform: translateY(-3px); }

    /* ---- Alert flash ---- */
    .flash-alert { position: fixed; top: 80px; right: 20px; z-index: 9999; min-width: 320px; }

    /* ---- Table ---- */
    .table th { font-size: .75rem; font-weight: 600; letter-spacing: .05em; text-transform: uppercase; color: #64748b; background: #f8fafc; }
    .table td { font-size: .875rem; vertical-align: middle; }

    /* ---- Badge status ---- */
    .badge-status { font-size: .7rem; padding: .3em .65em; border-radius: 6px; font-weight: 600; }

    /* ---- Mobile: hide sidebar by default ---- */
    @media (max-width: 991.98px) {
      #sidebar { transform: translateX(-100%); width: var(--sidebar-width) !important; }
      #sidebar.show { transform: translateX(0); }
      #main-content { margin-left: 0 !important; }
      #sidebarCollapseBtn { display: none !important; }
    }
  </style>
  @stack('styles')
</head>
<body>

  <!-- ===== SIDEBAR ===== -->
  <nav id="sidebar">
    <!-- Brand -->
    <div class="sidebar-brand">
      <img src="{{ asset('images/logo.png') }}" alt="JAF Logo" style="height:38px;width:auto;object-fit:contain;filter:invert(1);mix-blend-mode:screen;" />
    </div>

    <!-- Nav -->
    <div id="sidebar-nav">
    <ul class="nav flex-column py-3">
      <li class="nav-label">Utama</li>
      <li class="nav-item">
        <a href="{{ route('admin.dashboard') }}" title="Dashboard"
           class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
          <i class="bi bi-speedometer2"></i>
          <span class="link-text">Dashboard</span>
        </a>
      </li>

      <li class="nav-label mt-2">Konten</li>

      {{-- Artikel + Komentar submenu --}}
      @php
        $draftCount  = \App\Models\Article::where('status','draft')->count();
        $pendingCom  = \App\Models\Comment::where('is_spam', false)->where('is_approved', false)->count();
        $spamCom     = \App\Models\Comment::where('is_spam', true)->count();
        $artikelOpen = request()->routeIs('admin.articles*') || request()->routeIs('admin.comments*');
      @endphp
      <li class="nav-item">
        <a href="#artikelMenu" data-bs-toggle="collapse" title="Artikel"
           class="nav-link d-flex align-items-center {{ $artikelOpen ? '' : 'collapsed' }}"
           style="border-radius:0;">
          <i class="bi bi-newspaper"></i>
          <span class="link-text flex-grow-1 ms-1">Artikel</span>
          @if($draftCount > 0)
            <span class="badge bg-warning text-dark me-1 link-text">{{ $draftCount }}</span>
          @endif
          <i class="bi bi-chevron-down link-text" style="font-size:.65rem;transition:transform .2s;"></i>
        </a>
        <div id="artikelMenu" class="collapse {{ $artikelOpen ? 'show' : '' }}">
          <ul class="nav flex-column" style="background:rgba(0,0,0,.15);">
            <li class="nav-item">
              <a href="{{ route('admin.articles.index') }}" title="Daftar Artikel"
                 class="nav-link ps-5 py-2 {{ request()->routeIs('admin.articles*') ? 'active' : '' }}"
                 style="font-size:.82rem;">
                <i class="bi bi-list-ul me-2"></i><span class="link-text">Daftar Artikel</span>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.comments.index') }}" title="Komentar"
                 class="nav-link ps-5 py-2 {{ request()->routeIs('admin.comments*') ? 'active' : '' }}"
                 style="font-size:.82rem;">
                <i class="bi bi-chat-left-text me-2"></i><span class="link-text">Komentar</span>
                @if($pendingCom + $spamCom > 0)
                  <span class="badge bg-danger ms-1 link-text">{{ $pendingCom + $spamCom }}</span>
                @endif
              </a>
            </li>
          </ul>
        </div>
      </li>
      <li class="nav-item">
        <a href="{{ route('admin.categories.index') }}" title="Kategori"
           class="nav-link {{ request()->routeIs('admin.categories*') ? 'active' : '' }}">
          <i class="bi bi-tags-fill"></i>
          <span class="link-text">Kategori</span>
        </a>
      </li>
      <li class="nav-item">
        <a href="{{ route('admin.programs.index') }}" title="Program"
           class="nav-link {{ request()->routeIs('admin.programs*') ? 'active' : '' }}">
          <i class="bi bi-grid-fill"></i>
          <span class="link-text">Program</span>
        </a>
      </li>
      <li class="nav-item">
        <a href="{{ route('admin.gallery.index') }}" title="Galeri"
           class="nav-link {{ request()->routeIs('admin.gallery*') ? 'active' : '' }}">
          <i class="bi bi-images"></i>
          <span class="link-text">Galeri</span>
        </a>
      </li>

      <li class="nav-item">
        <a href="{{ route('admin.settings.index') }}" title="Pengaturan"
           class="nav-link {{ request()->routeIs('admin.settings*') ? 'active' : '' }}">
          <i class="bi bi-gear-fill"></i>
          <span class="link-text">Pengaturan</span>
        </a>
      </li>

      <li class="nav-label mt-2">Donasi & Relawan</li>
      <li class="nav-item">
        <a href="{{ route('admin.donations.index') }}" title="Data Donasi"
           class="nav-link {{ request()->routeIs('admin.donations*') ? 'active' : '' }}">
          <i class="bi bi-heart-fill"></i>
          <span class="link-text">Data Donasi</span>
          @php $pendingDon = \App\Models\Donation::where('payment_status','pending')->count(); @endphp
          @if($pendingDon > 0)
            <span class="badge bg-danger link-text">{{ $pendingDon }}</span>
          @endif
        </a>
      </li>
      <li class="nav-item">
        <a href="{{ route('admin.volunteers.index') }}" title="Data Relawan"
           class="nav-link {{ request()->routeIs('admin.volunteers*') ? 'active' : '' }}">
          <i class="bi bi-people-fill"></i>
          <span class="link-text">Data Relawan</span>
          @php $pendingVol = \App\Models\Volunteer::where('status','pending')->count(); @endphp
          @if($pendingVol > 0)
            <span class="badge bg-warning text-dark link-text">{{ $pendingVol }}</span>
          @endif
        </a>
      </li>

      <li class="nav-label mt-2">Sistem</li>
      <li class="nav-item">
        <a href="{{ route('admin.users.index') }}" title="Pengguna Admin"
           class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
          <i class="bi bi-person-gear"></i>
          <span class="link-text">Pengguna Admin</span>
        </a>
      </li>
    </ul>
    </div>{{-- /sidebar-nav --}}

    <!-- User info bottom -->
    <div class="p-3 border-top flex-shrink-0" style="background:var(--sidebar-bg);border-color:rgba(255,255,255,.08)!important;">
      <div class="d-flex align-items-center gap-2 user-bottom">
        <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center flex-shrink-0"
             style="width:34px;height:34px;">
          <i class="bi bi-person-fill text-white" style="font-size:.85rem;"></i>
        </div>
        <div class="flex-grow-1 overflow-hidden sidebar-user-info">
          <div class="text-white fw-semibold text-truncate" style="font-size:.8rem;">{{ auth()->user()->name }}</div>
          <div class="text-white-50 text-truncate" style="font-size:.7rem;">{{ auth()->user()->role }}</div>
        </div>
        <form action="{{ route('logout') }}" method="POST" class="sidebar-user-info">
          @csrf
          <button type="submit" class="btn btn-link p-0" title="Logout">
            <i class="bi bi-box-arrow-right text-white-50"></i>
          </button>
        </form>
      </div>
    </div>
  </nav>

  <!-- ===== MAIN ===== -->
  <div id="main-content">

    <!-- Topbar -->
    <div id="topbar" class="d-flex align-items-center gap-3">
      {{-- Mobile toggle --}}
      <button class="btn btn-link p-0 d-lg-none" id="sidebarToggle">
        <i class="bi bi-list fs-4 text-dark"></i>
      </button>
      {{-- Desktop collapse/expand --}}
      <button class="btn btn-link p-0 d-none d-lg-flex align-items-center text-muted" id="sidebarCollapseBtn" title="Collapse / Expand Sidebar">
        <i class="bi bi-layout-sidebar fs-5"></i>
      </button>
      <div class="fw-semibold text-dark ms-1" style="font-size:.95rem;">
        @yield('page-title', 'Dashboard')
      </div>
      <div class="ms-auto d-flex align-items-center gap-2">
        <span class="badge bg-primary-subtle text-primary px-3 py-2" style="font-size:.75rem;">
          <i class="bi bi-circle-fill me-1" style="font-size:.45rem;"></i>
          {{ config('midtrans.server_key') ? 'Midtrans Aktif' : 'Midtrans Belum Dikonfigurasi' }}
        </span>
        <div class="vr mx-1"></div>
        <span class="text-muted" style="font-size:.8rem;">{{ now()->format('d M Y') }}</span>
      </div>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
      <div class="flash-alert alert alert-success alert-dismissible shadow-sm" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif
    @if(session('error'))
      <div class="flash-alert alert alert-danger alert-dismissible shadow-sm" role="alert">
        <i class="bi bi-exclamation-circle-fill me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif

    <!-- Page Content -->
    <div class="page-content">
      @yield('content')
    </div>

    <!-- Footer -->
    <footer style="padding:.9rem 1.75rem;border-top:1px solid #e2e8f0;background:#fff;font-size:.75rem;color:#94a3b8;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.5rem;">
      <span>&copy; {{ date('Y') }} Jatiwangi Art Factory — Hak Cipta Dilindungi</span>
      <span>CMS build by <a href="https://github.com/babanmisbahudin" target="_blank" style="color:#1A5276;text-decoration:none;font-weight:600;">himisbah</a></span>
    </footer>
  </div>

  <!-- Sidebar Overlay (mobile) -->
  <div id="sidebarOverlay" class="d-none d-lg-none"
       style="position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:1039;"></div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const sidebar         = document.getElementById('sidebar');
    const sidebarOverlay  = document.getElementById('sidebarOverlay');
    const sidebarToggle   = document.getElementById('sidebarToggle');
    const collapseBtn     = document.getElementById('sidebarCollapseBtn');

    // ── Mobile toggle ────────────────────────────────────────────────
    sidebarToggle?.addEventListener('click', () => {
      sidebar.classList.toggle('show');
      sidebarOverlay.classList.toggle('d-none');
    });
    sidebarOverlay?.addEventListener('click', () => {
      sidebar.classList.remove('show');
      sidebarOverlay.classList.add('d-none');
    });

    // ── Desktop collapse / expand ────────────────────────────────────
    function applySidebarState(collapsed) {
      document.body.classList.toggle('sidebar-collapsed', collapsed);
      localStorage.setItem('sidebarCollapsed', collapsed ? '1' : '0');
    }

    // Restore saved state on load
    if (localStorage.getItem('sidebarCollapsed') === '1') {
      applySidebarState(true);
    }

    collapseBtn?.addEventListener('click', () => {
      const isCollapsed = document.body.classList.contains('sidebar-collapsed');
      applySidebarState(!isCollapsed);
    });

    // ── Klik icon saat collapsed → expand dulu, lalu navigasi ────────
    sidebar.addEventListener('click', (e) => {
      if (!document.body.classList.contains('sidebar-collapsed')) return;

      const link = e.target.closest('.nav-link');
      if (!link) return;

      e.preventDefault();
      e.stopPropagation();

      applySidebarState(false);

      // Setelah animasi expand selesai, jalankan aksi link
      setTimeout(() => {
        const href    = link.getAttribute('href');
        const toggle  = link.getAttribute('data-bs-toggle');

        if (toggle === 'collapse') {
          // submenu toggle → buka collapse-nya
          const targetId = href;
          const target   = document.querySelector(targetId);
          if (target) new bootstrap.Collapse(target, { toggle: true });
        } else if (href && href !== '#') {
          window.location.href = href;
        }
      }, 270);
    });

    // ── Auto-dismiss flash after 4s ──────────────────────────────────
    setTimeout(() => {
      document.querySelectorAll('.flash-alert').forEach(el => {
        bootstrap.Alert.getOrCreateInstance(el).close();
      });
    }, 4000);
  </script>
  @stack('scripts')
</body>
</html>
