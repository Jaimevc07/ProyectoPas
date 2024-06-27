<?php
include_once "header.php";
include_once "nav.php";
include_once "functions.php";
include_once "get_time_worked.php";

$start = date("Y-m-d");
$end = date("Y-m-d");
if (isset($_GET["start"])) {
    $start = $_GET["start"];
}
if (isset($_GET["end"])) {
    $end = $_GET["end"];
}
$employees_dirty = getDatosHistory($start, $end);
$employees = Array();

function sumarTiempo($times) {
    $sumSeconds = 0;

    foreach ($times as $time){
        $a = explode(":", $time);
        $seconds = $a[0] * 60 * 60 + $a[1] * 60 + $a[2];
        $sumSeconds += $seconds;
    }
    return str_pad((intdiv(intdiv($sumSeconds, 60), 60)), 2, "0", STR_PAD_LEFT). ':' . str_pad((($sumSeconds / 60) % 60), 2, "0", STR_PAD_LEFT) . ':' . str_pad(($sumSeconds % 60), 2, "0", STR_PAD_LEFT);
    
}

foreach ($employees_dirty as $employee){
    if (array_key_exists($employee->id, $employees)){
        $employees[$employee->id]->hour_worked = sumarTiempo([$employees[$employee->id]->hour_worked, $employee->hour_worked]);
        continue;
    }
    $employees[$employee->id] = $employee;
}
?>
<div class="row">
    <div class="col-12">
        <h1 class="text-center">Reporte de horas trabajadas</h1>
    </div>
    <div class="col-12">

        <form action="attendance_reportprueba.php" class="form-inline mb-2">
            <label for="start">Desde:&nbsp;</label>
            <input required id="start" type="date" name="start" value="<?php echo $start ?>" class="form-control mr-2">
            <label for="end">Hasta:&nbsp;</label>
            <input required id="end" type="date" name="end" value="<?php echo $end ?>" class="form-control">
            
            <button class="btn btn-success ml-2">Filtrar</button>
        </form>
        <!--
<a href="./download_employee_report.php?start=<?php echo $start ?>&end=<?php echo $end ?>" class="btn btn-info mb-2">Download Excel Report</a>
		-->
    </div>
    <div class="col-12">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Empleado</th>
                        <th>Cargo</th>
                        <th>Horas totales trabajadas</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($employees as $employee) { ?>
                        <tr>
                            <td>
                                <?php echo $employee->name." ".$employee->last_name ?>
                            </td>
                            <td>
                                <?php echo $employee->area ?>
                            </td>
                            <td>
                                <?php echo $employee->hour_worked ?>
                            </td>
                        </tr>
                    <?php } ?>
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