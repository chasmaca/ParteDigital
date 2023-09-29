<?php

/**
 * Class: query.php
 *
 * Resumen de queries de la app
 * php version 7.3.28
 * 
 * @category SELECT
 * @package  DaoSELECT
 * @author   Jesus Madrazo <chasmaca@gmail.com>
 * @license  http://www.php.net/license/3_0.txt PHP License 3.0
 * @version  GIT: <Initial Import>
 * @link     https://www.elpartedigital.com/
 */

/* Pagina de login - Consulta de Acceso */
$sentenciaLogonJSON = "SELECT
    usuario_id,
    logon,
    password,
    nombre,
    apellido,
    role_id
FROM
    usuario
WHERE 
    logon = ?
    AND password = ?";

/* Pagina Autorizador - Consulta Trabajos por aprobar */
$solicitudPorValidadorJSONQuery = "SELECT
    d1.solicitud_id AS solicitud_id,
    d1.departamento_id AS departamento_id,
    d1.subdepartamento_id AS subdepartamento_id,
    d1.nombre_solicitante AS nombre_solicitante,
    d1.apellidos_solicitante AS apellidos_solicitante,
    d1.autorizador_id AS autorizador_id,
    d1.descripcion_solicitante AS descripcion_solicitante,
    d1.email_solicitante AS email_solicitante,
    d1.status_id AS status_id,
    d1.fecha_alta AS fecha_alta,
    d1.fecha_validacion AS fecha_validacion,
    d1.fecha_cierre AS fecha_cierre,
    de1.departamentos_desc AS departamentos_desc,
    sd1.subdepartamento_desc AS subdepartamentos_desc
FROM
    solicitud d1
INNER JOIN departamento de1 ON
    d1.departamento_id = de1.departamento_id
INNER JOIN subdepartamento sd1 ON
    d1.subdepartamento_id = sd1.subdepartamento_id
    AND d1.departamento_id = sd1.departamento_id
    AND de1.markfordelete = 0
    AND sd1.markfordelete = 0
WHERE 
    d1.status_id = 1
    AND d1.autorizador_id = ?";  

/* Pagina Autorizador - Consulta Trabajos mes en curso */
$queryGastosMesActual = "SELECT
    solicitud.solicitud_id,
    departamento.departamentos_desc,
    subdepartamento.subdepartamento_desc,
    solicitud.nombre_solicitante,
    solicitud.apellidos_solicitante,
    solicitud.descripcion_solicitante,
    solicitud.autorizador_id,
    usuario.nombre,
    usuario.apellido,
    status.status_desc,
    trabajo.PrecioVarios,
    trabajo.precioByN,
    trabajo.precioColor,
    trabajo.precioEncuadernacion
FROM
    solicitud
INNER JOIN usuario ON
    solicitud.autorizador_id = usuario.usuario_id
INNER JOIN status ON
    solicitud.status_id = status.status_id
INNER JOIN departamento ON
    solicitud.departamento_id = departamento.departamento_id
INNER JOIN subdepartamento ON
    solicitud.subdepartamento_id = subdepartamento.subdepartamento_id
    AND solicitud.departamento_id = subdepartamento.departamento_id
left join trabajo ON
    solicitud.solicitud_id = trabajo.solicitud_id
WHERE 
    month(fecha_validacion) = month(CURDATE())
    AND year(fecha_validacion)= year(CURDATE())
    AND solicitud.autorizador_id = ?";  

/* Pagina Autorizador - Combo del periodo */
$recuperaAnioMes = "SELECT
    year(fecha_alta) AS anio_alta ,
    month(fecha_alta) AS mes_alta
FROM
    solicitud
group by
    anio_alta ,
    mes_alta
order by
    anio_alta desc ,
    mes_alta desc";  

/* Pagina Autorizador - Combo del departamento */
$recuperaDptoXAutorizadorJSON = "SELECT
    distinct (d1.departamento_id) AS DEPARTAMENTO_ID ,
    d1.departamentos_desc AS DEPARTAMENTOS_DESC ,
    d1.ceco AS CECO
FROM
    usuario u1
INNER JOIN usuariodepartamento ud1 ON
    ud1.usuario_id = u1.usuario_id
INNER JOIN departamento d1 ON
    ud1.departamento_id = d1.departamento_id
WHERE 
    role_id = 3
    AND d1.markfordelete = 0
    AND u1.usuario_id = ?";  

/* Pagina Autorizador - Combo de subdepartamento */
$recuperaSubdptoXDpto = "SELECT
    departamento_id ,
    subdepartamento_id ,
    subdepartamento_desc ,
    treintabarra
FROM
    subdepartamento
WHERE 
    departamento_id = ?
    AND markfordelete = 0
order by
    treintabarra";  

/* Pagina Autorizador - Departamentos por autorizador */
$recuperaDptoXAutorizadorArray = "SELECT
    distinct d1.departamento_id AS DEPARTAMENTO_ID
FROM
    usuario u1
INNER JOIN usuariodepartamento ud1 ON
    ud1.usuario_id = u1.usuario_id
INNER JOIN departamento d1 ON
    ud1.departamento_id = d1.departamento_id
WHERE 
    role_id = 3
    AND u1.usuario_id = ?";  

/* Pagina Autorizador - Informe global */
$recuperaValidadorGlobalTodosDpto = "SELECT
    d.ceco AS Ceco,
    d.departamentos_desc AS Departamento,
    ROUND(SUM(case when td.tipo_id = '1' then td.preciototal else 0 end), 4) AS Espiral,
    ROUND(SUM(case when td.tipo_id = '2' then td.preciototal else 0 end), 4) AS Encolado,
    ROUND(SUM(case when td.tipo_id = '3' then td.preciototal else 0 end), 4) AS Varios1,
    ROUND(SUM(case when td.tipo_id = '4' then td.preciototal else 0 end), 4) AS Color,
    ROUND(SUM(case when td.tipo_id = '5' then td.preciototal else 0 end), 4) AS BlancoNegro,
    ROUND(SUM(case when td.tipo_id in ('6', '7') then td.preciototal else 0 end), 4) AS Varios1
FROM
    trabajodetalle td
INNER JOIN solicitud s ON
    s.solicitud_id = td.solicitud_id
INNER JOIN departamento d ON
    d.departamento_id = s.departamento_id
INNER JOIN subdepartamento s2 ON
    s2.departamento_id = s.departamento_id
    AND s2.subdepartamento_id = s.subdepartamento_id
WHERE 
    year(td.fecha_cierre) = ?
    AND month(td.fecha_cierre)= ?
    AND d.departamento_id in (
    select
        distinct departamento_id
    FROM
        usuariodepartamento
    WHERE 
        usuario_id = ?)
group by
    d.departamento_id
union
select
    'cecoMaquinas' AS Ceco,
    concat('Maquinas ', d.departamentos_desc) AS Departamento,
    0,
    0,
    0,
    round(m1.color_total, 4),
    round(m1.byn_total, 4),
    0
FROM
    gastos_maquina m1
INNER JOIN departamento d ON
    d.departamento_id = m1.departamento_id
WHERE 
    year(m1.periodo) = ?
    AND month(m1.periodo) = ?
    AND d.departamento_id in (
    select
        distinct departamento_id
    FROM
        usuariodepartamento
    WHERE 
        usuario_id = ?)
union
select
    'cecoImpresoras' AS Ceco,
    concat('Impresoras ', d.departamentos_desc) AS Departamento,
    0,
    0,
    0,
    round(m1.color_total, 4),
    round(m1.byn_total, 4),
    0
FROM
    gastos_impresora m1
INNER JOIN departamento d ON
    d.departamento_id = m1.departamento_id
WHERE 
    year(m1.periodo) = ?
    AND month(m1.periodo) = ?
    AND d.departamento_id in (
    select
        distinct departamento_id
    FROM
        usuariodepartamento
    WHERE 
        usuario_id = ?)";  

$recuperaValidadorGlobalDpto = "SELECT
    d.ceco AS Ceco,
    d.departamentos_desc AS Departamento,
    ROUND(SUM(case when td.tipo_id = '1' then td.preciototal else 0 end), 4) AS Espiral,
    ROUND(SUM(case when td.tipo_id = '2' then td.preciototal else 0 end), 4) AS Encolado,
    ROUND(SUM(case when td.tipo_id = '3' then td.preciototal else 0 end), 4) AS Varios1,
    ROUND(SUM(case when td.tipo_id = '4' then td.preciototal else 0 end), 4) AS Color,
    ROUND(SUM(case when td.tipo_id = '5' then td.preciototal else 0 end), 4) AS BlancoNegro,
    ROUND(SUM(case when td.tipo_id in ('6', '7') then td.preciototal else 0 end), 4) AS Varios1
FROM
    trabajodetalle td
INNER JOIN solicitud s ON
    s.solicitud_id = td.solicitud_id
INNER JOIN departamento d ON
    d.departamento_id = s.departamento_id
INNER JOIN subdepartamento s2 ON
    s2.departamento_id = s.departamento_id
    AND s2.subdepartamento_id = s.subdepartamento_id
WHERE 
    year(td.fecha_cierre) = ?
    AND month(td.fecha_cierre)= ?
    AND d.departamento_id =?
group by
    d.departamento_id
union
select
    'cecoMaquinas' AS Ceco,
    concat('Maquinas ', d.departamentos_desc) AS Departamento,
    0,
    0,
    0,
    round(m1.color_total, 4),
    round(m1.byn_total, 4),
    0
