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
require_once '../../utiles/connectDBUtiles.php';
require_once 'updates.php';
require_once './../insert/insertLog.php';

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

actualizarTrabajoFunction($status, $usuario, $solicitud);

/**
 * Inserta los valores de ByN por solicitud.
 *
 * @param int $status    The position of the token in the token stack
 * @param int $usuario   The number of spaces to adjust the indent by
 * @param int $solicitud The position of the token in the token stack
 *
 * @return json
 */
function actualizarTrabajoFunction($status, $usuario, $solicitud)
{
    global $updateTrabajo, $updateSolicitud,  $mysqlCon;
    
    /*definimos el json*/
    $jsondata = array();
    $jsondata["data"] = array();

    $parametros = "Query:" . $updateTrabajo . 
    " status:" . $status . 
    " solicitud:" . $solicitud . 
    " usuario:" . $usuario;

    $parametrosSolicitud = "Query:" . $updateSolicitud . 
    " status:" . $status . 
    " solicitud:" . $solicitud . 
    " usuario:" . $usuario;


    $path_parts = pathinfo(__FILE__);
    try {
        if ($stmt = $mysqlCon->prepare($updateTrabajo)) {
            $stmt->bind_param('iii', $status, $usuario, $solicitud);
            if (!$stmt->execute()) {
                $jsondata["success"] = false;
                $jsondata["message"] =  "Trabajo NO Actualizado. " . $updateTrabajo .
                                        " Parametros: " . $status . "--" . $solicitud . "--" . $usuario;
                crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Error Actualizando Trabajo:' . $mysqlCon->error, "error");
            } else {
                $jsondata["success"] = true;
                $jsondata["message"] = "Trabajo Actualizado."; 
                crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Trabajo Actualizado', "correcto");   
            }
        } else {
            $jsondata["success"] = false;
            $jsondata["message"] = "Error Actualizando Trabajo " . $mysqlCon->error;
            crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Error Actualizando Trabajo:' . $mysqlCon->error, "error");
        }
    
        if ($jsondata["success"] !== false) {
            if ($stmt = $mysqlCon->prepare($updateSolicitud)) {
                $stmt->bind_param('iii', $status, $usuario, $solicitud);
                if (!$stmt->execute()) {
                    $jsondata["success"] = false;
                    $jsondata["message"] =  "Solicitud NO Actualizado. " . $updateSolicitud .
                                            " Parametros: " . $status . "--" . $solicitud . "--" . $usuario;
                    crearLog($path_parts['filename'], __FUNCTION__, $parametrosSolicitud, 'Error Actualizando Solicitud:' . $mysqlCon->error, "error");
                } else {
                    $jsondata["success"] = true;
                    $jsondata["message"] = "Solicitud Actualizado.";
                    crearLog($path_parts['filename'], __FUNCTION__, $parametrosSolicitud, 'Solicitud Actualizado', "correcto");
                }
            } else {
                $jsondata["success"] = false;
                $jsondata["message"] = "Error Actualizando Solicitud " . $mysqlCon->error;
                crearLog($path_parts['filename'], __FUNCTION__, $parametrosSolicitud, 'Error Actualizando Solicitud:' . $mysqlCon->error, "error");
            }   
        }
    } catch (Exception $e) {
        $jsondata["success"] = false;
        $jsondata["message"] = "Error Actualizando Trabajo " . $e;
        crearLog($path_parts['filename'], __FUNCTION__, $parametrosSolicitud, 'Error Actualizando Solicitud:' . $e, "error");
        crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Error Actualizando Trabajo:' . $e, "error");
    } finally {
        $stmt->close();
        /*Devolvemos el JSON con los datos de la consulta*/
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata, JSON_FORCE_OBJECT);
    }
}


