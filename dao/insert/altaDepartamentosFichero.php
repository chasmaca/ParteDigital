<?php
/**
 * Class: altaDepartamentosFichero.php
 *
 * Gestion masiva de departamentos y subdepartamentos
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
require_once './../../utiles/phpExcel/SimpleXLSX.php';
require_once './../select/query.php';
require_once './../delete/borrado.php';
require_once './inserciones.php';
require_once './insertLog.php' ;


iniciarProceso();

/**
 * Main del proceso.
 *
 * @return json
 */
function iniciarProceso()
{
    $path_parts = pathinfo(__FILE__);
    // $xlsx = new SimpleXLSX('./../../ficherosCarga/plantilla departamento.xlsx'); // try...catch

    $parametros = "";

    $jsondata = array();
    $jsondata["data"] = array();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_FILES['file'])) {
            $errors = [];
            $path = '../../utiles/uploads/';
            $extensions = ['xls', 'xlsx'];
    
        
            try{
                $fileTmpPath = $_FILES['file']['tmp_name'];
                $file_name = $_FILES['file']['name'];
                $file_tmp = $_FILES['file']['tmp_name'];
                $file_type = $_FILES['file']['type'];
                $file_ext = substr($file_name, strrpos($file_name, '.')+1);
                $file = $path . $file_name;
                $uploadFileDir = './uploaded_files/';
                $dest_path = $path . $file_name;

                if (!in_array($file_ext, $extensions)) {
                    $errors[] = 'Extension not allowed: ' . $file_name . ' ' . $file_type;
                } else {
                    
                    if (move_uploaded_file($fileTmpPath, $dest_path)) {
                        $message ='File is successfully uploaded.';
                        // echo 'File is successfully uploaded.';
                        if ($xlsx = SimpleXLSX::parse($file) ) {
                            
                            foreach ($xlsx->rows() as $r) {
                                $departamento_id = "-";
                                $subdepartamento_id = "-";
                                $insercion = false;
                                $borrado = false;
                        
                                $departamento_id = comprobarDepartamento($r[0]);
                        
                                if ($departamento_id !== "-") {
                                    if ($r[2] !== "") {
                                        $subdepartamento_id = comprobarSubdepartamento($departamento_id, $r[2]);
                                    }
                                    
                                }
                        
                                if ($r[4] === 'Alta') {
                                    if ($subdepartamento_id === '-') {
                                        $texto = "";
                                        $subdepartamento_id = recuperaMaxSubDpto($departamento_id);
                                        $insercion = insertaSubdepartamento($departamento_id, $subdepartamento_id, $r[3], $r[2]);
                                        if ($insercion) {
                                            $texto = "El Subdepartamento " . $r[3] . " se ha insertado correctamente";
                                        } else {
                                            $texto = "El Subdepartamento " . $r[3] . " no se ha insertado";
                                        }
                                        array_push($jsondata["data"], $texto);
                                    } else {
                                        $texto = "El Subdepartamento " . $r[3] . " ya estaba creado en base de datos";
                                        array_push($jsondata["data"], $texto);
                                    }
                                }
                                if ($r[4] === 'Baja') {
                                    if ($subdepartamento_id !== '-') {
                                        if (validarPartesMes($departamento_id, $subdepartamento_id) === false) {
                                            $texto = "";
                                            $borrado = borraSubdepartamento($departamento_id, $subdepartamento_id);
                    
                                            if ($borrado) {
                                                $texto = "El Subdepartamento " . $r[3] . " se ha eliminado correctamente";
                                            } else {
                                                $texto = "El Subdepartamento " . $r[3] . " no se ha eliminado.";
                                            }
                                            array_push($jsondata["data"], $texto);
                    
                                        } else {
                                            $texto = "El Subdepartamento " . $r[3] . " no se ha eliminado porque tiene partes en el mes en curso.";
                                            array_push($jsondata["data"], $texto);
                                        }
                                    } else {
                                        $texto = "El Subdepartamento " . $r[3] . " no se ha eliminado porque no esta en base de datos o le falta algun parametro.";
                                        array_push($jsondata["data"], $texto);
                                    }
                                }
                            }
                            $jsondata["success"] = true;
                            $jsondata["message"] = "Fichero gestionado Correctamente";
                        } else {
                            $jsondata["success"] = false;
                            $jsondata["message"] = SimpleXLSX::parseError();
                        }
                        unlink($dest_path);
                    } else {
                        $jsondata["success"] = false;
                        $jsondata["message"] = $errors;
                        
                    }
                }
            }catch (Exception $e) {
                $jsondata["success"] = false;
                $jsondata["message"] = $e;
            } finally {
                /*Devolvemos el JSON con los datos de la consulta*/
                header('Content-type: application/json; charset=utf-8');
                echo json_encode($jsondata, JSON_FORCE_OBJECT);
            }
        }
    }

}

