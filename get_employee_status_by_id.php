<?php
if (!isset($_GET["rfid_serial"])) {
    exit("rfid_serial is not present");
}
include_once "functions.php";
$employee = getEmployeeStatusByRfid($_GET["rfid_serial"]);
$statusEmployee = 1;
if ($employee->status == "presence") {
    $statusEmployee = 0;
}
echo $statusEmployee;
//echo json_encode($employee);