FROM
    gastos_maquina m1
INNER JOIN departamento d ON
    d.departamento_id = m1.departamento_id
WHERE 
    year(m1.periodo) = ?
    AND month(m1.periodo) = ?
    AND d.departamento_id =?
union
select
    'cecoImpresoras' AS Ceco,
    concat('Impresoras ', d.departamentos_desc) AS Departamento,
    0,
    0,
    0,
    round(m1.color_total, 4),
    round(m1.byn_total, 4),
    0
FROM
    gastos_impresora m1
INNER JOIN departamento d ON
    d.departamento_id = m1.departamento_id
WHERE 
    year(m1.periodo) = ?
    AND month(m1.periodo) = ?
    AND d.departamento_id =?";  

$recuperaValidadorGlobalDptoSubDpto = "SELECT
    d.ceco AS Ceco,
    d.departamentos_desc AS Departamento,
    ROUND(SUM(case when td.tipo_id = '1' then td.preciototal else 0 end), 4) AS Espiral,
    ROUND(SUM(case when td.tipo_id = '2' then td.preciototal else 0 end), 4) AS Encolado,
    ROUND(SUM(case when td.tipo_id = '3' then td.preciototal else 0 end), 4) AS Varios1,
    ROUND(SUM(case when td.tipo_id = '4' then td.preciototal else 0 end), 4) AS Color,
    ROUND(SUM(case when td.tipo_id = '5' then td.preciototal else 0 end), 4) AS BlancoNegro,
    ROUND(SUM(case when td.tipo_id in ('6', '7') then td.preciototal else 0 end), 4) AS Varios1
FROM
    trabajodetalle td
INNER JOIN solicitud s ON
    s.solicitud_id = td.solicitud_id
INNER JOIN departamento d ON
    d.departamento_id = s.departamento_id
INNER JOIN subdepartamento s2 ON
    s2.departamento_id = s.departamento_id
    AND s2.subdepartamento_id = s.subdepartamento_id
WHERE 
    year(td.fecha_cierre) = ?
    AND month(td.fecha_cierre)= ?
    AND s.departamento_id =?
    AND s.subdepartamento_id =?
group by
    d.departamento_id";  

/* Pagina Autorizador - Informe detallado */
$recuperaInformeDetalleValidaAuth = "SELECT
    td.solicitud_id AS Parte,
    d.ceco AS Ceco,
    d.departamentos_desc AS Departamento,
    s2.subdepartamento_desc AS Subepartamento,
    s2.treintabarra AS Treinta,
    s.nombre_solicitante AS Nombre,
    s.apellidos_solicitante AS Apellidos,
    replace(replace(SUBSTRING(s.descripcion_solicitante, 1, 30),
    '\r\n',
    ' '),
    '\t',
    ' ') AS Descripcion,
    td.fecha_cierre AS Fecha,
    ROUND(SUM(case when td.tipo_id = '1' then td.preciototal else 0 end), 4) AS Espiral,
    ROUND(SUM(case when td.tipo_id = '2' then td.preciototal else 0 end), 4) AS Encolado,
    ROUND(SUM(case when td.tipo_id = '3' then td.preciototal else 0 end), 4) AS Varios1,
    ROUND(SUM(case when td.tipo_id = '4' then td.preciototal else 0 end), 4) AS Color,
    ROUND(SUM(case when td.tipo_id = '5' then td.preciototal else 0 end), 4) AS BlancoNegro,
    ROUND(SUM(case when td.tipo_id in ('6', '7') then td.preciototal else 0 end), 4) AS Varios1
FROM
    trabajodetalle td
INNER JOIN solicitud s ON
    s.solicitud_id = td.solicitud_id
INNER JOIN departamento d ON
    d.departamento_id = s.departamento_id
INNER JOIN subdepartamento s2 ON
    s2.departamento_id = s.departamento_id
    AND s2.subdepartamento_id = s.subdepartamento_id
WHERE 
    year(td.fecha_cierre) = ?
    AND month(td.fecha_cierre)= ?
    AND d.departamento_id in (
    select
        distinct departamento_id
    FROM
        usuariodepartamento
    WHERE 
        usuario_id = ?)
group by
    td.solicitud_id
union
select
    'treintabarraMaq' AS Parte,
    'cecoMaquinas' AS Ceco,
    concat('Maquinas ', d.departamentos_desc) AS Departamento,
    'Maquinas' AS Subepartamento,
    'Maquinas' AS Treinta,
    'Maquinas' AS Nombre,
    'Maquinas' AS Apellidos,
    'Maquinas' AS Descripcion,
    m1.periodo AS Fecha,
    0,
    0,
    0,
    round(m1.color_total, 4),
    round(m1.byn_total, 4),
    0
FROM
    gastos_maquina m1
INNER JOIN departamento d ON
    d.departamento_id = m1.departamento_id
WHERE 
    year(m1.periodo) = ?
    AND month(m1.periodo) = ?
    AND d.departamento_id in (
    select
        distinct departamento_id
    FROM
        usuariodepartamento
    WHERE 
        usuario_id = ?)
union
select
    'treintabarraImp' AS Parte,
    'cecoImpresoras' AS Ceco,
    concat('Impresoras ', d.departamentos_desc) AS Departamento,
    'Impresoras' AS Subepartamento,
    'Impresoras' AS Treinta,
    'Impresoras' AS Nombre,
    'Impresoras' AS Apellidos,
    'Impresoras' AS Descripcion,
    m1.periodo AS Fecha,
    0,
    0,
    0,
    round(m1.color_total, 4),
    round(m1.byn_total, 4),
    0
FROM
    gastos_impresora m1
INNER JOIN departamento d ON
    d.departamento_id = m1.departamento_id
WHERE 
    year(m1.periodo) = ?
    AND month(m1.periodo) = ?
    AND d.departamento_id in (
    select
        distinct departamento_id
    FROM
        usuariodepartamento
    WHERE 
        usuario_id = ?)";  

$recuperaInformeDetalleValidaAuthDpto = "SELECT
    td.solicitud_id AS Parte,
    d.ceco AS Ceco,
    d.departamentos_desc AS Departamento,
    s2.subdepartamento_desc AS Subepartamento,
    s2.treintabarra AS Treinta,
    s.nombre_solicitante AS Nombre,
    s.apellidos_solicitante AS Apellidos,
    replace(replace(SUBSTRING(s.descripcion_solicitante, 1, 30),
    '\r\n',
    ' '),
    '\t',
    ' ') AS Descripcion,
    td.fecha_cierre AS Fecha,
    ROUND(SUM(case when td.tipo_id = '1' then td.preciototal else 0 end), 4) AS Espiral,
    ROUND(SUM(case when td.tipo_id = '2' then td.preciototal else 0 end), 4) AS Encolado,
    ROUND(SUM(case when td.tipo_id = '3' then td.preciototal else 0 end), 4) AS Varios1,
    ROUND(SUM(case when td.tipo_id = '4' then td.preciototal else 0 end), 4) AS Color,
    ROUND(SUM(case when td.tipo_id = '5' then td.preciototal else 0 end), 4) AS BlancoNegro,
    ROUND(SUM(case when td.tipo_id in ('6', '7') then td.preciototal else 0 end), 4) AS Varios1
FROM
    trabajodetalle td
INNER JOIN solicitud s ON
    s.solicitud_id = td.solicitud_id
INNER JOIN departamento d ON
    d.departamento_id = s.departamento_id
INNER JOIN subdepartamento s2 ON
    s2.departamento_id = s.departamento_id
    AND s2.subdepartamento_id = s.subdepartamento_id
WHERE 
    year(td.fecha_cierre) = ?
    AND month(td.fecha_cierre)= ?
    AND d.departamento_id = ?
group by
    td.solicitud_id
union
select
    'treintabarraMaq' AS Parte,
    'cecoMaquinas' AS Ceco,
    concat('Maquinas ', d.departamentos_desc) AS Departamento,
    'Maquinas' AS Subepartamento,
    'Maquinas' AS Treinta,
    'Maquinas' AS Nombre,
    'Maquinas' AS Apellidos,
    'Maquinas' AS Descripcion,
    m1.periodo AS Fecha,
    0,
    0,
    0,
    round(m1.color_total, 4),
    round(m1.byn_total, 4),
    0
FROM
    gastos_maquina m1
INNER JOIN departamento d ON
    d.departamento_id = m1.departamento_id
WHERE 
    year(m1.periodo) = ?
    AND month(m1.periodo) = ?
    AND d.departamento_id = ?
union
select
    'treintabarraImp' AS Parte,
    'cecoImpresoras' AS Ceco,
    concat('Impresoras ', d.departamentos_desc) AS Departamento,
    'Impresoras' AS Subepartamento,
    'Impresoras' AS Treinta,
    'Impresoras' AS Nombre,
    'Impresoras' AS Apellidos,
    'Impresoras' AS Descripcion,
    m1.periodo AS Fecha,
    0,
    0,
    0,
    round(m1.color_total, 4),
    round(m1.byn_total, 4),
    0
FROM
    gastos_impresora m1
INNER JOIN departamento d ON
    d.departamento_id = m1.departamento_id
WHERE 
    year(m1.periodo) = ?
    AND month(m1.periodo) = ?
    AND d.departamento_id = ?";  

