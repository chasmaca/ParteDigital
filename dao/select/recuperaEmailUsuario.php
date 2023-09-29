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

$recuperaEmailPorId = "SELECT logon from usuario where usuario_id = ?";

/*definimos el json*/
$jsondata = array();
$jsondata["data"] = array();

//Declaracion de parametros
$id = "";

//Recogemos los valores
if (isset($_POST['id'])) {
    $id = $_POST['id'];
}

if ($id !== null) {
    if ($stmt = $mysqlCon->prepare($recuperaEmailPorId)) {
        $stmt->bind_param('s', $id);
        /*Ejecucion*/
        $stmt->execute();
        /*Almacenamos el resultSet*/
        $stmt->bind_result($logon);

        /*Incluimos las lineas de la consulta en el json a devolver*/
        while ($stmt->fetch()) {
            $tmp = array();
            $tmp["logon"] = utf8_encode($logon);
            array_push($jsondata["data"], $tmp);
        }
        $stmt->close();

        /*Asociamos el correcto funcionamiento al json para comprobar en el js*/
        $jsondata["success"] = true;


    }
}

/*Devolvemos el JSON con los datos de la consulta*/
header('Content-type: application/json; charset=utf-8');
echo json_encode($jsondata, JSON_FORCE_OBJECT);


?>