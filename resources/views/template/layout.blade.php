<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'iNaturalist Ecuador')</title>
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-left">
                <a href="/" class="logo">
                    <span class="logo-text">iNaturalist</span>
                    <span class="logo-ec">Ec</span>
                </a>
                <div class="nav-links">
                    <a href="#explora">Explora</a>
                    <a href="#comunidad">Comunidad</a>
                    <a href="#mas">Más</a>
                </div>
            </div>
            <div class="nav-right">
                <button class="search-btn">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </button>
                <a href="#login" class="btn btn-ghost">Acceder</a>
                <a href="#registro" class="btn btn-primary">Crear una cuenta</a>
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <footer>
        <div class="footer-container">
            <div class="footer-logo">
                <span class="logo-text">iNaturalist</span>
                <span class="logo-ec">Ec</span>
            </div>
            <div class="footer-links">
                <a href="#about">Acerca de</a>
                <a href="#help">Ayuda</a>
                <a href="#contact">Contacto</a>
                <a href="#privacy">Privacidad</a>
                <a href="#terms">Términos</a>
            </div>
            <div class="footer-social">
                <!-- Aquí van los íconos sociales -->
            </div>
        </div>
    </footer>

    <script src="{{ asset('js/home.js') }}"></script>
</body>
</html>
