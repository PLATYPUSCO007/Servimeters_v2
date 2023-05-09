<?php

use JetBrains\PhpStorm\Internal\returnTypeContract;

require_once $_SERVER["DOCUMENT_ROOT"] . '/' . explode("/", $_SERVER['REQUEST_URI'])[1] . "/config/DB.config.php";

/*
 * - CREATE
 * 27/05/2023
 * Cree la estructura general de todo el codigo
 * - UPDATE
 * 03/05/2023
 * Cambio general en las consultas las hice con mysql y esto esta en sql server y pos bueno no funcionaba
 * Agregue getters para obtener información
 * Corrección de varios errores
 * - TESTING
 * 04/05/2023
 * Pruebas en general y documentar bien todo el codigo
 */

/**
 * @author Esteban Serna Palacios 😉😜
 * @version 1.0.0
 */

class AutomaticForm extends DB
{

    private $data;
    private $file;
    private $db;
    private $conn;
    private $config;
    private $table;
    private $action;
    private $update;
    private $alldata;
    private $idOper;

    /**
     * @param Array $alldata Recibe dos arreglos, el primero [ "data" ] para información en general y el otro es para archivos [ "file" ] -- si algo pararle como parámetro $_FILES.
     * @param String $table Nombre de la tabla si no existe puede ser creada.
     * @param String $action La accion recive uno de dos parametros "INSERT" o "UPDATE"
     * @param Array|String|Int $update Campo únicamente sirve para hacer updates puede recibir un arreglo con el nombre de su llave primaria o también puede recibir solo el valor y el código se encarga de buscar la llave de esa tabla.
     */
    public function __construct(
        array $alldata = [
            "data" => [],
            "file" => []
        ],
        String $table,
        String $action = "INSERT",
        array|String|Int $update = [
            "@primary" => false
        ]
    ) {
        $this->table = $table;
        $this->action = strtoupper($action);
        // $this->update = $update;

        if (!is_array($update)) { // si no es un arreglo busco a la llave primaria de la tabla y que filtre por el valor que le estoy pasando
            $this->update = [AutomaticForm::getNamePrimary($this->table) => $update];
        } else { // si es un arreglo verguero el que me toco hacer :c

            $check = key(array_filter($update, function ($x) { // busco que el arreglo contenga la palabra @primary para hacerle el cambio de la llave primaria de esa tabla
                return str_contains($x, "@primary");
            }, ARRAY_FILTER_USE_KEY));

            if (!is_null($check)) {
                $this->update = [AutomaticForm::getNamePrimary($this->table) => $update[$check]];
            } else {
                $this->update = $update;
            }
        }

        $this->db = new DB();
        $this->conn = $this->db->Conectar();
        $this->config = AutomaticForm::getConfig();

        $this->alldata = $alldata;

        $this->data = (isset($this->alldata["data"]) && !empty(count($this->alldata["data"])) ? $this->alldata["data"] : false);
        $this->file = (isset($this->alldata["file"]) && !empty(count($this->alldata["file"])) ? $this->alldata["file"] : false);

        $this->idOper = 0;
    }

    /**
     * @return Array Devuelve algunos de los parametros definidos en el __construct
     */
    public function getParams(): array
    {
        return [
            "table"     => $this->table,
            "action"    => $this->action,
            "data"      => $this->data,
            "file"      => $this->file,
            "config"    => $this->config
        ];
    }

    /**
     * @return Int Devuelve el ID de la operación
     */
    public function getId(): Int|String
    {
        return $this->idOper;
    }

    /**
     * @return Array Devuelve todos los datos enviados
     */
    public function getAllData(): array
    {
        return $this->alldata;
    }

    /**
     * @return Array Devuelve los datos enviados
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @return Array Devuelve los archivos enviados
     */
    public function getFile(): array
    {
        return $this->file;
    }

    /**
     * @return Array Devuelve el arreglo para actualizar los datos
     */
    public function getUpdate(): array
    {
        return $this->update;
    }

