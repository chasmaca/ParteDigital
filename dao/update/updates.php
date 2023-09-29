<?php

/**
 * Class: updates.php
 *
 * Insercion de Varios 2 Extra
 * php version 7.3.28
 * 
 * @category Update
 * @package  DaoUpdate
 * @author   Jesus Madrazo <chasmaca@gmail.com>
 * @license  http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version  GIT: <Initial Import>
 * @link     https://www.elpartedigital.com/
 */

$actualizaDepartamento = "UPDATE departamento SET departamentos_desc=?, ceco=? WHERE departamento_id = ?";   // phpcs:ignore
$actualizaSubdepartamento = "UPDATE subdepartamento SET subdepartamento_desc=?, treintabarra=? WHERE departamento_id = ? and subdepartamento_id=?";   // phpcs:ignore
$actualizaArticulo = "UPDATE detalle SET descripcion = ?, precio = ? WHERE tipo_id = ? and detalle_id = ?";   // phpcs:ignore
$actualizaImpresora = "UPDATE impresoras SET modelo = ?, edificio = ?, ubicacion = ?, fecha = ?, serie = ?, numero = ? WHERE impresora_id = ?";   // phpcs:ignore
$actualizaUsuario = "UPDATE usuario SET logon = ?, password = ?, nombre = ?, apellido = ?, role_id = ? WHERE usuario_id = ?";   // phpcs:ignore

$sentenciaEstadoSolicitud = "UPDATE solicitud SET STATUS_ID=? WHERE SOLICITUD_ID = ?";   // phpcs:ignore
$sentenciaActualizaEstado = "UPDATE trabajo set status_id = ? where solicitud_id = ? and trabajo_id = 1";   // phpcs:ignore
$sentenciaCierreSolicitud = "UPDATE solicitud SET STATUS_ID=? WHERE SOLICITUD_ID = ?";   // phpcs:ignore
$sentenciaUpdateStatusDosSolicitud = "UPDATE solicitud set status_id = ?, fecha_validacion = now() where solicitud_id = ?";   // phpcs:ignore
$sentenciaUpdateStatusTresSolicitud = "UPDATE solicitud set status_id = ?, fecha_validacion = now(), comentario = ? where solicitud_id = ?";   // phpcs:ignore

$sentenciaStatus6Solicitud = "UPDATE solicitud set status_id = ?, fecha_cierre = now() where solicitud_id = ?";   // phpcs:ignore

$updateLinea = "UPDATE trabajodetalle set unidades = ?, precioTotal = ? where trabajo_id=? and solicitud_id = ? and tipo_id = ? and detalle_id = ?";   // phpcs:ignore
$updateTrabajo = "UPDATE trabajo set status_id = ?, usuario_id = ? where solicitud_id = ? and trabajo_id = 1";   // phpcs:ignore
$updateSolicitud = "UPDATE solicitud set status_id = ?, usuario_plantilla = ? where solicitud_id = ?";   // phpcs:ignore
$updateSubtotal =  "UPDATE trabajo set precioByN = ?, precioColor = ?, precioEncolado = ?, precioEncuadernacion = ?, precioEspiral = ?, PrecioVarios = ?, precioVarios1 = ?, precioVarios2 = ? where solicitud_id = ? and trabajo_id = 1";    // phpcs:ignore
$reabreSolicitud =  "UPDATE solicitud set status_id = ?, usuario_plantilla = ?, fecha_cierre = null where solicitud_id = ?";   // phpcs:ignore

$sentenciaUpdateGastosMaquinaColor = "UPDATE gastos_maquina set color_unidades = ?, color_precio = ?, color_total = ? where departamento_id=? and YEAR(periodo) = ? and MONTH(periodo) = ?";   // phpcs:ignore
$sentenciaUpdateGastosMaquinaByN = "UPDATE gastos_maquina set byn_unidades = ?,  byn_precio = ?, byn_total = ? where departamento_id=? and YEAR(periodo) = ? and MONTH(periodo) = ?";   // phpcs:ignore
$sentenciaUpdateGastosImpresoraColor = "UPDATE gastos_impresora set color_unidades = ?, color_precio = ?, color_total = ? where departamento_id=? and  YEAR(periodo) = ? and MONTH(periodo) = ?";   // phpcs:ignore
$sentenciaUpdateGastosImpresoraByN = "UPDATE gastos_impresora set byn_unidades = ?,  byn_precio = ?, byn_total = ? where departamento_id=? and  YEAR(periodo) = ? and MONTH(periodo) = ?";   // phpcs:ignore

$sentenciaCierreSolicitudMes= "UPDATE solicitud set status_id = 6, fecha_cierre = ? where status_id in (2,4,5) and fecha_alta < (select CAST(DATE_FORMAT(NOW() ,'%Y-%m-01') as DATE))";   // phpcs:ignore
$sentenciaEstadoSolicitudPlantilla = "UPDATE solicitud SET STATUS_ID = ?, usuario_plantilla = ? WHERE SOLICITUD_ID = ?";   // phpcs:ignore
$updateTrabajoJSON = "UPDATE trabajo set fecha_inicio = STR_TO_DATE(?, '%d/%m/%Y') where solicitud_id = ?";   // phpcs:ignore

$sentenciaUpdateDetalleJSON = "UPDATE trabajodetalle set unidades=?, preciototal=? where trabajo_id = ? and tipo_id = ? and detalle_id = ? and solicitud_id = ?";   // phpcs:ignore
$sentenciaActualizaSubTotales = "UPDATE trabajo set precioVarios = ?, precioVarios1 = ?, precioVarios2 = ?, precioColor = ?, precioByN = ?, precioEncuadernacion = ?, precioEspiral = ?, precioEncolado = ? where solicitud_id = ?";   // phpcs:ignore
$updateVarios2ExtraTrabajoJSON = "UPDATE trabajodetalle set unidades = ?, preciototal = ?, fecha_cierre = now() where trabajo_id=1 and tipo_id = 7 and solicitud_id = ? and detalle_id = ?";   // phpcs:ignore
$updateVarios2ExtraTrabajoJSONId = "UPDATE trabajodetalle set unidades = ?, preciototal = ?, fecha_cierre = now() where trabajo_id=1 and tipo_id = 7 and solicitud_id = ? and detalle_id = ?";   // phpcs:ignore

?>