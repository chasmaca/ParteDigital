<?php

/**
 * Class: insertLog.php
 * php version 7.3.28
 *
 * Clase para generar el log de cada operativa
 * 
 * @category Borrado
 * @package  Dao-Insert
 * @author   Jesus Madrazo <chasmaca@gmail.com>
 * @license  http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version  GIT: <1> In development
 * @link     https://www.elpartedigital.com/
 */


/**
 * Inserta los valores de ByN por solicitud.
 *
 * @param string $fichero    The position of the token in the token stack
 * @param string $funcion    The number of spaces to adjust the indent by
 * @param string $parametros The position of the token in the token stack
 * @param string $mensaje    The number of spaces to adjust the indent by
 * @param string $directorio The position of the token in the token stack
 *
 * @return void
 */
function crearLog($fichero, $funcion, $parametros, $mensaje, $directorio) 
{

    try{
        $log  = 'Fichero: ' . $fichero.PHP_EOL.
            'Funcion: ' . $funcion.PHP_EOL.
            'Fecha: '.date("F j, Y, g:i a").PHP_EOL.
            'Success: '. $mensaje .PHP_EOL.
            'Parametros: '. $parametros .PHP_EOL.
            '-------------------------'.PHP_EOL;
            //Save string to log, use FILE_APPEND to append.

        if (!Folder_exist('./../../log/'.$directorio)) {
            mkdir('./../../log/'.$directorio);
        }
        $fecha = date("Y-m-d");

        $nombreCompleto = $fichero. $fecha;

        file_put_contents('./../../log/'.$directorio.'/'.$nombreCompleto.'.log', $log, FILE_APPEND);
    
    }catch(Exception $e) {
        printf($e);
    }
}


/**
 * Inserta los valores de ByN por solicitud.
 *
 * @param string $folder The position of the token in the token stack
 *
 * @return boolean
 */
function Folder_exist($folder)
{
    // Get canonicalized absolute pathname
    $path = realpath($folder);

    // If it exist, check if it's a directory
    if ($path !== false AND is_dir($path)) {
        // Return canonicalized absolute pathname
        return $path;
    }

    // Path/folder does not exist
    return false;
}

?>