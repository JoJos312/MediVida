<?php
session_start();

if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] != 3) {
    header("Location: ../login.html");
    exit();
}

// Incluir datos del paciente
include '../../Backend/datospaciente.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediVida - Área del Paciente</title>
    
    <!-- Bootstrap 4 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css">
    <!-- Font Awesome 4 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="Frontend/Paciente/citas.css">
    <link rel="stylesheet" href="../Header/Header.css">
    <link rel="stylesheet" href="../../css/usuarios.css">
    

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
                    <ul class="nav navbar-nav">
                        <li class="nav-item" role="presentation"><a class="nav-link active" href="#">Conocenos</a></li>
                            <li class="dropdown"><a class="dropdown-toggle nav-link dropdown-toggle" data-toggle="dropdown" aria-expanded="false" href="#">Redes Sociales</a>
                                <div class="dropdown-menu" role="menu">
                                    <a class="dropdown-item" role="presentation" href="#">Facebook</a>
                                    <a class="dropdown-item" role="presentation" href="#">Twitter (X)</a>
                                    <a class="dropdown-item" role="presentation" href="#">Instagram</a>
                                </div>
                    </ul>
                    <div class="mr-auto"></div>
                    <a class="btn btn-light action-button" role="button" href="#"><i class="fa fa-sign-out"></i> Cerrar Sesión</a>
                </div>
            </div>
        </nav>
    </header>

    <!-- Contenido principal -->
    <div id="content">
        <div class="d-flex align-items-center mb-4">
            <img src="../../Img/paciente.png" alt="Foto de perfil" id="UserPhoto">
        <span id="UserName" class="content-section">Bienvenido, <?php echo htmlspecialchars($nombrePaciente); ?></span>
        </div>

        <!-- Sección de notificaciones -->
        <div class="content-section" id="notificaciones">
            <h4><i class="fa fa-bell"></i> Notificaciones</h4>
            <hr>
            <div class="alert alert-warning">
                <strong><i class="fa fa-exclamation-triangle"></i> Cita cancelada:</strong> Su cita con el Dr. López el 15/06 ha sido cancelada. 
                <a href="#" onclick="mostrarSeccion('modificar-cita-modal')">¿Desea reprogramarla?</a>
            </div>
        </div>

        <!-- Secciones rápidas -->
<div class="row">
    <div class="col-md-3">
        <a href="AgendarCita.php" class="content-section InteractiveSection" role="button">
            <i class="fa fa-calendar-plus-o"></i>
            <h5>Agendar Cita</h5>
        </a>
    </div>
    
    <div class="col-md-3">
        <div class="content-section InteractiveSection" onclick="mostrarSeccion('ver-recetas')">
            <i class="fa fa-file-text-o"></i>
            <h5>Mis Recetas</h5>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="content-section InteractiveSection" onclick="mostrarSeccion('citas-pasadas')">
            <i class="fa fa-history"></i>
            <h5>Citas Pasadas</h5>
        </div>
    </div>
</div>

<!-- Citas próximas -->
<div class="content-section">
    <h4><i class="fa fa-calendar-check-o"></i> Mis Próximas Citas</h4>
    <hr>
    <table class="table-citas">

                <thead>
                    <tr>
                        <th>Médico</th>
                        <th>Especialidad</th>
                        <th>Fecha y Hora</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Dra. Ana López</td>
                        <td>Cardiología</td>
                        <td>15/06/2025 - 10:00 AM</td>
                        <td><span class="badge-estado badge-confirmada">Confirmada</span></td>
                        <td>
                            <button class="btn btn-sm btn-outline-danger" onclick="mostrarSeccion('cancelar-cita-modal')">
                                <i class="fa fa-times"></i> Cancelar
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td>Dr. Carlos Méndez</td>
                        <td>Pediatría</td>
                        <td>20/06/2025 - 2:00 PM</td>
                        <td><span class="badge-estado badge-pendiente">Pendiente pago</span></td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary" onclick="mostrarSeccion('pago-cita-modal')">
                                <i class="fa fa-credit-card"></i> Pagar
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- MODALES Y SECCIONES OCULTAS -->

       <!-- Modal Agendar Cita (versión mejorada) -->
