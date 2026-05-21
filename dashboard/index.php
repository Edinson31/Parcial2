<?php
session_start();

if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'aspirante') {
    header("Location: ../auth/login/index.php");
    exit();
}

include("../config/database.php");

$mensaje = "";
$id_usuario_sesion = $_SESSION['id_usuario'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cedula = trim($_POST['cedula_pasaporte']);
    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $estado_civil = $_POST['estado_civil'];
    $genero = $_POST['genero'];
    $tipo_sangre = trim($_POST['tipo_sangre']);
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $nacionalidad = trim($_POST['nacionalidad']);
    $telefono = trim($_POST['telefono']);
    $residencia = trim($_POST['residencia']);
    $correo = trim($_POST['correo']);

    if (!empty($cedula) && !empty($nombre) && !empty($apellido) && !empty($genero) && !empty($fecha_nacimiento) && !empty($nacionalidad) && !empty($telefono) && !empty($residencia) && !empty($correo)) {
        
        $sql_check = "SELECT id FROM aspirantes WHERE usuario_id = ?";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bind_param("i", $id_usuario_sesion);
        $stmt_check->execute();
        $res_check = $stmt_check->get_result();

        if ($res_check->num_rows > 0) {
            $sql_update = "UPDATE aspirantes SET cedula_pasaporte = ?, nombre = ?, apellido = ?, estado_civil = ?, genero = ?, tipo_sangre = ?, fecha_nacimiento = ?, nacionalidad = ?, telefono = ?, residencia = ?, correo = ? WHERE usuario_id = ?";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bind_param("sssssssssssi", $cedula, $nombre, $apellido, $estado_civil, $genero, $tipo_sangre, $fecha_nacimiento, $nacionalidad, $telefono, $residencia, $correo, $id_usuario_sesion);
            
            if ($stmt_update->execute()) {
                $mensaje = "<p style='background-color: #e6f4ea; color: #137333; padding: 10px; border-radius: 5px; text-align: center; font-weight: bold;'>Perfil actualizado exitosamente.</p>";
            } else {
                $mensaje = "<p class='error-msg'>Error al actualizar los datos.</p>";
            }
        } else {
            $sql_insert = "INSERT INTO aspirantes (usuario_id, cedula_pasaporte, nombre, apellido, estado_civil, genero, tipo_sangre, fecha_nacimiento, nacionalidad, telefono, residencia, correo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt_insert = $conn->prepare($sql_insert);
            $stmt_insert->bind_param("isssssssssss", $id_usuario_sesion, $cedula, $nombre, $apellido, $estado_civil, $genero, $tipo_sangre, $fecha_nacimiento, $nacionalidad, $telefono, $residencia, $correo);
            
            if ($stmt_insert->execute()) {
                $mensaje = "<p style='background-color: #e6f4ea; color: #137333; padding: 10px; border-radius: 5px; text-align: center; font-weight: bold;'>Información guardada exitosamente.</p>";
            } else {
                $mensaje = "<p class='error-msg'>Error al guardar los datos.</p>";
            }
        }
    } else {
        $mensaje = "<p class='error-msg'>Por favor, completa todos los campos obligatorios (*).</p>";
    }
}

$sql_load = "SELECT * FROM aspirantes WHERE usuario_id = ?";
$stmt_load = $conn->prepare($sql_load);
$stmt_load->bind_param("i", $id_usuario_sesion);
$stmt_load->execute();
$res_load = $stmt_load->get_result();
$datos = $res_load->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Datos del Aspirante</title>
    <link rel="stylesheet" href="../assets/css/aspirante.css">
</head>
<body>

