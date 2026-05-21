
<?php
session_start();
include("../../config/database.php");

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $username = htmlspecialchars($_POST['username']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE username=?");
    $stmt->bind_param("s",$username);
    $stmt->execute();

    $result = $stmt->get_result();

    if($result->num_rows > 0){

        $row = $result->fetch_assoc();

        if(password_verify($password,$row['password'])){

            $_SESSION['id'] = $row['id'];
            $_SESSION['rol'] = $row['rol'];

            if($row['rol'] == 'rh'){
                header("Location: ../rh/dashboard.php");
            }else{
                header("Location: ../aspirante/dashboard.php");
            }

        }else{
            sleep(1);
            echo "Contraseña incorrecta";
        }

    }else{
        echo "Usuario no encontrado";
    }

}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>

<div class="container">

    <h1>RH System</h1>

    <p>Inicio de sesión</p>

    <form method="POST">

        <label>Usuario</label>

        <input type="text" name="username" placeholder="Ingrese usuario">

        <label>Contraseña</label>

        <input type="password" name="password" placeholder="Ingrese contraseña">

        <button type="submit">Ingresar</button>

    </form>

    <div class="link">
        <a href="../register/">Crear cuenta</a>
    </div>

</div>

</body>
</html>