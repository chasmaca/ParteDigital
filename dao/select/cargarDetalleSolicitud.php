<?php
/**
 * Class: cargarDetalleSolicitud.php
 *
 * Cierre del mes contable
 * php version 7.3.28
 * 
 * @category Insert
 * @package  DaoInsert
 * @author   Jesus Madrazo <chasmaca@gmail.com>
 * @license  http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version  GIT: <Initial Import>
 * @link     https://www.elpartedigital.com/
 */
require_once './../../utiles/connectDBUtiles.php';
require_once './../select/query.php';
require_once './../insert/insertLog.php';

global $mysqlCon;


/*definimos el json*/
$jsondata = array();
$jsondata["data"] = $jsondata["cabecera"] = $jsondata["varios1"] = $jsondata["color"] = array();
$jsondata["encolado"] = $jsondata["encuadernacion"] = $jsondata["byn"]  = $jsondata["varios2"] = $jsondata["varios2Extra"] = array();


$solicitud = "";

//Recogemos los valores
if (isset($_POST['solicitud'])) {
    $solicitud = utf8_decode(trim($_POST['solicitud']));
}

//Recorremos la solicitud
$jsondataCab = cargaCabeceraSolicitud($solicitud, $mysqlCon);

$jsondataVarios1 = cargaDetalleVarios1Solicitud($solicitud, $jsondataCab[0]['fecha'], $mysqlCon);
$jsondataColor = cargaDetalleColorSolicitud($solicitud, $jsondataCab[0]['fecha'], $mysqlCon);
$jsondataEncolado = cargaDetalleEncoladoSolicitud($solicitud, $jsondataCab[0]['fecha'], $mysqlCon);
$jsondataEncuadernacion = cargaDetalleEncuadernacionSolicitud($solicitud, $jsondataCab[0]['fecha'], $mysqlCon);
$jsondataByN = cargaDetalleByNSolicitud($solicitud, $jsondataCab[0]['fecha'], $mysqlCon);
$jsondataVarios2 = cargaDetalleVarios2Solicitud($solicitud, $jsondataCab[0]['fecha'], $mysqlCon);
$jsondataVarios2Extra = cargaDetalleVarios2ExtraSolicitud($solicitud, $jsondataCab[0]['fecha'], $mysqlCon);


array_push($jsondata["cabecera"], $jsondataCab);
array_push($jsondata["varios1"], $jsondataVarios1);
array_push($jsondata["color"], $jsondataColor);
array_push($jsondata["encolado"], $jsondataEncolado);
array_push($jsondata["encuadernacion"], $jsondataEncuadernacion);
array_push($jsondata["byn"], $jsondataByN);
array_push($jsondata["varios2"], $jsondataVarios2);
array_push($jsondata["varios2Extra"], $jsondataVarios2Extra);

devuelveJson($jsondata);


/**
 * Cargamos la cabecera de una solicitud.
 *
 * @param int  $solicitud El Id de solicitud deseado
 * @param conn $mysqlCon  La conexion
 *
 * @return json
 */
