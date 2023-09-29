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
    
$tipo = "";
$detalle = "";
$nombre = "";
$precio = "";

//Recogemos los valores
if (isset($_POST['tipoHidden'])) {
    $tipo = trim($_POST['tipoHidden']);
}

if (isset($_POST['detalleHidden'])) {
    $detalle = utf8_decode(trim($_POST['detalleHidden']));
}

if (isset($_POST['nombreHidden'])) {
    $nombre = utf8_decode(trim($_POST['nombreHidden']));
}

if (isset($_POST['precioHidden'])) {
    $precio = utf8_decode(trim($_POST['precioHidden']));
}

modificacionArticuloFunction($nombre, $precio, $tipo, $detalle);

/**
 * Inserta los valores de ByN por solicitud.
 *
 * @param string $nombre  The position of the token in the token stack
 * @param double $precio  The position of the token in the token stack
 * @param int    $tipo    The position of the token in the token stack
 * @param int    $detalle The position of the token in the token stack
 *
 * @return json
 */
function modificacionArticuloFunction($nombre, $precio, $tipo, $detalle)
{
    /*Declaramos como global la conexion y la query y el id de validador*/
    global $mysqlCon, $actualizaArticulo;
    /*definimos el json*/
    $jsondata = array();
    $jsondata["data"] = array();
    $path_parts = pathinfo(__FILE__);
    $parametros = "Query:" . $actualizaArticulo . 
    " nombre:" . $nombre . 
    " precio:" . $precio . 
    " tipo:" . $tipo . 
    " detalle:" . $detalle;
    try {
        if ($tipo === "" || $detalle === ""  || $nombre === ""  || $precio === "") {
            $jsondata["success"] = false;
            $jsondata["message"] = "Faltan Parámetros";
        } else {
        
            if ($stmt = $mysqlCon->prepare($actualizaArticulo)) {
                $stmt->bind_param('sdii', $nombre, $precio, $tipo, $detalle);
                if ($stmt->execute()) {
                    $jsondata["success"] = true;
                    $jsondata["message"] = "Se ha actualizado el Articulo";
                    crearLog($path_parts['filename'], __FUNCTION__, $parametros, "Se ha actualizado el Articulo", "correcto");
                } else {
                    $jsondata["success"] = false;
                    $jsondata["message"] = "Error Actualizando Articulo " . $mysqlCon->error;
                    crearLog($path_parts['filename'], __FUNCTION__, $parametros, "Error Actualizando Articulo :" . $mysqlCon->error, "error");
                }
            } else {
                /*Llegamos aqui con error, asociamos false para identificarlo en el js*/
                $jsondata["success"] = false;
                $jsondata["message"] = "Error Actualizando Articulo " . $mysqlCon->error;
                crearLog($path_parts['filename'], __FUNCTION__, $parametros, "Error Actualizando Articulo :" . $mysqlCon->error, "error");
            }
        }
    } catch (Exception $e) {
        $jsondata["success"] = false;
        $jsondata["message"] = "Error Actualizando Articulo " . $e;
        crearLog($path_parts['filename'], __FUNCTION__, $parametros, "Error Actualizando Articulo :" . $e, "error");
    } finally {
        $stmt->close();
        /*Devolvemos el JSON con los datos de la consulta*/
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata, JSON_FORCE_OBJECT);
    }
}

?>