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
use PHPMailer\PHPMailer\Exception;
require_once 'query.php';
require_once '../../utiles/connectDBUtiles.php';


//Declaracion de parametros
$tipo = "";

//Recogemos los valores
if (isset($_POST['tipo'])) {
    $tipo = $_POST['tipo'];
}

if ($tipo === 'aprobador') {
    recuperaUsuarioAprobador();
} else {
    recuperaUsuarios();
}

function recuperaUsuarios()
{

    global $recuperaTodosUsuarios, $mysqlCon;

    /*definimos el json*/
    $jsondata = array();
    $jsondata["data"] = array();


    /*Prepare Statement*/
    if ($stmt = $mysqlCon->prepare($recuperaTodosUsuarios)) {

        /*Ejecucion*/
        $stmt->execute();
        /*Almacenamos el resultSet*/
        $stmt->bind_result($nombre, $rol, $nombreDepartamento);

        /*Incluimos las lineas de la consulta en el json a devolver*/
        while ($stmt->fetch()) {
            $tmp = array();
            $tmp["nombre"] = utf8_encode($nombre);
            $tmp["rol"] = utf8_encode($rol);
            $tmp["nombreDepartamento"] = utf8_encode($nombreDepartamento);

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
}


function recuperaUsuarioAprobador()
{

    global $todosAutorizadoresQuery, $mysqlCon;

    /*definimos el json*/
    $jsondata = array();
    $jsondata["data"] = array();


    /*Prepare Statement*/
    if ($stmt = $mysqlCon->prepare($todosAutorizadoresQuery)) {

        /*Ejecucion*/
        $stmt->execute();
        /*Almacenamos el resultSet*/
        $stmt->bind_result($id, $nombre, $apellido, $email);

        /*Incluimos las lineas de la consulta en el json a devolver*/
        while ($stmt->fetch()) {
            $tmp = array();
            $tmp["id"] = utf8_encode($id);
            $tmp["nombre"] = utf8_encode($nombre) . ' ' . utf8_encode($apellido);

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
}

