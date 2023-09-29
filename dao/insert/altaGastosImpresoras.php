<?php

/**
 * Class: trabajo-old.php
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

require_once 'inserciones.php';
require_once '../select/query.php';
require_once '../update/updates.php';
require_once '../../utiles/connectDBUtiles.php';

//Declaracion de parametros
/*Parametros que nos llega en la request*/
$periodo = "";
$departamento = "";
$unidades = "";
$precio = "";
$total = "";
$tipo = "";

/*Parametros para el tratamiento del periodo*/
$mes = "";
$anio = "";
$fecha = "";

/*JSON de vuelta*/
$jsondata = "";


//Recogemos los valores
if (isset($_POST['periodo'])) {
    $periodo = $_POST['periodo'];
}

if (isset($_POST['departamento'])) {
    $departamento = $_POST['departamento'];
}

if (isset($_POST['unidades'])) {
    $unidades = $_POST['unidades'];
}

if (isset($_POST['precio'])) {
    $precio = $_POST['precio'];
}

if (isset($_POST['total'])) {
    $total = $_POST['total'];
}

if (isset($_POST['tipo'])) {
    $tipo = $_POST['tipo'];
}

if ($periodo != "" && $departamento != "" && $unidades != "" && $precio != "" && $total != ""  && $tipo != "") {
    
    $mes = substr($periodo, 0, strpos($periodo, "/"));
    $anio = substr($periodo, strpos($periodo, "/")+1, strlen($periodo));
    
    $fecha = $anio.'-'.$mes.'-01 23:59:00';
    
    if ($tipo == 'Color') {
        insertamosGastosColor($departamento, $unidades, $precio, $total, $fecha, $anio, $mes);
    } else {
        insertamosGastosByN($departamento, $unidades, $precio, $total, $fecha, $anio, $mes);
    }
        

} else {
    echo $periodo . '---' . $departamento . '---' . $unidades . '---' . $precio . '---' . $total . '---' . $tipo;
    die("Debe Cumplimentar correctamente los campos.");
}

    
    
/**
 * Actualizamos los gastos de Blanco y Negro por departamento y por periodo.
 * 
 * @param int    $departamento departamento
 * @param int    $unidades     unidades
 * @param double $precio       precio
 * @param double $total        total
 * @param string $fecha        año
 * @param string $anio         año
 * @param string $mes          mes
 * 
 * @return json
 */
function insertamosGastosByN($departamento, $unidades, $precio, $total, $fecha, $anio, $mes)
{
    
    global $mysqlCon, $sentenciaInsertGastosImpresora;
    
    /*definimos el json*/
    $jsondata = array();
    $jsondata["data"] = array();
    
    $unidadesColor = 0;
    $precioColor = 0;
    $totalColor = 0;

    /**
     * Comprobamos si existe el registro para realizar la insercion o la actualizacion.
     * Si existeRegistro devuelve true se realiza la insercion.
     * Si existeRegistro devuelve false se realiza una actualizacion.
     */
    if (existeRegistro($departamento, $anio, $mes)) {
        if ($stmt = $mysqlCon->prepare($sentenciaInsertGastosImpresora)) {
            $stmt->bind_param('isiddidd', $departamento, $fecha, $unidades, $precio, $total, $unidadesColor, $precioColor, $totalColor);
            if (!$stmt->execute()) {
                $jsondata["success"] = false;
                $jsondata["message"] = "Fallo la ejecucion: (" . $mysqlCon->errno . ") " . $mysqlCon->error;
            } else {
                $jsondata["success"] = true;
                $jsondata["message"] = "Dato Insertado Correctamente";
            }

        } else {
            $jsondata["success"] = false;
            $jsondata["message"] = "Fallo la ejecucion: " .  $mysqlCon->error;
        }
    } else {
        actualizamosGastosByN($departamento, $unidades, $precio,  $total, $anio, $mes);
    }
    
    header('Content-type: application/json; charset=utf-8');
    echo json_encode($jsondata, JSON_FORCE_OBJECT);
}

/**
 * Actualizamos los gastos de Blanco y Negro por departamento y por periodo.
 * 
 * @param int    $departamento departamento
 * @param int    $unidades     unidades
 * @param double $precio       precio
 * @param double $total        total
 * @param string $anio         año
 * @param string $mes          mes
 * 
 * @return json
 */
