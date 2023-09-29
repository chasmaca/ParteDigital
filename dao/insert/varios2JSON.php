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
require_once './../select/query.php';
require_once './../update/updates.php';
require_once './inserciones.php';
require_once './insertLog.php';

$solicitud = $_GET["solicitudId"];
$tipo = $_GET["tipo"];
$descripcion = $_GET["descripcion"];
$unidades = $_GET["unidades"];
$precio = $_GET["precio"];
$total = $_GET["total"];
$path_parts = pathinfo(__FILE__);

if ($solicitud !== "" && $tipo !== "" && $descripcion !== "" && $unidades !== "" && $precio !== "" && $total !== "") {  
    insertamosVarios2Extra($solicitud, $tipo, $descripcion, $unidades, $precio, $total, $path_parts);  
}

/**
 * Inserta los valores de ByN por solicitud.
 *
 * @param int    $solicitud   The position of the token in the token stack
 * @param int    $tipo        The number of spaces to adjust the indent by
 * @param string $descripcion The position of the token in the token stack
 * @param int    $unidades    The number of spaces to adjust the indent by
 * @param double $precio      The position of the token in the token stack
 * @param double $total       The number of spaces to adjust the indent by
 * @param array  $path_parts  The number of spaces to adjust the indent by
 *
 * @return json
 */
function insertamosVarios2Extra($solicitud, $tipo, $descripcion, $unidades, $precio, $total, $path_parts) 
{
    $jsondata = array();
    $trabajo_id = 1;
    $operacionVarios2 = true;
    $operacionVarios2Trabajo = true;
    $lineas = 0;
    $parametros = "solicitud: " . $solicitud . 
    " tipo:" . $tipo . 
    " descripcion:" . $descripcion . 
    " unidades:" . $unidades . 
    " precio:" . $precio . 
    " total:" . $total;
    try {
        //$lineasTrabajo = 0;
        $idMaxVarios2 = recuperamosMaxVarios2Extra($path_parts);    

        $operacionVarios2 = insertamosVarios2($idMaxVarios2, $tipo, $descripcion, $precio, $path_parts);  
        
        $lineasTrabajo = comprobamosOperacionTrabajoVarios2($solicitud, $descripcion, $precio, $lineas, $path_parts);  

        if ($lineasTrabajo == 0) {
            $operacionVarios2Trabajo = insertamosVarios2DetalleTrabajo($trabajo_id, $tipo, $idMaxVarios2, $unidades, $solicitud, $total, $path_parts);  
        } else {
            $operacionVarios2Trabajo = actualizamosVarios2DetalleTrabajo($trabajo_id, $tipo, $lineasTrabajo, $unidades, $solicitud, $total, $path_parts);  
        }

        if ($operacionVarios2 && $operacionVarios2Trabajo) {
            $jsondata["success"] = true;
            $jsondata["errorMessage"] = "Se ha realizado de forma correcta la insercion de Varios2";  
            $jsondata["message"] = "Se ha realizado de forma correcta la insercion de Varios2";  
        } else {
            $jsondata["success"] = false;
            $jsondata["errorMessage"] = "Ha habido un problema en la insercion de valores de Varios2, actualiza la pagina.";  
            $jsondata["message"] = "Ha habido un problema en la insercion de valores de Varios2, actualiza la pagina."; 
        }
    } catch (Exception $e) {
        $jsondata["success"] = false;
        $jsondata["message"] = "Error en Varios 2 Extra " . $e;
        crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:' . $e, "error");
    } finally {
        /*Devolvemos el JSON con los datos de la consulta*/
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata, JSON_FORCE_OBJECT);
    }
}

/**
 * Inserta los valores de ByN por solicitud.
 *
 * @param array $path_parts The number of spaces to adjust the indent by
 * 
 * @return int
 */
