<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediVida - Agendar Cita</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        /* Fondo profesional con gradiente */
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            background-attachment: fixed;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        /* Contenedor principal con sombra y transparencia */
        .container {
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin-top: 30px;
            margin-bottom: 30px;
        }
        
        /* Estilos para los pasos del formulario */
        .paso-agendamiento { display: none; padding: 20px; }
        .paso-activo { display: block; }
        
        /* Tarjetas de selección */
        .card-sucursal, .card-doctor {
            cursor: pointer;
            transition: all 0.3s;
            border: 1px solid #dee2e6;
            margin-bottom: 15px;
        }
        .card-sucursal:hover, .card-doctor:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        .seleccionado {
            border: 2px solid #007bff !important;
            background-color: #f8f9fa;
        }
        
        /* Barra de progreso */
        .progress {
            height: 10px;
            margin-bottom: 30px;
        }
        .progress-bar {
            background-color: #4e73df;
            transition: width 0.5s ease;
        }
        
        /* Botones */
        .btn {
            padding: 8px 20px;
            border-radius: 5px;
            font-weight: 500;
            transition: all 0.3s;
        }
        .btn-primary {
            background-color: #4e73df;
            border-color: #4e73df;
        }
        .btn-primary:hover {
            background-color: #2e59d9;
            border-color: #2653d4;
        }
        
        /* Header */
        .header-blue {
            background-color: #4e73df;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        /* Efecto para los iconos */
        .fa {
            transition: all 0.3s;
        }
        .card:hover .fa {
            transform: scale(1.1);
        }
        
        /* Responsivo */
        @media (max-width: 768px) {
            .container {
                padding: 15px;
                margin-top: 15px;
                margin-bottom: 15px;
            }
        }

        /* ************************************** */
        /* Nuevo estilo para el color del texto MediVida */
        .navbar-brand {
            color: #1a73e8 !important; /* Un azul de Google, puedes cambiar a tu gusto */
            /* Opcional: para que el icono también sea azul si lo deseas */
            /* Si el icono tiene su propio estilo, podrías necesitar .navbar-brand .fa { color: #1a73e8 !important; } */
        }
        /* ************************************** */

    </style>
</head>
<body>
    <header class="header-blue">
        <nav class="navbar navbar-dark navbar-expand-md navigation-clean-search">
            <div class="container">
                <a class="navbar-brand" href="#"><i class="fa fa-heartbeat"></i> MediVida</a>
                <button class="navbar-toggler" data-toggle="collapse" data-target="#navcol-1">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navcol-1">
                    <div class="mr-auto"></div>
                    <a class="btn btn-light action-button" role="button" href="#"><i class="fa fa-sign-out"></i> Cerrar Sesión</a>
                </div>
            </div>
        </nav>
    </header>

    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3><i class="fa fa-calendar-plus-o"></i> Agendar Nueva Cita</h3>
            <a href="paciente.html" class="btn btn-secondary">
                <i class="fa fa-times"></i> Cancelar
            </a>
        </div>
        
        <form id="form-cita" action="cita-confirmada.html" method="GET">
            <div class="progress mb-4">
                <div id="progreso" class="progress-bar" role="progressbar" style="width: 20%">Paso 1 de 5</div>
            </div>
            
            <div id="paso-sucursal" class="paso-agendamiento paso-activo">
                <h4 class="text-center mb-4"><i class="fa fa-hospital-o"></i> Seleccione Sucursal</h4>
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <div class="card card-sucursal" onclick="seleccionarSucursal('norte', this)">
                            <div class="card-body text-center">
                                <i class="fa fa-map-marker fa-3x mb-3 text-primary"></i>
                                <h5>Sucursal Norte</h5>
                                <p class="text-muted">Av. Principal #123, Zona Norte</p>
                                <small class="text-info">Horario: 8:00 AM - 8:00 PM</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="card card-sucursal" onclick="seleccionarSucursal('sur', this)">
                            <div class="card-body text-center">
                                <i class="fa fa-map-marker fa-3x mb-3 text-primary"></i>
                                <h5>Sucursal Sur</h5>
                                <p class="text-muted">Calle Central #456, Zona Sur</p>
                                <small class="text-info">Horario: 7:00 AM - 7:00 PM</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div id="paso-especialidad" class="paso-agendamiento">
                <h4 class="text-center mb-4"><i class="fa fa-stethoscope"></i> Seleccione Especialidad</h4>
                <div class="form-group">
                    <select class="form-control" id="select-especialidad" name="especialidad" required>
                        <option value="">-- Seleccione una especialidad --</option>
                        <option value="Cardiología">Cardiología</option>
                        <option value="Pediatría">Pediatría</option>
                        <option value="Medicina General">Medicina General</option>
                        <option value="Dermatología">Dermatología</option>
                    </select>
                </div>
            </div>
            
            <div id="paso-doctor" class="paso-agendamiento">
                <h4 class="text-center mb-4"><i class="fa fa-user-md"></i> Seleccione Médico</h4>
                <div id="lista-doctores" class="list-group">
                    </div>
                <input type="hidden" id="input-doctor" name="doctor">
            </div>
            
            <div id="paso-fecha" class="paso-agendamiento">
                <h4 class="text-center mb-4"><i class="fa fa-calendar"></i> Seleccione Fecha y Hora</h4>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Fecha de la cita</label>
                            <input type="date" class="form-control" id="fecha-cita" name="fecha" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Hora disponible</label>
                            <select class="form-control" id="hora-cita" name="hora" required disabled>
                                <option value="">Seleccione primero una fecha</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            
            <div id="paso-confirmacion" class="paso-agendamiento">
                <h4 class="text-center mb-4"><i class="fa fa-check-circle"></i> Confirmar Cita</h4>
                <div class="alert alert-info">
                    <h5>Resumen de la cita:</h5>
                    <p><strong>Sucursal:</strong> <span id="resumen-sucursal">-</span></p>
                    <p><strong>Especialidad:</strong> <span id="resumen-especialidad">-</span></p>
                    <p><strong>Médico:</strong> <span id="resumen-doctor">-</span></p>
                    <p><strong>Fecha y Hora:</strong> <span id="resumen-fecha">-</span></p>
                </div>
                <div class="form-group">
                    <label>Motivo de la consulta (opcional)</label>
                    <textarea class="form-control" rows="3" id="motivo-consulta" name="motivo" placeholder="Describa brevemente el motivo de su consulta"></textarea>
                </div>
            </div>
            
            <div class="d-flex justify-content-between mt-4">
                <button type="button" class="btn btn-secondary" id="btn-anterior" onclick="retrocederPaso()" style="display: none;">
                    <i class="fa fa-arrow-left"></i> Anterior
                </button>
                <button type="button" class="btn btn-primary ml-auto" id="btn-siguiente" onclick="avanzarPaso()">
                    Siguiente <i class="fa fa-arrow-right"></i>
                </button>
                <button type="submit" class="btn btn-success" id="btn-confirmar" style="display: none;">
                    <i class="fa fa-calendar-check-o"></i> Confirmar Cita
                </button>
            </div>
        </form>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script>
        // Variables para controlar el paso actual
        let pasoActual = 1;
        const totalPasos = 5;
        
        // Objeto para almacenar datos temporales
        const datosCita = {
            sucursal: null,
            especialidad: null,
            doctor: null,
            fecha: null,
            hora: null
        };
        
        // Función para avanzar al siguiente paso
        function avanzarPaso() {
            // Validar datos del paso actual antes de avanzar
            if (!validarPasoActual()) return;
            
            // Ocultar paso actual
            document.getElementById(`paso-${obtenerNombrePaso(pasoActual)}`).classList.remove('paso-activo');
            
            // Avanzar al siguiente paso
            pasoActual++;
            
            // Si llegamos al último paso, cambiar botones
            if (pasoActual === totalPasos) {
                document.getElementById('btn-siguiente').style.display = 'none';
                document.getElementById('btn-confirmar').style.display = 'block';
                actualizarResumen();
            }
            
            // Mostrar botón "Anterior" si no estamos en el primer paso
            if (pasoActual > 1) {
                document.getElementById('btn-anterior').style.display = 'block';
            }
            
            // Mostrar siguiente paso
            document.getElementById(`paso-${obtenerNombrePaso(pasoActual)}`).classList.add('paso-activo');
            
            // Actualizar barra de progreso
            actualizarProgreso();
            
            // Acciones específicas por paso
            accionesPorPaso();
        }
        
        // Función para retroceder al paso anterior
        function retrocederPaso() {
            // Ocultar paso actual
            document.getElementById(`paso-${obtenerNombrePaso(pasoActual)}`).classList.remove('paso-activo');
            
            // Retroceder al paso anterior
            pasoActual--;
            
            // Si volvemos al primer paso, ocultar botón "Anterior"
            if (pasoActual === 1) {
                document.getElementById('btn-anterior').style.display = 'none';
            }
            
            // Mostrar botón "Siguiente" si estábamos en el último paso
            if (pasoActual === totalPasos - 1) {
                document.getElementById('btn-siguiente').style.display = 'block';
                document.getElementById('btn-confirmar').style.display = 'none';
            }
            
            // Mostrar paso anterior
            document.getElementById(`paso-${obtenerNombrePaso(pasoActual)}`).classList.add('paso-activo');
            
            // Actualizar barra de progreso
            actualizarProgreso();
        }
        
        // Función para obtener el nombre del paso según su número
        function obtenerNombrePaso(numero) {
            const pasos = ['', 'sucursal', 'especialidad', 'doctor', 'fecha', 'confirmacion'];
            return pasos[numero];
        }
        
        // Función para actualizar la barra de progreso
        function actualizarProgreso() {
            const porcentaje = (pasoActual / totalPasos) * 100;
            const progreso = document.getElementById('progreso');
            progreso.style.width = `${porcentaje}%`;
            progreso.textContent = `Paso ${pasoActual} de ${totalPasos}`;
        }
        
        // Función para validar el paso actual antes de avanzar
        function validarPasoActual() {
            switch(pasoActual) {
                case 1: // Sucursal
                    if (!datosCita.sucursal) {
                        alert('Por favor seleccione una sucursal');
                        return false;
                    }
                    break;
                case 2: // Especialidad
                    const especialidad = document.getElementById('select-especialidad').value;
                    if (!especialidad) {
                        alert('Por favor seleccione una especialidad');
                        return false;
                    }
                    datosCita.especialidad = especialidad;
                    break;
                case 3: // Doctor
                    if (!datosCita.doctor) {
                        alert('Por favor seleccione un médico');
                        return false;
                    }
                    break;
                case 4: // Fecha y Hora
                    const fecha = document.getElementById('fecha-cita').value;
                    const hora = document.getElementById('hora-cita').value;
                    if (!fecha || !hora) {
                        alert('Por favor seleccione fecha y hora');
                        return false;
                    }
                    datosCita.fecha = fecha;
                    datosCita.hora = hora;
                    break;
            }
            return true;
        }
        
        // Acciones específicas al entrar a cada paso
        function accionesPorPaso() {
            switch(pasoActual) {
                case 3: // Doctor
                    cargarDoctores();
                    break;
                case 4: // Fecha
                    // Establecer fecha mínima (mañana)
                    const fechaInput = document.getElementById('fecha-cita');
                    const hoy = new Date();
                    hoy.setDate(hoy.getDate() + 1);
                    fechaInput.min = hoy.toISOString().split('T')[0];
                    break;
            }
        }
        
        // Función para seleccionar sucursal
        function seleccionarSucursal(nombre, elemento) {
            datosCita.sucursal = nombre;
            document.querySelectorAll('.card-sucursal').forEach(c => c.classList.remove('seleccionado'));
            elemento.classList.add('seleccionado');
            document.getElementById('btn-siguiente').disabled = false;
        }
        
        // Función para cargar doctores según especialidad
        function cargarDoctores() {
            const lista = document.getElementById('lista-doctores');
            lista.innerHTML = '<div class="text-center py-4"><i class="fa fa-spinner fa-spin fa-2x"></i><p>Cargando médicos disponibles...</p></div>';
            
            // Simulación de carga con timeout
            setTimeout(() => {
                let doctores = [];
                if (datosCita.especialidad === 'Cardiología') {
                    doctores = [
                        { id: '1', nombre: 'Dra. Ana López', experiencia: 'Cardióloga con 10 años de experiencia' },
                        { id: '2', nombre: 'Dr. Carlos Méndez', experiencia: 'Cardiólogo pediátrico' }
                    ];
                } else if (datosCita.especialidad === 'Pediatría') {
                    doctores = [
                        { id: '3', nombre: 'Dra. Sofía Ramírez', experiencia: 'Pediatra con 8 años de experiencia' },
                        { id: '4', nombre: 'Dr. Javier Herrera', experiencia: 'Neonatólogo' }
                    ];
                } else {
                    doctores = [
                        { id: '5', nombre: 'Dr. Luis García', experiencia: 'Médico general' },
                        { id: '6', nombre: 'Dra. María Fernández', experiencia: 'Médico familiar' }
                    ];
                }
                
                lista.innerHTML = '';
                doctores.forEach(doctor => {
                    const item = document.createElement('a');
                    item.href = '#';
                    item.className = 'list-group-item list-group-item-action card-doctor';
                    item.innerHTML = `
                        <div class="d-flex align-items-center">
                            <div>
                                <h6 class="mb-1">${doctor.nombre}</h6>
                                <small class="text-muted">${doctor.experiencia}</small>
                            </div>
                        </div>
                    `;
                    item.onclick = (e) => {
                        e.preventDefault();
                        document.querySelectorAll('.card-doctor').forEach(d => d.classList.remove('seleccionado'));
                        item.classList.add('seleccionado');
                        datosCita.doctor = doctor.nombre;
                        document.getElementById('input-doctor').value = doctor.id;
                    };
                    lista.appendChild(item);
                });
            }, 800);
        }
        
        // Cargar horarios disponibles cuando se selecciona fecha
        document.getElementById('fecha-cita').addEventListener('change', function() {
            const horaSelect = document.getElementById('hora-cita');
            horaSelect.innerHTML = '<option value="">Cargando horarios...</option>';
            
            // Simulación de carga con timeout
            setTimeout(() => {
                const horas = ['08:00', '09:30', '11:00', '13:00', '15:00', '16:30'];
                horaSelect.innerHTML = '<option value="">-- Seleccione una hora --</option>';
                horas.forEach(hora => {
                    const option = document.createElement('option');
                    option.value = hora;
                    option.textContent = hora;
                    horaSelect.appendChild(option);
                });
                horaSelect.disabled = false;
            }, 500);
        });
        
        // Actualizar resumen en el paso de confirmación
        function actualizarResumen() {
            document.getElementById('resumen-sucursal').textContent = datosCita.sucursal === 'norte' ? 'Sucursal Norte' : 'Sucursal Sur';
            document.getElementById('resumen-especialidad').textContent = datosCita.especialidad;
            document.getElementById('resumen-doctor').textContent = datosCita.doctor;
            document.getElementById('resumen-fecha').textContent = `${datosCita.fecha} a las ${datosCita.hora}`;
        }
    </script>
</body>
</html>