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

$idUsuario = 0;
$nombre = "";
$apellido = "";
$email = "";
$password = "";
$role = "";
$destino = "";

//Recogemos los valores
if (isset($_POST['usuarioHidden'])) {
    $idUsuario = utf8_decode(trim($_POST['usuarioHidden']));
}

if (isset($_POST['nombreHidden'])) {
    $nombre = utf8_decode(trim($_POST['nombreHidden']));
}

if (isset($_POST['apellidoHidden'])) {
    $apellido = utf8_decode(trim($_POST['apellidoHidden']));
}

if (isset($_POST['emailHidden'])) {
    $email = utf8_decode(trim($_POST['emailHidden']));
}

if (isset($_POST['passwordHidden'])) {
    $password = utf8_decode(trim($_POST['passwordHidden']));
}

if (isset($_POST['rolHidden'])) {
    $role = utf8_decode(trim($_POST['rolHidden']));
}

if ($role === '3') {
    if (isset($_POST['destinoHidden'])) {
        $destino = utf8_decode(trim($_POST['destinoHidden']));
    }
}

modificacionUsuarioFunction($idUsuario, $nombre, $apellido, $email, $password, $role, $destino);

/**
 * Inserta los valores de ByN por solicitud.
 *
 * @param int    $idUsuario The position of the token in the token stack
 * @param string $nombre    The position of the token in the token stack
 * @param string $apellido  The position of the token in the token stack
 * @param string $email     The position of the token in the token stack
 * @param string $password  The position of the token in the token stack
 * @param int    $role      The position of the token in the token stack
 * @param string $destino   The position of the token in the token stack
 *
 * @return json
 */
