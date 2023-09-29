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
recuperamosVarios1($solicitudId);

/*Funcion que recupera todos los departamentos*/
function recuperamosVarios1($solicitudId)
{
    global $mysqlCon,$variosUnoQuery;
    $tipo="";
    $detalle="";
    $descripcion="";
    $precio="";
    $unidades="";
    $precioTotal="";
    $jsondata = array();
    $jsondata["data"] = array();
    if ($stmt = $mysqlCon->prepare($variosUnoQuery)) {
        $stmt->bind_param("i", $solicitudId);
        if ($stmt->execute()) {
            $stmt->bind_result($tipo, $detalle, $descripcion, $precio, $unidades, $precioTotal);
            while ($stmt->fetch()) {
                $tmp = array();
                $tmp["tipo"] = $tipo;
                $tmp["detalle"] = $detalle;
                $tmp["descripcion"] = utf8_encode($descripcion);
                $tmp["precio"] = $precio;
                $tmp["unidades"] = $unidades;
                $tmp["precioTotal"] = $precioTotal;
                array_push($jsondata["data"], $tmp);
            }
            $jsondata["success"] = true;
        } else {
            $jsondata["success"] = false;
            $log  = "Fichero: variosUnoJSON.php".PHP_EOL.
            "Query: ".$variosUnoQuery.PHP_EOL.
            "Errormessage: ". $mysqlCon->error.PHP_EOL.
            "-------------------------".PHP_EOL;
            error_log($log, 3, "../../log/errores.log");
        }
        $stmt->close();
    } else {
        $jsondata["success"] = false;
        $log  = "Fichero: variosUnoJSON.php".PHP_EOL.
        "Query: ".$variosUnoQuery.PHP_EOL.
        "Errormessage: ". $mysqlCon->error.PHP_EOL.
        "-------------------------".PHP_EOL;
        error_log($log, 3, "../../log/errores.log");
    }
    header('Content-type: application/json; charset=utf-8');
    echo json_encode($jsondata, JSON_FORCE_OBJECT);
}
?>