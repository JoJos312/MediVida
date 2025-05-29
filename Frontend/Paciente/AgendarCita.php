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
        .paso-agendamiento {
            display: none;
            padding: 20px;
        }

        .paso-activo {
            display: block;
        }

        /* Tarjetas de selección */
        .card-sucursal,
        .card-doctor {
            cursor: pointer;
            transition: all 0.3s;
            border: 1px solid #dee2e6;
            margin-bottom: 15px;
        }

        .card-sucursal:hover,
        .card-doctor:hover {
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
            color: #1a73e8 !important;
            /* Un azul de Google, puedes cambiar a tu gusto */
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
                    <a class="btn btn-light action-button" role="button" href="../../Backend/CerrarSesion.php"><i class="fa fa-sign-out"></i> Cerrar
                        Sesión</a>
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

        <form id="form-cita" action="cita-confirmada.php" method="GET">
            <div class="progress mb-4">
                <div id="progreso" class="progress-bar" role="progressbar" style="width: 20%">Paso 1 de 5</div>
            </div>

            <div id="paso-sucursal" class="paso-agendamiento paso-activo">
                <h4 class="text-center mb-4"><i class="fa fa-hospital-o"></i> Seleccione Sucursal</h4>
                <div class="row" id="contenedor-sucursales">
                    <!-- Aquí se insertarán las tarjetas dinámicamente -->
                </div>
            </div>

            <div id="paso-especialidad" class="paso-agendamiento">
                <h4 class="text-center mb-4"><i class="fa fa-stethoscope"></i> Seleccione Especialidad</h4>
                <div class="form-group">
                    <select class="form-control" id="select-especialidad" name="especialidad" required>
                        <option value="">-- Seleccione una especialidad --</option>
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
                            <label>Día disponible</label>
                            <select class="form-control" id="dia-cita" name="dia" required>
                                <option value="">Seleccione primero un médico</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Hora disponible</label>
                            <select class="form-control" id="hora-cita" name="hora" required disabled>
                                <option value="">Seleccione primero un día</option>
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
                    <textarea class="form-control" rows="3" id="motivo-consulta" name="motivo"
                        placeholder="Describa brevemente el motivo de su consulta"></textarea>
                </div>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <button type="button" class="btn btn-secondary" id="btn-anterior" onclick="retrocederPaso()"
                    style="display: none;">
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

</body>

</html>

<script>
    // Variables de control
    let pasoActual = 1;
    const totalPasos = 5;
    const datosCita = {
        sucursal: null,
        sucursalNombre: null,
        especialidad: null,
        especialidadNombre: null,
        doctor: null,
        doctorId: null,
        fecha: null,
        hora: null
    };

    // Avanzar paso
    function avanzarPaso() {
        if (!validarPasoActual()) return;
        document.getElementById(`paso-${obtenerNombrePaso(pasoActual)}`).classList.remove('paso-activo');
        pasoActual++;
        if (pasoActual === totalPasos) {
            document.getElementById('btn-siguiente').style.display = 'none';
            document.getElementById('btn-confirmar').style.display = 'block';
            actualizarResumen();
        }
        if (pasoActual > 1) {
            document.getElementById('btn-anterior').style.display = 'block';
        }
        document.getElementById(`paso-${obtenerNombrePaso(pasoActual)}`).classList.add('paso-activo');
        actualizarProgreso();
        accionesPorPaso();
    }

    // Retroceder paso
    function retrocederPaso() {
        document.getElementById(`paso-${obtenerNombrePaso(pasoActual)}`).classList.remove('paso-activo');
        pasoActual--;
        if (pasoActual === 1) {
            document.getElementById('btn-anterior').style.display = 'none';
        }
        if (pasoActual === totalPasos - 1) {
            document.getElementById('btn-siguiente').style.display = 'block';
            document.getElementById('btn-confirmar').style.display = 'none';
        }
        document.getElementById(`paso-${obtenerNombrePaso(pasoActual)}`).classList.add('paso-activo');
        actualizarProgreso();
        accionesPorPaso();
    }

    // Nombre de cada paso
    function obtenerNombrePaso(numero) {
        const pasos = ['', 'sucursal', 'especialidad', 'doctor', 'fecha', 'confirmacion'];
        return pasos[numero];
    }

    // Barra de progreso
    function actualizarProgreso() {
        const porcentaje = (pasoActual / totalPasos) * 100;
        const progreso = document.getElementById('progreso');
        progreso.style.width = `${porcentaje}%`;
        progreso.textContent = `Paso ${pasoActual} de ${totalPasos}`;
    }

    // Validar paso actual
    function validarPasoActual() {
        switch (pasoActual) {
            case 1:
                if (!datosCita.sucursal) {
                    alert('Por favor seleccione una sucursal');
                    return false;
                }
                break;
            case 2:
                const especialidad = document.getElementById('select-especialidad').value;
                if (!especialidad) {
                    alert('Por favor seleccione una especialidad');
                    return false;
                }
                datosCita.especialidad = especialidad;
                datosCita.especialidadNombre = document.getElementById('select-especialidad').selectedOptions[0].textContent;
                break;
            case 3:
                if (!datosCita.doctorId) {
                    alert('Por favor seleccione un médico');
                    return false;
                }
                break;
            case 4:
                const fecha = document.getElementById('dia-cita').value;
                const hora = document.getElementById('hora-cita').value;
                if (!fecha || !hora) {
                    alert('Por favor seleccione día y hora');
                    return false;
                }
                datosCita.fecha = fecha;
                datosCita.hora = hora;
                break;
        }
        return true;
    }

    // Acciones por paso
    function accionesPorPaso() {
        switch (pasoActual) {
            case 3:
                cargarDoctores();
                document.getElementById('btn-siguiente').disabled = true;
                break;
            case 4:
                cargarDiasDisponibles();
                break;
        }
    }

    // Resumen
    function actualizarResumen() {
        document.getElementById('resumen-sucursal').textContent = datosCita.sucursalNombre || '-';
        document.getElementById('resumen-especialidad').textContent = datosCita.especialidadNombre || '-';
        document.getElementById('resumen-doctor').textContent = datosCita.doctor || '-';
        document.getElementById('resumen-fecha').textContent = datosCita.fecha && datosCita.hora ? `${datosCita.fecha} a las ${datosCita.hora}` : '-';
    }

    // Sucursales dinámicas
    function cargarSucursales() {
        fetch('../../Backend/agendarCita.php?accion=get_sucursales')
            .then(r => r.json())
            .then(resp => {
                const contenedor = document.getElementById('contenedor-sucursales');
                contenedor.innerHTML = '';
                if (resp.success && resp.data.length > 0) {
                    resp.data.forEach(suc => {
                        const col = document.createElement('div');
                        col.className = 'col-md-6 mb-4';
                        col.innerHTML = `
                        <div class="card card-sucursal" onclick="seleccionarSucursal('${suc.ID}', '${suc.Nombre}', this)">
                            <div class="card-body text-center">
                                <i class="fa fa-map-marker fa-3x mb-3 text-primary"></i>
                                <h5>${suc.Nombre}</h5>
                            </div>
                        </div>
                    `;
                        contenedor.appendChild(col);
                    });
                } else {
                    contenedor.innerHTML = '<div class="col-12 text-center text-danger">No hay sucursales disponibles</div>';
                }
            });
    }
    document.addEventListener('DOMContentLoaded', cargarSucursales);

    // Seleccionar sucursal
    function seleccionarSucursal(id, nombre, elemento) {
        datosCita.sucursal = id;
        datosCita.sucursalNombre = nombre;
        document.querySelectorAll('.card-sucursal').forEach(c => c.classList.remove('seleccionado'));
        elemento.classList.add('seleccionado');
        document.getElementById('btn-siguiente').disabled = false;
        cargarEspecialidades();
        document.getElementById('select-especialidad').value = '';
        datosCita.especialidad = null;
        datosCita.especialidadNombre = null;
        document.getElementById('lista-doctores').innerHTML = '';
        document.getElementById('input-doctor').value = '';
        datosCita.doctor = null;
        datosCita.doctorId = null;
    }

    // Especialidades dinámicas
    function cargarEspecialidades() {
        const sucursalId = datosCita.sucursal;
        const select = document.getElementById('select-especialidad');
        select.innerHTML = '<option value="">Cargando especialidades...</option>';
        if (!sucursalId) {
            select.innerHTML = '<option value="">Seleccione primero una sucursal</option>';
            return;
        }
        fetch(`../../Backend/agendarCita.php?accion=get_especialidades_sucursal&sucursal=${sucursalId}`)
            .then(r => r.json())
            .then(resp => {
                select.innerHTML = '<option value="">-- Seleccione una especialidad --</option>';
                if (resp.success && resp.data.length > 0) {
                    resp.data.forEach(esp => {
                        const option = document.createElement('option');
                        option.value = esp.ID;
                        option.textContent = esp.Nombre;
                        select.appendChild(option);
                    });
                } else {
                    select.innerHTML = '<option value="">No hay especialidades disponibles</option>';
                }
            });
        document.getElementById('lista-doctores').innerHTML = '';
        document.getElementById('input-doctor').value = '';
        datosCita.doctor = null;
        datosCita.doctorId = null;
    }

    // Al cambiar especialidad
    document.getElementById('select-especialidad').addEventListener('change', function () {
        datosCita.especialidad = this.value;
        datosCita.especialidadNombre = this.selectedOptions[0] ? this.selectedOptions[0].textContent : '';
        cargarDoctores();
        document.getElementById('input-doctor').value = '';
        datosCita.doctor = null;
        datosCita.doctorId = null;
    });

    // Doctores dinámicos
    function cargarDoctores() {
        const lista = document.getElementById('lista-doctores');
        lista.innerHTML = '<div class="text-center py-4"><i class="fa fa-spinner fa-spin fa-2x"></i><p>Cargando médicos disponibles...</p></div>';

        const sucursalId = datosCita.sucursal;
        const especialidadId = document.getElementById('select-especialidad').value;
        if (!sucursalId || !especialidadId) {
            lista.innerHTML = '<div class="text-center text-danger">Seleccione primero una sucursal y especialidad</div>';
            return;
        }

        fetch(`../../Backend/agendarCita.php?accion=get_doctores_sucursal_especialidad&sucursal=${sucursalId}&especialidad=${especialidadId}`)
            .then(r => r.json())
            .then(resp => {
                lista.innerHTML = '';
                if (resp.success && resp.data.length > 0) {
                    resp.data.forEach(doctor => {
                        const item = document.createElement('a');
                        item.href = '#';
                        item.className = 'list-group-item list-group-item-action card-doctor';
                        item.innerHTML = `
                        <div class="d-flex align-items-center">
                            <div>
                                <h6 class="mb-1">${doctor.nombre}</h6>
                            </div>
                        </div>
                    `;
                        item.onclick = (e) => {
                            e.preventDefault();
                            document.querySelectorAll('.card-doctor').forEach(d => d.classList.remove('seleccionado'));
                            item.classList.add('seleccionado');
                            datosCita.doctor = doctor.nombre;
                            datosCita.doctorId = doctor.id;
                            document.getElementById('input-doctor').value = doctor.id;
                            document.getElementById('btn-siguiente').disabled = false;
                            // Solo carga días cuando el usuario está en el paso de fecha
                            if (pasoActual === 4) cargarDiasDisponibles();
                        };
                        lista.appendChild(item);
                    });
                } else {
                    lista.innerHTML = '<div class="text-center text-danger">No hay médicos disponibles para esta especialidad en la sucursal seleccionada</div>';
                }
            });
    }

    // Días disponibles para el doctor en la sucursal (para el select)
    function cargarDiasDisponibles() {
        const doctorId = datosCita.doctorId;
        const sucursalId = datosCita.sucursal;
        const diaSelect = document.getElementById('dia-cita');
        const horaSelect = document.getElementById('hora-cita');

        // Solo limpia y llena cuando estás en el paso de fecha
        diaSelect.innerHTML = '<option value="">Cargando días...</option>';
        diaSelect.disabled = true;
        horaSelect.innerHTML = '<option value="">Seleccione primero un día</option>';
        horaSelect.disabled = true;

        if (!doctorId || !sucursalId) {
            diaSelect.innerHTML = '<option value="">Seleccione primero un médico</option>';
            diaSelect.disabled = true;
            return;
        }

        fetch(`../../Backend/agendarCita.php?accion=get_dias_disponibles_doctor&doctor=${doctorId}&sucursal=${sucursalId}`)
            .then(r => r.json())
            .then(resp => {
                diaSelect.innerHTML = '<option value="">-- Seleccione un día --</option>';
                if (resp.success && Array.isArray(resp.data) && resp.data.length > 0) {
                    resp.data.forEach(fecha => {
                        const option = document.createElement('option');
                        option.value = String(fecha);
                        option.textContent = String(fecha);
                        diaSelect.appendChild(option);
                    });
                    diaSelect.disabled = false;
                } else {
                    diaSelect.innerHTML = '<option value="">No hay días disponibles</option>';
                    diaSelect.disabled = true;
                }
            })
            .catch(err => {
                console.error('Error al cargar días:', err);
                diaSelect.innerHTML = '<option value="">Error al cargar días</option>';
                diaSelect.disabled = true;
            });
    }

    // Al cambiar día, cargar horarios disponibles
    document.getElementById('dia-cita').addEventListener('change', function () {
        const fecha = this.value;
        const horaSelect = document.getElementById('hora-cita');
        horaSelect.innerHTML = '<option value="">Cargando horarios...</option>';
        horaSelect.disabled = true;

        const doctorId = datosCita.doctorId;
        const sucursalId = datosCita.sucursal;
        if (!doctorId || !sucursalId || !fecha) {
            horaSelect.innerHTML = '<option value="">Seleccione primero un día</option>';
            horaSelect.disabled = true;
            return;
        }

        fetch(`../../Backend/agendarCita.php?accion=get_horas_disponibles_doctor&doctor=${doctorId}&sucursal=${sucursalId}&fecha=${fecha}`)
            .then(r => r.json())
            .then(resp => {
                horaSelect.innerHTML = '<option value="">-- Seleccione una hora --</option>';
                if (resp.success && resp.data.length > 0) {
                    resp.data.forEach(hora => {
                        const option = document.createElement('option');
                        option.value = hora;
                        option.textContent = hora;
                        horaSelect.appendChild(option);
                    });
                    horaSelect.disabled = false;
                } else {
                    horaSelect.innerHTML = '<option value="">No hay horarios disponibles</option>';
                    horaSelect.disabled = true;
                }
            })
            .catch(() => {
                horaSelect.innerHTML = '<option value="">Error al cargar horarios</option>';
                horaSelect.disabled = true;
            });
    });

    document.getElementById('form-cita').addEventListener('submit', function (e) {
        e.preventDefault();

        // Toma los datos seleccionados
        const sucursal = datosCita.sucursal;
        const especialidad = datosCita.especialidad;
        const doctor = datosCita.doctorId;
        const fecha = document.getElementById('dia-cita').value;
        const hora = document.getElementById('hora-cita').value;
        const motivo = document.getElementById('motivo-consulta').value;

        // Validación simple
        if (!sucursal || !especialidad || !doctor || !fecha || !hora) {
            alert('Por favor complete todos los campos obligatorios.');
            return;
        }

        // Envía los datos al backend
        fetch('../../Backend/agendarCita.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            credentials: 'include', // <-- esto es clave
            body: JSON.stringify({
                accion: 'insertar_cita',
                doctor,
                fecha,
                hora,
                motivo
            })
        })
            .then(r => r.json())
            .then(resp => {
                if (resp.success) {
                    // Redirige o muestra mensaje de éxito
                    window.location.href = 'cita-confirmada.php';
                } else {
                    alert(resp.message || 'No se pudo agendar la cita.');
                }
            })
            .catch(() => {
                alert('Error al conectar con el servidor.');
            });
    });
</script>