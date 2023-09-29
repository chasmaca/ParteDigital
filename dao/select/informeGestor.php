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

require_once  'query.php';
require_once '../../utiles/connectDBUtiles.php';
require_once './../insert/insertLog.php';

//Declaracion de parametros
$periodo = "";
$departamento = "";
$subdepartamento = "";
$tipo = "";
$mes = "";
$anio = "";

//Recogemos los valores
if (isset($_POST['periodoHidden'])) {
    $periodo = $_POST['periodoHidden'];
    $periodoPartido = explode("/", $periodo);
    $mes = $periodoPartido[0];
    $anio = $periodoPartido[1];
}

if (isset($_POST['departamentoHidden'])) {
    $departamento = $_POST['departamentoHidden'];
    if ($departamento === '0') {
        $departamento = '%';
    }
}

if (isset($_POST['subdepartamentoHidden'])) {
    $subdepartamento = $_POST['subdepartamentoHidden'];
    if ($subdepartamento === '0') {
        $subdepartamento = '%';
    }
}

if (isset($_POST['tipoHidden'])) {
    $tipo = $_POST['tipoHidden'];
}

if ($tipo === 'global' && $periodo !== "") {
    // informeGlobal($mes, $anio, $departamento, $subdepartamento);
    informeGlobalUnificado($mes, $anio, $departamento, $subdepartamento);
} elseif ($tipo === 'detalle' && $periodo !== "") {
    informeDetalleGestor($anio, $mes, $departamento, $subdepartamento);
}

/**
 * Function: informeGlobal
 * 
 * @param int $mes               Id
 * @param int $anio              Id
 * @param int $departamentoId    Id
 * @param int $subdepartamentoId Id
 * 
 * @return json
 */