    /**
     * @param bool $auto_craete Válida la existencia de los campos y las tablas y si no existen las crea.
     * @param bool $checkEmptyValues Válida que los campos no este vacío, si recibe un dato que este vacío lo emite tanto en la creación como en la ación a realizar.
     * @return array Devuelve un arreglo con el estado y el ID de la operación, ya sea insert o update.
     */
    public function execute(Bool $auto_craete = true, Bool $checkEmptyValues = false): array
    {

        if (empty($this->table | $this->action) || ($this->action == "INSERT" || $this->action == "UPDATE" ? false : true)) {
            return ["Error" => "Error params"];
        } else if ($this->action == "UPDATE") {
            $id_u = key($this->update);
            $va_u = $this->update[$id_u];
        }

        if ($auto_craete == true) {
            // creamos la tabla en el caso de que no exista
            $this->conn->beginTransaction();

            // creamos los campos si no existen
            $query = $this->conn->prepare("
                IF NOT EXISTS (SELECT * FROM sysobjects WHERE name = '{$this->table}' and xtype = 'U')
                    CREATE TABLE {$this->table} (
                        id INT IDENTITY(1,1) PRIMARY KEY,
                        fechaRegistro DATETIME DEFAULT CURRENT_TIMESTAMP
                    )
                ");
            $query->execute();
            $this->conn->commit();

            $checkAll = [];

            $checkAll = array_merge(isset($this->data) ? $this->data : [], isset($this->file["name"]) ? $this->file["name"] : []);

            foreach ($checkAll as $key => $value) {
                if ($checkEmptyValues && !is_array($value) ? !empty($value) : true) {
                    $this->conn->beginTransaction();

                    // creamos las columnas si no existen
                    $query = $this->conn->prepare("
                        IF NOT EXISTS (SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '{$this->table}' AND COLUMN_NAME = '{$key}')
                            BEGIN
                            ALTER TABLE {$this->table} ADD {$key} VARCHAR(MAX) DEFAULT NULL
                        END
                        ");
                    $query->execute();
                    $this->conn->commit();
                }
            }
        }

        // variables que uso como plantilla
        // Nota: no cambiar por que me onojo >:(

        $insert = "(``) VALUES ('')";
        $update = "`` = ''";

        // data
        if (isset($this->data) && !empty(count($this->data))) {
            foreach ($this->data as $key => $value) {
                if ($checkEmptyValues && !is_array($value) ? !empty($value) : true) {
                    if ($this->action == "INSERT") {
                        $insert = str_replace("``", "`{$key}`, ``", str_replace("''", "'{$value}', ''", $insert));
                    } else if ($this->action == "UPDATE") {
                        $update = str_replace("`` = ''", "`{$key}` = '{$value}', `` = ''", $update);
                    }
                } else {
                    // pendiete codigo para datos multiples
                }
            }
        }
        // data

        // files
        if (isset($this->file["name"]) && !empty(count($this->file["name"]))) {
            foreach ($this->file["name"] as $key => $value) {
                if ($checkEmptyValues && !is_array($value) ? !empty($value) : true) {
                    $carpeta = "{$this->config->FOLDER_SITE}files/{$this->table}/";
                    if (!file_exists($carpeta)) { // creamos la carpeta si no existe
                        mkdir($carpeta, 0777, true);
                    }

                    $value = "{$carpeta}" . date("YmdHis") . "_{$value}"; // le cambiamos el nombre al archivo con toda la ruta donde se va a cargar 

                    move_uploaded_file($this->file["tmp_name"][$key], "{$value}"); // subimos el archivo

                    if ($this->action == "INSERT") {
                        $insert = str_replace("``", "`{$key}`, ``", str_replace("''", "'{$value}', ''", $insert));
                    } else if ($this->action == "UPDATE") {
                        $update = str_replace("`` = ''", "`{$key}` = '{$value}', `` = ''", $update);
                    }
                } else {
                    // pendiete codigo para datos multiples
                }
            }
        }
        // files

        $insert = str_replace("`", "", str_replace(", ``", "", str_replace(", ''", "", $insert)));
        $update = str_replace("`", "", str_replace(", `` = ''", "", $update));

        $q = $this->action == "INSERT" ? "{$this->action} INTO {$this->table} {$insert}" : "{$this->action} {$this->table} SET {$update} WHERE {$id_u} = '{$va_u}'";

        try {
            $this->conn->beginTransaction();
            $query = $this->conn->prepare($q);
            $checkQUery = $query->execute();
            $this->conn->commit();

            $returnID = $this->action == "INSERT" ? $this->conn->lastInsertId() : $va_u;

            $this->idOper = $returnID;

            return ["status" => $checkQUery ? true : false, "id" => $returnID, "query" => $q];
        } catch (PDOException $th) {
            return ["status" => false, "query" => $q, "error" => $th->errorInfo];
        }
    }

    /**
     * @param String $table nombre de la tabla
     * @return String Solo retorna el nombre de la llave primaria de una tabla, algo innecesario pero útil.
     */
    public static function getNamePrimary(String $table): String
    { // no he probado esta madre, pero confio en cristo rey :)
        $db = new DB();
        $conn = $db->Conectar();

        $q = "SELECT * FROM sys.columns WHERE OBJECT_ID = OBJECT_ID('{$table}') and is_identity = 1";
        $query = $conn->prepare($q);

        if (!$query->execute()) {
            return false;
        } else {
            $data = $query->fetch(PDO::FETCH_ASSOC);
            return (isset($data["name"]) && !empty($data["name"]) ? $data["name"] : false);
        }
    }

    /**
     * @param String $filter campo a filtrar
     * @param String $column columna a filtrar
     * @param String $return columna que va a devolver
     * @param String $table nombre de la tabla
     * @param Array  $config variable especial para cambiar algunos parametros 
     * @return String devuelve el valor enviado en $return si esta vacia o no se ejecuta la consulta devuelve false
     */
    public static function getValue($filter, $column, $return, $table, $config = []): String|Int
    {

        $defaultConfig = [
            "like" => false,
            "notResult" => false
        ];

        $c = array_merge($defaultConfig, $config);

        $db = new DB();
        $conn = $db->Conectar();
        $primaryKey = AutomaticForm::getNamePrimary($table);

        $q = "SELECT {$return} FROM {$table}
            WHERE {$column} " . ($c["like"] == true ? " like '%{$filter}%' " : " = '{$filter}' ") . "
            ORDER BY {$primaryKey}
            OFFSET 0 ROWS FETCH NEXT 1 ROWS ONLY";

        try {
            $query = $conn->prepare($q);

            if (!$query->execute()) {
                return $c["notResult"];
            } else {
                $data = $query->fetch(PDO::FETCH_ASSOC);
                return (isset($data[$return]) && !empty($data[$return]) ? $data[$return] : $c["notResult"]);
            }
        } catch (PDOException $th) {
            return $th->errorInfo;
        }
    }
}

// Pruebas y demostracion
// por favor dejar comentado no >:c
// INSERT
// include("../pruebasAF/pruebaAF1.php"); // prueba con codigo
// include("../pruebasAF/pruebaAF2.php"); // prueba con formulario
// include("../pruebasAF/pruebaAF3.php"); // prueba con formulario y nombre de los arreglos
// UPDATE
// include("../pruebasAF/pruebaAF4.php"); // prueba con formulario para actualizar datos