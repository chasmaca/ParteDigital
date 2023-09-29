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

$valor = '';
/*Realizamos la llamada a la funcion que calculara el periodo*/
if (isset($_POST["valor"])) {
    $valor = $_POST["valor"];
}

/*Declaramos como global la conexion y la query*/
global $mysqlCon,$recuperaPasswordMaestra;

/*definimos el json*/
$jsondata = array();
$jsondata["data"] = array();
    
try {
    /*Prepare Statement*/
    if ($stmt = $mysqlCon->prepare($recuperaPasswordMaestra)) {

        /*Ejecucion*/
        if ($stmt->execute()) {
            
            /*Almacenamos el resultSet*/
            $stmt->bind_result($claveMaestra);
            /*Incluimos las lineas de la consulta en el json a devolver*/
            $jsondata["message"] = "Usuario/Password No Valido";
            $jsondata["success"] = false;

            while ($stmt->fetch()) {
                if ($claveMaestra === $valor) {
                    $jsondata["success"] = true;
                    $jsondata["message"] = "Password Valido";
                } else {
                    $jsondata["success"] = false;
                    $jsondata["message"] = "Password No Valida";
                }
            }

        } else {
            $jsondata["success"] = false;
            $jsondata["message"] = "Errormessage: " . $mysqlCon->error . " " . $sentenciaLogonJSON;
        }
    } else {
        /*Llegamos aqui con error, asociamos false para identificarlo en el js*/
        $jsondata["success"] = false;
        $jsondata["message"] = "Errormessage: " . $mysqlCon->error . " " . $sentenciaLogonJSON;
    }

} catch (Exception $exception) {
    
    $jsondata["success"] = false;
    $jsondata["message"] = "Errormessage: " . $exception;
} finally {
    $stmt->close();
    /*Devolvemos el JSON con los datos de la consulta*/
    header('Content-type: application/json; charset=utf-8');
    echo json_encode($jsondata, JSON_FORCE_OBJECT);
}


?>