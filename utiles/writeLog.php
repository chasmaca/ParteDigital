<?php

function writeErrorLog ($clase, $consulta, $parametros){
    $log  = "Clase: " . $clase.PHP_EOL.
            date("Y-m-d H:i:s").PHP_EOL.
            "Query:". $consulta.PHP_EOL.
            json_encode($parametros).PHP_EOL.
            "-------------------------".PHP_EOL;
            error_log($log, 3, __DIR__ . "/../log/errores.log");
}


function writeSuccessfulLog ($clase, $consulta, $parametros){
    $log  = "Transaccion Correcta:".PHP_EOL.
            date("Y-m-d H:i:s").PHP_EOL.
            "Clase: " . $clase.PHP_EOL.
            "Query:". $consulta.PHP_EOL.
            json_encode($parametros).PHP_EOL.
            "-------------------------".PHP_EOL;
            error_log($log, 3, __DIR__ . "/../log/trazas.log");
}

function writeErrorNoQueryLog ($clase, $parametros){
    $log  = "Clase: " . $clase.PHP_EOL.
            date("Y-m-d H:i:s").PHP_EOL.
            json_encode($parametros).PHP_EOL.
            "-------------------------".PHP_EOL;
            error_log($log, 3, __DIR__ . "/../log/errores.log");
}

function writeSuccessfulNoQueryLog ($clase, $parametros){
    $log  = "Clase: " . $clase.PHP_EOL.
            date("Y-m-d H:i:s").PHP_EOL.
            json_encode($parametros).PHP_EOL.
            "-------------------------".PHP_EOL;
            error_log($log, 3, __DIR__ . "/../log/trazas.log");
}

?>