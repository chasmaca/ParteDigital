<?php
/**
 * Class: asociacionDepartamento.php
 *
 * Carga de Departamentos y Subdepartamentos
 * php version 7.3.28
 * 
 * @category Select
 * @package  DaoSelect
 * @author   Jesus Madrazo <chasmaca@gmail.com>
 * @license  http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version  GIT: <Initial Import>
 * @link     https://www.elpartedigital.com/
 */
require_once 'query.php';
require_once './../../utiles/connectDBUtiles.php';
require_once './../insert/insertLog.php';

//Declaracion de parametros
$departamento = "";

//Recogemos los valores
if (isset($_POST['departamento'])) {
    $departamento = $_POST['departamento'];
    cargarSubDptoDpto($departamento);
} else {
    cargarTodosDpto();
}

/**
 * Carga todos los departamentos
 *
 * @return json
 */
function cargarTodosDpto()
{   
    global $todosDepartamentosQuery, $mysqlCon;
    $path_parts = pathinfo(__FILE__);
    /*definimos el json*/
    $jsondata = array();
    $jsondata["data"] = array();

    $parametros = 'Query:' . $todosDepartamentosQuery;

    try{
        if ($stmt = $mysqlCon->prepare($todosDepartamentosQuery)) {

            /*Ejecucion*/
            $stmt->execute();
            /*Almacenamos el resultSet*/
            $stmt->bind_result($dptoId, $dptoDesc, $ceco);

            /*Incluimos las lineas de la consulta en el json a devolver*/
            while ($stmt->fetch()) {
                $tmp = array();
                $tmp["id"] = utf8_encode($dptoId);
                $tmp["nombre"] = utf8_encode($dptoDesc);
                array_push($jsondata["data"], $tmp);
            }
            /*Asociamos el correcto funcionamiento al json para comprobar en el js*/
            $jsondata["success"] = true;
        } else {
            /*Llegamos aqui con error, asociamos false para identificarlo en el js*/
            $jsondata["success"] = false;
            $jsondata["message"] = "Error recuperando Asociacion de Departamentos";
            crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:' . $mysqlCon->error, "error");
        }
    } catch (Exception $e) {
        $jsondata["success"] = false;
        $jsondata["message"] = "Error recuperando Asociacion de Departamentos " . $e;
        crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:' . $e, "error");
    } finally {
        $stmt->close();
        /*Devolvemos el JSON con los datos de la consulta*/
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata, JSON_FORCE_OBJECT);
    }
}

/**
 * Carga todos los subdepartamentos por id de departamento
 *
 * @param int $departamento Id de Departamento
 * 
 * @return json
 */
function cargarSubDptoDpto($departamento)
{

    global $recuperaSubdptoXDpto, $mysqlCon;
    $path_parts = pathinfo(__FILE__);
    /*definimos el json*/
    $jsondata = array();
    $jsondata["data"] = array();
    
    $parametros = 'Query:' . $recuperaSubdptoXDpto . ' departamento' . $departamento;

    try{
        /*Prepare Statement*/
        if ($stmt = $mysqlCon->prepare($recuperaSubdptoXDpto)) {
            $stmt->bind_param("i", $departamento);
        
            /*Ejecucion*/
            $stmt->execute();
            /*Almacenamos el resultSet*/
            $stmt->bind_result($dptoId, $subDptoId, $subDptoIdDesc, $treinta);
            /*Incluimos las lineas de la consulta en el json a devolver*/
            while ($stmt->fetch()) {
                $tmp = array();
                $tmp["id"] = utf8_encode($subDptoId);
                $tmp["nombre"] = utf8_encode($subDptoIdDesc);
                array_push($jsondata["data"], $tmp);
            }
            /*Asociamos el correcto funcionamiento al json para comprobar en el js*/
            $jsondata["success"] = true;
            // crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Cargados todos los Subdepartamentos', "correcto");
        } else {
            /*Llegamos aqui con error, asociamos false para identificarlo en el js*/
            $jsondata["success"] = false;
            $jsondata["message"] = "Error recuperando Subdepartamento por Departamentos ";
            crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:' . $mysqlCon->error, "error");
        }

    } catch (Exception $e) {
        $jsondata["success"] = false;
        $jsondata["message"] = "Error recuperando Subdepartamento por Departamentos " . $e;
        crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:' . $e, "error");
    } finally {
        $stmt->close();
        /*Devolvemos el JSON con los datos de la consulta*/
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata, JSON_FORCE_OBJECT);
    }    
}
?>