function recuperamosMaxVarios2Extra($path_parts)
{
    global $mysqlCon, $recuperaMaxDetalle;
    $valor = 1;
    $tipo = 7;
    $parametros = "Query:" . $recuperaMaxDetalle . " tipo" . $tipo;
    try {
        if ($stmt = $mysqlCon->prepare($recuperaMaxDetalle)) {
            $stmt->bind_param("i", $tipo);
            /*Ejecucion*/
            if ($stmt->execute()) {
                /*Almacenamos el resultSet*/
                $stmt->bind_result($detalle);
                /*Incluimos las lineas de la consulta en el json a devolver*/
                while ($stmt->fetch()) {
                    $valor = utf8_encode($detalle);
                }
            } else {
                crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:' . $mysqlCon->error, "error");  
            }
        } else {
            crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:' . $mysqlCon->error, "error");  
        }
    } catch (Exception $e) {
        $jsondata["success"] = false;
        $jsondata["message"] = "Error recuperando recuperamosMaxVarios2Extra " . $e;
        crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:' . $e, "error");   
    } finally {
        return $valor;
    }
}

/**
 * Inserta los valores de ByN por solicitud.
 *
 * @param int    $idMaxVarios2 The position of the token in the token stack
 * @param int    $tipo         The number of spaces to adjust the indent by
 * @param string $descripcion  The position of the token in the token stack
 * @param double $precio       The position of the token in the token stack
 * @param array  $path_parts   The number of spaces to adjust the indent by
 *
 * @return boolean
 */
function insertamosVarios2($idMaxVarios2, $tipo, $descripcion, $precio, $path_parts)
{
    global $mysqlCon;
    global $saveVarios2ExtraJSON;
    $operacion = false;

    $parametros = "Query:" . $saveVarios2ExtraJSON . " Id:" . $idMaxVarios2 . " tipo:" . $tipo . " descripcion:" . $descripcion . " precio:" . $precio;  
    try {
        if ($stmt = $mysqlCon->prepare($saveVarios2ExtraJSON)) {
            $stmt->bind_param('iisd', $idMaxVarios2, $tipo, $descripcion, $precio);
            if ($stmt->execute()) {
                $operacion = true;
                crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Se ha insertado Correctamente en Varios 2', "correcto");   
            } else {
                $operacion = false;
                crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:' . $mysqlCon->error, "error");   
            }
        } else {
            $operacion = false;
            crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:' . $mysqlCon->error, "error");   
        }
    } catch (Exception $e) {
        $operacion = false;
        crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:' . $e, "error");   
    } finally {
        return $operacion;
    }
}

/**
 * Inserta los valores de ByN por solicitud.
 *
 * @param int    $trabajo_id   The position of the token in the token stack
 * @param int    $tipo         The number of spaces to adjust the indent by
 * @param int    $idMaxVarios2 The position of the token in the token stack
 * @param int    $unidades     The position of the token in the token stack
 * @param int    $solicitud    The position of the token in the token stack
 * @param double $total        The position of the token in the token stack
 * @param array  $path_parts   The number of spaces to adjust the indent by
 *
 * @return boolean
 */
function insertamosVarios2DetalleTrabajo($trabajo_id, $tipo, $idMaxVarios2, $unidades, $solicitud, $total, $path_parts)   
{
    global $mysqlCon;
    global $saveVarios2ExtraTrabajoJSON;
    $operacion = true;

    $parametros = "Query:" . $saveVarios2ExtraTrabajoJSON . 
    " trabajo_id:" . $trabajo_id . 
    " tipo:" . $tipo . 
    " idMaxVarios2:" . $idMaxVarios2 . 
    " unidades:" . $unidades . 
    " solicitud:" . $solicitud . 
    " total:" . $total;
    try {
        if ($stmt = $mysqlCon->prepare($saveVarios2ExtraTrabajoJSON)) {
            $stmt->bind_param('iiiiid', $trabajo_id, $tipo, $idMaxVarios2, $unidades, $solicitud, $total);   
            if ($stmt->execute()) {
                $operacion = true;
                crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Se ha insertado Correctamente en detalleTrabajo', "correcto");   
            } else {
                $operacion = false;
                crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:' . $mysqlCon->error, "error");   
            }
        } else {
            $operacion = false;
            crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:' . $mysqlCon->error, "error");
        }
    } catch (Exception $e) {
        $operacion = false;
        crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:' . $e, "error");   
    } finally {

        return $operacion;
    }
}

