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
require_once "query.php";
require_once '../../utiles/connectDBUtiles.php';

//Declaracion de parametros
$periodo = "";
$departamento = "";
$subdepartamento = "";
$tipo = "";
$mes = "";
$anio = "";
$usuario = "";

//Recogemos los valores
if (isset($_POST['periodoHidden'])) {
    $periodo = $_POST['periodoHidden'];
    $periodoPartido = explode("/", $periodo);
    $mes = $periodoPartido[0];
    $anio = $periodoPartido[1];

}

if (isset($_POST['departamentoHidden'])) {
    $departamento = $_POST['departamentoHidden'];
    if ($departamento === '0') {
        $departamento = '%';
    }
}

if (isset($_POST['subdepartamentoHidden'])) {
    $subdepartamento = $_POST['subdepartamentoHidden'];
    if ($subdepartamento === '0') {
        $subdepartamento = '%';
    }

}

if (isset($_POST['tipoHidden'])) {
    $tipo = $_POST['tipoHidden'];
}

if (isset($_POST['usuarioHidden'])) {
    $usuario = $_POST['usuarioHidden'];
}

if ($tipo === 'global' && $periodo !== "") {
    informeGlobalValidador($mes, $anio, $departamento, $subdepartamento, $usuario);
} elseif ($tipo === 'detalle' && $periodo !== "") {
    informeDetalle($anio, $mes, $departamento, $subdepartamento, $usuario);
}

/**
 * Inserta los valores del trabajo.
 *
 * @param int $mes             The position of the token in the token stack
 * @param int $anio            The number of spaces to adjust the indent by
 * @param int $departamento    The position of the token in the token stack
 * @param int $subdepartamento The number of spaces to adjust the indent by
 * @param int $usuario         The number of spaces to adjust the indent by
 *
 * @return json
 */
function informeGlobalValidador($mes, $anio, $departamento,$subdepartamento, $usuario)
{
    global $mysqlCon, $recuperaValidadorGlobalTodosDpto, $recuperaValidadorGlobalDpto, $recuperaValidadorGlobalDptoSubDpto, $consultaTodasImpresorasDetalle, $consultaTodasMaquinasDetalle;
    $departamentosUsuario = cargarDptoSessionAsArray($usuario);

    /*definimos el json*/
    $jsondata = array();
    $jsondata["data"] = array();
    $contadorLinea = 0;
    $contadorOperacion = 0;

    if ($departamento === '%') {
        $queryEjecucion = $recuperaValidadorGlobalTodosDpto;
    } 
    if ($departamento !== '%' && $subdepartamento === '%') {
        $queryEjecucion = $recuperaValidadorGlobalDpto;
    } 
    if ($departamento !== '%' && $subdepartamento !== '%') {
        $queryEjecucion = $recuperaValidadorGlobalDptoSubDpto;
    } 
    try {
        if ($stmt = $mysqlCon->prepare($queryEjecucion)) {

            if ($departamento === '%') {
                $stmt->bind_param("ssississi", $anio, $mes, $usuario, $anio, $mes, $usuario, $anio, $mes, $usuario);
            } 
            if ($departamento !== '%' && $subdepartamento === '%') {
                $stmt->bind_param("ssississi", $anio, $mes, $departamento, $anio, $mes, $departamento, $anio, $mes, $departamento);
            }
            if ($departamento !== '%' && $subdepartamento !== '%') {
                $stmt->bind_param("ssii", $anio, $mes, $departamento, $subdepartamento);
            } 

            $stmt->execute();
            $stmt->bind_result($ceco, $departamento, $espiral, $encolado, $varios1, $color, $blancoNegro, $varios2);
    
            while ($stmt->fetch()) {
                $tmp = array();
                $tmp["ceco"] = utf8_encode($ceco);
                $tmp["departamentos_desc"] = utf8_encode($departamento);
                $tmp["espiral"] = utf8_encode($espiral);
                $tmp["encolado"] = utf8_encode($encolado);
                $tmp["varios1"] = utf8_encode($varios1);
                $tmp["color"] = utf8_encode($color);
                $tmp["blancoNegro"] = utf8_encode($blancoNegro);
                $tmp["varios2"] = utf8_encode($varios2);
                /*Asociamos el resultado en forma de array en el json*/
                array_push($jsondata["data"], $tmp);
            }
            
            $jsondata["success"] = true;

        } else {
            $jsondata["success"] = false;
            $jsondata["message"] = $queryEjecucion . '-> Anio:' . $anio . ' Mes:'. $mes . ' Departamento:'. $departamento . ' Subepartamento:'. $subdepartamento . ' Usuario:'. $usuario; // phpcs:ignore
        }

        
    } catch (Exception $th) {
        $jsondata["success"] = false;
        $jsondata["message"] = "Exception:" . $th . " Query:" . $queryEjecucion . '-> Anio:' . $anio . ' Mes:'. $mes . ' Departamento:'. $departamento . ' Subepartamento:'. $subdepartamento . ' Usuario:'. $usuario; // phpcs:ignore
    } finally {
        $stmt->close();
        /*Devolvemos el JSON con los datos de la consulta*/
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata, JSON_FORCE_OBJECT);
    }
}

