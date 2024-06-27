<?php
session_start();
if (!empty($_POST["btningresar"])) {
    if (!empty($_POST["username"]) and !empty($_POST["password"])) {
        $usuario = $_POST["username"];
        $password = $_POST["password"];
        $sql = $conexion->query("SELECT * FROM usuarios WHERE usuario='$usuario' and clave='$password'");
        if ($datos = $sql->fetch_object()) {
            $_SESSION["id"] = $datos->id;
            $_SESSION["nombre"] = $datos->nombres;
            $_SESSION["apellido"] = $datos->apellidos;
            
            header("Location: employees.php");
        } else {
            echo "<div class='alert alert-danger'> Acceso denegado </div>";
        }
    } else {
        echo  "<div class='alert alert-danger'> Usuario o contraseña invalidos </div>";
    }
}
