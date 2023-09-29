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

require_once "../../utiles/connectDBUtiles.php";
require_once "./../update/updates.php";
require_once "./../insert/insertLog.php";

$solicitud = isset($_POST['solicitud']) ? utf8_decode(trim($_POST['solicitud'])) : "";

$status = 5;

if ($solicitud === "") {
    $jsondata["success"] = false;
    $jsondata["message"] = "Faltan por enviar Parámetros";
     /*Devolvemos el JSON con los datos de la consulta*/
    header('Content-type: application/json; charset=utf-8');
    echo json_encode($jsondata, JSON_FORCE_OBJECT);
} else {
    reabreTrabajo($solicitud, $status);
}

/**
 * Inserta los valores de ByN por solicitud.
 *
 * @param int $solicitud The position of the token in the token stack
 * @param int $status    The position of the token in the token stack
 *
 * @return json
 */
function reabreTrabajo($solicitud, $status)
{
    global $mysqlCon, $sentenciaEstadoSolicitud;

    $jsondata = array();
    $jsondata["data"] = array();
    $path_parts = pathinfo(__FILE__);
    $parametros = "Query:" . $sentenciaEstadoSolicitud . 
    " status:" . $status . 
    " solicitud:" . $solicitud;

    try {
        if ($stmt = $mysqlCon->prepare($sentenciaEstadoSolicitud)) {
            $stmt->bind_param('ii', $status, $solicitud);
            if (!$stmt->execute()) {
                $jsondata["success"] = false;
                $jsondata["message"] = "Error Reabriendo Solicitud. " . $mysqlCon->error;
                crearLog($path_parts['filename'], __FUNCTION__, $parametros, "Error Reabriendo Solicitud :" . $mysqlCon->error, "error");
            } else {
                $jsondata["success"] = true;
                $jsondata["message"] = "Solicitud Reabierta";
                crearLog($path_parts['filename'], __FUNCTION__, $parametros, "Solicitud Reabierta", "correcto");
            }
        } else {
            $jsondata["success"] = false;
            $jsondata["message"] = "Error Reabriendo Solicitud. " . $mysqlCon->error;
            crearLog($path_parts['filename'], __FUNCTION__, $parametros, "Error Reabriendo Solicitud :" . $mysqlCon->error, "error");
        }
    } catch (Exception $e) {
        $jsondata["success"] = false;
        $jsondata["message"] = "Error Reabriendo Solicitud" . $e;
        crearLog($path_parts['filename'], __FUNCTION__, $parametros, "Error Reabriendo Solicitud :" . $e, "error");
    } finally {
        $stmt->close();
        /*Devolvemos el JSON con los datos de la consulta*/
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata, JSON_FORCE_OBJECT);
    }
}


?>