function informeGlobalUnificado($mes, $anio, $departamentoId, $subdepartamentoId)
{
    global $consultaTodasImpresionesGlobalNoMaq, 
    $consultaImpresionesGlobalDptoNoMaq,
    $consultaImpresionesGlobalDptoSubdptoNoMaq, 
    $consultaTodasMaquinas, $consultaTodasMaquinasDpto,
    $consultaTodasImpresoras, $consultaTodasImpresorasDpto,
    $mysqlCon;

    $jsondata = array();
    $jsondata["data"] = array();
    $jsondataMaquina["data"] = array();
    $jsondataImpresora["data"] = array();
    $path_parts = pathinfo(__FILE__);

    try{

        $consultaFinal = $consultaTodasImpresionesGlobalNoMaq;
        $consultaFinalMaquina = $consultaTodasMaquinas;
        $consultaFinalImpresora = $consultaTodasImpresoras;

        $parametros = "Query:" . $consultaFinal . " anio:" . $anio . " mes:" . $mes;
        $parametrosMaquina = "Query:" . $consultaFinalMaquina . " anio:" . $anio . " mes:" . $mes;
        $parametrosImpresora = "Query:" . $consultaFinalImpresora . " anio:" . $anio . " mes:" . $mes . " departamento:" . $departamentoId;

        if ($departamentoId !== '%' && $subdepartamentoId === '%') {
            $consultaFinal = $consultaImpresionesGlobalDptoNoMaq;
            $consultaFinalMaquina = $consultaTodasMaquinasDpto;
            $consultaFinalImpresora = $consultaTodasImpresorasDpto;
            
            $parametros = "Query:" . $consultaFinal . " anio:" . $anio . " mes:" . $mes . " departamento:" . $departamentoId;
            $parametrosMaquina = "Query:" . $consultaFinalMaquina . " anio:" . $anio . " mes:" . $mes . " departamento:" . $departamentoId;
            $parametrosImpresora = "Query:" . $consultaFinalImpresora . " anio:" . $anio . " mes:" . $mes . " departamento:" . $departamentoId;

        }
    
        if ($subdepartamentoId !== '%') {
            $consultaFinal = $consultaImpresionesGlobalDptoSubdptoNoMaq;
            $consultaFinalMaquina = $consultaTodasMaquinasDpto;
            $consultaFinalImpresora = $consultaTodasImpresorasDpto;
            
            $parametrosMaquina = "Query:" . $consultaFinalMaquina . " anio:" . $anio . " mes:" . $mes . " departamento:" . $departamentoId;
            $parametros = "Query:" . $consultaFinal . " anio:" . $anio . " mes:" . $mes . " departamento:" . $departamentoId . " subdepartamento:" . $subdepartamentoId;
            $parametrosImpresora = "Query:" . $consultaFinalImpresora . " anio:" . $anio . " mes:" . $mes . " departamento:" . $departamentoId;

        }

        if ($stmt = $mysqlCon->prepare($consultaFinal)) {
            if ($departamentoId === '%') {
                $stmt->bind_param("ii", $anio, $mes);
            }
            if ($departamentoId !== '%' && $subdepartamentoId === '%') {
                $stmt->bind_param("iii", $anio, $mes, $departamentoId);
            }
            if ($subdepartamentoId !== '%') {
                $stmt->bind_param("iiii", $anio, $mes, $departamentoId, $subdepartamentoId);
            }
            
            if (!$stmt->execute()) {

                $jsondata["success"] = false;
                $jsondata["message"] =  "Error consultando informe " . $parametros;
                crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:' . $mysqlCon->error, "error");
                
            } else {

                $stmt->bind_result($ceco, $departamento, $espiral, $encolado, $varios1, $color, $blancoNegro, $varios2);

                while ($stmt->fetch()) {
                    $tmp = array();
                    $tmp["ceco"] = utf8_encode($ceco);
                    $tmp["departamentos_desc"] = utf8_encode($departamento);
                    $tmp["espiral"] = utf8_encode($espiral);
                    $tmp["encolado"] = utf8_encode($encolado);
                    $tmp["varios1"] = utf8_encode($varios1);
                    $tmp["color"] = utf8_encode($color);
                    $tmp["blancoNegro"] = utf8_encode($blancoNegro);
                    $tmp["varios2"] = utf8_encode($varios2);
                    $tmp["maquinas"] = utf8_encode("0,0000");
                    $tmp["impresoras"] = utf8_encode("0,0000");
                    /*Asociamos el resultado en forma de array en el json*/
                    array_push($jsondata["data"], $tmp);
                }
                $jsondata["success"] = true;
            }

        } else {
            $jsondata["success"] = false;
            $jsondata["message"] = "Error consultando informe " . $mysqlCon->error;
            crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:' . $mysqlCon->error, "error");
        }

        //MAQUINAS
        if ($stmtMaquina = $mysqlCon->prepare($consultaFinalMaquina)) {

            if ($departamentoId === '%') {
                $stmtMaquina->bind_param("ii", $anio, $mes);
            }
            if ($departamentoId !== '%' && $subdepartamentoId === '%') {
                $stmtMaquina->bind_param("iii", $anio, $mes, $departamentoId);
            }
            if ($subdepartamentoId !== '%') {
                $stmtMaquina->bind_param("iii", $anio, $mes, $departamentoId);
            }
        
            if (!$stmtMaquina->execute()) {

                $jsondata["success"] = false;
                $jsondata["message"] =  "Error consultando informe " . $parametrosMaquina;
                crearLog($path_parts['filename'], __FUNCTION__, $parametrosMaquina, 'Exception:' . $mysqlCon->error, "error");
                
            } else {

                $stmtMaquina->bind_result($cecoMaquina, $departamento, $espiral, $encolado, $varios1, $color, $blancoNegro, $varios2, $colorM, $bynM);

                while ($stmtMaquina->fetch()) {
                    $tmp = array();
                    if (utf8_encode($cecoMaquina)!== '' && utf8_encode($cecoMaquina)!== null) {
                        $tmp["ceco"] = utf8_encode($cecoMaquina);
                        $tmp["departamentos_desc"] = utf8_encode($departamento);
                        $tmp["espiral"] = utf8_encode($espiral);
                        $tmp["encolado"] = utf8_encode($encolado);
                        $tmp["varios1"] = utf8_encode($varios1);
                        $tmp["color"] = utf8_encode($color);
                        $tmp["blancoNegro"] = utf8_encode($blancoNegro);
                        $tmp["varios2"] = utf8_encode($varios2);
                        $tmp["maquinas"] = strval($colorM + $bynM);
                        $tmp["impresoras"] = utf8_encode("0,0000");
                        /*Asociamos el resultado en forma de array en el json*/
                        array_push($jsondataMaquina["data"], $tmp);
                    }
                }

                $lineaMaquina = array();

                foreach ($jsondataMaquina["data"] as $linea) {
                    $validaMaquina = false;
                    $lineaMaquina = $linea;
                    foreach ($jsondata["data"] as $key => $val) {
                        if ($val['ceco'] === $linea['ceco']) {
                            $validaMaquina = true;
                            if ($jsondata["data"][$key]['maquinas'] === '') {
                                $jsondata["data"][$key]['maquinas'] = strval($linea['maquinas']);
                            } else {
                                $jsondata["data"][$key]['maquinas'] = strval(doubleval($jsondata["data"][$key]['maquinas']) + $linea['maquinas']);
                            }
                        }
                    }
                    if ($validaMaquina === false) {
                        array_push($jsondata["data"], $lineaMaquina);
                    }
                }
                $jsondata["success"] = true;
            }

        } else {
            $jsondata["success"] = false;
            $jsondata["message"] = "Error consultando informe " . $mysqlCon->error;
            crearLog($path_parts['filename'], __FUNCTION__, $parametrosMaquina, 'Exception:' . $mysqlCon->error, "error");
        }

        // impresoras
        if ($stmtImpresora = $mysqlCon->prepare($consultaFinalImpresora)) {

            if ($departamentoId === '%') {
                $stmtImpresora->bind_param("ii", $anio, $mes);
            }
            if ($departamentoId !== '%' && $subdepartamentoId === '%') {
                $stmtImpresora->bind_param("iii", $anio, $mes, $departamentoId);
            }
            if ($subdepartamentoId !== '%') {
                $stmtImpresora->bind_param("iii", $anio, $mes, $departamentoId);
            }
        
            if (!$stmtImpresora->execute()) {

                $jsondata["success"] = false;
                $jsondata["message"] =  "Error consultando informe " . $parametrosImpresora;
                crearLog($path_parts['filename'], __FUNCTION__, $parametrosImpresora, 'Exception:' . $mysqlCon->error, "error");
                
            } else {

                $stmtImpresora->bind_result($cecoImpresora, $departamento, $espiral, $encolado, $varios1, $color, $blancoNegro, $varios2, $colorI, $bynI);
                
                while ($stmtImpresora->fetch()) {

                    $tmp = array();
                    if ($cecoImpresora !== '' && $cecoImpresora != null) {
                        $tmp["ceco"] = utf8_encode($cecoImpresora);
                        $tmp["departamentos_desc"] = utf8_encode($departamento);
                        $tmp["espiral"] = utf8_encode($espiral);
                        $tmp["encolado"] = utf8_encode($encolado);
                        $tmp["varios1"] = utf8_encode($varios1);
                        $tmp["color"] = utf8_encode($color);
                        $tmp["blancoNegro"] = utf8_encode($blancoNegro);
                        $tmp["varios2"] = utf8_encode($varios2);
                        $tmp["maquinas"] = utf8_encode("0,0000");
                        $tmp["impresoras"] = strval($colorI + $bynI);
                        /*Asociamos el resultado en forma de array en el json*/
                        array_push($jsondataImpresora["data"], $tmp);
                    }
                }
                $lineaImpresora = array();
                
                foreach ($jsondataImpresora["data"] as $linea) {
                    $validaImpresora = false;
                    $lineaImpresora = $linea;
                    foreach ($jsondata["data"] as $key => $val) {
                        if ($val['ceco'] === $linea['ceco']) {
                            $validaImpresora = true;
                            if ($jsondata["data"][$key]['impresoras'] === '') {
                                $jsondata["data"][$key]['impresoras'] = strval($linea['impresoras']);
                            }
                            if ($jsondata["data"][$key]['impresoras'] !== '') {
                                $jsondata["data"][$key]['impresoras'] = strval(doubleval($jsondata["data"][$key]['impresoras']) + $linea['impresoras']);
                            }
                        }
                    }

                    if ($validaImpresora === false) {
                        array_push($jsondata["data"], $lineaImpresora);
                    }
                }
                $jsondata["success"] = true;
            }

        } else {
            $jsondata["success"] = false;
            $jsondata["message"] = "Error consultando informe " . $mysqlCon->error;
            crearLog($path_parts['filename'], __FUNCTION__, $parametrosImpresora, 'Exception:' . $mysqlCon->error, "error");
        }

    } catch (Exception $e) {
        $jsondata["success"] = false;
        $jsondata["message"] = "Error Insertando Articulo" . $e;
        crearLog($path_parts['filename'], __FUNCTION__, $parametros, 'Exception:' . $e, "error");
    } finally {
        $stmt->close();
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata, JSON_FORCE_OBJECT);
    }
}

