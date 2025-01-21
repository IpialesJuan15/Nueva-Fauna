<!doctype html>
<html lang="en">

<head>
    <title>Inicio de Sesión</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <link rel="stylesheet" href="{{ asset('css/recuperarEmail.css') }}">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="{{ route('home') }}" style="color: #00A86B;">
                <i class="uil uil-estate"></i> Volver al Home
            </a>
        </div>
    </nav>

    <!-- Login Section -->
    <section class="vh-100" style="background: linear-gradient(to right, #00A86B, #98FB98, #00C853);">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col col-xl-10">
                    <div class="card shadow-lg" style="border-radius: 1rem;">
                        <div class="row g-0">
                            <!-- Imagen -->
                            <div class="col-md-6 col-lg-5 d-none d-md-block">
                                <img src="{{ asset('images/imag2.jpg') }}" alt="Formulario de inicio de sesión"
                                    class="img-fluid"
                                    style="border-radius: 1rem 0 0 1rem; object-fit: cover; height: 100%;">
                            </div>

                            <!-- Formulario -->
                            <div class="col-md-6 col-lg-7 d-flex align-items-center"
                                style="background-color: rgba(255, 255, 255, 0.95); border-radius: 0 1rem 1rem 0;">
                                <div class="card-body p-4 p-lg-5 text-black">

                                    <!-- Título -->
                                    <form action="{{ route('login') }}" method="POST">
                                        @csrf
                                        <div class="d-flex align-items-center mb-3 pb-1">
                                            <i class="fas fa-leaf fa-2x me-3" style="color: #00A86B;"></i>
                                            <span class="h1 fw-bold mb-0" style="color: #00A86B;">¡Bienvenido!</span>
                                        </div>

                                        <h5 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;">
                                            Inicia sesión en tu cuenta
                                        </h5>

                                        <!-- Campo Email -->
                                        <div class="form-outline mb-4">
                                            <label for="user_email" class="form-label">Correo Electrónico</label>
                                            <input type="email" name="user_email" id="user_email"
                                                class="form-control form-control-lg" placeholder="Correo" required
                                                autofocus>
                                        </div>

                                        <!-- Campo Contraseña -->
                                        <div class="form-outline mb-4">
                                            <label for="user_password" class="form-label">Contraseña</label>
                                            <input type="password" name="user_password" id="user_password"
                                                class="form-control form-control-lg" placeholder="Contraseña" required>
                                        </div>

                                        <!-- Botón Iniciar Sesión -->
                                        <div class="pt-1 mb-4">
                                            <button class="btn btn-lg btn-block text-white" type="submit"
                                                style="background: linear-gradient(to right, #00A86B, #00C853); border: none; font-weight: 600;">
                                                Iniciar Sesión
                                            </button>
                                        </div>

                                        <!-- Links -->
                                        <a class="small text-muted" href="{{ route('recuperarEmail') }}">¿Olvidó su
                                            contraseña?</a>
                                        <p class="mb-5 pb-lg-2" style="color: #00A86B;">
                                            ¿No tienes una cuenta?
                                            <a href="{{ route('register') }}"
                                                style="color: #00C853; font-weight: bold;">Regístrate aquí</a>
                                        </p>
                                    </form>

                                    <!-- Mensajes de error -->
                                    @if ($errors->any())
                                        <div class="alert alert-danger">
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
</body>

</html>
