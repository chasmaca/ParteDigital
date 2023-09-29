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

$recuperaIdPorEmail = "SELECT usuario_id from usuario where logon = ?";

/*definimos el json*/
$jsondata = array();
$jsondata["data"] = array();

//Declaracion de parametros
$email = "";

//Recogemos los valores
if (isset($_POST['email'])) {
    $email = $_POST['email'];
}

if ($email !== null) {
    if ($stmt = $mysqlCon->prepare($recuperaIdPorEmail)) {
        $stmt->bind_param('s', $email);
        /*Ejecucion*/
        $stmt->execute();
        /*Almacenamos el resultSet*/
        $stmt->bind_result($usuarioId);

        /*Incluimos las lineas de la consulta en el json a devolver*/
        while ($stmt->fetch()) {
            $tmp = array();
            $tmp["id"] = utf8_encode($usuarioId);
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