/**
 * Function: informeGlobal
 * 
 * @param int $mes               Id
 * @param int $anio              Id
 * @param int $departamentoId    Id
 * @param int $subdepartamentoId Id
 * 
 * @return json
 */
function informeGlobal($mes, $anio, $departamentoId, $subdepartamentoId)
{
    global     $consultaTodasImpresionesGlobal,
        $consultaImpresionesGlobalDpto,
        $consultaImpresionesGlobalDptoSubdpto,
        $mysqlCon;

    $jsondata = array();
    $jsondata["data"] = array();

    $consultaFinal = $consultaTodasImpresionesGlobal;

    if ($departamentoId !== '%') {
        $consultaFinal = $consultaImpresionesGlobalDpto;
    }

    if ($subdepartamentoId !== '%') {
        $consultaFinal = $consultaImpresionesGlobalDptoSubdpto;
    }

    if ($stmt = $mysqlCon->prepare($consultaFinal)) {
        if ($departamentoId === '%') {
            $stmt->bind_param("iiiiii", $anio, $mes, $anio, $mes, $anio, $mes);
        }

        if ($departamentoId !== '%' && $subdepartamentoId === '%') {
            $stmt->bind_param("iiiiiiiii", $anio, $mes, $departamentoId, $anio, $mes, $departamentoId, $anio, $mes, $departamentoId);
        }

        if ($departamentoId !== '%' && $subdepartamentoId !== '%') {
            $stmt->bind_param("iiiiiiiiii", $anio, $mes, $departamentoId, $subdepartamentoId, $anio, $mes, $departamentoId, $anio, $mes, $departamentoId);
        }

        $stmt->execute();
        $stmt->bind_result($ceco, $departamento, $espiral, $encolado, $varios1, $color, $blancoNegro, $varios2);

        while ($stmt->fetch()) {
            $tmp = array();
            $tmp["ceco"] = utf8_encode($ceco);
            $tmp["departamentos_desc"] = utf8_encode($departamento);
            $tmp["espiral"] = utf8_encode($espiral);
            $tmp["encolado"] = utf8_encode($encolado);
            $tmp["varios1"] = utf8_encode($varios1);
            $tmp["color"] = utf8_encode($color);
            $tmp["blancoNegro"] = utf8_encode($blancoNegro);
            $tmp["varios2"] = utf8_encode($varios2);
            /*Asociamos el resultado en forma de array en el json*/
            array_push($jsondata["data"], $tmp);
        }
        $stmt->close();
        $jsondata["success"] = true;
    } else {
        $jsondata["success"] = false;
        die("Errormessage: " . $mysqlCon->error);
    }


    header('Content-type: application/json; charset=utf-8');
    echo json_encode($jsondata, JSON_FORCE_OBJECT);
}

