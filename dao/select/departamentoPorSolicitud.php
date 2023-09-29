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

require_once 'query.php';
require_once '../../utiles/connectDBUtiles.php';


$id = "No DATA";

//Recogemos los valores
if (isset($_POST['solicitudId'])) {
    $id = $_POST['solicitudId'];
} else {
    $id = $_GET['solicitudId'];
}


/*Realizamos la llamada a la funcion que devolvera los departamentos*/
recuperamosDepartamentoIdSolicitud($id);

function recuperamosDepartamentoIdSolicitud($id)
{
    
    $fichero = "departamentoPorSolicitud.php";
    $funcionLog = "recuperamosDepartamentoIdSolicitud";
    
    global $sentenciaDepartamentoJSON, $mysqlCon;
    /*definimos el json*/
    $jsondata = array();
    $jsondata["data"] = array();
    $departamentos_desc = "";
    $ceco = "";
    
    if ($stmt = $mysqlCon->prepare($sentenciaDepartamentoJSON)) {
    
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $jsondata["success"] = true;

        $stmt->bind_result($departamentos_desc, $ceco);
    
        while ($stmt->fetch()) {
            $tmp = array();
            $tmp["departamentos_desc"] =  utf8_encode($departamentos_desc);
            $tmp["ceco"] = $ceco;
            array_push($jsondata["data"], $tmp);
        }
        $stmt->close();
        $jsondata["success"] = true;
    } else {
        $log  = "Fichero: " . $fichero .PHP_EOL.
        "Funcion: " . $funcionLog .PHP_EOL.
        "Query: ".$sentenciaDepartamentoJSON.PHP_EOL.
        "Errormessage: ". $mysqlCon->error.PHP_EOL.
        "-------------------------".PHP_EOL;
        error_log($log, 3, "../../log/errores.log");
    }
    
    /*Devolvemos el JSON con los datos de la consulta*/
    header('Content-type: application/json; charset=utf-8');
    echo json_encode($jsondata, JSON_FORCE_OBJECT);

}