<?php

/**
 * Class: query.php
 *
 * Insercion de Varios 2 Extra
 * php version 7.3.28
 * 
 * @category Select
 * @package  DaoSelect
 * @author   Jesus Madrazo <chasmaca@gmail.com>
 * @license  http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version  GIT: <Initial Import>
 * @link     https://www.elpartedigital.com/
 */

$todosDepartamentosQuery = "SELECT
                                DEPARTAMENTO_ID,
                                DEPARTAMENTOS_DESC,
                                ceco
                            FROM
                                departamento
                            WHERE
                                markfordelete = 0
                            ORDER BY 
                                DEPARTAMENTOS_DESC";

$actualizaDepartamentoQuery = "SELECT
                                    DEPARTAMENTO_ID,
                                    DEPARTAMENTOS_DESC,
                                    ceco
                                FROM
                                    departamento
                                WHERE
                                    DEPARTAMENTO_ID = ? AND
                                    markfordelete = 0";

$todosAutorizadoresQuery = "SELECT
                                usuario_id AS AUTORIZADOR_ID,
                                upper(nombre) AS AUTORIZADOR_NOMBRE,
                                upper(apellido) AS AUTORIZADOR_APELLIDOS,
                                logon AS AUTORIZADOR_EMAIL
                            FROM
                                usuario
                            WHERE
                                role_id = 3
                            ORDER BY
                                AUTORIZADOR_NOMBRE,AUTORIZADOR_APELLIDOS";

$maximaSolicidud = "SELECT
                        MAX(SOLICITUD_ID)+1 AS SOLICITUD_MAX
                    FROM
                        solicitud";

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
                                            INNER JOIN
                                                departamento de1 
                                            ON 
                                                d1.departamento_id = de1.departamento_id
                                            INNER JOIN
                                                subdepartamento sd1
                                            ON
                                                d1.subdepartamento_id = sd1.subdepartamento_id AND
                                                d1.departamento_id = sd1.departamento_id AND
                                                de1.markfordelete= 0 AND
                                                sd1.markfordelete = 0
                                    WHERE
                                        d1.status_id = 1 AND
                                        d1.autorizador_id = ?";

$loginQuery = "SELECT
                    usuario_id,
                    nombre,
                    apellido,
                    role_id
                FROM
                    usuario
                ORDER BY
                    nombre";



$recuperaMaxUsuario =  "SELECT
                            MAX(USUARIO_ID)+1 AS idUsuario 
                        FROM 
                            usuario";

$recuperaUsuarios =     "SELECT
                            USUARIO_ID, LOGON, PASSWORD, NOMBRE, APELLIDO, ROLE_ID 
                        FROM 
                            usuario 
                        ORDER BY 
                            nombre,apellido";

$recuperaTipos = "SELECT
TIPO_ID
, TIPO_DESC
FROM
tipo";

$recuperaDetalle = "SELECT
detalle_id
, descripcion
FROM
detalle
WHERE
tipo_id=?";

$recuperaDetallePorId = "SELECT
detalle_id
, descripcion
, precio
FROM
detalle
WHERE
tipo_id       =?
AND detalle_id=?";

$recuperaMaxDetalle = "SELECT
MAX(DETALLE_ID)+1 AS DETALLEID
FROM
detalle
WHERE
TIPO_ID=?";

$consultaUsuarioQuery = "SELECT
USUARIO_ID
, LOGON
, NOMBRE
, APELLIDO
, ROLE_ID
, password
FROM
usuario
WHERE
USUARIO_ID = ?";

$consultaUsuarioValidador = "SELECT DISTINCT
(departamento_id)
FROM
usuariodepartamento
WHERE
USUARIO_ID = ?";

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
                                INNER JOIN
                                    departamento d1 ON
                                        s1.departamento_id = d1.departamento_id AND
                                        d1.markfordelete = 0
                                INNER JOIN
                                    subdepartamento sd1 ON
                                        s1.departamento_id = sd1.departamento_id AND
                                        s1.subdepartamento_id = sd1.subdepartamento_id 
                                INNER JOIN
                                    status s2 ON
                                        s1.status_id = s2.status_id
                                INNER JOIN
                                    usuario u1 ON
                                        s1.autorizador_id = u1.usuario_id
                        ORDER BY s1.solicitud_id DESC";

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
                                INNER JOIN
                                    departamento d1 ON
                                        s1.departamento_id = d1.departamento_id AND
                                        d1.markfordelete = 0 AND
                                        d1.departamento_id = ?
                                INNER JOIN
                                    subdepartamento sd1 ON
                                        s1.departamento_id = sd1.departamento_id AND
                                        s1.subdepartamento_id = sd1.subdepartamento_id 
                                INNER JOIN
                                    status s2 ON
                                        s1.status_id = s2.status_id
                                INNER JOIN
                                    usuario u1 ON
                                        s1.autorizador_id = u1.usuario_id
                        ORDER BY s1.solicitud_id DESC";

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
                                INNER JOIN
                                    departamento d1 ON
                                        s1.departamento_id = d1.departamento_id AND
                                        d1.markfordelete = 0
                                INNER JOIN
                                    subdepartamento sd1 ON
                                        s1.departamento_id = sd1.departamento_id AND
                                        s1.subdepartamento_id = sd1.subdepartamento_id 
                                INNER JOIN
                                    status s2 ON
                                        s1.status_id = s2.status_id
                                INNER JOIN
                                    usuario u1 ON
                                        s1.autorizador_id = u1.usuario_id
                        WHERE
                            MONTH(s1.fecha_alta) = ? and
                            YEAR(s1.fecha_alta) = ?
                        ORDER BY s1.solicitud_id DESC";


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
                                INNER JOIN
                                    departamento d1 ON
                                        s1.departamento_id = d1.departamento_id AND
                                        d1.markfordelete = 0 AND
                                        d1.departamento_id = ?
                                INNER JOIN
                                    subdepartamento sd1 ON
                                        s1.departamento_id = sd1.departamento_id AND
                                        s1.subdepartamento_id = sd1.subdepartamento_id 
                                INNER JOIN
                                    status s2 ON
                                        s1.status_id = s2.status_id
                                INNER JOIN
                                    usuario u1 ON
                                        s1.autorizador_id = u1.usuario_id
                        WHERE
                            MONTH(s1.fecha_alta) = ? and
                            YEAR(s1.fecha_alta) = ?
                        ORDER BY s1.solicitud_id DESC";

$generaInforme = "SELECT
                    d1.treintabarra AS codigo,
                    d1.CeCo,
                    t1.departamento_id,
                    d1.departamentos_desc,
                    s1.fecha_cierre,
                    t1.precioByN,
                    t1.precioColor,
                    t1.precioEncuadernacion,
                    t1.PrecioVarios
                FROM
                    trabajo t1
                        INNER JOIN
                            departamento d1 ON
                                t1.departamento_id = d1.departamento_id AND
                                d1.markfordelete = 0
                        INNER JOIN
                            solicitud s1 ON
                                t1.solicitud_id = s1.solicitud_id AND
                                s1.status_id = 6";

$generaInformeGlobal = "SELECT
t1.codigo
, t1.CeCo
, t1.departamento_id
, d1.departamentos_desc
, sum(t1.precioByN)            AS byn
, sum(t1.precioColor)          AS color
, sum(t1.precioEncuadernacion) AS encuadernacion
, sum(t1.PrecioVarios)         AS varios
FROM
trabajo t1
INNER JOIN
           departamento d1
           ON
                      t1.departamento_id   = d1.departamento_id
                      AND d1.markfordelete = 0
GROUP BY
t1.codigo";

$generaInformeGlobalMes = "SELECT
sd1.treintabarra AS codigo
, d1.CeCo
, t1.departamento_id
, d1.departamentos_desc
, sum(t1.precioByN)            AS byn
, sum(t1.precioColor)          AS color
, sum(t1.precioEncuadernacion) AS encuadernacion
, sum(t1.PrecioVarios)         AS varios
, gi1.byn_total                AS impresorasByN
, gi1.color_total              AS impresorasColor
, gm1.byn_total                AS maquinasByN
, gm1.color_total              AS maquinasColor
, sd1.subdepartamento_desc     AS subdepartamentos_desc
FROM
trabajo t1
INNER JOIN
                departamento d1
                ON
                                t1.departamento_id = d1.departamento_id
LEFT OUTER JOIN
                gastos_impresora gi1
                ON
                                t1.departamento_id = gi1.departamento_id
LEFT OUTER JOIN
                gastos_maquina gm1
                ON
                                t1.departamento_id = gm1.departamento_id
INNER JOIN
                solicitud s1
                ON
                                t1.solicitud_id  = s1.solicitud_id
                                AND s1.status_id = 6";

$recuperaEmail = "SELECT logon FROM usuario WHERE usuario_id = ?";

