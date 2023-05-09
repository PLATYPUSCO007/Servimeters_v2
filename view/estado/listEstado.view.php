<section class="content">
    <div class="container-fluid">
        <div class="card card-primary">
            <!-- <div class="card-header">
                <h3 class="card-title">Titulo</h3>
            </div> -->
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" id="listHE">
                        <thead class="shadow">
                            <tr>
                                <th>#</th>
                                <th>Documento</th>
                                <th>Centro Costo</th>
                                <th>Clase</th>
                                <th>Año</th>
                                <th>Mes</th>
                                <th>Aprobador</th>
                                <th>Estado</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Documento</th>
                                <th>Centro Costo</th>
                                <th>Clase</th>
                                <th>Año</th>
                                <th>Mes</th>
                                <th>Aprobador</th>
                                <th>Estado</th>
                                <th>Acción</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="card-foot"><!--Encabezado--></div>
        </div>
    </div>
</section>
<div class="modal fade" id="viewDetail">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Default Modal</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>One fine body&hellip;</p>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>
<script>
    $("#listHE").DataTable($.extend(datatableParams, {
        "processing": true,
        "severSide": true,
        "ajax": "../controller/ssp.controller.php?ssp=listEstadoHe"
    })).buttons().container().appendTo($('.col-sm-6:eq(0)'));
</script>