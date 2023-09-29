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

global $todosDepartamentosQuery, $mysqlCon;
$usuario = "";

    //Recogemos los valores
if (isset($_POST['valor'])) {
    $usuario = $_POST['valor'];
}

/*definimos el json*/
$jsondata = array();
$jsondata["data"] = array();

/*Prepare Statement*/
if ($stmt = $mysqlCon->prepare($recuperaDptoXAutorizadorJSON)) {
    $stmt->bind_param("i", $usuario);
    /*Ejecucion*/
    $stmt->execute();
    /*Almacenamos el resultSet*/
    $stmt->bind_result($dptoId, $dptoDesc, $ceco);

    /*Incluimos las lineas de la consulta en el json a devolver*/
    while ($stmt->fetch()) {
        $tmp = array();
        $tmp["id"] = utf8_encode($dptoId);
        $tmp["nombre"] = utf8_encode($dptoDesc);

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

/*Devolvemos el JSON con los datos de la consulta*/
header('Content-type: application/json; charset=utf-8');
echo json_encode($jsondata, JSON_FORCE_OBJECT);
?>