<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Kanban App')</title>
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <script src="{{ asset('js/kanban.js') }}" defer></script>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class  ="container">
          <a class="navbar-brand" href="{{ url('/') }}">Kanban App</a>
          <div class="d-flex ms-auto">
             <span class="me-3">👤 {{ Auth::user()->name }}</span>
             <a href="{{ route('logout') }}" class="btn btn-danger"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                Logout
             </a>
             <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                 @csrf
             </form>
          </div>
        </div>
      </nav>
      

    <div class="container mt-4">
        @yield('content')
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Sisipkan script inline dari child (home.blade.php) -->
    @stack('scripts')
</div>
</body>
</html>
