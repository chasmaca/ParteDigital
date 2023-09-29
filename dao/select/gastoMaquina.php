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

require_once 'query.php';


function recuperaGastoByN()
{

    global $mysqlCon, $recuperaPrecioByNMaquCierre;
    $precioByN = "";
    
    $precioResult = mysqli_query($mysqlCon, $recuperaPrecioByNMaquCierre);
    
    if (!$precioResult) {
        echo "No se pudo ejecutar con exito la consulta ($recuperaPrecioByNMaquCierre) en la BD: " . $mysqli->error;
        exit;
    }
    
    if (mysqli_num_rows($precioResult) == 0) {
        echo "No se han encontrado filas, nada a imprimir, asi que voy a detenerme.";
        exit;
    }
    
    
    if (mysqli_num_rows($precioResult) > 1) {
        echo "Hay varios precios que se corresponden con la selecci�n, por favor, revise la bbdd.";
        exit;
    }
    
    while ($fila = mysqli_fetch_assoc($precioResult)) {
        if ($fila['precio'] == null) {
            $precioByN = 0;
        } else {
            $precioByN = $fila['precio'];
        }
            
    }
    
    return $precioByN;
    

}

function recuperaGastoColor()
{

    global $mysqlCon,$recuperaPrecioColorMaquCierre;
    $precioColor = "";
    
    $precioResult = mysqli_query($mysqlCon, $recuperaPrecioColorMaquCierre);
    
    if (!$precioResult) {
        echo "No se pudo ejecutar con exito la consulta ($recuperaPrecioColorMaquCierre) en la BD: " . $mysqli->error;
        exit;
    }
    
    if (mysqli_num_rows($precioResult) == 0) {
        echo "No se han encontrado filas, nada a imprimir, asi que voy a detenerme.";
        exit;
    }
    
    
    if (mysqli_num_rows($precioResult) > 1) {
        echo "Hay varios precios que se corresponden con la selecci�n, por favor, revise la bbdd.";
        exit;
    }
    
    while ($fila = mysqli_fetch_assoc($precioResult)) {
        if ($fila['precio'] == null) {
            $precioColor = 0;
        } else {
            $precioColor = $fila['precio'];
        }
    }
    
    return $precioColor;
}


?>