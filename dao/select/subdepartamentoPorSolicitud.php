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

$id = "No DATA";

//Recogemos los valores
if (isset($_POST['solicitudId'])) {
    $id = $_POST['solicitudId'];
} else {
    $id = $_GET['solicitudId'];
}
/*Realizamos la llamada a la funcion que devolvera los departamentos*/
recuperamosSubdepartamentoIdSolicitud($id);

/**
 * Inserta los valores de ByN por solicitud.
 *
 * @param int $id The position of the token in the token stack
 *
 * @return json
 */
function recuperamosSubdepartamentoIdSolicitud($id)
{
    global $sentenciaSubDepartamentoJSON, $mysqlCon;
    /*definimos el json*/
    $jsondata = array();
    $jsondata["data"] = array();
    $subdepartamento_desc = "";
    $treintaBarra = "";
    
    if ($stmt = $mysqlCon->prepare($sentenciaSubDepartamentoJSON)) {
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $jsondata["success"] = true;
        $stmt->bind_result($subdepartamento_desc, $treintaBarra);
        while ($stmt->fetch()) {
            $tmp = array();
            $tmp["subdepartamentos_desc"] =  utf8_encode($subdepartamento_desc);
            $tmp["treintaBarra"] = $treintaBarra;
            /*Asociamos el resultado en forma de array en el json*/
            array_push($jsondata["data"], $tmp);
        }
        $stmt->close();
        $jsondata["success"] = true;
    } else {
        $jsondata["success"] = false;
    }
    /*Devolvemos el JSON con los datos de la consulta*/
    header('Content-type: application/json; charset=utf-8');
    echo json_encode($jsondata, JSON_FORCE_OBJECT);
}
