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

global $consultaTodosTrabajos, $mysqlCon;

/*definimos el json*/
$jsondata = array();
$jsondata["data"] = array();

$periodo = '';
$departamento = '';

if (isset($_POST['periodo'])) {
    $periodo = $_POST['periodo'];
}

if (isset($_POST['departamento'])) {
    $departamento = $_POST['departamento'];
}

$query = "";

if ($periodo === "0" && $departamento === "0") {
    consultaTodosTrabajos($consultaTodosTrabajos, $mysqlCon, $jsondata);
}

if ($periodo === "0" && $departamento !== "0") {
    consultaTodosTrabajosPorDpto($consultaTodosTrabajosDepartamento, $mysqlCon, $jsondata, $departamento);
}

if ($periodo !== "0" && $departamento === "0") {
    consultaTodosTrabajosPorPeriodo($consultaTodosTrabajosFecha, $mysqlCon, $jsondata, $periodo);
}

if ($periodo !== "0" && $departamento !== "0") {
    consultaTodosTrabajosPorDptoYPeriodo($consultaTodosTrabajosDepartamentoFecha, $mysqlCon, $jsondata, $departamento, $periodo);
}


function consultaTodosTrabajos($consultaTodosTrabajos, $mysqlCon, $jsondata) 
{


    $solicitud_id = $departamentos_desc = $subdepartamento_desc = $nombre_solicitante = $apellidos_solicitante = $fecha_alta = '';
    $fecha_cierre = $nombre = $apellido = $descripcion_solicitante = $email_solicitante = $status_desc = $status_id = $fecha_alta = '';

    /*Prepare Statement*/
    if ($stmt = $mysqlCon->prepare($consultaTodosTrabajos)) {
        /*Ejecucion*/
        $stmt->execute();

        /*Almacenamos el resultSet*/
        $stmt->bind_result(
            $solicitud_id, $departamentos_desc, $subdepartamento_desc,
            $nombre_solicitante, $apellidos_solicitante, $fecha_alta,
            $fecha_cierre, $nombre, $apellido, $descripcion_solicitante,
            $email_solicitante, $status_desc, $status_id, $fecha_alta
        );

        /*Incluimos las lineas de la consulta en el json a devolver*/
        while ($stmt->fetch()) {
            $tmp = array();
            
            $tmp["SOLICITUD"] = utf8_encode($solicitud_id);
            $tmp["DEPARTAMENTO"] = utf8_encode($departamentos_desc);
            $tmp["SUBDEPARTAMENTO"] = utf8_encode($subdepartamento_desc);
            $tmp["NOMBRE"] = utf8_encode($nombre_solicitante) . ' ' . utf8_encode($apellidos_solicitante);
            $tmp["EMAIL"] = utf8_encode($email_solicitante);
            $tmp["AUTORIZADOR"] = utf8_encode($nombre) . ' ' . utf8_encode($apellido);
            $tmp["DESCRIPCION"] = utf8_encode($descripcion_solicitante);
            $tmp["ESTADO"] = utf8_encode($status_desc);
            $tmp["ALTA"] = utf8_encode($fecha_alta);
            $tmp["CIERRE"] = utf8_encode($fecha_cierre);

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

    

function consultaTodosTrabajosPorDpto($consultaTodosTrabajosDepartamento, $mysqlCon, $jsondata, $dpto) 
{

    $solicitud_id = $departamentos_desc = $subdepartamento_desc = $nombre_solicitante = $apellidos_solicitante = $fecha_alta = '';
    $fecha_cierre = $nombre = $apellido = $descripcion_solicitante = $email_solicitante = $status_desc = $status_id = $fecha_alta = '';

    /*Prepare Statement*/
    if ($stmt = $mysqlCon->prepare($consultaTodosTrabajosDepartamento)) {
        $stmt->bind_param('i', $dpto);
        /*Ejecucion*/
        $stmt->execute();
        /*Almacenamos el resultSet*/

        /**
         * 'OPERACIONES'
         **/
        $stmt->bind_result(
            $solicitud_id, $departamentos_desc, $subdepartamento_desc,
            $nombre_solicitante, $apellidos_solicitante, $fecha_alta,
            $fecha_cierre, $nombre, $apellido, $descripcion_solicitante,
            $email_solicitante, $status_desc, $status_id, $fecha_alta
        );

        /*Incluimos las lineas de la consulta en el json a devolver*/
        while ($stmt->fetch()) {
            $tmp = array();
            
            $tmp["SOLICITUD"] = utf8_encode($solicitud_id);
            $tmp["DEPARTAMENTO"] = utf8_encode($departamentos_desc);
            $tmp["SUBDEPARTAMENTO"] = utf8_encode($subdepartamento_desc);
            $tmp["NOMBRE"] = utf8_encode($nombre_solicitante) . ' ' . utf8_encode($apellidos_solicitante);
            $tmp["EMAIL"] = utf8_encode($email_solicitante);
            $tmp["AUTORIZADOR"] = utf8_encode($nombre) . ' ' . utf8_encode($apellido);
            $tmp["DESCRIPCION"] = utf8_encode($descripcion_solicitante);
            $tmp["ESTADO"] = utf8_encode($status_desc);
            $tmp["ALTA"] = utf8_encode($fecha_alta);
            $tmp["CIERRE"] = utf8_encode($fecha_cierre);

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



function consultaTodosTrabajosPorPeriodo($consultaTodosTrabajosFecha, $mysqlCon, $jsondata, $periodo) 
{

    $solicitud_id = $departamentos_desc = $subdepartamento_desc = $nombre_solicitante = $apellidos_solicitante = $fecha_alta = '';
    $fecha_cierre = $nombre = $apellido = $descripcion_solicitante = $email_solicitante = $status_desc = $status_id = $fecha_alta = '';
    $detalleFecha = explode("/", $periodo);

    /*Prepare Statement*/
    if ($stmt = $mysqlCon->prepare($consultaTodosTrabajosFecha)) {

        $stmt->bind_param('ss', $detalleFecha[0], $detalleFecha[1]);
        /*Ejecucion*/
        $stmt->execute();
        /*Almacenamos el resultSet*/

        /**
         * 'OPERACIONES'
         **/
        $stmt->bind_result(
            $solicitud_id, $departamentos_desc, $subdepartamento_desc,
            $nombre_solicitante, $apellidos_solicitante, $fecha_alta,
            $fecha_cierre, $nombre, $apellido, $descripcion_solicitante,
            $email_solicitante, $status_desc, $status_id, $fecha_alta
        );

        /*Incluimos las lineas de la consulta en el json a devolver*/
        while ($stmt->fetch()) {
            $tmp = array();
            
            $tmp["SOLICITUD"] = utf8_encode($solicitud_id);
            $tmp["DEPARTAMENTO"] = utf8_encode($departamentos_desc);
            $tmp["SUBDEPARTAMENTO"] = utf8_encode($subdepartamento_desc);
            $tmp["NOMBRE"] = utf8_encode($nombre_solicitante) . ' ' . utf8_encode($apellidos_solicitante);
            $tmp["EMAIL"] = utf8_encode($email_solicitante);
            $tmp["AUTORIZADOR"] = utf8_encode($nombre) . ' ' . utf8_encode($apellido);
            $tmp["DESCRIPCION"] = utf8_encode($descripcion_solicitante);
            $tmp["ESTADO"] = utf8_encode($status_desc);
            $tmp["ALTA"] = utf8_encode($fecha_alta);
            $tmp["CIERRE"] = utf8_encode($fecha_cierre);

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



function consultaTodosTrabajosPorDptoYPeriodo($consultaTodosTrabajosDepartamentoFecha, $mysqlCon, $jsondata, $departamento, $periodo) 
{

    $solicitud_id = $departamentos_desc = $subdepartamento_desc = $nombre_solicitante = $apellidos_solicitante = $fecha_alta = '';
    $fecha_cierre = $nombre = $apellido = $descripcion_solicitante = $email_solicitante = $status_desc = $status_id = $fecha_alta = '';
    $detalleFecha = explode("/", $periodo);

    /*Prepare Statement*/
    if ($stmt = $mysqlCon->prepare($consultaTodosTrabajosDepartamentoFecha)) {

        $stmt->bind_param('iss', $departamento, $detalleFecha[0], $detalleFecha[1]);
        /*Ejecucion*/
        $stmt->execute();
        /*Almacenamos el resultSet*/

        /**
         * 'OPERACIONES'
         **/
        $stmt->bind_result(
            $solicitud_id, $departamentos_desc, $subdepartamento_desc,
            $nombre_solicitante, $apellidos_solicitante, $fecha_alta,
            $fecha_cierre, $nombre, $apellido, $descripcion_solicitante,
            $email_solicitante, $status_desc, $status_id, $fecha_alta
        );

        /*Incluimos las lineas de la consulta en el json a devolver*/
        while ($stmt->fetch()) {
            $tmp = array();
            
            $tmp["SOLICITUD"] = utf8_encode($solicitud_id);
            $tmp["DEPARTAMENTO"] = utf8_encode($departamentos_desc);
            $tmp["SUBDEPARTAMENTO"] = utf8_encode($subdepartamento_desc);
            $tmp["NOMBRE"] = utf8_encode($nombre_solicitante) . ' ' . utf8_encode($apellidos_solicitante);
            $tmp["EMAIL"] = utf8_encode($email_solicitante);
            $tmp["AUTORIZADOR"] = utf8_encode($nombre) . ' ' . utf8_encode($apellido);
            $tmp["DESCRIPCION"] = utf8_encode($descripcion_solicitante);
            $tmp["ESTADO"] = utf8_encode($status_desc);
            $tmp["ALTA"] = utf8_encode($fecha_alta);
            $tmp["CIERRE"] = utf8_encode($fecha_cierre);

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
?>


