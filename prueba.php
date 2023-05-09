<h1>La pagina se reinicia al darle click!</h1>
<?php // codigo
$myFolder = explode("/", $_SERVER['REQUEST_URI'])[1];

x("{$_SERVER['DOCUMENT_ROOT']}/{$myFolder}/node_modules/*");
// function x ($folder, $search, $replace) {
function x($folder)
{
    $all_files = glob($folder);
    // if (is_dir($folder)) {
    //     $all_files = glob($folder);
    // } else {
    //     $all_files = [$folder];
    // }
    // $ArrayReplace = [
    //     "col-1-xsmall"  => "col-sm-1",
    //     "col-1-medium"  => "col-md-1",
    //     "col-2-xsmall"  => "col-sm-2",
    //     "col-2-medium"  => "col-md-2",
    //     "col-3-xsmall"  => "col-sm-3",
    //     "col-3-medium"  => "col-md-3",
    //     "col-4-xsmall"  => "col-sm-4",
    //     "col-4-medium"  => "col-md-4",
    //     "col-5-xsmall"  => "col-sm-5",
    //     "col-5-medium"  => "col-md-5",
    //     "col-6-xsmall"  => "col-sm-6",
    //     "col-6-medium"  => "col-md-6",
    //     "col-7-xsmall"  => "col-sm-7",
    //     "col-7-medium"  => "col-md-7",
    //     "col-8-xsmall"  => "col-sm-8",
    //     "col-8-medium"  => "col-md-8",
    //     "col-9-xsmall"  => "col-sm-9",
    //     "col-9-medium"  => "col-md-9",
    //     "col-10-xsmall" => "col-sm-10",
    //     "col-10-medium" => "col-md-10",
    //     "col-11-xsmall" => "col-sm-11",
    //     "col-11-medium" => "col-md-11",
    //     "col-12-xsmall" => "col-sm-12",
    //     "col-12-medium" => "col-md-12",
    //     "mainValue fieldReport" => "form-control",
    //     "fieldReport" => "form-control",
    //     'class="error"' => 'class="text-danger"',
    //     "icon solid" => "fas",
    //     "button primary" => "btn btn-primary",
    //     "button success" => "btn btn-success",
    //     "button info" => "btn btn-info",
    //     "button danger" => "btn btn-danger",
    //     "button warning" => "btn btn-warning",
    //     "row gtr-uniform" => "row",
    //     "row gtr-50" => "row",
    //     "alt tableStatus row-border display compact" => "table",
    //     "wrapper style1 special fade-up" => "my-2 py-2",
    //     "> <" => "><",
    //     "session_start" => "// session_start"
    // ];

    print "<pre>";
    // print_r(["recover" => $folder]);
    foreach ($all_files as $recover) {
        // formateo los valores para evitar problemas
        if (is_dir($recover)) { // si es un directorio entra en bucle para buscar cada uno de los archivos de cada carpeta
            print_r(["enter in folder" => $recover]);
            x("{$recover}/*");
        } else { // si es un archivo obtenemos su contenido para poder reemplazar los valores
            print_r(["open and change file" => $recover]);

            // formateo los valores para evitar errores
            $file = "";
            $newFile = "";

            // $file = file_get_contents($recover); // obtenemos el archivo
            $newFile = $file; // asigno el contenido a una nueva variable

            // foreach ($ArrayReplace as $search => $replace) {
            //     if ($search && $replace) { // por si acaso
            //         // reemplazamos los valores sobre si mismo para que se actualice todo el archivo con los nuevos datos
            //         $newFile = str_replace($search, $replace, $newFile);
            //     }
            // }

            // file_put_contents($recover, $newFile); // por ultimo solo cambio los valores

        }
    }
    print "</pre>";
}
?>
<!-- <script>
    document.querySelector("html").addEventListener("click", () => {
        location.reload();
    });
</script> -->