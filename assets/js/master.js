// class System {

// }
const timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
const datatableParams = {
    "responsive": true, "lengthChange": true, "autoWidth": false, "dom": "Bfrtip",
    "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
    "language": {
        "lengthMenu": "mostrar _MENU_ entradas",
        "zeroRecords": "No conseguimos ningún resultado",
        "info": "Mostrando _PAGE_ de _PAGES_",
        "infoFiltered": "(filtrado _MAX_ registros totales)",
        "search": "Buscar",
        "loadingRecords": "Cargando...",
        "processing": "Procesando...",
        "emptyTable": "Sin resultados para mostrar",
        "infoEmpty": "Sin resultados para mostrar",
        "paginate": {
            "first": "Primero",
            "last": "Ultimo",
            "next": "Siguiente",
            "previous": "Anterior"
        }
    }
};
//------------------------------------------------------------------------------------------------------------------------------------------------------
// config con async await
//------------------------------------------------------------------------------------------------------------------------------------------------------
const loadConfig = async function () {
    let phpLoadConfig = await fetch(`${location.origin}/${location.pathname.split("/")[1]}/config/config.php`);
    let jsonLoadConfig = await fetch(`${location.origin}/${location.pathname.split("/")[1]}/config/config.json`);
    return await jsonLoadConfig.json();
}
//------------------------------------------------------------------------------------------------------------------------------------------------------
// Replace estricto que reemplaza en absoluto lo que quieran reemplazar ( jaja perdon por la redundancia, pero si e asi n: )
// Nota: función peligrosa ojo en como se usa
//------------------------------------------------------------------------------------------------------------------------------------------------------
/**
 * @info  Este es un replace que borra en absoluto lo que le pasen
*/
function strictReplace(string, search, replace) {
    if (!string.includes(search) || search == replace) { // valido si esta lo que quieren reemplazar y que no sea igual lo que busca con lo que quiere reemplazar
        return string; // si no esta pa fuera (sale por que no hay nada que reemplazar)
    } else { // si esta lo que quieren reemplzar pasa a esta validación
        string = string.replaceAll(search, replace); // replace normal de toda la vida
        if (string.includes(search)) { // aqui viene el truco, valido si todavia tiene lo que quieren reemplazar
            string = strictReplace(string, search, replace); // genero un bucle con el cual reenvio el nuevo string
        }
        return string; // si termina pa fuera (sale por que ya reemplazo todo)
    }
}
//------------------------------------------------------------------------------------------------------------------------------------------------------
// alertas personalizables
// Nota: Por lo menos esa es mi idea :c
//------------------------------------------------------------------------------------------------------------------------------------------------------
/**
 * @param Array arrayAlert arreglo con valores para la alerta ninguno es oblitagorio ejemplo alerts([title: "prueba", text: "test", icon: "success", position: "top-end"])
 * @param String arrayAlert[position] valores que acepta (top, top-start, top-end, center, center-start, center-end, bottom, bottom-start, bottom-end) default top-end
 * @param String arrayAlert[icon] valores que acepta (success, error, warning, info, question) default false
 * @param String typeAlert tipo de alerta segun la que quieran usar por defecto Sweetalert2
*/
function alerts(arrayAlert, typeAlert = "Sweetalert2") {

    // la configuracion la hice basandome en los valores de Sweetalert2 para nuevas alertas seria adecuarlas para que funcione con estos parametros
    let config = $.extend({
        title: false, // titulo
        text: false, // texto
        icon: false, // icon
        duration: 3000,
        position: "top-end" // por si depronto quieren configurar una posicion diferente
    }, arrayAlert);

    switch (typeAlert.toLocaleLowerCase()) { // por si quieren hacer configuracion diferentes de alertas yo de momento voy a utilizar esta con sweetalert
        case "sweetalert2":
            let Sweetalert2 = Swal.mixin({
                toast: true,
                position: config.position,
                showConfirmButton: false,
                timer: config.duration,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener("mouseenter", Swal.stopTimer)
                    toast.addEventListener("mouseleave", Swal.resumeTimer)
                }
            });

            Sweetalert2.fire({
                title: `${config.title ? config.title : ``}`,
                text: `${config.text ? config.text : ``}`,
                icon: `${config.icon ? config.icon : ``}`
            })
            break;
        default:
            window.alert(`
                ${config.status ? config.status : ``}
                ${config.title ? config.title : ``}
                ${config.text ? config.text : ``}
            `);
            break;
    }
}
//------------------------------------------------------------------------------------------------------------------------------------------------------
// carga una lista de opciones
//------------------------------------------------------------------------------------------------------------------------------------------------------
function cargarLista(data, ident, idvalue, content) {
    let html = '<option value="">Seleccione</option>';
    // let datos = JSON.parse(data);
    let datos = data;

    datos.forEach(element => {
        html += `<option value="${element[idvalue]}">${element[content]}</option>`;
    });

    $(ident).html(html);
}