<div id="agendar-cita-modal" class="modal-section">
    <div class="modal-content">
        <!-- Paso 1: Selección de Sucursal -->
        <div id="paso-sucursal" class="paso-agendamiento">
            <h4 class="text-center mb-4"><i class="fa fa-hospital-o"></i> Seleccione Sucursal</h4>
            
            <div class="row">
                <!-- Sucursal Norte -->
                <div class="col-md-6 mb-4">
                    <div class="card sucursal-card" onclick="seleccionarSucursal('norte', this)">
                        <div class="card-body text-center">
                            <i class="fa fa-map-marker fa-3x mb-3 text-primary"></i>
                            <h5>Sucursal Norte</h5>
                            <p class="text-muted">Av. Principal #123, Zona Norte</p>
                            <small class="text-info">Horario: 8:00 AM - 8:00 PM</small>
                        </div>
                    </div>
                </div>
                
                <!-- Sucursal Sur -->
                <div class="col-md-6 mb-4">
                    <div class="card sucursal-card" onclick="seleccionarSucursal('sur', this)">
                        <div class="card-body text-center">
                            <i class="fa fa-map-marker fa-3x mb-3 text-primary"></i>
                            <h5>Sucursal Sur</h5>
                            <p class="text-muted">Calle Central #456, Zona Sur</p>
                            <small class="text-info">Horario: 7:00 AM - 7:00 PM</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="text-right mt-3">
                <button class="btn btn-secondary" onclick="ocultarSecciones()">
                    <i class="fa fa-times"></i> Cancelar
                </button>
                <button class="btn btn-primary" onclick="avanzarPaso('paso-sucursal', 'paso-especialidad')" id="btn-siguiente-sucursal" disabled>
                    Siguiente <i class="fa fa-arrow-right"></i>
                </button>
            </div>
        </div>
        
        <!-- Paso 2: Selección de Especialidad -->
        <div id="paso-especialidad" class="paso-agendamiento" style="display:none;">
            <h4 class="text-center mb-4"><i class="fa fa-stethoscope"></i> Seleccione Especialidad</h4>
            
            <div class="form-group">
                <select class="form-control" id="select-especialidad" onchange="habilitarBoton('btn-siguiente-especialidad', this.value)">
                    <option value="">-- Seleccione una especialidad --</option>
                    <option value="cardiologia">Cardiología</option>
                    <option value="pediatria">Pediatría</option>
                    <option value="general">Medicina General</option>
                    <option value="dermatologia">Dermatología</option>
                </select>
            </div>
            
            <div class="text-right mt-3">
                <button class="btn btn-secondary" onclick="retrocederPaso('paso-especialidad', 'paso-sucursal')">
                    <i class="fa fa-arrow-left"></i> Anterior
                </button>
                <button class="btn btn-primary" onclick="avanzarPaso('paso-especialidad', 'paso-doctor')" id="btn-siguiente-especialidad" disabled>
                    Siguiente <i class="fa fa-arrow-right"></i>
                </button>
            </div>
        </div>
        
        <!-- Paso 3: Selección de Doctor -->
        <div id="paso-doctor" class="paso-agendamiento" style="display:none;">
            <h4 class="text-center mb-4"><i class="fa fa-user-md"></i> Seleccione Médico</h4>
            
            <div id="lista-doctores" class="list-group mb-3">
                <!-- Se llenará dinámicamente con JavaScript -->
                <div class="text-center py-4">
                    <i class="fa fa-spinner fa-spin fa-2x"></i>
                    <p>Cargando médicos disponibles...</p>
                </div>
            </div>
            
            <div class="text-right mt-3">
                <button class="btn btn-secondary" onclick="retrocederPaso('paso-doctor', 'paso-especialidad')">
                    <i class="fa fa-arrow-left"></i> Anterior
                </button>
                <button class="btn btn-primary" onclick="avanzarPaso('paso-doctor', 'paso-fecha')" id="btn-siguiente-doctor" disabled>
                    Siguiente <i class="fa fa-arrow-right"></i>
                </button>
            </div>
        </div>
        
        <!-- Paso 4: Selección de Fecha y Hora -->
        <div id="paso-fecha" class="paso-agendamiento" style="display:none;">
            <h4 class="text-center mb-4"><i class="fa fa-calendar"></i> Seleccione Fecha y Hora</h4>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Fecha de la cita</label>
                        <input type="date" class="form-control" id="fecha-cita" min="" onchange="cargarHorariosDisponibles()">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Hora disponible</label>
                        <select class="form-control" id="hora-cita" disabled onchange="habilitarBoton('btn-siguiente-fecha', this.value)">
                            <option value="">Seleccione primero una fecha</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="text-right mt-3">
                <button class="btn btn-secondary" onclick="retrocederPaso('paso-fecha', 'paso-doctor')">
                    <i class="fa fa-arrow-left"></i> Anterior
                </button>
                <button class="btn btn-primary" onclick="avanzarPaso('paso-fecha', 'paso-confirmacion')" id="btn-siguiente-fecha" disabled>
                    Siguiente <i class="fa fa-arrow-right"></i>
                </button>
            </div>
        </div>
        
        <!-- Paso 5: Confirmación -->
        <div id="paso-confirmacion" class="paso-agendamiento" style="display:none;">
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
                <textarea class="form-control" rows="3" id="motivo-consulta" placeholder="Describa brevemente el motivo de su consulta"></textarea>
            </div>
            
            <div class="text-right mt-3">
                <button class="btn btn-secondary" onclick="retrocederPaso('paso-confirmacion', 'paso-fecha')">
                    <i class="fa fa-arrow-left"></i> Anterior
                </button>
                <button class="btn btn-success" onclick="confirmarCita()">
                    <i class="fa fa-calendar-check-o"></i> Confirmar Cita
                </button>
            </div>
        </div>
        
        <!-- Paso 6: Confirmación Exitosa -->
        <div id="paso-exito" class="paso-agendamiento text-center" style="display:none;">
            <div class="mb-4">
                <i class="fa fa-check-circle fa-5x text-success"></i>
            </div>
            <h4>¡Cita agendada con éxito!</h4>
            <p id="detalles-cita-confirmada" class="mb-4"></p>
            <button class="btn btn-primary" onclick="reiniciarFormularioCita(); mostrarSeccion('agendar-cita-modal')">
    <i class="fa fa-plus"></i> Agendar otra cita
