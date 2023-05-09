<?php
// session_start();
if (!isset($_SESSION["isAdmin"]) || (strcasecmp($_SESSION["isAdmin"], 'Si') !== 0)) {
    require_once "../../config/LoadConfig.config.php";
    $config = LoadConfig::getConfig();
    header('Location:'.$config['URL_SITE'].'index.php');
}

?>

<section id="four" class="content">
    <div class="container">
        <header>
            <h3>Agregar Clase</h3>
        </header>
        <form action="#" id="formClase">
            <div class="row">
                <section class="col-4 col-md-3 col-sm-1">
                    <p></p>
                </section>
                <section class="col-4 col-md-6 col-sm-10">
                    <label for="title">Nombre</label>
                    <input type="text" name="title" style="color: black !important;" required/>
                </section>
                <section class="col-4 col-md-3 col-sm-1">
                    <p></p>
                </section>
                <section class="col-12 col-md-8 col-sm-12" id="butonSend">
                    <footer class="major">
                        <ul class="actions special">
                            <li><button type="submit" id="sendData" class="btn btn-primary fas fa-check-circle fi">Guardar</button></li>
                        </ul>
                    </footer>
                </section>
            </div>
        </form>
    </div>
</section>

<section id="five" class="wrapper style2 special fade">
    <div class="container">
        <header>
            <h3 style="color: white;">Administracion Clase</h3>
        </header>

        <section class="col-12 col-md-4 col-sm-12">
            <table class="tableAdmin">
                <thead>
                <tr>
                    <th>Titulo</th>
                    <th>Guardar</th>
                </tr>
                </thead>
                <tbody id="clase">
                <!-- Llenar datos con iteracion -->
                </tbody>
            </table>
        </section>
    </div>
</section>