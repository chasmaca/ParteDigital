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
require_once "query.php";
require_once "../../utiles/connectDBUtiles.php";

//Declaracion de parametros
$periodo = "";
$departamento = "";
$subdepartamento = "";
$tipo = "";
$mes = "";
$anio = "";
$usuario = "";

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

if (isset($_POST['usuarioHidden'])) {
    $usuario = $_POST['usuarioHidden'];
}

if ($tipo === 'global' && $periodo !== "") {
    informeGlobalValidador($mes, $anio, $departamento, $subdepartamento, $usuario);
} elseif ($tipo === 'detalle' && $periodo !== "") {
    informeDetalle($anio, $mes, $departamento, $subdepartamento, $usuario);
}

/**
 * Carga todos los subdepartamentos por id de departamento
 *
 * @param int $mes             Id de Departamento
 * @param int $anio            Id de Departamento
 * @param int $departamento    Id de Departamento
 * @param int $subdepartamento Id de Departamento
 * @param int $usuario         Id de Departamento
 * 
 * @return json
 */
function informeGlobalValidador($mes, $anio, $departamento,$subdepartamento, $usuario)
{
    global $mysqlCon;
    $recuperaValidadorGlobalTodosDpto = "SELECT
                                            de1.ceco AS codigo,
                                            de1.departamentos_desc AS departamento,
                                            sum(td.preciototal) AS precio, 
                                            t2.tipo_id as tipo
                                        FROM
                                            solicitud s1 
                                            INNER JOIN trabajo t1 ON s1.solicitud_id = t1.solicitud_id 
                                            INNER JOIN departamento de1 ON s1.departamento_id = de1.departamento_id AND de1.markfordelete=0 
                                            INNER JOIN trabajodetalle td ON td.solicitud_id = s1.solicitud_id 
                                            INNER JOIN tipo t2 on t2.tipo_id = td.tipo_id
                                        WHERE
                                            s1.status_id = 6 AND 
                                            s1.departamento_id IN(    SELECT ud1.departamento_id FROM usuariodepartamento ud1 WHERE ud1.usuario_id = ?) AND 
                                            MONTH(s1.fecha_validacion) = ? AND 
                                            YEAR(s1.fecha_validacion) = ?  
                                        group by de1.ceco, t2.tipo_id";

    $recuperaValidadorGlobalDpto = "SELECT
                                        de1.ceco AS codigo,
                                        de1.departamentos_desc AS departamento,
                                        sum(td.preciototal) AS precio, 
                                        t2.tipo_id as tipo
                                    FROM
                                        solicitud s1 
                                        INNER JOIN trabajo t1 ON s1.solicitud_id = t1.solicitud_id 
                                        INNER JOIN departamento de1 ON s1.departamento_id = de1.departamento_id AND de1.markfordelete=0 
                                        INNER JOIN trabajodetalle td ON td.solicitud_id = s1.solicitud_id 
                                        INNER JOIN tipo t2 on t2.tipo_id = td.tipo_id
                                    WHERE
                                        s1.status_id = 6 AND 
                                        s1.departamento_id IN(    SELECT ud1.departamento_id FROM usuariodepartamento ud1 WHERE ud1.usuario_id = ?) AND 
                                        MONTH(s1.fecha_validacion) = ? AND 
                                        YEAR(s1.fecha_validacion) = ? AND
                                        de1.departamento_id = ? 
                                    group by de1.ceco, t2.tipo_id";
    $recuperaValidadorGlobalDptoSubDpto = "SELECT
                                                de1.ceco AS codigo,
                                                de1.departamentos_desc AS departamento,
                                                sum(td.preciototal) AS precio, 
                                                t2.tipo_id as tipo
                                            FROM
                                                solicitud s1 
                                                INNER JOIN trabajo t1 ON s1.solicitud_id = t1.solicitud_id 
                                                INNER JOIN departamento de1 ON s1.departamento_id = de1.departamento_id AND de1.markfordelete=0 
                                                INNER JOIN trabajodetalle td ON td.solicitud_id = s1.solicitud_id 
                                                INNER JOIN tipo t2 on t2.tipo_id = td.tipo_id
                                            WHERE
                                                s1.status_id = 6 AND 
                                                s1.departamento_id IN(    SELECT ud1.departamento_id FROM usuariodepartamento ud1 WHERE ud1.usuario_id = ?) AND 
                                                MONTH(s1.fecha_validacion) = ? AND 
                                                YEAR(s1.fecha_validacion) = ? AND
                                                de1.departamento_id = ? AND
                                                s1.subdepartamento_id = ?
                                            group by de1.ceco, t2.tipo_id";


    /*definimos el json*/
    $jsondata = array();
    $jsondata["data"] = array();
    $contadorLinea = 0;
    $contadorOperacion = 0;

    if ($departamento === '%') {
        $queryEjecucion = $recuperaValidadorGlobalTodosDpto;
    }
    if ($departamento !== '%' && $subdepartamento === '%') {
        $queryEjecucion = $recuperaValidadorGlobalDpto;
    }
    if ($departamento !== '%' && $subdepartamento !== '%') {
        $queryEjecucion = $recuperaValidadorGlobalDptoSubDpto;
    }
    try {
        if ($stmt = $mysqlCon->prepare($queryEjecucion)) {

            if ($departamento === '%') {
                $stmt->bind_param("iss", $usuario, $mes, $anio);
            }
            if ($departamento !== '%' && $subdepartamento === '%') {
                $stmt->bind_param("issi", $usuario, $mes, $anio, $departamento);
            }
            if ($departamento !== '%' && $subdepartamento !== '%') {
                $stmt->bind_param("issii", $usuario, $mes, $anio, $departamento, $subdepartamento);
            }

            /*Ejecucion*/
            if ($stmt->execute()) {
                $stmt->bind_result($codigo, $departamento, $precio, $tipo);
    
                while ($stmt->fetch()) {

                    $validador = false;

                    foreach ($jsondata["data"] as $linea) {
                        if (utf8_encode($departamento) == utf8_encode($linea['departamento'])) {
                            //Actualizacion
                            if (utf8_encode($tipo) === "1" || utf8_encode($tipo) === "2") {
                                if ($jsondata["data"][$contadorLinea - 1]['encuadernacion'] == 0) {
                                    $jsondata["data"][$contadorLinea - 1]['encuadernacion']= utf8_encode($precio);
                                } else {
                                    $encuadernacionTmp = floatval($jsondata["data"][$contadorLinea - 1]['encuadernacion']);
                                    $encuadernacionTmp2 = floatval($precio);
                                    $jsondata["data"][$contadorLinea - 1]['encuadernacion']= $encuadernacionTmp + $encuadernacionTmp2;
                                }
                                
                            }
                            if (utf8_encode($tipo) === "5") {
                                if ($jsondata["data"][$contadorLinea - 1]['byn'] == 0) {
                                    $jsondata["data"][$contadorLinea - 1]['byn']= utf8_encode($precio);
                                } else {
                                    $blancoTmp = floatval($jsondata["data"][$contadorLinea - 1]['byn']);
                                    $blancoTmp2 = floatval($precio);
                                    $jsondata["data"][$contadorLinea - 1]['byn']= $blancoTmp + $blancoTmp2;
        
                                    //$jsondata["data"][$contadorLinea - 1]['byn']= utf8_encode($precio);
                                }
                                
                            }
                            if (utf8_encode($tipo) === "4") {
                                if ($jsondata["data"][$contadorLinea - 1]['color'] == 0) {
                                    $jsondata["data"][$contadorLinea - 1]['color']= utf8_encode($precio);
                                } else {
                                    $colorTmp = floatval($jsondata["data"][$contadorLinea - 1]['color']);
                                    $colorTmp2 = floatval($precio);
                                    $jsondata["data"][$contadorLinea - 1]['color']= $colorTmp + $colorTmp2;
                                }
                                
                            }
                            if (utf8_encode($tipo) === "3" || utf8_encode($tipo) === "6" || utf8_encode($tipo) === "7") {
                                if ($jsondata["data"][$contadorLinea - 1]['varios'] == 0) {
                                    $jsondata["data"][$contadorLinea - 1]['varios']= utf8_encode($precio);
                                } else {
                                    $variosTmp = floatval($jsondata["data"][$contadorLinea - 1]['varios']);
                                    $variosTmp2 = floatval($precio);
                                    $jsondata["data"][$contadorLinea - 1]['varios']= $variosTmp + $variosTmp2;
                                }
                            }
                             $validador = true;
                        }
                    }

                    if ($validador == false) {
                        if (mb_detect_encoding($departamento) === 'UTF-8') {

                            $tmp = array();
                            //    $tmp["esb"] = $esb;
                            $tmp["codigo"] = trim($codigo);
                            $tmp["departamento"] = $departamento;
                            //    $tmp["subdepartamento"] = $subdepartamento;
                            if (utf8_encode($tipo) === "1" || utf8_encode($tipo) === "2") {
                                $tmp["encuadernacion"] = utf8_encode($precio);
                            } else {
                                $tmp["encuadernacion"] = utf8_encode("0");
                            }
                            if (utf8_encode($tipo) === "5") {
                                $tmp["byn"] = utf8_encode($precio);
                            } else {
                                $tmp["byn"] = utf8_encode("0");
                            }
                            if (utf8_encode($tipo) === "4") {
                                $tmp["color"] = utf8_encode($precio);
                            } else {
                                $tmp["color"] = utf8_encode("0");
                            }
                            if (utf8_encode($tipo) === "3" || utf8_encode($tipo) === "6" || utf8_encode($tipo) === "7") {
                                $tmp["varios"] = utf8_encode($precio);
                            } else {
                                $tmp["varios"] = utf8_encode("0");
                            }
                        } else {
                            $tmp = array();
                            //    $tmp["esb"] = $esb;
                            $tmp["codigo"] = trim($codigo);
                            $tmp["departamento"] = $departamento;
                            //    $tmp["subdepartamento"] = $subdepartamento;
                            if (utf8_encode($tipo) === "1" || utf8_encode($tipo) === "2") {
                                $tmp["encuadernacion"] = utf8_encode($precio);
                            } else {
                                $tmp["encuadernacion"] = utf8_encode("0");
                            }
                            if (utf8_encode($tipo) === "5") {
                                $tmp["byn"] = utf8_encode($precio);
                            } else {
                                $tmp["byn"] = utf8_encode("0");
                            }
                            if (utf8_encode($tipo) === "4") {
                                $tmp["color"] = utf8_encode($precio);
                            } else {
                                $tmp["color"] = utf8_encode("0");
                            }
                            if (utf8_encode($tipo) === "3" || utf8_encode($tipo) === "6" || utf8_encode($tipo) === "7") {
                                $tmp["varios"] = utf8_encode($precio);
                            } else {
                                $tmp["varios"] = utf8_encode("0");
                            }
                        }
                        $contadorLinea++;
                        /*Asociamos el resultado en forma de array en el json*/
                        array_push($jsondata["data"], $tmp);
                    } 
                    $contadorOperacion++;
                }

                /*Asociamos el correcto funcionamiento al json para comprobar en el js*/
                $jsondata["success"] = true;
            } else {
                $jsondata["success"] = false;
                $jsondata["message"] = $queryEjecucion . '-> Anio:' . $anio . 
                ' Mes:'. $mes . ' Departamento:'. $departamento . 
                ' Subepartamento:'. $subdepartamento . ' Usuario:'. $usuario;
            }
        } else {
            $jsondata["success"] = false;
            $jsondata["message"] = $queryEjecucion . '-> Anio:' . $anio . 
            ' Mes:'. $mes . ' Departamento:'. $departamento . 
            ' Subepartamento:'. $subdepartamento . ' Usuario:'. $usuario;
        }
        
    } catch (Exception $th) {
        $jsondata["success"] = false;
        $jsondata["message"] = "Exception:" . $th . " Query:" . $queryEjecucion . 
        '-> Anio:' . $anio . ' Mes:'. $mes . ' Departamento:'. $departamento . 
        ' Subepartamento:'. $subdepartamento . ' Usuario:'. $usuario;
    } finally {
        /*Devolvemos el JSON con los datos de la consulta*/
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata, JSON_FORCE_OBJECT);
    }
}