/**
 * En caso de insercion obtenemos el id del subdepartamento.
 *
 * @param int $departamento Id del Departamento
 *
 * @return int
 */
function recuperaMaxSubDpto($departamento)
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

/**
 * Comprobamos si existe el departamento.
 *
 * @param int $departamento Ceco del Departamento
 *
 * @return int
 */
function comprobarDepartamento($departamento)
{
    global $existeDepartamentoFichero, $mysqlCon;
    $path_parts = pathinfo(__FILE__);
    /*Prepare Statement*/
    $idDepartamento = "-";
    $parametros = "Query:" . $existeDepartamentoFichero . " - " . $departamento;
    try {
        if ($stmt = $mysqlCon->prepare($existeDepartamentoFichero)) {
                    
            $stmt->bind_param('s', $departamento);
            /*Ejecucion*/
            $stmt->execute();
            /*Almacenamos el resultSet*/
            $stmt->bind_result($departamento_id);
    
            /*Incluimos las lineas de la consulta en el json a devolver*/
            while ($stmt->fetch()) {
                $idDepartamento = utf8_encode($departamento_id);
            }
    
        } else {
            /*Llegamos aqui con error, asociamos false para identificarlo en el js*/
            $jsondata["success"] = false;
            crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Error:' . $mysqlCon->error, "error");
        }
    } catch (Exception $e) {
        $jsondata["success"] = false;
        $jsondata["message"] = "Error Comprobando Departamento " . $e;
        crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:' . $e, "error");
    } finally {
        $stmt->close();
        return $idDepartamento;
    }
}

/**
 * Comprobamos si existe el subdepartamento.
 *
 * @param int $departamento    Id del Departamento
 * @param int $subdepartamento 30/ Subdepartamento
 *
 * @return int
 */
function comprobarSubdepartamento($departamento, $subdepartamento)
{
    global $existeSubdepartamentoFichero, $mysqlCon;
    $path_parts = pathinfo(__FILE__);
    $idSubdepartamento = "-";

    $parametros = "Query:" . $existeSubdepartamentoFichero . " -> " . $departamento. " - " . $subdepartamento;

    try {
        if ($stmt = $mysqlCon->prepare($existeSubdepartamentoFichero)) {
            $stmt->bind_param('is', $departamento, $subdepartamento);
            /*Ejecucion*/
            $stmt->execute();
            /*Almacenamos el resultSet*/
            $stmt->bind_result($subdepartamentoId);
            /*Incluimos las lineas de la consulta en el json a devolver*/
            while ($stmt->fetch()) {
                $idSubdepartamento = utf8_encode($subdepartamentoId);
            }
        } else {
            /*Llegamos aqui con error, asociamos false para identificarlo en el js*/
            $jsondata["success"] = false;
            crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Error:' . $mysqlCon->error, "error");
        }
    } catch (Exception $e) {
        $jsondata["success"] = false;
        $jsondata["message"] = "Error Comprobando Subdepartamento " . $e;
        crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:' . $e, "error");
    } finally {
        $stmt->close();
        return $idSubdepartamento;
    }
}

/**
 * Insertamos el subdepartamento.
 *
 * @param int    $departamento_id    Id del Departamento
 * @param int    $subdepartamento_id Id del Subdepartamento
 * @param string $descripcion        Nombre del subdepartamento
 * @param string $treinta            30/ del Subdepartamento
 *
 * @return int
 */
