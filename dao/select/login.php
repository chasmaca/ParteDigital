<?php
/**
 * Class: cerrarMes.php
 *
 * Cierre del mes contable
 * php version 7.3.28
 * 
 * @category Update
 * @package  DaoUpdate
 * @author   Jesus Madrazo <chasmaca@gmail.com>
 * @license  http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version  GIT: <Initial Import>
 * @link     https://www.elpartedigital.com/
 */
session_start();

require_once 'query.php';
require_once  '../../utiles/connectDBUtiles.php';
require_once './../insert/insertLog.php';

/*Definimos las variables*/
$usuario = "";
$password = "";

/*Recuperamos la request*/
if (isset($_POST['usuario'])) {
    $usuario = utf8_decode($_POST['usuario']);
} else {
    $usuario = "";
}

/*Recuperamos la request*/
if (isset($_POST['password'])) {
    $password = utf8_decode($_POST['password']);
} else {
    $password = "";
}

/*Realizamos la llamada a la funcion*/
if ($usuario != "" && $password != "") {
    recuperaUsuario($usuario, $password);
}
    
/**
 * Inserta los valores de ByN por solicitud.
 *
 * @param string $usuario  The number of spaces to adjust the indent by
 * @param string $password The number of spaces to adjust the indent by
 *
 * @return json
 */
function recuperaUsuario($usuario, $password)
{

    global $sentenciaLogonJSON, $mysqlCon;

    $usuario_id = "";
    $logon = "";
    $nombre = "";
    $apellido = "";
    $role_id = "";

    /*definimos el json*/
    $jsondata = array();
    $jsondata["data"] = array();
    try {
        /*Prepare Statement*/
        if ($stmt = $mysqlCon->prepare($sentenciaLogonJSON)) {

            /*Asociacion de parametros*/
            $stmt->bind_param('ss', $usuario, $password);

            /*Ejecucion*/
            if ($stmt->execute()) {
                
                /*Almacenamos el resultSet*/
                $stmt->bind_result($usuario_id, $logon, $password, $nombre, $apellido, $role_id);
                /*Incluimos las lineas de la consulta en el json a devolver*/
                $jsondata["message"] = "Usuario/Password No Valido";
                $jsondata["success"] = false;

                while ($stmt->fetch()) {
                    $_SESSION["role_session"] = $role_id;
                    $_SESSION["nombre_session"] = utf8_encode($nombre) . " " . utf8_encode($apellido);
                    $_SESSION["userId_session"] = $usuario_id;

                    $tmp = array();
                    $tmp["usuario_id"] = $usuario_id;
                    $tmp["logon"] = $logon;
                    $tmp["password"] = $password;
                    $tmp["nombre"] = utf8_encode($nombre);
                    $tmp["apellido"] = utf8_encode($apellido);
                    $tmp["role_id"] = $role_id;
                    array_push($jsondata["data"], $tmp);
                    $jsondata["success"] = true;
                    $jsondata["message"] = "Usuario/Password Valido";
                }

            } else {
                $jsondata["success"] = false;
                $jsondata["message"] = "Errormessage: " . $mysqlCon->error . " " . $sentenciaLogonJSON;
            }
        } else {
            /*Llegamos aqui con error, asociamos false para identificarlo en el js*/
            $jsondata["success"] = false;
            $jsondata["message"] = "Errormessage: " . $mysqlCon->error . " " . $sentenciaLogonJSON;
        }

        
    } catch (Exception $exception) {
        $jsondata["success"] = false;
        $jsondata["message"] = "Errormessage: " . $exception;
    } finally {
        $stmt->close();
        /*Devolvemos el JSON con los datos de la consulta*/
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata, JSON_FORCE_OBJECT);
    }

    
}
