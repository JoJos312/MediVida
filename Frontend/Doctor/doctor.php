<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    if(!isset($_SESSION['persona_id'])){
        header("Location: ../login.html?error=credenciales");
        exit();
    }

    require_once '../../Backend/confBD.php';
    $conexion = Conectarse();

    //$sql = "SELECT Nombre FROM persona WHERE ID = "+$_SESSION['usuario_persona'];
    $sql = "SELECT Nombre FROM persona WHERE ID = {$_SESSION['persona_id']}";
    $stmt = $conexion->prepare($sql);
    $stmt->execute();
    $resultado = $stmt->get_result();

    $data = $resultado->fetch_assoc();
    $dr_nombre = $data['Nombre'];
?>

<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,700">

    <link rel="stylesheet" href="../Header/Header.css">
    
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet" />
    <!-- FullCalendar JS -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

    <style>
        @media( max-width: 768px ){
            #content{
                width: 90%;
                margin-inline: 5%;
            }
        }
        @media( min-width: 769px ){
            #content{
                width: 70%;
                margin-inline: 15%;
            }
        }

        #content{
            .content-section{
                border-radius: 0.5rem;
                background-color: whitesmoke;
                padding: 0.6rem;
                margin-block: 1rem;
                margin-inline-end: 1rem;
                width: fit-content;

                position: relative;
                .close-modal{
                    position: absolute;
                    top: 0;
                    right: 0;
                    margin: 10px;
                    color: rgba(255, 0, 0, 0.715);
                    cursor: pointer;
                    transform: scale(1.4);
                }
                .close-modal:hover{
                    transform: scale(1.5);
                }
            }
            #UserPhoto{
                width: 7rem;
                border-radius: 100%;
                margin-inline-end: 1rem;
            }
            #UserName {
                width: 100%;
                color: rgb(1, 0, 55);
                font-size: xx-large;
            }

            #sections{
                display: flex;
                flex-wrap: wrap;
                

                section {
                    height: fit-content;
                }

                #section-meeting {
                    table{
                        tr{
                            cursor:pointer;
                        }
                        th:first-child{
                            font-weight:600;
                            width: 250px;
                        }
                        th:nth-child(2){
                            font-weight: normal;
                            width: 150px;
                            text-align: right;
                        }
                    }
                }

                .InteractiveSection {
                    cursor: pointer;
                    transition: box-shadow 0.15s ease;
                }
                .InteractiveSection:hover{
                    box-shadow:0 0 10px 6px rgba(229, 248, 255, 0.267);
                    transform: scale(1.05);
                }

                #section-2{
                    text-align: center;
                    height: auto;
                    i {
                        margin-block: 15%;
                        font-size: xxx-large;
                    }
                }

                #section-calendar{
                    width: 30rem;
                    height: 35rem;
                }

            }

            #section-new-consult.visible {
                opacity: 1;
                visibility: visible;
            }
            #section-new-consult{
                opacity: 0;
                visibility: hidden;
                transition: opacity 0.15s ease, visibility 0.15s ease;

                position: fixed;
                top: 0;
                left: 0;
                width: 100vw;
                min-height: 100vh;
                background-color: rgba(0, 0, 0, 0.367);
                z-index:999;
                
                @media( max-width: 768px ){
                    .section-new-consult-content{
                        width: 90%;
                        margin-inline: 5%;
                    }
                }
                @media( min-width: 769px ){
                    .section-new-consult-content{
                        width: 60%;
                        margin-inline: 20%;
                    }
                }
                .section-new-consult-content{
                    border-radius: 0.6rem;
                    margin-top: 2%;
                    overflow-y: auto;
                    height: 80vh;
                    section {
                        height: fit-content;
                    }
                    .title{
                        font-size: xx-large;
                    }
                    .btn-group{
                        width: 90%;
                        margin-inline: 5%;
                        .btn{
                            width: 100%;
                        }
                    }
                }
            }

            #section-watch-history.visible {
                opacity: 1;
                visibility: visible;
            }
            #section-watch-history {
                opacity: 0;
                visibility: hidden;
                transition: opacity 0.15s ease, visibility 0.15s ease;

                position: fixed;
                top: 0;
                left: 0;
                width: 100vw;
                min-height: 100vh;
                background-color: rgba(0, 0, 0, 0.367);
                z-index:999;

                @media( max-width: 768px ){
                    .section-watch-history-content{
                        width: 90vw;
                        margin-inline: 5%;
                    }
                }
                @media( min-width: 769px ){
                    .section-watch-history-content{
                        width: 70vw;
                        margin-inline: 15%;
                    }
                }

                .section-watch-history-content{
                    display: flex;
                    justify-content: center;
                    border-radius: 0.6rem;
                    padding-top: 5%;
                    
                    .title{
                        font-size: xx-large;
                    }
                    .content-section{
                        width: 100%;
                        table{
                            width: 90%;
                            margin-inline: 2rem;
                            border-collapse: separate;
                            border-spacing: 0 20px;
                            th:nth-child(2){
                                text-align: right;
                                padding-inline-start: 3rem;
                            }
                        }
                        #section-watch-history-table{
                            tr{
                                cursor: pointer;
                            }
                            tr:hover{
                                color: rgb(84, 84, 84);
                            }
                            th:nth-child(2){
                                text-align: right;
                                padding-inline-start: 3rem;
                            }
                        }

                        .page-control{
                            display: flex;
                            span{
                                width: 100%;
                                text-align: center;
                            }
                        }
                    }
                }
            }

            
            #section-watch-person-history.visible {
                opacity: 1;
                visibility: visible;
            }
            #section-watch-person-history {
                opacity: 0;
                visibility: hidden;
                transition: opacity 0.15s ease, visibility 0.15s ease;

                position: fixed;
                top: 0;
                left: 0;
                width: 100vw;
                min-height: 100vh;
                background-color: rgba(0, 0, 0, 0.367);
                z-index:999;

                @media( max-width: 768px ){
                    .section-watch-person-history-content{
                        width: 90vw;
                        margin-inline: 5%;
                    }
                }
                @media( min-width: 769px ){
                    .section-watch-person-history-content{
                        width: 70vw;
                        margin-inline: 15%;
                    }
                }

                .section-watch-person-history-content{
                    display: flex;
                    justify-content: center;
                    border-radius: 0.6rem;
                    padding-top: 5%;
                    
                    .title{
                        font-size: xx-large;
                    }
                    .content-section{
                        width: 100%;
                        height: 75vh;
                        padding: 1.5rem;
                        overflow-y: auto;

                        .table-scroll{
                            overflow-y: auto;
                            height: 40vh;
                            table{
                                margin-inline: 2rem;
                                border-collapse: separate;
                                border-spacing: 0 20px;

                                tr{
                                    cursor: pointer;
                                }
                                tr:hover{
                                    color: rgb(84, 84, 84);
                                }
                                th:nth-child(2){
                                    width: 100%;
                                    text-align: right;
                                    padding-inline-start: 3rem;
                                }
                            }
                        }
                        
                        .page-control{
                            display: flex;
                            span{
                                width: 100%;
                                text-align: center;
                            }
                        }
                    }
                }
            }

            #section-consult-history.visible {
                opacity: 1;
                visibility: visible;
            }
            #section-consult-history {
                opacity: 0;
                visibility: hidden;
                transition: opacity 0.15s ease, visibility 0.15s ease;

                position: fixed;
                top: 0;
                left: 0;
                width: 100vw;
                min-height: 100vh;
                background-color: rgba(0, 0, 0, 0.367);
                z-index:999;

                @media( max-width: 768px ){
                    .section-watch-person-history-content{
                        width: 90vw;
                        margin-inline: 5vw;
                    }
                }
                @media( min-width: 769px ){
                    .section-consult-history-content{
                        width: 70vw;
                        margin-inline: 15vw;
                    }
                }

                .section-consult-history-content{
                    display: flex;
                    justify-content: center;
                    border-radius: 0.6rem;
                    padding-top: 5%;
                    
                    .title{
                        font-size: xx-large;
                    }
                    .subtitle{
                        font-size: x-large;
                    }
                    .content-section{
                        width: 100%;
                        height: 75vh;
                        padding: 1.5rem;
                        overflow-y: auto;
                    }
                }
            }


            #modal-calendar-meeting{
                display:none;
                position:fixed;
                top:20%;
                left:50%;
                transform:translateX(-50%);
                background:#fff;
                padding:20px;
                box-shadow:0 0 10px rgba(0,0,0,0.3);
                border-radius:8px;
                z-index:999;
            }
        }

    </style>

