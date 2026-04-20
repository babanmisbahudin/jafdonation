<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login – Jaf Donation CMS</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
  <style>
    * { font-family: 'Inter', sans-serif; }
    body {
      background: linear-gradient(135deg, #0f172a 0%, #1A5276 50%, #0f172a 100%);
      min-height: 100vh;
      display: flex; align-items: center; justify-content: center;
    }
    .login-card {
      background: #fff; border-radius: 20px;
      padding: 2.5rem; width: 100%; max-width: 420px;
      box-shadow: 0 25px 60px rgba(0,0,0,.35);
    }
    .login-logo {
      display: flex; justify-content: center;
      margin: 0 auto 1.25rem;
    }
    .login-logo img { height: 60px; width: auto; object-fit: contain; filter: invert(1); mix-blend-mode: screen; }
    .form-control:focus { border-color: #1A5276; box-shadow: 0 0 0 .25rem rgba(26,82,118,.15); }
    .btn-login {
      background: linear-gradient(135deg, #1A5276, #2980B9);
      border: none; color: #fff; font-weight: 600; padding: .75rem;
      border-radius: 12px; transition: opacity .2s;
    }
    .btn-login:hover { opacity: .9; color: #fff; }
    .pass-toggle { cursor: pointer; background: #f8f9fa; border-left: none; border-radius: 0 8px 8px 0; }
    .form-control { border-radius: 8px; }
    .input-group .form-control { border-radius: 8px 0 0 8px; }
  </style>
</head>
<body>
  <div class="login-card">
    <div class="login-logo">
      <img src="{{ asset('images/logo.png') }}" alt="Jatiwangi Art Factory" />
    </div>
    <h4 class="text-center fw-bold mb-1" style="color:#1A5276;">Jatiwangi Art Factory</h4>
    <p class="text-center text-muted mb-4" style="font-size:.875rem;">Masuk ke Panel CMS</p>

    @if($errors->any())
      <div class="alert alert-danger alert-sm py-2 px-3" style="font-size:.875rem;">
        <i class="bi bi-exclamation-circle me-1"></i>
        {{ $errors->first() }}
      </div>
    @endif

    <form action="{{ route('login.post') }}" method="POST">
      @csrf
      <div class="mb-3">
        <label class="form-label fw-semibold text-dark" style="font-size:.875rem;">Email</label>
        <div class="input-group">
          <span class="input-group-text bg-light border-end-0" style="border-radius:8px 0 0 8px;">
            <i class="bi bi-envelope text-muted"></i>
          </span>
          <input type="email" name="email" value="{{ old('email') }}"
                 class="form-control border-start-0 @error('email') is-invalid @enderror"
                 placeholder="admin@jafdonation.id" required autofocus
                 style="border-radius:0 8px 8px 0;" />
        </div>
      </div>
      <div class="mb-4">
        <label class="form-label fw-semibold text-dark" style="font-size:.875rem;">Password</label>
        <div class="input-group">
          <span class="input-group-text bg-light border-end-0" style="border-radius:8px 0 0 8px;">
            <i class="bi bi-lock text-muted"></i>
          </span>
          <input type="password" name="password" id="passwordInput"
                 class="form-control border-start-0 border-end-0 @error('password') is-invalid @enderror"
                 placeholder="••••••••" required />
          <span class="input-group-text pass-toggle" onclick="togglePass()">
            <i class="bi bi-eye" id="eyeIcon"></i>
          </span>
        </div>
      </div>
      <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="form-check mb-0">
          <input class="form-check-input" type="checkbox" name="remember" id="remember">
          <label class="form-check-label text-muted" for="remember" style="font-size:.8rem;">Ingat saya</label>
        </div>
      </div>
      <button type="submit" class="btn btn-login w-100">
        <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
      </button>
    </form>

    <p class="text-center mt-4 mb-0 text-muted" style="font-size:.75rem;">
      &copy; {{ date('Y') }} Jaf Donation. Panel CMS v1.0
    </p>
  </div>

  <script>
    function togglePass() {
      const input = document.getElementById('passwordInput');
      const icon  = document.getElementById('eyeIcon');
      if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'bi bi-eye-slash';
      } else {
        input.type = 'password';
        icon.className = 'bi bi-eye';
      }
    }
  </script>
</body>
</html>
