<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login — Team Activity Tracker</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <style>
    body {
      background: linear-gradient(135deg, #1e293b 0%, #2563eb 100%);
      min-height: 100vh;
      display: flex; align-items: center; justify-content: center;
    }
    .login-card {
      width: 100%; max-width: 420px;
      border: none; border-radius: 1rem;
      box-shadow: 0 20px 60px rgba(0,0,0,.3);
    }
    .login-header {
      background: #2563eb; color: #fff;
      border-radius: 1rem 1rem 0 0;
      padding: 2rem; text-align: center;
    }
    .login-header .app-icon {
      width: 56px; height: 56px; background: rgba(255,255,255,.2);
      border-radius: 50%; display: flex; align-items: center;
      justify-content: center; margin: 0 auto 1rem;
      font-size: 1.5rem;
    }
    .login-body { padding: 2rem; }
    .form-control:focus { border-color: #2563eb; box-shadow: 0 0 0 .2rem rgba(37,99,235,.25); }
    .btn-login { background: #2563eb; border-color: #2563eb; }
    .btn-login:hover { background: #1d4ed8; border-color: #1d4ed8; }
  </style>
</head>
<body>
<div class="login-card card">
  <div class="login-header">
    <div class="app-icon"><i class="bi bi-activity"></i></div>
    <h5 class="mb-1 fw-bold">Team Activity Tracker</h5>
    <p class="mb-0 opacity-75 small">Applications Support Team</p>
  </div>
  <div class="login-body">
    @if($errors->any())
      <div class="alert alert-danger py-2 small">
        <i class="bi bi-exclamation-circle me-1"></i>{{ $errors->first() }}
      </div>
    @endif
    @if(session('error'))
      <div class="alert alert-danger py-2 small">
        <i class="bi bi-exclamation-circle me-1"></i>{{ session('error') }}
      </div>
    @endif

    <form method="POST" action="{{ route('login.submit') }}">
      @csrf
      <div class="mb-3">
        <label class="form-label fw-semibold small">Email Address</label>
        <div class="input-group">
          <span class="input-group-text"><i class="bi bi-envelope"></i></span>
          <input type="email" name="email" class="form-control"
                 placeholder="you@company.com"
                 value="{{ old('email') }}" required autofocus>
        </div>
      </div>
      <div class="mb-4">
        <label class="form-label fw-semibold small">Password</label>
        <div class="input-group">
          <span class="input-group-text"><i class="bi bi-lock"></i></span>
          <input type="password" name="password" id="password"
                 class="form-control" placeholder="••••••••" required>
          <button type="button" class="btn btn-outline-secondary"
                  onclick="togglePwd()">
            <i class="bi bi-eye" id="pwd-eye"></i>
          </button>
        </div>
      </div>
      <button type="submit" class="btn btn-login btn-primary w-100 fw-semibold">
        <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
      </button>
    </form>
  </div>
</div>

<script>
function togglePwd() {
  const inp = document.getElementById('password');
  const eye = document.getElementById('pwd-eye');
  if (inp.type === 'password') {
    inp.type = 'text';
    eye.className = 'bi bi-eye-slash';
  } else {
    inp.type = 'password';
    eye.className = 'bi bi-eye';
  }
}
</script>
</body>
</html>
