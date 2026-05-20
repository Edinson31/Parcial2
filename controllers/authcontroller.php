<?php

session_start();

include('../../config/database.php');
include('../../models/user.php');

$userModel = new User($conn);

$mensaje = "";

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $username = htmlspecialchars($_POST['username']);
    $password = $_POST['password'];

    if(strlen($password) < 15){

        $mensaje = "La contraseña debe tener mínimo 15 caracteres";

    }else{

        $verificar = $userModel->verificarUsuario($username);

        if($verificar->num_rows > 0){

            $mensaje = "El usuario ya existe";

        }else{

            $passwordHash = password_hash(
                $password,
                PASSWORD_BCRYPT
            );

            $guardar = $userModel->registrarUsuario(
                $username,
                $passwordHash
            );

            if($guardar){

                $mensaje = "Usuario registrado correctamente";

            }else{

                $mensaje = "Error al registrar";
            }
        }
    }
}
?>