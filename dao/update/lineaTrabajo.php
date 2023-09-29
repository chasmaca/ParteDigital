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

/*definimos el json*/
$trabajo = 1;
$solicitud = "";
$tipo = "";
$detalle = "";
$unidades = "";
$total = "";
$fecha = "";

//Recogemos los valores
if (isset($_POST['solicitud'])) {
    $solicitud = $_POST['solicitud'];
}

if (isset($_POST['tipo'])) {
    $tipo = $_POST['tipo'];
}

if (isset($_POST['detalle'])) {
    $detalle = $_POST['detalle'];
}

if (isset($_POST['unidades'])) {
    $unidades = $_POST['unidades'];
}

if (isset($_POST['total'])) {
    $total = $_POST['total'];
}

lineaTrabajoFunction($unidades, $total, $trabajo, $solicitud, $tipo, $detalle);

/**
 * Inserta los valores de ByN por solicitud.
 *
 * @param int    $unidades  The position of the token in the token stack
 * @param double $total     The position of the token in the token stack
 * @param int    $trabajo   The position of the token in the token stack
 * @param int    $solicitud The position of the token in the token stack
 * @param int    $tipo      The position of the token in the token stack
 * @param int    $detalle   The position of the token in the token stack
 *
 * @return json
 */
function lineaTrabajoFunction($unidades, $total, $trabajo, $solicitud, $tipo, $detalle)
{
    global $updateLinea, $mysqlCon;
    /*definimos el json*/
    $jsondata = array();
    $jsondata["data"] = array();
    $path_parts = pathinfo(__FILE__);
    $parametros = "Query:" . $updateLinea . 
    " unidades:" . $unidades . 
    " total:" . $total . 
    " trabajo:" . $trabajo . 
    " solicitud:" . $solicitud . 
    " tipo:" . $tipo . 
    " detalle:" . $detalle;

    try {
        if ($stmt = $mysqlCon->prepare($updateLinea)) {
            $stmt->bind_param('idiiii', $unidades, $total, $trabajo, $solicitud, $tipo, $detalle);
            if (!$stmt->execute()) {
                $jsondata["success"] = false;
                $jsondata["message"] =  "Linea NO Actualizada. " . $updateLinea .
                                        " Parametros: " . $unidades . "--" . $total . "--" .
                                                          $trabajo . "--" . $solicitud . "--" .
                                                          $tipo . "--" . $detalle;
                crearLog($path_parts['filename'], __FUNCTION__, $parametros, "Linea NO Actualizada:" . $mysqlCon->error, "error");
            } else {
                $jsondata["success"] = true;
                $jsondata["message"] = "Linea Actualizada."; 
                $jsondata["message"] =  "Linea Actualizada. " . $updateLinea .
                                        " Parametros: " . $unidades . "--" . $total . "--" .
                                                          $trabajo . "--" . $solicitud . "--" .
                                                          $tipo . "--" . $detalle;
                crearLog($path_parts['filename'], __FUNCTION__, $parametros, "Linea Actualizada", "correcto");
            }
        } else {
            $jsondata["success"] = false;
            $jsondata["message"] = "Error Actualizando Linea " . $mysqlCon->error;
            crearLog($path_parts['filename'], __FUNCTION__, $parametros, "Linea NO Actualizada:" . $mysqlCon->error, "error");
        }
    } catch (Exception $e) {
        $jsondata["success"] = false;
        $jsondata["message"] = "Error Actualizando Linea " . $e;
        crearLog($path_parts['filename'], __FUNCTION__, $parametros, "Linea NO Actualizada:" . $e, "error");
    } finally {
        $stmt->close();
        /*Devolvemos el JSON con los datos de la consulta*/
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata, JSON_FORCE_OBJECT);
    }
}



