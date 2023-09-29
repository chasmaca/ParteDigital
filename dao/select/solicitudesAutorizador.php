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

//Declaracion de parametros
$validador = "";

//Recogemos los valores
if (isset($_POST['usuario'])) {
    $validador = $_POST['usuario'];
    cargarSolicitudesPendientes($validador);
}

/**
 * Inserta los valores de ByN por solicitud.
 *
 * @param int $validador The position of the token in the token stack
 *
 * @return json
 */
function cargarSolicitudesPendientes($validador) 
{
    global $solicitudPorValidadorJSONQuery, $mysqlCon;

    /*definimos el json*/
    $jsondata = array();
    $jsondata["data"] = array();
    
    $parametros = "Query:" .$solicitudPorValidadorJSONQuery . " validador:" . $validador;
    try {
        /*Prepare Statement*/
        if ($stmt = $mysqlCon->prepare($solicitudPorValidadorJSONQuery)) {
            $stmt->bind_param("i", $validador);

            /*Ejecucion*/
            $stmt->execute();
            /*Almacenamos el resultSet*/
            $stmt->bind_result(
                $solicitud_id, $departamento_id, $subdepartamento_id, $nombre_solicitante,
                $apellidos_solicitante, $autorizador_id, $descripcion_solicitante, $email_solicitante, $status_id,
                $fecha_alta, $fecha_validacion, $fecha_cierre, $departamentos_desc, $subdepartamentos_desc
            );

            /*Incluimos las lineas de la consulta en el json a devolver*/
            while ($stmt->fetch()) {
                $tmp = array();
                $tmp["solicitud_id"] = utf8_encode($solicitud_id);
                $tmp["departamento_id"] = utf8_encode($departamento_id);
                $tmp["subdepartamento_id"] = utf8_encode($subdepartamento_id);
                $tmp["nombre"] = utf8_encode($nombre_solicitante) . ' ' . utf8_encode($apellidos_solicitante);
                $tmp["descripcion_solicitante"] = utf8_encode($descripcion_solicitante);
                $tmp["email_solicitante"] = utf8_encode($email_solicitante);
                $tmp["fecha_alta"] = utf8_encode($fecha_alta);
                $tmp["departamentos_desc"] = utf8_encode($departamentos_desc);
                $tmp["subdepartamentos_desc"] = utf8_encode($subdepartamentos_desc);

                array_push($jsondata["data"], $tmp);
            }
            /*Asociamos el correcto funcionamiento al json para comprobar en el js*/
            $jsondata["success"] = true;
        } else {
            /*Llegamos aqui con error, asociamos false para identificarlo en el js*/
            $jsondata["success"] = false;
            $jsondata["message"] = "Error recuperando trabajos " . $mysqlCon->error;
            crearLog(__FILE__, __FUNCTION__, $parametros, 'Exception:' . $mysqlCon->error, "error"); 
        }
    } catch (Exception $e) {
        $jsondata["success"] = false;
        $jsondata["message"] = "Error recuperando trabajos " . $e;
        crearLog(__FILE__, __FUNCTION__, $parametros, 'Exception:' . $e, "error"); 
    } finally {
        $stmt->close();
        /*Devolvemos el JSON con los datos de la consulta*/
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata, JSON_FORCE_OBJECT);
    }
}

?>