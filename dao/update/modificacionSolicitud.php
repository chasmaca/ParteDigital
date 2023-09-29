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
require_once "./../select/query.php";
require_once "./../delete/borrado.php";
require_once "./../insert/inserciones.php";
require_once "./../insert/insertLog.php";


$queryModificacionSolicitud = "UPDATE solicitud SET departamento_id = ?, subdepartamento_id = ? WHERE solicitud_id = ?";
$queryModificacionTrabajo = "UPDATE trabajo SET departamento_id = ?, subdepartamento_id = ? WHERE solicitud_id = ? AND trabajo_id = 1";

$solicitud = (isset($_POST['solicitud'])) ? $_POST['solicitud'] : '';
$departamento = (isset($_POST['departamento'])) ? $_POST['departamento'] : '';
$subdepartamento = (isset($_POST['subdepartamento'])) ? $_POST['subdepartamento'] : '';

/*definimos el json*/
$jsondata = array();
$jsondata["data"] = array();

if ($solicitud === '' || $departamento === '' || $subdepartamento === '') {
    $jsondata["success"] = false;
    $jsondata["message"] = "Faltan Parámetros";
    /*Devolvemos el JSON con los datos de la consulta*/
    header('Content-type: application/json; charset=utf-8');
    echo json_encode($jsondata, JSON_FORCE_OBJECT);
} else {
    modificarDepartamentoSolicitud($solicitud, $departamento, $subdepartamento);
}

/**
 * Inserta los valores de ByN por solicitud.
 *
 * @param int $solicitud       The position of the token in the token stack
 * @param int $departamento    The position of the token in the token stack
 * @param int $subdepartamento The position of the token in the token stack
 *
 * @return json
 */
function modificarDepartamentoSolicitud($solicitud, $departamento, $subdepartamento)
{
    global $mysqlCon, $queryModificacionSolicitud;
    $jsondata = array();
    $jsondata["data"] = array();
    $path_parts = pathinfo(__FILE__);
    $parametros = "Query:" . $queryModificacionSolicitud . 
    " departamento:" . $departamento . 
    " subdepartamento:" . $subdepartamento . 
    " solicitud:" . $solicitud;

    try {
        if ($stmt = $mysqlCon->prepare($queryModificacionSolicitud)) {
            $stmt->bind_param('iii', $departamento, $subdepartamento, $solicitud);
            if (!$stmt->execute()) {
                $jsondata["success"] = false;
                $jsondata["message"] = "Problemas en la consulta " . $queryModificacionSolicitud;
                crearLog($path_parts['filename'], __FUNCTION__, $parametros, "Error Actualizando departamento :" . $mysqlCon->error, "error");
            } else {
                $jsondata["success"] = true;
                $jsondata["message"] = "Actualización Realizada con éxito ";
                crearLog($path_parts['filename'], __FUNCTION__, $parametros, "Se ha actualizado el departamento", "correcto");
            }
        } else {
            $jsondata["success"] = false;
            $jsondata["message"] = "Problemas en la consulta " . $queryModificacionSolicitud;
            crearLog($path_parts['filename'], __FUNCTION__, $parametros, "Error Actualizando departamento :" . $mysqlCon->error, "error");
        }
    } catch (Exception $e) {
        $jsondata["success"] = false;
        $jsondata["message"] = "Problemas en la consulta " . $e->getMessage();
        crearLog($path_parts['filename'], __FUNCTION__, $parametros, "Error Actualizando departamento :" . $e, "error");
    } finally {
        /*Devolvemos el JSON con los datos de la consulta*/
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata, JSON_FORCE_OBJECT);
    }
    
}
?>