$(document).ready(function($) {

    var direccionEnvio = '../dao/select/informeImpresoras.php';

    mostrarSpin();
    $.post(direccionEnvio, function(response) {

        response = get_hostname(response);
        resultadoGlobal = response.data;

        var resultadoJSON = "[";

        for (var key in resultadoGlobal) {
            if (resultadoGlobal.hasOwnProperty(key)) {
                var id = JSON.stringify(resultadoGlobal[key].IMPRESORA_ID).trim();
                var modelo = JSON.stringify(resultadoGlobal[key].MODELO).trim();
                var edificio = JSON.stringify(resultadoGlobal[key].EDIFICIO).trim();
                var ubicacion = JSON.stringify(resultadoGlobal[key].UBICACION).trim();
                var fecha = JSON.stringify(resultadoGlobal[key].FECHA).trim();
                var serie = JSON.stringify(resultadoGlobal[key].SERIE).trim();
                var numero = JSON.stringify(resultadoGlobal[key].NUMERO).trim();

                if (resultadoJSON === '[') {
                    resultadoJSON += "{ \"id\" :" + id +
                        ", \"modelo\": " + modelo +
                        ", \"edificio\": " + edificio +
                        ", \"ubicacion\": " + ubicacion +
                        ", \"fecha\": " + fecha +
                        ", \"serie\": " + serie +
                        ", \"numero\": " + numero +
                        " }";
                } else {
                    resultadoJSON += "," +
                        "{ \"id\" :" + id +
                        ", \"modelo\": " + modelo +
                        ", \"edificio\": " + edificio +
                        ", \"ubicacion\": " + ubicacion +
                        ", \"fecha\": " + fecha +
                        ", \"serie\": " + serie +
                        ", \"numero\":" + numero +
                        " }";
                }
            }
        }
        resultadoJSON += "]";
        var d = new Date().toISOString();
        borrarSpin();
        $('#listadoImpresora').bootstrapTable('destroy');
        $('#listadoImpresora').bootstrapTable({
            search: true,
            sortable: true,
            cache: false,
            exportTypes: ['csv', 'excel', 'pdf'],
            exportOptions: {
                fileName: 'Listado Impresoras-' + d
            },
            columns: [
                { field: 'id', title: 'ID' },
                { field: 'modelo', title: 'MODELO' },
                { field: 'edificio', title: 'EDIFICIO' },
                { field: 'ubicacion', title: 'UBICACIÓN' },
                { field: 'fecha', title: 'FECHA' },
                { field: 'serie', title: 'SERIE' },
                { field: 'numero', title: 'NÚMERO' }
            ],
            data: JSON.parse(resultadoJSON)
        });
    });
});