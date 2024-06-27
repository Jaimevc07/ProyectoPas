<?php

if (!isset($_GET["id"])) exit("No id provided");
include_once "header.php";
include_once "nav.php";
$id = $_GET["id"];
include_once "functions.php";
$employee = getEmployeeById($id);
?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1 class="text-center mb-4">Información del empleado <?php echo $employee->name ?></h1>
        </div>
        <div class="col-md-12">
            <div class="float-right mb-3">
                <a href="employee_edit.php?id=<?php echo $employee->id ?>" class="btn btn-warning">
                    Editar <i class="fa fa-edit"></i>
                </a>
                <a href="employees.php" class="btn btn-dark ml-2">
                    Volver <i class="fa fa-reply"></i>
                </a>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Identificación:</label>
                        <div class="col-sm-9">
                            <p class="form-control-static"><?php echo $employee->identificacion ?></p>
                        </div>
                    </div>
                    <hr>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Nombres:</label>
                        <div class="col-sm-9">
                            <p class="form-control-static"><?php echo $employee->name ?></p>
                        </div>
                    </div>
                    <hr>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Apellidos:</label>
                        <div class="col-sm-9">
                            <p class="form-control-static"><?php echo $employee->last_name ?></p>
                        </div>
                    </div>
                    <hr>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Teléfono:</label>
                        <div class="col-sm-9">
                            <p class="form-control-static"><?php echo $employee->phone ?></p>
                        </div>
                    </div>
                    <hr>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Email:</label>
                        <div class="col-sm-9">
                            <p class="form-control-static"><?php echo $employee->email ?></p>
                        </div>
                    </div>
                    <hr>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Área:</label>
                        <div class="col-sm-9">
                            <p class="form-control-static"><?php echo $employee->area ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include_once "footer.php";
