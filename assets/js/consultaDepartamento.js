$(document).ready(function($) {

    var direccionEnvio = '../dao/select/informeDepartamentos.php';
    mostrarSpin();
    $.post(direccionEnvio, function(response) {
        response = get_hostname(response);
        resultadoGlobal = response.data;
        var resultadoJSON = "[";
        for (var key in resultadoGlobal) {
            if (resultadoGlobal.hasOwnProperty(key)) {
                var ceco = JSON.stringify(resultadoGlobal[key].CECO).replace('\"', '').trim();
                var departamento = JSON.stringify(resultadoGlobal[key].DEPARTAMENTO).replace('"', '').trim();
                var subdepartamento = JSON.stringify(resultadoGlobal[key].SUBDEPARTAMENTO).replace('"', '').trim();
                var treintabarra = JSON.stringify(resultadoGlobal[key].TREINTA).replace('"', '').trim();
                if (resultadoJSON === '[') {
                    resultadoJSON += "{ \"esb\" :\"" + ceco + ", \"departamento\": \"" + departamento + ", \"subdepartamento\": \"" + subdepartamento + ", \"treintabarra\": \"" + treintabarra + " }";
                } else {
                    resultadoJSON += ",{ \"esb\" :\"" + ceco + ", \"departamento\": \"" + departamento + ", \"subdepartamento\": \"" + subdepartamento + ", \"treintabarra\": \"" + treintabarra + " }";
                }
            }
        }
        borrarSpin();
        resultadoJSON += "]";
        var d = new Date().toISOString();
        $('#listadoDepartartamento').bootstrapTable('destroy');
        $('#listadoDepartartamento').bootstrapTable({
            cache: false,
            exportDataType: $('#listadoDepartartamento').val(),
            exportTypes: ['csv', 'excel', 'pdf'],
            exportOptions: {
                fileName: 'Consulta Departamentos' + d
            },
            columns: [
                { field: 'esb', title: 'ESB' },
                { field: 'departamento', title: 'Departamento' },
                { field: 'subdepartamento', title: 'Subdepartamento' },
                { field: 'treintabarra', title: 'treintabarra' }
            ],
            data: JSON.parse(resultadoJSON)
        });
    });

});