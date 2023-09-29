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

require_once '../../utiles/connectDBUtiles.php';
require_once 'query.php';

$maxSolicitudId = mysqli_query($mysqlCon, $maximaSolicidud);

if (!$maxSolicitudId) {
    echo "No se pudo ejecutar con exito la consulta ($maximaSolicidud) en la BD: " . mysql_error();
    exit;
}

if (mysqli_num_rows($maxSolicitudId) == 0) {
    echo "No se han encontrado filas, nada a imprimir, asi que voy a detenerme.";
    exit;
}

?>
