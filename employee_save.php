<?php
if (
    !isset($_POST["identificacion"]) &&
    !isset($_POST["name"]) &&
    !isset($_POST["last_name"]) &&
    !isset($_POST["phone"]) &&
    !isset($_POST["email"]) &&
    !isset($_POST["area"])
) {
    http_response_code(404);
    exit("No data provided");
}
include_once "functions.php";

if (isEmployeeExists($_POST["identificacion"])) {
    http_response_code(404);
} else {
    $identificacion = $_POST["identificacion"];
    $name = $_POST["name"];
    $last_name = $_POST["last_name"];
    $phone = $_POST["phone"];
    $email = $_POST["email"];
    $area = $_POST["area"];
    saveEmployee($identificacion, $name, $last_name, $phone, $email, $area);
    http_response_code(200);
}