function cargaCabeceraSolicitud($solicitud, $mysqlCon)
{
    global $cabeceraDatosQuery;
    $nombre = $apellido = $fecha = $departamento = $subdepartamento = $ceco = $treinta = $departamentoId = $subdepartamentoId = $descripcion = "";
    /*definimos el json*/
    $jsondataCabecera = array();
    $path_parts = pathinfo(__FILE__);
    $parametros = "Query:" . $cabeceraDatosQuery . 
    " solicitud:" . $solicitud;
    try{

        if ($stmt = $mysqlCon->prepare($cabeceraDatosQuery)) {
            $stmt->bind_param("i", $solicitud);
            /*Ejecucion*/
            $stmt->execute();
            /*Almacenamos el resultSet*/
            /*s1.nombre_solicitante, s1.apellido_solicitante, s1.fecha_validacion, d1.departamentos_desc, sd1.subdepartamento_desc*/
            $stmt->bind_result($nombre, $apellido, $fecha, $departamento, $subdepartamento, $ceco, $treinta, $departamentoId, $subdepartamentoId, $descripcion);
    
            /*Incluimos las lineas de la consulta en el json a devolver*/
            while ($stmt->fetch()) {
                $tmp = array();
                $tmp["nombre"] = utf8_encode($nombre) . ' ' . utf8_encode($apellido);
                $tmp["fecha"] = utf8_encode($fecha);
                $tmp["departamento"] = utf8_encode($departamento);
                $tmp["subdepartamento"] = utf8_encode($subdepartamento);
                $tmp["ceco"] = utf8_encode($ceco);
                $tmp["treinta"] = utf8_encode($treinta);
                $tmp["departamentoId"] = utf8_encode($departamentoId);
                $tmp["subdepartamentoId"] = utf8_encode($subdepartamentoId);
                $tmp["descripcion"] = utf8_encode($descripcion);
                
                array_push($jsondataCabecera, $tmp);
            }
            /*Asociamos el correcto funcionamiento al json para comprobar en el js*/
            $jsondataCabecera["success"] = true;
        }

    } catch (Exception $e) {
        $jsondataCabecera["success"] = false;
        $jsondataCabecera["message"] = "Error recuperando la cabecera de la solicitud " . $e;
        crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:' . $e, "error");
    } finally {
        $stmt->close();
        return $jsondataCabecera;
        /*Devolvemos el JSON con los datos de la consulta
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondataCabecera, JSON_FORCE_OBJECT);*/
    }

}


/**
 * Cargamos la cabecera de una solicitud.
 *
 * @param int    $solicitud El Id de solicitud deseado
 * @param string $fecha     El Id de solicitud deseado
 * @param conn   $mysqlCon  La conexion
 *
 * @return json
 */
function cargaDetalleVarios1Solicitud($solicitud, $fecha, $mysqlCon) 
{
    global $variosUnoDatosQueryNoFecha;
    $anio = substr($fecha, 0, 4); 
    $mes = substr($fecha, 5, 2); 
    $tipo = $detalle = $descripcion = $precio = $unidades = $precioTotal = "";
    /*definimos el json*/
    $jsondataVarios1 = array();
    $jsondataVarios1["data"] = array();
    $path_parts = pathinfo(__FILE__);
    $parametros = "Query:" . $variosUnoDatosQueryNoFecha . 
    " solicitud:" . $solicitud;
    try{
        if ($stmt = $mysqlCon->prepare($variosUnoDatosQueryNoFecha)) {
            $stmt->bind_param("i", $solicitud);
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
                    array_push($jsondataVarios1, $tmp);
                }
                $jsondataVarios1["success"] = true;
            } else {
                $jsondataVarios1["success"] = false;
                $jsondataVarios1["message"] = "Error recuperando la cabecera de la solicitud " . $mysqlCon->error;
                crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:' . $mysqlCon->error, "error");
            }
        } else {
            $jsondataVarios1["success"] = false;
            $jsondataVarios1["message"] = "Error recuperando la cabecera de la solicitud " . $mysqlCon->error;
            crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:' . $mysqlCon->error, "error");
        }
    } catch (Exception $e) {
        $jsondataVarios1["success"] = false;
        $jsondataVarios1["message"] = "Error recuperando la cabecera de la solicitud " . $e;
        crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:' . $e, "error");
    } finally {
        $stmt->close();
        return $jsondataVarios1;
        /*Devolvemos el JSON con los datos de la consulta
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondataVarios1, JSON_FORCE_OBJECT);*/
    }
}

/**
 * Cargamos la cabecera de una solicitud.
 *
 * @param int    $solicitud El Id de solicitud deseado
 * @param string $fecha     El Id de solicitud deseado
 * @param conn   $mysqlCon  La conexion
 *
 * @return json
 */
