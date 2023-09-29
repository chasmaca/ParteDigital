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

$solicitudId = "No DATA";

//Recogemos los valores
if (isset($_POST['solicitudId'])) {
    $solicitudId = $_POST['solicitudId'];
} else {
    $solicitudId = $_GET['solicitudId'];
}

/*Realizamos la llamada a la funcion que devolvera los departamentos*/
recuperamosEncolado($solicitudId);

function recuperamosEncolado($solicitudId)
{

    /*Declaramos como global la conexion y la query*/
    global $mysqlCon,$encoladoQuery;

    /*definimos el json*/
    $jsondata = array();
    $jsondata["data"] = array();

    if ($stmt = $mysqlCon->prepare($encoladoQuery)) {
        $stmt->bind_param("i", $solicitudId);
        if ($stmt->execute()) {
            /*Almacenamos el resultSet*/
            $stmt->bind_result($tipo, $detalle, $descripcion, $precio, $unidades, $precioTotal);
            while ($stmt->fetch()) {
                $tmp = array();
                $tmp["tipo"] = $tipo;
                $tmp["detalle"] = $detalle;
                $tmp["descripcion"] = utf8_encode($descripcion);
                $tmp["precio"] = $precio;
                $tmp["unidades"] = $unidades;
                $tmp["precioTotal"] = $precioTotal;
                /*Asociamos el resultado en forma de array en el json*/
                array_push($jsondata["data"], $tmp);
            }
            /*Asociamos el correcto funcionamiento al json para comprobar en el js*/
            $jsondata["success"] = true;
        } else {
            /*Asociamos el correcto funcionamiento al json para comprobar en el js*/
            $jsondata["success"] = false;
        }
        $stmt->close();
    } else {
        /*Asociamos el correcto funcionamiento al json para comprobar en el js*/
        $jsondata["success"] = false;
    }
    /*Devolvemos el JSON con los datos de la consulta*/
    header('Content-type: application/json; charset=utf-8');
    echo json_encode($jsondata, JSON_FORCE_OBJECT);
}
?>