</head>
<body>
    <Header>
        <div class="header-blue">
            <nav class="navbar navbar-dark navbar-expand-md navigation-clean-search">
                <div class="container">
                    <a class="navbar-brand" href="../../index.html">MediVida</a>
                    <button class="navbar-toggler" data-toggle="collapse" data-target="#navcol-1">
                        <span class="sr-only">Toggle navigation</span>
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
                            </li>
                        </ul>
                        <div class="mr-auto"></div>

                        <ul class="nav navbar-nav">
                            <li class="dropdown"><a class="dropdown-toggle nav-link dropdown-toggle" data-toggle="dropdown" aria-expanded="false" href="#"><b>Hola pibe</b></a>
                                <div class="dropdown-menu" role="menu">
                                    <a class="dropdown-item" role="presentation" href="#">Configuracion</a>
                                    <a class="dropdown-item" role="presentation" href="#">Cerrar Sesion</a>
                                </div>
                            </li>
                        </ul>
                        <!-- <span class="navbar-text">
                            <a href="#" class="login">Iniciar Sesion</a>
                        </span>
                        <a class="btn btn-light action-button" role="button" href="#">Registrarme</a> -->
                    </div>
                </div>
            </nav>
        </div>
    </Header>

    <!-- Contenido -->
    <div id="content">
        <img src="../../Img/dr.jpg" alt="" id="UserPhoto">
        <span id="UserName" class="content-section">Bienvenido, Dr. <?php echo $dr_nombre ?></span>
        <div id="sections">
            <!-- Secciones Interactuables -->
            <section id="section-2" class="content-section InteractiveSection" onclick="ToggleWatchHistory()">
                <!-- Citas pendientes -->
                <h4>Ver Expedientes</h4>
                <i class="fa fa-archive"></i> 
            </section>

            <!-- Citas del dia -->
            <section id="section-meeting" class="content-section">
                 <h4>Citas del Dia</h4>
                 <hr>
                 <table>
                    <tr>
                        <th>Hoy</th>
                    </tr>

                    <?php
                        // Todas las citas, ajustar para que solo sean las fechas del dia actual
                        $sql = "SELECT c.ID, p.Nombre, c.Fecha_Inicio FROM cita c 
                        INNER JOIN persona p ON p.ID = c.Paciente_ID
                        WHERE c.Doctor_ID = {$_SESSION['persona_id']} AND DATE(c.Fecha_Inicio)=CURDATE()";
                        $stmt = $conexion->prepare($sql);
                        $stmt->execute();
                        $resultado = $stmt->get_result();
                        if($resultado->num_rows > 0)
                            while ($row = $resultado->fetch_array(MYSQLI_ASSOC)) {
                                echo "<tr onclick=\"ToggleNewConsult(".htmlspecialchars($row['ID']).")\">";
                                echo "<th>" . htmlspecialchars($row['Nombre']) . "</th>";
                                echo "<th>" . htmlspecialchars($row['Fecha_Inicio']) . "</th>";
                                echo "</tr>";
                            }
                    ?>
                 </table>
            </section> 

            <!-- Calendario -->
            <section id="section-calendar" class="content-section"></section>

            <!-- Modals -->
            <!-- Nueva consulta -->
            <section id="section-new-consult" class="oculto">
                <div class="section-new-consult-content content-section">
                    <i class="fa fa-times close-modal" onclick="ToggleNewConsult()"></i>
                    <span class="title">Nueva consulta</span>
                    <hr>
                    <label><b>Paciente</b></label>
                    <div id="section-new-consult-nombre"></div>
                    <input type="hidden" id="paciente_id" value="">
                    <label><b>Motivo de la consulta</b></label>
                    <textarea class="form-control" id="section-new-consult-motivo" rows="4"></textarea>
                    <label><b>Diagnostico</b></label>
                    <textarea class="form-control" id="section-new-consult-diagnostico" rows="4"></textarea>
                    <label><b>Receta</b></label>
                    <textarea class="form-control" id="section-new-consult-receta" rows="4"></textarea>
                    <label><b>Detalles</b></label>
                    <textarea class="form-control" id="section-new-consult-detalles" rows="4"></textarea>

                    <div class="btn-group" role="group">
                        <button class="btn btn-danger" onclick="DeleteNewConsult()">Borrar</button>
                        <button class="btn btn-info" onclick="SaveNewConsult()">Guardar</button>
                    </div>
                </div>
            </section>


            
            <!-- Ver historial -->
            <section id="section-watch-history" class="oculto">
                <div class="section-watch-history-content">
                    <section class="content-section">
                        <i class="fa fa-times close-modal" onclick="ToggleWatchHistory()"></i>
                        <span class="title">Expedientes</span>
                        <hr>
                        <table>
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Ultima Cita</th>
                                </tr>
                            </thead>
                            <tbody id="section-watch-history-table">
                            </tbody>
                        </table>
                        <hr>
                        <div class="page-control">
                            <button class="btn btn-outline-primary" onclick="AddPageWatchHistory(-1)">Anterior</button>
                            <span id="section-watch-history-number-page" value="1">Pagina 1 de 10</span>
                            <button class="btn btn-outline-primary" onclick="AddPageWatchHistory(1)">Siguiente</button>
                        </div>
                    </section>
                </div>
            </section>

            <!-- Ver el historial de una persona -->
            <section id="section-watch-person-history" class="oculto">
                <div class="section-watch-person-history-content">
                    <section class="content-section">
                        <i class="fa fa-times close-modal" onclick="ToggleWatchPersonHistory()"></i>
                        <span class="title">Expediente</span>
                        <hr>
                        <span><b>Nombre: </b><span id="section-watch-person-history-nombre"></span></span>
                        <br>
                        <span><b>Telefono: </b><span id="section-watch-person-history-telefono"></span></span>
                        <br>
                        <span><b>Fecha de Nacimiento: </b><span id="section-watch-person-history-nacimiento"></span></span>
                        <hr>
                        <div class="table-scroll">
                            <table>
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Fecha</th>
                                    </tr>
                                </thead>
                                <!-- Max 10 consultas por pagina -->
                                <tbody id="section-watch-person-history-table">
                                    <tr onclick="ShowConsultByID(1)">
                                        <th>1</th>
                                        <th>2005-01-01</th>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <hr>
                        <div class="page-control">
                            <button class="btn btn-outline-primary" onclick="AddPageWatchPersonHistory(-1)">Anterior</button>
                            <span id="section-watch-person-history-number-page" value="1">Pagina 1 de 10</span>
                            <button class="btn btn-outline-primary" onclick="AddPageWatchPersonHistory(1)">Siguiente</button>
                        </div>
                    </section>
                </div>
            </section>

            <!-- Ver los datos de la consulta -->
            <section id="section-consult-history" class="oculto">
                <div class="section-consult-history-content">
                    <section class="content-section">
                        <i class="fa fa-times close-modal" onclick="ToggleConsultHistory()"></i>
                        <span class="title">Expediente</span>
                        <hr>
                        <span><b>Nombre: </b><span id="section-consult-history-nombre">Kevin Vladimir</span></span>
                        <hr>
                        <span class="subtitle">Datos de la consulta</span>
                        <div><b>Motivo</b></div>
                        <p id="section-consult-history-motivo">El motivo es que esta pendejo</p>
                        <div><b>Diagnostico</b></div>
                        <p id="section-consult-history-diagnostico">Mucho fri fais</p>
                        <div><b>Receta</b></div>
                        <p id="section-consult-history-receta">Unos focos</p>
                        <div><b>Detalles</b></div>
                        <p id="section-consult-history-detalles">La neta este paciente me da un pinche perro asco ojala y le peguen una madriza</p>
                    </section>
                </div>
            </section>

            <div id="modal-calendar-meeting">
                <section class="content-section">
                    <h3>Citas del día</h3>
                    <ul id="lista-citas"></ul>
                    <button onclick="document.getElementById('modal-calendar-meeting').style.display='none'">Cerrar</button>
                </section>
            </div>
        </div>
    </div>

