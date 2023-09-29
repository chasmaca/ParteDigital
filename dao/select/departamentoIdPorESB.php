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

require_once 'query.php';
require_once '../../utiles/connectDBUtiles.php';

global $mysqlCon;

$getDepartmentId = "SELECT
                        departamento_id
                    FROM
                        departamento
                    WHERE
                        ceco = ? AND
                        markfordelete = 0";
                        

/*definimos el json*/
$jsondata = array();
$jsondata["data"] = array();

//Declaracion de parametros
$departamento = "";

//Recogemos los valores
if (isset($_POST['esb'])) {
    $departamento = $_POST['esb'];
}

try{
    /*Prepare Statement*/
    if ($stmt = $mysqlCon->prepare($getDepartmentId)) {
        $stmt->bind_param('s', $departamento);
        /*Ejecucion*/
        if ($stmt->execute()) {
            /*Almacenamos el resultSet*/
            $stmt->bind_result($dptoId);

            /*Incluimos las lineas de la consulta en el json a devolver*/
            while ($stmt->fetch()) {
                $tmp = array();
                $tmp["idCentro"] = $dptoId;
                array_push($jsondata["data"], $tmp);
            }
            $stmt->close();
            /*Asociamos el correcto funcionamiento al json para comprobar en el js*/
            $jsondata["success"] = true;
        } else {
            echo $getDepartmentId . $departamento;
            $jsondata["success"] = false;
        }
    } else {
        echo $getDepartmentId . $departamento;
        /*Llegamos aqui con error, asociamos false para identificarlo en el js*/
        $jsondata["success"] = false;
    }
} catch (Exception $e){
    echo $getDepartmentId . $departamento;
    /*Llegamos aqui con error, asociamos false para identificarlo en el js*/
    $jsondata["success"] = false;
}

/*Devolvemos el JSON con los datos de la consulta*/
header('Content-type: application/json; charset=utf-8');
echo json_encode($jsondata, JSON_FORCE_OBJECT);
