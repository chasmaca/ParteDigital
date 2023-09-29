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

global $recuperaGastosImpresora, $recuperaGastosImpresoraToday, $mysqlCon;

//Recogemos los valores
if (isset($_POST['periodoHidden'])) {
    $periodo = $_POST['periodoHidden'];
}

$fecha = explode("/", $periodo);

/*definimos el json*/
$jsondata = array();
$jsondata["data"] = array();

$transdate = date('m/Y', time());

$fechasIguales = false;
if ($periodo === $transdate) {
    $fechasIguales = true;
}

if ($fechasIguales === true) {
    /*Prepare Statement*/
    if ($stmt = $mysqlCon->prepare($recuperaGastosImpresoraToday)) {

        $stmt->bind_param("ss", $fecha[0], $fecha[1]);
        /*Ejecucion*/
        $stmt->execute();
        /*Almacenamos el resultSet*/
        $stmt->bind_result($departamento, $departamentoId, $periodo, $byn_unidades, $byn_precio, $byn_total, $color_unidades, $color_precio, $color_total);
        
        /*Incluimos las lineas de la consulta en el json a devolver*/
        while ($stmt->fetch()) {
            $tmp = array();
            
            $tmp["departamento"] = utf8_encode($departamento);
            $tmp["departamentoId"] = utf8_encode($departamentoId);
            $tmp["periodo"] = utf8_encode($periodo);
            $tmp["byn_unidades"] = utf8_encode($byn_unidades);
            $tmp["byn_precio"] = utf8_encode($byn_precio);
            $tmp["byn_total"] = utf8_encode($byn_total);
            $tmp["color_unidades"] = utf8_encode($color_unidades);
            $tmp["color_precio"] = utf8_encode($color_precio);
            $tmp["color_total"] = utf8_encode($color_total);
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

} else {
    /*Prepare Statement*/
    if ($stmt = $mysqlCon->prepare($recuperaGastosImpresora)) {

        $stmt->bind_param("ss", $fecha[0], $fecha[1]);
        /*Ejecucion*/
        $stmt->execute();
        /*Almacenamos el resultSet*/
        $stmt->bind_result($departamento, $departamentoId, $periodo, $byn_unidades, $byn_precio, $byn_total, $color_unidades, $color_precio, $color_total);
    
        /*Incluimos las lineas de la consulta en el json a devolver*/
        while ($stmt->fetch()) {
            $tmp = array();
            
            $tmp["departamento"] = utf8_encode($departamento);
            $tmp["departamentoId"] = utf8_encode($departamentoId);
            $tmp["periodo"] = utf8_encode($periodo);
            $tmp["byn_unidades"] = utf8_encode($byn_unidades);
            $tmp["byn_precio"] = utf8_encode($byn_precio);
            $tmp["byn_total"] = utf8_encode($byn_total);
            $tmp["color_unidades"] = utf8_encode($color_unidades);
            $tmp["color_precio"] = utf8_encode($color_precio);
            $tmp["color_total"] = utf8_encode($color_total);
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

}

/*Devolvemos el JSON con los datos de la consulta*/
header('Content-type: application/json; charset=utf-8');
echo json_encode($jsondata, JSON_FORCE_OBJECT);
?>