/**
 * Function: informeDetalleGestor
 * 
 * @param int $anio              Id
 * @param int $mes               Id
 * @param int $departamentoId    Id
 * @param int $subdepartamentoId Id
 * 
 * @return json
 */
function informeDetalleGestor($anio, $mes, $departamentoId, $subdepartamentoId)
{
    global 
        $consultaTodasImpresionesDetalle,
        $consultaTodasImpresionesDetalleDpto,
        $consultaTodasImpresionesDetalleDptoSubdpto,
        $mysqlCon;
    $jsondata = array();
    $jsondata["data"] = array();

    if ($departamentoId === '%') {
        $consultaFinal = $consultaTodasImpresionesDetalle;
    }

    if ($departamentoId !== '%' && $subdepartamentoId === "%") {
        $consultaFinal = $consultaTodasImpresionesDetalleDpto;
    }

    if ($subdepartamentoId !== '%') {
        $consultaFinal = $consultaTodasImpresionesDetalleDptoSubdpto;
    }

    try {
        if ($stmt = $mysqlCon->prepare($consultaFinal)) {

            if ($departamentoId === '%') {
                $stmt->bind_param("iiiiii", $anio, $mes, $anio, $mes, $anio, $mes);
            }
            if ($departamentoId !== '%' && $subdepartamentoId === "%") {
                $stmt->bind_param("iiiiiiiii", $anio, $mes, $departamentoId, $anio, $mes, $departamentoId, $anio, $mes, $departamentoId);
            }
            if ($subdepartamentoId !== '%') {
                $stmt->bind_param(
                    "iiiiiiiiii", 
                    $anio, 
                    $mes, 
                    $departamentoId,
                    $subdepartamentoId,
                    $anio, 
                    $mes, 
                    $departamentoId, 
                    $anio, 
                    $mes, 
                    $departamentoId
                );
            }

            $stmt->execute();

            $stmt->bind_result(
                $solicitud, $ceco, $departamento,
                $subdepartamento, $treinta,
                $nombreSolicitante, $apellidosSolicitante, $descripcion,
                $fecha, $espiral, $encolado, $varios1, $color, 
                $blancoNegro, $varios2
            );

            while ($stmt->fetch()) {
                $tmp = array();
                $tmp["codigo"] = $solicitud;
                $tmp["fechaCierre"] = $fecha;
                $tmp["ceco"] = utf8_encode($ceco);
                $tmp["departamentos_desc"] = utf8_encode($departamento);
                $tmp["subdepartamentos_desc"] = utf8_encode($subdepartamento);
                $tmp["treinta"] = utf8_encode($treinta);
                $tmp["nombre"] = utf8_encode($nombreSolicitante) . ' ' . utf8_encode($apellidosSolicitante);
                $tmp["descripcion"] = utf8_encode($descripcion);
                $tmp["espiral"] = utf8_encode($espiral);
                $tmp["encolado"] = utf8_encode($encolado);
                $tmp["varios1"] = utf8_encode($varios1);
                $tmp["color"] = utf8_encode($color);
                $tmp["blancoNegro"] = utf8_encode($blancoNegro);
                $tmp["varios2"] = utf8_encode($varios2);
                /*Asociamos el resultado en forma de array en el json*/
                array_push($jsondata["data"], $tmp);
            }

            $jsondata["success"] = true;
        } else {
            $jsondata["success"] = false;
            die("Errormessage: " . $mysqlCon->error);
        }
    } catch (Exception $e) {
        $jsondata["success"] = false;
        die("Errormessage: " . $mysqlCon->error);
    } finally {
        $stmt->close();
        /*Devolvemos el JSON con los datos de la consulta*/
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata, JSON_FORCE_OBJECT);
    }
}

/**
 * Function: informeGlobal
 * 
 * @param int $dpto Id
 * 
 * @return array
 */
function recuperaSubXDpto($dpto)
{
    global $recuperaSubdptoXDpto, $mysqlCon;
    $valores = "";
    $valoresArray =  array();


    /*Prepare Statement*/
    if ($stmt = $mysqlCon->prepare($recuperaSubdptoXDpto)) {

        /*Asociacion de parametros*/
        $stmt->bind_param('i', $dpto);

        /*Ejecucion*/
        $stmt->execute();

        $stmt->bind_result($col1, $col2, $col3, $col4);

        /*Recogemos el resultado en la variable*/
        while ($stmt->fetch()) {
            $valores = array($col1, $col2, $col3, $col4);
            array_push($valoresArray, $valores);
        }
        /*Cerramos la conexion*/
        $stmt->close();
    } else {
        echo $stmt->error;
    }

    return $valoresArray;
}
