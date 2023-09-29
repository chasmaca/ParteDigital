<?php

/**
 * Class: trabajo-old.php
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
require_once "./../../utiles/connectDBUtiles.php";
require_once "./../select/query.php";
require_once "./../update/updates.php";
require_once "./inserciones.php";
require_once "./insertLog.php";


global $mysqlCon, $consultaTrabajoJSON;

$departamentoId = "";
$treinta = "";
$path_parts = pathinfo(__FILE__);

$solicitud = $_GET["solicitudId"];

if ($stmt = $mysqlCon->prepare($consultaTrabajoJSON)) {
    $trabajo = 1;
    $stmt->bind_param('i', $solicitud);
    
    $stmt->execute();

    $stmt->store_result();
    $row_cnt = $stmt->num_rows;
    
    if ($row_cnt < 1) {
        insertarTrabajo($solicitud, $trabajo, $departamentoId, $treinta, $path_parts);
    } else {
        actualizaTrabajo($solicitud, $path_parts);
    }
}

/**
 * Actualiza los valores del trabajo.
 *
 * @param int   $solicitud  The number of spaces to adjust the indent by
 * @param array $path_parts The number of spaces to adjust the indent by
 *
 * @return json
 */
function actualizaTrabajo($solicitud, $path_parts)
{
    global $updateTrabajoJSON, $mysqlCon;
    $jsondata = array();

    $fechaActual = date("d/m/Y");
    
    $parametros = "Query:" . $updateTrabajoJSON . " fechaActual:" . $fechaActual . " solicitud:" . $solicitud;
    
    try {
        if ($stmt = $mysqlCon->prepare($updateTrabajoJSON)) {
        
            $stmt->bind_param('si', $fechaActual, $solicitud);
            
            if ($stmt->execute()) {
                $jsondata["success"] = true;
                $jsondata["errorMessage"] = "Se ha actualizado el trabajo correctamente.";
                crearLog($path_parts['filename'], __FUNCTION__, $parametros, "Se ha actualizado el trabajo correctamente.", "correcto");

            } else { 
                $jsondata["success"] = false;
                $jsondata["errorMessage"] = "Problema al actualizar el trabajo, por favor, refresca la pagina.";
                crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:' . $mysqlCon->error, "error");
            }
    
        } else {
            $jsondata["success"] = false;
            $jsondata["errorMessage"] = "Errormessage: ". $mysqlCon->error;
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

/**
 * Inserta los valores del trabajo.
 *
 * @param int   $solicitud      The number of spaces to adjust the indent by
 * @param int   $trabajo        The number of spaces to adjust the indent by
 * @param int   $departamentoId The number of spaces to adjust the indent by
 * @param int   $treinta        The number of spaces to adjust the indent by
 * @param array $path_parts     The number of spaces to adjust the indent by
 *
 * @return json
 */
function insertarTrabajo($solicitud,$trabajo,$departamentoId,$treinta, $path_parts)
{
    /*definimos el json*/
    global $insertTrabajoJSON, $mysqlCon;
    $jsondata = array();
    $fechaActual = date("d/m/Y");

    $parametros = "Query:" . $insertTrabajoJSON . 
    " trabajo:" . $trabajo . 
    " solicitud:" . $solicitud . 
    " fechaActual:" . $fechaActual . 
    " solicitud:" . $solicitud . 
    " departamentoId:" . $departamentoId;
    
    try {
        if ($stmt = $mysqlCon->prepare($insertTrabajoJSON)) {
            
            $ceco = recuperaCeco($solicitud, $path_parts);
            $codigo =  recuperaCodigo($solicitud, $path_parts);
            $subdepartamentoId = recuperaSubdepartamentoId($solicitud, $path_parts);
            $departamentoId =  recuperaDepartamento($solicitud, $path_parts);
            
            $parametros = $parametros . 
            " ceco:" . $ceco . 
            " codigo:" . $codigo . 
            " departamentoId:" . $departamentoId . 
            " subdepartamentoId:" . $subdepartamentoId;
    
            $stmt->bind_param('iisdsiii', $trabajo, $solicitud, $fechaActual, $ceco, $codigo, $solicitud, $departamentoId, $subdepartamentoId);
            if ($stmt->execute()) {
                $jsondata["success"] = true;
                $jsondata["errorMessage"] = "Se ha creado el trabajo correctamente.";
            } else { 
                $jsondata["success"] = false;
                $jsondata["errorMessage"] = "Problema al crear el trabajo, por favor, refresque la pagina.";
                crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:' . $mysqlCon->error, "error");
            }
        } else {
            $jsondata["success"] = false;
            $jsondata["errorMessage"] = "Errormessage: ". $mysqlCon->error;
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

/**
 * Inserta los valores del trabajo.
 *
 * @param int   $solicitud  The number of spaces to adjust the indent by
 * @param array $path_parts The number of spaces to adjust the indent by
 *
 * @return string
 */
function recuperaCeco($solicitud, $path_parts)
{
    global $consultaCeco, $mysqlCon;
    $cecoParam = "";
    
    $parametros = "Query:" . $consultaCeco . " solicitud:" . $solicitud;
    try {
        if ($stmt = $mysqlCon->prepare($consultaCeco)) {
            $stmt->bind_param('i', $solicitud);
            if ($stmt->execute()) {
                $stmt->bind_result($ceco);
                while ($stmt->fetch()) {
                    $cecoParam = $ceco;
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
        return $cecoParam;
    }
    
}

/**
 * Inserta los valores del trabajo.
 *
 * @param int   $solicitud  The number of spaces to adjust the indent by
 * @param array $path_parts The number of spaces to adjust the indent by
 *
 * @return string
 */
function recuperaDepartamento($solicitud, $path_parts)
{
    global $consultaDepartamentoId, $mysqlCon;
    $depParam = "";
    $parametros = "Query:" . $consultaDepartamentoId . " solicitud:" . $solicitud;

    try {
        if ($stmt = $mysqlCon->prepare($consultaDepartamentoId)) {
            $stmt->bind_param('i', $solicitud);
            if ($stmt->execute()) {
                $stmt->bind_result($departamentoId);
                while ($stmt->fetch()) {
                    $depParam = $departamentoId;
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
        return $depParam;
    }
}

/**
 * Inserta los valores del trabajo.
 *
 * @param int   $solicitud  The number of spaces to adjust the indent by
 * @param array $path_parts The number of spaces to adjust the indent by
 *
 * @return string
 */
function recuperaCodigo($solicitud, $path_parts)
{
    $codigoParam = "";
    global $consultaCodigo, $mysqlCon;
    $parametros = "Query:" . $consultaCodigo . " solicitud:" . $solicitud;

    try {
        if ($stmt = $mysqlCon->prepare($consultaCodigo)) {
            $stmt->bind_param('i', $solicitud);
            if ($stmt->execute()) {
                $stmt->bind_result($treintabarra);
                while ($stmt->fetch()) {
                    $codigoParam = $treintabarra;
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
        return $codigoParam;
    }
}

/**
 * Inserta los valores del trabajo.
 *
 * @param int   $solicitud  The number of spaces to adjust the indent by
 * @param array $path_parts The number of spaces to adjust the indent by
 *
 * @return string
 */
function recuperaSubdepartamentoId($solicitud, $path_parts)
{
    $subdepParam = "";
    global $consultaSubDepartamentoId, $mysqlCon;
    $parametros = "Query:" . $consultaSubDepartamentoId . " solicitud:" . $solicitud;

    try {
        if ($stmt = $mysqlCon->prepare($consultaSubDepartamentoId)) {
            $stmt->bind_param('i', $solicitud);
            if ($stmt->execute()) {
                $stmt->bind_result($subdepartamento_id);
                while ($stmt->fetch()) {
                    $subdepParam = $subdepartamento_id;
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
        return $subdepParam;
    }
}