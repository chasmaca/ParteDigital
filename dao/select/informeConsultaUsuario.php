<?php
/**
 * Class: altaArticulo.php
 *
 * Formulario de alta de nuevos articulos de reprografia
 * php version 7.3.28
 * 
 * @category Insert
 * @package  DaoInsert
 * @author   Jesus Madrazo <chasmaca@gmail.com>
 * @license  http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version  GIT: <Initial Import>
 * @link     https://www.elpartedigital.com/
 */

require_once 'query.php';
require_once '../../utiles/connectDBUtiles.php';

global $mysqlCon;

$idUsuario = 0;
$rol = 0;

/*definimos el json*/
$jsondata = array();
$jsondata["data"] = array();

$recuperaUsuariosFiltro = "";

if (isset($_POST['usuario'])) {
    $idUsuario = utf8_decode(trim($_POST['usuario']));
}

if (isset($_POST['rolHidden'])) {
    $rol = utf8_decode(trim($_POST['rolHidden']));
}

if ($idUsuario !== "0" ) {
    //consultaUsuario
    $recuperaUsuariosFiltro = "SELECT usuario_id, logon, nombre, apellido, role_id FROM usuario WHERE usuario_id = ?";

    if ($stmt = $mysqlCon->prepare($recuperaUsuariosFiltro)) {

        $stmt->bind_param("i", $idUsuario);
        /*Ejecucion*/
        $stmt->execute();
        /*Almacenamos el resultSet*/
        $stmt->bind_result($usuario_id, $logon, $nombre, $apellido, $role_id);
        /*Incluimos las lineas de la consulta en el json a devolver*/
        while ($stmt->fetch()) {
            $tmp = array();
            $tmp["id"] = utf8_encode($usuario_id);
            $tmp["logon"] = utf8_encode($logon);
            $tmp["nombre"] = utf8_encode($nombre);
            $tmp["apellido"] = utf8_encode($apellido);
            $tmp["rol"] = utf8_encode($role_id);
            array_push($jsondata["data"], $tmp);
        }
        $stmt->close();
        /*Asociamos el correcto funcionamiento al json para comprobar en el js*/
        $jsondata["success"] = true;
    } else {
        /*Llegamos aqui con error, asociamos false para identificarlo en el js*/
        $jsondata["success"] = false;
        die("Errormessage: " . $mysqlCon->error);
    }
}
if ($idUsuario === "0" && $rol !== "0" ) {
    //consultaUsuariosRol
    $recuperaUsuariosFiltro = "SELECT usuario_id, logon, nombre, apellido, role_id FROM usuario WHERE role_id = ? ORDER BY nombre";

    if ($stmt = $mysqlCon->prepare($recuperaUsuariosFiltro)) {

        $stmt->bind_param("i", $rol);
        /*Ejecucion*/
        $stmt->execute();
        /*Almacenamos el resultSet*/
        $stmt->bind_result($usuario_id, $logon, $nombre, $apellido, $role_id);
        /*Incluimos las lineas de la consulta en el json a devolver*/
        while ($stmt->fetch()) {
            $tmp = array();
            $tmp["id"] = utf8_encode($usuario_id);
            $tmp["logon"] = utf8_encode($logon);
            $tmp["nombre"] = utf8_encode($nombre);
            $tmp["apellido"] = utf8_encode($apellido);
            $tmp["rol"] = utf8_encode($role_id);
            array_push($jsondata["data"], $tmp);
        }
        $stmt->close();
        /*Asociamos el correcto funcionamiento al json para comprobar en el js*/
        $jsondata["success"] = true;
    } else {
        /*Llegamos aqui con error, asociamos false para identificarlo en el js*/
        $jsondata["success"] = false;
        die("Errormessage: " . $mysqlCon->error);
    }
}
if ($idUsuario === "0" && $rol === "0" ) {
    $recuperaUsuariosFiltro = "SELECT usuario_id, logon, nombre, apellido, role_id FROM usuario ORDER BY nombre";

    if ($stmt = $mysqlCon->prepare($recuperaUsuariosFiltro)) {
        /*Ejecucion*/
        $stmt->execute();
        /*Almacenamos el resultSet*/
        $stmt->bind_result($usuario_id, $logon, $nombre, $apellido, $role_id);
        /*Incluimos las lineas de la consulta en el json a devolver*/
        while ($stmt->fetch()) {
            $tmp = array();
            $tmp["id"] = utf8_encode($usuario_id);
            $tmp["logon"] = utf8_encode($logon);
            $tmp["nombre"] = utf8_encode($nombre);
            $tmp["apellido"] = utf8_encode($apellido);
            $tmp["rol"] = utf8_encode($role_id);
            array_push($jsondata["data"], $tmp);
        }
        $stmt->close();
        /*Asociamos el correcto funcionamiento al json para comprobar en el js*/
        $jsondata["success"] = true;
    } else {
        /*Llegamos aqui con error, asociamos false para identificarlo en el js*/
        $jsondata["success"] = false;
        die("Errormessage: " . $mysqlCon->error);
    }
}



/*Devolvemos el JSON con los datos de la consulta*/
header('Content-type: application/json; charset=utf-8');
echo json_encode($jsondata, JSON_FORCE_OBJECT);
