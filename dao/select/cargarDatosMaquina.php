<?php

/**
 * Class: varios2JSON.php
 *
 * Insercion de Varios 2 Extra
 * php version 7.3.28
 * 
 * @category Insert
 * @package  DaoInsert
 * @author   Jesus Madrazo <chasmaca@gmail.com>
 * @license  http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version  GIT: <Initial Import>
 * @link     https://www.elpartedigital.com/
 */
require_once '../select/query.php';
require_once '../../utiles/connectDBUtiles.php';

//Declaracion de parametros
$periodo = "";

//Recogemos los valores
if (isset($_POST['periodo'])) {
    $periodo = $_POST['periodo'];
}

/*En caso que el combo no tenga seleccionado un periodo no se realizara ninguna operacion.*/
if ($periodo != "") {

    recuperamosGastos($periodo);

} else {

    die("Debe Cumplimentar correctamente los campos.");

}

/**
 * Funcion para la recuperacion de todos los datos de gastos de cierre en impresoras.
 * Las consultas se haran en funcion del periodo del combo del html
 * 
 * @param string $periodoCierre periodo
 */
function recuperamosGastos($periodoCierre)
{

    global $mysqlCon,$recuperaGastosMaquinaCierre;

    /*definimos el json*/
    $jsondata = array();
    $jsondata["data"] = array();

    //Realizaremos un split del periodo que vendra con este formato--> 11/2016

    $separador = strrpos($periodoCierre, '/');

    $mes = substr($periodoCierre, 0, $separador);
    $anio = substr($periodoCierre, $separador+1);

    if ($stmt = $mysqlCon->prepare($recuperaGastosMaquinaCierre)) {
        /*Asociacion de parametros*/
        $stmt->bind_param('ss', $mes, $anio);
        /*Ejecucion de la consulta*/
        $stmt->execute();
        /*Almacenamos el resultSet*/

        /*Almacenamos el resultSet*/
        $stmt->bind_result($departamento_id, $ceco, $periodo, $byn_unidades, $byn_precio, $byn_total, $color_unidades, $color_precio, $color_total);

        while ($stmt->fetch()) {
            
            $tmp = array();
            $tmp["departamento_id"] = $departamento_id;
            $tmp["esb"] = $ceco;
            $tmp["periodo"] = $periodo;
            $tmp["byn_unidades"] =$byn_unidades;
            $tmp["byn_precio"] = $byn_precio;
            $tmp["byn_total"] = $byn_total;
            $tmp["color_unidades"] = $color_unidades;
            $tmp["color_precio"] = $color_precio;
            $tmp["color_total"] =$color_total;
            
            /*Asociamos el resultado en forma de array en el json*/
            array_push($jsondata["data"], $tmp);
        }
        $stmt->close();
        /*Asociamos el correcto funcionamiento al json para comprobar en el js*/
        $jsondata["success"] = true;
    } else {
        /*Llegamos aqui con error, asociamos false para identificarlo en el js*/
        $jsondata["success"] = false;
        die("Errormessage: ". $mysqlCon->error);
    }
    
    /*Devolvemos el JSON con los datos de la consulta*/
    header('Content-type: application/json; charset=utf-8');
    echo json_encode($jsondata, JSON_FORCE_OBJECT);
}
?>