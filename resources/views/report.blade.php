<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Informe de Especie</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <!-- Font Awesome para íconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
          integrity="sha512-Fo3rlrQkTyZ7VRGynrVNRnjHyUqUQ+9S5eYz4jj9jK1tkmJZsWWQV7gxo4e2LmmkFv7yU2bJ2g8Q5N3v3F+1dA=="
          crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body class="report-body">
    <header class="report-header">
        <div class="header-container">
            <div class="brand">
                <i class="fas fa-dove"></i>
                <h1>Informe de Especie</h1>
            </div>
            <nav>
                <ul>
                    <li><a href="{{ url('/observador') }}"><i class="fas fa-arrow-left"></i> Volver al inicio</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="report-main">
        <div class="report-container" id="reportContent">
            <!-- Contenido del informe generado dinámicamente -->
        </div>
        <div class="report-actions">
            <button id="printReport" class="action-btn"><i class="fas fa-print"></i> Imprimir</button>
            <button id="downloadPDF" class="action-btn"><i class="fas fa-download"></i> Descargar PDF</button>
        </div>
    </main>

    <footer class="report-footer">
        <p>&copy; 2024 Observador de Aves - Ibarra, Ecuador</p>
    </footer>

    <!-- jsPDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <!-- Script del informe -->
    <script src="{{ asset('js/report.js') }}"></script>
</body>
</html>
