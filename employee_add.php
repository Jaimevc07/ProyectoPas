<?php

include_once "header.php";
include_once "nav.php";
?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <h5 class="card-header text-center">Añadir Empleado</h5>
                <div class="card-body">
                    <form id="formulario" onsubmit="return false">

                        <div class="form-group row">
                            <label for="identificacion" class="col-sm-3 col-form-label text-sm-right">Identificación:</label>
                            <div class="col-sm-9">
                                <input name="identificacion" type="text" id="identificacion" class="form-control" placeholder="Identity" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="name" class="col-sm-3 col-form-label text-sm-right">Nombres:</label>
                            <div class="col-sm-9">
                                <input name="name" type="text" id="name" class="form-control" placeholder="First name" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="last_name" class="col-sm-3 col-form-label text-sm-right">Apellidos:</label>
                            <div class="col-sm-9">
                                <input name="last_name" type="text" id="last_name" class="form-control" placeholder="Last name" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="phone" class="col-sm-3 col-form-label text-sm-right">Teléfono:</label>
                            <div class="col-sm-9">
                                <input name="phone" type="text" id="phone" class="form-control" placeholder="Phone" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-sm-3 col-form-label text-sm-right">Email:</label>
                            <div class="col-sm-9">
                                <input type="email" class="form-control" name="email" placeholder="Email">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="area" class="col-sm-3 col-form-label text-sm-right">Área:</label>
                            <div class="col-sm-9">
                                <select id="area" name="area" class="form-control" required>
                                    <option selected disabled hidden value="">Seleccione</option>
                                    <option>Administración</option>
                                    <option>Bartender</option>
                                    <option>Mesero</option>
                                    <option>Juegos</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-9 offset-sm-3">
                                <button type="submit" class="btn btn-success" onclick="alerta_existente();">
                                    Guardar 
                                </button>
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
