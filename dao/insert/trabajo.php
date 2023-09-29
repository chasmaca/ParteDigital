<?php

/**
 * Class: trabajo.php
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
require_once '../select/query.php';
require_once 'inserciones.php';
require_once 'insertLog.php';

$solicitud = '';
$departamentoId = '';
$subdepartamentoId = '';
$ceco = '';
$treinta = '';
$trabajo = '1';
$fechaActual = date("d/m/Y");
$path_parts = pathinfo(__FILE__);

//Recogemos los valores
if (isset($_POST['solicitud'])) {
    $solicitud = utf8_decode(trim($_POST['solicitud']));
}

if (isset($_POST['departamento'])) {
    $departamentoId = utf8_decode(trim($_POST['departamento']));
}

if (isset($_POST['subdepartamento'])) {
    $subdepartamentoId = utf8_decode(trim($_POST['subdepartamento']));
}

if (isset($_POST['ceco'])) {
    $ceco = utf8_decode(trim($_POST['ceco']));
}

if (isset($_POST['treinta'])) {
    $treinta = utf8_decode(trim($_POST['treinta']));
}

insertTrabajo($trabajo, $solicitud, $fechaActual, $ceco, $treinta, $departamentoId, $subdepartamentoId, $path_parts);

/**
 * Inserta los valores del trabajo.
 *
 * @param int    $trabajo           The position of the token in the token stack
 * @param int    $solicitud         The number of spaces to adjust the indent by
 * @param string $fechaActual       The position of the token in the token stack
 * @param int    $ceco              The number of spaces to adjust the indent by
 * @param int    $treinta           The position of the token in the token stack
 * @param int    $departamentoId    The number of spaces to adjust the indent by
 * @param int    $subdepartamentoId The number of spaces to adjust the indent by
 * @param array  $path_parts        The number of spaces to adjust the indent by
 *
 * @return json
 */
function insertTrabajo($trabajo, $solicitud, $fechaActual, $ceco, $treinta, $departamentoId, $subdepartamentoId, $path_parts) 
{
    global $guardaTrabajo, $mysqlCon;

    $parametros = "Query: " . $guardaTrabajo . 
    " trabajo:" . $trabajo . 
    " solicitud:" . $solicitud . 
    " fechaActual:" . $fechaActual . 
    " ceco:" . $ceco . 
    " treinta:" . $treinta . 
    " solicitud:" . $solicitud . 
    " departamentoId:" . $departamentoId . 
    " subdepartamentoId:" . $subdepartamentoId;

    /*definimos el json*/
    $jsondata = array();
    $jsondata["data"] = array();
    try {
        if ($stmt = $mysqlCon->prepare($guardaTrabajo)) {
            $stmt->bind_param('iisssiii', $trabajo, $solicitud, $fechaActual, $ceco, $treinta, $solicitud, $departamentoId, $subdepartamentoId);
            if (!$stmt->execute()) {
                $jsondata["success"] = false;
                $jsondata["message"] =  "Trabajo NO Insertado. " . $guardaTrabajo .
                                        " Parametros: " . $trabajo . "--" . $solicitud . "--" .
                                                            $fechaActual . "--" . $ceco . "--" .
                                                            $treinta . "--" . $solicitud . "--" .
                                                            $departamentoId . "--" . $subdepartamentoId;
                crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:' . $mysqlCon->error, "error");
            } else {
                $jsondata["success"] = true;
                $jsondata["message"] = "Trabajo Insertado.";
                crearLog($path_parts['filename'], __FUNCTION__, $parametros, "Trabajo Insertado.", "correcto");
            }
        } else {
            $jsondata["success"] = false;
            $jsondata["message"] = "Error Insertando Trabajo " . $mysqlCon->error;
            crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:' . $mysqlCon->error, "error");
        }
    } catch (Exception $e) {
        $jsondata["success"] = false;
        $jsondata["message"] = "Error Insertando Trabajo " . $e;
        crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:' . $e, "error");
        
    } finally {
        $stmt->close();
        /*Devolvemos el JSON con los datos de la consulta*/
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata, JSON_FORCE_OBJECT);
    }

}