</button>
<button class="btn btn-secondary ml-2" onclick="ocultarSecciones(); reiniciarFormularioCita()">
    <i class="fa fa-home"></i> Volver al inicio
</button>
         </div>
     </div>
    </div>

        <!-- Modal Cancelar Cita -->
        <div id="cancelar-cita-modal" class="modal-section">
            <div class="modal-content">
                <h4 class="text-center mb-4"><i class="fa fa-calendar-times-o"></i> Cancelar Cita</h4>
                
                <div class="alert alert-info">
                    <strong>Cita con:</strong> Dra. Ana López (Cardiología)<br>
                    <strong>Fecha:</strong> 15/06/2025 - 10:00 AM
                </div>
                
                <div class="form-group">
                    <label>Motivo de cancelación</label>
                    <select class="form-control">
                        <option>Seleccione un motivo</option>
                        <option>Ya no necesito la consulta</option>
                        <option>Problemas de agenda</option>
                        <option>Prefiero otro médico</option>
                        <option>Otro motivo</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>¿Desea reprogramar la cita?</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="reprogramar" id="reprogramar-si" value="si">
                        <label class="form-check-label" for="reprogramar-si">Sí, deseo reprogramar</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="reprogramar" id="reprogramar-no" value="no" checked>
                        <label class="form-check-label" for="reprogramar-no">No, cancelar definitivamente</label>
                    </div>
                </div>
                
                <div id="reprogramar-options" style="display: none;">
                    <div class="form-group">
                        <label>Nueva fecha preferida</label>
                        <input type="date" class="form-control">
                    </div>
                </div>
                
                <div class="text-right mt-4">
                    <button class="btn btn-secondary" onclick="ocultarSecciones()">
                        <i class="fa fa-arrow-left"></i> Volver
                    </button>
                    <button class="btn btn-danger ml-2" onclick="alert('Cita cancelada correctamente'); ocultarSecciones()">
                        <i class="fa fa-check"></i> Confirmar Cancelación
                    </button>
                </div>
            </div>
        </div>

        <!-- Sección Ver Recetas -->
        <div id="ver-recetas" class="modal-section">
            <div class="modal-content">
                <h4 class="text-center mb-4"><i class="fa fa-file-text-o"></i> Mis Recetas Médicas</h4>
                
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Médico</th>
                                <th>Medicamentos</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>10/05/2025</td>
                                <td>Dra. Ana López</td>
                                <td>Paracetamol 500mg, Ibuprofeno 400mg</td>
                                <td>
                                    <button class="btn btn-sm btn-primary">
                                        <i class="fa fa-download"></i> Descargar
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>15/04/2025</td>
                                <td>Dr. Carlos Méndez</td>
                                <td>Amoxicilina 500mg</td>
                                <td>
                                    <button class="btn btn-sm btn-primary">
                                        <i class="fa fa-download"></i> Descargar
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="text-right mt-3">
                    <button class="btn btn-secondary" onclick="ocultarSecciones()">
                        <i class="fa fa-times"></i> Cerrar
                    </button>
                </div>
            </div>
        </div>

        <!-- Sección Citas Pasadas -->
        <div id="citas-pasadas" class="modal-section">
            <div class="modal-content">
                <h4 class="text-center mb-4"><i class="fa fa-history"></i> Historial de Citas</h4>
                
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Médico</th>
                                <th>Especialidad</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>10/05/2025</td>
                                <td>Dra. Ana López</td>
                                <td>Cardiología</td>
                                <td><span class="badge-estado badge-completada">Completada</span></td>
                                <td>
                                    <button class="btn btn-sm btn-info">
                                        <i class="fa fa-eye"></i> Ver Detalles
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>15/04/2025</td>
                                <td>Dr. Carlos Méndez</td>
                                <td>Pediatría</td>
                                <td><span class="badge-estado badge-completada">Completada</span></td>
                                <td>
                                    <button class="btn btn-sm btn-info">
                                        <i class="fa fa-eye"></i> Ver Detalles
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="text-right mt-3">
                    <button class="btn btn-secondary" onclick="ocultarSecciones()">
                        <i class="fa fa-times"></i> Cerrar
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal Modificar Cita (desde notificación) -->
        <div id="modificar-cita-modal" class="modal-section">
            <div class="modal-content">
                <h4 class="text-center mb-4"><i class="fa fa-calendar-check-o"></i> Reprogramar Cita Cancelada</h4>
                
                <div class="alert alert-warning">
                    <strong>Aviso:</strong> Su cita con el Dr. López el 15/06 ha sido cancelada por el médico.
                </div>
                
                <div class="form-group">
                    <label>¿Desea reprogramar la cita?</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="reprogramar-notif" id="reprogramar-notif-si" value="si" checked>
                        <label class="form-check-label" for="reprogramar-notif-si">Sí, deseo reprogramar</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="reprogramar-notif" id="reprogramar-notif-no" value="no">
                        <label class="form-check-label" for="reprogramar-notif-no">No, cancelar definitivamente</label>
                    </div>
                </div>
                
                <div id="reprogramar-notif-options">
                    <div class="form-group">
                        <label>Nueva fecha preferida</label>
                        <input type="date" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Nueva hora preferida</label>
                        <input type="time" class="form-control">
                    </div>
                </div>
                
                <div class="text-right mt-4">
                    <button class="btn btn-secondary" onclick="ocultarSecciones()">
                        <i class="fa fa-times"></i> Cancelar
                    </button>
                    <button class="btn btn-primary ml-2" onclick="alert('Cita reprogramada correctamente'); ocultarSecciones()">
                        <i class="fa fa-check"></i> Confirmar
                    </button>
                </div>
            </div>
        </div>
    </div>


    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/js/bootstrap.bundle.min.js"></script>


    <script>
    let datosCita = {
        sucursal: '',
        especialidad: '',
        doctor: '',
        fecha: '',
        hora: ''
    };

    function seleccionarSucursal(nombre, card) {
        datosCita.sucursal = nombre;
        document.querySelectorAll('.sucursal-card').forEach(c => c.classList.remove('border-primary'));
        card.classList.add('border', 'border-primary');
        document.getElementById('btn-siguiente-sucursal').disabled = false;
    }

    function avanzarPaso(actual, siguiente) {
        document.getElementById(actual).style.display = 'none';
        document.getElementById(siguiente).style.display = 'block';

        if (siguiente === 'paso-doctor') cargarDoctores();
        if (siguiente === 'paso-confirmacion') actualizarResumen();
    }

    function retrocederPaso(actual, anterior) {
        document.getElementById(actual).style.display = 'none';
        document.getElementById(anterior).style.display = 'block';
    }

    function habilitarBoton(idBoton, valor) {
        datosCita.especialidad = valor;
        document.getElementById(idBoton).disabled = !valor;
    }

    function seleccionarDoctor(nombre) {
        datosCita.doctor = nombre;
        document.querySelectorAll('.doctor-item').forEach(d => d.classList.remove('active'));
        document.querySelector(`[data-nombre="${nombre}"]`).classList.add('active');
        document.getElementById('btn-siguiente-doctor').disabled = false;
    }

    function cargarDoctores() {
        const lista = document.getElementById('lista-doctores');
        lista.innerHTML = '';
        // Simulación de carga
        const doctores = ['Dra. Ana López', 'Dr. Carlos Méndez', 'Dra. Julia Torres'];
        doctores.forEach(nombre => {
            const item = document.createElement('a');
            item.href = '#';
            item.className = 'list-group-item list-group-item-action doctor-item';
            item.textContent = nombre;
            item.setAttribute('data-nombre', nombre);
            item.onclick = () => seleccionarDoctor(nombre);
            lista.appendChild(item);
        });
    }

    function cargarHorariosDisponibles() {
        const fecha = document.getElementById('fecha-cita').value;
        datosCita.fecha = fecha;
        const horaSelect = document.getElementById('hora-cita');
        horaSelect.innerHTML = '<option value="">Seleccione un horario</option>';

        if (fecha) {
            horaSelect.disabled = false;
            const horas = ['08:00', '09:30', '11:00', '13:00'];
            horas.forEach(hora => {
                const opt = document.createElement('option');
                opt.value = hora;
                opt.textContent = hora;
                horaSelect.appendChild(opt);
            });

            horaSelect.onchange = () => {
                datosCita.hora = horaSelect.value;
                habilitarBoton('btn-siguiente-fecha', horaSelect.value);
            };
        } else {
            horaSelect.disabled = true;
        }
    }

    function actualizarResumen() {
        document.getElementById('resumen-sucursal').textContent = datosCita.sucursal;
        document.getElementById('resumen-especialidad').textContent = datosCita.especialidad;
        document.getElementById('resumen-doctor').textContent = datosCita.doctor;
        document.getElementById('resumen-fecha').textContent = `${datosCita.fecha} a las ${datosCita.hora}`;
    }

    function confirmarCita() {
        const motivo = document.getElementById('motivo-consulta').value;
        const resumen = `
            <strong>Sucursal:</strong> ${datosCita.sucursal}<br>
            <strong>Especialidad:</strong> ${datosCita.especialidad}<br>
            <strong>Médico:</strong> ${datosCita.doctor}<br>
            <strong>Fecha:</strong> ${datosCita.fecha}<br>
            <strong>Hora:</strong> ${datosCita.hora}<br>
            <strong>Motivo:</strong> ${motivo || 'No especificado'}
        `;
        document.getElementById('paso-confirmacion').style.display = 'none';
        document.getElementById('paso-exito').style.display = 'block';
        document.getElementById('detalles-cita-confirmada').innerHTML = resumen;

        // Aquí podrías enviar los datos al servidor con fetch()
        // fetch('/api/agendar', { method: 'POST', body: JSON.stringify(datosCita) })
    }
