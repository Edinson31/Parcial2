<?php

include("../../config/database.php");

$mensaje = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = trim($_POST['username']);
    $plain_password = $_POST['password'];
    $rol = $_POST['rol'];

    if (!empty($user) && !empty($plain_password) && !empty($rol)) {
        
        
        $hashed_password = password_hash($plain_password, PASSWORD_BCRYPT);

        $sql = "INSERT INTO usuarios (username, password, rol) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $user, $hashed_password, $rol);

        if ($stmt->execute()) {
            header("Location: ../login/index.php?registro=exitoso");
            exit();
        } else {
            $mensaje = "<p class='error-msg'>Error: El usuario ya existe o hubo un problema.</p>";
        }
    } else {
        $mensaje = "<p class='error-msg'>Por favor, completa todos los campos requeridos.</p>";
    }
}
?>

<link rel="stylesheet" href="../../assets/css/register.css"> 

<div class="container">
    <h1>Crear Cuenta</h1>
    <p>Regístrate para ingresar al sistema de RH</p>

    <?php echo $mensaje; ?>

    <form action="" method="POST">
        
        <div class="form-group">
            <label for="username">Nombre de Usuario</label>
            <input type="text" id="username" name="username" required placeholder="Crea tu usuario">
        </div>

        <div class="form-group">
            <label for="password">Contraseña (6 dígitos)</label>
            <input type="password" id="password" name="password" required minlength="6" placeholder="Ingresa tus 15 dígitos">
        </div>

        <div class="form-group">
            <label for="rol">Tipo de Usuario</label>
            <select id="rol" name="rol" required>
                <option value="aspirante">Aspirante</option>
                <option value="rh">Recursos Humanos (RH)</option>
            </select>
        </div>

        <button type="submit">Registrarse</button>
    </form>

    <div class="link">
        ¿Ya tienes cuenta? <a href="../login/index.php">Inicia sesión aquí</a>
    </div>
</div>