$generaInformeMes = "SELECT
                        s1.solicitud_id AS codigo,
                        d1.CeCo AS ceco,
                        t1.departamento_id  AS departamentoId,
                        d1.departamentos_desc AS departamentoDesc,
                        s1.fecha_cierre  AS fechaCierre,
                        t1.precioByN AS byn,
                        t1.precioColor AS color,
                        t1.precioEncuadernacion AS encuadernacion,
                        t1.PrecioVarios AS varios,
                        sd1.subdepartamento_desc AS subdepartamentos_desc,
                        replace(s1.descripcion_solicitante,'\r',' ')  AS descripcion,
                        s1.nombre_solicitante AS nombre,
                        s1.apellidos_solicitante AS apellido
                    FROM trabajo t1
                        INNER JOIN departamento d1 ON t1.departamento_id = d1.departamento_id
                        INNER JOIN solicitud s1 ON t1.solicitud_id = s1.solicitud_id AND s1.status_id = 6
                        right join subdepartamento sd1 ON sd1.departamento_id = s1.departamento_id AND 
                                    sd1.subdepartamento_id = s1.subdepartamento_id
                    WHERE  YEAR(s1.fecha_cierre) =";

$recuperaDptoXAutorizador = "SELECT DISTINCT
(d1.departamento_id)  AS DEPARTAMENTO_ID
, d1.departamentos_desc AS DEPARTAMENTOS_DESC
, d1.ceco               AS CECO
FROM
usuario u1
INNER JOIN
           usuariodepartamento ud1
           ON
                      ud1.usuario_id = u1.usuario_id
INNER JOIN
           departamento d1
           ON
                      ud1.departamento_id = d1.departamento_id
WHERE
role_id           = 3
AND u1.usuario_id =";

$recuperaDptoXAutorizadorArray = "SELECT DISTINCT
d1.departamento_id AS DEPARTAMENTO_ID
FROM
usuario u1
INNER JOIN
           usuariodepartamento ud1
           ON
                      ud1.usuario_id = u1.usuario_id
INNER JOIN
           departamento d1
           ON
                      ud1.departamento_id = d1.departamento_id
WHERE
role_id           = 3
AND u1.usuario_id = ?";

$recuperaRole = "SELECT
role_id
, role_desc
FROM
role";

$recuperaCorreoSolicitud = "SELECT
email_solicitante
FROM
solicitud
WHERE
solicitud_id = ?";

$recuperaAnio = "SELECT DISTINCT
(YEAR(fecha_alta)) AS fecha_alta
FROM
solicitud";

$recuperaAnioMes = "SELECT
YEAR( fecha_alta )  AS anio_alta
, MONTH( fecha_alta ) AS mes_alta
FROM
solicitud
GROUP BY
anio_alta
, mes_alta
ORDER BY
anio_alta desc
, mes_alta desc";

$recuperaAnioMesCierre = "SELECT
YEAR( fecha_alta )  AS anio_alta
, MONTH( fecha_alta ) AS mes_alta
FROM
solicitud
WHERE
status_id in (2, 4, 5)
GROUP BY
anio_alta
, mes_alta
ORDER BY
anio_alta desc
, mes_alta desc";

$recuperaInformeDetalleValida = "SELECT
s1.solicitud_id                  AS solicitudId
, sd1.treintabarra                 AS esb
, de1.ceco                         AS codigo
, de1.departamentos_desc           AS departamento
, sd1.subdepartamento_desc         AS subdepartamento
, s1.nombre_solicitante            AS nombre
, s1.apellidos_solicitante         AS apellidos
, trim(s1.descripcion_solicitante) AS descripcion
, s1.fecha_cierre                  AS fecha
, t1.precioEncuadernacion          AS encuadernacion
, t1.precioByN                     AS byn
, t1.precioColor                   AS color
, t1.PrecioVarios                  AS varios
, '0'                              AS BYN_MAQUINA
, '0'                              AS COLOR_MAQUINA
, '0'                              AS BYN_IMPRESORA
, '0'                              AS COLOR_IMPRESORA
FROM
solicitud s1
INNER JOIN
           trabajo t1
           ON
                      s1.solicitud_id = t1.solicitud_id
INNER JOIN
           departamento de1
           ON
                      s1.departamento_id = de1.departamento_id
INNER JOIN
           subdepartamento sd1
           ON
                      s1.departamento_id        = sd1.departamento_id
                      AND s1.subdepartamento_id = sd1.subdepartamento_id
