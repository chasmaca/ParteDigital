<?php
/**
 * Class: altaArticulo.php
 *
 * Formulario de alta de nuevos articulos de reprografia
 * php version 7.3.28
 * 
 * @category Insert
 * @package  DaoInsert
 * @author   Jesus Madrazo <chasmaca@gmail.com>
 * @license  http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version  GIT: <Initial Import>
 * @link     https://www.elpartedigital.com/
 */
require_once './../../utiles/connectDBUtiles.php';
require_once './borrado.php' ;
require_once './../insert/insertLog.php' ;

global $borradoDepartamento, $mysqlCon;
$departamento = "";

//Recogemos los valores
if (isset($_POST['departamento'])) {
    $departamento = utf8_decode(trim($_POST['departamento']));
}

$parametros = "departamento: " . $departamento;
$className = basename(__FILE__, '.php'); 

if ($departamento === '') {
    $jsondata["success"] = false;
    $jsondata["message"] = "Faltan por enviar Parámetros";
    crearLog($className, "no-function", $parametros, "Faltan Parámetros", "error");

} else {
    try {
        if ($stmt = $mysqlCon->prepare($borradoDepartamento)) {
            $stmt->bind_param('i', $departamento);
            $stmt->execute();
            $jsondata["success"] = true;
            $jsondata["message"] = "Se ha eliminado el Departamento";
            crearLog($className, "no-function", $parametros, "Se ha eliminado el Departamento", "correcto");
        } else {
            $jsondata["success"] = false;
            $jsondata["message"] = "Error Insertando Departamento" . $mysqlCon->error;
            crearLog($className, "no-function", $parametros, $mysqlCon->error, "error");
        }
    } catch (Exception $e) {
        $jsondata["success"] = false;
        $jsondata["message"] = "Error Insertando Departamento" . $e;
        crearLog($className, "no-function", $parametros, $e, "error");
    }
    $stmt->close();
}

/*Devolvemos el JSON con los datos de la consulta*/
header('Content-type: application/json; charset=utf-8');
echo json_encode($jsondata, JSON_FORCE_OBJECT);

?>