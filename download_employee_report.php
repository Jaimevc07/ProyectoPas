<?php
include_once "vendor/autoload.php";
include_once "functions.php";
$start = date("Y-m-d");
$end = date("Y-m-d");
if (isset($_GET["start"])) {
    $start = $_GET["start"];
}
if (isset($_GET["end"])) {
    $end = $_GET["end"];
}
$employees = getDatosHistory($start, $end);

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

$documento = new Spreadsheet();
$nombreDelDocumento = "Reporte.xlsx";
$hoja = $documento->getActiveSheet();
$hoja->setTitle("Hoja 1");
$hoja->setCellValueByColumnAndRow(1, 1, sprintf("Desde %s a %s", $start, $end));
$hoja->setCellValueByColumnAndRow(1, 2, "ID");
$hoja->setCellValueByColumnAndRow(2, 2, "Nombres");
$hoja->setCellValueByColumnAndRow(3, 2, "Apellidos");
$hoja->setCellValueByColumnAndRow(4, 2, "Cargo");
$hoja->setCellValueByColumnAndRow(5, 2, "Horas de entrada");
$hoja->setCellValueByColumnAndRow(6, 2, "Horas de salida");
$hoja->setCellValueByColumnAndRow(7, 2, "Fecha registrada");
$y = 7;
foreach ($employees as $employee) {
    $hoja->setCellValueByColumnAndRow(1, $y, $employee->id);
    $hoja->setCellValueByColumnAndRow(2, $y, $employee->name);
    $hoja->setCellValueByColumnAndRow(3, $y, $employee->last_name);
    $hoja->setCellValueByColumnAndRow(4, $y, $employee->area );
    $hoja->setCellValueByColumnAndRow(5, $y, $employee->hour);
    $hoja->setCellValueByColumnAndRow(6, $y, $employee->hour_out );
    $hoja->setCellValueByColumnAndRow(7, $y, $employee->date);
    $y++;
}
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $nombreDelDocumento . '"');
header('Cache-Control: max-age=0');

$writer = IOFactory::createWriter($documento, 'Xlsx');
$writer->save('php://output');
exit;