function cargaDetalleColorSolicitud($solicitud, $fecha, $mysqlCon) 
{
    global $colorDatosQueryNoFecha;
    $anio = substr($fecha, 0, 4); 
    $mes = substr($fecha, 5, 2); 
    $tipo = $detalle = $descripcion = $precio = $unidades = $precioTotal = "";
    /*definimos el json*/
    $jsondataColor = array();
    $jsondataColor["data"] = array();
    $path_parts = pathinfo(__FILE__);
    $parametros = "Query:" . $colorDatosQueryNoFecha . 
    " solicitud:" . $solicitud;
    try{
        if ($stmt = $mysqlCon->prepare($colorDatosQueryNoFecha)) {
            $stmt->bind_param("i", $solicitud);
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
                    /*Asociamos el resultado en forma de array en el json*/
                    array_push($jsondataColor, $tmp);
                }
                /*Asociamos el correcto funcionamiento al json para comprobar en el js*/
                $jsondataColor["success"] = true;
            } else {
                /*Asociamos el correcto funcionamiento al json para comprobar en el js*/
                $jsondataColor["success"] = false;
            }
        } else {
            /*Asociamos el correcto funcionamiento al json para comprobar en el js*/
            $jsondataColor["success"] = false;
        }
    } catch (Exception $e) {
        $jsondataColor["success"] = false;
        $jsondataColor["message"] = "Error recuperando la cabecera de la solicitud " . $e;
        crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:' . $e, "error");
    } finally {
        $stmt->close();
        return $jsondataColor;
        /*Devolvemos el JSON con los datos de la consulta
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondataColor, JSON_FORCE_OBJECT);*/
    }
}

/**
 * Cargamos la cabecera de una solicitud.
 *
 * @param int    $solicitud El Id de solicitud deseado
 * @param string $fecha     El Id de solicitud deseado
 * @param conn   $mysqlCon  La conexion
 *
 * @return json
 */
function cargaDetalleEncoladoSolicitud($solicitud, $fecha, $mysqlCon) 
{
    global $encoladoDatosQueryNoFecha;
    $anio = substr($fecha, 0, 4); 
    $mes = substr($fecha, 5, 2); 
    $tipo = $detalle = $descripcion = $precio = $unidades = $precioTotal = "";
    /*definimos el json*/
    $jsondataEncolado = array();
    $jsondataEncolado["data"] = array();
    $path_parts = pathinfo(__FILE__);
    $parametros = "Query:" . $encoladoDatosQueryNoFecha . 
    " solicitud:" . $solicitud;
    try{
        if ($stmt = $mysqlCon->prepare($encoladoDatosQueryNoFecha)) {
            $stmt->bind_param("i", $solicitud);
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
                    /*Asociamos el resultado en forma de array en el json*/
                    array_push($jsondataEncolado, $tmp);
                }
                /*Asociamos el correcto funcionamiento al json para comprobar en el js*/
                $jsondataEncolado["success"] = true;
            } else {
                /*Asociamos el correcto funcionamiento al json para comprobar en el js*/
                $jsondataEncolado["success"] = false;
            }
        } else {
            /*Asociamos el correcto funcionamiento al json para comprobar en el js*/
            $jsondataEncolado["success"] = false;
        }
    } catch (Exception $e) {
        $jsondataEncolado["success"] = false;
        $jsondataEncolado["message"] = "Error recuperando la cabecera de la solicitud " . $e;
        crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:' . $e, "error");
    } finally {
        $stmt->close();
        return $jsondataEncolado;
        /*Devolvemos el JSON con los datos de la consulta
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondataEncolado, JSON_FORCE_OBJECT);*/
    }
}

/**
 * Cargamos la cabecera de una solicitud.
 *
 * @param int    $solicitud El Id de solicitud deseado
 * @param string $fecha     El Id de solicitud deseado
 * @param conn   $mysqlCon  La conexion
 *
 * @return json
 */
