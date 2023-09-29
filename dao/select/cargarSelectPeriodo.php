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
    
/*definimos el json*/
$jsondata = array();
$jsondata["data"] = array();


for ($i = 0; $i <= 20; $i++) {
    $months[] = (string) date("m/Y", strtotime(date('01-m-Y')." -$i months"));
}

foreach ($months as $valor) {
    $tmp = array();
    $tmp["id"] = $valor;
    $tmp["nombre"] = $valor;
    /*Asociamos el resultado en forma de array en el json*/
    array_push($jsondata["data"], $tmp);
}

/*Asociamos el correcto funcionamiento al json para comprobar en el js*/
$jsondata["success"] = true;

/*Devolvemos el JSON con los datos de la consulta*/
header('Content-type: application/json; charset=utf-8');
echo json_encode($jsondata, JSON_FORCE_OBJECT);

?>