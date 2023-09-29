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

$solicitud = $_GET["solicitudId"];
$varios1 = $_GET["varios1"];
$varios2 = $_GET["varios2"];
$color = $_GET["color"];
$blancoYNegro = $_GET["byn"];
$espiral = $_GET["espiral"];
$encolado = $_GET["encolado"];


actualizarSubtotales($solicitud, $varios1, $varios2, $color, $blancoYNegro, $espiral, $encolado);

/**
 * Inserta los valores de ByN por solicitud.
 *
 * @param int    $solicitud    The position of the token in the token stack
 * @param double $varios1      The number of spaces to adjust the indent by
 * @param double $varios2      The position of the token in the token stack
 * @param double $color        The number of spaces to adjust the indent by
 * @param double $blancoYNegro The position of the token in the token stack
 * @param double $espiral      The position of the token in the token stack
 * @param double $encolado     The position of the token in the token stack
 *
 * @return json
 */
function actualizarSubtotales($solicitud, $varios1, $varios2, $color, $blancoYNegro, $espiral, $encolado)
{
    global $mysqlCon, $sentenciaActualizaSubTotales;
    $jsondata = array();
    $parametros = "Query:" . $sentenciaActualizaSubTotales . 
    " solicitud:" . $solicitud . 
    " varios1:" . $varios1 . 
    " varios2:" . $varios2 . 
    " color:" . $color . 
    " blancoYNegro:" . $blancoYNegro . 
    " espiral:" . $espiral . 
    " encolado:" . $encolado;

    $path_parts = pathinfo(__FILE__);

    try {
        $precioVarios = $varios1 + $varios2;
        $precioEncuadenaciones = $espiral + $encolado;
        if ($stmt = $mysqlCon->prepare($sentenciaActualizaSubTotales)) {
            $stmt->bind_param('ddddddddi', $precioVarios, $varios1, $varios2, $color, $blancoYNegro, $precioEncuadenaciones, $espiral, $encolado, $solicitud);
            if ($stmt->execute()) {
                $jsondata["success"] = true;
                $jsondata["message"] = "Actualizacion realizada con exito.";
                crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Subtotales calculados correctamente', "correcto");
            } else {
                $jsondata["success"] = false;
                $jsondata["message"] = "Ha habido un problema con el calculo de subtotales, por favor recargue la pagina.";
                crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Solicitud validada con error:' . $mysqlCon->error, "error");
            }
        } else {
            $jsondata["success"] = false;
            $jsondata["errorMessage"] = "Errormessage: ". $mysqlCon->error;
            $jsondata["message"] = "Ha habido un problema con el calculo de subtotales, por favor recargue la pagina.";
            crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Solicitud validada con error:' . $mysqlCon->error, "error");
        }
    } catch (Exception $e) {
        $jsondata["success"] = false;
        $jsondata["errorMessage"] = "Errormessage: ". $e;
        crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Solicitud validada con error:' . $e, "error");
    } finally {
        $stmt->close();
        /*Devolvemos el JSON con los datos de la consulta*/
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata, JSON_FORCE_OBJECT);
    }
    
}
?>