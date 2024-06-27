<?php
include_once "functions.php";
$payload = json_decode(file_get_contents("php://input"));
if (!$payload) exit("No data present");
$hour = date('H:i:s');
$response = saveAttendanceData($payload->date, $hour, $payload->employees);
echo json_encode($response);