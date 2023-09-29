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

/*Declaramos como global la conexion y la query*/
global $mysqlCon;

$recuperaPrecioByNCierre = "select precio from detalle d1 inner join tipo t1 on d1.tipo_id = t1.tipo_id and t1.tipo_desc like '%Blanco%' where d1.descripcion='B/N'";

/*definimos el json*/
$jsondata = array();
$jsondata["data"] = array();

$bynResult = mysqli_query($mysqlCon, $recuperaPrecioByNCierre);
        
if (!$bynResult) {
    echo "No se pudo ejecutar con exito la consulta ($recuperaPrecioByNCierre) en la BD: " . mysql_error();
    exit;
}
            
if (mysqli_num_rows($bynResult) == 0) {
    echo "No se han encontrado filas, nada a imprimir, asi que voy a detenerme.";
    exit;
}
            
while ($fila = mysqli_fetch_assoc($bynResult)) {
    $tmp = array();
    $tmp["precio"] = $fila["precio"];

    /*Asociamos el resultado en forma de array en el json*/
    array_push($jsondata["data"], $tmp);

}

/*Asociamos el correcto funcionamiento al json para comprobar en el js*/
$jsondata["success"] = true;
        
/*Devolvemos el JSON con los datos de la consulta*/
header('Content-type: application/json; charset=utf-8');
echo json_encode($jsondata, JSON_FORCE_OBJECT);
?>