function insertaSubdepartamento($departamento_id, $subdepartamento_id, $descripcion, $treinta)
{
    global $guardaSubDepartamento, $mysqlCon;
    $path_parts = pathinfo(__FILE__);
    $parametros = "Query:" . $guardaSubDepartamento . 
                    " parametros:" . $departamento_id . " - " . $subdepartamento_id . " - " . $descripcion . " - " . $treinta;

    $operacion = false;

    try {
        if ($stmt = $mysqlCon->prepare($guardaSubDepartamento)) {
            $stmt->bind_param('iiss', $departamento_id, $subdepartamento_id, $descripcion, $treinta);
            if ($stmt->execute()) {
                $operacion = true;
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
    } catch (Exception $e) {
        $jsondata["success"] = false;
        $jsondata["message"] = "Error Comprobando Subdepartamento " . $e;
        crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:' . $e, "error");
    } finally {
        $stmt->close();
        return $operacion;
    }
}

/**
 * Baja logica del subdepartamento.
 *
 * @param int $departamento_id    Id del Departamento
 * @param int $subdepartamento_id Id del subdepartamento
 *
 * @return boolean
 */
function borraSubdepartamento($departamento_id, $subdepartamento_id)
{
    global $borradoSubdepartamento, $mysqlCon;
    $path_parts = pathinfo(__FILE__);
    $parametros = "Query:" . $borradoSubdepartamento . " parametros: departamento->" . $departamento_id . " subdepartamento->" . $subdepartamento_id;
    $operacion = false;

    try {
        
        if ($stmt = $mysqlCon->prepare($borradoSubdepartamento)) {
            $stmt->bind_param('ii', $departamento_id, $subdepartamento_id);
            if ($stmt->execute()) {
                $operacion = true;
                $jsondata["success"] = true;
                $jsondata["message"] = "Se ha eliminado el subdepartamento";
                crearLog($path_parts['filename'], "no-function", $parametros, "Se ha eliminado el subdepartamento", "correcto");
            } else {
                $jsondata["success"] = false;
                $jsondata["message"] = "Error eliminando el subdepartamento" . $mysqlCon->error;   
                crearLog($path_parts['filename'], "no-function", $parametros, $mysqlCon->error, "error"); 
            }
        } else {
            /*Llegamos aqui con error, asociamos false para identificarlo en el js*/
            $jsondata["success"] = false;
            $jsondata["message"] = "Error Eliminando Subepartamento " . $mysqlCon->error;
            crearLog($path_parts['filename'], "no-function", $parametros, $mysqlCon->error, "error");
        }
    } catch (Exception $e) {
        $jsondata["success"] = false;
        $jsondata["message"] = "Error Comprobando Subdepartamento " . $e;
        crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:' . $e, "error");
    } finally {
        $stmt->close();
        return $operacion;
    }
}

/**
 * Comprobación de partes activos.
 *
 * @param int $departamento    Id del Departamento
 * @param int $subdepartamento Id del subdepartamento
 *
 * @return boolean
 */
function validarPartesMes($departamento, $subdepartamento)
{
    global $consultaImpresionesSubdepartamento, $mysqlCon;
    $path_parts = pathinfo(__FILE__);

    $anio = date("Y");
    $mes = date("m");
    $datos = false;

    $parametros = "Query:" . $consultaImpresionesSubdepartamento . 
                    " parametros: anio->" . $anio . " subdepartamento->" . $mes . 
                    " departamento->" . $departamento . " subdepartamento->" . $subdepartamento ;
    $operacion = false;

    try {
        
        if ($stmt = $mysqlCon->prepare($consultaImpresionesSubdepartamento)) {
            $stmt->bind_param("iiii", $anio, $mes, $departamento, $subdepartamento);
            if ($stmt->execute()) {
                $stmt->bind_result($ceco, $departamento, $espiral, $encolado, $varios1, $color, $blancoNegro, $varios2);
               
                if ($stmt->num_rows() > 0) {
                    $datos = true;
                }

            } else {
                $jsondata["success"] = false;
                $jsondata["message"] = "Error eliminando el subdepartamento" . $mysqlCon->error;   
                crearLog($path_parts['filename'], __FUNCTION__, $parametros, $mysqlCon->error, "error"); 
            }
        } else {
            /*Llegamos aqui con error, asociamos false para identificarlo en el js*/
            $jsondata["success"] = false;
            $jsondata["message"] = "Error Eliminando Subepartamento " . $mysqlCon->error;
            crearLog($path_parts['filename'], __FUNCTION__, $parametros, $mysqlCon->error, "error");
        }
    } catch (Exception $e) {
        $jsondata["success"] = false;
        $jsondata["message"] = "Error Comprobando Subdepartamento " . $e;
        crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:' . $e, "error");
    } finally {
        $stmt->close();
        return $datos;
    }
}