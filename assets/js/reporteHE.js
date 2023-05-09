$(document).ready(async function () {
    config = await loadConfig();

    fetch("../controller/CRUD.controller.php?action=listAll&model=CentroCosto&crud=get")
        .then(response => response.json())
        .then(response => { cargarLista(response, "#ceco", "id", "titulo") });

    fetch("../controller/CRUD.controller.php?action=listAll&model=Aprobador&crud=get")
        .then(response => response.json())
        .then(response => {

            cargarLista(response.filter(
                x => x.tipo.toLocaleUpperCase() == "JEFE" ? true : false
            ), "#listJefe", "id", "nombre");

            cargarLista(response.filter(
                x => x.tipo.toLocaleUpperCase() == "GERENTE" ? true : false
            ), "#listGerente", "id", "nombre");


        });

    $("#formReporte").on("submit", function (e) {
        e.preventDefault();
        $.ajax("../controller/submit.controller.php?action=registroHE", {
            type: "POST",
            dataType: "JSON",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function (response) {
                if (response.error !== undefined) {
                    alerts({ title: `Error SQL: ${response.error}`, icon: "error", duration: 10000 });
                } else if (response.status == true) {
                    $(`[href*="estado/listEstado.view"]`).click();
                    alerts({ title: "Horas extras registradas", icon: "success" });
                } else {
                    alerts({ title: "Error al registrar las horas extras registradas, inténtalo más tarde", icon: "error" });
                }
            }
        })
    });

    $(`#listJefe, #listGerente`).on("change", function () {
        $(`[name="data[id_aprobador]"]`).val($(this).val());
    });

    $(`[name="aprobador"]`).on("change", function () {
        $val = $(this).val().toLocaleUpperCase();
        $(`[name="data[id_estado]"]`).val(
            $val == "JEFE" ? config.APROBACION_JEFE : (
                $val == "GERENTE" ? config.APROBACION_GERENTE : (
                    $val == "CONTABLE" ? config.APROBACION_CONTABLE : (
                        $val == "RH" ? config.APROBACION_RH :
                            config.EDICION
                    )
                )
            )
        );

        if ($val == "JEFE") {
            $("#listJefe").attr("required", true).removeAttr("disabled");
            $("#listGerente").attr("disabled", true).removeAttr("required").val("");
        } else if ($val == "GERENTE") {
            $("#listGerente").attr("required", true).removeAttr("disabled");
            $("#listJefe").attr("disabled", true).removeAttr("required").val("");
        }

    }).ready(function () {
        $(`[name="data[id_estado]"]`).val(config.EDICION);
    });

    $(`#addHE`).on("click", function () {
        $($("#bodyTableEdit tr")[0])
            .clone()
            .appendTo("#bodyTableEdit")
            .find("input, button").val("").removeAttr("disabled");
    });

});

function total() {
    let Ctotal = 0;
    $(`[data-he="descuento"]`).each(function () {
        Ctotal += Number($(this).val());
    });
    $(`#total`).html(Ctotal);
    $(`#totales`).html(Ctotal);
    $(`[name="data[total]"]`).val(Ctotal);
}

function deleteT(x) {
    $(x.parentNode.parentNode).remove();
    total();
}

function fechas() {
    let f = getFechas();
    $(`[name="data[fechaInicio]"]`).val(f ? f[2] : "");
    $(`[name="data[fechaFin]"]`).val(f ? f[1] : "");
}

function getFechas() {
    var fechas = [];
    var fecha;
    var mes = $('#mes').val();

    if (mes.length <= 0) {
        return;
    }

    mes = mes.split('-');

    if (parseInt(mes[1]) <= 8) {
        mes = mes[0] + '-' + '0' + (parseInt(mes[1]) + 1);
    } else if (parseInt(mes[1]) >= 9 && parseInt(mes[1]) <= 11) {
        mes = mes[0] + '-' + (parseInt(mes[1]) + 1);
    } else if (parseInt(mes[1]) == 12) {
        mes = (parseInt(mes[0]) + 1) + '-' + '01';
    }

    fecha = new Date();
    fechas[0] = fecha.getFullYear() + '-' + (fecha.getMonth() + 1) + '-' + fecha.getDate();
    fecha = new Date(mes);

    var month;
    var year;
    if (fecha.getMonth() <= 9) {
        if (fecha.getMonth() == 0) {
            month = '12';
            year = (fecha.getFullYear() - 1);
        } else {
            month = '0' + fecha.getMonth();
            year = fecha.getFullYear();
        }
    } else {
        month = fecha.getMonth();
        year = fecha.getFullYear();
    }

    fechas[2] = year + '-' + month + '-01';

    if (fecha.getMonth() <= 8) {
        fechas[1] = fecha.getFullYear() + '-0' + (fecha.getMonth() + 1) + '-' + fecha.getDate();
    } else {
        fechas[1] = fecha.getFullYear() + '-' + (fecha.getMonth() + 1) + '-' + fecha.getDate();
    }

    return fechas;
}