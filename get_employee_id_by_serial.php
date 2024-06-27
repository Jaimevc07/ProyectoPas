<?php
if (!isset($_GET["rfid_serial"])) {
    exit("rfid_serial is not present");
}
include_once "functions.php";
$employee = getEmployeeIdByRfid($_GET["rfid_serial"]);
$id = "";
if ($employee) {
    $id = $employee->employee_id;
}
echo json_encode($id);