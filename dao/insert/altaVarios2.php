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
require_once "../../utiles/connectDBUtiles.php";
require_once "../select/query.php";
require_once "inserciones.php";
require_once "insertLog.php";

/*definimos el json*/
$jsondata = array();
$jsondata["data"] = array();
$descripcion = "";
$precio = "";
$tipo = 7;
$detalle = 0;

//Recogemos los valores
if (isset($_POST['descripcion'])) {
    $descripcion = $_POST['descripcion'];
}

if (isset($_POST['precio'])) {
    $precio = $_POST['precio'];
}

insertaVariosDos($detalle, $tipo, $descripcion, $precio);

/**
 * Inserta los valores del trabajo.
 *
 * @param int    $detalle     The position of the token in the token stack
 * @param int    $tipo        The number of spaces to adjust the indent by
 * @param string $descripcion The position of the token in the token stack
 * @param double $precio      The number of spaces to adjust the indent by
 *
 * @return json
 */
function insertaVariosDos($detalle, $tipo, $descripcion, $precio)
{
    global $mysqlCon, $guardaVarios2, $recuperaMaxDetalle;
    $path_parts = pathinfo(__FILE__);
    $parametros = "Query:" . $guardaVarios2 . 
    " detalle:" . $detalle . 
    " tipo:" . $tipo . 
    " descripcion:" . $descripcion . 
    " precio:" . $precio;

    try {
        if ($stmt = $mysqlCon->prepare($recuperaMaxDetalle)) {
            $stmt->bind_param('i', $tipo);
            if (!$stmt->execute()) {
                $jsondata["success"] = false;
                $jsondata["message"] =  "Error consultado Maximo de Varios 2. ";
            } else {
                /*Almacenamos el resultSet*/
                $stmt->bind_result($detalleId);
                /*Incluimos las lineas de la consulta en el json a devolver*/
                while ($stmt->fetch()) {
                    $detalle = $detalleId;
                }
            }
            $stmt->close();
        } else {
            $jsondata["success"] = false;
            $jsondata["message"] = "Error consultado Maximo de Varios 2. " . $mysqlCon->error;
            $stmt->close();
        }
        
        if ($stmt = $mysqlCon->prepare($guardaVarios2)) {
            $stmt->bind_param('iisd', $detalle, $tipo, $descripcion, $precio);
            if (!$stmt->execute()) {
                $jsondata["success"] = false;
                $jsondata["message"] =  "Linea NO Insertado. " . $parametros;
                crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:' . $mysqlCon->error, "error");
                
            } else {
                $jsondata["success"] = true;
                $jsondata["message"] = $detalle;    
                crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Alta de Varios 2 Correcta', "correcto");   // phpcs:ignore
            }
        } else {
            $jsondata["success"] = false;
            $jsondata["message"] = "Error Insertando Linea " . $mysqlCon->error;
            crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:' . $mysqlCon->error, "error");
        }
    } catch (Exception $e) {
        $jsondata["success"] = false;
        $jsondata["message"] = "Error Insertando Linea " . $e;
        crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:' . $e, "error");
        
    } finally {
        $stmt->close();
        /*Devolvemos el JSON con los datos de la consulta*/
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata, JSON_FORCE_OBJECT);
    }
    
}

