<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ config('app.name', 'Laravel') }} | Registration Page</title>

  <!-- Favicons -->
  <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="32x32">
  <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
  <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
  <link rel="manifest" href="{{ asset('site.webmanifest') }}">

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/icheck-bootstrap/3.0.1/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('adminlte/css/adminlte.min.css') }}">
  
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Inter', sans-serif;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      min-height: 100vh;
      overflow-x: hidden;
      position: relative;
    }

    /* Animated background particles */
    .bg-particles {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      overflow: hidden;
      z-index: 0;
      pointer-events: none;
    }

    .particle {
      position: absolute;
      background: rgba(255, 255, 255, 0.1);
      border-radius: 50%;
      animation: float 20s infinite ease-in-out;
    }

    .particle:nth-child(1) { width: 80px; height: 80px; left: 10%; animation-delay: 0s; }
    .particle:nth-child(2) { width: 60px; height: 60px; left: 20%; animation-delay: 2s; }
    .particle:nth-child(3) { width: 100px; height: 100px; left: 35%; animation-delay: 4s; }
    .particle:nth-child(4) { width: 50px; height: 50px; left: 50%; animation-delay: 1s; }
    .particle:nth-child(5) { width: 90px; height: 90px; left: 65%; animation-delay: 3s; }
    .particle:nth-child(6) { width: 70px; height: 70px; left: 80%; animation-delay: 5s; }

    @keyframes float {
      0%, 100% { transform: translateY(0) rotate(0deg); opacity: 0.3; }
      50% { transform: translateY(-100vh) rotate(360deg); opacity: 0.6; }
    }

    .register-box {
      position: relative;
      z-index: 10;
    }

    .card {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(20px);
      border: 1px solid rgba(255, 255, 255, 0.3);
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    }
  </style>
</head>
<body class="hold-transition register-page">
<!-- Animated Background Particles -->
<div class="bg-particles">
  <div class="particle"></div>
  <div class="particle"></div>
  <div class="particle"></div>
  <div class="particle"></div>
  <div class="particle"></div>
  <div class="particle"></div>
</div>

<div class="register-box">
  <div class="register-logo">
    <a href="{{ route('dashboard') }}">
      <img src="{{ asset('images/LogoSISCONINT.png') }}" alt="SISCONINT Logo" style="max-height: 100px;">
    </a>
  </div>

  <div class="card">
    <div class="card-body register-card-body">
      <p class="login-box-msg">Registrar una nueva membresía</p>

      <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="input-group mb-3">
          <input type="text" class="form-control" placeholder="Nombre completo" name="name" value="{{ old('name') }}" required autofocus>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        @error('name')
          <div class="text-danger mb-2">{{ $message }}</div>
        @enderror
        <div class="input-group mb-3">
          <input type="email" class="form-control" placeholder="Correo electrónico" name="email" value="{{ old('email') }}" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        @error('email')
          <div class="text-danger mb-2">{{ $message }}</div>
        @enderror
        <div class="input-group mb-3">
          <input type="password" class="form-control" placeholder="Contraseña" name="password" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        @error('password')
          <div class="text-danger mb-2">{{ $message }}</div>
        @enderror
        <div class="input-group mb-3">
          <input type="password" class="form-control" placeholder="Repite la contraseña" name="password_confirmation" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        @error('password_confirmation')
          <div class="text-danger mb-2">{{ $message }}</div>
        @enderror
        <div class="row">
          <div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox" id="agreeTerms" name="terms" value="agree">
              <label for="agreeTerms">
               Acepto los <a href="#">términos</a>
              </label>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block">Registrar</button>
          </div>
          <!-- /.col -->
        </div>
      </form>

      <a href="{{ route('login') }}" class="text-center">Ya tengo una membresía</a>
    </div>
    <!-- /.form-box -->
  </div><!-- /.card -->
</div>
<!-- /.register-box -->

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap 4 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="{{ asset('adminlte/js/adminlte.min.js') }}"></script>
</body>
</html>
