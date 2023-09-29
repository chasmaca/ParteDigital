$(document).ready(function($) {

    var direccionEnvio = '../dao/select/cargarSelectRol.php';
    var optionText = "";
    cargarSelect('usuario', 'listadoUsuarios');
    $.post(direccionEnvio, function(response) {
        response = get_hostname(response);
        resultadoGlobal = response.data;
        for (var key in resultadoGlobal) {
            if (resultadoGlobal.hasOwnProperty(key)) {
                var id = JSON.stringify(resultadoGlobal[key].id).replace(/"/g, '').trim();
                var texto = JSON.stringify(resultadoGlobal[key].nombre).replace(/"/g, '').trim();
                optionText += '<option value="' + id + '">' + texto + '</option>';
            }
        }
        $('#rol').append(optionText);
    });

    $('#listadoUsuarios').click(function() {
        mostrarSpin();
        direccionEnvio = '../dao/select/informeConsultaUsuario.php';
        valorFormulario = "usuario=" + $('#usuario').val() + "&rolHidden=" + $('#rol').val();
        $.post(direccionEnvio, valorFormulario, function(response) {
            response = get_hostname(response);
            resultadoGlobal = response.data;
            var resultadoJSON = "[";
            for (var key in resultadoGlobal) {
                if (resultadoGlobal.hasOwnProperty(key)) {
                    var nombre = JSON.stringify(resultadoGlobal[key].nombre).replace('\"', '').trim();
                    var apellido = JSON.stringify(resultadoGlobal[key].apellido).replace('"', '').trim();
                    var logon = JSON.stringify(resultadoGlobal[key].logon).replace('"', '').trim();
                    var rol = JSON.stringify(resultadoGlobal[key].rol).replace(/"/g, '').trim();
                    switch (rol) {
                        case '1':
                            rol = 'Administrador';
                            break;
                        case '2':
                            rol = 'Gestor';
                            break;
                        case '3':
                            rol = 'Autorizador';
                            break;
                        case '4':
                            rol = 'Plantilla';
                            break;
                        case '5':
                            rol = 'Consulta';
                            break;
                        case '6':
                            rol = 'Impresoras';
                            break;
                    }

                    if (resultadoJSON === '[') {
                        resultadoJSON += "{ \"nombre\" :\"" + nombre + ", \"apellido\": \"" + apellido + ", \"logon\": \"" + logon + ", \"rol\": \"" + rol + "\" }";
                    } else {
                        resultadoJSON += ",{ \"nombre\" :\"" + nombre + ", \"apellido\": \"" + apellido + ", \"logon\": \"" + logon + ", \"rol\": \"" + rol + "\" }";
                    }
                }
            }
            resultadoJSON += "]";
            var d = new Date().toISOString();
            borrarSpin();
            $('#listadoUsuariosTable').bootstrapTable('destroy');
            $('#listadoUsuariosTable').bootstrapTable({
                search: true,
                sortable: true,
                cache: false,
                exportTypes: ['csv', 'excel', 'pdf'],
                exportOptions: {
                    fileName: 'Consulta Usuarios' + d
                },
                columns: [
                    { field: 'nombre', title: 'Nombre' },
                    { field: 'apellido', title: 'Apellido' },
                    { field: 'logon', title: 'Email' },
                    { field: 'rol', title: 'Rol' }
                ],
                data: JSON.parse(resultadoJSON)
            });
        });
    });

});