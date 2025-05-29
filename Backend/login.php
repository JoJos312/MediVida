<?php
include 'confBD.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $contrasena = isset($_POST['contrasena']) ? $_POST['contrasena'] : '';

    if (empty($email) || empty($contrasena)) {
        header("Location: ../Frontend/login.html?error=credenciales");
        exit();
    }

    $conexion = Conectarse();

    // Buscar usuario por correo
    $stmt = $conexion->prepare(
        "SELECT usuario.Contrasena_Hash, usuario.Rol, usuario.Persona
         FROM usuario
         WHERE usuario.Correo = ?"
    );
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $usuario = $resultado->fetch_assoc();
        // Verificar contraseña
        if ($contrasena === $usuario['Contrasena_Hash']) {
            session_start();
            $_SESSION['usuario_email'] = $email;
            $_SESSION['usuario_rol'] = $usuario['Rol'];
            $_SESSION['usuario_persona'] = $usuario['Persona'];
            $_SESSION['persona_id'] = $usuario['Persona'];

            // Redirigir según el rol numérico
            if ($usuario['Rol'] == 1) {
                header("Location: ../Frontend/Admin/admin.html");
            } elseif ($usuario['Rol'] == 2) {
                header("Location: ../Frontend/Doctor/doctor.php");
            } elseif ($usuario['Rol'] == 3) {
                header("Location: ../Frontend/Paciente/paciente.php");
            } else {
                header("Location: ../Frontend/login.html?error=rol");
            }
            exit();
        } else {
            // Contraseña incorrecta
            header("Location: ../Frontend/login.html?error=credenciales");
            exit();
        }
    } else {
        // Usuario no encontrado
        header("Location: ../Frontend/login.html?error=credenciales");
        exit();
    }
    $stmt->close();
    $conexion->close();
} else {
    header("Location: ../Frontend/login.html");
    exit();
}