function cargaDetalleEncuadernacionSolicitud($solicitud, $fecha, $mysqlCon) 
{
    global $encuadernacionDatosQueryNoFecha;
    $anio = substr($fecha, 0, 4); 
    $mes = substr($fecha, 5, 2); 
    $tipo = $detalle = $descripcion = $precio = $unidades = $precioTotal = "";
    /*definimos el json*/
    $jsondataEncuadernacion = array();
    $jsondataEncuadernacion["data"] = array();
    $path_parts = pathinfo(__FILE__);
    $parametros = "Query:" . $encuadernacionDatosQueryNoFecha . 
    " solicitud:" . $solicitud;
    try{
        if ($stmt = $mysqlCon->prepare($encuadernacionDatosQueryNoFecha)) {
            $stmt->bind_param("i", $solicitud);
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
                    /*Asociamos el resultado en forma de array en el json*/
                    array_push($jsondataEncuadernacion, $tmp);
                }
                /*Asociamos el correcto funcionamiento al json para comprobar en el js*/
                $jsondataEncuadernacion["success"] = true;
            } else {
                /*Asociamos el correcto funcionamiento al json para comprobar en el js*/
                $jsondataEncuadernacion["success"] = false;
            }
        } else {
            /*Asociamos el correcto funcionamiento al json para comprobar en el js*/
            $jsondataEncuadernacion["success"] = false;
        }
    } catch (Exception $e) {
        $jsondataEncuadernacion["success"] = false;
        $jsondataEncuadernacion["message"] = "Error recuperando la cabecera de la solicitud " . $e;
        crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:' . $e, "error");
    } finally {
        $stmt->close();
        return $jsondataEncuadernacion;
        /*Devolvemos el JSON con los datos de la consulta
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondataEncolado, JSON_FORCE_OBJECT);*/
    }
}

/**
 * Cargamos la cabecera de una solicitud.
 *
 * @param int    $solicitud El Id de solicitud deseado
 * @param string $fecha     El Id de solicitud deseado
 * @param conn   $mysqlCon  La conexion
 *
 * @return json
 */
function cargaDetalleByNSolicitud($solicitud, $fecha, $mysqlCon) 
{
    global $blancoYNegroDatosQueryNoFecha;
    $anio = substr($fecha, 0, 4); 
    $mes = substr($fecha, 5, 2); 
    $tipo = $detalle = $descripcion = $precio = $unidades = $precioTotal = "";
    /*definimos el json*/
    $jsondataByN = array();
    $jsondataByN["data"] = array();
    $path_parts = pathinfo(__FILE__);
    $parametros = "Query:" . $blancoYNegroDatosQueryNoFecha . 
    " solicitud:" . $solicitud;
    try{
        if ($stmt = $mysqlCon->prepare($blancoYNegroDatosQueryNoFecha)) {
            $stmt->bind_param("i", $solicitud);
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
                    /*Asociamos el resultado en forma de array en el json*/
                    array_push($jsondataByN, $tmp);
                }
                /*Asociamos el correcto funcionamiento al json para comprobar en el js*/
                $jsondataByN["success"] = true;
            } else {
                /*Asociamos el correcto funcionamiento al json para comprobar en el js*/
                $jsondataByN["success"] = false;
            }
        } else {
            /*Asociamos el correcto funcionamiento al json para comprobar en el js*/
            $jsondataByN["success"] = false;
        }
    } catch (Exception $e) {
        $jsondataByN["success"] = false;
        $jsondataByN["message"] = "Error recuperando la cabecera de la solicitud " . $e;
        crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:' . $e, "error");
    } finally {
        $stmt->close();
        return $jsondataByN;
        /*Devolvemos el JSON con los datos de la consulta
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondataByN, JSON_FORCE_OBJECT);*/
    }
}

/**
 * Cargamos la cabecera de una solicitud.
 *
 * @param int    $solicitud El Id de solicitud deseado
 * @param string $fecha     El Id de solicitud deseado
 * @param conn   $mysqlCon  La conexion
 *
 * @return json
 */
