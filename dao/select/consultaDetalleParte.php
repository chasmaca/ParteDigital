<?php
/**
 * Class: cerrarMes.php
 *
 * Cierre del mes contable
 * php version 7.3.28
 * 
 * @category Update
 * @package  DaoUpdate
 * @author   Jesus Madrazo <chasmaca@gmail.com>
 * @license  http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version  GIT: <Initial Import>
 * @link     https://www.elpartedigital.com/
 */
require_once 'query.php';
require_once './../../utiles/connectDBUtiles.php';
require_once './../insert/insertLog.php';

//Declaracion de parametros
$parte = "";

//Recogemos los valores
if (isset($_POST['valor'])) {
    $parte = $_POST['valor'];
    cargarDetalleParte($parte);
}

/**
 * Funcion para la recuperacion de todos los datos de gastos de cierre en impresoras.
 * Las consultas se haran en funcion del periodo del combo del html
 *
 * @param int $parte Id de Solicitud
 *
 * @return json
 */
function cargarDetalleParte($parte)
{

    global $consultaDetalleParte, $mysqlCon;

    /*definimos el json*/
    $jsondata = array();
    $jsondata["data"] = array();

    $parametros = "Query:" . $consultaDetalleParte . " id:" . $parte;

    try {
        /*Prepare Statement*/
        if ($stmt = $mysqlCon->prepare($consultaDetalleParte)) {
            $stmt->bind_param("i", $parte);

            /*Ejecucion*/
            $stmt->execute();
            /*Almacenamos el resultSet*/
            $stmt->bind_result($nombre, $apellidos, $tipo, $detalle, $unidades, $precio, $departamento, $ceco, $subdepartamento, $treinta, $fecha);

            /*Incluimos las lineas de la consulta en el json a devolver*/
            while ($stmt->fetch()) {
                $tmp = array();
                $tmp["nombre"] = utf8_encode($nombre) . " " . utf8_encode($apellidos);
                $tmp["tipo"] = utf8_encode($tipo);
                $tmp["detalle"] = utf8_encode($detalle);
                $tmp["unidades"] = utf8_encode($unidades);
                $tmp["precio"] = utf8_encode($precio);
                $tmp["departamento"] = utf8_encode($departamento);
                $tmp["ceco"] = utf8_encode($ceco);
                $tmp["subdepartamento"] = utf8_encode($subdepartamento);
                $tmp["treinta"] = utf8_encode($treinta);
                $tmp["fecha"] = utf8_encode($fecha);

                array_push($jsondata["data"], $tmp);
            }
            /*Asociamos el correcto funcionamiento al json para comprobar en el js*/
            $jsondata["success"] = true;
        } else {
            /*Llegamos aqui con error, asociamos false para identificarlo en el js*/
            $jsondata["success"] = false;
            $jsondata["message"] = "Problemas al cargar la select de subdepartamento:". $mysqlCon->error;
            crearLog(__FILE__, __FUNCTION__, $parametros, 'Error:' . $mysqlCon->error, "error");
        }
    } catch (Exception $e) {
        $jsondata["success"] = false;
        $jsondata["message"] = "Problemas al cargar la select de subdepartamento:". $e;
        crearLog(__FILE__, __FUNCTION__, $parametros, 'Exception:' . $e, "error");
    } finally {
        $stmt->close();
        /*Devolvemos el JSON con los datos de la consulta*/
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata, JSON_FORCE_OBJECT);
    }
}