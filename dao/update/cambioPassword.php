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

//Declaracion de parametros
$id = "";
$password = "";

//Recogemos los valores
if (isset($_POST['id'])) {
    $id = $_POST['id'];
}

if (isset($_POST['password'])) {
    $password = $_POST['password'];
}

modificacionPasswordFunction($id, $password);

/**
 * Inserta los valores de ByN por solicitud.
 *
 * @param int    $id       The position of the token in the token stack
 * @param string $password The position of the token in the token stack
 *
 * @return json
 */
function modificacionPasswordFunction($id, $password)
{
    global $mysqlCon;
    /*definimos el json*/
    $jsondata = array();
    $jsondata["data"] = array();
    $cambioPassword = "UPDATE usuario SET password = ? where usuario_id = ?";
    $parametros = "Query:" . $cambioPassword . " Id: " . $id . " password:". $password;
    $path_parts = pathinfo(__FILE__);
    
    try{
        if ($id !== "" && $password !== "") {
            if ($stmt = $mysqlCon->prepare($cambioPassword)) {
                
                $stmt->bind_param('si', $password, $id);
                
                if ($stmt->execute()) {
                    $jsondata["success"] = true;
                    $jsondata["errorMessage"] = "La password se ha actualizado correctamente.";
                    crearLog($path_parts['filename'], __FUNCTION__, $parametros, "La password se ha actualizado correctamente.", "correcto");
                } else {
                    $jsondata["success"] = false;
                    $jsondata["errorMessage"] = "Problemas al actualizar la password.";
                    crearLog($path_parts['filename'], __FUNCTION__, $parametros, $mysqlCon->error, "error");
                }
            } else {
                $jsondata["success"] = false;
                $jsondata["errorMessage"] = "Problemas al actualizar la password.";
                crearLog($path_parts['filename'], __FUNCTION__, $parametros, $mysqlCon->error, "error");
            }
        
        } else {
            crearLog($path_parts['filename'], __FUNCTION__, $parametros, "Faltan Parámetros", "error");
        }
    }catch(Exception $e) {
        crearLog($path_parts['filename'], __FUNCTION__, $parametros, $e, "error");
    }finally{
        /*Devolvemos el JSON con los datos de la consulta*/
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata, JSON_FORCE_OBJECT);
    }
}

?>