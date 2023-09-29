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
$usuario = "";

//Recogemos los valores
if (isset($_POST['usuario'])) {
    $usuario = $_POST['usuario'];
    cargarSolicitudesPendientes($usuario);
   

}

/**
 * Inserta los valores de ByN por solicitud.
 *
 * @param int $usuario The position of the token in the token stack
 *
 * @return json
 */
function cargarSolicitudesPendientes($usuario) 
{
    global $queryGastosMesActual, $mysqlCon;

    /*definimos el json*/
    $jsondata = array();
    $jsondata["data"] = array();

    $parametros = "Query:" . $queryGastosMesActual . " usuario:" . $usuario;
    try {
        /*Prepare Statement*/
        if ($stmt = $mysqlCon->prepare($queryGastosMesActual)) {
            $stmt->bind_param("i", $usuario);

            /*Ejecucion*/
            $stmt->execute();
            /*Almacenamos el resultSet*/
            
            $stmt->bind_result(
                $solicitud_id, $departamentos_desc, $subdepartamento_desc, $nombre_solicitante,
                $apellidos_solicitante, $descripcion_solicitante, $autorizador_id, $nombre, $apellido,
                $status_desc, $precioVarios, $precioByN, $precioColor, $precioEncuadernacion
            );

            /*Incluimos las lineas de la consulta en el json a devolver*/
            while ($stmt->fetch()) {
                $tmp = array();
                $tmp["solicitud_id"] = utf8_encode($solicitud_id);
                $tmp["departamentos_desc"] = utf8_encode($departamentos_desc);
                $tmp["subdepartamento_desc"] = utf8_encode($subdepartamento_desc);
                $tmp["nombre_solicitante"] = utf8_encode($nombre_solicitante);
                $tmp["apellidos_solicitante"] = utf8_encode($apellidos_solicitante);
                $tmp["descripcion_solicitante"] = utf8_encode($descripcion_solicitante);
                $tmp["autorizador_id"] = utf8_encode($autorizador_id);
                $tmp["nombre"] = utf8_encode($nombre);
                $tmp["apellido"] = utf8_encode($apellido);
                $tmp["status_desc"] = utf8_encode($status_desc);
                $tmp["precioVarios"] = utf8_encode($precioVarios);
                $tmp["precioByN"] = utf8_encode($precioByN);
                $tmp["precioColor"] = utf8_encode($precioColor);
                $tmp["precioEncuadernacion"] = utf8_encode($precioEncuadernacion);

                array_push($jsondata["data"], $tmp);
            }
            /*Asociamos el correcto funcionamiento al json para comprobar en el js*/
            $jsondata["success"] = true;
        } else {
            /*Llegamos aqui con error, asociamos false para identificarlo en el js*/
            $jsondata["success"] = false;
            $jsondata["message"] = "Error recuperando informe Autorizador Mes Curso " . $mysqlCon->error;
            crearLog(__FILE__, __FUNCTION__, $parametros, 'Exception:' . $mysqlCon->error, "error"); 
        }
    } catch (Exception $e) {
        $jsondata["success"] = false;
        $jsondata["message"] = "Error recuperando informe Autorizador Mes Curso " . $e;
        crearLog(__FILE__, __FUNCTION__, $parametros, 'Exception:' . $e, "error"); 
    } finally {
        $stmt->close();
        /*Devolvemos el JSON con los datos de la consulta*/
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata, JSON_FORCE_OBJECT);
    }

}

?>