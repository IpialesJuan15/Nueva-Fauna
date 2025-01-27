<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Birdly Ecuador</title>
    <!-- Usando el helper asset() para vincular el archivo CSS -->
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-left">
                <a href="/" class="logo">
                    <span class="logo-text">Birdly</span>
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
                <a href="{{ route('login') }}" class="btn btn-ghost">Acceder</a>
                <a href="{{ route('register') }}" class="btn btn-primary">Crear una cuenta</a>
            </div>
        </div>
    </nav>

    <main>
        <!-- Hero Content con Carrusel de Imágenes -->
        <div class="hero-content">
            <!-- Botón para imagen anterior -->
            <button class="slider-btn slider-prev">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="15 18 9 12 15 6"></polyline>
                </svg>
            </button>

            <div class="hero-card">
                <h1>Conéctate con la naturaleza</h1>
                <p>Explora y comparte tus observaciones de la naturaleza.</p>
                <div class="hero-buttons">
                    <a href="{{ route('register') }}" class="btn btn-white">REGÍSTRATE</a>
                    <button class="btn btn-outline-white">EXPLORA</button>
                </div>
            </div>

            <!-- Botón para imagen siguiente -->
            <button class="slider-btn slider-next">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </button>
        </div>

        <section id="explora" class="how-it-works">
            <h2>Cómo funciona</h2>
            <div class="steps-container">
                <div class="step">
                    <div class="step-icon">
                        <img src="{{ asset('images/fotos.png') }}" alt="Tomar fotos">
                    </div>
                    <p>Toma fotos y registra tus observaciones</p>
                </div>
                <div class="step">
                    <div class="step-icon">
                        <img src="{{ asset('images/compartir.png') }}" alt="Compartir">
                    </div>
                    <p>Comparte con la comunidad de naturalistas</p>
                </div>
                <div class="step">
                    <div class="step-icon">
                        <img src="{{ asset('images/aprender.png') }}" alt="Aprender">
                    </div>
                    <p>Aprende y contribuye a la ciencia ciudadana</p>
                </div>
            </div>
        </section>

        <section id="comunidad" class="community">
            <h2>Comunidad Birdly Ecuador</h2>
            <div class="stats-container">
                <div class="stat">
                    <h3>0</h3> <!-- Este número será reemplazado dinámicamente -->
                    <p>Observaciones</p>
                </div>
                <div class="stat">
                    <h3>0</h3> <!-- Este número será reemplazado dinámicamente -->
                    <p>Especies</p>
                </div>
                <div class="stat">
                    <h3>12,345</h3>
                    <p>Naturalistas</p>
                </div>
            </div>
        </section>

        <section id="mas" class="recent-observations">
            <h2>Observaciones Recientes</h2>
            <div class="observations-grid">
                <!-- Aquí se cargarán dinámicamente las especies aprobadas -->
            </div>
            <button class="btn btn-primary" onclick="cargarEspeciesAprobadas()">Actualizar observaciones</button>
        </section>
    </main>

    <footer>
        <div class="footer-container">
            <div class="footer-logo">
                <span class="logo-text">Birdly</span>
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
                <!-- Social Media Links -->
            </div>
        </div>
    </footer>

    <!-- Usando el helper asset() para vincular el archivo JS -->
    <script src="{{ asset('js/home.js') }}"></script>
</body>
</html>
