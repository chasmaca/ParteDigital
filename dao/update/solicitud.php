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
require_once "../update/updates.php";
require_once "./../insert/insertLog.php";

$solicitud = "";
$status = "";
$usuario = "";

//Recogemos los valores
if (isset($_POST['solicitud'])) {
    $solicitud = $_POST['solicitud'];
}

if (isset($_POST['status'])) {
    $status = $_POST['status'];
}

if (isset($_POST['usuario'])) {
    $usuario = $_POST['usuario'];
}

actualizaSolicitudFunction($status, $usuario, $solicitud);

/**
 * Inserta los valores de ByN por solicitud.
 *
 * @param int $status    The position of the token in the token stack
 * @param int $usuario   The position of the token in the token stack
 * @param int $solicitud The position of the token in the token stack
 *
 * @return json
 */
function actualizaSolicitudFunction($status, $usuario, $solicitud)
{
    global $updateTrabajo, $reabreSolicitud, $mysqlCon;

    /*definimos el json*/
    $jsondata = array();
    $jsondata["data"] = array();
    $path_parts = pathinfo(__FILE__);
    $parametros = "Query:" . $updateTrabajo . 
    " status:" . $status . 
    " usuario:" . $usuario . 
    " solicitud:" . $solicitud;

    $parametrosReapertura = "Query:" . $reabreSolicitud . 
    " status:" . $status . 
    " usuario:" . $usuario . 
    " solicitud:" . $solicitud;

    try {
        if ($stmt = $mysqlCon->prepare($updateTrabajo)) {
            $stmt->bind_param('iii', $status, $usuario, $solicitud);
            if (!$stmt->execute()) {
                $jsondata["success"] = false;
                $jsondata["message"] =  "Trabajo NO Actualizado. " . $updateTrabajo .
                                        " Parametros: " . $status . "--" . $solicitud . "--" . $usuario;
                crearLog($path_parts['filename'], __FUNCTION__, $parametros, "Error Actualizando Trabajo :" . $mysqlCon->error, "error");
            } else {
                $jsondata["success"] = true;
                $jsondata["message"] = "Trabajo Actualizado.";
                crearLog($path_parts['filename'], __FUNCTION__, $parametros, "Trabajo Actualizado", "correcto");
            }
        } else {
            $jsondata["success"] = false;
            $jsondata["message"] = "Error Actualizando Trabajo " . $mysqlCon->error;
            crearLog($path_parts['filename'], __FUNCTION__, $parametros, "Error Actualizando Trabajo :" . $mysqlCon->error, "error");
        }
    
        if ($jsondata["success"] !== false) {
            if ($stmt = $mysqlCon->prepare($reabreSolicitud)) {
                $stmt->bind_param('iii', $status, $usuario, $solicitud);
                if (!$stmt->execute()) {
                    $jsondata["success"] = false;
                    $jsondata["message"] =  "Solicitud NO Actualizado. " . $reabreSolicitud .
                                            " Parametros: " . $status . "--" . $solicitud . "--" . $usuario;
                    crearLog($path_parts['filename'], __FUNCTION__, $parametrosReapertura, "Solicitud NO Actualizado :" . $mysqlCon->error, "error");
                } else {
                    $jsondata["success"] = true;
                    $jsondata["message"] = "Solicitud Actualizado.";    
                    crearLog($path_parts['filename'], __FUNCTION__, $parametrosReapertura, "Solicitud Actualizada", "correcto");
                }
            } else {
                $jsondata["success"] = false;
                $jsondata["message"] = "Error Actualizando Solicitud " . $mysqlCon->error;
                crearLog($path_parts['filename'], __FUNCTION__, $parametrosReapertura, "Solicitud NO Actualizado :" . $mysqlCon->error, "error");
            }   
        }
    } catch (Exception $e) {
        $jsondata["success"] = false;
        $jsondata["message"] = "Error Actualizando Trabajo " . $e;
        crearLog($path_parts['filename'], __FUNCTION__, $parametrosReapertura, "Solicitud NO Actualizado :" . $e, "error");
        crearLog($path_parts['filename'], __FUNCTION__, $parametros, "Error Actualizando Trabajo :" . $e, "error");
    } finally {
        $stmt->close();
        /*Devolvemos el JSON con los datos de la consulta*/
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata, JSON_FORCE_OBJECT);
    }
}