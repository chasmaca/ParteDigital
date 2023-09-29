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

cerrarSolicitud();

/**
 * Inserta los valores de ByN por solicitud.
 *
 * @return json
 */
function cerrarSolicitud() 
{
    /*Declaramos como global la conexion y la query y el id de validador*/
    global $mysqlCon, $sentenciaCierreSolicitudMes;
    /*definimos el json*/
    $jsondata = array();
    $jsondata["data"] = array();
    $path_parts = pathinfo(__FILE__);
    $parametros = "NO-DATA";
    try {
        $periodo = $_POST["periodo"];
        $periodo = explode("/", $periodo);
        $fecha_cierre = $periodo[1]."-".$periodo[0]."-15";
        $parametros = "Query:" . $sentenciaCierreSolicitudMes . " fecha:" .$fecha_cierre;

        if ($stmt = $mysqlCon->prepare($sentenciaCierreSolicitudMes)) {
            
            $stmt->bind_param('s', $fecha_cierre);
            
            if ($stmt->execute()) {
                $jsondata["success"] = true;
                $jsondata["errorMessage"] = "Se han cerrado los partes.";
                crearLog($path_parts['filename'], __FUNCTION__, $parametros, "Se han cerrado los partes.", "correcto");
            } else {
                $jsondata["success"] = false;
                $jsondata["errorMessage"] = "Problemas al cerrar los partes.";

                crearLog($path_parts['filename'], __FUNCTION__, $parametros, $mysqlCon->error, "error");
            }
        } else {
            $jsondata["success"] = false;
            $jsondata["errorMessage"] = "Problemas al cerrar los partes.";
            crearLog($path_parts['filename'], __FUNCTION__, $parametros, $mysqlCon->error, "error");
        }
    } catch (Exception $e) {
        crearLog($path_parts['filename'], __FUNCTION__, $parametros, $e, "error");
    } finally {
        /*Devolvemos el JSON con los datos de la consulta*/
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata, JSON_FORCE_OBJECT);
    }

}

?>