WHERE
s1.status_id = 6
AND s1.departamento_id in
(
       SELECT
              ud1.departamento_id
       FROM
              usuariodepartamento ud1
       WHERE
              ud1.usuario_id =";

$consultaImpresoras = "SELECT
IMPRESORA_ID
, MODELO
, EDIFICIO
, UBICACION
, FECHA
, SERIE
, NUMERO
FROM
impresoras
ORDER BY
UBICACION";

$consultaImpresorasPorId = "SELECT
IMPRESORA_ID
, MODELO
, EDIFICIO
, UBICACION
, FECHA
, SERIE
, NUMERO
FROM
impresoras
WHERE
IMPRESORA_ID = ?";

$recuperaMaxSubDptoQuery = "SELECT
MAX(subdepartamento_id)+1
FROM
subdepartamento
WHERE
departamento_id = ?";

$recuperaSubdptoXDpto = "SELECT
departamento_id
, subdepartamento_id
, subdepartamento_desc
, treintabarra
FROM
subdepartamento
WHERE
departamento_id   = ?
AND markfordelete = 0
ORDER BY
treintabarra";

$recuperaIdSubdptoXDpto = "SELECT
subdepartamento_id
FROM
subdepartamento
WHERE
departamento_id   = ?
AND markfordelete = 0";

$recuperaGastosCierre = "SELECT
departamento_id
, periodo
, byn_precio
, byn_total
, color_unidades
, color_precio
, color_total
, byn_unidades
FROM
gastos_impresora
WHERE
YEAR(periodo)      = ?
AND MONTH(periodo) = ?";

$recuperaUsuariosConsulta = "SELECT
usuario_id
, logon
, nombre
, apellido
, role_id
FROM
usuario
WHERE
nombre       LIKE ?
AND apellido LIKE ?
AND logon    LIKE ?
AND role_id     =?";

$generaInformeGlobalMesAdmin = "SELECT 
                                    d1.departamento_id, d1.departamentos_desc, 
                                    round(i1.byn_total+i1.color_total,2) AS totalImpresoras,
                                    round(m1.byn_total+m1.color_total,2) AS totalMaquinas,
                                    round(sum(t1.precioByN),2) AS byn, 
                                    round(sum(t1.precioColor),2) AS color, 
                                    round(sum(t1.precioEncuadernacion),2) AS encuadernacion, 
                                    round(sum(t1.PrecioVarios),2) AS varios 
                                FROM
                                    departamento d1
                                        LEFT OUTER JOIN 
                                            gastos_impresora i1 ON 
                                                i1.departamento_id = d1.departamento_id AND 
                                                month(i1.periodo) = ? AND 
                                                YEAR(i1.periodo) = ?
                                        LEFT OUTER JOIN 
                                            gastos_maquina m1 ON 
                                                m1.departamento_id=d1.departamento_id AND 
                                                month(m1.periodo) = ? AND 
                                                YEAR(m1.periodo) = ?
                                        LEFT OUTER JOIN 
                                            trabajo t1 ON 
                                                t1.departamento_id = d1.departamento_id
                                                AND t1.solicitud_id in (
                                                    SELECT 
                                                        solicitud_id 
                                                    FROM
                                                        solicitud 
                                                    WHERE 
                                                        status_id = 6 AND 
                                                        month(fecha_cierre) = ? AND 
                                                        YEAR(fecha_cierre) = ?)
                                WHERE d1.departamento_id LIKE ?
                                GROUP BY d1.departamento_id";

$generaInformeGlobalMesGestor = "SELECT 
                                    d1.departamento_id, d1.departamentos_desc, 
                                    round(i1.byn_total+i1.color_total,4) AS totalImpresoras,
                                    round(m1.byn_total+m1.color_total,4) AS totalMaquinas,
                                    round(sum(t1.precioByN),4) AS byn, 
                                    round(sum(t1.precioColor),4) AS color, 
                                    round(sum(t1.precioEncuadernacion),4) AS encuadernacion, 
                                    round(sum(t1.PrecioVarios),4) AS varios,
                                    d1.ceco AS ceco
                                FROM
                                    departamento d1
                                    INNER JOIN 
                                            trabajo t1 ON 
                                                t1.departamento_id = d1.departamento_id
                                                AND t1.solicitud_id in (
                                                    SELECT 
                                                        solicitud_id 
                                                    FROM
                                                        solicitud 
                                                    WHERE 
                                                        status_id = 6 AND 
                                                        month(fecha_cierre) = ? AND 
                                                        YEAR(fecha_cierre) = ?)
                                        LEFT OUTER JOIN 
                                            gastos_impresora i1 ON 
                                                i1.departamento_id = d1.departamento_id AND 
                                                month(i1.periodo) = ? AND 
                                                YEAR(i1.periodo) = ?
                                        LEFT OUTER JOIN 
                                            gastos_maquina m1 ON 
                                                m1.departamento_id=d1.departamento_id AND 
                                                month(m1.periodo) = ? AND 
                                                YEAR(m1.periodo) = ?
                                WHERE d1.departamento_id LIKE ?
                                GROUP BY d1.departamento_id";
                                
$generaInformeGlobalAdmin = "SELECT 
    d1.departamento_id, d1.departamentos_desc, 
    0 AS totalImpresoras,
    0 AS totalMaquinas,
    round(sum(t1.precioByN),4) AS byn, 
    round(sum(t1.precioColor),4) AS color, 
    round(sum(t1.precioEncuadernacion),4) AS encuadernacion, 
    round(sum(t1.PrecioVarios),4) AS varios,
    d1.ceco AS ceco
FROM
    departamento d1
    INNER JOIN 
            trabajo t1 ON 
                t1.departamento_id = d1.departamento_id
                AND t1.solicitud_id in (
                    SELECT 
                        solicitud_id 
                    FROM
                        solicitud 
                    WHERE 
                        status_id = 6 AND 
                        month(fecha_cierre) = ? AND 
                        YEAR(fecha_cierre) = ?)
WHERE d1.departamento_id LIKE ?
GROUP BY d1.departamento_id
UNION
    SELECT d1.departamento_id, d1.departamentos_desc, 
            round(i1.byn_total+i1.color_total,4) AS totalImpresoras,
            0 AS totalMaquinas,
            0 AS byn, 
            0 AS color, 
            0 AS encuadernacion, 
            0 AS varios,
            d1.ceco AS ceco
        FROM departamento d1 INNER JOIN      
            gastos_impresora i1 ON 
                i1.departamento_id = d1.departamento_id AND 
                month(i1.periodo) = ? AND 
                YEAR(i1.periodo) = ?
        WHERE d1.departamento_id LIKE ?
        GROUP BY d1.departamento_id
UNION 
    SELECT d1.departamento_id, d1.departamentos_desc, 
            0 AS totalImpresoras,
            round(m1.byn_total+m1.color_total,4) AS totalMaquinas,
            0 AS byn, 
            0 AS color, 
            0 AS encuadernacion, 
            0 AS varios,
            d1.ceco AS ceco
        FROM departamento d1 INNER JOIN    
            gastos_maquina m1 ON 
                m1.departamento_id=d1.departamento_id AND 
                month(m1.periodo) = ? AND 
                YEAR(m1.periodo) = ?
        WHERE d1.departamento_id LIKE ?
        GROUP BY d1.departamento_id";


$generaInformeGlobalAdminUnion = "SELECT 
d1.departamento_id, 
d1.departamentos_desc, 
round(i1.byn_total+i1.color_total,4) as gImpresora ,
round(im.byn_total+im.color_total,4) as gMaquina,
round(sum(t1.precioByN),4) AS byn, 
round(sum(t1.precioColor),4) AS color, 
round(sum(t1.precioEncuadernacion),4) AS encuadernacion, 
round(sum(t1.PrecioVarios),4) AS varios,
d1.ceco AS ceco
FROM
departamento d1
INNER JOIN 
        trabajo t1 ON 
            t1.departamento_id = d1.departamento_id
            AND t1.solicitud_id in (
                SELECT 
                    solicitud_id 
                FROM
                    solicitud 
                WHERE 
                    status_id = 6 AND 
                    month(fecha_cierre) =  ? AND 
                    YEAR(fecha_cierre) =  ?)
LEFT OUTER JOIN 
        gastos_impresora i1 ON 
            i1.departamento_id = d1.departamento_id AND 
            month(i1.periodo) = ? AND 
            YEAR(i1.periodo) = ?
LEFT OUTER JOIN 
        gastos_maquina im ON 
            im.departamento_id = d1.departamento_id AND 
            month(im.periodo) = ? AND 
            YEAR(im.periodo) =  ?
WHERE d1.departamento_id LIKE ?
GROUP BY d1.departamento_id";


$generaInformeGlobalAdminPartes = "SELECT 
                                    d1.departamento_id, d1.departamentos_desc, 
                                    round(sum(t1.precioByN),4) AS byn, 
                                    round(sum(t1.precioColor),4) AS color, 
                                    round(sum(t1.precioEncuadernacion),4) AS encuadernacion, 
                                    round(sum(t1.PrecioVarios),4) AS varios,
                                    d1.ceco AS ceco
                                FROM
                                    departamento d1
                                INNER JOIN 
                                    trabajo t1 ON 
                                        t1.departamento_id = d1.departamento_id
                                        AND t1.solicitud_id in (
                                            SELECT 
                                                solicitud_id 
                                            FROM
                                                solicitud 
                                            WHERE 
                                                status_id = 6 AND 
                                                month(fecha_cierre) = ? AND 
                                                YEAR(fecha_cierre) = ?)
                                WHERE d1.departamento_id LIKE ?
                                GROUP BY d1.departamento_id";

$generaInformeGlobalMaquinas = "    SELECT 
                                    i1.departamento_id, 
                                    round(i1.byn_total+i1.color_total,4) AS totalImpresoras
                                FROM 
                                    gastos_maquina i1 
                                WHERE month(i1.periodo) = ? AND 
                                    YEAR(i1.periodo) = ? AND 
                                    i1.departamento_id LIKE ?
                                GROUP BY i1.departamento_id";

$generaInformeGlobalImpresoras = "    SELECT 
                                        i1.departamento_id, 
                                        round(i1.byn_total+i1.color_total,4) AS totalImpresoras
                                    FROM 
                                        gastos_impresora i1 
                                    WHERE month(i1.periodo) = ? AND 
                                        YEAR(i1.periodo) = ? AND 
                                        i1.departamento_id LIKE ?
                                    GROUP BY i1.departamento_id";

$generaInformeGlobalFacturacion= "SELECT 
                                    d1.departamento_id, d1.departamentos_desc, 
                                    0 AS totalImpresoras,
                                    0 AS totalMaquinas,
                                    round(sum(t1.precioByN),4) AS byn, 
                                    round(sum(t1.precioColor),4) AS color, 
                                    round(sum(t1.precioEncuadernacion),4) AS encuadernacion, 
                                    round(sum(t1.PrecioVarios),4) AS varios,
                                    d1.ceco AS ceco
                                    FROM
                                    departamento d1
                                    INNER JOIN 
                                            trabajo t1 ON 
                                                t1.departamento_id = d1.departamento_id
                                                AND t1.solicitud_id in (
                                                    SELECT 
                                                        solicitud_id 
                                                    FROM
                                                        solicitud 
                                                    WHERE 
                                                        status_id = 6 AND 
                                                        month(fecha_cierre) = ? AND 
                                                        YEAR(fecha_cierre) = ?)
                                    WHERE d1.departamento_id LIKE ?
                                    GROUP BY d1.departamento_id";

$recuperaDptoXAutorizadorJSON = "SELECT DISTINCT
(d1.departamento_id)  AS DEPARTAMENTO_ID
, d1.departamentos_desc AS DEPARTAMENTOS_DESC
, d1.ceco               AS CECO
FROM
usuario u1
INNER JOIN
           usuariodepartamento ud1
           ON
                      ud1.usuario_id = u1.usuario_id
INNER JOIN
           departamento d1
           ON
                      ud1.departamento_id = d1.departamento_id
WHERE
role_id              = 3
AND d1.markfordelete = 0
AND u1.usuario_id    = ?";

$recuperaValidadorGlobalTodosDpto = "SELECT
        sd1.treintabarra AS esb,
        de1.ceco AS codigo,
        de1.departamentos_desc AS departamento,
        sd1.subdepartamento_desc AS subdepartamento,
        td.preciototal AS PRECIO
    FROM
        solicitud s1 
        INNER JOIN trabajo t1 ON s1.solicitud_id = t1.solicitud_id 
        INNER JOIN departamento de1 ON s1.departamento_id = de1.departamento_id AND de1.markfordelete=0 
        INNER JOIN subdepartamento sd1 ON s1.departamento_id = sd1.departamento_id AND 
                    s1.subdepartamento_id = sd1.subdepartamento_id AND sd1.markfordelete=0
        INNER JOIN trabajodetalle td ON td.solicitud_id = s1.solicitud_id 
    WHERE
        s1.status_id = 6 AND 
        s1.departamento_id IN(    SELECT ud1.departamento_id FROM usuariodepartamento ud1 WHERE ud1.usuario_id = ?) AND 
        MONTH(s1.fecha_validacion) = ? AND 
        YEAR(s1.fecha_validacion) = ?";

$recuperaValidadorGlobalPorDpto = "SELECT
        sd1.treintabarra AS esb,
        de1.ceco AS codigo,
        de1.departamentos_desc AS departamento,
        sd1.subdepartamento_desc AS subdepartamento,
        td.preciototal AS PRECIO
    FROM
        solicitud s1 
        INNER JOIN trabajo t1 ON s1.solicitud_id = t1.solicitud_id 
        INNER JOIN departamento de1 ON s1.departamento_id = de1.departamento_id AND de1.markfordelete=0 
        INNER JOIN subdepartamento sd1 ON s1.departamento_id = sd1.departamento_id AND 
                    s1.subdepartamento_id = sd1.subdepartamento_id AND sd1.markfordelete=0
        INNER JOIN trabajodetalle td ON td.solicitud_id = s1.solicitud_id 
    WHERE
        s1.status_id = 6 AND 
        s1.departamento_id IN(?) AND 
        MONTH(s1.fecha_validacion) = ? AND 
        YEAR(s1.fecha_validacion) = ?";
    //GROUP BY de1.ceco";

$recuperaTodosUsuarios = "SELECT DISTINCT
CONCAT(usuario.nombre, ' ', usuario.apellido) AS nombre
, role.role_desc                                           AS rol
, departamento.departamentos_desc                          AS nombreDepartamento
FROM
usuario
INNER JOIN
role
ON
           role.role_id = usuario.role_id
INNER JOIN
usuariodepartamento
ON
           usuariodepartamento.usuario_id = usuario.usuario_id
INNER JOIN
departamento
ON
           departamento.departamento_id  = usuariodepartamento.departamento_id
           AND departamento.markfordelete=0
ORDER BY
nombre
, nombreDepartamento ASC";

$recuperaSubdepartamento = "SELECT
d1.subdepartamento_desc
, d1.treintabarra
FROM
subdepartamento d1
INNER JOIN
           departamento s1
           ON
                      s1.departamento_id        = d1.departamento_id
                      AND s1.departamento_id    = ?
                      AND d1.subdepartamento_id = ?
                      AND d1.markfordelete      = 0";

$sentenciaLogonJSON = "SELECT
usuario_id
, logon
, password
, nombre
, apellido
, role_id
FROM
usuario
WHERE
logon        = ?
AND password = ?";

$informeDepartamentos = 'SELECT
d1.departamento_id
, d1.departamentos_desc
, d1.ceco
, s1.subdepartamento_id
, s1.subdepartamento_desc
, s1.treintabarra
FROM
departamento d1
INNER JOIN
           subdepartamento s1
           ON
                      s1.departamento_id = d1.departamento_id
WHERE
d1.markfordelete    = 0
or s1.markfordelete = 0
ORDER BY
d1.departamentos_desc
, s1.subdepartamento_desc';

$recuperaTrabajos = "SELECT
s1.solicitud_id
, s1.departamento_id
, s1.nombre_solicitante
, s1.apellidos_solicitante
, s1.autorizador_id
, s1.descripcion_solicitante
, s1.email_solicitante
, s1.status_id
, s1.fecha_alta
, s1.fecha_validacion
, s1.fecha_cierre
, d1.departamentos_desc
, s1.usuario_plantilla
FROM
solicitud s1
INNER JOIN
           departamento d1
           ON
                      d1.departamento_id   = s1.departamento_id
                      AND d1.markfordelete = 0
WHERE
s1.status_id in (2, 4, 5)";

$recuperaArticulos = "SELECT  
        r1.tipo_id AS TIPO_ID,
        r1.tipo_desc AS TIPO_DESC,
        d1.detalle_id AS detalle,
        d1.descripcion AS descripcion,
        d1.precio AS precio,
        td.unidades AS unidades,
        td.preciototal AS preciototal
    FROM 
        detalle d1 
            INNER JOIN 
                tipo r1 ON r1.tipo_id = d1.tipo_id 
            LEFT OUTER JOIN    
                trabajodetalle td ON td.tipo_id=d1.tipo_id AND td.detalle_id=d1.detalle_id AND td.solicitud_id = ?
    WHERE 
        d1.tipo_id = ?
    ORDER BY
        d1.descripcion";

$recuperaArticulosExtra = "SELECT  
        r1.tipo_id AS TIPO_ID,
        r1.tipo_desc AS TIPO_DESC,
        d1.detalle_id AS detalle,
        d1.descripcion AS descripcion,
        d1.precio AS precio,
        td.unidades AS unidades,
        td.preciototal AS preciototal
    FROM 
        detalle d1 
            INNER JOIN 
                tipo r1 ON r1.tipo_id = d1.tipo_id 
            LEFT OUTER JOIN    
                trabajodetalle td ON td.tipo_id=d1.tipo_id AND td.detalle_id=d1.detalle_id AND td.solicitud_id = ?
    WHERE 
        d1.tipo_id = 6
    union
    SELECT  
        r11.tipo_id AS TIPO_ID,
        r11.tipo_desc AS TIPO_DESC,
        d11.detalle_id AS detalle,
        d11.descripcion AS descripcion,
        d11.precio AS precio,
        td1.unidades AS unidades,
        td1.preciototal AS preciototal
    FROM 
        detalle d11 
            INNER JOIN 
                tipo r11 ON r11.tipo_id = d11.tipo_id 
            INNER JOIN    
                trabajodetalle td1 ON td1.tipo_id=d11.tipo_id AND td1.detalle_id=d11.detalle_id AND td1.solicitud_id = ?
    WHERE 
    d11.tipo_id = 7
    ";

$recuperaNavSolicitud = "SELECT 
        s1.nombre_solicitante, s1.apellidos_solicitante, 
        s1.fecha_validacion, d1.departamentos_desc, 
        sd1.subdepartamento_desc, d1.ceco,
        sd1.treintabarra, d1.departamento_id, sd1.subdepartamento_id 
    FROM 
        solicitud s1 INNER JOIN  departamento d1 ON d1.departamento_id = s1.departamento_id 
            INNER JOIN  subdepartamento sd1 ON sd1.departamento_id = s1.departamento_id AND sd1.subdepartamento_id = s1.subdepartamento_id WHERE solicitud_id = ?";

$recuperaLinea = "SELECT
                        trabajo_id
                    FROM
                        trabajodetalle
                    WHERE
                        solicitud_id = ? AND
                        tipo_id= ? AND
                        detalle_id= ?";

$recuperaTrabajo = "SELECT
                        trabajo_id
                    FROM
                        trabajo
                    WHERE
                        solicitud_id = ?";

$recuperaSubtotalesTrabajo = "SELECT
                                tipo_id,
                                preciototal
                            FROM
                                trabajodetalle
                            WHERE
                                solicitud_id = ?";

$recuperaPasswordMaestra = "SELECT
                                password
                            FROM
                                administracion";

$recuperaGastosImpresora = "SELECT
                                d1.departamentos_desc,
                                d1.departamento_id,
                                gi1.periodo,
                                gi1.byn_unidades,
                                gi1.byn_precio,
                                gi1.byn_total,
                                gi1.color_unidades,
                                gi1.color_precio,
                                gi1.color_total
                            FROM
                                departamento d1
                            LEFT OUTER JOIN  
                                gastos_impresora gi1 ON
                                    gi1.departamento_id = d1.departamento_id
                                    and month(gi1.periodo) = ?
                                    and year(gi1.periodo) = ?";

$recuperaGastosImpresoraToday = "SELECT
                                d1.departamentos_desc,
                                d1.departamento_id,
                                gi1.periodo,
                                gi1.byn_unidades,
                                (SELECT PRECIO FROM DETALLE WHERE TIPO_ID=5 AND DETALLE_ID=1) AS byn_precio,
                                gi1.byn_total,
                                gi1.color_unidades,
                                (SELECT PRECIO FROM DETALLE WHERE TIPO_ID=4 AND DETALLE_ID=1) AS COLOR_precio,
                                gi1.color_total
                            FROM
                                departamento d1
                            LEFT OUTER JOIN  
                                gastos_impresora gi1 ON
                                    gi1.departamento_id = d1.departamento_id
                                    and month(gi1.periodo) = ?
                                    and year(gi1.periodo) = ?";

$recuperaGastosMaquinaToday = "SELECT
                                    d1.departamentos_desc,
                                    d1.departamento_id,
                                    gi1.periodo,
                                    gi1.byn_unidades,
                                    (SELECT PRECIO FROM DETALLE WHERE TIPO_ID=5 AND DETALLE_ID=1) AS byn_precio,
                                    gi1.byn_total,
                                    gi1.color_unidades,
                                    (SELECT PRECIO FROM DETALLE WHERE TIPO_ID=4 AND DETALLE_ID=1) AS COLOR_precio,
                                    gi1.color_total
                                FROM
                                    departamento d1
                                LEFT OUTER JOIN  
                                    gastos_maquina gi1 ON
                                        gi1.departamento_id = d1.departamento_id
                                            and month(gi1.periodo) = ?
                                            and year(gi1.periodo) = ?";

$recuperaGastosMaquina = "SELECT d1.departamentos_desc, d1.departamento_id, gi1.periodo, gi1.byn_unidades, gi1.byn_precio, gi1.byn_total, gi1.color_unidades, gi1.color_precio, gi1.color_total
                            FROM departamento d1 LEFT OUTER JOIN gastos_maquina gi1 ON gi1.departamento_id = d1.departamento_id and month(gi1.periodo) = ? and year(gi1.periodo) = ?";

$existeGastoMaquina = "SELECT * FROM gastos_maquina WHERE departamento_id    =? and YEAR(periodo)  = ? and MONTH(periodo) = ?";
$existeGastoImpresora = "SELECT * FROM gastos_impresora WHERE departamento_id    =? and YEAR(periodo)  = ? and MONTH(periodo) = ?";
$sentenciaDepartamentoJSON = "SELECT d1.departamentos_desc, d1.ceco FROM departamento d1 INNER JOIN solicitud s1 on s1.departamento_id   = d1.departamento_id and s1.solicitud_id  = ? and d1.markfordelete = 0";
$sentenciaSubDepartamentoJSON = "SELECT d1.subdepartamento_desc, d1.treintabarra FROM subdepartamento d1 INNER JOIN solicitud s1 on s1.subdepartamento_id = d1.subdepartamento_id and s1.solicitud_id   = ? and d1.markfordelete  = 0";
$variosUnoQuery = "SELECT d1.tipo_id as tipo, d1.detalle_id as detalle, d1.descripcion as descripcion, d1.precio as precio, td.unidades as unidades,td.preciototal as preciototal FROM detalle d1 LEFT OUTER JOIN trabajodetalle td on td.tipo_id = d1.tipo_id and td.detalle_id =d1.detalle_id and td.solicitud_id = ? WHERE d1.tipo_id = 3";
$colorQuery = "SELECT d1.tipo_id as tipo, d1.detalle_id as detalle, d1.descripcion as descripcion, d1.precio as precio, td.unidades as unidades, td.preciototal as preciototal FROM detalle d1 LEFT OUTER JOIN trabajodetalle td on td.tipo_id =d1.tipo_id and td.detalle_id =d1.detalle_id and td.solicitud_id = ? WHERE d1.tipo_id = 4";
$encuadernacionQuery = "SELECT d1.tipo_id as tipo, d1.detalle_id as detalle, d1.descripcion as descripcion, d1.precio as precio, td.unidades as unidades, td.preciototal as preciototal FROM detalle d1 LEFT OUTER JOIN trabajodetalle td on td.tipo_id =d1.tipo_id and td.detalle_id =d1.detalle_id and td.solicitud_id = ? WHERE d1.tipo_id = 1";
$encoladoQuery = "SELECT d1.tipo_id as tipo, d1.detalle_id as detalle, d1.descripcion as descripcion, d1.precio as precio, td.unidades as unidades, td.preciototal as preciototal FROM detalle d1 LEFT OUTER JOIN trabajodetalle td on td.tipo_id =d1.tipo_id and td.detalle_id =d1.detalle_id and td.solicitud_id = ? WHERE d1.tipo_id = 2";
$blancoYNegroQuery = "SELECT d1.tipo_id as tipo, d1.detalle_id as detalle, d1.descripcion as descripcion, d1.precio as precio, td.unidades as unidades, td.preciototal as preciototal FROM detalle d1 LEFT OUTER JOIN trabajodetalle td on td.tipo_id =d1.tipo_id and td.detalle_id =d1.detalle_id and td.solicitud_id = ? WHERE d1.tipo_id = 5";
$varios2QueryTabla = "SELECT d1.tipo_id as tipo, d1.detalle_id as detalle, d1.descripcion as descripcion, d1.precio as precio, td.unidades as unidades, td.preciototal as preciototal FROM detalle d1 INNER JOIN trabajodetalle td on td.tipo_id =d1.tipo_id and td.detalle_id=d1.detalle_id WHERE d1.tipo_id = 6 and td.solicitud_id = ?";
$varios2ExtraQuery = "SELECT d1.tipo_id as tipo, d1.detalle_id as detalle, d1.descripcion as descripcion, d1.precio as precio, td.unidades as unidades, td.preciototal as preciototal FROM detalle d1 INNER JOIN trabajodetalle td on td.tipo_id =d1.tipo_id and td.detalle_id =d1.detalle_id and td.solicitud_id = ? WHERE d1.tipo_id = 7";
$varios2Query = "SELECT d1.tipo_id as tipo,d1.detalle_id as detalle,d1.descripcion as descripcion,d1.precio as precio,td.unidades as unidades,td.preciototal as preciototal from  detalle d1  left OUTER join trabajodetalle td on td.tipo_id=d1.tipo_id  and td.detalle_id=d1.detalle_id and td.solicitud_id = ?  where d1.tipo_id = 6";


$cabeceraDatosQuery = "SELECT s1.nombre_solicitante, s1.apellidos_solicitante, s1.fecha_cierre, d1.departamentos_desc, sd1.subdepartamento_desc, d1.ceco, sd1.treintabarra, d1.departamento_id, sd1.subdepartamento_id, s1.descripcion_solicitante FROM solicitud s1 INNER JOIN  departamento d1 ON d1.departamento_id = s1.departamento_id INNER JOIN  subdepartamento sd1 ON sd1.departamento_id = s1.departamento_id AND sd1.subdepartamento_id = s1.subdepartamento_id WHERE solicitud_id = ?";
$variosUnoDatosQuery = "SELECT DISTINCT d1.tipo_id as tipo, d1.detalle_id as detalle, d1.descripcion as descripcion, d1.precio as precio, td.unidades as unidades, td.preciototal as preciototal FROM trabajodetalle td INNER JOIN detalle d1 on td.tipo_id = d1.tipo_id and td.detalle_id =d1.detalle_id WHERE td.solicitud_id = ? and d1.tipo_id = 3 and year(td.fecha_cierre) = ? and month(td.fecha_cierre) = ?";
$colorDatosQuery = "SELECT DISTINCT d1.tipo_id as tipo, d1.detalle_id as detalle, d1.descripcion as descripcion, d1.precio as precio, td.unidades as unidades, td.preciototal as preciototal FROM trabajodetalle td INNER JOIN detalle d1 on td.tipo_id =d1.tipo_id and td.detalle_id =d1.detalle_id WHERE td.solicitud_id = ? and d1.tipo_id = 4 and year(td.fecha_cierre) = ? and month(td.fecha_cierre) = ?";
$encuadernacionDatosQuery = "SELECT DISTINCT d1.tipo_id as tipo, d1.detalle_id as detalle, d1.descripcion as descripcion, d1.precio as precio, td.unidades as unidades, td.preciototal as preciototal FROM trabajodetalle td INNER JOIN detalle d1 on td.tipo_id = d1.tipo_id and td.detalle_id = d1.detalle_id WHERE d1.tipo_id = 1 and td.solicitud_id = ? and year(td.fecha_cierre) = ? and month(td.fecha_cierre) = ?";
$encoladoDatosQuery = "SELECT DISTINCT d1.tipo_id as tipo, d1.detalle_id as detalle, d1.descripcion as descripcion, d1.precio as precio, td.unidades as unidades, td.preciototal as preciototal FROM trabajodetalle td INNER JOIN detalle d1 on td.tipo_id = d1.tipo_id and td.detalle_id = d1.detalle_id WHERE d1.tipo_id = 2 and td.solicitud_id = ? and year(td.fecha_cierre) = ? and month(td.fecha_cierre) = ?";
$blancoYNegroDatosQuery = "SELECT DISTINCT d1.tipo_id as tipo, d1.detalle_id as detalle, d1.descripcion as descripcion, d1.precio as precio, td.unidades as unidades, td.preciototal as preciototal FROM trabajodetalle td INNER JOIN detalle d1 on td.tipo_id = d1.tipo_id and td.detalle_id = d1.detalle_id WHERE d1.tipo_id = 5 and td.solicitud_id = ? and year(td.fecha_cierre) = ? and month(td.fecha_cierre) = ?";
$varios2DatosQuery = "SELECT DISTINCT d1.tipo_id as tipo, d1.detalle_id as detalle, d1.descripcion as descripcion, d1.precio as precio, td.unidades as unidades, td.preciototal as preciototal FROM trabajodetalle td INNER JOIN detalle d1 on td.tipo_id =d1.tipo_id and td.detalle_id =d1.detalle_id WHERE d1.tipo_id = 6 and td.solicitud_id = ? and year(td.fecha_cierre) = ? and month(td.fecha_cierre) = ?";
$varios2DatosExtraQuery = "SELECT DISTINCT d1.tipo_id as tipo, d1.detalle_id as detalle, d1.descripcion as descripcion, d1.precio as precio, td.unidades as unidades, td.preciototal as preciototal FROM trabajodetalle td INNER JOIN detalle d1 on td.tipo_id =d1.tipo_id and td.detalle_id =d1.detalle_id WHERE d1.tipo_id = 7 and td.solicitud_id = ? and year(td.fecha_cierre) = ? and month(td.fecha_cierre) = ?";

$consultaTrabajoJSON = "SELECT solicitud_id FROM trabajo WHERE solicitud_id = ?";
$sentenciaSolicitanteJSON = "SELECT nombre_solicitante as nombre, apellidos_solicitante as apellido FROM solicitud WHERE solicitud_id = ?";
$consultaDetalleJSON = "SELECT unidades, preciototal FROM trabajodetalle WHERE trabajo_id = ? and tipo_id = ? and detalle_id = ? and solicitud_id = ?";
$detalleVarios2PorId = "SELECT detalle_id, tipo_id, descripcion, precio FROM detalle WHERE tipo_id = 6 and detalle_id = ?";
$comprobarVarios2ExtraJSON = "SELECT 
                                detalle_id, 
                                tipo_id, 
                                descripcion, 
                                precio 
                            FROM 
                                detalle 
                            WHERE 
                                tipo_id=7 and  
                                descripcion = ? and 
                                CAST(precio AS DECIMAL) = CAST(? AS DECIMAL)";

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
                                        tipo_id=7 and 
                                        solicitud_id = ? and 
                                        detalle_id = ?";

$comprobarVarios2TrabajoExtra = "SELECT 
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
                                    tipo_id=7 and 
                                    solicitud_id = ?";

$recuperaGastosImpresoraCierre = "SELECT 
                            departamento.departamento_id as departamento_id, 
                            gastos_impresora.periodo as periodo,
                            gastos_impresora.byn_unidades as byn_unidades,
                            (SELECT precio FROM detalle WHERE tipo_id = 5 and detalle_id = 1 ) as byn_precio,
                            gastos_impresora.byn_total as byn_total,
                            gastos_impresora.color_unidades as color_unidades,
                            (SELECT precio FROM detalle WHERE tipo_id = 4 and detalle_id = 1 ) as color_precio,
                            gastos_impresora.color_total as color_total
                        FROM
                            departamento LEFT OUTER JOIN
                            gastos_impresora on departamento.departamento_id = gastos_impresora.departamento_id AND
                            month(gastos_impresora.periodo) = ? AND
                            year(gastos_impresora.periodo) = ?";

$recuperaGastosMaquinaCierre = "SELECT 
                                    departamento.departamento_id as departamento_id, 
                                    departamento.ceco as ceco, 
                                    gastos_maquina.periodo as periodo,
                                    gastos_maquina.byn_unidades as byn_unidades,
                                    (SELECT precio FROM detalle WHERE tipo_id = 5 and detalle_id = 1 ) as byn_precio,
                                    gastos_maquina.byn_total as byn_total,
                                    gastos_maquina.color_unidades as color_unidades,
                                    (SELECT precio FROM detalle WHERE tipo_id = 4 and detalle_id = 1 ) as color_precio,
                                    gastos_maquina.color_total as color_total
                                FROM
                                    departamento LEFT OUTER JOIN
                                    gastos_maquina on departamento.departamento_id = gastos_maquina.departamento_id AND
                                    month(gastos_maquina.periodo) = ? AND
                                    year(gastos_maquina.periodo) = ?";
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
                                INNER JOIN usuario on solicitud.autorizador_id = usuario.usuario_id
                                INNER JOIN status on solicitud.status_id = status.status_id
                                INNER JOIN departamento on solicitud.departamento_id = departamento.departamento_id
                                INNER JOIN subdepartamento on solicitud.subdepartamento_id = subdepartamento.subdepartamento_id and 
                                    solicitud.departamento_id = subdepartamento.departamento_id
                                left JOIN trabajo on solicitud.solicitud_id = trabajo.solicitud_id
                        WHERE 
                            month(fecha_validacion) = MONTH(CURDATE()) and 
                            year(fecha_validacion)= YEAR(CURDATE()) and
                            solicitud.autorizador_id = ?";
$consultaDepartamentoId = "SELECT 
                                d1.departamento_id 
                            FROM 
                                departamento d1 
                            INNER JOIN solicitud s1 on s1.departamento_id = d1.departamento_id and 
                                s1.solicitud_id = ? and d1.markfordelete=0";




/**
 * Iformes por separado 
*/
$consultaTodasImpresionesDetalle = "SELECT
                                        td.solicitud_id as Parte,
                                        d.ceco as Ceco,
                                        d.departamentos_desc as Departamento,
                                        s2.subdepartamento_desc as Subepartamento,
                                        s2.treintabarra as Treinta,
                                        s.nombre_solicitante as Nombre,
                                        s.apellidos_solicitante as Apellidos,
                                        replace(replace(SUBSTRING(s.descripcion_solicitante, 1, 30),
                                        '\r\n',
                                        ' '),
                                        '\t',
                                        ' ') as Descripcion,
                                        td.fecha_cierre as Fecha,
                                        ROUND(SUM(case when td.tipo_id = '1' then td.preciototal else 0 end), 4) as Espiral,
                                        ROUND(SUM(case when td.tipo_id = '2' then td.preciototal else 0 end), 4) as Encolado,
                                        ROUND(SUM(case when td.tipo_id = '3' then td.preciototal else 0 end), 4) as Varios1,
                                        ROUND(SUM(case when td.tipo_id = '4' then td.preciototal else 0 end), 4) as Color,
                                        ROUND(SUM(case when td.tipo_id = '5' then td.preciototal else 0 end), 4) as BlancoNegro,
                                        ROUND(SUM(case when td.tipo_id in ('6', '7') then td.preciototal else 0 end), 4) as Varios1
                                    FROM
                                        trabajodetalle td
                                        inner join solicitud s on
                                        s.solicitud_id = td.solicitud_id
                                        inner join departamento d on
                                        d.departamento_id = s.departamento_id
                                        inner join subdepartamento s2 on
                                        s2.departamento_id = s.departamento_id
                                        and s2.subdepartamento_id = s.subdepartamento_id
                                    WHERE
                                        year(td.fecha_cierre) = ?
                                        and month(td.fecha_cierre)= ?
                                    GROUP BY
                                        td.solicitud_id
                                    UNION 
                                    SELECT
                                        'treintabarraMaq' as Parte,
                                        'cecoMaquinas' as Ceco,
                                        concat('Maquinas ', d.departamentos_desc) as Departamento,
                                        'Maquinas' as Subepartamento,
                                        'Maquinas' as Treinta,
                                        'Maquinas' as Nombre,
                                        'Maquinas' as Apellidos,
                                        'Maquinas' as Descripcion,
                                        m1.periodo as Fecha,
                                        0,
                                        0,
                                        0,
                                        round(m1.color_total, 4),
                                        round(m1.byn_total, 4),
                                        0
                                    FROM
                                        gastos_maquina m1
                                        inner join departamento d on
                                        d.departamento_id = m1.departamento_id
                                    WHERE
                                        year(m1.periodo) = ?
                                        and month(m1.periodo) = ?
                                    UNION 
                                    SELECT
                                        'treintabarraImp' as Parte,
                                        'cecoImpresoras' as Ceco,
                                        concat('Impresoras ', d.departamentos_desc) as Departamento,
                                        'Impresoras' as Subepartamento,
                                        'Impresoras' as Treinta,
                                        'Impresoras' as Nombre,
                                        'Impresoras' as Apellidos,
                                        'Impresoras' as Descripcion,
                                        m1.periodo as Fecha,
                                        0,
                                        0,
                                        0,
                                        round(m1.color_total, 4),
                                        round(m1.byn_total, 4),
                                        0
                                    FROM
                                        gastos_impresora m1
                                        inner join departamento d on
                                        d.departamento_id = m1.departamento_id
                                    WHERE
                                        year(m1.periodo) = ?
                                        and month(m1.periodo) = ?";

$consultaTodasImpresionesDetalleDpto = "SELECT
                                            td.solicitud_id as Parte,
                                            d.ceco as Ceco,
                                            d.departamentos_desc as Departamento,
                                            s2.subdepartamento_desc as Subdepartamento,
                                            s2.treintabarra as Treinta,
                                            s.nombre_solicitante as Nombre,
                                            s.apellidos_solicitante as Apellidos,
                                            replace(replace(SUBSTRING(s.descripcion_solicitante, 1, 30),
                                            '\r\n',
                                            ' '),
                                            '\t',
                                            ' ') as Descripcion,
                                            td.fecha_cierre as Fecha,
                                            ROUND(SUM(case when td.tipo_id = '1' then td.preciototal else 0 end), 4) as Espiral,
                                            ROUND(SUM(case when td.tipo_id = '2' then td.preciototal else 0 end), 4) as Encolado,
                                            ROUND(SUM(case when td.tipo_id = '3' then td.preciototal else 0 end), 4) as Varios1,
                                            ROUND(SUM(case when td.tipo_id = '4' then td.preciototal else 0 end), 4) as Color,
                                            ROUND(SUM(case when td.tipo_id = '5' then td.preciototal else 0 end), 4) as BlancoNegro,
                                            ROUND(SUM(case when td.tipo_id in ('6', '7') then td.preciototal else 0 end), 4) as Varios1
                                        FROM
                                            trabajodetalle td
                                            inner join solicitud s on
                                            s.solicitud_id = td.solicitud_id
                                            inner join departamento d on
                                            d.departamento_id = s.departamento_id
                                            inner join subdepartamento s2 on
                                            s2.departamento_id = s.departamento_id
                                            and s2.subdepartamento_id = s.subdepartamento_id
                                        WHERE
                                            year(td.fecha_cierre) = ?
                                            and month(td.fecha_cierre)= ?
                                            and d.departamento_id = ?
                                        GROUP BY
                                            td.solicitud_id
                                        UNION 
                                        SELECT
                                            'treintabarraMaq' as Parte,
                                            'cecoMaquinas' as Ceco,
                                            concat('Maquinas ', d.departamentos_desc) as Departamento,
                                            'Maquinas' as Subepartamento,
                                            'Maquinas' as Treinta,
                                            'Maquinas' as Nombre,
                                            'Maquinas' as Apellidos,
                                            'Maquinas' as Descripcion,
                                            m1.periodo as Fecha,
                                            0,
                                            0,
                                            0,
                                            round(m1.color_total, 4),
                                            round(m1.byn_total, 4),
                                            0
                                        FROM
                                            gastos_maquina m1
                                            inner join departamento d on
                                            d.departamento_id = m1.departamento_id
                                        WHERE
                                            year(m1.periodo) = ?
                                            and month(m1.periodo) = ?
                                            and m1.departamento_id = ?
                                        UNION 
                                        SELECT
                                            'treintabarraImp' as Parte,
                                            'cecoImpresoras' as Ceco,
                                            concat('Impresoras ', d.departamentos_desc) as Departamento,
                                            'Impresoras' as Subepartamento,
                                            'Impresoras' as Treinta,
                                            'Impresoras' as Nombre,
                                            'Impresoras' as Apellidos,
                                            'Impresoras' as Descripcion,
                                            m1.periodo as Fecha,
                                            0,
                                            0,
                                            0,
                                            round(m1.color_total, 4),
                                            round(m1.byn_total, 4),
                                            0
                                        FROM
                                            gastos_impresora m1
                                            inner join departamento d on
                                            d.departamento_id = m1.departamento_id
                                        WHERE
                                            year(m1.periodo) = ?
                                            and month(m1.periodo) = ?
                                            and m1.departamento_id = ?";


$consultaTodasImpresionesDetalleDptoSubdpto = "SELECT
                                                    td.solicitud_id as Parte,
                                                    d.ceco as Ceco,
                                                    d.departamentos_desc as Departamento,
                                                    s2.subdepartamento_desc as Subdepartamento,
                                                    s2.treintabarra as Treinta,
                                                    s.nombre_solicitante as Nombre,
                                                    s.apellidos_solicitante as Apellidos,
                                                    replace(replace(SUBSTRING(s.descripcion_solicitante, 1, 30),
                                                    '\r\n',
                                                    ' '),
                                                    '\t',
                                                    ' ') as Descripcion,
                                                    td.fecha_cierre as Fecha,
                                                    ROUND(SUM(case when td.tipo_id = '1' then td.preciototal else 0 end), 4) as Espiral,
                                                    ROUND(SUM(case when td.tipo_id = '2' then td.preciototal else 0 end), 4) as Encolado,
                                                    ROUND(SUM(case when td.tipo_id = '3' then td.preciototal else 0 end), 4) as Varios1,
                                                    ROUND(SUM(case when td.tipo_id = '4' then td.preciototal else 0 end), 4) as Color,
                                                    ROUND(SUM(case when td.tipo_id = '5' then td.preciototal else 0 end), 4) as BlancoNegro,
                                                    ROUND(SUM(case when td.tipo_id in ('6', '7') then td.preciototal else 0 end), 4) as Varios1
                                                FROM
                                                    trabajodetalle td
                                                    inner join solicitud s on
                                                    s.solicitud_id = td.solicitud_id
                                                    inner join departamento d on
                                                    d.departamento_id = s.departamento_id
                                                    inner join subdepartamento s2 on
                                                    s2.departamento_id = s.departamento_id
                                                    and s2.subdepartamento_id = s.subdepartamento_id
                                                WHERE
                                                    year(td.fecha_cierre) = ?
                                                    and month(td.fecha_cierre)= ?
                                                    and s.departamento_id = ?
                                                    and s.subdepartamento_id = ?
                                                GROUP BY
                                                    td.solicitud_id
                                                UNION 
                                                SELECT
                                                    'treintabarraMaq' as Parte,
                                                    'cecoMaquinas' as Ceco,
                                                    concat('Maquinas ', d.departamentos_desc) as Departamento,
                                                    'Maquinas' as Subepartamento,
                                                    'Maquinas' as Treinta,
                                                    'Maquinas' as Nombre,
                                                    'Maquinas' as Apellidos,
                                                    'Maquinas' as Descripcion,
                                                    m1.periodo as Fecha,
                                                    0,
                                                    0,
                                                    0,
                                                    round(m1.color_total, 4),
                                                    round(m1.byn_total, 4),
                                                    0
                                                FROM
                                                    gastos_maquina m1
                                                    inner join departamento d on
                                                    d.departamento_id = m1.departamento_id
                                                WHERE
                                                    year(m1.periodo) = ?
                                                    and month(m1.periodo) = ?
                                                    and m1.departamento_id = ?
                                                UNION 
                                                SELECT
                                                    'treintabarraImp' as Parte,
                                                    'cecoImpresoras' as Ceco,
                                                    concat('Impresoras ', d.departamentos_desc) as Departamento,
                                                    'Impresoras' as Subepartamento,
                                                    'Impresoras' as Treinta,
                                                    'Impresoras' as Nombre,
                                                    'Impresoras' as Apellidos,
                                                    'Impresoras' as Descripcion,
                                                    m1.periodo as Fecha,
                                                    0,
                                                    0,
                                                    0,
                                                    round(m1.color_total, 4),
                                                    round(m1.byn_total, 4),
                                                    0
                                                FROM
                                                    gastos_impresora m1
                                                    inner join departamento d on
                                                    d.departamento_id = m1.departamento_id
                                                WHERE
                                                    year(m1.periodo) = ?
                                                    and month(m1.periodo) = ?
                                                    and m1.departamento_id = ?";

$consultaTodasMaquinasDetalleDptoSubdpto = "    SELECT 
                                        'treintabarraMaq',
                                        'cecoMaquinas',
                                        concat('Maquinas ', d.departamentos_desc),
                                        'Maquinas',
                                        m1.periodo, 
                                        round(m1.byn_total,4),
                                        round(m1.color_total,4)
                                    FROM 
                                        gastos_maquina m1 
                                    INNER JOIN 
                                        departamento d on 
                                        d.departamento_id = m1.departamento_id 
                                    WHERE 
                                        YEAR(m1.periodo) = ? AND
                                        month(m1.periodo) = ? AND 
                                        m1.departamento_id = ?";


$consultaTodasImpresorasDetalleDptoSubdpto = "    SELECT 
                                        'treintabarraImp',
                                        'cecoImpresoras',
                                        concat('Impresoras ', d.departamentos_desc),
                                        'Impresoras',
                                        m1.periodo, 
                                        round(m1.byn_total,4),
                                        round(m1.color_total,4)
                                    FROM 
                                        gastos_impresora m1 
                                    INNER JOIN 
                                        departamento d on 
                                        d.departamento_id = m1.departamento_id 
                                    WHERE 
                                        YEAR(m1.periodo) = ? AND
                                        month(m1.periodo) = ? AND 
                                        m1.departamento_id = ?";

$consultaDetalleParte = "SELECT 
                            s.nombre_solicitante, 
                            s.apellidos_solicitante, 
                            t.tipo_desc, 
                            d.descripcion, 
                            td.unidades, 
                            td.preciototal, 
                            d1.departamentos_desc, 
                            d1.ceco, 
                            s1.subdepartamento_desc, 
                            s1.treintabarra, 
                            s.fecha_cierre 
                        FROM 
                            trabajodetalle td 
                            INNER JOIN 
                                tipo t on 
                                    td.tipo_id = t.tipo_id 
                            INNER JOIN 
                                detalle d on 
                                    td.tipo_id = d.tipo_id and 
                                    td.detalle_id = d.detalle_id 
                            INNER JOIN 
                                solicitud s on 
                                    td.solicitud_id = s.solicitud_id 
                            INNER JOIN 
                                departamento d1 on 
                                    s.departamento_id = d1.departamento_id and 
                                    d1.markfordelete = 0 
                            INNER JOIN 
                                subdepartamento s1 on 
                                    s.departamento_id = s1.departamento_id and 
                                    s.subdepartamento_id= s1.subdepartamento_id and 
                                    s1.markfordelete = 0 
                        WHERE 
                            td.solicitud_id = ?";

$consultaTodasImpresionesGlobal = "SELECT 
                                        d.ceco as Ceco,
                                        d.departamentos_desc as Departamento,
                                        ROUND(SUM(case when td.tipo_id = '1' then td.preciototal else 0 end), 4) as Espiral,
                                        ROUND(SUM(case when td.tipo_id = '2' then td.preciototal else 0 end), 4) as Encolado,
                                        ROUND(SUM(case when td.tipo_id = '3' then td.preciototal else 0 end), 4) as Varios1,
                                        ROUND(SUM(case when td.tipo_id = '4' then td.preciototal else 0 end), 4) as Color,
                                        ROUND(SUM(case when td.tipo_id = '5' then td.preciototal else 0 end), 4) as BlancoNegro,
                                        ROUND(SUM(case when td.tipo_id in ('6', '7') then td.preciototal else 0 end), 4) as Varios1
                                    FROM
                                        trabajodetalle td
                                        inner join solicitud s on
                                        s.solicitud_id = td.solicitud_id
                                        inner join departamento d on
                                        d.departamento_id = s.departamento_id
                                        inner join subdepartamento s2 on
                                        s2.departamento_id = s.departamento_id
                                        and s2.subdepartamento_id = s.subdepartamento_id
                                    WHERE
                                        year(td.fecha_cierre) = ?
                                        and month(td.fecha_cierre)= ?
                                    GROUP BY
                                        d.departamento_id 
                                    UNION 
                                    SELECT
                                        'cecoMaquinas' as Ceco,
                                        concat('Maquinas ', d.departamentos_desc) as Departamento,
                                        0,
                                        0,
                                        0,
                                        round(m1.color_total, 4),
                                        round(m1.byn_total, 4),
                                        0
                                    FROM
                                        gastos_maquina m1
                                        inner join departamento d on
                                        d.departamento_id = m1.departamento_id
                                    WHERE
                                        year(m1.periodo) = ?
                                        and month(m1.periodo) = ?
                                    UNION 
                                    SELECT
                                        'cecoImpresoras' as Ceco,
                                        concat('Impresoras ', d.departamentos_desc) as Departamento,
                                        0,
                                        0,
                                        0,
                                        round(m1.color_total, 4),
                                        round(m1.byn_total, 4),
                                        0
                                    FROM
                                        gastos_impresora m1
                                        inner join departamento d on
                                        d.departamento_id = m1.departamento_id
                                    WHERE
                                        year(m1.periodo) = ?
                                        and month(m1.periodo) = ?";

$consultaImpresionesGlobalDpto = "SELECT 
                                    d.ceco as Ceco,
                                    d.departamentos_desc as Departamento,
                                    ROUND(SUM(case when td.tipo_id = '1' then td.preciototal else 0 end), 4) as Espiral,
                                    ROUND(SUM(case when td.tipo_id = '2' then td.preciototal else 0 end), 4) as Encolado,
                                    ROUND(SUM(case when td.tipo_id = '3' then td.preciototal else 0 end), 4) as Varios1,
                                    ROUND(SUM(case when td.tipo_id = '4' then td.preciototal else 0 end), 4) as Color,
                                    ROUND(SUM(case when td.tipo_id = '5' then td.preciototal else 0 end), 4) as BlancoNegro,
                                    ROUND(SUM(case when td.tipo_id in ('6', '7') then td.preciototal else 0 end), 4) as Varios1
                                FROM
                                    trabajodetalle td
                                    inner join solicitud s on
                                    s.solicitud_id = td.solicitud_id
                                    inner join departamento d on
                                    d.departamento_id = s.departamento_id
                                    inner join subdepartamento s2 on
                                    s2.departamento_id = s.departamento_id
                                    and s2.subdepartamento_id = s.subdepartamento_id
                                WHERE
                                    year(td.fecha_cierre) = ?
                                    and month(td.fecha_cierre)= ?
                                    and s.departamento_id = ?
                                GROUP BY
                                    d.departamento_id 
                                UNION 
                                SELECT
                                    'cecoMaquinas' as Ceco,
                                    concat('Maquinas ', d.departamentos_desc) as Departamento,
                                    0,
                                    0,
                                    0,
                                    round(m1.color_total, 4),
                                    round(m1.byn_total, 4),
                                    0
                                FROM
                                    gastos_maquina m1
                                    inner join departamento d on
                                    d.departamento_id = m1.departamento_id
                                WHERE
                                    year(m1.periodo) = ?
                                    and month(m1.periodo) = ?
                                    and m1.departamento_id = ?
                                UNION 
                                SELECT
                                    'cecoImpresoras' as Ceco,
                                    concat('Impresoras ', d.departamentos_desc) as Departamento,
                                    0,
                                    0,
                                    0,
                                    round(m1.color_total, 4),
                                    round(m1.byn_total, 4),
                                    0
                                FROM
                                    gastos_impresora m1
                                    inner join departamento d on
                                    d.departamento_id = m1.departamento_id
                                WHERE
                                    year(m1.periodo) = ?
                                    and month(m1.periodo) = ?
                                    and m1.departamento_id = ?";

$consultaImpresionesGlobalDptoSubdpto = "SELECT 
                                    d.ceco as Ceco,
                                    d.departamentos_desc as Departamento,
                                    ROUND(SUM(case when td.tipo_id = '1' then td.preciototal else 0 end), 4) as Espiral,
                                    ROUND(SUM(case when td.tipo_id = '2' then td.preciototal else 0 end), 4) as Encolado,
                                    ROUND(SUM(case when td.tipo_id = '3' then td.preciototal else 0 end), 4) as Varios1,
                                    ROUND(SUM(case when td.tipo_id = '4' then td.preciototal else 0 end), 4) as Color,
                                    ROUND(SUM(case when td.tipo_id = '5' then td.preciototal else 0 end), 4) as BlancoNegro,
                                    ROUND(SUM(case when td.tipo_id in ('6', '7') then td.preciototal else 0 end), 4) as Varios1
                                FROM
                                    trabajodetalle td
                                    inner join solicitud s on
                                    s.solicitud_id = td.solicitud_id
                                    inner join departamento d on
                                    d.departamento_id = s.departamento_id
                                    inner join subdepartamento s2 on
                                    s2.departamento_id = s.departamento_id
                                    and s2.subdepartamento_id = s.subdepartamento_id
                                WHERE
                                    year(td.fecha_cierre) = ?
                                    and month(td.fecha_cierre)= ?
                                    and s.departamento_id = ?
                                    and s.subdepartamento_id = ?
                                GROUP BY
                                    d.departamento_id 
                                UNION 
                                SELECT
                                    'cecoMaquinas' as Ceco,
                                    concat('Maquinas ', d.departamentos_desc) as Departamento,
                                    0,
                                    0,
                                    0,
                                    round(m1.color_total, 4),
                                    round(m1.byn_total, 4),
                                    0
                                FROM
                                    gastos_maquina m1
                                    inner join departamento d on
                                    d.departamento_id = m1.departamento_id
                                WHERE
                                    year(m1.periodo) = ?
                                    and month(m1.periodo) = ?
                                    and m1.departamento_id = ?
                                UNION 
                                SELECT
                                    'cecoImpresoras' as Ceco,
                                    concat('Impresoras ', d.departamentos_desc) as Departamento,
                                    0,
                                    0,
                                    0,
                                    round(m1.color_total, 4),
                                    round(m1.byn_total, 4),
                                    0
                                FROM
                                    gastos_impresora m1
                                    inner join departamento d on
                                    d.departamento_id = m1.departamento_id
                                WHERE
                                    year(m1.periodo) = ?
                                    and month(m1.periodo) = ?
                                    and m1.departamento_id = ?";
