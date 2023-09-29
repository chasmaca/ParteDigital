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

recuperaDepartamentos();

 

function recuperaDepartamentos()
{
    
    global $recuperaDptoSdpto, $mysqlCon, $ceco, $departamento, $treintabarra, $subdepartamento;
    
    $recuperaDptoSdpto = "select d1.ceco as ceco, d1.departamentos_desc, s1.treintabarra, s1.subdepartamento_desc from departamento d1 inner join subdepartamento s1 on s1.departamento_id = d1.departamento_id where d1.markfordelete = 1 order by d1.departamentos_desc";
    
    /*definimos el json*/
    $jsondata = array();
    $jsondata["data"] = array();

    /*Prepare Statement*/
    if ($stmt = $mysqlCon->prepare($recuperaDptoSdpto)) {
        /*Ejecucion*/
        $stmt->execute();
        /*Almacenamos el resultSet*/
        $stmt->bind_result($ceco, $departamento, $treintabarra, $subdepartamento);
        
        /*Incluimos las lineas de la consulta en el json a devolver*/
        while ($stmt->fetch()) {
            $tmp = array();
            $tmp["ceco"] = utf8_encode($ceco);
            $tmp["departamento"] = utf8_encode($departamento);
            $tmp["treintabarra"] = utf8_encode($treintabarra);
            $tmp["subdepartamento"] = utf8_encode($subdepartamento);
                
            array_push($jsondata["data"], $tmp);
        }
        $stmt->close();
        /*Asociamos el correcto funcionamiento al json para comprobar en el js*/
        $jsondata["success"] = true;
    } else {
        /*Llegamos aqui con error, asociamos false para identificarlo en el js*/
        $jsondata["success"] = false;
        die("Errormessage: ". $mysqlCon->error);
    }
        
    /*Devolvemos el JSON con los datos de la consulta*/
    header('Content-type: application/json; charset=utf-8');
    echo json_encode($jsondata, JSON_FORCE_OBJECT);
        
}
        
?>