/**
 * Inserta los valores de ByN por solicitud.
 *
 * @param int    $trabajo_id The position of the token in the token stack
 * @param int    $tipo       The number of spaces to adjust the indent by
 * @param int    $detalle    The position of the token in the token stack
 * @param int    $unidades   The position of the token in the token stack
 * @param int    $solicitud  The position of the token in the token stack
 * @param double $total      The position of the token in the token stack
 * @param array  $path_parts The number of spaces to adjust the indent by
 *
 * @return boolean
 */
function actualizamosVarios2DetalleTrabajo($trabajo_id, $tipo, $detalle, $unidades, $solicitud, $total, $path_parts)   
{
    global $mysqlCon;
    global $updateVarios2ExtraTrabajoJSON;
    $operacion = true;

    $parametros = "Query:" . $updateVarios2ExtraTrabajoJSON .
    " unidades:" . $unidades .
    " total:" . $total .
    " solicitud:" . $solicitud .
    " detalle:" . $detalle;
    try {
        if ($stmt = $mysqlCon->prepare($updateVarios2ExtraTrabajoJSON)) {
            $stmt->bind_param('idii', $unidades, $total, $solicitud, $detalle);
            if ($stmt->execute()) {
                $operacion = true;
                crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Se ha actualizado Correctamente en detalleTrabajo', "correcto");   
            } else {
                $operacion = false;
                crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:' . $mysqlCon->error, "error");   
            }
        } else {
            $operacion = false;
            crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:' . $mysqlCon->error, "error");
        }
    } catch (Exception $e) {
        $operacion = false;
        crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:' . $e, "error");   
    } finally {
        return $operacion;
    }
}

/**
 * Inserta los valores de ByN por solicitud.
 *
 * @param int    $solicitud   The position of the token in the token stack
 * @param string $descripcion The position of the token in the token stack
 * @param double $precio      The number of spaces to adjust the indent by
 * @param int    $detalle     The position of the token in the token stack
 * @param array  $path_parts  The number of spaces to adjust the indent by
 *
 * @return int
 */
function comprobamosOperacionTrabajoVarios2($solicitud, $descripcion, $precio, $detalle, $path_parts)   
{
    global $mysqlCon, $comprobarVarios2TrabajoExtraJSON;
    $trabajo_id = $tipo_id = $detalle_id = $unidades = $fecha_cierre = $solicitud_id = $preciototal = "";
    $resultado = 0;
    
    $parametros = "Query:" . $comprobarVarios2TrabajoExtraJSON . 
    " trabajo_id:" . $trabajo_id . 
    " tipo_id:" . $tipo_id . 
    " detalle_id:" . $detalle_id . 
    " unidades:" . $unidades . 
    " fecha_cierre:" . $fecha_cierre . 
    " solicitud_id:" . $solicitud_id . 
    " preciototal:" . $preciototal;
    try {
        if ($stmt = $mysqlCon->prepare($comprobarVarios2TrabajoExtraJSON)) {
            $stmt->bind_param('ii', $solicitud, $detalle);
            if ($stmt->execute()) {
                $stmt->bind_result($trabajo_id, $tipo_id, $detalle_id, $unidades, $fecha_cierre, $solicitud_id, $preciototal);   
                while ($stmt->fetch()) {
                    $resultado = $detalle_id;
                }
            } else {
                crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:' . $mysqlCon->error, "error");   
            }
        } else {
            crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:' . $mysqlCon->error, "error");
        }
    } catch (Exception $e) {
        crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:' . $e, "error");   
    } finally {
        return $resultado;
    }
}
?>