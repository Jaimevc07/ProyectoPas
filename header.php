<?php
session_start();
if (empty($_SESSION["id"])) {
    header("Location: login.php");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chass Rojo</title>
    <link rel="shortcut icon" href="img/Ficha.png" type="image/x-icon">
    <link rel="icon" href="img/Ficha.png" type="image/x-icon">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/all.min.css">

    <style>
        body {
            padding-top: 100px;
            background: url(../img/backgroundB.jpg) no-repeat center center fixed;
            background-size: cover;
        }
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
<script src="js/funcion_alerta.js"></script>
</head>
<body>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<main class="container-fluid">