$recuperaInformeDetalleValidaAuthDptoSub = "SELECT
    td.solicitud_id AS Parte,
    d.ceco AS Ceco,
    d.departamentos_desc AS Departamento,
    s2.subdepartamento_desc AS Subepartamento,
    s2.treintabarra AS Treinta,
    s.nombre_solicitante AS Nombre,
    s.apellidos_solicitante AS Apellidos,
    replace(replace(SUBSTRING(s.descripcion_solicitante, 1, 30),
    '\r\n',
    ' '),
    '\t',
    ' ') AS Descripcion,
    td.fecha_cierre AS Fecha,
    ROUND(SUM(case when td.tipo_id = '1' then td.preciototal else 0 end), 4) AS Espiral,
    ROUND(SUM(case when td.tipo_id = '2' then td.preciototal else 0 end), 4) AS Encolado,
    ROUND(SUM(case when td.tipo_id = '3' then td.preciototal else 0 end), 4) AS Varios1,
    ROUND(SUM(case when td.tipo_id = '4' then td.preciototal else 0 end), 4) AS Color,
    ROUND(SUM(case when td.tipo_id = '5' then td.preciototal else 0 end), 4) AS BlancoNegro,
    ROUND(SUM(case when td.tipo_id in ('6', '7') then td.preciototal else 0 end), 4) AS Varios1
FROM
    trabajodetalle td
INNER JOIN solicitud s ON
    s.solicitud_id = td.solicitud_id
INNER JOIN departamento d ON
    d.departamento_id = s.departamento_id
INNER JOIN subdepartamento s2 ON
    s2.departamento_id = s.departamento_id
    AND s2.subdepartamento_id = s.subdepartamento_id
WHERE 
    year(td.fecha_cierre) = ?
    AND month(td.fecha_cierre)= ?
    AND s.departamento_id = ?
    AND s.subdepartamento_id = ?
group by
    td.solicitud_id";  

/* Pagina Autorizador - Informe detallado Detalle Desplegable de la linea */
$cabeceraDatosQuery = "SELECT
    s1.nombre_solicitante,
    s1.apellidos_solicitante,
    s1.fecha_cierre,
    d1.departamentos_desc,
    sd1.subdepartamento_desc,
    d1.ceco,
    sd1.treintabarra,
    d1.departamento_id,
    sd1.subdepartamento_id,
    s1.descripcion_solicitante
FROM
    solicitud s1
INNER JOIN departamento d1 ON
    d1.departamento_id = s1.departamento_id
INNER JOIN subdepartamento sd1 ON
    sd1.departamento_id = s1.departamento_id
    AND sd1.subdepartamento_id = s1.subdepartamento_id
WHERE 
    solicitud_id = ?";  

$variosUnoDatosQuery = "SELECT
    distinct d1.tipo_id AS tipo,
    d1.detalle_id AS detalle,
    d1.descripcion AS descripcion,
    d1.precio AS precio,
    td.unidades AS unidades,
    td.preciototal AS preciototal
FROM
    trabajodetalle td
INNER JOIN detalle d1 ON
    td.tipo_id = d1.tipo_id
    AND td.detalle_id = d1.detalle_id
WHERE 
    td.solicitud_id = ?
    AND d1.tipo_id = 3
    AND year(td.fecha_cierre) = ?
    AND month(td.fecha_cierre) = ?";  


$variosUnoDatosQueryNoFecha = "SELECT
    distinct d1.tipo_id AS tipo,
    d1.detalle_id AS detalle,
    d1.descripcion AS descripcion,
    d1.precio AS precio,
    td.unidades AS unidades,
    td.preciototal AS preciototal
FROM
    trabajodetalle td
INNER JOIN detalle d1 ON
    td.tipo_id = d1.tipo_id
    AND td.detalle_id = d1.detalle_id
WHERE 
    td.solicitud_id = ?
    AND d1.tipo_id = 3";  

$colorDatosQuery = "SELECT
    distinct d1.tipo_id AS tipo,
    d1.detalle_id AS detalle,
    d1.descripcion AS descripcion,
    d1.precio AS precio,
    td.unidades AS unidades,
    td.preciototal AS preciototal
FROM
    trabajodetalle td
INNER JOIN detalle d1 ON
    td.tipo_id = d1.tipo_id
    AND td.detalle_id = d1.detalle_id
WHERE 
    td.solicitud_id = ?
    AND d1.tipo_id = 4
    AND year(td.fecha_cierre) = ?
    AND month(td.fecha_cierre) = ?";  

$colorDatosQueryNoFecha = "SELECT
    distinct d1.tipo_id AS tipo,
    d1.detalle_id AS detalle,
    d1.descripcion AS descripcion,
    d1.precio AS precio,
    td.unidades AS unidades,
    td.preciototal AS preciototal
FROM
    trabajodetalle td
INNER JOIN detalle d1 ON
    td.tipo_id = d1.tipo_id
    AND td.detalle_id = d1.detalle_id
WHERE 
    td.solicitud_id = ?
    AND d1.tipo_id = 4";  

$encuadernacionDatosQuery = "SELECT
    distinct d1.tipo_id AS tipo,
    d1.detalle_id AS detalle,
    d1.descripcion AS descripcion,
    d1.precio AS precio,
    td.unidades AS unidades,
    td.preciototal AS preciototal
FROM
    trabajodetalle td
INNER JOIN detalle d1 ON
    td.tipo_id = d1.tipo_id
    AND td.detalle_id = d1.detalle_id
WHERE 
    d1.tipo_id = 1
    AND td.solicitud_id = ?
    AND year(td.fecha_cierre) = ?
    AND month(td.fecha_cierre) = ?";  

$encuadernacionDatosQueryNoFecha = "SELECT
distinct d1.tipo_id AS tipo,
d1.detalle_id AS detalle,
d1.descripcion AS descripcion,
d1.precio AS precio,
td.unidades AS unidades,
td.preciototal AS preciototal
FROM
trabajodetalle td
INNER JOIN detalle d1 ON
td.tipo_id = d1.tipo_id
AND td.detalle_id = d1.detalle_id
WHERE 
d1.tipo_id = 1
AND td.solicitud_id = ?";  

$encoladoDatosQuery = "SELECT
    distinct d1.tipo_id AS tipo,
    d1.detalle_id AS detalle,
    d1.descripcion AS descripcion,
    d1.precio AS precio,
    td.unidades AS unidades,
    td.preciototal AS preciototal
FROM
    trabajodetalle td
INNER JOIN detalle d1 ON
    td.tipo_id = d1.tipo_id
    AND td.detalle_id = d1.detalle_id
WHERE 
    d1.tipo_id = 2
    AND td.solicitud_id = ?
    AND year(td.fecha_cierre) = ?
    AND month(td.fecha_cierre) = ?";  

$encoladoDatosQueryNoFecha = "SELECT
    distinct d1.tipo_id AS tipo,
    d1.detalle_id AS detalle,
    d1.descripcion AS descripcion,
    d1.precio AS precio,
    td.unidades AS unidades,
    td.preciototal AS preciototal
FROM
    trabajodetalle td
INNER JOIN detalle d1 ON
    td.tipo_id = d1.tipo_id
    AND td.detalle_id = d1.detalle_id
WHERE 
    d1.tipo_id = 2
    AND td.solicitud_id = ?";  

$blancoYNegroDatosQuery = "SELECT
    distinct d1.tipo_id AS tipo,
    d1.detalle_id AS detalle,
    d1.descripcion AS descripcion,
    d1.precio AS precio,
    td.unidades AS unidades,
    td.preciototal AS preciototal
FROM
    trabajodetalle td
INNER JOIN detalle d1 ON
    td.tipo_id = d1.tipo_id
    AND td.detalle_id = d1.detalle_id
WHERE 
    d1.tipo_id = 5
    AND td.solicitud_id = ?
    AND year(td.fecha_cierre) = ?
    AND month(td.fecha_cierre) = ?";  

$blancoYNegroDatosQueryNoFecha = "SELECT
    distinct d1.tipo_id AS tipo,
    d1.detalle_id AS detalle,
    d1.descripcion AS descripcion,
    d1.precio AS precio,
    td.unidades AS unidades,
    td.preciototal AS preciototal
FROM
    trabajodetalle td
INNER JOIN detalle d1 ON
    td.tipo_id = d1.tipo_id
    AND td.detalle_id = d1.detalle_id
WHERE 
    d1.tipo_id = 5
    AND td.solicitud_id = ?";  

$varios2DatosQuery = "SELECT
    distinct d1.tipo_id AS tipo,
    d1.detalle_id AS detalle,
    d1.descripcion AS descripcion,
    d1.precio AS precio,
    td.unidades AS unidades,
    td.preciototal AS preciototal
FROM
    trabajodetalle td
INNER JOIN detalle d1 ON
    td.tipo_id = d1.tipo_id
    AND td.detalle_id = d1.detalle_id
WHERE 
    d1.tipo_id = 6
    AND td.solicitud_id = ?
    AND year(td.fecha_cierre) = ?
    AND month(td.fecha_cierre) = ?";  

$varios2DatosQueryNoFecha = "SELECT
    distinct d1.tipo_id AS tipo,
    d1.detalle_id AS detalle,
    d1.descripcion AS descripcion,
    d1.precio AS precio,
    td.unidades AS unidades,
    td.preciototal AS preciototal