function cargaDetalleVarios2Solicitud($solicitud, $fecha, $mysqlCon) 
{
    global $varios2DatosQueryNoFecha;
    $anio = substr($fecha, 0, 4); 
    $mes = substr($fecha, 5, 2); 
    $tipo = $detalle = $descripcion = $precio = $unidades = $precioTotal = "";
    /*definimos el json*/
    $jsondataVarios2 = array();
    $jsondataVarios2["data"] = array();
    $path_parts = pathinfo(__FILE__);
    $parametros = "Query:" . $varios2DatosQueryNoFecha . 
    " solicitud:" . $solicitud;
    try{
        if ($stmt = $mysqlCon->prepare($varios2DatosQueryNoFecha)) {
            $stmt->bind_param("i", $solicitud);
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
                    /*Asociamos el resultado en forma de array en el json*/
                    array_push($jsondataVarios2, $tmp);
                }
                /*Asociamos el correcto funcionamiento al json para comprobar en el js*/
                $jsondataVarios2["success"] = true;
            } else {
                /*Asociamos el correcto funcionamiento al json para comprobar en el js*/
                $jsondataVarios2["success"] = false;
            }
        } else {
            /*Asociamos el correcto funcionamiento al json para comprobar en el js*/
            $jsondataVarios2["success"] = false;
        }
    } catch (Exception $e) {
        $jsondataVarios2["success"] = false;
        $jsondataVarios2["message"] = "Error recuperando la cabecera de la solicitud " . $e;
        crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:' . $e, "error");
    } finally {
        $stmt->close();
        return $jsondataVarios2;
        /*Devolvemos el JSON con los datos de la consulta
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondataEncolado, JSON_FORCE_OBJECT);*/
    }
}

/**
 * Cargamos la cabecera de una solicitud.
 *
 * @param int    $solicitud El Id de solicitud deseado
 * @param string $fecha     El Id de solicitud deseado
 * @param conn   $mysqlCon  La conexion
 *
 * @return json
 */
function cargaDetalleVarios2ExtraSolicitud($solicitud, $fecha, $mysqlCon) 
{
    global $varios2DatosExtraQueryNoFecha;
    $anio = substr($fecha, 0, 4); 
    $mes = substr($fecha, 5, 2); 
    $tipo = $detalle = $descripcion = $precio = $unidades = $precioTotal = "";
    /*definimos el json*/
    $jsondataVarios2Extra = array();
    $jsondataVarios2Extra["data"] = array();
    $path_parts = pathinfo(__FILE__);
    $parametros = "Query:" . $varios2DatosExtraQueryNoFecha . 
    " solicitud:" . $solicitud;
    try{
        if ($stmt = $mysqlCon->prepare($varios2DatosExtraQueryNoFecha)) {
            $stmt->bind_param("i", $solicitud);
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
                    /*Asociamos el resultado en forma de array en el json*/
                    array_push($jsondataVarios2Extra, $tmp);
                }
                /*Asociamos el correcto funcionamiento al json para comprobar en el js*/
                $jsondataVarios2Extra["success"] = true;
            } else {
                /*Asociamos el correcto funcionamiento al json para comprobar en el js*/
                $jsondataVarios2Extra["success"] = false;
            }
        } else {
            /*Asociamos el correcto funcionamiento al json para comprobar en el js*/
            $jsondataVarios2Extra["success"] = false;
        }
    } catch (Exception $e) {
        $jsondataVarios2Extra["success"] = false;
        $jsondataVarios2Extra["message"] = "Error recuperando la cabecera de la solicitud " . $e;
        crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:' . $e, "error");
    } finally {
        $stmt->close();
        return $jsondataVarios2Extra;
        /*Devolvemos el JSON con los datos de la consulta
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondataEncolado, JSON_FORCE_OBJECT);*/
    }
}

/**
 * Cargamos la cabecera de una solicitud.
 *
 * @param $jsonData El Id de solicitud deseado
 *
 * @return json
 */
function devuelveJson($jsonData)
{
    /*Devolvemos el JSON con los datos de la consulta*/
    header('Content-type: application/json; charset=utf-8');
    echo json_encode($jsonData, JSON_FORCE_OBJECT);
}