</body>
</html>

<!-- #section-consult-history -->
<script>
    function ToggleConsultHistory(id){
        const ele = document.getElementById("section-consult-history");
        ele.classList.toggle("visible");

        if(!ele.classList.contains("visible"))
            return;

        const nombre = document.getElementById("section-consult-history-nombre");
        const motivo = document.getElementById("section-consult-history-motivo");
        const diagnostico = document.getElementById("section-consult-history-diagnostico");
        const receta = document.getElementById("section-consult-history-receta");
        const detalles = document.getElementById("section-consult-history-detalles");
        

        fetch("../../Backend/Doctor/GetDatosConsultaPaciente.php?ID=" + id)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    nombre.innerHTML = data.error;
                } else {
                    nombre.innerHTML = data.Nombre+" "+data.Ape_Ma+" "+data.Ape_Ma;
                    motivo.innerHTML = data.Motivo;
                    diagnostico.innerHTML = data.Diagnostico;
                    receta.innerHTML = data.Tratamiento;
                    detalles.innerHTML = data.Notas;
                }
                
            })
            .catch(error => console.error("Error:", error));

    }
</script>

<!-- #section-watch-person-history -->
<script>
    function ShowConsultByID(id){
        // Cargar los datos de la consulta
        ToggleConsultHistory();
    }

    function AddPageWatchPersonHistory(value){
        document.getElementById("section-watch-person-history-number-page").value += value;
        ChangeDataPageWatchHistory(document.getElementById("section-watch-person-history-number-page").value);
    }

    function ChangeDataPageWatchPersonHistory(id,page){
        const table = document.getElementById("section-watch-person-history-table");
    
        table.innerHTML = "";
        
        var count = 0;

        fetch("../../Backend/Doctor/GetConsultasPaciente.php?id="+id+"&page="+page)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.log("Error:", data.error);
            } else {
                data.forEach(consulta => {
                    count++;
                    table.innerHTML += "<tr onclick=\"ToggleConsultHistory("+consulta.ID+")\"><th>"+count+"</th><th>"+consulta.Fecha+"</th></tr>";
                });
            }
        });
    }

    function ToggleWatchPersonHistory(id){
        const ele = document.getElementById("section-watch-person-history");
        ele.classList.toggle("visible");
        
        if(!ele.classList.contains("visible"))
            return;

        const nombre = document.getElementById("section-watch-person-history-nombre");
        const telefono = document.getElementById("section-watch-person-history-telefono");
        const nacimiento = document.getElementById("section-watch-person-history-nacimiento");


        fetch("../../Backend/Doctor/GetPacienteByID.php?ID=" + id)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    nombre.innerHTML = data.error;
                } else {
                    nombre.innerHTML = data.Nombre+" "+data.Ape_Pa+" "+data.Ape_Ma;
                    telefono.innerHTML = data.Telefono;
                    nacimiento.innerHTML = data.Fecha_Nacimiento;
                }
                paciente_id.value = id;
            })
            .catch(error => console.error("Error:", error));

        ChangeDataPageWatchPersonHistory(id,1);
    }
