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
$borradoDepartamento = "update departamento set markfordelete = 1 where departamento_id = ?";
$borradoSubdepartamento = "update subdepartamento set markfordelete = 1 where departamento_id = ? and subdepartamento_id = ?";
$borradoImpresora = "DELETE FROM impresoras where impresora_id = ?";
$borradoArticulo = "DELETE FROM detalle where tipo_id = ? and detalle_id = ?";

$borrarUsuarioDepartamento = "DELETE FROM usuariodepartamento where usuario_id = ?";
$sentenciaBorradoUsuario = "DELETE FROM usuario WHERE USUARIO_ID = ?";
?>