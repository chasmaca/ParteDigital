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
require_once './../../utiles/connectDBUtiles.php';
require_once './../../utiles/phpExcel/SimpleXLSX.php';
require_once 'insertLog.php';

global $mysqlCon;

$getDepartmentId 
    = "SELECT departamento_id FROM departamento WHERE ceco = ? AND markfordelete = 0";
$getDepartmentByName 
    = "SELECT departamento_id FROM departamento WHERE departamentos_desc = ? AND markfordelete = 0";
$getSingleCopyPrice 
    = "SELECT departamento_id FROM departamento WHERE ceco = ? AND markfordelete = 0";
$recuperaPrecioColor 
    = "SELECT precio FROM detalle d1 inner join tipo t1 on d1.tipo_id = t1.tipo_id AND t1.tipo_desc like '%Color%' WHERE d1.descripcion like '%Color A4%'";
$recuperaPrecioByNCierre 
    = "SELECT precio FROM detalle d1 inner join tipo t1 on d1.tipo_id = t1.tipo_id AND t1.tipo_desc like '%Blanco%' WHERE d1.descripcion='B/N'";
$sentenciaInsertExcel 
    = "INSERT INTO gastos_maquina values (?,?,?,?,?,?,?,?)";
$sentenciaUpdateExcel 
    = "UPDATE gastos_maquina SET 
            byn_unidades = ?, 
            byn_precio = ?, 
            byn_total = ?, 
            color_unidades = ?, 
            color_precio = ?, 
            color_total = ? 
        WHERE 
            departamento_id = ? AND 
            MONTH(periodo) = ? AND 
            YEAR(periodo) = ?";
$existeGastoExcel 
    = "SELECT * FROM gastos_maquina WHERE departamento_id=? AND YEAR(periodo) = ? AND MONTH(periodo) = ?";