FROM
    trabajodetalle td
INNER JOIN detalle d1 ON
    td.tipo_id = d1.tipo_id
    AND td.detalle_id = d1.detalle_id
WHERE 
    d1.tipo_id = 6
    AND td.solicitud_id = ?";  

$varios2DatosExtraQuery = "SELECT
    distinct d1.tipo_id AS tipo,
    d1.detalle_id AS detalle,
    d1.descripcion AS descripcion,
    d1.precio AS precio,
    td.unidades AS unidades,
    td.preciototal AS preciototal
FROM
    trabajodetalle td
INNER JOIN detalle d1 ON
    td.tipo_id = d1.tipo_id
    AND td.detalle_id = d1.detalle_id
WHERE 
    d1.tipo_id = 7
    AND td.solicitud_id = ?
    AND year(td.fecha_cierre) = ?
    AND month(td.fecha_cierre) = ?";  

$varios2DatosExtraQueryNoFecha = "SELECT
    distinct d1.tipo_id AS tipo,
    d1.detalle_id AS detalle,
    d1.descripcion AS descripcion,
    d1.precio AS precio,
    td.unidades AS unidades,
    td.preciototal AS preciototal
FROM
    trabajodetalle td
INNER JOIN detalle d1 ON
    td.tipo_id = d1.tipo_id
    AND td.detalle_id = d1.detalle_id
WHERE 
    d1.tipo_id = 7
    AND td.solicitud_id = ?";  

/* Pagina de creacion de solicitud - Password Maestra*/
$recuperaPasswordMaestra = "SELECT password FROM administracion";

/* Pagina de creacion de solicitud - Recuperacion de Usuarios*/
$recuperaTodosUsuarios = "SELECT
    distinct CONCAT(usuario.nombre, ' ', usuario.apellido) AS nombre ,
    role.role_desc AS rol ,
    departamento.departamentos_desc AS nombreDepartamento
FROM
    usuario
INNER JOIN role ON
    role.role_id = usuario.role_id
INNER JOIN usuariodepartamento ON
    usuariodepartamento.usuario_id = usuario.usuario_id
INNER JOIN departamento ON
    departamento.departamento_id = usuariodepartamento.departamento_id
    AND departamento.markfordelete = 0
order by
    nombre ,
    nombreDepartamento asc";  

/* Pagina de creacion de solicitud - Recuperacion de Autorizadores*/
$todosAutorizadoresQuery = "SELECT
    usuario_id AS AUTORIZADOR_ID,
    upper(nombre) AS AUTORIZADOR_NOMBRE,
    upper(apellido) AS AUTORIZADOR_APELLIDOS,
    logon AS AUTORIZADOR_EMAIL
FROM
    usuario
WHERE 
    role_id = 3
order by
    AUTORIZADOR_NOMBRE,
    AUTORIZADOR_APELLIDOS";  

/* Pagina de creacion de solicitud - Consulta de Id*/
$maximaSolicidud = "SELECT MAX(SOLICITUD_ID)+1 AS SOLICITUD_MAX FROM solicitud";

/* Pagina de creacion de solicitud - Email de confirmacion a validador*/
$recuperaCorreoSolicitud = "SELECT email_solicitante FROM solicitud WHERE  solicitud_id = ?";

/* Pagina de creacion de solicitud - Email de confirmacion a usuario*/
$recuperaEmail = "SELECT logon FROM usuario WHERE  usuario_id = ?";

/* Pagina Gestor - Combo del departamento */
$todosDepartamentosQuery = "SELECT
    DEPARTAMENTO_ID,
    DEPARTAMENTOS_DESC,
    ceco
FROM
    departamento
WHERE 
    markfordelete = 0
order by
    DEPARTAMENTOS_DESC";

/* Pagina Gestor - Informe global */
$consultaTodasImpresionesGlobal = "SELECT
    d.ceco AS Ceco,
    d.departamentos_desc AS Departamento,
    ROUND(SUM(case when td.tipo_id = '1' then td.preciototal else 0 end), 4) AS Espiral,
    ROUND(SUM(case when td.tipo_id = '2' then td.preciototal else 0 end), 4) AS Encolado,
    ROUND(SUM(case when td.tipo_id = '3' then td.preciototal else 0 end), 4) AS Varios1,
    ROUND(SUM(case when td.tipo_id = '4' then td.preciototal else 0 end), 4) AS Color,
    ROUND(SUM(case when td.tipo_id = '5' then td.preciototal else 0 end), 4) AS BlancoNegro,
    ROUND(SUM(case when td.tipo_id in ('6', '7') then td.preciototal else 0 end), 4) AS Varios1
FROM
    trabajodetalle td
INNER JOIN solicitud s ON
    s.solicitud_id = td.solicitud_id
INNER JOIN departamento d ON
    d.departamento_id = s.departamento_id
INNER JOIN subdepartamento s2 ON
    s2.departamento_id = s.departamento_id
    AND s2.subdepartamento_id = s.subdepartamento_id
WHERE 
    year(td.fecha_cierre) = ?
    AND month(td.fecha_cierre)= ?
group by
    d.departamento_id
union
select
    'cecoMaquinas' AS Ceco,
    concat('Maquinas ', d.departamentos_desc) AS Departamento,
    0,
    0,
    0,
    round(m1.color_total, 4),
    round(m1.byn_total, 4),
    0
FROM
    gastos_maquina m1
INNER JOIN departamento d ON
    d.departamento_id = m1.departamento_id
WHERE 
    year(m1.periodo) = ?
    AND month(m1.periodo) = ?
union
select
    'cecoImpresoras' AS Ceco,
    concat('Impresoras ', d.departamentos_desc) AS Departamento,
    0,
    0,
    0,
    round(m1.color_total, 4),
    round(m1.byn_total, 4),
    0
FROM
    gastos_impresora m1
INNER JOIN departamento d ON
    d.departamento_id = m1.departamento_id
WHERE 
    year(m1.periodo) = ?
    AND month(m1.periodo) = ?";  

$consultaTodasImpresionesGlobalNoMaq = "SELECT
    d.ceco AS Ceco,
    d.departamentos_desc AS Departamento,
    ROUND(SUM(case when td.tipo_id = '1' then td.preciototal else 0 end), 4) AS Espiral,
    ROUND(SUM(case when td.tipo_id = '2' then td.preciototal else 0 end), 4) AS Encolado,
    ROUND(SUM(case when td.tipo_id = '3' then td.preciototal else 0 end), 4) AS Varios1,
    ROUND(SUM(case when td.tipo_id = '4' then td.preciototal else 0 end), 4) AS Color,
    ROUND(SUM(case when td.tipo_id = '5' then td.preciototal else 0 end), 4) AS BlancoNegro,
    ROUND(SUM(case when td.tipo_id in ('6', '7') then td.preciototal else 0 end), 4) AS Varios1
FROM
    trabajodetalle td
INNER JOIN solicitud s ON
    s.solicitud_id = td.solicitud_id
INNER JOIN departamento d ON
    d.departamento_id = s.departamento_id
INNER JOIN subdepartamento s2 ON
    s2.departamento_id = s.departamento_id
    AND s2.subdepartamento_id = s.subdepartamento_id
WHERE 
    year(td.fecha_cierre) = ?
    AND month(td.fecha_cierre)= ?
group by
    d.departamento_id
";  

$consultaImpresionesSubdepartamento = "SELECT
    d.ceco AS Ceco,
    d.departamentos_desc AS Departamento,
    ROUND(SUM(case when td.tipo_id = '1' then td.preciototal else 0 end), 4) AS Espiral,
    ROUND(SUM(case when td.tipo_id = '2' then td.preciototal else 0 end), 4) AS Encolado,
    ROUND(SUM(case when td.tipo_id = '3' then td.preciototal else 0 end), 4) AS Varios1,
    ROUND(SUM(case when td.tipo_id = '4' then td.preciototal else 0 end), 4) AS Color,
    ROUND(SUM(case when td.tipo_id = '5' then td.preciototal else 0 end), 4) AS BlancoNegro,
    ROUND(SUM(case when td.tipo_id in ('6', '7') then td.preciototal else 0 end), 4) AS Varios2
FROM
    trabajodetalle td
INNER JOIN solicitud s ON
    s.solicitud_id = td.solicitud_id
INNER JOIN departamento d ON
    d.departamento_id = s.departamento_id
INNER JOIN subdepartamento s2 ON
    s2.departamento_id = s.departamento_id
    AND s2.subdepartamento_id = s.subdepartamento_id
WHERE 
    year(td.fecha_cierre) = ?
    AND month(td.fecha_cierre)= ?
    AND s2.departamento_id = ?
    AND s2.subdepartamento_id = ?
group by
    d.departamento_id
";  

$consultaImpresionesGlobalDptoNoMaq = "SELECT
    d.ceco AS Ceco,
    d.departamentos_desc AS Departamento,
    ROUND(SUM(case when td.tipo_id = '1' then td.preciototal else 0 end), 4) AS Espiral,
    ROUND(SUM(case when td.tipo_id = '2' then td.preciototal else 0 end), 4) AS Encolado,
    ROUND(SUM(case when td.tipo_id = '3' then td.preciototal else 0 end), 4) AS Varios1,
    ROUND(SUM(case when td.tipo_id = '4' then td.preciototal else 0 end), 4) AS Color,
    ROUND(SUM(case when td.tipo_id = '5' then td.preciototal else 0 end), 4) AS BlancoNegro,
    ROUND(SUM(case when td.tipo_id in ('6', '7') then td.preciototal else 0 end), 4) AS Varios1
