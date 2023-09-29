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
require_once 'insertLog.php';


/*definimos el json*/
$jsondata = array();
$jsondata["data"] = array();
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

insertaLinea($trabajo, $tipo, $detalle, $unidades, $solicitud, $total);

/**
 * Inserta los valores del trabajo.
 *
 * @param int   $trabajo   The position of the token in the token stack
 * @param int   $tipo      The number of spaces to adjust the indent by
 * @param int   $detalle   The position of the token in the token stack
 * @param int   $unidades  The number of spaces to adjust the indent by
 * @param int   $solicitud The position of the token in the token stack
 * @param float $total     The number of spaces to adjust the indent by
 *
 * @return json
 */
function insertaLinea($trabajo, $tipo, $detalle, $unidades, $solicitud, $total)
{
    global $guardaLinea, $mysqlCon;
    $path_parts = pathinfo(__FILE__);
    $parametros = "Query:" . $guardaLinea . 
    " trabajo:" . $trabajo . 
    " tipo:" . $tipo . 
    " detalle:" . $detalle . 
    " unidades:" . $unidades .
    " solicitud:" . $solicitud . 
    " total:" . $total;

    try {
        if ($stmt = $mysqlCon->prepare($guardaLinea)) {
            $stmt->bind_param('iiiiid', $trabajo, $tipo, $detalle, $unidades, $solicitud, $total);
            if (!$stmt->execute()) {
                $jsondata["success"] = false;
                crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:' . $mysqlCon->error, "error");
            } else {
                $jsondata["success"] = true;
                $jsondata["message"] = "Linea Insertado.";
            }
        } else {
            $jsondata["success"] = false;
            $jsondata["message"] = "Error Insertando Linea " . $mysqlCon->error;
            crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:' . $mysqlCon->error, "error");
            $stmt->close();
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