/**
 * Carga todos los subdepartamentos por id de departamento
 *
 * @param int $anio            Id de Departamento
 * @param int $mes             Id de Departamento
 * @param int $departamento    Id de Departamento
 * @param int $subdepartamento Id de Departamento
 * @param int $usuario         Id de Departamento
 * 
 * @return json
 */
function informeDetalle($anio, $mes, $departamento, $subdepartamento, $usuario)
{

    global $mysqlCon;
    $recuperaInformeDetalleValidaAuth = "SELECT
                                            s1.solicitud_id AS solicitudId,
                                            sd1.treintabarra AS esb,
                                            de1.ceco AS codigo,
                                            de1.departamentos_desc AS departamento,
                                            s1.nombre_solicitante AS nombre,
                                            s1.apellidos_solicitante AS apellidos,
                                            TRIM(s1.descripcion_solicitante) AS descripcion,
                                            s1.fecha_cierre AS fecha,
                                            td.preciototal AS precio, 
                                            t2.tipo_id as tipo
                                        FROM
                                            solicitud s1 
                                            INNER JOIN trabajo t1 ON s1.solicitud_id = t1.solicitud_id 
                                            INNER JOIN departamento de1 ON s1.departamento_id = de1.departamento_id AND de1.markfordelete=0 
                                            INNER JOIN subdepartamento sd1 on sd1.departamento_id = s1.departamento_id and 
                                            sd1.subdepartamento_id = s1.subdepartamento_id
                                            INNER JOIN trabajodetalle td ON td.solicitud_id = s1.solicitud_id 
                                            INNER JOIN tipo t2 on t2.tipo_id = td.tipo_id
                                        WHERE
                                            s1.status_id = 6 AND 
                                            s1.departamento_id IN(    SELECT ud1.departamento_id FROM usuariodepartamento ud1 WHERE ud1.usuario_id = ?) AND 
                                            MONTH(s1.fecha_validacion) = ? AND 
                                            YEAR(s1.fecha_validacion) = ?";


    /*definimos el json*/
    $jsondata = array();
    $jsondata["data"] = array();

    if ($departamento === '%') {
        $queryEjecucion = $recuperaInformeDetalleValidaAuth;
    }
    try {
        if ($stmt = $mysqlCon->prepare($queryEjecucion)) {

            if ($departamento === '%') {
                $stmt->bind_param("iss", $usuario, $mes, $anio);
            }

            if ($stmt->execute()) {
                $stmt->bind_result(
                    $solicitudId, $esb, $codigo, $departamento,
                    $nombre, $apellidos, $descripcion, $fecha, $precio, $tipo
                );
    
                while ($stmt->fetch()) {
                    if (mb_detect_encoding($descripcion) === 'UTF-8') {
                        $tmp = array();
                        $tmp["solicitudId"] = $solicitudId;
                        $tmp["esb"] = $esb;
                        $tmp["codigo"] = trim($codigo);
                        $tmp["departmento"] = $departamento;
                        // $tmp["subdepartmento"] = $subdepartamento;
                        $tmp["nombre"] =  $nombre;
                        $tmp["apellidos"] = $apellidos;
                        $tmp["descripcion"] = $descripcion;
                        $tmp["fecha"] = date("d-m-Y", strtotime($fecha));  
                        $tmp["precio"] = $precio;
                        $tmp["tipo"] = $tipo;
                    } else {
                        $tmp = array();
                        $tmp["solicitudId"] = $solicitudId;
                        $tmp["esb"] = $esb;
                        $tmp["codigo"] = trim($codigo);
                        $tmp["departmento"] = $departamento;
                        // $tmp["subdepartmento"] = $subdepartamento;
                        $tmp["nombre"] =  $nombre;
                        $tmp["apellidos"] = $apellidos;
                        $tmp["descripcion"] = $descripcion;
                        $tmp["fecha"] = date("d-m-Y", strtotime($fecha));  
                        $tmp["precio"] = $precio;
                        $tmp["tipo"] = $tipo;
                    }
                    
                    /*Asociamos el resultado en forma de array en el json*/
                    array_push($jsondata["data"], $tmp);
                }

                /*Asociamos el correcto funcionamiento al json para comprobar en el js*/
                $jsondata["success"] = true;
            } else {
                $jsondata["success"] = false;
                $jsondata["message"] = $queryEjecucion . '-> Anio:' . $anio . 
                ' Mes:'. $mes . ' Departamento:'. $departamento . 
                ' Subepartamento:'. $subdepartamento . ' Usuario:'. $usuario;
            }
        } else {
            $jsondata["success"] = false;
            $jsondata["message"] = $queryEjecucion . '-> Anio:' . $anio . 
            ' Mes:'. $mes . ' Departamento:'. $departamento . 
            ' Subepartamento:'. $subdepartamento . ' Usuario:'. $usuario;
        }
        
    } catch (Exception $th) {
        $jsondata["success"] = false;
        $jsondata["message"] = "Exception:" . $th . 
        " Query:" . $queryEjecucion . 
        '-> Anio:' . $anio . 
        ' Mes:'. $mes . 
        ' Departamento:'. $departamento . 
        ' Subepartamento:'. $subdepartamento . 
        ' Usuario:'. $usuario;
        /*Devolvemos el JSON con los datos de la consulta*/
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata, JSON_FORCE_OBJECT);
    }
    
    /*Devolvemos el JSON con los datos de la consulta*/
    header('Content-type: application/json; charset=utf-8');
    echo json_encode($jsondata, JSON_FORCE_OBJECT);
    
}

