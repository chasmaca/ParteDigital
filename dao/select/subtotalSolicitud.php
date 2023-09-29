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

//Declaracion de parametros
$solicitud = "";

//Recogemos los valores
if (isset($_POST['solicitud'])) {
    $solicitud = $_POST['solicitud'];
}

/*definimos el json*/
$jsondata = array();
$jsondata["data"] = array();


/*Prepare Statement*/
if ($stmt = $mysqlCon->prepare($recuperaSubtotalesTrabajo)) {
    $stmt->bind_param("i", $solicitud);
    /*Ejecucion*/
    $stmt->execute();
    /*Almacenamos el resultSet*/
    /*s1.nombre_solicitante, s1.apellido_solicitante, s1.fecha_validacion, d1.departamentos_desc, sd1.subdepartamento_desc*/
    $stmt->bind_result($tipo_id, $preciototal);

    /*Incluimos las lineas de la consulta en el json a devolver*/
    while ($stmt->fetch()) {
        $tmp = array();
        $tmp["tipo_id"] = $tipo_id;
        $tmp["preciototal"] = $preciototal;
        
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
?>