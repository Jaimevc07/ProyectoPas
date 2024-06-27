<?php
include_once "header.php";
include_once "nav.php";
include_once "functions.php";
$employees = getEmployees();
?>
<div class="row">
    <div class="col-12">
        <h1 class="text-end p-0">PANEL DE CONTROL</h1>
    </div>


    <!-- Tabla  -->
    <div class="col-12">
    <a href="employee_add.php" class="btn btn-outline-primary mb-2">Añadir <i class="fa fa-plus"></i></a>
</div>
<div class="col-12">
    <div class="table-responsive">
        <table class="table table-striped table-hover text-center">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Nombre</th>
                        <th>Editar</th>
                        <th>Informacion</th>
                        <th>Eliminar</th>
                        <th>Huella</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($employees as $employee) { ?>
                        <tr>
                            <td>
                                <?php echo $employee->id ?>
                            </td>
                            <td>
                                <?php echo $employee->name ?>
                            </td>
                            <td>
                                <a class="btn btn-primary" href="employee_edit.php?id=<?php echo $employee->id ?>">
                                    Editar 
                                </a>
                            </td>
                            <td>
                                <a class="btn btn-success" href="employee_info.php?id=<?php echo $employee->id ?>">
                                    Información 
                                </a>
                            </td>
                            <td>
                                <a class="btn btn-danger" onclick="call_alert(<? echo $employee->id ?>);">
                                    Eliminar 
                                </a>
                            </td>
                            <td>
                                <a  >
                                     
                                </a>
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