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

global $actualizaDepartamentoQuery, $mysqlCon;

/*definimos el json*/
$jsondata = array();
$jsondata["data"] = array();

//Declaracion de parametros
$impresora = "";

//Recogemos los valores
if (isset($_POST['id'])) {
    $impresora = $_POST['id'];
}


/*Prepare Statement*/
if ($stmt = $mysqlCon->prepare($consultaImpresorasPorId)) {
    $stmt->bind_param('i', $impresora);
    /*Ejecucion*/
    $stmt->execute();
    /*Almacenamos el resultSet*/
    $stmt->bind_result($impresoraId, $modelo, $edificio, $ubicacion, $fecha, $serie, $maquina);

    /*Incluimos las lineas de la consulta en el json a devolver*/
    while ($stmt->fetch()) {
        $tmp = array();
        $tmp["id"] = utf8_encode($impresoraId);
        $tmp["modelo"] = utf8_encode($modelo);
        $tmp["edificio"] = utf8_encode($edificio);
        $tmp["ubicacion"] = utf8_encode($ubicacion);
        $fechaPartida = explode('-', $fecha);
        $fecha = ($fechaPartida[2] .'/'. $fechaPartida[1] . '/' . $fechaPartida[0]);
        $tmp["fecha"] = utf8_encode($fecha);
        $tmp["serie"] = utf8_encode($serie);
        $tmp["maquina"] = utf8_encode($maquina);
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