<div class="container"> 
    <h1>Datos del Aspirante</h1>
    <p>Por favor, complete su perfil de postulación obligatorio</p>

    <?php echo $mensaje; ?>

    <form action="" method="POST">
        
        <div class="form-group">
            <label for="cedula_pasaporte">Cédula o Pasaporte *</label>
            <input type="text" id="cedula_pasaporte" name="cedula_pasaporte" required value="<?php echo isset($datos['cedula_pasaporte']) ? htmlspecialchars($datos['cedula_pasaporte']) : ''; ?>" placeholder="Ej: 8-XXX-XXXX">
        </div>

        <div class="form-group">
            <label for="nombre">Nombre *</label>
            <input type="text" id="nombre" name="nombre" required value="<?php echo isset($datos['nombre']) ? htmlspecialchars($datos['nombre']) : ''; ?>" placeholder="Tu nombre">
        </div>

        <div class="form-group">
            <label for="apellido">Apellido *</label>
            <input type="text" id="apellido" name="apellido" required value="<?php echo isset($datos['apellido']) ? htmlspecialchars($datos['apellido']) : ''; ?>" placeholder="Tu apellido">
        </div>

        <div class="form-group">
            <label for="estado_civil">Estado Civil</label>
            <select id="estado_civil" name="estado_civil">
                <option value="soltero" <?php echo (isset($datos['estado_civil']) && $datos['estado_civil'] == 'soltero') ? 'selected' : ''; ?>>Soltero(a)</option>
                <option value="casado" <?php echo (isset($datos['estado_civil']) && $datos['estado_civil'] == 'casado') ? 'selected' : ''; ?>>Casado(a)</option>
                <option value="divorciado" <?php echo (isset($datos['estado_civil']) && $datos['estado_civil'] == 'divorciado') ? 'selected' : ''; ?>>Divorciado(a)</option>
                <option value="viudo" <?php echo (isset($datos['estado_civil']) && $datos['estado_civil'] == 'viudo') ? 'selected' : ''; ?>>Viudo(a)</option>
            </select>
        </div>

        <div class="form-group">
            <label for="genero">Género *</label>
            <select id="genero" name="genero" required>
                <option value="">Seleccione...</option>
                <option value="masculino" <?php echo (isset($datos['genero']) && $datos['genero'] == 'masculino') ? 'selected' : ''; ?>>Masculino</option>
                <option value="femenino" <?php echo (isset($datos['genero']) && $datos['genero'] == 'femenino') ? 'selected' : ''; ?>>Femenino</option>
            </select>
        </div>

        <div class="form-group">
            <label for="tipo_sangre">Tipo de Sangre</label>
            <input type="text" id="tipo_sangre" name="tipo_sangre" value="<?php echo isset($datos['tipo_sangre']) ? htmlspecialchars($datos['tipo_sangre']) : ''; ?>" placeholder="Ej: O+">
        </div>

        <div class="form-group">
            <label for="fecha_nacimiento">Fecha de Nacimiento *</label>
            <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" required value="<?php echo isset($datos['fecha_nacimiento']) ? $datos['fecha_nacimiento'] : ''; ?>">
        </div>

        <div class="form-group">
            <label for="nacionalidad">Nacionalidad *</label>
            <input type="text" id="nacionalidad" name="nacionalidad" required value="<?php echo isset($datos['nacionalidad']) ? htmlspecialchars($datos['nacionalidad']) : ''; ?>" placeholder="Ej: Panameña">
        </div>

        <div class="form-group">
            <label for="telefono">Teléfono *</label>
            <input type="tel" id="telefono" name="telefono" required value="<?php echo isset($datos['telefono']) ? htmlspecialchars($datos['telefono']) : ''; ?>" placeholder="Ej: 6666-6666">
        </div>

        <div class="form-group">
            <label for="residencia">Residencia *</label>
            <input type="text" id="residencia" name="residencia" required value="<?php echo isset($datos['residencia']) ? htmlspecialchars($datos['residencia']) : ''; ?>" placeholder="Dirección residencial">
        </div>

        <div class="form-group">
            <label for="correo">Correo Electrónico *</label>
            <input type="email" id="correo" name="correo" required value="<?php echo isset($datos['correo']) ? htmlspecialchars($datos['correo']) : ''; ?>" placeholder="ejemplo@correo.com">
        </div>

        <button type="submit">Guardar Información</button>
    </form>
</div>

</body>
</html>