/**
 * Inserta los valores del trabajo.
 *
 * @param int $anio            The position of the token in the token stack
 * @param int $mes             The number of spaces to adjust the indent by
 * @param int $departamento    The position of the token in the token stack
 * @param int $subdepartamento The number of spaces to adjust the indent by
 * @param int $usuario         The number of spaces to adjust the indent by
 *
 * @return json
 */
function informeDetalle($anio, $mes, $departamento, $subdepartamento, $usuario)
{

    global $mysqlCon, $recuperaInformeDetalleValidaAuth, $recuperaInformeDetalleValidaAuthDpto, $recuperaInformeDetalleValidaAuthDptoSub;
    $contadorLinea = 0;
    $contadorOperacion = 0;
    $departamentosUsuario = cargarDptoSessionAsArray($usuario);
    

    /*definimos el json*/
    $jsondata = array();
    $jsondata["data"] = array();

    if ($departamento === '%') {
        $queryEjecucion = $recuperaInformeDetalleValidaAuth;
    }
    
    if ($departamento !== '%' && $subdepartamento === '%') {
        $queryEjecucion = $recuperaInformeDetalleValidaAuthDpto;
    } 
    if ($departamento !== '%' && $subdepartamento !== '%') {
        $queryEjecucion = $recuperaInformeDetalleValidaAuthDptoSub;
    } 
    try {
        if ($stmt = $mysqlCon->prepare($queryEjecucion)) {

            if ($departamento === '%') {
                $stmt->bind_param("ssississi", $anio, $mes, $usuario, $anio, $mes, $usuario, $anio, $mes, $usuario);
            }
            if ($departamento !== '%' && $subdepartamento === '%') {
                $stmt->bind_param("ssississi", $anio, $mes, $departamento, $anio, $mes, $departamento, $anio, $mes, $departamento);
            } 
            if ($departamento !== '%' && $subdepartamento !== '%') {
                $stmt->bind_param("ssii", $anio, $mes, $departamento, $subdepartamento);
            }

            /*Ejecucion*/
            if ($stmt->execute()) {
                $stmt->bind_result(
                    $solicitud,
                    $ceco,
                    $departamento,
                    $subdepartamento,
                    $treintabarra,
                    $nombre,
                    $apellidos,
                    $descripcion,
                    $fecha,
                    $espiral,
                    $encolado,
                    $varios1,
                    $color,
                    $blancoNegro,
                    $varios2
                );
    
                while ($stmt->fetch()) {
                    
                    $tmp = array();

                    $tmp["solicitud"] = $solicitud;
                    $tmp["ceco"] = $ceco;
                    $tmp["departamento"] = utf8_encode($departamento);
                    $tmp["subdepartamento"] = utf8_encode($subdepartamento);
                    $tmp["treintabarra"] = utf8_encode($treintabarra);
                    $tmp["nombre"] = utf8_encode($nombre) . " " . utf8_encode($apellidos);
                    $tmp["descripcion"] = utf8_encode($descripcion);
                    $tmp["fecha"] = date("d-m-Y", strtotime($fecha));
                    $tmp["espiral"] = $espiral;
                    $tmp["encolado"] = $encolado;
                    $tmp["varios1"] = $varios1;
                    $tmp["color"] = $color;
                    $tmp["blancoNegro"] = $blancoNegro;
                    $tmp["varios2"] = $varios2;

                    /*Asociamos el resultado en forma de array en el json*/
                    array_push($jsondata["data"], $tmp);
                }
                /*Asociamos el correcto funcionamiento al json para comprobar en el js*/
                $jsondata["success"] = true;
            } else {
                $jsondata["success"] = false;
                $jsondata["message"] = $queryEjecucion . '-> Anio:' . $anio . ' Mes:'. $mes . ' Departamento:'. $departamento . ' Subepartamento:'. $subdepartamento . ' Usuario:'. $usuario; // phpcs:ignore
            }
        } else {
            $jsondata["success"] = false;
            $jsondata["message"] = $queryEjecucion . '-> Anio:' . $anio . ' Mes:'. $mes . ' Departamento:'. $departamento . ' Subepartamento:'. $subdepartamento . ' Usuario:'. $usuario; // phpcs:ignore
        }

    } catch (Exception $th) {
        $jsondata["success"] = false;
        $jsondata["message"] = "Exception:" . $th . " Query:" . $queryEjecucion . '-> Anio:' . $anio . ' Mes:'. $mes . ' Departamento:'. $departamento . ' Subepartamento:'. $subdepartamento . ' Usuario:'. $usuario; // phpcs:ignore
        /*Devolvemos el JSON con los datos de la consulta*/
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata, JSON_FORCE_OBJECT);
    }
    /*Devolvemos el JSON con los datos de la consulta*/
    header('Content-type: application/json; charset=utf-8');
    echo json_encode($jsondata, JSON_FORCE_OBJECT);
    
}

