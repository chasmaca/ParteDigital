$(document).ready(function($) {

    cargarComboPeriodo();
    direccionEnvio = '../dao/select/cargarSelectDepartamento.php';
    var optionTextDpto = "";
    $.post(direccionEnvio, function(response) {
        optionText = "";
        response = get_hostname(response);
        resultadoGlobal = response.data;
        for (var key in resultadoGlobal) {
            if (resultadoGlobal.hasOwnProperty(key)) {
                var id = JSON.stringify(resultadoGlobal[key].id).replace(/"/g, '').trim();
                var texto = JSON.stringify(resultadoGlobal[key].nombre).replace(/"/g, '').trim();
                optionTextDpto += '<option value="' + id + '">' + texto + '</option>';
            }
        }
        $('#departamento').append(optionTextDpto);
    });

    $(document).on("click", "#listadoTrabajos", function() {
        mostrarSpin();
        var direccionEnvio = '../dao/select/informeTrabajos.php';
        var valorEnvio = "periodo=" + $("#periodo").val() + '&departamento=' + $("#departamento").val();
        $.post(direccionEnvio, valorEnvio, function(response) {
            response = get_hostname(response);
            resultadoGlobal = response.data;
            var resultadoJSON = "[";
            for (var key in resultadoGlobal) {
                if (resultadoGlobal.hasOwnProperty(key)) {
                    var solicitud = JSON.stringify(resultadoGlobal[key].SOLICITUD).replace('\"', '').trim();
                    var departamento = JSON.stringify(resultadoGlobal[key].DEPARTAMENTO).replace('\"', '').trim();
                    var subdepartamento = JSON.stringify(resultadoGlobal[key].SUBDEPARTAMENTO).replace('\"', '').trim();
                    var nombre = JSON.stringify(resultadoGlobal[key].NOMBRE).replace('\"', '').trim();
                    var email = JSON.stringify(resultadoGlobal[key].EMAIL).replace('\"', '').trim();
                    var autorizador = JSON.stringify(resultadoGlobal[key].AUTORIZADOR).replace('\"', '').trim();
                    var descripcion = JSON.stringify(resultadoGlobal[key].DESCRIPCION).replace('\"', '').trim();
                    var estado = JSON.stringify(resultadoGlobal[key].ESTADO).replace('\"', '').trim();
                    var alta = JSON.stringify(resultadoGlobal[key].ALTA).replace('\"', '').trim();
                    var cierre = JSON.stringify(resultadoGlobal[key].CIERRE).replace('\"', '').trim();
                    var reabrirSolicitud = "reabrir_" + solicitud.replace('\"', '');
                    var campoEnlace = "";
                    if (estado.replace('"', '') === 'Finalizada') {
                        campoEnlace = "<a id='" + reabrirSolicitud + "' style='cursor:pointer'>Reabrir</a>";
                    } else {
                        campoEnlace = "";
                    }
                    if (resultadoJSON === '[') {
                        resultadoJSON += "{ \"solicitud\" :\"" + solicitud +
                            ", \"departamento\": \"" + departamento +
                            ", \"subdepartamento\": \"" + subdepartamento +
                            ", \"nombre\": \"" + nombre +
                            ", \"email\": \"" + email +
                            ", \"autorizador\": \"" + autorizador +
                            ", \"descripcion\": \"" + descripcion +
                            ", \"estado\": \"" + estado +
                            ", \"alta\": \"" + alta +
                            ", \"cierre\": \"" + cierre +
                            ", \"operativa\": \"" + campoEnlace +
                            "\" }";
                    } else {
                        resultadoJSON += "," +
                            "{ \"solicitud\" :\"" + solicitud +
                            ", \"departamento\": \"" + departamento +
                            ", \"subdepartamento\": \"" + subdepartamento +
                            ", \"nombre\": \"" + nombre +
                            ", \"email\": \"" + email +
                            ", \"autorizador\": \"" + autorizador +
                            ", \"descripcion\": \"" + descripcion +
                            ", \"estado\": \"" + estado +
                            ", \"alta\": \"" + alta +
                            ", \"cierre\": \"" + cierre +
                            ", \"operativa\": \"" + campoEnlace +
                            "\" }";
                    }
                }
            }
            resultadoJSON += "]";
            borrarSpin();
            $('#listadoTrabajosTable').bootstrapTable('destroy');
            $('#listadoTrabajosTable').bootstrapTable({
                pagination: true,
                search: true,
                sortable: true,
                cache: true,
                columns: [
                    { field: 'solicitud', title: 'Solicitud' },
                    { field: 'departamento', title: 'Departamento' },
                    { field: 'subdepartamento', title: 'Subdepartamento' },
                    { field: 'nombre', title: 'Nombre' },
                    { field: 'email', title: 'Email' },
                    { field: 'autorizador', title: 'Autorizador' },
                    { field: 'descripcion', title: 'Descripcion' },
                    { field: 'estado', title: 'Estado' },
                    { field: 'alta', title: 'Fecha de Alta' },
                    { field: 'cierre', title: 'Fecha de Cierre' },
                    { field: 'operativa', title: 'Reabrir' }
                ],
                data: JSON.parse(resultadoJSON)
            });
        });
    });

    $(document).on("click", "a", function() {
        if (this.id.indexOf('reabrir_') === 0) {
            var nombreHref = this.id;
            var idSolicitud = nombreHref.substring(nombreHref.indexOf('_') + 1);
            var direccionEnvio = '../dao/update/reabreTrabajo.php';
            var valorEnvio = "solicitud=" + idSolicitud;
            $.post(direccionEnvio, valorEnvio, function(response) {
                response = get_hostname(response);
                $('#listadoTrabajos').click();
                notifier(response.message);
            }).fail(function(e) {
                notifier(e);
                console.log(e);
            });
        }
    });

});