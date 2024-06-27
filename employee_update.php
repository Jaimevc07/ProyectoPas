<?php
header("Location: employees.php");
print_r($_POST);
if (
!isset($_POST["name"])&&
!isset($_POST["last_name"])&&
!isset($_POST["phone"])&&
!isset($_POST["email"])&&
!isset($_POST["area"]) || !isset($_POST["id"])) {
    exit("No data provided");
}
include_once "functions.php";
$name = $_POST["name"];
$last_name = $_POST["last_name"];
$phone =$_POST["phone"];
$email = $_POST["email"];
$area =$_POST["area"];
$id = $_POST["id"];

updateEmployee($name, $last_name, $phone, $email, $area, $id);