FROM
    trabajodetalle td
INNER JOIN solicitud s ON
    s.solicitud_id = td.solicitud_id
INNER JOIN departamento d ON
    d.departamento_id = s.departamento_id
INNER JOIN subdepartamento s2 ON
    s2.departamento_id = s.departamento_id
    AND s2.subdepartamento_id = s.subdepartamento_id
WHERE 
    year(td.fecha_cierre) = ?
    AND month(td.fecha_cierre)= ?
    AND s.departamento_id = ?
group by
    d.departamento_id";  


$consultaImpresionesGlobalDptoSubdptoNoMaq = "SELECT
    d.ceco AS Ceco,
    d.departamentos_desc AS Departamento,
    ROUND(SUM(case when td.tipo_id = '1' then td.preciototal else 0 end), 4) AS Espiral,
    ROUND(SUM(case when td.tipo_id = '2' then td.preciototal else 0 end), 4) AS Encolado,
    ROUND(SUM(case when td.tipo_id = '3' then td.preciototal else 0 end), 4) AS Varios1,
    ROUND(SUM(case when td.tipo_id = '4' then td.preciototal else 0 end), 4) AS Color,
    ROUND(SUM(case when td.tipo_id = '5' then td.preciototal else 0 end), 4) AS BlancoNegro,
    ROUND(SUM(case when td.tipo_id in ('6', '7') then td.preciototal else 0 end), 4) AS Varios1
FROM
    trabajodetalle td
INNER JOIN solicitud s ON
    s.solicitud_id = td.solicitud_id
INNER JOIN departamento d ON
    d.departamento_id = s.departamento_id
INNER JOIN subdepartamento s2 ON
    s2.departamento_id = s.departamento_id
    AND s2.subdepartamento_id = s.subdepartamento_id
WHERE 
    year(td.fecha_cierre) = ?
    AND month(td.fecha_cierre)= ?
    AND s.departamento_id = ?
    AND s.subdepartamento_id = ?
group by
    d.departamento_id";  

$consultaImpresionesGlobalDpto = "SELECT
    d.ceco AS Ceco,
    d.departamentos_desc AS Departamento,
    ROUND(SUM(case when td.tipo_id = '1' then td.preciototal else 0 end), 4) AS Espiral,
    ROUND(SUM(case when td.tipo_id = '2' then td.preciototal else 0 end), 4) AS Encolado,
    ROUND(SUM(case when td.tipo_id = '3' then td.preciototal else 0 end), 4) AS Varios1,
    ROUND(SUM(case when td.tipo_id = '4' then td.preciototal else 0 end), 4) AS Color,
    ROUND(SUM(case when td.tipo_id = '5' then td.preciototal else 0 end), 4) AS BlancoNegro,
    ROUND(SUM(case when td.tipo_id in ('6', '7') then td.preciototal else 0 end), 4) AS Varios1,
    0,
    0
FROM
    trabajodetalle td
INNER JOIN solicitud s ON
    s.solicitud_id = td.solicitud_id
INNER JOIN departamento d ON
    d.departamento_id = s.departamento_id
INNER JOIN subdepartamento s2 ON
    s2.departamento_id = s.departamento_id
    AND s2.subdepartamento_id = s.subdepartamento_id
WHERE 
    year(td.fecha_cierre) = ?
    AND month(td.fecha_cierre)= ?
    AND s.departamento_id = ?
group by
    d.departamento_id
union
select
    'cecoMaquinas' AS Ceco,
    concat('Maquinas ', d.departamentos_desc) AS Departamento,
    0,
    0,
    0,
    0,
    0,
    0,
    round(m1.color_total, 4),
    round(m1.byn_total, 4)
FROM
    gastos_maquina m1
INNER JOIN departamento d ON
    d.departamento_id = m1.departamento_id
WHERE 
    year(m1.periodo) = ?
    AND month(m1.periodo) = ?
    AND m1.departamento_id = ?
union
select
    'cecoImpresoras' AS Ceco,
    concat('Impresoras ', d.departamentos_desc) AS Departamento,
    0,
    0,
    0,
    0,
    0,
    0,
    round(m1.color_total, 4),
    round(m1.byn_total, 4)
FROM
    gastos_impresora m1
INNER JOIN departamento d ON
    d.departamento_id = m1.departamento_id
WHERE 
    year(m1.periodo) = ?
    AND month(m1.periodo) = ?
    AND m1.departamento_id = ?";  

$consultaImpresionesGlobalDptoSubdpto = "SELECT
    d.ceco AS Ceco,
    d.departamentos_desc AS Departamento,
    ROUND(SUM(case when td.tipo_id = '1' then td.preciototal else 0 end), 4) AS Espiral,
    ROUND(SUM(case when td.tipo_id = '2' then td.preciototal else 0 end), 4) AS Encolado,
    ROUND(SUM(case when td.tipo_id = '3' then td.preciototal else 0 end), 4) AS Varios1,
    ROUND(SUM(case when td.tipo_id = '4' then td.preciototal else 0 end), 4) AS Color,
    ROUND(SUM(case when td.tipo_id = '5' then td.preciototal else 0 end), 4) AS BlancoNegro,
    ROUND(SUM(case when td.tipo_id in ('6', '7') then td.preciototal else 0 end), 4) AS Varios1
FROM
    trabajodetalle td
INNER JOIN solicitud s ON
    s.solicitud_id = td.solicitud_id
INNER JOIN departamento d ON
    d.departamento_id = s.departamento_id
INNER JOIN subdepartamento s2 ON
    s2.departamento_id = s.departamento_id
    AND s2.subdepartamento_id = s.subdepartamento_id
WHERE 
    year(td.fecha_cierre) = ?
    AND month(td.fecha_cierre)= ?
    AND s.departamento_id = ?
    AND s.subdepartamento_id = ?
group by
    d.departamento_id
union
select
    'cecoMaquinas' AS Ceco,
    concat('Maquinas ', d.departamentos_desc) AS Departamento,
    0,
    0,
    0,
    round(m1.color_total, 4),
    round(m1.byn_total, 4),
    0
FROM
    gastos_maquina m1
INNER JOIN departamento d ON
    d.departamento_id = m1.departamento_id
WHERE 
    year(m1.periodo) = ?
    AND month(m1.periodo) = ?
    AND m1.departamento_id = ?
union
select
    'cecoImpresoras' AS Ceco,
    concat('Impresoras ', d.departamentos_desc) AS Departamento,
    0,
    0,
    0,
    round(m1.color_total, 4),
    round(m1.byn_total, 4),
    0
FROM
    gastos_impresora m1
INNER JOIN departamento d ON
    d.departamento_id = m1.departamento_id
WHERE 
    year(m1.periodo) = ?
    AND month(m1.periodo) = ?
    AND m1.departamento_id = ?";  

/* Pagina Gestor - Informe Detallado */
$consultaTodasImpresionesDetalle = "SELECT
    td.solicitud_id AS Parte,
    d.ceco AS Ceco,
    d.departamentos_desc AS Departamento,
    s2.subdepartamento_desc AS Subepartamento,
    s2.treintabarra AS Treinta,
    s.nombre_solicitante AS Nombre,
    s.apellidos_solicitante AS Apellidos,
    replace(replace(SUBSTRING(s.descripcion_solicitante, 1, 30),
    '\r\n',
    ' '),
    '\t',
    ' ') AS Descripcion,
    td.fecha_cierre AS Fecha,
    ROUND(SUM(case when td.tipo_id = '1' then td.preciototal else 0 end), 4) AS Espiral,
    ROUND(SUM(case when td.tipo_id = '2' then td.preciototal else 0 end), 4) AS Encolado,
    ROUND(SUM(case when td.tipo_id = '3' then td.preciototal else 0 end), 4) AS Varios1,
    ROUND(SUM(case when td.tipo_id = '4' then td.preciototal else 0 end), 4) AS Color,
    ROUND(SUM(case when td.tipo_id = '5' then td.preciototal else 0 end), 4) AS BlancoNegro,
    ROUND(SUM(case when td.tipo_id in ('6', '7') then td.preciototal else 0 end), 4) AS Varios1
FROM
    trabajodetalle td
INNER JOIN solicitud s ON
    s.solicitud_id = td.solicitud_id
INNER JOIN departamento d ON
    d.departamento_id = s.departamento_id
INNER JOIN subdepartamento s2 ON
    s2.departamento_id = s.departamento_id
    AND s2.subdepartamento_id = s.subdepartamento_id
WHERE 
    year(td.fecha_cierre) = ?
    AND month(td.fecha_cierre)= ?
group by
    td.solicitud_id
union
select
    'treintabarraMaq' AS Parte,
    'cecoMaquinas' AS Ceco,
    concat('Maquinas ', d.departamentos_desc) AS Departamento,
    'Maquinas' AS Subepartamento,
    'Maquinas' AS Treinta,
    'Maquinas' AS Nombre,
    'Maquinas' AS Apellidos,
    'Maquinas' AS Descripcion,
    m1.periodo AS Fecha,
    0,
    0,
    0,
    round(m1.color_total, 4),
    round(m1.byn_total, 4),
    0
FROM
    gastos_maquina m1
