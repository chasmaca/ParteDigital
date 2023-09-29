$('cargaMasivaForm').ready(function($) {

    //    var formulario = document.activeElement.id;
    //    if (formulario === 'gestionFichero') {


    $(document).on('change', '#cargaSubdepartamento', function(e) {
        var fd = new FormData();
        var files = $('#cargaSubdepartamento')[0].files;

        // Check file selected or not
        if (files.length > 0) {
            fd.append('file', files[0]);

            $.ajax({
                url: '../dao/insert/altaDepartamentosFichero.php',
                type: 'post',
                data: fd,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.success === true) {
                        console.log(response);
                        var resultadoJSON = "[";
                        resultadoGlobal = response.data;
                        for (var key in resultadoGlobal) {
                            var texto = JSON.stringify(resultadoGlobal[key]);
                            if (resultadoJSON === '[') {
                                resultadoJSON += '{ \"texto\" :' + texto + ' }';
                            } else {
                                resultadoJSON += ',{ \"texto\" :' + texto + ' }';
                            }
                        }
                        resultadoJSON += "]";

                        $('#cargaMasiva').bootstrapTable('destroy');
                        $('#cargaMasiva').bootstrapTable({
                            sortable: true,
                            cache: false,
                            search: true,
                            columns: [
                                { field: 'texto', title: 'Resultado' }
                            ],
                            data: JSON.parse(resultadoJSON)
                        });



                    } else {
                        alert('file not uploaded');
                    }
                },
            });
        } else {
            alert("Please select a file.");
        }
    });


});