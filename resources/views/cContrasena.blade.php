<!doctype html>
<html lang="en">

<head>
    <title>Title</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- Bootstrap CSS v5.2.1 -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
        crossorigin="anonymous" />
</head>

<body>
    <div class="d-flex justify-content-center align-items-center vh-100"
        style="background-image: url('../../public/images/imag4.jpg'); background-size: cover; background-position: center; 
            background-color: rgba(0, 0, 0, 0.5); background-blend-mode: overlay;">
        <div class="card text-center shadow-lg" style="width: 350px; border-radius: 10px; background-color: rgba(255, 255, 255, 0.9);">
            <div class="card-header h5 text-white" style="background-color: #4285f4; border-top-left-radius: 10px; border-top-right-radius: 10px;">
                Cambiar Contraseña
            </div>
            <div class="card-body px-4">
                <p class="card-text py-3">
                    Por favor, ingrese su nueva contraseña y confírmela para actualizarla.
                </p>
                <form>
                    <!-- Nueva Contraseña -->
                    <div class="form-outline mb-3">
                        <input type="password" id="newPassword" class="form-control" placeholder="Nueva Contraseña" required />
                    </div>
                    <!-- Confirmar Contraseña -->
                    <div class="form-outline mb-3">
                        <input type="password" id="confirmPassword" class="form-control" placeholder="Confirmar Contraseña" required />
                    </div>
                    <!-- Botón para Cambiar Contraseña -->
                    <button type="submit" class="btn btn-primary btn-block w-100" style="background-color: #4285f4; border: none; border-radius: 5px;">
                        Cambiar Contraseña
                    </button>
                </form>
            </div>
        </div>
    </div>
    <script
        src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
        crossorigin="anonymous"></script>
</body>

</html>