function modificacionUsuarioFunction($idUsuario, $nombre, $apellido, $email, $password, $role, $destino)
{
    global $mysqlCon, $actualizaUsuario, $borrarUsuarioDepartamento, $sentenciaInsertUsuarioValida;

    /*definimos el json*/
    $jsondata = array();
    $jsondata["data"] = array();
    $path_parts = pathinfo(__FILE__);
    $parametros = "Query:" . $actualizaUsuario . 
    " email:" . $email . 
    " password:" . $password . 
    " nombre:" . $nombre . 
    " apellido:" . $apellido . 
    " role:" . $role . 
    " idUsuario:" . $idUsuario;

    $parametrosValidador = "Query:" . $sentenciaInsertUsuarioValida;

    $parametrosBorrado = "Query:" . $borrarUsuarioDepartamento . " idUsuario:" . $idUsuario;
    

    try {
        if ($nombre === "" || $apellido === "" || $email === "" || $password === "" || $role === "") {
            $jsondata["success"] = false;
            $jsondata["message"] = "Faltan por enviar Parámetros";
        } elseif ($role === "3" && $destino === "") {
            $jsondata["success"] = false;
            $jsondata["message"] = "Faltan por completar departamentos";
        } else {
            if ($stmt = $mysqlCon->prepare($actualizaUsuario)) {
    
                $stmt->bind_param('ssssii', $email, $password, $nombre, $apellido, $role, $idUsuario);
                if ($stmt->execute()) {
                    if ($role === '3') {
                        
                        if ($stmt = $mysqlCon->prepare($borrarUsuarioDepartamento)) {
                            $stmt->bind_param('i', $idUsuario);
                            if ($stmt->execute()) {
                                $jsondata["success"] = true;
                                crearLog($path_parts['filename'], __FUNCTION__, $parametrosBorrado, "Se ha actualizado el Usuario", "correcto");
                            } else {
                                $jsondata["success"] = false;
                                $jsondata["message"] = "Error Insertando Usuario" . $mysqlCon->error;
                                crearLog($path_parts['filename'], __FUNCTION__, $parametrosBorrado, "Error Actualizando Usuario:" . $mysqlCon->error, "error");
                            }
                            
                        } else {
                            $jsondata["success"] = false;
                            $jsondata["message"] = "Error Insertando Usuario" . $mysqlCon->error;
                            crearLog($path_parts['filename'], __FUNCTION__, $parametrosBorrado, "Error Actualizando Usuario:" . $mysqlCon->error, "error");
                        }
        
                        $destinoArray = explode(",", $destino);
                        
                        for ($i = 0, $c = count($destinoArray); $i < $c; $i++) {
                            if ($destinoArray[$i] !== "") {
                                if ($stmt = $mysqlCon->prepare($sentenciaInsertUsuarioValida)) {
        
                                    $subdepartamentos = recuperaIdSubXDpto($destinoArray[$i]);
        
                                    if ($subdepartamentos != null) {
                                        $subdepartamento = explode(",", $subdepartamentos);
                                        for ($x = 0; $x < count($subdepartamento); $x++) {
                                            $stmt->bind_param('iii', $destinoArray[$i], $idUsuario, $subdepartamento[$x]);
                                            $parametrosValidador = $parametrosValidador .  " destinoArray:" . $destinoArray[$i] . " idUsuario:" . $idUsuario . " subdepartamento:" . $subdepartamento[$x];
                                            if (!$stmt->execute()) {
                                                $jsondata["success"] = false;
                                                $jsondata["message"] = "Error Actualizando Usuario -A" . $mysqlCon->error;
                                                crearLog($path_parts['filename'], __FUNCTION__, $parametrosValidador, "Error Actualizando Usuario:" . $mysqlCon->error, "error");
                                                break;
                                            } else {
                                                $jsondata["success"] = true;
                                                crearLog($path_parts['filename'], __FUNCTION__, $parametrosValidador, "Se ha actualizado el Usuario", "correcto");
                                            }
                                        }
                                    } else {
                                        $a=null;
                                        $stmt->bind_param('iii', $destinoArray[$i], $idUsuario, $a);
                                        $parametrosValidador = $parametrosValidador .  " destinoArray:" . $destinoArray[$i] . " idUsuario:" . $idUsuario . " subdepartamento:" . $a;
                                        if (!$stmt->execute()) {
                                            $jsondata["success"] = false;
                                            $jsondata["message"] = "Error Actualizando Usuario -B" . $mysqlCon->error;
                                            crearLog($path_parts['filename'], __FUNCTION__, $parametrosValidador, "Error Actualizando Usuario:" . $mysqlCon->error, "error");
                                            break;
                                        } else {
                                            $jsondata["success"] = true;
                                            crearLog($path_parts['filename'], __FUNCTION__, $parametrosValidador, "Se ha actualizado el Usuario", "correcto");
                                        }
                                    }
                                    $jsondata["success"] = true;
                                    $jsondata["message"] = "Usuario Actualizado";
                                    crearLog($path_parts['filename'], __FUNCTION__, $parametrosValidador, "Se ha actualizado el Usuario", "correcto");
                                } else {
                                    $jsondata["success"] = false;
                                    $jsondata["message"] = "Error Actualizando Usuario -D" . $mysqlCon->error;
                                    crearLog($path_parts['filename'], __FUNCTION__, $parametrosValidador, "Error Actualizando Usuario:" . $mysqlCon->error, "error");
                                }
                            }
                        }
                    } else {
                        $jsondata["success"] = true;
                        $jsondata["message"] = "Usuario Actualizado";
                        crearLog($path_parts['filename'], __FUNCTION__, $parametros, "Se ha actualizado el Usuario", "correcto");
                    }
                }
            } else {
                $jsondata["success"] = false;
                $jsondata["message"] = "Error Actualizando Usuario -E" . $mysqlCon->error;
                crearLog($path_parts['filename'], __FUNCTION__, $parametros, "Error Actualizando Usuario:" . $mysqlCon->error, "error");
            }
        }
    } catch (Exception $e) {
        $jsondata["success"] = false;
        $jsondata["message"] = "Error Actualizando Usuario -F" . $e;
        crearLog($path_parts['filename'], __FUNCTION__, $parametros, "Error Actualizando Usuario:" . $e, "error");
    } finally {
        $stmt->close();
        /*Devolvemos el JSON con los datos de la consulta*/
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata, JSON_FORCE_OBJECT);
    }
}

/**
 * Inserta los valores de ByN por solicitud.
 *
 * @param int $dpto The position of the token in the token stack
 *
 * @return string
 */
function recuperaIdSubXDpto($dpto)
{
    global $recuperaIdSubdptoXDpto, $mysqlCon;
    $path_parts = pathinfo(__FILE__);
    $parametros = "Query:" . $recuperaIdSubdptoXDpto . 
    " dpto:" . $dpto;
    $valores = "";
    try {
        if ($stmt = $mysqlCon->prepare($recuperaIdSubdptoXDpto)) {
            $stmt->bind_param('i', $dpto);
            if ($stmt->execute()) {
                $stmt->bind_result($col1);
                while ($stmt->fetch()) {
                    if ($valores == "") {
                        $valores = $col1;
                    } else {
                        $valores .= "," . $col1;
                    }
                }
            } else {
                crearLog($path_parts['filename'], __FUNCTION__, $parametros, "Error recuperando Id Usuario:" . $mysqlCon->error, "error");
            }
        } else {
            crearLog($path_parts['filename'], __FUNCTION__, $parametros, "Error recuperando Id Usuario:" . $mysqlCon->error, "error");
        }
    } catch (Exception $e) {
        crearLog($path_parts['filename'], __FUNCTION__, $parametros, "Error recuperando Id Usuario:" . $e, "error");
    } finally {
        return $valores;
    }
}