</script>

<!-- #section-watch-history -->
<script>
    function ChangeDataPageWatchHistory(page){
        const table = document.getElementById("section-watch-history-table");
        
        table.innerHTML = "";
        
        fetch("../../Backend/Doctor/GetExpedientes.php?page="+page)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.log("Error:", data.error);
            } else {
                data.forEach(paciente => {
                    table.innerHTML += "<tr onclick=\"ToggleWatchPersonHistory("+paciente.ID+")\"><th>"+paciente.Nombre+" "+paciente.Ape_Pa+" "+paciente.Ape_Ma+"</th><th>"+paciente.Fecha+"</th></tr>";
                });
            }
        });
    }

    function AddPageWatchHistory(value){
        document.getElementById("section-watch-history-number-page").value += value;
        ChangeDataPageWatchHistory(document.getElementById("section-watch-history-number-page").value);
    }

    function ToggleWatchHistory(){
        const ele = document.getElementById("section-watch-history");
        ele.classList.toggle("visible");
        if( ele.classList.contains("visible") ){
            document.getElementById("section-watch-history-number-page").value = 1;
            document.body.style.overflowY = "hidden";
            ChangeDataPageWatchHistory(1);
        }else{
            document.body.style.overflowY = "auto";
        }
    }
</script>

<script>
    function DeleteNewConsult(){
        // Solo se borran los datos que esten en los campos
        document.getElementById("section-new-consult-motivo").value = "";
        document.getElementById("section-new-consult-diagnostico").value = "";
        document.getElementById("section-new-consult-receta").value = "";
        document.getElementById("section-new-consult-detalles").value = "";
    }
    function SaveNewConsult(){
        const paciente_id = document.getElementById("paciente_id");
        const motivo = document.getElementById("section-new-consult-motivo");
        const diagnostico = document.getElementById("section-new-consult-diagnostico");
        const receta = document.getElementById("section-new-consult-receta");
        const detalles = document.getElementById("section-new-consult-detalles");

        const datos = new FormData();
        datos.append("id",paciente_id.value);
        datos.append("motivo",motivo.value);
        datos.append("diagnostico",diagnostico.value);
        datos.append("receta",receta.value);
        datos.append("detalles",detalles.value);
        
        
        fetch("../../Backend/Doctor/InsertarConsulta.php", {
            method: "POST",
            body: datos
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.mensaje);
                DeleteNewConsult();
                ToggleNewConsult(0);
            } else {
                alert(data.error);
            }
        })
        .catch(error => alert("Error en la solicitud:", error));
    }


    function ToggleNewConsult(id){
        if(id >= 1){

            const paciente_id = document.getElementById("paciente_id");
            const nombre_paciente = document.getElementById("section-new-consult-nombre");
            
            fetch("../../Backend/Doctor/GetPacienteByCitaID.php?ID=" + id)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    nombre_paciente.innerHTML = data.error;
                } else {
                    nombre_paciente.innerHTML = data.Nombre+" "+data.Ape_Pa+" "+data.Ape_Ma;
                }
                paciente_id.value = id;
            })
            .catch(error => console.error("Error:", error));
        }

        const ele = document.getElementById("section-new-consult");
        ele.classList.toggle("visible");

        if( ele.classList.contains("visible") )
            document.body.style.overflowY = "hidden";
        else
            document.body.style.overflowY = "auto";
    }
