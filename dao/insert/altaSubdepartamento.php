<?php

/**
 * Class: altaSubdepartamento.php
 *
 * Creacion de subdepartamentos
 * php version 7.3.28
 * 
 * @category Insert
 * @package  DaoInsert
 * @author   Jesus Madrazo <chasmaca@gmail.com>
 * @license  http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version  GIT: <Initial Import>
 * @link     https://www.elpartedigital.com/
 */

require_once '../../utiles/connectDBUtiles.php';
require_once '../select/query.php';
require_once 'inserciones.php';
require_once 'insertLog.php';

$departamento = "";
$subdepartamento = "";
$treintaBarra = "";

if (isset($_POST['departamentoHidden'])) {
    $departamento = utf8_decode(trim($_POST['departamentoHidden']));
}

if (isset($_POST['nombreHidden'])) {
    $subdepartamento = utf8_decode(trim($_POST['nombreHidden']));
}

if (isset($_POST['treintaBarraHidden'])) {
    $treintaBarra = utf8_decode(trim($_POST['treintaBarraHidden']));
}

altaSubdepartamento($departamento, $subdepartamento, $treintaBarra);

/**
 * Funcion que crea el alta de subdepartamento
 *
 * @param string $departamento    Descripcion del departamento
 * @param string $subdepartamento Descripcion del ceco
 * @param string $treintaBarra    Descripcion del ceco
 * 
 * @return json
 */
function altaSubdepartamento($departamento, $subdepartamento, $treintaBarra)
{
    global $guardaSubDepartamento, $mysqlCon;
    $path_parts = pathinfo(__FILE__);
    /*definimos el json*/
    $jsondata = array();
    $jsondata["data"] = array();

    $parametros = "Query:" . $guardaSubDepartamento . " -> departamento:" . $departamento . " subdepartamento:" . $subdepartamento . " treintaBarra:" . $treintaBarra;  // phpcs:ignore

    try {
        if ($subdepartamento === "" || $treintaBarra ==="") {
            $jsondata["success"] = false;
            $jsondata["message"] = "Faltan por enviar Parámetros";
            crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:Faltan Parametros', "error");
        } else {
            if ($stmt = $mysqlCon->prepare($guardaSubDepartamento)) {
                $idSubdepartamento = recuperaMaxSubDpto($departamento);
                $stmt->bind_param('iiss', $departamento, $idSubdepartamento, $subdepartamento, $treintaBarra);
                if ($stmt->execute()) {
                    $jsondata["success"] = true;
                    $jsondata["message"] = "Subdepartamento Insertado";
                    crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Departamento creado Correctamente', "correcto");
                } else {
                    $jsondata["success"] = false;
                    $jsondata["message"] = "Error Insertando Subdepartamento" . $mysqlCon->error;
                    crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:' . $mysqlCon->error, "error");
                }
            } else {
                $jsondata["success"] = false;
                $jsondata["message"] = "Error Insertando Subdepartamento" . $mysqlCon->error;
                crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:' . $mysqlCon->error, "error");
            }
        }
    } catch (Exception $e) {
        $jsondata["success"] = false;
        $jsondata["message"] = "Error Insertando Subdepartamento" . $e;
        crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:' . $e, "error");
    } finally {
        $stmt->close();
        /*Devolvemos el JSON con los datos de la consulta*/
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata, JSON_FORCE_OBJECT);
    }
}


/**
 * Adjust the indent of a code block.
 *
 * @param int $departamento The position of the token in the token stack
 *
 * @return int
 */function recuperaMaxSubDpto($departamento)
{
    global $recuperaMaxSubDptoQuery,$mysqlCon;
    $path_parts = pathinfo(__FILE__);
    $parametros = $recuperaMaxSubDptoQuery . " -> departamento:" . $departamento;
    $idMaximo = 1;
    
    try {
        if ($stmt = $mysqlCon->prepare($recuperaMaxSubDptoQuery)) {
            /*Asociacion de parametros*/
            $stmt->bind_param('i', $departamento);
            if ($stmt->execute()) {
                /*Asociacion de resultados*/
                $stmt->bind_result($col1);
                /*Recogemos el resultado en la variable*/
                while ($stmt->fetch()) {
                    if ($col1!=null) {
                        $idMaximo = $col1;
                    }
                }
            } else {
                $jsondata["success"] = false;
                $jsondata["message"] = "Error Insertando recuperaMaximoPorTipo " . $mysqlCon->error;
                crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:' . $mysqlCon->error, "error");
            }
        } else {
            $jsondata["success"] = false;
            $jsondata["message"] = "Error Insertando recuperaMaximoPorTipo " . $mysqlCon->error;
            crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:' . $mysqlCon->error, "error");
        }
    } catch (Exception $e) {
        $jsondata["success"] = false;
        $jsondata["message"] = "Error Insertando recuperaMaxSubDpto " . $e;
        crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:' . $e, "error");
    } finally {
        $stmt->close();
        return $idMaximo;
    }

}