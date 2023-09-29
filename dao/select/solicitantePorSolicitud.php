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
recuperamosSolicitanteIdSolicitud($id);

function recuperamosSolicitanteIdSolicitud($id)
{
    global $sentenciaSolicitanteJSON, $mysqlCon;
    /*definimos el json*/
    $jsondata = array();
    $jsondata["data"] = array();

    $nombre = "";
    $apellido = "";
    
    if ($stmt = $mysqlCon->prepare($sentenciaSolicitanteJSON)) {

        $stmt->bind_param('i',$id);
        $stmt->execute();
        $jsondata["success"] = true;

        $stmt->bind_result($nombre, $apellido);

        while($stmt->fetch()){
            $tmp = array();
            $tmp["nombre"] = utf8_encode($nombre . " " . $apellido);
            /*Asociamos el resultado en forma de array en el json*/

            array_push($jsondata["data"], $tmp);

        }
        $stmt->close();
        /*Asociamos el correcto funcionamiento al json para comprobar en el js*/
        $jsondata["success"] = true;
    } else {
        /*Asociamos el correcto funcionamiento al json para comprobar en el js*/
        $jsondata["success"] = false;
        $log  =
        "Fichero: solicitantePorSolicitud.php".PHP_EOL.
        "Query: ".$sentenciaSolicitanteJSON.PHP_EOL.
        "Errormessage: ". $mysqlCon->error.PHP_EOL.
        "-------------------------".PHP_EOL;
        error_log($log, 3, "../../log/errores.log");
    }

    /*Devolvemos el JSON con los datos de la consulta*/
    header('Content-type: application/json; charset=utf-8');
    echo json_encode($jsondata, JSON_FORCE_OBJECT);

}