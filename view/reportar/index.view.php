<style>
    .center {
        display: flex;
        justify-content: center;
        align-items: center;
    }
</style>
<section class="content">
    <div class="container-fluid">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Registro de horas extras</h3>
            </div>
            <div class="card-body">
                <form id="formReporte">
                    <div class="row">
                        <div class="col-12 col-xl-3">
                            <label for="cc">Cedula <b class="text-danger">*</b></label>
                            <input type="number" name="data[cc]" id="cc" class="form-control" maxlength="10" required>
                        </div>
                        <div class="col-12 col-xl-3">
                            <label for="cargo">Cargo <b class="text-danger">*</b></label>
                            <input type="text" name="data[cargo]" id="cargo" class="form-control" required>
                        </div>
                        <div class="col-12 col-xl-3">
                            <label for="mes">Mes Reportado <b class="text-danger">*</b></label>
                            <input type="month" name="mes" id="mes" class="form-control" required oninput="fechas()">
                        </div>
                        <div class="col-12 col-xl-3">
                            <label for="correoEmpleado">Correo <b class="text-danger">*</b></label>
                            <input type="email" name="data[correoEmpleado]" id="correoEmpleado" class="form-control" required value="<?= $_SESSION['email'] ?>">
                        </div>
                        <div class="col-12 col-xl-6">
                            <label for="ceco">Centro de Costo </label>
                            <select name="data[id_ceco]" id="ceco" class="form-control"></select>
                        </div>
                        <div class="col-12 col-xl-6">
                            <label for="proyecto">Proyecto Asociado </label>
                            <input type="text" name="proyecto" id="proyecto" class="form-control">
                        </div>
                        <div class="col-12"><hr></div>
                        <div class="col-12 my-3">
                            <div class="d-flex justify-content-center align-items-center">
                                <a href="#one" style="font-size: 25px"><i class="fas fa-chevron-down"></i></a>
                            </div>
                        </div>
                        <div class="col-12" id="heReportadas">
                            <div class="table-responsive">
                                <table class="table" id="tableEdit">
                                    <thead>
                                        <tr class="shadow" id="headTableEdit">
                                            <th>Fecha</th>
                                            <th>Actividad</th>
                                            <th>Permisos Descuentos</th>
                                            <th>Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody id="bodyTableEdit">
                                        <tr>
                                            <td><input type="date" name="HorasExtra[fecha][]" class="form-control"></td>
                                            <td><input type="text" name="HorasExtra[novedad][]" class="form-control" required></td>
                                            <td><input type="number" name="HorasExtra[descuento][]" data-he="descuento" class="form-control" step="0.5" min="0.5" oninput="total()"></td>
                                            <td><button class="btn btn-danger" type="button" disabled onclick="deleteT(this)"><i class="fa fa-times"></i></button></td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr class="shadow">
                                            <td align="left"><button id="addHE" type="button" class="btn btn-primary"><i class="fas fa-plus fi"></i></button></td>
                                            <td colspan="2" align="right">
                                                <input type="hidden" name="totales">
                                                <b>Totales</b>
                                            </td>
                                            <td id="totales">0</td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" align="right">
                                                <input type="hidden" name="calcHE">
                                                Total horas extras
                                            </td>
                                            <td class="text-success">0</td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" align="right">
                                                <input type="hidden" name="calcRec">
                                                Total recargos
                                            </td>
                                            <td>0</td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" align="right">
                                                <input type="hidden" name="data[total]">
                                                Total
                                            </td>
                                            <td id="total">0.0</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div class="col-12 my-3">
                            <div class="d-flex justify-content-center align-items-center">
                                <a href="#one" style="font-size: 25px"><i class="fas fa-chevron-down"></i></a>
                            </div>
                        </div>
                        <div class="col-12"><hr></div>
                        <div class="col-6">
                            <div class="form-group clearfix">
                                <div class="icheck-primary d-inline">
                                    <input type="radio" id="jefe" value="Jefe" name="aprobador">
                                    <label for="jefe">Jefe</label>
                                </div>
                                <select name="listJefe" id="listJefe" class="form-control mt-1" disabled>
                                    <option value="">###</option>
                                    <option value="">###</option>
                                    <option value="">###</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group clearfix">
                                <div class="icheck-primary d-inline">
                                    <input type="radio" id="gerente" value="Gerente" name="aprobador">
                                    <label for="gerente">Gerente</label>
                                </div>
                                <select name="listGerente" id="listGerente" class="form-control mt-1" disabled>
                                    <option value="">###</option>
                                    <option value="">###</option>
                                    <option value="">###</option>
                                </select>
                            </div>
                        </div>
                        <input type="hidden" name="data[id_estado]">
                        <input type="hidden" name="data[id_aprobador]">
                        <input type="hidden" name="data[empleado]" value="<?= $_SESSION['usuario'] ?>">
                        <input type="hidden" name="data[fechaInicio]">
                        <input type="hidden" name="data[fechaFin]">
                        <div class="col-12">
                            <div class="center">
                                <button type="submit" class="btn btn-outline-primary"><i class="fa fa-check-circle"></i> Enviar</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>