<?php
if (!isset($_GET["id"])) {
    exit("employee_id is not present");
}
include_once "functions.php";
$employee = getEmployeeNameById($_GET["id"]);
$nameEmployee = "";
if ($employee) {
    $nameEmployee = $employee->name;
}
echo json_encode($nameEmployee);