<?php

/**
 * Class: varios2JSON.php
 *
 * Insercion de Varios 2 Extra
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

global $mysqlCon;

$solicitud = "";
$tipo = "";

//Recogemos los valores
if (isset($_POST['solicitud'])) {
    $solicitud = $_POST['solicitud'];
}
if (isset($_POST['tipo'])) {
    $tipo = $_POST['tipo'];
}
/*definimos el json*/
$jsondata = array();
$jsondata["data"] = array();

/*Prepare Statement*/
if ($tipo !== '6') {
    if ($stmt = $mysqlCon->prepare($recuperaArticulos)) {
        $stmt->bind_param('ii', $solicitud, $tipo);
        /*Ejecucion*/
        $stmt->execute();
        /*Almacenamos el resultSet*/
        $stmt->bind_result($tipoId, $tipoDesc, $detalle, $descripcion, $precio, $unidades, $precioTotal);
        
        /*Incluimos las lineas de la consulta en el json a devolver*/
        while ($stmt->fetch()) {
            $tmp = array();
            $tmp["detalle_id"] = utf8_encode($detalle);
            $tmp["tipo_id"] = utf8_encode($tipoId);
            $tmp["descripcion"] = utf8_encode($descripcion);
            $tmp["precio"] = utf8_encode($precio);
            $tmp["unidades"] = utf8_encode($unidades);
            $tmp["precioTotal"] = utf8_encode($precioTotal);
            
            array_push($jsondata["data"], $tmp);
        }
        $stmt->close();
        /*Asociamos el correcto funcionamiento al json para comprobar en el js*/
        $jsondata["success"] = true;
    } else {
        /*Llegamos aqui con error, asociamos false para identificarlo en el js*/
        $jsondata["success"] = false;
        die("Errormessage: " . $mysqlCon->error);
    }
} else {
    if ($stmt = $mysqlCon->prepare($recuperaArticulosExtra)) {
        $stmt->bind_param('ii', $solicitud, $solicitud);
        /*Ejecucion*/
        $stmt->execute();
        /*Almacenamos el resultSet*/
        $stmt->bind_result($tipoId, $tipoDesc, $detalle, $descripcion, $precio, $unidades, $precioTotal);
        
        /*Incluimos las lineas de la consulta en el json a devolver*/
        while ($stmt->fetch()) {
            $tmp = array();
            $tmp["detalle_id"] = utf8_encode($detalle);
            $tmp["tipo_id"] = utf8_encode($tipoId);
            $tmp["descripcion"] = utf8_encode($descripcion);
            $tmp["precio"] = utf8_encode($precio);
            $tmp["unidades"] = utf8_encode($unidades);
            $tmp["precioTotal"] = utf8_encode($precioTotal);
            
            array_push($jsondata["data"], $tmp);
        }
        $stmt->close();
        /*Asociamos el correcto funcionamiento al json para comprobar en el js*/
        $jsondata["success"] = true;
    } else {
        /*Llegamos aqui con error, asociamos false para identificarlo en el js*/
        $jsondata["success"] = false;
        die("Errormessage: " . $mysqlCon->error);
    }

}


/*Devolvemos el JSON con los datos de la consulta*/
header('Content-type: application/json; charset=utf-8');
echo json_encode($jsondata, JSON_FORCE_OBJECT);
?>