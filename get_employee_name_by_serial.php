<?php
if (!isset($_GET["rfid_serial"])) {
    exit("rfid_serial is not present");
}
include_once "functions.php";
$employee = getEmployeeNameByRfidSerial($_GET["rfid_serial"]);
$nameEmployee = "";
if ($employee) {
    $nameEmployee = $employee->name;
}
echo Unaccent($nameEmployee);
