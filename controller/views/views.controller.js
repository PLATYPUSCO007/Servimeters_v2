//------------------------------------------------------------------------------------------------------------------------------------------------------
// Cargamos el contenido de la pantalla principal
//------------------------------------------------------------------------------------------------------------------------------------------------------
$(document).ready(function () {
    let error = sessionStorage.getItem("errorContent");
    if (error) {
        sessionStorage.removeItem("errorContent");
        contentPage(`error/error.view?filenotfound=errorUrlByUser&error=${error}`, "Erro Url");
    } else {
        contentPage("Principal/default.view", "Dashboard");
    }
});
//------------------------------------------------------------------------------------------------------------------------------------------------------
// Boton para salir
// Nota: no se me ocurrio nada especial para esta parte si quieren poner alertas o algo para validar cuando valla a cerrar la session bien puedan
//------------------------------------------------------------------------------------------------------------------------------------------------------
$(`nav .nav-item .nav-link[href="exit"]`).on(`click`, function (e) {
    console.log("Exit")
    e.preventDefault();
    window.location.replace("../controller/session.controller.php?action=finish");
});
//------------------------------------------------------------------------------------------------------------------------------------------------------
// Barra de navegación
//------------------------------------------------------------------------------------------------------------------------------------------------------
$(`nav .nav-item .nav-link[href!="#"][href!="exit"]`).on(`click`, function(e) {
    e.preventDefault(); // prevengo el evento para que no redirecione cuando le de click
    let page, title1, title2, scripts; // declaro mis variables
    // asgino los valores
    page    = $(this).attr(`href`);
    title1  = $(this).attr(`data-title`); // atributo con el titulo
    title2  = $(this).text(); // texto de la etiqueta
    scripts = $(this).attr(`data-script`);
    if (page && page !== undefined) {
        contentPage(page, title1 ? title1 : strictReplace(title2, "  ", " "), scripts);
    }
});
//------------------------------------------------------------------------------------------------------------------------------------------------------
// Contenido de la pagina con peticiones de jquery
//------------------------------------------------------------------------------------------------------------------------------------------------------
function contentPage(page, title, scripts = undefined) {
    checkSession();
    let session = sessionStorage.getItem("session");
    if (session == true||'true') {
        $.ajax(`../controller/views/views.controller.php?t=${timezone}`, {
            dataType: `HTML`,
            type: `POST`,
            data: {
                view: page ? page : false,
                titl: title ? title : `Dashboard`
            },
            beforeSend: function() { // antes de enviarlo cargamos animación
                $(`.wrapper .preloader`).attr(`style`, `height: 100%;`);
                $(`.wrapper .preloader .animation__shake`).attr(`style`, `display: block;`);
            },
            success: function(response) {
                if (scripts !== undefined) {
                    console.log(`page: ${page}`, `title: ${title}`, `scripts: ${scripts}`);
                    let allScripts = [ // todos los scripts
                        "../assets/js/reporteHE.js",
                        "../assets/js/listadoHE.js",
                        "../assets/js/detailsReporte.js",
                        "../assets/js/aproveRejectHE.js",
                        "../assets/js/generarReporte.js",
                        "../assets/js/admin/claseAdmin.js",
                        "../assets/js/admin/cecoAdmin.js",
                        "../assets/js/admin/aprobadoresAdmin.js"
                    ];
                    
                    let loadScripts = []; // arreglo que va a contener los scripts que se van a cargar en la pagina
                    
                    scripts.split(", ").forEach(q => { // hago un recorrido del atributo que agregue a la lista
                        loadScripts.push(allScripts.filter( // lo carga al arreglo
                            w => w.toLowerCase().includes(q.toLowerCase().replace(" ", "")) // filtro por los valores de los atributos
                        ));
                    });
                    
                    for (ls in loadScripts) { // por ultimo hago que jquery me cargue esos scripts
                        $.getScript(loadScripts[ls]);
                    }
                }
                
                $.getScript("../controller/views/all.page.js");
                
                $(`router`).html(`
                    <div class="content-wrapper">
                        ${response}
                    </div>
                `);
            },
            complete: function() { // despues de que se carga esperamos un rato y quitamos la animación
                setTimeout(() => {
                    $(`.wrapper .preloader`).attr(`style`, `height: 0;`);
                    $(`.wrapper .preloader .animation__shake`).attr(`style`, `display: none;`);
                }, 1000);
            }
        });
    }
}
function checkSession() {
    fetch(`../controller/views/checkSession.php`)
    .then(check => check.json())
    .then(check => {
        sessionStorage.setItem("session", check.STATUS);
    })
}
//------------------------------------------------------------------------------------------------------------------------------------------------------
// Modo oscuro
//------------------------------------------------------------------------------------------------------------------------------------------------------
$(`[data-widget="dark-mode"]`).on(`click`, function () {
    $(this).addClass(`animation__wobble`);
    setTimeout(() => {
        $(this).removeClass(`animation__wobble`);
    }, 1000);

    let classCheck = $(`[data-widget="dark-mode"] i`).attr(`class`);

    if (classCheck == `fa fa-sun`) {
        $(`.main-header`).attr(`class`, `main-header navbar navbar-expand navbar-dark`);
        $(`[data-widget="dark-mode"] i`).attr(`class`, `fa fa-moon`);
        $(`.sidebar`).removeClass(`os-theme-dark`).addClass(`os-theme-light`);
        $(`.main-sidebar`).removeClass(`sidebar-light-primary`).addClass(`sidebar-dark-primary`);
        $(`body`).addClass(`dark-mode`);
    } else {
        $(`.main-header`).attr(`class`, `main-header navbar navbar-expand navbar-white navbar-light`);
        $(`[data-widget="dark-mode"] i`).attr(`class`, `fa fa-sun`);
        $(`.sidebar`).removeClass(`os-theme-light`).addClass(`os-theme-dark`);
        $(`.main-sidebar`).removeClass(`sidebar-dark-primary`).addClass(`sidebar-light-primary`);
        $(`body`).removeClass(`dark-mode`);
    }
}).ready(function () { // psicologia inversa para establecer el estilo cuando carga

    let classCheck = $(`[data-widget="dark-mode"] i`).attr(`class`);

    if (classCheck == `fa fa-sun`) {
        $(`.main-header`).attr(`class`, `main-header navbar navbar-expand navbar-white navbar-light`);
        $(`[data-widget="dark-mode"] i`).attr(`class`, `fa fa-sun`);
        $(`.sidebar`).removeClass(`os-theme-light`).addClass(`os-theme-dark`);
        $(`.main-sidebar`).removeClass(`sidebar-dark-primary`).addClass(`sidebar-light-primary`);
        $(`body`).removeClass(`dark-mode`);
    } else {
        $(`.main-header`).attr(`class`, `main-header navbar navbar-expand navbar-dark`);
        $(`[data-widget="dark-mode"] i`).attr(`class`, `fa fa-moon`);
        $(`.sidebar`).removeClass(`os-theme-dark`).addClass(`os-theme-light`);
        $(`.main-sidebar`).removeClass(`sidebar-light-primary`).addClass(`sidebar-dark-primary`);
        $(`body`).addClass(`dark-mode`);
    }
})