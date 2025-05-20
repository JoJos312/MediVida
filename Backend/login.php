<?php
session_start();

// Configuración de conexión
$host = "localhost:3307";
$user = "root";
$pass = ""; // Si has configurado contraseña, colócala aquí
$db = "loginprueba";

// Intenta conectar
try {
    $conn = new mysqli($host, $user, $pass, $db);
    
    if ($conn->connect_error) {
        throw new Exception("Error de conexión: " . $conn->connect_error);
    }
    
    // Verifica si es una solicitud POST
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $conn->real_escape_string($_POST['email'] ?? '');
        $contrasena = $_POST['contrasena'] ?? '';
        
        // Consulta preparada para mayor seguridad
        $sql = "SELECT id, usuario, contrasena, rol FROM usuarios WHERE email = ?";
        $stmt = $conn->prepare($sql);
        
        if (!$stmt) {
            throw new Exception("Error en la preparación: " . $conn->error);
        }
        
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            
            // Verificación de contraseña (debería ser con password_verify si usas hash)
            if ($contrasena === $user['contrasena']) { // ¡Cambia esto por password_verify en producción!
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['usuario'] = $user['usuario'];
                $_SESSION['rol'] = $user['rol'];
                
                // Redirección segura
                $redirect = match($user['rol']) {
                    'admin' => '../Frontend/admin.html',
                    'doctor' => '../Frontend/doctor.html',
                    'cliente' => '../Frontend/cliente.html',
                    default => '../Frontend/login.html?error=rol'
                };
                
                header("Location: $redirect");
                exit;
            }
        }
        
        // Si llega aquí, las credenciales son incorrectas
        header("Location: ../Frontend/login.html?error=credenciales");
        exit;
    }
} catch (Exception $e) {
    error_log($e->getMessage());
    header("Location: ../Frontend/login.html?error=conexion");
    exit;
} finally {
    if (isset($conn)) {
        $conn->close();
    }
}
?>