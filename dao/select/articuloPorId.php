<?php
/**
 * Class: altaDepartamento.php
 *
 * Clase que da de alta el departamento
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

global $recuperaDetallePorId, $mysqlCon;

/*definimos el json*/
$jsondata = array();
$jsondata["data"] = array();

//Declaracion de parametros
$tipo = "";
$detalle = "";

//Recogemos los valores
if (isset($_POST['id'])) {
    $tipo = $_POST['id'];
}

if (isset($_POST['id1'])) {
    $detalle = $_POST['id1'];
}


if ($tipo === '' || $detalle === '') {
    $jsondata["success"] = false;
    $jsondata["message"] = 'Faltan Parametros de entrada.';
} else {
    try {
        /*Prepare Statement*/
        if ($stmt = $mysqlCon->prepare($recuperaDetallePorId)) {
            $stmt->bind_param('ii', $tipo, $detalle);
            /*Ejecucion*/
            $stmt->execute();
            /*Almacenamos el resultSet*/
            $stmt->bind_result($detalleId, $nombre, $precio);

            /*Incluimos las lineas de la consulta en el json a devolver*/
            while ($stmt->fetch()) {
                $tmp = array();
                $tmp["id"] = utf8_encode($detalleId);
                $tmp["nombre"] = utf8_encode($nombre);
                $tmp["precio"] = utf8_encode($precio);
                array_push($jsondata["data"], $tmp);
            }
            $stmt->close();
            /*Asociamos el correcto funcionamiento al json para comprobar en el js*/
            $jsondata["success"] = true;
        } else {
            /*Llegamos aqui con error, asociamos false para identificarlo en el js*/
            $jsondata["success"] = false;
            $jsondata["message"] = $mysqlCon->error;
        }
    } catch (Exception $e) {
        $jsondata["success"] = false;
        $jsondata["message"] = $e;
    }
}

/*Devolvemos el JSON con los datos de la consulta*/
header('Content-type: application/json; charset=utf-8');
echo json_encode($jsondata, JSON_FORCE_OBJECT);
?>