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
require_once  './../../utiles/connectDBUtiles.php';
require_once './borrado.php';
require_once  './../insert/insertLog.php';

/*Declaramos como global la conexion y la query y el id de validador*/
global $mysqlCon, $borrarUsuarioDepartamento, $sentenciaBorradoUsuario;

/*definimos el json*/
$jsondata = array();
$jsondata["data"] = array();
    
$id = '';
$idS = '';

//Recogemos los valores
if (isset($_POST['usuarioHidden'])) {
    $id = trim($_POST['usuarioHidden']);
}

$parametros = "id: " . $id;
$className = basename(__FILE__, '.php'); 

if ($id === '') {
    $jsondata["success"] = false;
    $jsondata["message"] = "Faltan Parámetros";
    crearLog($className, "no-function", $parametros, "Faltan Parámetros", "error");
} else {
    try {
        if ($stmt = $mysqlCon->prepare($borrarUsuarioDepartamento)) {
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $jsondata["success"] = true;
            $jsondata["message"] = "Se ha eliminado el Usuario";
            crearLog($className, "no-function", $parametros, "Se ha eliminado el Usuario", "correcto");

            if ($stmt = $mysqlCon->prepare($sentenciaBorradoUsuario)) {
                $stmt->bind_param('i', $id);
                $stmt->execute();
                $jsondata["success"] = true;
                $jsondata["message"] = "Se ha eliminado el Usuario";
                crearLog($className, "no-function", $parametros, "Se ha eliminado el Usuario", "correcto");
            } else {
                /**
                 * Llegamos aqui con error, 
                 * asociamos false para identificarlo en el js
                 * */
                $jsondata["success"] = false;
                $jsondata["message"] = "Error Eliminando Usuario " . $mysqlCon->error;
                crearLog($className, "no-function", $parametros, $mysqlCon->error, "error");
            }
        } else {
            /*Llegamos aqui con error, asociamos false para identificarlo en el js*/
            $jsondata["success"] = false;
            $jsondata["message"] = "Error Eliminando Usuario " . $mysqlCon->error;
            crearLog($className, "no-function", $parametros, $mysqlCon->error, "error");
        }
        $stmt->close();
    } catch (Exception $e){
        $jsondata["success"] = false;
        $jsondata["message"] = "Error Eliminando Usuario " . $e;
        crearLog($className, "no-function", $parametros, $e, "error");
    }
}

/*Devolvemos el JSON con los datos de la consulta*/
header('Content-type: application/json; charset=utf-8');
echo json_encode($jsondata, JSON_FORCE_OBJECT);

?>