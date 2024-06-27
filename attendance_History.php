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
<div class="row">
    <div class="col-12">
        <h1 class="text-center">Historial de asistencia</h1>
    </div>

    <div class="container">
        <form action="attendance_History.php">
            <div class="row">
                <div class="form-row ">
                    <div class="col">
                        <div class="form-group">
                            <label for="start">Desde:&nbsp;</label>
                            <input required="" id="start" type="date" name="start" value="2023-02-11" class="form-control mr-2">
                        </div>

                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="end">Hasta:&nbsp;</label>
                            <input required="" id="end" type="date" name="end" value="2023-02-11" class="form-control">
                        </div>
                    </div>
                    <div class="col">
                        <label for="filter">&nbsp;</label>
                    </div>
                </div>

                <div class="col-sm">

                </div>

                <div class="col-sm">
                    <div class="row">
                        <div class="col">
                            <label for="filter">&nbsp;</label>
                            <input id="documento" name="documento" type="text" class="form-control" placeholder="Documento...">
                        </div>
                        <div class="col">
                            <label for="filter">&nbsp;</label>
                            <button id="filter" name="filter" class="btn btn-dark btn-sm form-control"> Buscar <i class="fa fa-search"></i></button>
                        </div>
                        <!--
                        <div class="col">
                            <label >Reporte excel</label>
                            <a href="./download_employee_report.php?start=2023-02-11&end=2023-02-11" class="btn btn-primary btn-sm ml-auto">Descargar <i class="fa fa-download" aria-hidden="true"></i></a>
                        </div>
                        -->
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="col-12">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Documento</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Cargo</th>
                    <th>Horas de entrada</th>
                    <th>Horas de salida</th>
                    <th>Fecha registrada</th>
                </tr>
            </thead>
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
                <?php }}
                ?>
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
