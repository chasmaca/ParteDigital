<?php
/**
 * Class: altaArticulo.php
 *
 * Formulario de alta de nuevos articulos de reprografia
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

global $mysqlCon;

/*definimos el json*/
$jsondata = array();
$jsondata["data"] = array();

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
try {
    if ($stmt = $mysqlCon->prepare($updateTrabajo)) {
        $stmt->bind_param('iii', $status, $usuario, $solicitud);
        if (!$stmt->execute()) {
            $jsondata["success"] = false;
            $jsondata["message"] =  "Trabajo NO Actualizado. " . $updateTrabajo .
                                    " Parametros: " . $status . "--" . $solicitud . "--" . $usuario;
        } else {
            $jsondata["success"] = true;
            $jsondata["message"] = "Trabajo Actualizado.";    
        }
        $stmt->close();
    } else {
        $jsondata["success"] = false;
        $jsondata["message"] = "Error Actualizando Trabajo " . $mysqlCon->error;
        $stmt->close();
    }

    if ($jsondata["success"] !== false) {
        if ($stmt = $mysqlCon->prepare($updateSolicitud)) {
            $stmt->bind_param('iii', $status, $usuario, $solicitud);
            if (!$stmt->execute()) {
                $jsondata["success"] = false;
                $jsondata["message"] =  "Solicitud NO Actualizado. " . $updateSolicitud .
                                        " Parametros: " . $status . "--" . $solicitud . "--" . $usuario;
            } else {
                $jsondata["success"] = true;
                $jsondata["message"] = "Solicitud Actualizado.";    
            }
            $stmt->close();
        } else {
            $jsondata["success"] = false;
            $jsondata["message"] = "Error Actualizando Solicitud " . $mysqlCon->error;
            $stmt->close();
        }   
    }
} catch (Exception $e) {
    $jsondata["success"] = false;
    $jsondata["message"] = "Error Actualizando Trabajo " . $e;
    $stmt->close();
}

/*Devolvemos el JSON con los datos de la consulta*/
header('Content-type: application/json; charset=utf-8');
echo json_encode($jsondata, JSON_FORCE_OBJECT);