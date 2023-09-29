<?php
/**
 * Class: altaDepartamento.php
 *
 * Clase que da de alta el departamento
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
require_once './../select/query.php';
require_once 'inserciones.php';
require_once 'insertLog.php';

$departamentoDesc = "";
$ceco = "";

//Recogemos los valores
if (isset($_POST['nombreHidden'])) {
    $departamentoDesc = utf8_decode(trim($_POST['nombreHidden']));
}

if (isset($_POST['cecoHidden'])) {
    $ceco = utf8_decode(trim($_POST['cecoHidden']));
}

altaDepartamentoFunction($departamentoDesc, $ceco);

/**
 * Funcion que crea el alta de departamento
 *
 * @param string $departamento Descripcion del departamento
 * @param string $ceco         Descripcion del ceco
 * 
 * @return json
 */
function altaDepartamentoFunction($departamento, $ceco)
{
    global $guardaDepartamento, $mysqlCon;
    $path_parts = pathinfo(__FILE__);

    /*definimos el json*/
    $jsondata = array();
    $jsondata["data"] = array();

    $parametros = "Query:" . $guardaDepartamento . " " . "departamento:" . $departamento . " ceco:" . $ceco;

    try {
        if ($departamento === "" || $ceco ==="") {
            $jsondata["success"] = false;
            $jsondata["message"] = "Faltan por enviar Parámetros";
            crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:Faltan Parametros', "error");
        } else {
            if ($stmt = $mysqlCon->prepare($guardaDepartamento)) {
                $stmt->bind_param('ss', $departamento, $ceco);
                if ($stmt->execute()) {
                    $jsondata["success"] = true;
                    $jsondata["message"] = "Departamento Insertado";
                    crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Departamento creado Correctamente', "correcto");
                } else {
                    $jsondata["success"] = false;
                    $jsondata["message"] = "Error Insertando Departamento" . $mysqlCon->error;
                    crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:' . $mysqlCon->error, "error");
                }
            } else {
                $jsondata["success"] = false;
                $jsondata["message"] = "Error Insertando Departamento" . $mysqlCon->error;
                crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:' . $mysqlCon->error, "error");
            }
        }

    } catch (Exception $e) {
        $jsondata["success"] = false;
        $jsondata["message"] = "Error Insertando Departamento " . $e;
        crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:' . $e, "error");
    } finally {
        $stmt->close();
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata, JSON_FORCE_OBJECT);
    }
}
