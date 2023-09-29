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
if (isset($_POST['tipoHidden'])) {
    $id = trim($_POST['tipoHidden']);
}

//Recogemos los valores
if (isset($_POST['detalleHidden'])) {
    $idS = trim($_POST['detalleHidden']);
}

$parametros = "Id: " . $id . " idS:". $idS;
$className = basename(__FILE__, '.php'); 

if ($id === '' || $idS === '') {
    $jsondata["success"] = false;
    $jsondata["message"] = "Faltan Parámetros";
    crearLog($className, "no-function", $parametros, "Faltan Parámetros", "error");

} else {
    try {
        if ($stmt = $mysqlCon->prepare($borradoArticulo)) {
            $stmt->bind_param('ii', $id, $idS);
            $stmt->execute();
            $stmt->close();
            $jsondata["success"] = true;
            $jsondata["message"] = "Se ha eliminado el Articulo";
            crearLog($className, "no-function", $parametros, 'Se ha eliminado el Articulo', 'correcto');
        } else {
            /*Llegamos aqui con error, asociamos false para identificarlo en el js*/
            $jsondata["success"] = false;
            $jsondata["message"] = "Error Eliminando Articulo " . $mysqlCon->error;
            crearLog($className, "no-function", $parametros, $mysqlCon->error, 'error');
        }
    } catch (Exception $e){
        $jsondata["success"] = false;
        $jsondata["message"] = "Error Eliminando Articulo " . $e;
        crearLog($className, "no-function", $parametros, $e, 'error');
    }
}

/*Devolvemos el JSON con los datos de la consulta*/
header('Content-type: application/json; charset=utf-8');
echo json_encode($jsondata, JSON_FORCE_OBJECT);

?>