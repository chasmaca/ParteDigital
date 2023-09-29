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
require_once "./../../utiles/connectDBUtiles.php";
require_once "./inserciones.php";
require_once "./insertLog.php";
require_once "./../select/solicitudMax.php";
require_once "./../select/autorizador.php";
require_once "./../select/query.php";

//Declaramos las variables
$departamento = htmlspecialchars($_POST["solDpto"]);
$nombre = utf8_decode(htmlspecialchars($_POST["solName"]));
$apellidos = utf8_decode(htmlspecialchars($_POST["solSurname"]));
$email = htmlspecialchars($_POST["solEmail"]);
$autorizador = htmlspecialchars($_POST["solAuth"]);
$observaciones = utf8_decode(htmlspecialchars($_POST["solComment"]));
$idSolicitudSQL = mysqli_fetch_assoc($maxSolicitudId);
$idSolicitud = $idSolicitudSQL["SOLICITUD_MAX"];
$subdepartamento = htmlspecialchars($_POST["solSubdpto"]);
$statusInicial = 1;
$fechaActual = date("d/m/Y");
$error = "";

date_default_timezone_set("Europe/Madrid");

if ($idSolicitud == null) {
    $idSolicitud = 1;
}


insertarSolicitud($idSolicitud, $departamento, $nombre, $apellidos, $autorizador, $observaciones, $email, $statusInicial, $fechaActual, $subdepartamento);

/**
 * Actualiza los valores del trabajo.
 *
 * @param int    $idSolicitud     id de solicitud
 * @param int    $departamento    id de departamento
 * @param string $nombre          nombre del peticionario
 * @param string $apellidos       apellido del peticionario
 * @param int    $autorizador     id de autorizador
 * @param string $observaciones   observaciones de la solicitud
 * @param string $email           email del peticionario
 * @param int    $statusInicial   status inicial de la solicitud
 * @param int    $fechaActual     fecha de creacion
 * @param int    $subdepartamento id de subdepartamento
 *
 * @return json
 */
function insertarSolicitud(
    $idSolicitud, $departamento, $nombre, 
    $apellidos, $autorizador, $observaciones, 
    $email, $statusInicial, $fechaActual, $subdepartamento
) {
    
    global $mysqlCon, $sentenciaInsertSolicitud;
    $jsondata = array();
    $path_parts = pathinfo(__FILE__);
    $parametros = "Query:" . $sentenciaInsertSolicitud . " solicitud:" . $idSolicitud .
    " departamento:" .  $departamento . " nombre:" . $nombre . " apellidos:" . $apellidos .
    " autorizador:" . $autorizador . " observaciones:" . $observaciones . " email:" . $email .
    " statusInicial:" . $statusInicial . " fechaActual:" . $fechaActual . " subdepartamento:" . $subdepartamento;

    try {
        if ($stmt = $mysqlCon->prepare($sentenciaInsertSolicitud)) {
            
    
            $stmt->bind_param(
                'iississisi', $idSolicitud, $departamento, 
                $nombre, $apellidos, $autorizador, $observaciones, 
                $email, $statusInicial, $fechaActual, $subdepartamento
            );
    
            if ($stmt->execute()) {
                $jsondata["success"] = true;
                $jsondata["message"] = "Insercion realizada con Éxito";
                crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Insercion realizada con Éxito', "correcto");
            } else {
                $jsondata["success"] = true;
                $jsondata["message"] = "Problemas al crear la solicitud";
                crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:' . $mysqlCon->error, "error");
            }
        } else {
            $jsondata["success"] = false;
            $jsondata["message"] = "Errormessage: " . $mysqlCon->error;
            crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:' . $mysqlCon->error, "error");
        }
    } catch (Exception $e) {
        $jsondata["success"] = false;
        $jsondata["message"] = "Errormessage: " . $e->getMessage();
        crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:' . $e, "error");
    } finally {
        $stmt->close();
        /*Devolvemos el JSON con los datos de la consulta*/
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata, JSON_FORCE_OBJECT);
    }
}


?>