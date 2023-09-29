<?php

/**
 * Class: trabajo-old.php
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
require_once "../select/query.php";
require_once "../update/updates.php";
require_once "inserciones.php";
require_once "insertLog.php";

global $mysqlCon, $consultaDetalleJSON;

$path_parts = pathinfo(__FILE__);

$solicitud = $_GET["solicitudId"];
$tipo = $_GET["tipo"];
$detalle = $_GET["detalle"];
$unidades = $_GET["unidades"];
$total = $_GET["total"];

if ($stmt = $mysqlCon->prepare($consultaDetalleJSON)) {
    $trabajo = 1;
    $stmt->bind_param('iiii', $trabajo, $tipo, $detalle, $solicitud);
    
    $stmt->execute();
    $stmt->store_result();
    $row_cnt = $stmt->num_rows;

    if ($row_cnt > 0) {
        actualizaTrabajoDetalleColor($solicitud, $tipo, $detalle, $unidades, $total, $path_parts);
    } else {
        insertarTrabajoDetalleColor($solicitud, $tipo, $detalle, $unidades, $total, $path_parts);
    }
}

/**
 * Inserta los valores del trabajo.
 *
 * @param int    $solicitud  The position of the token in the token stack
 * @param int    $tipo       The number of spaces to adjust the indent by
 * @param string $detalle    The position of the token in the token stack
 * @param int    $unidades   The number of spaces to adjust the indent by
 * @param double $total      The number of spaces to adjust the indent by
 * @param array  $path_parts The number of spaces to adjust the indent by
 *
 * @return json
 */
function insertarTrabajoDetalleColor($solicitud,$tipo,$detalle,$unidades,$total, $path_parts)
{
    global $sentenciaInsertDetalleJSON, $mysqlCon;
    /*definimos el json*/
    $jsondata = array();
    $trabajo = 1;

    $parametros = "Query: " . $sentenciaInsertDetalleJSON . 
    " trabajo:" . $trabajo . 
    " tipo:" . $tipo . 
    " detalle:" . $detalle . 
    " unidades:" . $unidades . 
    " solicitud:" . $solicitud . 
    " total:" . $total;

    try {
        if ($stmt = $mysqlCon->prepare($sentenciaInsertDetalleJSON)) {
            $stmt->bind_param('iiiiid', $trabajo, $tipo, $detalle, $unidades, $solicitud, $total);
            if ($stmt->execute()) {
                $jsondata["success"] = true;
                $jsondata["errorMessage"] = "Se insertado la linea de forma correcta.";
            } else {
                $jsondata["success"] = false;
                $jsondata["errorMessage"] = "Ha habido un problema, por favor, recargue la pagina.";
                crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:' . $mysqlCon->error, "error");
            }
        } else {
            $jsondata["success"] = false;
            $jsondata["errorMessage"] = "Errormessage: ". $mysqlCon->error;
            crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:' . $mysqlCon->error, "error");
        }
    } catch (Exception $e) {
        crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:' . $e, "error");
    } finally {
        $stmt->close();
        /*Devolvemos el JSON con los datos de la consulta*/
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata, JSON_FORCE_OBJECT);
    
    }
}

/**
 * Inserta los valores del trabajo.
 *
 * @param int    $solicitud  The position of the token in the token stack
 * @param int    $tipo       The number of spaces to adjust the indent by
 * @param string $detalle    The position of the token in the token stack
 * @param int    $unidades   The number of spaces to adjust the indent by
 * @param double $total      The number of spaces to adjust the indent by
 * @param array  $path_parts The number of spaces to adjust the indent by
 *
 * @return json
 */
function actualizaTrabajoDetalleColor($solicitud,$tipo,$detalle,$unidades,$total, $path_parts)
{
    global $sentenciaUpdateDetalleJSON, $mysqlCon;
    $jsondata = array();
    $trabajo = 1;

    $parametros = "Query:" . $sentenciaUpdateDetalleJSON . 
    " unidades:" . $unidades .
    " total:" . $total .
    " trabajo:" . $trabajo .
    " tipo:" . $tipo .
    " detalle:" . $detalle .
    " solicitud:" . $solicitud;
    
    try {
        if ($stmt = $mysqlCon->prepare($sentenciaUpdateDetalleJSON)) {
            $stmt->bind_param('idiiii', $unidades, $total, $trabajo, $tipo, $detalle, $solicitud);
            if ($stmt->execute()) {
                $jsondata["success"] = true;
                $jsondata["errorMessage"] = "Se insertado la linea de forma correcta.";
            } else {
                $jsondata["success"] = false;
                $jsondata["errorMessage"] = "Ha habido un problema, por favor, recargue la pagina.";
                crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:' . $mysqlCon->error, "error");
            }
        } else {
            $jsondata["success"] = false;
            $jsondata["errorMessage"] = "Errormessage: ". $mysqlCon->error;
            crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:' . $mysqlCon->error, "error");
        }
    } catch (Exception $e) {
        crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:' . $e, "error");
    } finally {
        $stmt->close();
        /*Devolvemos el JSON con los datos de la consulta*/
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata, JSON_FORCE_OBJECT);
    }
    
}
?>