INNER JOIN departamento d ON
    d.departamento_id = m1.departamento_id
WHERE 
    year(m1.periodo) = ?
    AND month(m1.periodo) = ?
union
select
    'treintabarraImp' AS Parte,
    'cecoImpresoras' AS Ceco,
    concat('Impresoras ', d.departamentos_desc) AS Departamento,
    'Impresoras' AS Subepartamento,
    'Impresoras' AS Treinta,
    'Impresoras' AS Nombre,
    'Impresoras' AS Apellidos,
    'Impresoras' AS Descripcion,
    m1.periodo AS Fecha,
    0,
    0,
    0,
    round(m1.color_total, 4),
    round(m1.byn_total, 4),
    0
FROM
    gastos_impresora m1
INNER JOIN departamento d ON
    d.departamento_id = m1.departamento_id
WHERE 
    year(m1.periodo) = ?
    AND month(m1.periodo) = ?";  

$consultaTodasImpresionesDetalleDpto = "SELECT
    td.solicitud_id AS Parte,
    d.ceco AS Ceco,
    d.departamentos_desc AS Departamento,
    s2.subdepartamento_desc AS Subdepartamento,
    s2.treintabarra AS Treinta,
    s.nombre_solicitante AS Nombre,
    s.apellidos_solicitante AS Apellidos,
    replace(replace(SUBSTRING(s.descripcion_solicitante, 1, 30),
    '\r\n',
    ' '),
    '\t',
    ' ') AS Descripcion,
    td.fecha_cierre AS Fecha,
    ROUND(SUM(case when td.tipo_id = '1' then td.preciototal else 0 end), 4) AS Espiral,
    ROUND(SUM(case when td.tipo_id = '2' then td.preciototal else 0 end), 4) AS Encolado,
    ROUND(SUM(case when td.tipo_id = '3' then td.preciototal else 0 end), 4) AS Varios1,
    ROUND(SUM(case when td.tipo_id = '4' then td.preciototal else 0 end), 4) AS Color,
    ROUND(SUM(case when td.tipo_id = '5' then td.preciototal else 0 end), 4) AS BlancoNegro,
    ROUND(SUM(case when td.tipo_id in ('6', '7') then td.preciototal else 0 end), 4) AS Varios1
FROM
    trabajodetalle td
INNER JOIN solicitud s ON
    s.solicitud_id = td.solicitud_id
INNER JOIN departamento d ON
    d.departamento_id = s.departamento_id
INNER JOIN subdepartamento s2 ON
    s2.departamento_id = s.departamento_id
    AND s2.subdepartamento_id = s.subdepartamento_id
WHERE 
    year(td.fecha_cierre) = ?
    AND month(td.fecha_cierre)= ?
    AND d.departamento_id = ?
group by
    td.solicitud_id
union
select
    'treintabarraMaq' AS Parte,
    'cecoMaquinas' AS Ceco,
    concat('Maquinas ', d.departamentos_desc) AS Departamento,
    'Maquinas' AS Subepartamento,
    'Maquinas' AS Treinta,
    'Maquinas' AS Nombre,
    'Maquinas' AS Apellidos,
    'Maquinas' AS Descripcion,
    m1.periodo AS Fecha,
    0,
    0,
    0,
    round(m1.color_total, 4),
    round(m1.byn_total, 4),
    0
FROM
    gastos_maquina m1
INNER JOIN departamento d ON
    d.departamento_id = m1.departamento_id
WHERE 
    year(m1.periodo) = ?
    AND month(m1.periodo) = ?
    AND m1.departamento_id = ?
union
select
    'treintabarraImp' AS Parte,
    'cecoImpresoras' AS Ceco,
    concat('Impresoras ', d.departamentos_desc) AS Departamento,
    'Impresoras' AS Subepartamento,
    'Impresoras' AS Treinta,
    'Impresoras' AS Nombre,
    'Impresoras' AS Apellidos,
    'Impresoras' AS Descripcion,
    m1.periodo AS Fecha,
    0,
    0,
    0,
    round(m1.color_total, 4),
    round(m1.byn_total, 4),
    0
FROM
    gastos_impresora m1
INNER JOIN departamento d ON
    d.departamento_id = m1.departamento_id
WHERE 
    year(m1.periodo) = ?
    AND month(m1.periodo) = ?
    AND m1.departamento_id = ?";

$consultaTodasImpresionesDetalleDptoSubdpto = "SELECT
    td.solicitud_id AS Parte,
    d.ceco AS Ceco,
    d.departamentos_desc AS Departamento,
    s2.subdepartamento_desc AS Subdepartamento,
    s2.treintabarra AS Treinta,
    s.nombre_solicitante AS Nombre,
    s.apellidos_solicitante AS Apellidos,
    replace(replace(SUBSTRING(s.descripcion_solicitante, 1, 30),
    '\r\n',
    ' '),
    '\t',
    ' ') AS Descripcion,
    td.fecha_cierre AS Fecha,
    ROUND(SUM(case when td.tipo_id = '1' then td.preciototal else 0 end), 4) AS Espiral,
    ROUND(SUM(case when td.tipo_id = '2' then td.preciototal else 0 end), 4) AS Encolado,
    ROUND(SUM(case when td.tipo_id = '3' then td.preciototal else 0 end), 4) AS Varios1,
    ROUND(SUM(case when td.tipo_id = '4' then td.preciototal else 0 end), 4) AS Color,
    ROUND(SUM(case when td.tipo_id = '5' then td.preciototal else 0 end), 4) AS BlancoNegro,
    ROUND(SUM(case when td.tipo_id in ('6', '7') then td.preciototal else 0 end), 4) AS Varios1
FROM
    trabajodetalle td
INNER JOIN solicitud s ON
    s.solicitud_id = td.solicitud_id
INNER JOIN departamento d ON
    d.departamento_id = s.departamento_id
INNER JOIN subdepartamento s2 ON
    s2.departamento_id = s.departamento_id
    AND s2.subdepartamento_id = s.subdepartamento_id
WHERE 
    year(td.fecha_cierre) = ?
    AND month(td.fecha_cierre)= ?
    AND s.departamento_id = ?
    AND s.subdepartamento_id = ?
group by
    td.solicitud_id
union
select
    'treintabarraMaq' AS Parte,
    'cecoMaquinas' AS Ceco,
    concat('Maquinas ', d.departamentos_desc) AS Departamento,
    'Maquinas' AS Subepartamento,
    'Maquinas' AS Treinta,
    'Maquinas' AS Nombre,
    'Maquinas' AS Apellidos,
    'Maquinas' AS Descripcion,
    m1.periodo AS Fecha,
    0,
    0,
    0,
    round(m1.color_total, 4),
    round(m1.byn_total, 4),
    0
FROM
    gastos_maquina m1
INNER JOIN departamento d ON
    d.departamento_id = m1.departamento_id
WHERE 
    year(m1.periodo) = ?
    AND month(m1.periodo) = ?
    AND m1.departamento_id = ?
union
select
    'treintabarraImp' AS Parte,
    'cecoImpresoras' AS Ceco,
    concat('Impresoras ', d.departamentos_desc) AS Departamento,
    'Impresoras' AS Subepartamento,
    'Impresoras' AS Treinta,
    'Impresoras' AS Nombre,
    'Impresoras' AS Apellidos,
    'Impresoras' AS Descripcion,
    m1.periodo AS Fecha,
    0,
    0,
    0,
    round(m1.color_total, 4),
    round(m1.byn_total, 4),
    0
FROM
    gastos_impresora m1
INNER JOIN departamento d ON
    d.departamento_id = m1.departamento_id
WHERE 
    year(m1.periodo) = ?
    AND month(m1.periodo) = ?
    AND m1.departamento_id = ?";  

/* Pagina Administrador - Consulta de departamento */
$actualizaDepartamentoQuery = "SELECT
    DEPARTAMENTO_ID,
    DEPARTAMENTOS_DESC,
    ceco
FROM
    departamento
WHERE 
    DEPARTAMENTO_ID = ?
    AND markfordelete = 0";

/* Pagina Administrador - Consulta de todos los departamentos */
$informeDepartamentos = "SELECT
    d1.departamento_id ,
    d1.departamentos_desc ,
    d1.ceco ,
    s1.subdepartamento_id ,
    s1.subdepartamento_desc ,
    s1.treintabarra
FROM
    departamento d1
INNER JOIN subdepartamento s1 ON
    s1.departamento_id = d1.departamento_id
WHERE 
    d1.markfordelete = 0
    or s1.markfordelete = 0
order by
    d1.departamentos_desc ,
    s1.subdepartamento_desc";  

/* Pagina Administrador - Consulta Max de subdepartamento para su insercion */
$recuperaMaxSubDptoQuery = "SELECT
    MAX(subdepartamento_id)+ 1
FROM
    subdepartamento
WHERE 
    departamento_id = ?";

/* Pagina Administrador - Modificacion subdepartamento */
$recuperaSubdepartamento = "SELECT
    d1.subdepartamento_desc ,
    d1.treintabarra
FROM
    subdepartamento d1
INNER JOIN departamento s1 ON
    s1.departamento_id = d1.departamento_id
    AND s1.departamento_id = ?
    AND d1.subdepartamento_id = ?
    AND d1.markfordelete = 0";  

/* Pagina Administrador - Alta Usuario */
$recuperaRole = "SELECT role_id , role_desc FROM role";

