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

global $consultaUsuarioQuery, $mysqlCon;

/*definimos el json*/
$jsondata = array();
$jsondata["data"] = array();

//Declaracion de parametros
$usuario = "";


//Recogemos los valores
if (isset($_POST['id'])) {
    $usuario = $_POST['id'];
}

/*
    *  $consultaUsuarioQuery = "SELECT USUARIO_ID, LOGON, NOMBRE, APELLIDO, ROLE_ID, password FROM usuario WHERE USUARIO_ID = ?";
    *  $consultaUsuarioValidador = "SELECT distinct(departamento_id) FROM usuariodepartamento  WHERE USUARIO_ID = ?";
    */
/*Prepare Statement*/
if ($stmt = $mysqlCon->prepare($consultaUsuarioQuery)) {
    $stmt->bind_param('i', $usuario);
    /*Ejecucion*/
    $stmt->execute();
    /*Almacenamos el resultSet*/
    $stmt->bind_result($usuarioId, $email, $nombre, $apellido, $role_id, $password);
    /*Incluimos las lineas de la consulta en el json a devolver*/
    while ($stmt->fetch()) {
        $tmp = array();
        $tmp["email"] = utf8_encode($email);
        $tmp["nombre"] = utf8_encode($nombre);
        $tmp["apellido"] = utf8_encode($apellido);
        $tmp["rol"] = utf8_encode($role_id);
        $tmp["password"] = utf8_encode($password);
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

/*Devolvemos el JSON con los datos de la consulta*/
header('Content-type: application/json; charset=utf-8');
echo json_encode($jsondata, JSON_FORCE_OBJECT);
