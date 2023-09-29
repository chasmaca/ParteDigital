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

require_once "./../../utiles/connectDBUtiles.php";
require_once "./../update/updates.php";
require_once "./../insert/insertLog.php";

session_start();
$solicitud = $_GET["solicitudId"];
$status = $_GET["status"];

if ($status==5) {
    updateSolicitud($status, $solicitud);
}

if ($status == 4) {
        $usuarioPlantilla = $_SESSION["nombre_session"];
        guardarSolicitudPorUsuario($status, $solicitud, $usuarioPlantilla);
}

if ($status==6) {
    cerrarSolicitud($status, $solicitud);
}

/**
 * Inserta los valores de ByN por solicitud.
 *
 * @param int $status    The position of the token in the token stack
 * @param int $solicitud The position of the token in the token stack
 *
 * @return json
 */
function updateSolicitud($status, $solicitud)
{
    global $mysqlCon, $sentenciaEstadoSolicitud, $sentenciaEstadoSolicitudPlantilla;
    $jsondata = array();
    $parametros = "status - " . $status . " solicitud - " . $solicitud;
    $path_parts = pathinfo(__FILE__);

    try{
        if ($stmt = $mysqlCon->prepare($sentenciaEstadoSolicitud)) {
            $stmt->bind_param('ii', $status, $solicitud);
            if (!$stmt->execute()) {
                $jsondata["success"] = false;
                $jsondata["errorMessage"] = "Errormessage: ". $mysqlCon->error;
                crearLog($path_parts['filename'], __FUNCTION__, $parametros, $mysqlCon->error, "error");
            } else {
                $jsondata["success"] = true;
                crearLog($path_parts['filename'], __FUNCTION__, $parametros, "Solicitud Actualizada", "correcto");
            }
        } else {
            $jsondata["success"] = false;
            $jsondata["errorMessage"] = "Errormessage: ". $mysqlCon->error;

            crearLog($path_parts['filename'], __FUNCTION__, $parametros, $mysqlCon->error, "error");
        }

    } catch (Exception $e) {
        $jsondata["success"] = false;
        $jsondata["errorMessage"] = "Errormessage: ". $e;
        crearLog($path_parts['filename'], __FUNCTION__, $parametros, $e, "error");
    }finally{
        $stmt->close();
        /*Devolvemos el JSON con los datos de la consulta*/
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata, JSON_FORCE_OBJECT);

    }
    
}

/**
 * Inserta los valores de ByN por solicitud.
 *
 * @param int $status           The position of the token in the token stack
 * @param int $solicitud        The position of the token in the token stack
 * @param int $usuarioPlantilla The position of the token in the token stack
 *
 * @return json
 */
function guardarSolicitudPorUsuario($status, $solicitud, $usuarioPlantilla)
{
    global $mysqlCon, $sentenciaEstadoSolicitudPlantilla;
    $jsondata = array();
    $parametros = "status - " . $status . " solicitud - " . $solicitud . " usuarioPlantilla:" . $usuarioPlantilla;
    $path_parts = pathinfo(__FILE__);

    try{
        if ($stmt = $mysqlCon->prepare($sentenciaEstadoSolicitudPlantilla)) {

            $stmt->bind_param('isi', $status, $usuarioPlantilla, $solicitud);
        
            if (!$stmt->execute()) {
                $jsondata["success"] = false;
                $jsondata["errorMessage"] = "Errormessage: ". $mysqlCon->error;

                crearLog($path_parts['filename'], __FUNCTION__, $parametros, $mysqlCon->error, "error");

            } else {
                $jsondata["success"] = true;
                crearLog($path_parts['filename'], __FUNCTION__,  $parametros, "Solicitud Guardada", "correcto");
            }
            
        } else {
            $jsondata["success"] = false;
            $jsondata["errorMessage"] = "Errormessage: ". $mysqlCon->error;

            crearLog($path_parts['filename'], __FUNCTION__,  $parametros, $mysqlCon->error, "error");

        }
    
    } catch (Exception $e) {
        $jsondata["success"] = false;
        $jsondata["errorMessage"] = "Errormessage: ". $e;
        crearLog("solicitudStatusJSON.php", "guardarSolicitudPorUsuario", $parametros, $e, "error");
    } finally {
        $stmt->close();
        /*Devolvemos el JSON con los datos de la consulta*/
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata, JSON_FORCE_OBJECT);
    }
}

/**
 * Inserta los valores de ByN por solicitud.
 *
 * @param int $status    The position of the token in the token stack
 * @param int $solicitud The position of the token in the token stack
 *
 * @return json
 */
function cerrarSolicitud($status, $solicitud)
{
    global $mysqlCon, $sentenciaStatus6Solicitud;
    $jsondata = array();
    $parametros = "status - " . $status . " solicitud - " . $solicitud;
    $path_parts = pathinfo(__FILE__);
    
    try{
        if ($stmt = $mysqlCon->prepare($sentenciaStatus6Solicitud)) {
        
            $stmt->bind_param('ii', $status, $solicitud);
            
            if (!$stmt->execute()) {
                $jsondata["success"] = false;
                $jsondata["errorMessage"] = "Errormessage: ". $mysqlCon->error;

                crearLog($path_parts['filename'], __FUNCTION__, $parametros, $mysqlCon->error, "error");

            } else {
                $jsondata["success"] = true;
                crearLog($path_parts['filename'], __FUNCTION__, $parametros, "Solicitud Cerrada", "correcto");
            }
            
        } else {
            $jsondata["success"] = false;
            $jsondata["errorMessage"] = "Errormessage: ". $mysqlCon->error;
            crearLog($path_parts['filename'], __FUNCTION__, $parametros, $mysqlCon->error, "error");
        }
    } 
    catch(Exception $e) {
        $jsondata["success"] = false;
        $jsondata["errorMessage"] = "Errormessage: ". $e;
        crearLog($path_parts['filename'], __FUNCTION__, $parametros, $e, "error");
    } finally {
        $stmt->close();
        /*Devolvemos el JSON con los datos de la consulta*/
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata, JSON_FORCE_OBJECT);
    }
}