/**
 * Carga todos los subdepartamentos por id de departamento
 *
 * @param int $departamento Id de Departamento
 * 
 * @return json
 */
function recuperaSubXDpto($departamento)
{
    global $recuperaSubdptoXDpto,$mysqlCon;
    $valores = "";
    $valoresArray =  array();
    
   
    /*Prepare Statement*/
    if ($stmt = $mysqlCon->prepare($recuperaSubdptoXDpto)) {

        /*Asociacion de parametros*/
        $stmt->bind_param('i', $departamento);

        /*Ejecucion*/
        $stmt->execute();

        /*Asociacion de resultados*/
        /*Id Departamento, Id SubDepartamento, Descripcion SubDepartamento, treintabarra*/
        $stmt->bind_result($col1, $col2, $col3, $col4);

        /*Recogemos el resultado en la variable*/
        while ($stmt->fetch()) {
            $valores = array($col1,$col2,$col3,$col4);
            array_push($valoresArray, $valores);
            
        }

        /*Cerramos la conexion*/
        $stmt->close();

    } else {
        echo $stmt->error;
    }
    
    return $valoresArray;
}

/**
 * Carga todos los subdepartamentos por id de departamento
 *
 * @param int $usuario Id de Departamento
 * 
 * @return json
 */
function cargarDptoSessionAsArray($usuario)
{

    global $recuperaDptoXAutorizadorArray,$mysqlCon;

    $valores = "";
    /*Prepare Statement*/
    if ($stmt = $mysqlCon->prepare($recuperaDptoXAutorizadorArray)) {
        /*Asociacion de parametros*/
        $stmt->bind_param('i', $usuario);
        /*Ejecucion*/
        $stmt->execute();
        /*Asociacion de resultados*/
        $stmt->bind_result($col1);
        /*Recogemos el resultado en la variable*/
        while ($stmt->fetch()) {
            if ($valores == "") {
                $valores = $col1;
            } else {
                $valores .= "," . $col1;
            }
        }
        /*Cerramos la conexion*/
    
    } else {
        echo $stmt->error;
    }
    
    return $valores;
}

/**
 * Carga todos los subdepartamentos por id de departamento
 *
 * @param int $usuario Id de Departamento
 * @param int $dpto    Id de Departamento
 * 
 * @return json
 */
function cargarSubDptoXDptoAsArray($usuario, $dpto)
{

    global $recuperaSubdptoXDpto,$mysqlCon;

    $valores = "";
    /*Prepare Statement*/
    if ($stmt = $mysqlCon->prepare($recuperaSubdptoXDpto)) {
        /*Asociacion de parametros*/
        $stmt->bind_param('i', $dpto);
        /*Ejecucion*/
        $stmt->execute();
        /*Asociacion de resultados*/
        $stmt->bind_result($col1, $col2, $col3, $col4);
        /*Recogemos el resultado en la variable*/
        while ($stmt->fetch()) {
            if ($valores == "") {
                $valores = $col2;
            } else {
                $valores .= "," . $col2;
            }
        }
        /*Cerramos la conexion*/

        $stmt->close();
    } else {
        echo $stmt->error;
    }

    return $valores;
}
