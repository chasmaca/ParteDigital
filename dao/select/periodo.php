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

/*Realizamos la llamada a la funcion que calculara el periodo*/
if (isset($_GET["opcion"])) {
    recuperamosPeriodoCierre();
} else {
    recuperamosPeriodo();
}

/**
 * Inserta los valores de ByN por solicitud.
 *
 * @return json
 */
function recuperamosPeriodo() 
{
    global $mysqlCon,$recuperaAnioMes;

    /*definimos el json*/
    $jsondata = array();
    $jsondata["data"] = array();

    $parametros = "Query:" . $recuperaAnioMes;

    try {
        /*Prepare Statement*/
        if ($stmt = $mysqlCon->prepare($recuperaAnioMes)) {

            /*Ejecucion*/
            $stmt->execute();
            /*Almacenamos el resultSet*/
            $stmt->bind_result($anio_alta, $mes_alta);

            /*Incluimos las lineas de la consulta en el json a devolver*/
            while ($stmt->fetch()) {
                $tmp = array();
                $tmp["mes_alta"] = utf8_encode($mes_alta);
                $tmp["anio_alta"] = utf8_encode($anio_alta);

                array_push($jsondata["data"], $tmp);
            }
            
            /*Asociamos el correcto funcionamiento al json para comprobar en el js*/
            $jsondata["success"] = true;
        } else {
            /*Llegamos aqui con error, asociamos false para identificarlo en el js*/
            $jsondata["success"] = false;
            $jsondata["message"] = "Problemas en la periodo de cierre" . $mysqlCon->error;
            crearLog(__FILE__, __FUNCTION__, $parametros, 'Error:' . $mysqlCon->error, "error");
        }
    } catch (Exception $e) {
        $jsondata["success"] = false;
        $jsondata["message"] = "Problemas en la periodo de cierre" . $e;
        crearLog(__FILE__, __FUNCTION__, $parametros, 'Exception:' . $e, "error");
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
 * @return json
 */
function recuperamosPeriodoCierre() 
{

    global $mysqlCon,$recuperaAnioMesCierre;

    /*definimos el json*/
    $jsondata = array();
    $jsondata["data"] = array();

    $parametros = "Query:" . $recuperaAnioMesCierre;

    try {
        /*Prepare Statement*/
        if ($stmt = $mysqlCon->prepare($recuperaAnioMesCierre)) {

            /*Ejecucion*/
            $stmt->execute();
            /*Almacenamos el resultSet*/
            $stmt->bind_result($anio_alta, $mes_alta);

            /*Incluimos las lineas de la consulta en el json a devolver*/
            while ($stmt->fetch()) {
                $tmp = array();
                $tmp["mes_alta"] = utf8_encode($mes_alta);
                $tmp["anio_alta"] = utf8_encode($anio_alta);

                array_push($jsondata["data"], $tmp);
            }
            
            /*Asociamos el correcto funcionamiento al json para comprobar en el js*/
            $jsondata["success"] = true;
        } else {
            /*Llegamos aqui con error, asociamos false para identificarlo en el js*/
            $jsondata["success"] = false;
            $jsondata["message"] = "Problemas en la periodo de cierre" . $mysqlCon->error;
            crearLog(__FILE__, __FUNCTION__, $parametros, 'Error:' . $mysqlCon->error, "error");
        }
    } catch (Exception $e) {
        $jsondata["success"] = false;
        $jsondata["message"] = "Problemas en la periodo de cierre" . $e;
        crearLog(__FILE__, __FUNCTION__, $parametros, 'Exception:' . $e, "error");
    } finally {
        $stmt->close();
        /*Devolvemos el JSON con los datos de la consulta*/
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata, JSON_FORCE_OBJECT);
    }


}
?>