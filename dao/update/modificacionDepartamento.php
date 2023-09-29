<?php

/**
 * Class: varios2JSON.php
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

require_once "../../utiles/connectDBUtiles.php";
require_once "./../update/updates.php";
require_once "./../select/query.php";
require_once "./../delete/borrado.php";
require_once "./../insert/inserciones.php";
require_once "./../insert/insertLog.php";
    
$id = "";
$departamento = "";
$ceco = "";

//Recogemos los valores
if (isset($_POST['departamentoHidden'])) {
    $id = trim($_POST['departamentoHidden']);
}

if (isset($_POST['nombreHidden'])) {
    $departamento = utf8_decode(trim($_POST['nombreHidden']));
}

if (isset($_POST['cecoHidden'])) {
    $ceco = utf8_decode(trim($_POST['cecoHidden']));
}

modificacionDepartamentoFunction($departamento, $ceco, $id);


/**
 * Inserta los valores de ByN por solicitud.
 *
 * @param string $departamento The position of the token in the token stack
 * @param string $ceco         The position of the token in the token stack
 * @param int    $id           The position of the token in the token stack
 *
 * @return json
 */
function modificacionDepartamentoFunction($departamento, $ceco, $id)
{
    global $mysqlCon, $actualizaDepartamento;
    $jsondata = array();
    $jsondata["data"] = array();
    $path_parts = pathinfo(__FILE__);
    $parametros = "Query:" . $actualizaDepartamento . 
    " departamento:" . $departamento . 
    " ceco:" . $ceco . 
    " id:" . $id;

    try {

        if ($id === "" || $departamento === ""  || $ceco === "") {
            $jsondata["success"] = false;
            $jsondata["message"] = "Faltan Parámetros";
        } else {

            if ($stmt = $mysqlCon->prepare($actualizaDepartamento)) {
                $stmt->bind_param('ssi', $departamento, $ceco, $id);
                if ($stmt->execute()) {
                    $jsondata["success"] = true;
                    $jsondata["message"] = "Se ha actualizado el departamento";
                    crearLog($path_parts['filename'], __FUNCTION__, $parametros, "Se ha actualizado el departamento", "correcto");
                } else {
                    /*Llegamos aqui con error, asociamos false para identificarlo en el js*/
                    $jsondata["success"] = false;
                    $jsondata["message"] = "Error Actualizando Departamento " . $mysqlCon->error;
                    crearLog($path_parts['filename'], __FUNCTION__, $parametros, "Error Actualizando Articulo :" . $mysqlCon->error, "error");
                }
            } else {
                /*Llegamos aqui con error, asociamos false para identificarlo en el js*/
                $jsondata["success"] = false;
                $jsondata["message"] = "Error Actualizando Departamento " . $mysqlCon->error;
                crearLog($path_parts['filename'], __FUNCTION__, $parametros, "Error Actualizando Articulo :" . $mysqlCon->error, "error");
            }
        }
    } catch (Exception $e) {
        $jsondata["success"] = false;
        $jsondata["message"] = "Error Actualizando Departamento " . $e;
        crearLog($path_parts['filename'], __FUNCTION__, $parametros, "Error Actualizando Articulo :" . $e, "error");
    } finally {
        $stmt->close();
        /*Devolvemos el JSON con los datos de la consulta*/
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata, JSON_FORCE_OBJECT);
    }
}

?>