function actualizamosGastosByN($departamento, $unidades, $precio, $total, $anio, $mes)
{
    
    global $mysqlCon, $sentenciaUpdateGastosImpresoraByN;

    if ($stmt = $mysqlCon->prepare($sentenciaUpdateGastosImpresoraByN)) {
        $stmt->bind_param('iddiss', $unidades, $precio, $total, $departamento, $anio, $mes);
            
        if (!$stmt->execute()) {
            $jsondata["success"] = false;
            $jsondata["message"] = "Fallo la ejecucion: (" . $mysqlCon->errno . ") " . $mysqlCon->error;

        } else {
            $jsondata["success"] = true;
            $jsondata["message"] = "Actualizacion Realizada";
        }
    
    } else {
        $jsondata["success"] = false;
        $jsondata["message"] = "Errormessage: ". $mysqlCon->error;
    }
    header('Content-type: application/json; charset=utf-8');
    echo json_encode($jsondata, JSON_FORCE_OBJECT);
}

/**
 * Actualizamos los gastos de Blanco y Negro por departamento y por periodo.
 * 
 * @param int    $departamento departamento
 * @param int    $unidades     unidades
 * @param double $precio       precio
 * @param double $total        total
 * @param string $fecha        año
 * @param string $anio         año
 * @param string $mes          mes
 * 
 * @return json
 */
function insertamosGastosColor($departamento, $unidades, $precio, $total, $fecha, $anio, $mes)
{

    global $mysqlCon, $sentenciaInsertGastosImpresora;
    
    /*definimos el json*/
    $jsondata = array();
    $jsondata["data"] = array();

    $unidadesByN = 0;
    $precioByN = 0;
    $totalByN = 0;
    
    /**
     * Comprobamos si existe el registro para realizar la insercion o la actualizacion.
     * Si existeRegistro devuelve true se realiza la insercion.
     * Si existeRegistro devuelve false se realiza una actualizacion.
     */
    if (existeRegistro($departamento, $anio, $mes)) {
        if ($stmt = $mysqlCon->prepare($sentenciaInsertGastosImpresora)) {
            $stmt->bind_param('isiddidd', $departamento, $fecha, $unidadesByN, $precioByN, $totalByN, $unidades, $precio, $total);
    
            if (!$stmt->execute()) {
                $jsondata["success"] = false;
                $jsondata["message"] = "Fallo la ejecucion: (" . $mysqlCon->errno . ") " . $mysqlCon->error;
                
            } else {
                $jsondata["success"] = true;
                $jsondata["message"] = "Insercion Correcta";
            }
            $stmt->close();
    
        } else {
            $jsondata["success"] = false;
            $jsondata["message"] = "Errormessage: ". $mysqlCon->error;
        }
    } else {
        actualizamosGastosColor($departamento, $unidades, $precio, $total, $anio, $mes);
    }
    
    header('Content-type: application/json; charset=utf-8');
    echo json_encode($jsondata, JSON_FORCE_OBJECT);
}

/**
 * Actualizamos los gastos de Blanco y Negro por departamento y por periodo.
 * 
 * @param int    $departamento departamento
 * @param int    $unidades     unidades
 * @param double $precio       precio
 * @param double $total        total
 * @param string $anio         año
 * @param string $mes          mes
 * 
 * @return json
 */
function actualizamosGastosColor($departamento, $unidades, $precio, $total, $anio, $mes)
{
    
    global $mysqlCon, $sentenciaUpdateGastosImpresoraColor;
    
    if ($stmt = $mysqlCon->prepare($sentenciaUpdateGastosImpresoraColor)) {
        $stmt->bind_param('iddiss', $unidades, $precio, $total, $departamento, $anio, $mes);
    
        if (!$stmt->execute()) {
            $jsondata["success"] = false;
            $jsondata["message"] = "Fallo la ejecucion: (" . $mysqlCon->errno . ") " . $mysqlCon->error;
        } else {
            $jsondata["success"] = true;
            $jsondata["message"] = "Actualizacion Correcta";
        }
    
    } else {
        $jsondata["success"] = false;
        $jsondata["message"] = "Errormessage: ". $mysqlCon->error;
        
    }
    header('Content-type: application/json; charset=utf-8');
    echo json_encode($jsondata, JSON_FORCE_OBJECT);
}

/**
 * Comprobamos si existe un reistro para ese departamento y ese periodo 
 * y devuelve true si no existe y false si existe
 * 
 * @param int    $departamento departamento
 * @param string $anio         año
 * @param string $mes          mes
 * 
 * @return boolean
 */
function existeRegistro($departamento, $anio, $mes)
{
    
    global $mysqlCon, $existeGastoImpresora;
    $insertamos = true;

    /*Prepare Statement*/
    if ($stmt = $mysqlCon->prepare($existeGastoImpresora)) {
        /*Asociacion de parametros*/
        $stmt->bind_param('iss', $departamento, $anio, $mes);
        /*Ejecucion*/
        $stmt->execute();
        
        $stmt->store_result();
        $row_cnt = $stmt->num_rows;

        if ($row_cnt>0) {
            $insertamos = false;
        }
            
        
        /*Cerramos la conexion*/
        $stmt->close();
    } else {
        echo $stmt->error;
    }

    return $insertamos;
}
    
?>