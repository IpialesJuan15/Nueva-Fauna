<!doctype html>
<html lang="en">

<head>
    <title>Registro</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- Bootstrap CSS v5.2.1 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <!-- Font Awesome para íconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-sri7u8rJ46c8RVFj/oR+9jupHkk6AJXAOIsJ1kFyKlNUE/IS+Fb8Hs6J3TYT0YElmpjG+uS6O3+4oZqUzjzkDg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <!-- Navbar Mejorado -->
    <nav class="navbar navbar-expand-lg" style="background-color: #28a745;">
        <div class="container-fluid">
            <a class="navbar-brand text-white fw-bold" href="{{ route('home') }}">
                <i class="fa-solid fa-house"></i> Volver al Home
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>

    <!-- Registration Section -->
    <section class="p-3 p-md-4 p-xl-5" style="background-color: #f3f3f3;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-xxl-11">
                    <div class="card border-0 shadow-sm" style="background-color:rgb(255, 255, 255);">
                        <div class="row g-0">
                            <!-- Imagen -->
                            <div class="col-12 col-md-6">
                                <img class="img-fluid rounded-start w-100 h-100 object-fit-cover"
                                    src="{{ asset('images/imag5.jpg') }}" alt="Welcome back you've been missed!"
                                    loading="lazy">
                            </div>

                            <!-- Formulario -->
                            <div class="col-12 col-md-6 d-flex align-items-center justify-content-center"
                                style="background-color:rgb(255, 255, 255);">
                                <div class="col-12 col-lg-11 col-xl-10">
                                    <div class="card-body p-3 p-md-4 p-xl-5">
                                        <div class="text-center mb-4">
                                            <h2 class="h4 fw-bold" style="color: #5f794e;">Registro</h2>
                                        </div>
                                        @if ($errors->any())
                                            <div class="alert alert-danger">
                                                <ul>
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif

                                        <form action="{{ route('register') }}" method="POST">
                                            @csrf
                                            <div class="row gy-3">
                                                <!-- Cedula -->
                                                <div class="col-12">
                                                    <div class="form-floating">
                                                        <input type="text" name="user_cedula" class="form-control"
                                                            id="firstName" placeholder="Cedula" required
                                                            style="border: 1px solid #d3e0cf;">
                                                        <label for="firstName" class="form-label"
                                                            style="color: #5f794e;">Cedula</label>
                                                    </div>
                                                </div>
                                                <!-- Nombre -->
                                                <div class="col-12">
                                                    <div class="form-floating">
                                                        <input type="text" name="user_nombre" class="form-control"
                                                            id="firstName" placeholder="Nombre" required
                                                            style="border: 1px solid #d3e0cf;">
                                                        <label for="firstName" class="form-label"
                                                            style="color: #5f794e;">Nombre</label>
                                                    </div>
                                                </div>
                                                <!-- Apellido -->
                                                <div class="col-12">
                                                    <div class="form-floating">
                                                        <input type="text" name="user_apellido" class="form-control"
                                                            id="lastName" placeholder="Apellido" required
                                                            style="border: 1px solid #d3e0cf;">
                                                        <label for="lastName" class="form-label"
                                                            style="color: #5f794e;">Apellido</label>
                                                    </div>
                                                </div>
                                                <!-- Email -->
                                                <div class="col-12">
                                                    <div class="form-floating">
                                                        <input type="email" name="user_email" class="form-control"
                                                            id="email" placeholder="Email" required
                                                            style="border: 1px solid #d3e0cf;">
                                                        <label for="email" class="form-label"
                                                            style="color: #5f794e;">Email</label>
                                                    </div>
                                                </div>
                                                <!-- Teléfono -->
                                                <div class="col-12">
                                                    <div class="form-floating">
                                                        <input type="text" name="user_telefono" class="form-control"
                                                            id="phone" placeholder="Teléfono" required
                                                            style="border: 1px solid #d3e0cf;">
                                                        <label for="phone" class="form-label"
                                                            style="color: #5f794e;">Teléfono</label>
                                                    </div>
                                                </div>
                                                <!-- Password -->
                                                <div class="col-12">
                                                    <div class="form-floating">
                                                        <input type="password" name="user_password"
                                                            class="form-control" id="password"
                                                            placeholder="Password" required
                                                            style="border: 1px solid #d3e0cf;">
                                                        <label for="password" class="form-label"
                                                            style="color: #5f794e;">Password</label>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-floating">
                                                        <input type="password" name="user_password_confirmation"
                                                            class="form-control" id="password"
                                                            placeholder="Password" required
                                                            style="border: 1px solid #d3e0cf;">
                                                        <label for="password_confirmation" class="form-label"
                                                            style="color: #5f794e;">Confirmar Contraseña</label>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <label for="tipus_id" class="form-label" style="color: #5f794e;">Seleccione Tipo de Usuario</label>
                                                    <select name="tipus_id" id="tipus_id" class="form-select" style="border: 1px solid #d3e0cf; color: #5f794e; background-color: #f9fff5;">
                                                        <option value="1">Observador</option>
                                                        <option value="2">Investigador</option>
                                                        <option value="3">Taxonomo</option>
                                                    </select>
                                                </div>
                                                <!-- Checkbox -->
                                               <!-- <div class="col-12 form-check">
                                                    <input class="form-check-input" type="checkbox" id="agree"
                                                        required>
                                                    <label class="form-check-label" for="agree"
                                                        style="color: #5f794e;">
                                                        Acepto los <a href="#"
                                                            class="link-primary text-decoration-none">Términos y
                                                            condiciones</a>
                                                    </label>
                                                </div>-->
                                                <!-- Botón -->
                                                <div class="col-12 d-grid">
                                                    <button class="btn" type="submit"
                                                        style="background-color: #5f794e; color: #ffffff;">Registrar</button>
                                                </div>
                                            </div>
                                        </form>
                                        <!-- Enlace de inicio de sesión -->
                                        <p class="mt-4 text-center" style="color: #5f794e;">
                                            ¿Ya tienes una cuenta? <a href="{{ route('login') }}"
                                                class="link-primary text-decoration-none">Iniciar sesión</a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4l1g+QkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous">
    </script>
</body>

</html>
