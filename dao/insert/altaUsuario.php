<?php

/**
 * Class: altaUsuario.php
 *
 * Creacion de subdepartamentos
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

/*definimos el json*/
$jsondata = array();
$jsondata["data"] = array();
$idUsuario = 0;
$nombre = "";
$apellido = "";
$email = "";
$password = "";
$role = "";
$destino = "";

//Recogemos los valores
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

altaUsuarioFunction($email, $password, $nombre, $apellido, $role, $destino);

/**
 * Recupera unicamente los id de subdepartamentos existentes, 
 * 
 * @param string $email    codigo de departamento
 * @param string $password codigo de departamento
 * @param string $nombre   codigo de departamento
 * @param string $apellido codigo de departamento
 * @param int    $role     codigo de departamento
 * @param string $destino  codigo de departamento
 * 
 * @return json
 */
function altaUsuarioFunction($email, $password, $nombre, $apellido, $role, $destino)
{
    global $guardaUsuario, $sentenciaInsertUsuarioValida, $mysqlCon;
    $path_parts = pathinfo(__FILE__);
    $jsondata = array();
    $jsondata["data"] = array();
    $idUsuario = recuperaMaxUsuario();
    $parametros = "Query:" . $guardaUsuario . " -> id:" . $idUsuario . "email:" . $email . " password:" . $password . " nombre:" . $nombre . " apellido:" . $apellido . " role:" . $role . " destino:" . $destino;  // phpcs:ignore

    try {
        if ($nombre === "" || $apellido ==="" || $email ==="" || $password ==="" || $role ==="") {
            $jsondata["success"] = false;
            $jsondata["message"] = "Faltan por enviar Parámetros";
            crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:Faltan Parametros', "error");
        } elseif ($role ==="3" && $destino === "") {
            $jsondata["success"] = false;
            $jsondata["message"] = "Faltan por completar departamentos";
            crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:Faltan por completar departamentos', "error");
        } else {
            if ($stmt = $mysqlCon->prepare($guardaUsuario)) {
                $stmt->bind_param('issssi', $idUsuario, $email, $password, $nombre, $apellido, $role);
                $stmt->execute();
                if ($role === '3') {
                    $destinoArray = explode(",", $destino);
                    for ($i = 0, $c = count($destinoArray); $i < $c; $i++) {
                        if ($destinoArray[$i] !== "") {
                            if ($stmt = $mysqlCon->prepare($sentenciaInsertUsuarioValida)) {
                                $subdepartamentos = recuperaIdSubXDpto($destinoArray[$i]);
                                if ($subdepartamentos != null) {
                                    $subdepartamento = explode(",", $subdepartamentos);
                                    for ($x=0; $x<count($subdepartamento);$x++) {
                                        $stmt->bind_param('iii', $destinoArray[$i], $idUsuario, $subdepartamento[$x]);
                                        if (! $stmt->execute()) {
                                            $jsondata["success"] = false;
                                            $jsondata["message"] = "Error Insertando Usuario" . $mysqlCon->error;
                                            crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:Error Insertando Usuario ' . $mysqlCon->error, "error");   // phpcs:ignore
                                            break;
                                        }
                                    }
                                } else {
                                    $stmt->bind_param('iii', $destinoArray[$i], $idUsuario, null);
                                    $parametrosDepartamento = "Query:" . $sentenciaInsertUsuarioValida . " -> dptos:" . $destinoArray[$i] . "usuario:" . $idUsuario;  // phpcs:ignore
                                    if (! $stmt->execute()) {
                                        $jsondata["success"] = false;
                                        $jsondata["message"] = "Error Insertando Usuario" . $mysqlCon->error;
                                        crearLog($path_parts['filename'], __FUNCTION__, $parametrosDepartamento, 'Exception:Error Insertando Usuario Departamento ' . $mysqlCon->error, "error");   // phpcs:ignore
                                        break;
                                    } else {
                                        $jsondata["success"] = true;
                                        $jsondata["message"] = "Usuario Insertado";
                                        crearLog($path_parts['filename'], __FUNCTION__, $parametrosDepartamento, 'Usuario actualizado con departamento', "correcto");   // phpcs:ignore
                                    }
                                }
                                $jsondata["success"] = true;
                                $jsondata["message"] = "Usuario Insertado";
                                crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Usuario Insertado', "correcto");   // phpcs:ignore

                            } else {
                                $jsondata["success"] = false;
                                $jsondata["message"] = "Error Insertando Usuario " . $mysqlCon->error;
                                crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:Error Insertando Usuario ' . $mysqlCon->error, "error");
                            }
                        }
                    }
                } else {
                    $jsondata["success"] = true;
                    $jsondata["message"] = "Usuario Insertado";
                    crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Usuario Insertado', "correcto");   // phpcs:ignore
                }
            } else {
                $jsondata["success"] = false;
                $jsondata["message"] = "Error Insertando Usuario " . $mysqlCon->error;
                crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:Error Insertando Usuario ' . $mysqlCon->error, "error");
            }
        }
    } catch (Exception $e) {
        $jsondata["success"] = false;
        $jsondata["message"] = "Error Insertando Usuario " . $e;
        crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:Error Insertando Usuario ' . $e, "error");
    } finally {
        $stmt->close();
        /*Devolvemos el JSON con los datos de la consulta*/
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata, JSON_FORCE_OBJECT);
    }
}

/**
 * Recupera unicamente los id de subdepartamentos existentes, 
 * 
 * @param int $dpto codigo de departamento
 * 
 * @return string
 */
function recuperaIdSubXDpto($dpto)
{
    global $recuperaIdSubdptoXDpto,$mysqlCon;
    $valores = "";
    $path_parts = pathinfo(__FILE__);
    $parametros = "Query:" . $recuperaIdSubdptoXDpto . " -> dpto:" . $dpto;  // phpcs:ignore

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
                crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:Error Recuperando Subdepartamentos por departamento ' . $mysqlCon->error, "error");   // phpcs:ignore
            }
        } else {
            crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:Error Recuperando Subdepartamentos por departamento ' . $mysqlCon->error, "error");   // phpcs:ignore
        }
    } catch (Exception $e) {
        crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:Error Recuperando Subdepartamentos por departamento ' . $e, "error");
    } finally{
        $stmt->close();
        return $valores;
    }
}

/**
 * Recupera unicamente los id de subdepartamentos existentes, 
 * 
 * @return string
 */
function recuperaMaxUsuario()
{
    global $recuperaMaxUsuario,$mysqlCon;
    $valores = "";
    $path_parts = pathinfo(__FILE__);
    $parametros = "Query:" . $recuperaMaxUsuario;  // phpcs:ignore
    try {
        if ($stmt = $mysqlCon->prepare($recuperaMaxUsuario)) {
            if ($stmt->execute()) {
                $stmt->bind_result($col1);
                while ($stmt->fetch()) {
                    $valores = $col1;
                }
            } else {
                crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:Error Recuperando el Maximo del Usuario ' . $mysqlCon->error, "error");
            }
        } else {
            crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:Error Recuperando el Maximo del Usuario ' . $mysqlCon->error, "error");
        }
    } catch (Exception $e) {
        crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:Error Recuperando el Maximo del Usuario ' . $e, "error");
    } finally{
        $stmt->close();
        return $valores;
    }
}