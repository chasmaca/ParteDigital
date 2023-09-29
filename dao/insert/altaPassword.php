<?php

/**
 * Class: altaPassword.php
 *
 * Actualizacion de contraseña para acceder a la solicitud
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

$password1 = '';
$password2 = '';

//Recogemos los valores
if (isset($_POST['masterPassword1Hidden'])) {
    $password1 = utf8_decode(trim($_POST['masterPassword1Hidden']));
}

if (isset($_POST['masterPassword2Hidden'])) {
    $password2 = utf8_decode(trim($_POST['masterPassword2Hidden']));
}

altaPasswordFunction($password1, $password2);

/**
 * Adjust the indent of a code block.
 *
 * @param string $password1 The position of the token in the token stack
 * @param string $password2 The position of the token in the token stack
 * 
 * @return json
 */
function altaPasswordFunction($password1, $password2)
{
    global $mysqlCon;
    $guardaPassword = "UPDATE administracion set password = ?";
    $jsondata = array();
    $jsondata["data"] = array();
    $parametros = $guardaPassword . " password:" . $password1;
    $path_parts = pathinfo(__FILE__);

    try {
        if ($password1 === "" || $password2 ==="") {
            $jsondata["success"] = false;
            $jsondata["message"] = "Faltan por enviar Parámetros";
            crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception: Faltan por enviar Parámetros', "error");
        } else {
            if ($password1 !== $password2) {
                $jsondata["success"] = false;
                $jsondata["message"] = "Las contraseñas deben ser iguales";
                crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception: Las contraseñas deben ser iguales', "error");
            } else {
                if ($stmt = $mysqlCon->prepare($guardaPassword)) {
                    $stmt->bind_param('s', $password1);
                    if ($stmt->execute()) {
                        $jsondata["success"] = true;
                        $jsondata["message"] = "Contraseña Actualizada";
                        crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Contraseña Actualizada', "correcto");
                    } else {
                        $jsondata["success"] = false;
                        $jsondata["message"] = "Error Insertando la Contraseña" . $mysqlCon->error;
                        crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:' . $mysqlCon->error, "error");
                    }
                } else {
                    $jsondata["success"] = false;
                    $jsondata["message"] = "Error Insertando la Contraseña" . $mysqlCon->error;
                    crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:' . $mysqlCon->error, "error");
                }
            }
        }
    } catch (Exception $e) {
        $jsondata["success"] = false;
        $jsondata["message"] = "Error Insertando password" . $e;
        crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:' . $e, "error");
    } finally {
        $stmt->close();
        /*Devolvemos el JSON con los datos de la consulta*/
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata, JSON_FORCE_OBJECT);
    }
}