/**
 * Inserta los valores del trabajo.
 *
 * @param int $departamento The position of the token in the token stack
 *
 * @return array
 */
function recuperaSubXDpto($departamento)
{
    global $recuperaSubdptoXDpto,$mysqlCon;
    $valores = "";
    $valoresArray =  array();
    
   
    /*Prepare Statement*/
    if ($stmt = $mysqlCon->prepare($recuperaSubdptoXDpto)) {

        /*Asociacion de parametros*/
        $stmt->bind_param('i', $departamento);

        /*Ejecucion*/
        $stmt->execute();

        /*Asociacion de resultados*/
        /*Id Departamento, Id SubDepartamento, Descripcion SubDepartamento, treintabarra*/
        $stmt->bind_result($col1, $col2, $col3, $col4);

        /*Recogemos el resultado en la variable*/
        while ($stmt->fetch()) {
            $valores = array($col1, $col2, $col3, $col4);
            array_push($valoresArray, $valores);
            
        }

        /*Cerramos la conexion*/
        $stmt->close();

    } else {
        echo $stmt->error;
    }
    
    return $valoresArray;
}

/**
 * Inserta los valores del trabajo.
 *
 * @param int $usuario The number of spaces to adjust the indent by
 *
 * @return string
 */
function cargarDptoSessionAsArray($usuario)
{

    global $recuperaDptoXAutorizadorArray,$mysqlCon;

    $valores = "";
    /*Prepare Statement*/
    if ($stmt = $mysqlCon->prepare($recuperaDptoXAutorizadorArray)) {
        /*Asociacion de parametros*/
        $stmt->bind_param('i', $usuario);
        /*Ejecucion*/
        $stmt->execute();
        /*Asociacion de resultados*/
        $stmt->bind_result($col1);
        /*Recogemos el resultado en la variable*/
        while ($stmt->fetch()) {
            if ($valores == "") {
                $valores = $col1;
            } else {
                $valores .= "," . $col1;
            }
        }
        /*Cerramos la conexion*/
    
    } else {
        echo $stmt->error;
    }
    
    return $valores;
}

/**
 * Inserta los valores del trabajo.
 *
 * @param int $usuario The number of spaces to adjust the indent by
 * @param int $dpto    The number of spaces to adjust the indent by
 *
 * @return string
 */
function cargarSubDptoXDptoAsArray($usuario, $dpto)
{

    global $recuperaSubdptoXDpto,$mysqlCon;

    $valores = "";
    /*Prepare Statement*/
    if ($stmt = $mysqlCon->prepare($recuperaSubdptoXDpto)) {
        /*Asociacion de parametros*/
        $stmt->bind_param('i', $dpto);
        /*Ejecucion*/
        $stmt->execute();
        /*Asociacion de resultados*/
        $stmt->bind_result($col1, $col2, $col3, $col4);
        /*Recogemos el resultado en la variable*/
        while ($stmt->fetch()) {
            if ($valores == "") {
                $valores = $col2;
            } else {
                $valores .= "," . $col2;
            }
        }
        /*Cerramos la conexion*/

        $stmt->close();
    } else {
        echo $stmt->error;
    }

    return $valores;
}
