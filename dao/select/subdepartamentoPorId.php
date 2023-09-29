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

global $recuperaSubdepartamento, $mysqlCon;

/*definimos el json*/
$jsondata = array();
$jsondata["data"] = array();

//Declaracion de parametros
$departamento = "";
$subdepartamento = "";

//Recogemos los valores
if (isset($_POST['id'])) {
    $departamento = $_POST['id'];
}

//Recogemos los valores
if (isset($_POST['id1'])) {
    $subdepartamento = $_POST['id1'];
}

/*Prepare Statement*/
if ($stmt = $mysqlCon->prepare($recuperaSubdepartamento)) {
    $stmt->bind_param('ii', $departamento, $subdepartamento);
    /*Ejecucion*/
    $stmt->execute();
    /*Almacenamos el resultSet*/
    $stmt->bind_result($subdepartamento_desc, $treintabarra);
    /*Incluimos las lineas de la consulta en el json a devolver*/
    while ($stmt->fetch()) {
        $tmp = array();
        $tmp["nombre"] = utf8_encode($subdepartamento_desc);
        $tmp["treintaBarra"] = utf8_encode($treintabarra);
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