$recuperaMaxUsuario = "SELECT MAX(USUARIO_ID)+1 AS idUsuario FROM usuario";

$recuperaIdSubdptoXDpto = "SELECT subdepartamento_id FROM subdepartamento WHERE  departamento_id = ? AND markfordelete = 0";

/* Pagina Administrador - Consulta Usuario */
$loginQuery = "SELECT usuario_id, nombre, apellido, role_id FROM usuario ORDER BY nombre";

/* Pagina Administrador - Modificacion Usuario */
$consultaUsuarioQuery = "SELECT USUARIO_ID , LOGON , NOMBRE , APELLIDO , ROLE_ID , password FROM usuario WHERE  USUARIO_ID = ?";

/* Pagina Administrador - Alta de Articulo */
$recuperaTipos = "SELECT TIPO_ID , TIPO_DESC FROM tipo";

$recuperaMaxDetalle = "SELECT MAX(DETALLE_ID)+1 AS DETALLEID FROM detalle WHERE  TIPO_ID=?";

/* Pagina Administrador - Baja de Articulo */
$recuperaDetalle = "SELECT detalle_id , descripcion FROM detalle WHERE  tipo_id=?";

/* Pagina Administrador - Modificacion de Articulo */
$recuperaDetallePorId = "SELECT detalle_id , descripcion , precio FROM detalle WHERE  tipo_id =? AND detalle_id=?";

/* Pagina Administrador - Trabajos */
$consultaTodosTrabajos = "SELECT
    s1.solicitud_id,
    d1.departamentos_desc,
    sd1.subdepartamento_desc,
    s1.nombre_solicitante,
    s1.apellidos_solicitante,
    s1.fecha_alta,
    s1.fecha_cierre,
    u1.nombre,
    u1.apellido,
    s1.descripcion_solicitante,
    s1.email_solicitante,
    s2.status_desc,
    s1.status_id,
    s1.fecha_alta
FROM
    solicitud s1
INNER JOIN departamento d1 ON
    s1.departamento_id = d1.departamento_id
    AND d1.markfordelete = 0
INNER JOIN subdepartamento sd1 ON
    s1.departamento_id = sd1.departamento_id
    AND s1.subdepartamento_id = sd1.subdepartamento_id
INNER JOIN status s2 ON
    s1.status_id = s2.status_id
INNER JOIN usuario u1 ON
    s1.autorizador_id = u1.usuario_id
order by
    s1.solicitud_id desc";  

$consultaTodosTrabajosDepartamento = "SELECT
    s1.solicitud_id,
    d1.departamentos_desc,
    sd1.subdepartamento_desc,
    s1.nombre_solicitante,
    s1.apellidos_solicitante,
    s1.fecha_alta,
    s1.fecha_cierre,
    u1.nombre,
    u1.apellido,
    s1.descripcion_solicitante,
    s1.email_solicitante,
    s2.status_desc,
    s1.status_id,
    s1.fecha_alta
FROM
    solicitud s1
INNER JOIN departamento d1 ON
    s1.departamento_id = d1.departamento_id
    AND d1.markfordelete = 0
    AND d1.departamento_id = ?
INNER JOIN subdepartamento sd1 ON
    s1.departamento_id = sd1.departamento_id
    AND s1.subdepartamento_id = sd1.subdepartamento_id
INNER JOIN status s2 ON
    s1.status_id = s2.status_id
INNER JOIN usuario u1 ON
    s1.autorizador_id = u1.usuario_id
order by
    s1.solicitud_id desc";  

$consultaTodosTrabajosFecha = "SELECT
    s1.solicitud_id,
    d1.departamentos_desc,
    sd1.subdepartamento_desc,
    s1.nombre_solicitante,
    s1.apellidos_solicitante,
    s1.fecha_alta,
    s1.fecha_cierre,
    u1.nombre,
    u1.apellido,
    s1.descripcion_solicitante,
    s1.email_solicitante,
    s2.status_desc,
    s1.status_id,
    s1.fecha_alta
FROM
    solicitud s1
INNER JOIN departamento d1 ON
    s1.departamento_id = d1.departamento_id
    AND d1.markfordelete = 0
INNER JOIN subdepartamento sd1 ON
    s1.departamento_id = sd1.departamento_id
    AND s1.subdepartamento_id = sd1.subdepartamento_id
INNER JOIN status s2 ON
    s1.status_id = s2.status_id
INNER JOIN usuario u1 ON
    s1.autorizador_id = u1.usuario_id
WHERE 
    month(s1.fecha_alta) = ?
    AND year(s1.fecha_alta) = ?
order by
    s1.solicitud_id desc";  

$consultaTodosTrabajosDepartamentoFecha = "SELECT
    s1.solicitud_id,
    d1.departamentos_desc,
    sd1.subdepartamento_desc,
    s1.nombre_solicitante,
    s1.apellidos_solicitante,
    s1.fecha_alta,
    s1.fecha_cierre,
    u1.nombre,
    u1.apellido,
    s1.descripcion_solicitante,
    s1.email_solicitante,
    s2.status_desc,
    s1.status_id,
    s1.fecha_alta
FROM
    solicitud s1
INNER JOIN departamento d1 ON
    s1.departamento_id = d1.departamento_id
    AND d1.markfordelete = 0
    AND d1.departamento_id = ?
INNER JOIN subdepartamento sd1 ON
    s1.departamento_id = sd1.departamento_id
    AND s1.subdepartamento_id = sd1.subdepartamento_id
INNER JOIN status s2 ON
    s1.status_id = s2.status_id
INNER JOIN usuario u1 ON
    s1.autorizador_id = u1.usuario_id
WHERE 
    month(s1.fecha_alta) = ?
    AND year(s1.fecha_alta) = ?
order by
    s1.solicitud_id desc";  

/* Pagina Administrador - Baja Impresoras */
$consultaImpresoras = "SELECT IMPRESORA_ID , MODELO , EDIFICIO , UBICACION , FECHA , SERIE , NUMERO FROM impresoras ORDER BY UBICACION";

/* Pagina Administrador - Modificacion Impresoras */
$consultaImpresorasPorId = "SELECT IMPRESORA_ID , MODELO , EDIFICIO , UBICACION , FECHA , SERIE , NUMERO FROM impresoras WHERE  IMPRESORA_ID = ?";

/* Pagina Administrador - Cierre - Gastos Maquina */
$recuperaGastosMaquinaCierre = "SELECT
    departamento.departamento_id AS departamento_id,
    departamento.ceco AS ceco,
    gastos_maquina.periodo AS periodo,
    gastos_maquina.byn_unidades AS byn_unidades,
    (
    select
        precio
    FROM
        detalle
    WHERE 
        tipo_id = 5
        AND detalle_id = 1 ) AS byn_precio,
    gastos_maquina.byn_total AS byn_total,
    gastos_maquina.color_unidades AS color_unidades,
    (
    select
        precio
    FROM
        detalle
    WHERE 
        tipo_id = 4
        AND detalle_id = 1 ) AS color_precio,
    gastos_maquina.color_total AS color_total
FROM
    departamento
LEFT OUTER JOIN gastos_maquina ON
    departamento.departamento_id = gastos_maquina.departamento_id
    AND month(gastos_maquina.periodo) = ?
    AND year(gastos_maquina.periodo) = ?";  

/* Pagina Administrador - Cierre - Gastos Impresora */
$recuperaGastosImpresoraCierre = "SELECT
    departamento.departamento_id AS departamento_id,
    gastos_impresora.periodo AS periodo,
    gastos_impresora.byn_unidades AS byn_unidades,
    (
    select
        precio
    FROM
        detalle
    WHERE 
        tipo_id = 5
        AND detalle_id = 1 ) AS byn_precio,
    gastos_impresora.byn_total AS byn_total,
    gastos_impresora.color_unidades AS color_unidades,
    (
    select
        precio
    FROM
        detalle
    WHERE 
        tipo_id = 4
        AND detalle_id = 1 ) AS color_precio,
    gastos_impresora.color_total AS color_total
FROM
    departamento
LEFT OUTER JOIN gastos_impresora ON
    departamento.departamento_id = gastos_impresora.departamento_id
    AND month(gastos_impresora.periodo) = ?
    AND year(gastos_impresora.periodo) = ?";  

/* Pagina Trabajo - Home */
$recuperaTrabajos = "SELECT
    s1.solicitud_id ,
    s1.departamento_id ,
    s1.nombre_solicitante,
    s1.apellidos_solicitante ,
    s1.autorizador_id ,
    s1.descripcion_solicitante ,
    s1.email_solicitante ,
    s1.status_id ,
    s1.fecha_alta ,
    s1.fecha_validacion ,
    s1.fecha_cierre ,
    d1.departamentos_desc ,
    s1.usuario_plantilla
FROM
    solicitud s1
INNER JOIN departamento d1 ON
    d1.departamento_id = s1.departamento_id
    AND d1.markfordelete = 0
WHERE 
    s1.status_id in (2, 4, 5)";  

/* Pagina Trabajo - Solicitud */
$sentenciaDepartamentoJSON = "SELECT
d1.departamentos_desc,
d1.ceco
FROM
departamento d1
INNER JOIN solicitud s1 ON
s1.departamento_id = d1.departamento_id
AND s1.solicitud_id = ?
AND d1.markfordelete = 0";

