<?php

/**
 * Class: asociacionAutorizador.php
 *
 * Funciones para cargar departamentos y subdepartamentos por id de usuario
 * php version 7.3.28
 * 
 * @category Select
 * @package  DaoSelect
 * @author   Jesus Madrazo <chasmaca@gmail.com>
 * @license  http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version  GIT: <Initial Import>
 * @link     https://www.elpartedigital.com/
 */
require_once 'query.php';
require_once './../../utiles/connectDBUtiles.php';
require_once './../insert/insertLog.php';

//Declaracion de parametros
$autorizador = "";
$departamento = "";

//Recogemos los valores
if (isset($_POST['autorizador'])) {
    $autorizador = $_POST['autorizador'];
    cargarDptoAutorizador($autorizador);
}

//Recogemos los valores
if (isset($_POST['departamento'])) {
    $departamento = $_POST['departamento'];
    cargarSubDptoDpto($departamento);
}

/**
 * Devuelve todos los departamentos con permisos para un id de autorizador
 *
 * @param int $autorizador Id de Autorizador
 *
 * @return json
 */
function cargarDptoAutorizador($autorizador)
{

    global $recuperaDptoXAutorizadorJSON, $mysqlCon;
    $path_parts = pathinfo(__FILE__);
    /*definimos el json*/
    $jsondata = array();
    $jsondata["data"] = array();

    $parametros = "Query: " . $recuperaDptoXAutorizadorJSON . " autorizador:" . $autorizador;
    
    try{
        /*Prepare Statement*/
        if ($stmt = $mysqlCon->prepare($recuperaDptoXAutorizadorJSON)) {
            $stmt->bind_param("i", $autorizador);

            /*Ejecucion*/
            $stmt->execute();
            /*Almacenamos el resultSet*/
            $stmt->bind_result($dptoId, $dptoDesc, $ceco);

            /*Incluimos las lineas de la consulta en el json a devolver*/
            while ($stmt->fetch()) {
                $tmp = array();
                $tmp["id"] = utf8_encode($dptoId);
                $tmp["nombre"] = utf8_encode($dptoDesc);
                $tmp["ceco"] = utf8_encode($ceco);
                
                array_push($jsondata["data"], $tmp);
            }
            /*Asociamos el correcto funcionamiento al json para comprobar en el js*/
            $jsondata["success"] = true;
        } else {
            /*Llegamos aqui con error, asociamos false para identificarlo en el js*/
            $jsondata["success"] = false;
            crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:' . $mysqlCon->error, "error");
            //die("Errormessage: " . $mysqlCon->error);
        }

    } catch (Exception $e) {
        $jsondata["success"] = false;
        $jsondata["message"] = "Error recuperando Asociacion Autorizador " . $e;
        crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Error recuperando Asociacion Autorizador ' . $e, "error");
    } finally {
        $stmt->close();
        /*Devolvemos el JSON con los datos de la consulta*/
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata, JSON_FORCE_OBJECT);
    }
}

/**
 * Devuelve todos los departamentos con permisos para un id de autorizador
 *
 * @param int $departamento Cargar todos los departamentos por el id de departamento
 *
 * @return json
 */
function cargarSubDptoDpto($departamento)
{

    global $recuperaSubdptoXDpto, $mysqlCon;
    $path_parts = pathinfo(__FILE__);
    /*definimos el json*/
    $jsondata = array();
    $jsondata["data"] = array();

    $parametros = "Query:" . $recuperaSubdptoXDpto . " departamento:" . $departamento;
    try{
        /*Prepare Statement*/
        if ($stmt = $mysqlCon->prepare($recuperaSubdptoXDpto)) {
            $stmt->bind_param("i", $departamento);
            /*Ejecucion*/
            $stmt->execute();
            /*Almacenamos el resultSet*/
            $stmt->bind_result($dptoId, $subDptoId, $subDptoIdDesc, $treinta);
            /*Incluimos las lineas de la consulta en el json a devolver*/
            while ($stmt->fetch()) {
                $tmp = array();
                $tmp["id"] = utf8_encode($subDptoId);
                $tmp["nombre"] = utf8_encode($subDptoIdDesc);
                $tmp["treinta"] = utf8_encode($treinta);
                array_push($jsondata["data"], $tmp);
            }
            /*Asociamos el correcto funcionamiento al json para comprobar en el js*/
            $jsondata["success"] = true;
        } else {
            /*Llegamos aqui con error, asociamos false para identificarlo en el js*/
            $jsondata["success"] = false;
            crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:' . $mysqlCon->error, "error");
        }
    } catch (Exception $e) {
        $jsondata["success"] = false;
        $jsondata["message"] = "Error recuperando Subdpto de dpto " . $e;
        crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Error recuperando Subdpto de dpto ', "error");
    } finally {
        $stmt->close();
        /*Devolvemos el JSON con los datos de la consulta*/
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata, JSON_FORCE_OBJECT);
    }
}
?>