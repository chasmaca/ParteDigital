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
require_once '../../utiles/writeLog.php';

recuperaTipos();

function recuperaTipos()
{
    global $mysqlCon, $recuperaTipos;
    $jsondata = array();
    $jsondata["data"] = array();
    try {
        if ($stmt = $mysqlCon->prepare($recuperaTipos)) {
            $stmt->execute();
            $stmt->bind_result($tipoId, $tipoDesc);
            while ($stmt->fetch()) {
                $tmp = array();
                $tmp["id"] = utf8_encode($tipoId);
                $tmp["nombre"] = utf8_encode($tipoDesc);
                array_push($jsondata["data"], $tmp);
            }
            $stmt->close();
            $jsondata["success"] = true;
        } else {
            $jsondata["success"] = false;
            $jsondata["message"] = "Error en la funcion recuperaTipos de SelectTipoClass:".$recuperaTipos;
            writeErrorLog(basename($_SERVER['PHP_SELF']), $recuperaTipos, array("No Params"));
        }
    } catch (Exception $th) {
        $jsondata["success"] = false;
        $jsondata["message"] = "Error en la funcion recuperaTipos de SelectTipoClass:".$th;
        writeErrorNoQueryLog(basename($_SERVER['PHP_SELF']), array($th));
    } finally {
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata, JSON_FORCE_OBJECT);
    }
    
}
?>