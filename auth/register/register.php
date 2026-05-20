<?php

include('../../controllers/authcontroller.php');

?>

<!DOCTYPE html>
<html>

<head>

<link rel="stylesheet"
href="../../assets/css/style.css">

</head>

<body>

<div class="container">

<h1>RH System</h1>

<p>Crear Cuenta</p>

<?php if(!empty($mensaje)): ?>

<p><?php echo $mensaje; ?></p>

<?php endif; ?>

<form method="POST">

<label>Usuario</label>

<input type="text"
name="username"
required>

<label>Contraseña</label>

<input type="password"
name="password"
required>

<button type="submit">
Crear Cuenta
</button>

</form>

</div>

</body>
</html>