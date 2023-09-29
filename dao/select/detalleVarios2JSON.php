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

$id = $_GET['id'];
/*Realizamos la llamada a la funcion que devolvera los departamentos*/
recuperamosDetalle($id);

function recuperamosDetalle($id)
{
    /*Declaramos como global la conexion y la query*/
    global $mysqlCon,$detalleVarios2PorId;
    /*definimos el json*/
    $jsondata = array();
    $jsondata["data"] = array();

    if ($stmt = $mysqlCon->prepare($detalleVarios2PorId)) {
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            /*Almacenamos el resultSet*/
            $stmt->bind_result($detalle_id, $tipo_id, $descripcion, $precio);
            while ($stmt->fetch()) {
                $tmp = array();
                $tmp["tipo"] = $tipo_id;
                $tmp["detalle"] = $detalle_id;
                $tmp["descripcion"] = utf8_encode($descripcion);
                $tmp["precio"] = $precio;
                /*Asociamos el resultado en forma de array en el json*/
                array_push($jsondata["data"], $tmp);
            }
            $jsondata["success"] = true;
        } else {
            $jsondata["success"] = false;
        }
        $stmt->close();
        /*Asociamos el correcto funcionamiento al json para comprobar en el js*/
    } else {
        /*Asociamos el correcto funcionamiento al json para comprobar en el js*/
        $jsondata["success"] = false;
    }
    /*Devolvemos el JSON con los datos de la consulta*/
    header('Content-type: application/json; charset=utf-8');
    echo json_encode($jsondata, JSON_FORCE_OBJECT);
}
?>