$sentenciaSubDepartamentoJSON = "SELECT
    d1.subdepartamento_desc,
    d1.treintabarra
FROM
    subdepartamento d1
INNER JOIN solicitud s1 ON
    s1.subdepartamento_id = d1.subdepartamento_id
    AND s1.solicitud_id = ?
    AND d1.markfordelete = 0";  

$variosUnoQuery = "SELECT
    d1.tipo_id AS tipo,
    d1.detalle_id AS detalle,
    d1.descripcion AS descripcion,
    d1.precio AS precio,
    td.unidades AS unidades,
    td.preciototal AS preciototal
FROM
    detalle d1
LEFT OUTER JOIN trabajodetalle td ON
    td.tipo_id = d1.tipo_id
    AND td.detalle_id = d1.detalle_id
    AND td.solicitud_id = ?
WHERE 
    d1.tipo_id = 3";  

$colorQuery = "SELECT
    d1.tipo_id AS tipo,
    d1.detalle_id AS detalle,
    d1.descripcion AS descripcion,
    d1.precio AS precio,
    td.unidades AS unidades,
    td.preciototal AS preciototal
FROM
    detalle d1
LEFT OUTER JOIN trabajodetalle td ON
    td.tipo_id = d1.tipo_id
    AND td.detalle_id = d1.detalle_id
    AND td.solicitud_id = ?
WHERE 
    d1.tipo_id = 4";  

$encuadernacionQuery = "SELECT
    d1.tipo_id AS tipo,
    d1.detalle_id AS detalle,
    d1.descripcion AS descripcion,
    d1.precio AS precio,
    td.unidades AS unidades,
    td.preciototal AS preciototal
FROM
    detalle d1
LEFT OUTER JOIN trabajodetalle td ON
    td.tipo_id = d1.tipo_id
    AND td.detalle_id = d1.detalle_id
    AND td.solicitud_id = ?
WHERE 
    d1.tipo_id = 1";  

$encoladoQuery = "SELECT
    d1.tipo_id AS tipo,
    d1.detalle_id AS detalle,
    d1.descripcion AS descripcion,
    d1.precio AS precio,
    td.unidades AS unidades,
    td.preciototal AS preciototal
FROM
    detalle d1
LEFT OUTER JOIN trabajodetalle td ON
    td.tipo_id = d1.tipo_id
    AND td.detalle_id = d1.detalle_id
    AND td.solicitud_id = ?
WHERE 
    d1.tipo_id = 2";  

$blancoYNegroQuery = "SELECT
    d1.tipo_id AS tipo,
    d1.detalle_id AS detalle,
    d1.descripcion AS descripcion,
    d1.precio AS precio,
    td.unidades AS unidades,
    td.preciototal AS preciototal
FROM
    detalle d1
LEFT OUTER JOIN trabajodetalle td ON
    td.tipo_id = d1.tipo_id
    AND td.detalle_id = d1.detalle_id
    AND td.solicitud_id = ?
WHERE 
    d1.tipo_id = 5";  

$varios2QueryTabla = "SELECT
    d1.tipo_id AS tipo,
    d1.detalle_id AS detalle,
    d1.descripcion AS descripcion,
    d1.precio AS precio,
    td.unidades AS unidades,
    td.preciototal AS preciototal
FROM
    detalle d1
INNER JOIN trabajodetalle td ON
    td.tipo_id = d1.tipo_id
    AND td.detalle_id = d1.detalle_id
WHERE 
    d1.tipo_id = 6
    AND td.solicitud_id = ?";  

$varios2ExtraQuery = "SELECT
    d1.tipo_id AS tipo,
    d1.detalle_id AS detalle,
    d1.descripcion AS descripcion,
    d1.precio AS precio,
    td.unidades AS unidades,
    td.preciototal AS preciototal
FROM
    detalle d1
INNER JOIN trabajodetalle td ON
    td.tipo_id = d1.tipo_id
    AND td.detalle_id = d1.detalle_id
    AND td.solicitud_id = ?
WHERE 
    d1.tipo_id = 7";  

$varios2Query = "SELECT
    d1.tipo_id AS tipo,
    d1.detalle_id AS detalle,
    d1.descripcion AS descripcion,
    d1.precio AS precio,
    td.unidades AS unidades,
    td.preciototal AS preciototal
FROM
    detalle d1
LEFT OUTER JOIN trabajodetalle td ON
    td.tipo_id = d1.tipo_id
    AND td.detalle_id = d1.detalle_id
    AND td.solicitud_id = ?
WHERE 
    d1.tipo_id = 6";  

$sentenciaSolicitanteJSON = "SELECT
    nombre_solicitante AS nombre,
    apellidos_solicitante AS apellido
FROM
    solicitud
WHERE 
    solicitud_id = ?";

$detalleVarios2PorId = "SELECT
    detalle_id,
    tipo_id,
    descripcion,
    precio
FROM
    detalle
WHERE 
    tipo_id = 6
    AND detalle_id = ?";

$consultaTrabajoJSON = "SELECT
    solicitud_id
FROM
    trabajo
WHERE 
    solicitud_id = ?";

$consultaCeco = "SELECT
    d1.ceco
FROM
    departamento d1
INNER JOIN solicitud s1 ON
    s1.departamento_id = d1.departamento_id
    AND s1.solicitud_id = ?
    AND d1.markfordelete = 0";  

$consultaSubDepartamentoId = "SELECT
    sd1.subdepartamento_id
FROM
    subdepartamento sd1
INNER JOIN solicitud s1 ON
    s1.departamento_id = sd1.departamento_id
    AND s1.subdepartamento_id = sd1.subdepartamento_id
    AND s1.solicitud_id = ?
    AND sd1.markfordelete = 0";  

$consultaCodigo = "SELECT
    sd1.treintabarra
FROM
    subdepartamento sd1
INNER JOIN solicitud s1 ON
    s1.departamento_id = sd1.departamento_id
    AND s1.subdepartamento_id = sd1.subdepartamento_id
    AND s1.solicitud_id = ?
    AND sd1.markfordelete = 0";  

$consultaDepartamentoId = "SELECT
    d1.departamento_id
FROM
    departamento d1
INNER JOIN solicitud s1 ON
    s1.departamento_id = d1.departamento_id
    AND s1.solicitud_id = ?
    AND d1.markfordelete = 0";  
    
$consultaDetalleJSON = "SELECT
    unidades,
    preciototal
FROM
    trabajodetalle
WHERE 
    trabajo_id = ?
    AND tipo_id = ?
    AND detalle_id = ?
    AND solicitud_id = ?";

$comprobarVarios2ExtraJSON = "select
    td.detalle_id
from
    detalle d1
    inner join trabajodetalle td on
    td.tipo_id = d1.tipo_id
    and td.detalle_id = d1.detalle_id
where
    td.tipo_id=7 and  
    d1.descripcion = ? and
    td.solicitud_id = ?";

$comprobarVarios2TrabajoExtraJSON = "SELECT 
    trabajo_id, 
    tipo_id, 
    detalle_id, 
    unidades,
    fecha_cierre,
    solicitud_id, 
    preciototal 
FROM 
    trabajodetalle 
WHERE  
    tipo_id=7 AND 
    solicitud_id = ? AND 
    detalle_id = ?";


$consultaTodasMaquinas = "select
d.ceco AS Ceco,
d.departamentos_desc AS Departamento,
0,
0,
0,
0,
0,
0,
round(m1.color_total, 4),
round(m1.byn_total, 4)
FROM
gastos_maquina m1
INNER JOIN departamento d ON
d.departamento_id = m1.departamento_id
WHERE 
year(m1.periodo) = ?
AND month(m1.periodo) = ?";

$consultaTodasMaquinasDpto = "select
d.ceco AS Ceco,
d.departamentos_desc AS Departamento,
0,
0,
0,
0,
0,
0,
round(m1.color_total, 4),
round(m1.byn_total, 4)
FROM
gastos_maquina m1
INNER JOIN departamento d ON
d.departamento_id = m1.departamento_id
WHERE 
year(m1.periodo) = ?
AND month(m1.periodo) = ?
AND m1.departamento_id = ?";

$consultaTodasImpresoras = "select
d.ceco AS Ceco,
d.departamentos_desc AS Departamento,
0,
0,
0,
0,
0,
0,
round(m1.color_total, 4),
round(m1.byn_total, 4)
FROM
gastos_impresora m1
INNER JOIN departamento d ON
d.departamento_id = m1.departamento_id
WHERE 
year(m1.periodo) = ?
AND month(m1.periodo) = ?";

$consultaTodasImpresorasDpto = "select
d.ceco AS Ceco,
d.departamentos_desc AS Departamento,
0,
0,
0,
0,
0,
0,
round(m1.color_total, 4),
round(m1.byn_total, 4)
FROM
gastos_impresora m1
INNER JOIN departamento d ON
d.departamento_id = m1.departamento_id
WHERE 
year(m1.periodo) = ?
AND month(m1.periodo) = ?
AND m1.departamento_id = ?";

$existeGastoImpresora = "SELECT * FROM gastos_impresora WHERE departamento_id    =? and YEAR(periodo)  = ? and MONTH(periodo) = ?";

$existeDepartamentoFichero = "SELECT departamento_id from departamento where ceco = ?";
$existeSubdepartamentoFichero = "SELECT subdepartamento_id from subdepartamento where departamento_id = ? AND treintabarra = ?";
