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
    
$departamento = "";
$subdepartamento = "";
$nombre = "";
$treintaBarra = "";

//Recogemos los valores
if (isset($_POST['departamentoHidden'])) {
    $departamento = trim($_POST['departamentoHidden']);
}

if (isset($_POST['subdepartamentoHidden'])) {
    $subdepartamento = utf8_decode(trim($_POST['subdepartamentoHidden']));
}

if (isset($_POST['nombreHidden'])) {
    $nombre = utf8_decode(trim($_POST['nombreHidden']));
}

if (isset($_POST['treintaBarraHidden'])) {
    $treintaBarra = utf8_decode(trim($_POST['treintaBarraHidden']));
}

modificacionSubdepartamentoFunction($nombre, $treintaBarra, $departamento, $subdepartamento);

/**
 * Inserta los valores de ByN por solicitud.
 *
 * @param string $nombre          The position of the token in the token stack
 * @param string $treintaBarra    The position of the token in the token stack
 * @param int    $departamento    The position of the token in the token stack
 * @param int    $subdepartamento The position of the token in the token stack
 *
 * @return json
 */
function modificacionSubdepartamentoFunction($nombre, $treintaBarra, $departamento, $subdepartamento)
{
    /*Declaramos como global la conexion y la query y el id de validador*/
    global $mysqlCon, $actualizaSubdepartamento;

    /*definimos el json*/
    $jsondata = array();
    $jsondata["data"] = array();
    $path_parts = pathinfo(__FILE__);
    $parametros = "Query:" . $actualizaSubdepartamento . 
    " nombre:" . $nombre . 
    " treintaBarra:" . $treintaBarra . 
    " departamento:" . $departamento . 
    " subdepartamento:" . $subdepartamento;

    try {
        if ($departamento === "" || $subdepartamento === ""  || $nombre === ""  || $treintaBarra === "") {
            $jsondata["success"] = false;
            $jsondata["message"] = "Faltan Parámetros";
        } else {
        
            if ($stmt = $mysqlCon->prepare($actualizaSubdepartamento)) {
                $stmt->bind_param('ssii', $nombre, $treintaBarra, $departamento, $subdepartamento);
                if (!$stmt->execute()) {
                    /*Llegamos aqui con error, asociamos false para identificarlo en el js*/
                    $jsondata["success"] = false;
                    $jsondata["message"] = "Error Actualizando Subdepartamento " . $mysqlCon->error;
                    crearLog($path_parts['filename'], __FUNCTION__, $parametros, "Error Actualizando subdepartamento :" . $mysqlCon->error, "error");
                } else {
                    $jsondata["success"] = true;
                    $jsondata["message"] = "Se ha actualizado el subdepartamento";
                    crearLog($path_parts['filename'], __FUNCTION__, $parametros, "Se ha actualizado el subdepartamento", "correcto");
                }
            } else {
                /*Llegamos aqui con error, asociamos false para identificarlo en el js*/
                $jsondata["success"] = false;
                $jsondata["message"] = "Error Actualizando Subdepartamento " . $mysqlCon->error;
                crearLog($path_parts['filename'], __FUNCTION__, $parametros, "Error Actualizando subdepartamento :" . $mysqlCon->error, "error");
            }
        }
    } catch (Exception $e) {
        $jsondata["success"] = false;
        $jsondata["message"] = "Error Actualizando Subdepartamento " . $e;
        crearLog($path_parts['filename'], __FUNCTION__, $parametros, "Error Actualizando subdepartamento :" . $e, "error");
    } finally {
        $stmt->close();
        /*Devolvemos el JSON con los datos de la consulta*/
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata, JSON_FORCE_OBJECT);
    }
}

?>