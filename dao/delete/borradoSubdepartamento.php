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
require_once './borrado.php';
require_once './../insert/insertLog.php';

/*Declaramos como global la conexion y la query y el id de validador*/
global $mysqlCon, $borradoSubdepartamento;

/*definimos el json*/
$jsondata = array();
$jsondata["data"] = array();
    
$id = '';
$idS = '';

//Recogemos los valores
if (isset($_POST['departamentoHidden'])) {
    $id = trim($_POST['departamentoHidden']);
}

//Recogemos los valores
if (isset($_POST['subdepartamentoHidden'])) {
    $idS = trim($_POST['subdepartamentoHidden']);
}

$parametros = "Id: " . $id . " idS:". $idS;
$className = basename(__FILE__, '.php'); 

if ($id === '' || $idS === '') {
    $jsondata["success"] = false;
    $jsondata["message"] = "Faltan Parámetros";
    crearLog($className, "no-function", $parametros, "Faltan Parámetros", "error");
} else {
    try {
        if ($stmt = $mysqlCon->prepare($borradoSubdepartamento)) {
            $stmt->bind_param('ii', $id, $idS);
            if ($stmt->execute()) {
                $stmt->close();
                $jsondata["success"] = true;
                $jsondata["message"] = "Se ha eliminado el subdepartamento";
                crearLog($className, "no-function", $parametros, "Se ha eliminado el subdepartamento", "correcto");
            } else {
                $jsondata["success"] = false;
                $jsondata["message"] = "Error eliminando el subdepartamento" . $mysqlCon->error;   
                crearLog($className, "no-function", $parametros, $mysqlCon->error, "error"); 
            }
        } else {
            /*Llegamos aqui con error, asociamos false para identificarlo en el js*/
            $jsondata["success"] = false;
            $jsondata["message"] = "Error Eliminando Subepartamento " . $mysqlCon->error;
            crearLog($className, "no-function", $parametros, $mysqlCon->error, "error");
        }
    } catch (Exception $e){
        $jsondata["success"] = false;
        $jsondata["message"] = "Error Eliminando Subepartamento " . $e;
        crearLog($className, "no-function", $parametros, $e, "error");
    }
}

/*Devolvemos el JSON con los datos de la consulta*/
header('Content-type: application/json; charset=utf-8');
echo json_encode($jsondata, JSON_FORCE_OBJECT);

?>