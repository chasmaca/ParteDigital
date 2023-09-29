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

$impresora = '';
$modelo = '';
$edificio = '';
$ubicacion = '';
$fecha = '';
$serie = '';
$maquina = '';

//Recogemos los valores
if (isset($_POST['impresoraHidden'])) {
    $impresora = utf8_decode(trim($_POST['impresoraHidden']));
}

if (isset($_POST['modeloHidden'])) {
    $modelo = utf8_decode(trim($_POST['modeloHidden']));
}

if (isset($_POST['edificioHidden'])) {
    $edificio = utf8_decode(trim($_POST['edificioHidden']));
}

if (isset($_POST['ubicacionHidden'])) {
    $ubicacion = utf8_decode(trim($_POST['ubicacionHidden']));
}

if (isset($_POST['fechaHidden'])) {
    $fecha = utf8_decode(trim($_POST['fechaHidden']));
    $fechaPartida = explode('/', $fecha);
    
    $fecha = ($fechaPartida[2] .'-'. $fechaPartida[1] . '-' . $fechaPartida[0]);
}

if (isset($_POST['serieHidden'])) {
    $serie = utf8_decode(trim($_POST['serieHidden']));
}

if (isset($_POST['maquinaHidden'])) {
    $maquina = utf8_decode(trim($_POST['maquinaHidden']));
}

modificacionImpresoraFunction($modelo, $edificio, $ubicacion, $fecha, $serie, $maquina, $impresora);

/**
 * Inserta los valores de ByN por solicitud.
 *
 * @param string $modelo    The position of the token in the token stack
 * @param string $edificio  The position of the token in the token stack
 * @param string $ubicacion The position of the token in the token stack
 * @param string $fecha     The position of the token in the token stack
 * @param string $serie     The position of the token in the token stack
 * @param int    $maquina   The position of the token in the token stack
 * @param int    $impresora The position of the token in the token stack
 *
 * @return json
 */
function modificacionImpresoraFunction($modelo, $edificio, $ubicacion, $fecha, $serie, $maquina, $impresora)
{
    global $actualizaImpresora, $mysqlCon;
    $jsondata = array();
    $jsondata["data"] = array();
    $path_parts = pathinfo(__FILE__);
    $parametros = "Query:" . $actualizaImpresora . 
    " modelo:" . $modelo . 
    " edificio:" . $edificio . 
    " ubicacion:" . $ubicacion . 
    " fecha:" . $fecha . 
    " serie:" . $serie . 
    " maquina:" . $maquina . 
    " impresora:" . $impresora;

    try {
        if ($modelo === "" || $edificio ==="" || $ubicacion ==="" || $fecha ==="" || $serie ==="" || $maquina ==="") {
            $jsondata["success"] = false;
            $jsondata["message"] = "Faltan por enviar Parámetros";
        } else {
            if ($stmt = $mysqlCon->prepare($actualizaImpresora)) {
                $stmt->bind_param('sssssii', $modelo, $edificio, $ubicacion, $fecha, $serie, $maquina, $impresora);
                if ($stmt->execute()) {
                    $jsondata["success"] = true;
                    $jsondata["message"] = "Impresora Actualizada";
                    crearLog($path_parts['filename'], __FUNCTION__, $parametros, "Impresora Actualizada", "correcto");
                } else {
                    $jsondata["success"] = false;
                    $jsondata["message"] = "Error Actualizando Impresora" . $mysqlCon->error;
                    crearLog($path_parts['filename'], __FUNCTION__, $parametros, "Error Actualizando Impresora :" . $mysqlCon->error, "error");
                }
            } else {
                $jsondata["success"] = false;
                $jsondata["message"] = "Error Actualizando Impresora" . $mysqlCon->error;
                crearLog($path_parts['filename'], __FUNCTION__, $parametros, "Error Actualizando Impresora :" . $mysqlCon->error, "error");
            }
        }
    } catch (Exception $e) {
        $jsondata["success"] = false;
        $jsondata["message"] = "Error Actualizando Impresora" . $e;
        crearLog($path_parts['filename'], __FUNCTION__, $parametros, "Error Actualizando Impresora :" . $e, "error");
    } finally {
        $stmt->close();
        /*Devolvemos el JSON con los datos de la consulta*/
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata, JSON_FORCE_OBJECT);
    }

}






