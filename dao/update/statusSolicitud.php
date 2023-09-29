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
require_once './../update/updates.php';
require_once './../insert/insertLog.php';

$solicitud = "";
$operacion = "";
$motivo = "";

//Recogemos los valores
if (isset($_POST['idSolicitud'])) {
    $solicitud = $_POST['idSolicitud'];
}

if (isset($_POST['operacion'])) {
    $operacion = $_POST["operacion"];
}

if (isset($_POST['motivo'])) {
    $motivo = $_POST["motivo"];
}

if ($operacion === '2') {
    aprobarSolicitud($solicitud, $operacion);
} else {
    rechazarSolicitud($solicitud, $operacion, $motivo);
}

/**
 * Inserta los valores de ByN por solicitud.
 *
 * @param int $solicitud The position of the token in the token stack
 * @param int $operacion The number of spaces to adjust the indent by
 *
 * @return json
 */
function aprobarSolicitud($solicitud, $operacion)
{

    global $mysqlCon, $sentenciaUpdateStatusDosSolicitud;

    $jsondata = array();
    $jsondata["data"] = array();
    $parametros = "Query:" . $sentenciaUpdateStatusDosSolicitud . " solicitud:" . $solicitud . " operacion:" . $operacion;
    $path_parts = pathinfo(__FILE__);

    try {
        if ($stmt = $mysqlCon->prepare($sentenciaUpdateStatusDosSolicitud)) {
            $stmt->bind_param('ii', $operacion, $solicitud);
            if ($stmt->execute()) {
                $jsondata["success"] = true;
                $jsondata["message"] = "Se ha aprobado la solicitud";
                crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Solicitud Aprobada', "correcto");
            } else {
                $jsondata["success"] = false;
                $jsondata["message"] = $mysqlCon->error;
                crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Solicitud validada con error:' . $mysqlCon->error, "error");
            }
        } else {
            /*Llegamos aqui con error, asociamos false para identificarlo en el js*/
            $jsondata["success"] = false;
            $jsondata["message"] = $mysqlCon->error;
            crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Solicitud validada con error:' . $mysqlCon->error, "error");
        }
    } catch (Exception $e) {
        $jsondata["success"] = false;
        $jsondata["message"] = $e;
        crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Solicitud validada con error:' . $e, "error");
    } finally {
        $stmt->close();
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata, JSON_FORCE_OBJECT);
    }

}

/**
 * Inserta los valores de ByN por solicitud.
 *
 * @param int    $solicitud  The position of the token in the token stack
 * @param int    $operacion  The number of spaces to adjust the indent by
 * @param string $comentario The position of the token in the token stack
 *
 * @return json
 */
function rechazarSolicitud($solicitud, $operacion, $comentario)
{

    global $mysqlCon, $sentenciaUpdateStatusTresSolicitud;
    $jsondata = array();
    $jsondata["data"] = array();
    $parametros = "Query:" . $sentenciaUpdateStatusTresSolicitud . " solicitud:" . $solicitud . " operacion:" . $operacion . " comentario:" . $comentario;
    $path_parts = pathinfo(__FILE__);

    try {
        if ($stmt = $mysqlCon->prepare($sentenciaUpdateStatusTresSolicitud)) {
            $stmt->bind_param('isi', $operacion, $comentario, $solicitud);
            if ($stmt->execute()) {
                $jsondata["success"] = true;
                $jsondata["message"] = $comentario;
                crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Solicitud Rechazada', "correcto");
            } else {
                $jsondata["success"] = false;
                $jsondata["message"] = $mysqlCon->error;
                crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Solicitud rechazada con error:' . $mysqlCon->error, "error");
            }
            
        } else {
            $jsondata["success"] = false;
            $jsondata["message"] = $mysqlCon->error;
            crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Solicitud rechazada con error:' . $mysqlCon->error, "error");
        }
    } catch (Exception $e) {
        $jsondata["success"] = false;
        $jsondata["message"] = $e;
        crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Solicitud rechazada con error:' . $e, "error");
    } finally {
        $stmt->close();
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata, JSON_FORCE_OBJECT);
    }
}
?>

