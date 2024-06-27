<?php
include "modelo/conexion.php";
include_once "functions.php";

$start = date("Y-m-d");
$end = date("Y-m-d");
if (isset($_GET["start"])) {
    $start = $_GET["start"];
}
if (isset($_GET["end"])) {
    $end = $_GET["end"];
}
$employeeData = getDatosHistory($start, $end);
$employeeFiltered = null;
if (isset($_GET["documento"])) {
    $documento = $_GET["documento"];
    $employeeFiltered = array();
    foreach ($employeeData as $employee) {
        if ($employee->identificacion == $documento) {
            $employeeFiltered[] = $employee;
        }
    }
}
?>

<table class="table">
    <caption> Historial de asistencia </caption>
    <tr>
        <th>Documento</th>
        <th>Nombre</th>
        <th>Apellido</th>
        <th>Cargo</th>
        <th>Horas de entrada</th>
        <th>Horas de salida</th>
        <th>Fecha registrada</th>
    </tr>
    <tbody>
        <? if ($employeeFiltered != null) {
            foreach ($employeeFiltered as $employee) { ?>
                <tr>
                    <td>
                        <?php echo $employee->identificacion ?>
                    </td>
                    <td>
                        <?php echo $employee->name ?>
                    </td>
                    <td>
                        <?php echo $employee->last_name ?>
                    </td>
                    <td>
                        <?php echo $employee->area ?>
                    </td>
                    <td>
                        <?php echo $employee->hour ?>
                    </td>
                    <td>
                        <?php echo $employee->hour_out ?>
                    </td>
                    <td>
                        <?php echo $employee->date ?>
                    </td>
                </tr>
            <? }
        } else { ?>
            <?php foreach ($employeeData as $employee) { ?>
                <tr>
                    <td>
                        <?php echo $employee->identificacion ?>
                    </td>
                    <td>
                        <?php echo $employee->name ?>
                    </td>
                    <td>
                        <?php echo $employee->last_name ?>
                    </td>
                    <td>
                        <?php echo $employee->area ?>
                    </td>
                    <td>
                        <?php echo $employee->hour ?>
                    </td>
                    <td>
                        <?php
                        if ($employee->hour_out === "-00:00:01") {
                            echo "Trabajando...";
                        } else {
                            echo $employee->hour_out;
                        }
                        ?>

                    </td>
                    <td>
                        <?php echo $employee->date ?>
                    </td>
                </tr>
        <?php }
        } ?>
    </tbody>
</table>