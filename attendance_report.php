<?php

include_once "header.php";
include_once "nav.php";
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
$employeeFiltered = null;
if (isset($_GET["documento"])) {
    $documento = $_GET["documento"];
    foreach ($employees as $employee) {
        if ($employee->documento === $documento) {
            $employeeFiltered = $employee;
            break;
        }
    }
}
?>
<div class="row">
    <div class="col-12">
        <h1 class="text-center">Reporte de asistencia</h1>
    </div>
    <div class="col-12">

        <form action="attendance_report.php" class="form-inline mb-2">
            <label for="start">Desde:&nbsp;</label>
            <input required id="start" type="date" name="start" value="<?php echo $start ?>" class="form-control mr-2">
            <label for="end">Hasta:&nbsp;</label>
            <input required id="end" type="date" name="end" value="<?php echo $end ?>" class="form-control">

            <button class="btn btn-success ml-2">Filtrar</button>
        </form>
        <!--
<a href="./download_employee_report.php?start=<?php echo $start ?>&end=<?php echo $end ?>" class="btn btn-info mb-2">Download Excel Report</a>
		-->
        <form action="attendance_report.php" class="form-inline mb-2">
        <div class="form-group">
            <div class="col">
                <input type="text" class="form-control" placeholder="Documento...">
            </div>
            <a class="btn btn-dark float-right" class="form-control" href=""> Buscar <i class="fa fa-search"></i></a>
        </div>
        </form>

    </div>
    <div class="col-12">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Empleado</th>
                        <th>Cargo</th>
                        <th>Horas Totales trabajadas</th>
                    </tr>
                </thead>
                <tbody>
                    <? if (isset($employeeFiltered)) { ?>
                        <tr>
                            <td>
                                <?php echo $employeeFiltered->name . " " . $employeeFiltered->last_name ?>
                            </td>
                            <td>
                                <?php echo $employeeFiltered->area ?>
                            </td>
                            <td>
                                <?php echo $employeeFiltered->hour_worked ?>
                            </td>
                        </tr>
                    <? } else { ?>
                        <?php foreach ($employees as $employee) { ?>
                            <tr>
                                <td>
                                    <?php echo $employee->name . " " . $employee->last_name ?>
                                </td>
                                <td>
                                    <?php echo $employee->area ?>
                                </td>
                                <td>
                                    <?php echo $employee->hour_worked ?>
                                </td>
                            </tr>
                    <?php }
                    } ?>
                </tbody>
            </table>
            <br>
            <br>
            <br>
            <br>

        </div>
    </div>
</div>
<?php
include_once "footer.php";
