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
require_once "./../insert/insertLog.php";


$solicitud = "";
$color = 0;
$negro = 0;
$espiral = 0;
$encolado = 0;
$varios1 = 0;
$varios2 = 0;
$varios = 0;
$encuadernacion = 0;


//Recogemos los valores
if (isset($_POST['solicitud'])) {
    $solicitud = utf8_decode(trim($_POST['solicitud']));
}

if (isset($_POST['color'])) {
    $color = utf8_decode(trim($_POST['color']));
}

if (isset($_POST['negro'])) {
    $negro = utf8_decode(trim($_POST['negro']));
}

if (isset($_POST['espiral'])) {
    $espiral = utf8_decode(trim($_POST['espiral']));
}

if (isset($_POST['encolado'])) {
    $encolado = utf8_decode(trim($_POST['encolado']));
}

if (isset($_POST['varios1'])) {
    $varios1 = utf8_decode(trim($_POST['varios1']));
}


if (isset($_POST['varios2'])) {
    $varios2 = utf8_decode(trim($_POST['varios2']));
}

if (isset($_POST['varios'])) {
    $varios = utf8_decode(trim($_POST['varios']));
}

if (isset($_POST['encuadernacion'])) {
    $encuadernacion = utf8_decode(trim($_POST['encuadernacion']));
}

modificacionSubtotalesFunction($negro, $color, $encolado, $encuadernacion, $espiral, $varios, $varios1, $varios2, $solicitud);

/**
 * Inserta los valores de ByN por solicitud.
 *
 * @param double $negro          The position of the token in the token stack
 * @param double $color          The position of the token in the token stack
 * @param double $encolado       The position of the token in the token stack
 * @param double $encuadernacion The position of the token in the token stack
 * @param double $espiral        The position of the token in the token stack
 * @param double $varios         The position of the token in the token stack
 * @param double $varios1        The position of the token in the token stack
 * @param double $varios2        The position of the token in the token stack
 * @param int    $solicitud      The position of the token in the token stack
 *
 * @return json
 */
function modificacionSubtotalesFunction($negro, $color, $encolado, $encuadernacion, $espiral, $varios, $varios1, $varios2, $solicitud)
{
    global $mysqlCon, $updateSubtotal;

    /*definimos el json*/
    $jsondata = array();
    $jsondata["data"] = array();
    $path_parts = pathinfo(__FILE__);
    $parametros = "Query:" . $updateSubtotal . 
    " negro:" . $negro . 
    " color:" . $color . 
    " encolado:" . $encolado . 
    " encuadernacion:" . $encuadernacion . 
    " espiral:" . $espiral . 
    " varios:" . $varios . 
    " varios1:" . $varios1 . 
    " varios2:" . $varios2 . 
    " solicitud:" . $solicitud;

    try {
        if ($solicitud === "") {
            $jsondata["success"] = false;
            $jsondata["message"] = "Faltan por enviar Parámetros";
            crearLog($path_parts['filename'], __FUNCTION__, $parametros, "Faltan por enviar Parámetros", "error");
        } else {
            if ($stmt = $mysqlCon->prepare($updateSubtotal)) {
    
                $stmt->bind_param('ddddddddi', $negro, $color, $encolado, $encuadernacion, $espiral, $varios, $varios1, $varios2, $solicitud);
                if (!$stmt->execute()) {
                    $jsondata["success"] = false;
                    $jsondata["message"] = "Error Actualizando Subtotales" . $mysqlCon->error;
                    crearLog($path_parts['filename'], __FUNCTION__, $parametros, "Error Actualizando Subtotales :" . $mysqlCon->error, "error");
                } else {
                    $jsondata["success"] = true;
                    $jsondata["message"] = "Subtotales Actualizados";
                    crearLog($path_parts['filename'], __FUNCTION__, $parametros, "Subtotales Actualizados", "correcto");
                }
    
            } else {
                $jsondata["success"] = false;
                $jsondata["message"] = "Error Actualizando Subtotales" . $mysqlCon->error;
                crearLog($path_parts['filename'], __FUNCTION__, $parametros, "Error Actualizando Subtotales :" . $mysqlCon->error, "error");
            }
        }
        
    } catch (Exception $e) {
        $jsondata["success"] = false;
        $jsondata["message"] = "Error Actualizando Subtotales" . $e;
        crearLog($path_parts['filename'], __FUNCTION__, $parametros, "Error Actualizando Subtotales :" . $e, "error");
    } finally {
        $stmt->close();
        /*Devolvemos el JSON con los datos de la consulta*/
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata, JSON_FORCE_OBJECT);
    }

}

?>