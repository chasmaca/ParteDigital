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
require_once '../../utiles/connectDBUtiles.php';
require_once './../insert/insertLog.php';

$solicitudId = "No DATA";
$accion = "NO DATA";

//Recogemos los valores
if (isset($_POST['solicitudId'])) {
    $solicitudId = $_POST['solicitudId'];
} else {
    $solicitudId = $_GET['solicitudId'];
}

if (isset($_POST['accion'])) {
    $accion = $_POST['accion'];
} else {
    $accion = $_GET['accion'];
}

$path_parts = pathinfo(__FILE__);

/**
 * Realizamos la llamada a la funcion que devolvera los departamentos
 * */
if ($accion == "combo") {
    recuperamosVarios2($solicitudId, $path_parts);
}
    
if ($accion == "tabla") {
    recuperamosVarios2Tabla($solicitudId, $path_parts);
}
    
    
/**
 * Inserta los valores de ByN por solicitud.
 *
 * @param int   $solicitudId The position of the token in the token stack
 * @param array $path_parts  The position of the token in the token stack
 *
 * @return json
 */
function recuperamosVarios2($solicitudId, $path_parts)
{
    /*Declaramos como global la conexion y la query*/
    global $mysqlCon,$varios2Query;
    /*definimos el json*/
    $jsondata = array();
    $jsondata["data"] = array();

    $parametros = "Query:" . $varios2Query . " solicitudId:" . $solicitudId;
    
    try{
        if ($stmt = $mysqlCon->prepare($varios2Query)) {
            $stmt->bind_param("i", $solicitudId);
    
            if ($stmt->execute()) {
                
                /**
                 * Almacenamos el resultSet
                 * */
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
                // crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'recuperamosVarios2', "correcto");   
            } else {
                $jsondata["success"] = false;
                $jsondata["success"] = false;
                crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:' . $mysqlCon->error, "error");   
            }
            
        } else {
            /*Asociamos el correcto funcionamiento al json para comprobar en el js*/
            $jsondata["success"] = false;
            crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:' . $mysqlCon->error, "error");   
        }
    } catch  (Exception $e) {
        $jsondata["success"] = false;
        $jsondata["message"] = "Error en recuperamosVarios2 " . $e;
        crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:' . $e, "error");
    } finally {
        $stmt->close();
        /*Devolvemos el JSON con los datos de la consulta*/
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata, JSON_FORCE_OBJECT);
    }
}

/**
 * Inserta los valores de ByN por solicitud.
 *
 * @param int   $solicitudId The position of the token in the token stack
 * @param array $path_parts  The position of the token in the token stack
 *
 * @return json
 */
function recuperamosVarios2Tabla($solicitudId, $path_parts)
{

    /*Declaramos como global la conexion y la query*/
    global $mysqlCon,$varios2QueryTabla;
    $tipo="";
    $detalle="";
    $descripcion="";
    $precio="";
    $unidades="";
    $precioTotal="";
    /*definimos el json*/
    $jsondata = array();
    $jsondata["data"] = array();
    $parametros = "Query:" . $varios2QueryTabla . " solicitud:" . $solicitudId;

    try {
        if ($stmt = $mysqlCon->prepare($varios2QueryTabla)) {
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
                    array_push($jsondata["data"], $tmp);
                }
                /*Asociamos el correcto funcionamiento al json para comprobar en el js*/
                $jsondata["success"] = true;
            } else {
                $jsondata["success"] = false;
                $jsondata["message"] = "Error en recuperamosVarios2Tabla " . $mysqlCon->error;
                crearLog($path_parts['filename'], __FUNCTION__, $parametros,  'Exception:' . $mysqlCon->error, "error");   
            }
            
        } else {
            /*Asociamos el correcto funcionamiento al json para comprobar en el js*/
            $jsondata["success"] = false;
            $jsondata["message"] = "Error en recuperamosVarios2Tabla " . $mysqlCon->error;
            crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:' . $mysqlCon->error, "error");   
        }
    } catch (Exception $e) {
        $jsondata["success"] = false;
        $jsondata["message"] = "Error en recuperamosVarios2Tabla " . $e;
        crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:' . $e, "error");
    } finally {
        $stmt->close();
        /*Devolvemos el JSON con los datos de la consulta*/
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata, JSON_FORCE_OBJECT);
    }

}
?>