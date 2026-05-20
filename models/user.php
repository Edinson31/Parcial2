<?php

class User{

    private $conn;

    public function __construct($conexion){
        $this->conn = $conexion;
    }

    public function verificarUsuario($username){

        $stmt = $this->conn->prepare("
        SELECT id FROM usuarios WHERE username = ?
        ");

        $stmt->bind_param("s",$username);

        $stmt->execute();

        return $stmt->get_result();
    }

    public function registrarUsuario($username,$password){

        $stmt = $this->conn->prepare("
        INSERT INTO usuarios(username,password)
        VALUES(?,?)
        ");

        $stmt->bind_param("ss",$username,$password);

        return $stmt->execute();
    }

}
?>