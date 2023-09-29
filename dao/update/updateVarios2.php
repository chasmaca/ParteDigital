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
require_once './../../utiles/connectDBUtiles.php';
require_once './updates.php';
require_once './../select/query.php';
require_once './../insert/insertLog.php';

$solicitud = "";
$unidades = "";
$precio = "";
$precioTotal = "";
$descripcion = "";

$solicitud = $_GET["solicitud"];
$unidades = $_GET["unidades"];
$precio = $_GET["precio"];
$precioTotal = $_GET["precioTotal"];
$descripcion = $_GET["descripcion"];
$identificador = $_GET["identificador"];

updateVarios2($solicitud, $unidades, $precio, $precioTotal, $descripcion, $identificador);

/**
 * Inserta los valores de ByN por solicitud.
 *
 * @param int    $solicitud     The position of the token in the token stack
 * @param int    $unidades      The number of spaces to adjust the indent by
 * @param double $precio        The position of the token in the token stack
 * @param double $precioTotal   The number of spaces to adjust the indent by
 * @param string $descripcion   The position of the token in the token stack
 * @param int    $identificador The position of the token in the token stack
 *
 * @return json
 */
function updateVarios2($solicitud, $unidades, $precio, $precioTotal, $descripcion, $identificador)
{
    global $mysqlCon, $updateVarios2ExtraTrabajoJSON;
    $jsondata = array();
    $jsondata["success"] = false;
    $path_parts = pathinfo(__FILE__);
    $parametrosUpdate = "Query:" . $updateVarios2ExtraTrabajoJSON . 
                        " parametros: Unidades:" . $unidades . 
                        " precioTotal:" . $precioTotal . 
                        " solicitud:" . $solicitud . 
                        " detalle:" . $identificador; 
    try {

        if ($identificador !== 0) {
            $stmt = $mysqlCon->prepare($updateVarios2ExtraTrabajoJSON);
            $stmt->bind_param('idii', $unidades, $precioTotal, $solicitud, $identificador);
            if ($stmt->execute()) {
                $jsondata["success"] = true;
                $jsondata["errorMessage"] = "Se ha realizado de forma correcta la actualización de Varios2";
                crearLog($path_parts['filename'], __FUNCTION__, $parametrosUpdate, 'Exception:' . $mysqlCon->error, "correcto"); 
            } else {
                $jsondata["success"] = false;
                $jsondata["errorMessage"] = "Problemas en la actualizacion de Varios2";
                crearLog($path_parts['filename'], __FUNCTION__, $parametrosUpdate, 'Exception:' . $mysqlCon->error, "error"); 
            }
        } else {
            $jsondata["errorMessage"] = "Problemas en la actualizacion de Varios2:";
            crearLog($path_parts['filename'], __FUNCTION__, $parametrosUpdate, 'Exception: Identificador a 0', "error"); 
        }
    } catch (Exception $e) {
        $jsondata["success"] = false;
        $jsondata["errorMessage"] = "Problemas en la actualizacion de Varios2:";
        crearLog($path_parts['filename'], __FUNCTION__, $parametrosUpdate, 'Exception:' . $e, "error"); 
    } finally {
        /*Devolvemos el JSON con los datos de la consulta*/
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata, JSON_FORCE_OBJECT);
    }
}
?>