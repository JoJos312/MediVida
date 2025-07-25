<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediVida - Cita Confirmada</title>
    <!-- Bootstrap 4 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css">
    <!-- Font Awesome 4 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        .card { max-width: 600px; margin: 0 auto; }
        .fa-check-circle { color: #28a745; }
    </style>
</head>
<body>
    <header class="header-blue">
        <!-- Mismo header que en tu código original -->
    </header>

    <div class="container text-center mt-5">
        <i class="fa fa-check-circle fa-5x mb-4"></i>
        <h2>¡Cita agendada con éxito!</h2>
        
        <div class="card mt-4 mb-4 text-left">
            <div class="card-body">
                <h5>Detalles de la cita:</h5>
                <div id="detalles-cita"></div>
            </div>
        </div>
        
        <div class="mt-4">
            <a href="paciente.html" class="btn btn-secondary ml-2">
                <i class="fa fa-home"></i> Volver al inicio
            </a>
        </div>
    </div>

    <script>
        // Mostrar los detalles de la cita desde los parámetros URL
        document.addEventListener('DOMContentLoaded', function() {
            const params = new URLSearchParams(window.location.search);
            if (params) {
                const sucursal = params.get('sucursal') === 'norte' ? 'Sucursal Norte' : 'Sucursal Sur';
                const detalles = `
                    <p><strong>Sucursal:</strong> ${sucursal}</p>
                    <p><strong>Especialidad:</strong> ${params.get('especialidad')}</p>
                    <p><strong>Médico:</strong> ${params.get('doctor')}</p>
                    <p><strong>Fecha:</strong> ${params.get('fecha')}</p>
                    <p><strong>Hora:</strong> ${params.get('hora')}</p>
                    <p><strong>Motivo:</strong> ${params.get('motivo') || 'No especificado'}</p>
                `;
                document.getElementById('detalles-cita').innerHTML = detalles;
            }
        });
    </script>
</body>
</html>