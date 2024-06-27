<?php
include "modelo/conexion.php";
include "controlador/controlador_login.php";
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/all.min.css">
    <link rel="stylesheet" type="text/css" href="css/index.css" th:href="@{/css/index.css}">
    <style>
        body {
            background-color: #f7f9fc;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .main-section {
            margin-top: 50px;
        }

        .modal-content {
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .user-img img {
            width: 100px;
            height: 100px;
            margin-bottom: 20px;
        }

        .title {
            margin-bottom: 20px;
        }

        .form-control {
            margin-bottom: 15px;
        }

        .btn-primary {
            width: 100%;
        }

        .forgot a {
            color: #007bff;
            text-decoration: none;
        }

        .forgot a:hover {
            text-decoration: underline;
        }

        .alert {
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="modal-dialog text-center">
        <div class="col-sm-20 main-section">
            <div class="modal-content">
                <div class="col-12 user-img">
                    <img src="img/avatar.png" alt="Avatar" th:src="@{/img/avatar.png}">
                    <h1 class="title text-dark">Zona Admin</h1>
                </div>
                <form method="post" action="">
                    <div class="form-group" id="user-group">
                        <input type="text" class="form-control" placeholder="Nombre de usuario" name="username" required>
                    </div>
                    <div class="form-group" id="contrasena-group">
                        <input type="password" class="form-control" placeholder="Contraseña" name="password" required>
                    </div>
                    <input name="btningresar" class="btn btn-primary" type="submit" value="Ingresar">
                    <div class="col-12 forgot">
                        <a href="#">He olvidado mi contraseña</a>
                    </div>
                    <div class="col-12 forgot">
                        <!-- Display errors if any -->
                        <!-- <div th:if="${param.error}" class="alert alert-danger" role="alert">
                            Nombre de usuario o contraseña inválidos.
                        </div>
                        <div th:if="${param.logout}" class="alert alert-success" role="alert">
                            Has cerrado sesión correctamente.
                        </div> -->
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="js/bootstrap.min.js"></script>
</body>

</html>
