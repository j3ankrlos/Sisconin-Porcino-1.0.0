<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title') | Sisconint-Porcino</title>

  <!-- Favicons -->
  <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="32x32">
  <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
  <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
  <link rel="manifest" href="{{ asset('site.webmanifest') }}">

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('adminlte/css/adminlte.min.css') }}">
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  
  <style>
    /* Ocultar el emblema cuando el sidebar NO está contraído (está expandido) */
    body:not(.sidebar-collapse) .main-sidebar .brand-link .brand-logo-emblem {
      display: none !important;
    }
    
    /* Asegurar que el logo completo se oculte cuando el sidebar SÍ está contraído */
    body.sidebar-collapse .main-sidebar .brand-link .brand-logo-full {
      display: none !important;
    }

    /* Estilo para el contenedor del logo para que no se vea doble espacio */
    .brand-link {
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 56px;
      padding: .5rem .5rem !important;
    }

    .brand-logo-emblem {
      max-height: 33px;
      width: auto;
    }

    .brand-logo-full {
      max-height: 50px;
      width: auto;
    }
  </style>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="{{ route('dashboard') }}" class="nav-link">Home</a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- User Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-user"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <a href="{{ route('profile.edit') }}" class="dropdown-item">
            <i class="fas fa-user mr-2"></i> Profile
          </a>
          <div class="dropdown-divider"></div>
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="dropdown-item">
              <i class="fas fa-sign-out-alt mr-2"></i> Logout
            </button>
          </form>
        </div>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('admin.index') }}" class="brand-link">
      <!-- Emblema para cuando está colapsado -->
      <img src="{{ asset('Emblema.png') }}" alt="Sisconint-Porcino Emblema" class="brand-logo-emblem elevation-3 img-circle">
      <!-- Logo completo para cuando está expandido -->
      <span class="brand-text brand-logo-full">
        <img src="{{ asset('images/LogoSISCONINT.png') }}" alt="Sisconint-Porcino Logo" style="max-height: 50px;">
      </span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{ asset('adminlte/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">{{ Auth::user()->name }}</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item">
            <a href="{{ route('admin.index') }}" class="nav-link {{ request()->is('admin') ? 'active' : '' }}">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Panel de Control</p>
            </a>
          </li>
          <li class="nav-header">GESTIÓN POR SITIO</li>
          
          <!-- Sitio I -->
          @can('ver sitio 1')
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-map-marker-alt text-primary"></i>
              <p>
                Sitio I
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              @can('ver reemplazo')
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="nav-icon fas fa-sync-alt"></i>
                  <p>
                    Reemplazo
                    <i class="right fas fa-angle-left"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="{{ route('admin.animals.index') }}" class="nav-link {{ request()->routeIs('admin.animals.index') ? 'active' : '' }}">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Lista de activo</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ route('admin.animals.create') }}" class="nav-link {{ request()->routeIs('admin.animals.create') ? 'active' : '' }}">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Crear activo</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ route('admin.animals.batch-create') }}" class="nav-link {{ request()->routeIs('admin.animals.batch-create') ? 'active' : '' }}">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Ingreso por Lote</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="#" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Cuarentena</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="#" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Entradas a Recría</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="#" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Rearetear</p>
                    </a>
                  </li>
                </ul>
              </li>
              @endcan

              @can('ver reproduccion')
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="nav-icon fas fa-venus-mars"></i>
                  <p>
                    Reproducción
                    <i class="right fas fa-angle-left"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="#" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Detección de celos</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="#" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Montas / IA</p>
                    </a>
                  </li>
                </ul>
              </li>
              @endcan

              @can('ver maternidad')
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="nav-icon fas fa-baby"></i>
                  <p>
                    Maternidad
                    <i class="right fas fa-angle-left"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="#" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Partos</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="#" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Cierre de Salas</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="#" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Destetes</p>
                    </a>
                  </li>
                </ul>
              </li>
              @endcan

              @can('ver movimientos')
              <li class="nav-item">
                <a href="{{ route('admin.movements.index') }}" class="nav-link {{ request()->routeIs('admin.movements.index') ? 'active' : '' }}">
                  <i class="nav-icon fas fa-exchange-alt"></i>
                  <p>Movimientos</p>
                </a>
              </li>
              @endcan


              @can('ver mortalidad')
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="nav-icon fas fa-skull"></i>
                  <p>
                    Mortalidad
                    <i class="right fas fa-angle-left"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="#" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Mortalidad MPD</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="#" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Mortalidad Activos</p>
                    </a>
                  </li>
                </ul>
              </li>
              @endcan

              @can('ver reportes')
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="nav-icon fas fa-chart-bar"></i>
                  <p>
                    Reportes
                    <i class="right fas fa-angle-left"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="#" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Montas</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="#" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Partos</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="#" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Hato Reproductivo</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="#" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Hato Reemplazo</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="#" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Ingresos a Maternidad</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="#" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Mortalidad MPD</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="#" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Hembras Programadas</p>
                    </a>
                  </li>
                </ul>
              </li>
              @endcan
            </ul>
          </li>
          @endcan

          <!-- Sitio II -->
          @can('ver sitio 2')
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-map-marker-alt text-success"></i>
              <p>
                Sitio II
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="far fa-circle nav-icon text-info"></i>
                  <p>Opción Placeholder</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="nav-icon fas fa-folder"></i>
                  <p>
                    Submenú Placeholder
                    <i class="right fas fa-angle-left"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="#" class="nav-link">
                      <i class="far fa-dot-circle nav-icon"></i>
                      <p>Item de prueba</p>
                    </a>
                  </li>
                </ul>
              </li>
            </ul>
          </li>
          @endcan

          <!-- Sitio III -->
          @can('ver sitio 3')
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-map-marker-alt text-warning"></i>
              <p>
                Sitio III
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="far fa-circle nav-icon text-secondary"></i>
                  <p>Próximamente</p>
                </a>
              </li>
            </ul>
          </li>
          @endcan



          @can('ver usuarios')
          <li class="nav-header">ADMINISTRACIÓN</li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Usuarios
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('admin.users.index') }}" wire:navigate class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Lista de usuarios</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.users.create') }}" wire:navigate class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Registro de usuarios</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-user-shield"></i>
              <p>
                Roles y Permisos
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('admin.roles.index') }}" wire:navigate class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Lista de roles</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.roles.create') }}" wire:navigate class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Crear rol</p>
                </a>
              </li>
            </ul>
          </li>
          @endcan
          @can('ver empresa')
          <li class="nav-header">CONFIGURACIÓN</li>
          <li class="nav-item">
            <a href="{{ route('admin.empresa.index') }}" wire:navigate class="nav-link">
              <i class="nav-icon fas fa-cogs"></i>
              <p>Empresa Principal</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('admin.granjas.index') }}" wire:navigate class="nav-link">
              <i class="nav-icon fas fa-map-marked-alt"></i>
              <p>Granjas / Sucursales</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('admin.especies.index') }}" wire:navigate class="nav-link">
              <i class="nav-icon fas fa-paw"></i>
              <p>Especies</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('admin.razas.index') }}" wire:navigate class="nav-link">
              <i class="nav-icon fas fa-fingerprint"></i>
              <p>Razas</p>
            </a>
          </li>
          @endcan
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">@yield('title', 'Dashboard')</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Inicio</a></li>
              <li class="breadcrumb-item active">@yield('title', 'Panel de Control')</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        @yield('content')
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->

  <!-- Main Footer -->
  <footer class="main-footer">
    <strong>Copyright &copy; {{ date('Y') }} <a href="{{ route('admin.index') }}">Sisconint-Porcino</a>.</strong>
    Todos los derechos reservados.
    <div class="float-right d-none d-sm-inline-block">
      <b>Versión</b> 1.4.0
    </div>
  </footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap 4 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="{{ asset('adminlte/js/adminlte.min.js') }}"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@livewireStyles
@livewireScripts
@if(session('success'))
<script>
Swal.fire({
  title: 'Éxito',
  text: '{{ session('success') }}',
  icon: 'success',
  confirmButtonText: 'OK'
});
</script>
@endif
@if(session('error'))
<script>
Swal.fire({
  title: 'Error',
  text: '{{ session('error') }}',
  icon: 'error',
  confirmButtonText: 'OK'
});
</script>
@endif
@yield('scripts')
</body>
</html>