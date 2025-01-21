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
    <div class="hero-slider">
        <button class="slider-btn slider-prev">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="15 18 9 12 15 6"></polyline>
            </svg>
        </button>
        <button class="slider-btn slider-next">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="9 18 15 12 9 6"></polyline>
            </svg>
        </button>
        <div class="slider-indicators"></div>
        </div>
            <div class="hero-content">
                <div class="hero-card">
                    <h1>Conéctate con la naturaleza</h1>
                    <p>Explora y comparte tus observaciones de la naturaleza.</p>
                    <div class="hero-buttons">
                    <a href="{{ route('register') }}" class="btn btn-white">REGÍSTRATE</a>
                        <button class="btn btn-outline-white">EXPLORA</button>
                    </div>
                </div>
            </div>
        </section>

        <section id="explora" class="how-it-works">
            <h2>Cómo funciona</h2>
            <div class="steps-container">
                <div class="step">
                    <div class="step-icon">
                        <img src="placeholder-1.svg" alt="Tomar fotos">
                    </div>
                    <p>Toma fotos y registra tus observaciones</p>
                </div>
                <div class="step">
                    <div class="step-icon">
                        <img src="placeholder-2.svg" alt="Compartir">
                    </div>
                    <p>Comparte con la comunidad de naturalistas</p>
                </div>
                <div class="step">
                    <div class="step-icon">
                        <img src="placeholder-3.svg" alt="Aprender">
                    </div>
                    <p>Aprende y contribuye a la ciencia ciudadana</p>
                </div>
            </div>
        </section>

        <section id="comunidad" class="community">
            <h2>Comunidad Birdly Ecuador</h2>
            <div class="stats-container">
                <div class="stat">
                    <h3>1,234,567</h3>
                    <p>Observaciones</p>
                </div>
                <div class="stat">
                    <h3>98,765</h3>
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
                <div class="observation">
                    <img src="../../public/images/ruficollarejo.png" alt="Observación 1">
                    <p>Especie 1</p>
                </div>
                <div class="observation">
                    <img src="placeholder-obs-2.jpg" alt="Observación 2">
                    <p>Especie 2</p>
                </div>
                <div class="observation">
                    <img src="placeholder-obs-3.jpg" alt="Observación 3">
                    <p>Especie 3</p>
                </div>
                <div class="observation">
                    <img src="placeholder-obs-4.jpg" alt="Observación 4">
                    <p>Especie 4</p>
                </div>
            </div>
            <button class="btn btn-primary">Ver más observaciones</button>
        </section>
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
                <a href="#" class="social-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                    </svg>
                </a>
                <a href="#" class="social-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                    </svg>
                </a>
                <a href="#" class="social-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/>
                    </svg>
                </a>
            </div>
        </div>
    </footer>

    <!-- Usando el helper asset() para vincular el archivo JS -->
    <script src="{{ asset('js/home.js') }}"></script>
</body>
</html>
