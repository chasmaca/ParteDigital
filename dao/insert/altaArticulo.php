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
require_once './../../utiles/connectDBUtiles.php';
require_once './../select/query.php';
require_once './inserciones.php';
require_once './insertLog.php';

$tipo = "";
$nombre = "";
$precio = "";

//Recogemos los valores
if (isset($_POST['tipoHidden'])) {
    $tipo = utf8_decode(trim($_POST['tipoHidden']));
}

if (isset($_POST['nombreHidden'])) {
    $nombre = utf8_decode(trim($_POST['nombreHidden']));
}

if (isset($_POST['precioHidden'])) {
    $precio = utf8_decode(trim($_POST['precioHidden']));
}

altaArticuloFunction($tipo, $nombre, $precio);

/**
 * Adjust the indent of a code block.
 *
 * @param int    $tipo   The position of the token in the token stack
 * @param string $nombre The position of the token in the token stack
 * @param double $precio The position of the token in the token stack
 * 
 * @return json
 */
function altaArticuloFunction($tipo, $nombre, $precio)
{
    global $guardaArticulo, $mysqlCon;
    /*definimos el json*/
    $jsondata = array();
    $jsondata["data"] = array();
    $parametros = $guardaArticulo . " " . "tipo:" . $tipo . " nombre:" . $nombre . " precio:" . $precio;
    $path_parts = pathinfo(__FILE__);
    try {
            
        if ($tipo === "" || $nombre ==="" || $precio ==="") {
            $jsondata["success"] = false;
            $jsondata["message"] = "Faltan por enviar Parámetros";
            crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:Faltan Parametros', "error");
        } else {
            if ($stmt = $mysqlCon->prepare($guardaArticulo)) {
                $detalle = recuperaMaximoPorTipo($mysqlCon, $tipo);
                $stmt->bind_param('iiss', $tipo, $detalle, $nombre, $precio);
                if ($stmt->execute()) {
                    $jsondata["success"] = true;
                    $jsondata["message"] = "Articulo Insertado";
                    crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Articulo creado Correctamente', "correcto");
                } else {
                    $jsondata["success"] = false;
                    $jsondata["message"] = "Error Insertando Articulo" . $mysqlCon->error;
                    crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:' . $mysqlCon->error, "error");
                }
            } else {
                $jsondata["success"] = false;
                $jsondata["message"] = "Error Insertando Articulo" . $mysqlCon->error;
                crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:' . $mysqlCon->error, "error");
            }
        }
    } catch (Exception $e) {
        $jsondata["success"] = false;
        $jsondata["message"] = "Error Insertando Articulo" . $e;
        crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:' . $e, "error");
    } finally {
        $stmt->close();
        /*Devolvemos el JSON con los datos de la consulta*/
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata, JSON_FORCE_OBJECT);
    }
}

/**
 * Adjust the indent of a code block.
 *
 * @param conn $mysqlCon The position of the token in the token stack
 * @param int  $tipo     The position of the token in the token stack
 *
 * @return int
 */
function recuperaMaximoPorTipo($mysqlCon,$tipo)
{
    global $recuperaMaxDetalle;
    $path_parts = pathinfo(__FILE__);
    $parametros = $recuperaMaxDetalle . " " . "tipo:" . $tipo;
    $valor = 1;
    $detalle = "";
    try {
        if ($stmt = $mysqlCon->prepare($recuperaMaxDetalle)) {
            $detalle = recuperaMaximoPorTipo($mysqlCon, $tipo);
            $stmt->bind_param('i', $tipo);
            /*Ejecucion de la consulta*/
            if ($stmt->execute()) {
                /*Almacenamos el resultSet*/
                $stmt->bind_result($detalle);
                while ($stmt->fetch()) {
                    $valor = $detalle;
                }
                if ($valor == null) {
                    $valor = 1;
                }  
            } else {
                $jsondata["success"] = false;
                $jsondata["message"] = "Error Insertando recuperaMaximoPorTipo " . $mysqlCon->error;
                crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:' . $mysqlCon->error, "error");
            }
        } else {
            $jsondata["success"] = false;
            $jsondata["message"] = "Error Insertando recuperaMaximoPorTipo " . $mysqlCon->error;
            crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:' . $mysqlCon->error, "error");
        }
    } catch (Exception $e) {
        $jsondata["success"] = false;
        $jsondata["message"] = "Error Insertando recuperaMaximoPorTipo " . $e;
        crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:' . $e, "error");
    } finally {
        $stmt->close();
        return $valor;
    }
}