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

require_once '../../utiles/connectDBUtiles.php';
require_once '../select/query.php';
require_once 'inserciones.php';
require_once 'insertLog.php';

$modelo = '';
$edificio = '';
$ubicacion = '';
$fecha = '';
$serie = '';
$maquina = '';

//Recogemos los valores
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
}

if (isset($_POST['serieHidden'])) {
    $serie = utf8_decode(trim($_POST['serieHidden']));
}

if (isset($_POST['maquinaHidden'])) {
    $maquina = utf8_decode(trim($_POST['maquinaHidden']));
}

altaImpresoraFunction($modelo, $edificio, $ubicacion, $fecha, $serie, $maquina);

/**
 * Adjust the indent of a code block.
 *
 * @param string $modelo    The position of the token in the token stack
 * @param string $edificio  The position of the token in the token stack
 * @param string $ubicacion The position of the token in the token stack
 * @param string $fecha     The position of the token in the token stack
 * @param string $serie     The position of the token in the token stack
 * @param string $maquina   The position of the token in the token stack
 * 
 * @return json
 */
function altaImpresoraFunction($modelo, $edificio, $ubicacion, $fecha, $serie, $maquina)
{
    global $guardaImpresoras, $mysqlCon;

    /*definimos el json*/
    $jsondata = array();
    $jsondata["data"] = array();
    $parametros = $guardaImpresoras . " modelo:" . $modelo . " edificio:" . $edificio . " ubicacion:" . $ubicacion . " fecha:" . $fecha . " serie:" . $serie . " maquina:" . $maquina;  // phpcs:ignore
    $path_parts = pathinfo(__FILE__);

    try{
        if ($modelo === "" || $edificio ==="" || $ubicacion ==="" || $fecha ==="" || $serie ==="" || $maquina ==="") {
            $jsondata["success"] = false;
            $jsondata["message"] = "Faltan por enviar Parámetros";
        } else {
            if ($stmt = $mysqlCon->prepare($guardaImpresoras)) {
                $stmt->bind_param('sssssi', $modelo, $edificio, $ubicacion, $fecha, $serie, $maquina);
                if ($stmt->execute()) {
                    $jsondata["success"] = true;
                    $jsondata["message"] = "Impresora Insertada";
                    crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Impresora creado Correctamente', "correcto");
                } else {
                    $jsondata["success"] = false;
                    $jsondata["message"] = "Error Insertando Impresora" . $mysqlCon->error;
                    crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:' . $mysqlCon->error, "error");
                }
            } else {
                $jsondata["success"] = false;
                $jsondata["message"] = "Error Insertando Impresora" . $mysqlCon->error;
                crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:' . $mysqlCon->error, "error");
            }
        }
    } catch (Exception $e) {
        $jsondata["success"] = false;
        $jsondata["message"] = "Error Insertando Impresora" . $e;
        crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:' . $e, "error");
    } finally {
        $stmt->close();
        /*Devolvemos el JSON con los datos de la consulta*/
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata, JSON_FORCE_OBJECT);
    }
}
