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

/*definimos el json*/
$jsondata = array();
$jsondata["data"] = array();
try{
    /*Prepare Statement*/
    if ($stmt = $mysqlCon->prepare($recuperaTrabajos)) {

        /*Ejecucion*/
        $stmt->execute();
        /*Almacenamos el resultSet */

        $stmt->bind_result(
            $solicitudId, $departamentoId, $nombre, $apellido,
            $autorizadorId, $descripcion, $email, $status, 
            $fechaAlta, $fechaValidacion, $fechaCierre, $departamentoDesc, $plantilla
        );

        
        /*Incluimos las lineas de la consulta en el json a devolver*/
        while ($stmt->fetch()) {
            
            $tmp = array();
            $tmp["id"] = utf8_encode($solicitudId);
            $tmp["departamento"] = utf8_encode($departamentoId);
            $tmp["solicitante"] = utf8_encode($nombre) . ' ' . utf8_encode($apellido);
            $tmp["autorizadorId"] = utf8_encode($autorizadorId);
            $tmp["descripcion"] = utf8_encode($descripcion);
            $tmp["email"] = utf8_encode($email);
            $tmp["status"] = utf8_encode($status);
            $tmp["fechaAlta"] = utf8_encode($fechaAlta);
            $tmp["fechaValidacion"] = utf8_encode($fechaValidacion);
            $tmp["fechaCierre"] = utf8_encode($fechaCierre);
            $tmp["nombreDepartamento"] = utf8_encode($departamentoDesc);
            $tmp["plantilla"] = utf8_encode($plantilla);

            array_push($jsondata["data"], $tmp);
        }

        /*Asociamos el correcto funcionamiento al json para comprobar en el js*/
        $jsondata["success"] = true;
    } else {
        /*Llegamos aqui con error, asociamos false para identificarlo en el js*/
        $jsondata["success"] = false;
        $jsondata["message"] = "Error recuperando trabajos " . $mysqlCon->error;
    }
} catch (Exception $e) {
    $jsondata["success"] = false;
    $jsondata["message"] = "Error recuperando trabajos " . $e;
}

$stmt->close();
/*Devolvemos el JSON con los datos de la consulta*/
header('Content-type: application/json; charset=utf-8');
echo json_encode($jsondata, JSON_FORCE_OBJECT);
?>