</script>

<script>
    function MostrarCitasPorFecha(fecha){

    }

  // Citas por día
  const citasPorDia = {
    '2025-06-05': [
      { hora: '10:00', descripcion: 'Consulta con el Dr. Pérez' },
      { hora: '14:00', descripcion: 'Chequeo general' }
    ],
    '2025-06-12': [
      { hora: '09:30', descripcion: 'Control de presión' }
    ]
  };

  document.addEventListener('DOMContentLoaded', function () {
    var calendarEl = document.getElementById('section-calendar');

    // Convertimos los datos a eventos visuales en el calendario
    const eventos = Object.keys(citasPorDia).map(fecha => ({
      start: fecha,
      display: 'background',
      backgroundColor: '#34c759' // Verde
    }));

    var calendar = new FullCalendar.Calendar(calendarEl, {
      initialView: 'dayGridMonth',
      events: eventos,
      dateClick: function (info) {
        const fecha = info.dateStr;
        const citas = citasPorDia[fecha];
        if (citas) {
          const lista = document.getElementById('lista-citas');
          lista.innerHTML = '';
          citas.forEach(cita => {
            const li = document.createElement('li');
            li.textContent = `${cita.hora} - ${cita.descripcion}`;
            lista.appendChild(li);
          });
          document.getElementById('modal-calendar-meeting').style.display = 'block';
        }
      }
    });
    calendar.render();
  });
</script>


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/js/bootstrap.bundle.min.js"></script>
