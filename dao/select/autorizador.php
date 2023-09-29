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

require_once 'query.php';
require_once '../../utiles/connectDBUtiles.php';

function recuperaTodosValidadores($mysqlCon){
    
    global $todosAutorizadoresQuery;

    $autorizadorResult = mysqli_query($mysqlCon, $todosAutorizadoresQuery);

    if (!$autorizadorResult) {
        echo "No se pudo ejecutar con exito la consulta ($todosAutorizadoresQuery) en la BD: " . mysql_error();
        exit;
    }

    if (mysqli_num_rows($autorizadorResult) == 0) {
        echo "No se han encontrado filas, nada a imprimir, asi que voy a detenerme.";
        exit;
    }

    return $autorizadorResult;

}

function recuperaEmail($usuario){
    global $recuperaEmail,$mysqlCon;

    $email = "";

    /*Prepare Statement*/
    if ($stmt = $mysqlCon->prepare($recuperaEmail)) {
        /*Asociacion de parametros*/
        $stmt->bind_param('i', $usuario);
        /*Ejecucion*/
        $stmt->execute();
        /*Asociacion de resultados*/
        $stmt->bind_result($col1);
        /*Recogemos el resultado en la variable*/
        while ($stmt->fetch()) {
            $email = $col1;
        }
        /*Cerramos la conexion*/
        $stmt->close();
    } else {
        echo $stmt->error;
    }
    
    return $email;
}


function recuperaCorreo($solicitud){
    global $recuperaCorreoSolicitud,$mysqlCon;

    $email = "";

    /*Prepare Statement*/
    if ($stmt = $mysqlCon->prepare($recuperaCorreoSolicitud)) {
        /*Asociacion de parametros*/
        $stmt->bind_param('i', $solicitud);
        /*Ejecucion*/
        $stmt->execute();
        /*Asociacion de resultados*/
        $stmt->bind_result($col1);
        /*Recogemos el resultado en la variable*/
        while ($stmt->fetch()) {
            $email = $col1;
        }
        /*Cerramos la conexion*/
        $stmt->close();
    } else {
        echo $stmt->error;
    }

    return $email;
}

?>