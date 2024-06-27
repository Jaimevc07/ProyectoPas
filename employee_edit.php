<?php

if (!isset($_GET["id"])) exit("No id provided");
include_once "header.php";
include_once "nav.php";
$id = $_GET["id"];
include_once "functions.php";
$employee = getEmployeeById($id);
?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h1 class="text-center"><?php echo $employee->name ?></h1>
                </div>
                <div class="card-body">
                    <form action="employee_update.php" method="POST">

                        <input type="hidden" name="id" value="<?php echo $employee->id ?>">

                        <div class="form-group row">
                            <label for="identificacion" class="col-sm-3 col-form-label">Identificación:</label>
                            <div class="col-sm-9">
                                <p class="form-control-plaintext"><?php echo $employee->identificacion ?></p>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="name" class="col-sm-3 col-form-label">Nombres:</label>
                            <div class="col-sm-9">
                                <input value="<?php echo $employee->name ?>" name="name" placeholder="Nombres" type="text" id="name" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="last_name" class="col-sm-3 col-form-label">Apellidos:</label>
                            <div class="col-sm-9">
                                <input value="<?php echo $employee->last_name ?>" name="last_name" placeholder="Apellidos" type="text" id="last_name" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="phone" class="col-sm-3 col-form-label">Teléfono:</label>
                            <div class="col-sm-9">
                                <input value="<?php echo $employee->phone ?>" name="phone" placeholder="Teléfono" type="text" id="phone" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-sm-3 col-form-label">Email:</label>
                            <div class="col-sm-9">
                                <input value="<?php echo $employee->email ?>" type="email" class="form-control" name="email" placeholder="Email">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="area" class="col-sm-3 col-form-label">Área:</label>
                            <div class="col-sm-9">
                                <select id="area" name="area" class="form-control">
                                    <option selected value="<?php echo $employee->area ?>"><?php echo $employee->area ?></option>
                                    <option>Administración</option>
                                    <option>Juegos</option>
                                    <option>Bartender</option>
                                    <option>Mesero</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-12">
                                <button type="submit" class="btn btn-success"> Guardar </button>
                                <a href="employees.php" class="btn btn-secondary float-right"> Cancelar</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include_once "footer.php";