</script>



    <script>
        // Función para mostrar secciones/modales
        function mostrarSeccion(id) {
            document.querySelectorAll('.modal-section').forEach(sec => {
                sec.style.display = 'none';
            });
            document.getElementById(id).style.display = 'flex';
        }

        // Función para ocultar todas las secciones/modales
        function ocultarSecciones() {
            document.querySelectorAll('.modal-section').forEach(sec => {
                sec.style.display = 'none';
            });
        }

        // Mostrar opciones de reprogramación al cancelar cita
        document.querySelectorAll('input[name="reprogramar"]').forEach(radio => {
            radio.addEventListener('change', function() {
                document.getElementById('reprogramar-options').style.display = 
                    this.value === 'si' ? 'block' : 'none';
            });
        });

        // Mostrar opciones de reprogramación en notificación
        document.querySelectorAll('input[name="reprogramar-notif"]').forEach(radio => {
            radio.addEventListener('change', function() {
                document.getElementById('reprogramar-notif-options').style.display = 
                    this.value === 'si' ? 'block' : 'none';
            });
        });

        // Mostrar notificación de ejemplo al cargar la página
        window.onload = function() {
            // Simular notificación de cita cancelada
            document.getElementById('notificaciones').style.display = 'block';
        };
    </script>

    <script src="Frontend/Paciente/citas.js"></script>
</body>
</html>