/*definimos el json*/
$jsondata = array();
$jsondata["data"] = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['files'])) {
        $errors = [];
        $path = '../../utiles/uploads/';
        $extensions = ['xls', 'xlsx'];

        $all_files = count($_FILES['files']['tmp_name']);

        try{
            for ($i = 0; $i < $all_files; $i++) {
                $fileTmpPath = $_FILES['files']['tmp_name'][$i];
                $file_name = $_FILES['files']['name'][$i];
                $file_tmp = $_FILES['files']['tmp_name'][$i];
                $file_type = $_FILES['files']['type'][$i];
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
                            $mes = "";
                            $anio = "";
                            $i = 0;
                            $precioByN = getCopyPrice('byn');
                            $precioColor = getCopyPrice('color');
                            
                            foreach ($xlsx->rows() as $elt) {
                                if (strrpos($elt[0], 'Fecha Inicial =') > 0) {
                                    $periodo = substr($elt[0], strrpos($elt[0], 'Fecha Inicial =') + 16, 11);
                                    $mes = substr($periodo, 3, 3);
                                    $mes = recoverMonthByText($mes);
                                    $anio = substr($periodo, 7);
                                    $log  = "Periodo Correcto:".PHP_EOL.
                                            date("Y-m-d H:i:s").PHP_EOL.
                                            "Clase: altaExcelMaquina" .PHP_EOL.
                                            "Query:". $elt[0].PHP_EOL.
                                            "Parametro:". $mes .'/'. $anio.PHP_EOL.
                                            "-------------------------".PHP_EOL;
                                            error_log($log, 3, __DIR__ . "/../../log/ficheros.log");
                                }
                                if (strrpos($elt[0], 'ESB') === 0) {
                                    $esb = substr($elt[0], 0, strpos($elt[0], '_'));
                                    
                                    if (strpos($elt[0], ' ') !== false ) {
                                        $esb = substr($esb, 0, strpos($elt[0], ' '));
                                    }
                                    
                                    $departamento = getDepartmentIdByEsb($esb);
    
                                    $totalByN = $elt[5] * $precioByN;
                                    $totalColor =  $elt[4] * $precioColor;
    
                                    $periodoFinal = $anio . '-' . $mes . '-01 23:59:00';
    
                                    insertamosGastos(
                                        $departamento, 
                                        $periodoFinal, $elt[5], 
                                        $precioByN, round($totalByN, 4), 
                                        $elt[4], $precioColor, 
                                        round($totalColor, 4), $anio, $mes
                                    );
                                }
                                if (strrpos($elt[0], 'Enea') === 0) {
                                    $departamento = getDepartmentIdByName($elt[0]);
                                    $totalByN = $elt[5] * $precioByN;
                                    $totalColor =  $elt[4] * $precioColor;
                                    $periodoFinal = $anio . '-' . $mes . '-01 23:59:00';
    
                                    insertamosGastos(
                                        $departamento, 
                                        $periodoFinal, 
                                        $elt[5], 
                                        $precioByN, 
                                        round($totalByN, 4), 
                                        $elt[4], 
                                        $precioColor, 
                                        round($totalColor, 4), 
                                        $anio, 
                                        $mes
                                    );

                                }
                                $i++;
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

/**
 * Adjust the indent of a code block.
 *
 * @param string $mes The position of the token in the token stack
 *
 * @return string
 */
function recoverMonthByText($mes) 
{
    $allMonths = array('01' => 'ene', '02' => 'feb', '03' => 'mar', 
                        '04' => 'abr', '05' => 'may', '06' => 'jun', 
                        '07' => 'jul', '08' => 'ago', '09' => 'sep', 
                        '10' => 'oct', '11' => 'nov', '12' => 'dic');
    $clave = array_search($mes, $allMonths); // $clave = 2;
    return $clave;
}

/**
 * Adjust the indent of a code block.
 *
 * @param string $esb The position of the token in the token stack
 *
 * @return string
 */
function getDepartmentIdByEsb($esb)
{
    global $mysqlCon,$getDepartmentId;
    //Declaracion de parametros
    $departamentoId =  "";
    try{
        /*Prepare Statement*/
        if ($stmt = $mysqlCon->prepare($getDepartmentId)) {
            $stmt->bind_param('s', $esb);
            /*Ejecucion*/
            if ($stmt->execute()) {
                /*Almacenamos el resultSet*/
                $stmt->bind_result($dptoId);
                while ($stmt->fetch()) {
                    $departamentoId = $dptoId;
                    break;
                }
                $stmt->close();
                return $departamentoId;
            }
        }
    } catch (Exception $e){
        $jsondata["success"] = false;
        $jsondata["message"] = $e;
    }
}

/**
 * Adjust the indent of a code block.
 *
 * @param string $nombre The position of the token in the token stack
 *
 * @return string
 */
function getDepartmentIdByName($nombre)
{
    global $mysqlCon,$getDepartmentByName;
    //Declaracion de parametros
    $departamentoId =  "";
    try{
        /*Prepare Statement*/
        if ($stmt = $mysqlCon->prepare($getDepartmentByName)) {
            $stmt->bind_param('s', $nombre);
            /*Ejecucion*/
            if ($stmt->execute()) {
                /*Almacenamos el resultSet*/
                $stmt->bind_result($dptoId);
                while ($stmt->fetch()) {
                    $departamentoId = $dptoId;
                    break;
                }
                $stmt->close();
                return $departamentoId;
            }
        }
    } catch (Exception $e){
        $jsondata["success"] = false;
        $jsondata["message"] = $e;
    }
}

/**
 * Adjust the indent of a code block.
 *
 * @param string $tipo The position of the token in the token stack
 *
 * @return string
 */
function getCopyPrice($tipo)
{

    global $mysqlCon, $recuperaPrecioByNCierre,  $recuperaPrecioColor;
    if ($tipo === 'byn') {
        $queryToExecute = $recuperaPrecioByNCierre;
    } else {
        $queryToExecute = $recuperaPrecioColor;
    }
    $copyPrice = "";
    try{
        /*Prepare Statement*/
        if ($stmt = $mysqlCon->prepare($queryToExecute)) {
            /*Ejecucion*/
            if ($stmt->execute()) {
                /*Almacenamos el resultSet*/
                $stmt->bind_result($precio);
                while ($stmt->fetch()) {
                    $copyPrice = $precio;
                    break;
                }
                $stmt->close();
                return $copyPrice;
            }
        }
    } catch (Exception $e){
        $jsondata["success"] = false;
        $jsondata["message"] = $e;
    }
}

/**
 * Inserta los valores de ByN por solicitud.
 *
 * @param int    $departamento  The position of the token in the token stack
 * @param string $periodoFinal  The position of the token in the token stack
 * @param int    $unidadesByN   The number of spaces to adjust the indent by
 * @param double $precioByN     The position of the token in the token stack
 * @param double $totalByN      The number of spaces to adjust the indent by
 * @param int    $unidadesColor The number of spaces to adjust the indent by
 * @param double $precioColor   The number of spaces to adjust the indent by
 * @param double $totalColor    The position of the token in the token stack
 * @param string $anio          The number of spaces to adjust the indent by
 * @param string $mes           The number of spaces to adjust the indent by
 *
 * @return json
 */
function insertamosGastos($departamento, $periodoFinal, $unidadesByN, $precioByN, $totalByN, $unidadesColor, $precioColor, $totalColor, $anio, $mes)
{
    global $mysqlCon, $sentenciaInsertExcel;
    $parametros = "Query:" . $sentenciaInsertExcel . 
    " departamento:" . $departamento . 
    " periodoFinal:" . $periodoFinal . 
    " unidadesByN:" . $unidadesByN . 
    " precioByN:" . $precioByN . 
    " totalByN:" . $totalByN . 
    " unidadesColor:" . $unidadesColor . 
    " precioColor:" . $precioColor . 
    " totalColor:" . $totalColor;
    $path_parts = pathinfo(__FILE__);

    if (existeRegistro($departamento, $anio, $mes)) {
        if ($stmt = $mysqlCon->prepare($sentenciaInsertExcel)) {
            $stmt->bind_param('isiddidd', $departamento, $periodoFinal, $unidadesByN, $precioByN, $totalByN, $unidadesColor, $precioColor, $totalColor);
            if (!$stmt->execute()) {
                crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:' . $mysqlCon->error, "error");
                $jsondata["success"] = false;
                $jsondata["message"] = "Fallo la ejecucion: " . $sentenciaInsertExcel;
            } else {
                crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Insercion correcta de gastos', "correcto");
                $jsondata["success"] = true;
                $jsondata["message"] = "Inserción Correcta";
            }
            $stmt->close();
        } else {
            crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:' . $mysqlCon->error, "error");
            $jsondata["success"] = false;
            $jsondata["message"] = "Fallo la ejecucion: " .  $mysqlCon->error;
        }
    } else {
        actualizamosGastos($departamento, $unidadesByN, $precioByN, $totalByN, $unidadesColor, $precioColor, $totalColor, $anio, $mes);
    }
}

/**
 * Inserta los valores de ByN por solicitud.
 *
 * @param int    $departamento  The position of the token in the token stack
 * @param int    $unidadesByN   The number of spaces to adjust the indent by
 * @param double $precioByN     The position of the token in the token stack
 * @param double $totalByN      The number of spaces to adjust the indent by
 * @param int    $unidadesColor The number of spaces to adjust the indent by
 * @param double $precioColor   The number of spaces to adjust the indent by
 * @param double $totalColor    The position of the token in the token stack
 * @param string $anio          The number of spaces to adjust the indent by
 * @param string $mes           The number of spaces to adjust the indent by
 *
 * @return json
 */
function actualizamosGastos($departamento,$unidadesByN, $precioByN, $totalByN, $unidadesColor, $precioColor, $totalColor, $anio, $mes)
{
    global $mysqlCon, $sentenciaUpdateExcel;
    $parametros = "Query:" . $sentenciaUpdateExcel . 
    " unidadesByN:" . $unidadesByN . 
    " precioByN:" . $precioByN . 
    " totalByN:" . $totalByN . 
    " unidadesColor:" . $unidadesColor . 
    " precioColor:" . $precioColor . 
    " totalColor:" . $totalColor . 
    " departamento:" . $departamento . 
    " mes:" . $mes . 
    " anio:" . $anio;
    $path_parts = pathinfo(__FILE__);

    /*definimos el json*/
    $jsondata = array();
    $jsondata["data"] = array();
    if ($stmt = $mysqlCon->prepare($sentenciaUpdateExcel)) {
        $stmt->bind_param('iddiddiss', $unidadesByN, $precioByN, $totalByN, $unidadesColor, $precioColor, $totalColor, $departamento, $mes, $anio);
        if (!$stmt->execute()) {
            $jsondata["success"] = false;
            $jsondata["message"] = "Fallo la ejecucion: " . $sentenciaUpdateExcel;
            crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:' . $mysqlCon->error, "error");
        } else {
            crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Insercion correcta de gastos', "correcto");
            $jsondata["success"] = true;
            $jsondata["message"] = "Actualizacion Correcta";
        }
    } else {
        crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:' . $mysqlCon->error, "error");
        $jsondata["success"] = false;
        $jsondata["message"] = "Fallo la ejecucion: " .  $mysqlCon->error;
    }
}

/**
 * Inserta los valores de ByN por solicitud.
 *
 * @param int    $departamento The position of the token in the token stack
 * @param string $anio         The number of spaces to adjust the indent by
 * @param string $mes          The number of spaces to adjust the indent by
 *
 * @return json
 */
function existeRegistro($departamento, $anio, $mes)
{
    global $mysqlCon, $existeGastoExcel;
    $insertamos = true;
    try {
        /*Prepare Statement*/
        if ($stmt = $mysqlCon->prepare($existeGastoExcel)) {
            /*Asociacion de parametros*/
            $stmt->bind_param('iss', $departamento, $anio, $mes);
            /*Ejecucion*/
            $stmt->execute();
            $stmt->store_result();
            $row_cnt = $stmt->num_rows;
            if ($row_cnt > 0) {
                $insertamos = false;
            }
                
            /*Cerramos la conexion*/
            $stmt->close();
        }
        return $insertamos;
    } catch (Exception $e) {
        $jsondata["success"] = false;
        $jsondata["message"] = "Fallo la ejecucion: " .  $e;
    }
}

?>