$(document).ready(function($) {

    var formulario = document.activeElement.id;
    if (formulario === 'gastosMaquina') {
        cargarComboPeriodo();
        direccionEnvio = '../dao/select/cargarTableDepartamento.php';
        direccionConsulta = '../dao/select/cargarDatosMaquina.php';
        var cargamos = true;
        $(document).on('change', '#listadoPaper', function(e) {
            var files = document.querySelector('[type=file]').files;
            var formData = new FormData();
            var urlExcel = '../dao/insert/altaExcelMaquina.php';
            for (var i = 0; i < files.length; i++) {
                var file = files[i];
                formData.append('files[]', file);
            }
            fetch(urlExcel, {
                method: 'POST',
                body: formData,
            }).then(response => {
                console.log(response);
                if (response.status === 200) {
                    notifier("Fichero Subido Correctamente");
                } else {
                    notifier("Fichero Subido con Problemas. Por favor Revisa los datos.");
                }
            });
        });

        $(document).on("change", "select", function() {
            if (this.id === 'periodo') {
                var variable = 'periodo=' + $("#periodo").val();
                $.post(direccionConsulta, variable, function(response) {
                    response = get_hostname(response);
                    resultadoGlobal = response.data;
                    for (var key in resultadoGlobal) {
                        if (resultadoGlobal.hasOwnProperty(key)) {

                            var departamentoMaquina = JSON.stringify(resultadoGlobal[key].departamento_id).trim();
                            var ESB = JSON.stringify(resultadoGlobal[key].esb).trim();
                            var ByNTotal = (resultadoGlobal[key].byn_total === null) ? 0 : JSON.stringify(resultadoGlobal[key].byn_total).trim();
                            var ByNPrecio = JSON.stringify(resultadoGlobal[key].byn_precio).trim();
                            var ByNUnidades = (resultadoGlobal[key].byn_unidades === null) ? 0 : JSON.stringify(resultadoGlobal[key].byn_unidades).trim();
                            var ColorTotal = (resultadoGlobal[key].color_total === null) ? 0 : JSON.stringify(resultadoGlobal[key].color_total).trim();
                            var ColorPrecio = JSON.stringify(resultadoGlobal[key].color_precio).trim();
                            var ColorUnidades = (resultadoGlobal[key].color_unidades === null) ? 0 : JSON.stringify(resultadoGlobal[key].color_unidades).trim();

                            $("#unidadesByN_" + departamentoMaquina).val(ByNUnidades);
                            $("#precioByN_" + departamentoMaquina).val(ByNPrecio);
                            $("#totalByN_" + departamentoMaquina).val(ByNTotal);
                            $("#unidadesColor_" + departamentoMaquina).val(ColorUnidades);
                            $("#precioColor_" + departamentoMaquina).val(ColorPrecio);
                            $("#totalColor_" + departamentoMaquina).val(ColorTotal);
                        }
                    }
                    cargamos = false;
                });
            }
        });

        if (cargamos) {
            $.when(recuperaPrecioByN(), recuperaPrecioColor()).done(function(precioByN, precioColor) {

                var blancoPrecio = "";
                var colorPrecio = "";
                try {
                    blancoPrecio = JSON.parse(precioByN[0]);
                    colorPrecio = JSON.parse(precioColor[0]);
                } catch (e) {
                    blancoPrecio = precioByN[0];
                    colorPrecio = precioColor[0];
                }

                var precioByNPrecio = blancoPrecio.data[0].precio;
                var precioColorPrecio = colorPrecio.data[0].precio;
                colorCopia = colorPrecio.data[0].precio;
                bynCopia = blancoPrecio.data[0].precio;

                $.post(direccionEnvio, function(response) {
                    response = get_hostname(response);
                    resultadoGlobal = response.data;
                    var resultadoJSON = "[";
                    for (var key in resultadoGlobal) {
                        if (resultadoGlobal.hasOwnProperty(key)) {
                            var id = JSON.stringify(resultadoGlobal[key].id.trim());
                            var nombre = JSON.stringify(resultadoGlobal[key].nombre.trim());
                            var ceco = JSON.stringify(resultadoGlobal[key].esb.trim());
                            var idCampos = id.substring(id.indexOf('"') + 1, id.lastIndexOf("") - 1);
                            if (resultadoJSON === '[') {
                                resultadoJSON += '{ \"id\" :' + id +
                                    ', \"ESB\":' + ceco +
                                    ', \"nombre\":' + nombre +
                                    ', \"unidadesByN\": \"<input type=\'text\' name=\'unidadesByN_' + idCampos + '\' id=\'unidadesByN_' + idCampos + '\'>\"' +
                                    ', \"precioByN\": \"<input type=\'text\' name=\'precioByN_' + idCampos + '\' id=\'precioByN_' + idCampos + '\' value=\'' + precioByNPrecio + '\' readonly>\"' +
                                    ', \"totalByN\": \"<input type=\'text\' name=\'totalByN_' + idCampos + '\' id=\'totalByN_' + idCampos + '\' readonly>\"' +
                                    ', \"unidadesColor\": \"<input type=\'text\' name=\'unidadesColor_' + idCampos + '\' id=\'unidadesColor_' + idCampos + '\'>\"' +
                                    ', \"precioColor\": \"<input type=\'text\' name=\'precioColor_' + idCampos + '\' id=\'precioColor_' + idCampos + '\' value=\'' + precioColorPrecio + '\' readonly>\"' +
                                    ', \"totalColor\": \"<input type=\'text\' name=\'totalColor_' + idCampos + '\' id=\'totalColor_' + idCampos + '\' readonly>\"' +
                                    ' }';
                            } else {
                                resultadoJSON += ',' +
                                    '{ \"id\" :' + id +
                                    ', \"ESB\":' + ceco +
                                    ', \"nombre\":' + nombre +
                                    ', \"unidadesByN\": \"<input type=\'text\' name=\'unidadesByN_' + idCampos + '\' id=\'unidadesByN_' + idCampos + '\'>\"' +
                                    ', \"precioByN\": \"<input type=\'text\' name=\'precioByN_' + idCampos + '\' id=\'precioByN_' + idCampos + '\' value=\'' + precioByNPrecio + '\' readonly>\"' +
                                    ', \"totalByN\": \"<input type=\'text\' name=\'totalByN_' + idCampos + '\' id=\'totalByN_' + idCampos + '\' readonly>\"' +
                                    ', \"unidadesColor\": \"<input type=\'text\' name=\'unidadesColor_' + idCampos + '\' id=\'unidadesColor_' + idCampos + '\'>\"' +
                                    ', \"precioColor\": \"<input type=\'text\' name=\'precioColor_' + idCampos + '\' id=\'precioColor_' + idCampos + '\' value=\'' + precioColorPrecio + '\' readonly>\"' +
                                    ', \"totalColor\": \"<input type=\'text\' name=\'totalColor_' + idCampos + '\' id=\'totalColor_' + idCampos + '\' readonly>\"' +
                                    ' }';
                            }
                        }
                    }
                    resultadoJSON += "]";
                    $('#listadoGastosMaquina').bootstrapTable('destroy');
                    $('#listadoGastosMaquina').bootstrapTable({
                        sortable: true,
                        cache: false,
                        search: true,
                        columns: [
                            { field: 'id', title: 'ID' },
                            { field: 'ESB', title: 'ESB' },
                            { field: 'nombre', title: 'NOMBRE' },
                            { field: 'unidadesByN', title: 'COPIAS ByN' },
                            { field: 'precioByN', title: 'PRECIO ByN' },
                            { field: 'totalByN', title: 'TOTAL ByN' },
                            { field: 'unidadesColor', title: 'COPIAS COLOR' },
                            { field: 'precioColor', title: 'PRECIO COLOR' },
                            { field: 'totalColor', title: 'TOTAL COLOR' }
                        ],
                        data: JSON.parse(resultadoJSON)
                    });
                });
                $(document).on("blur", "input", function() {
                    if (this.id.indexOf('unidadesByN') !== -1) {
                        var dpto = this.id.substring(this.id.indexOf('_') + 1);
                        var precioByN = $('#precioByN_' + dpto).val();
                        var total = parseFloat(this.value) * parseFloat(precioByN);
                        $('#totalByN_' + dpto).val(total.toFixed(4));
                        var periodo = $('#periodo').val();
                        var tipo = 'ByN';
                        if (validaContenidoImpresora(periodo, dpto, this.value, precioByN, total, tipo)) {
                            var valores = "periodo=" + periodo +
                                "&departamento=" + dpto +
                                "&unidades=" + this.value +
                                "&precio=" + precioByN +
                                "&total=" + total.toFixed(4) +
                                "&tipo=" + tipo;
                            direccionEnvio = '../dao/insert/altaGastosMaquinas.php';
                            $.post(direccionEnvio, valores, function(response) {
                                response = get_hostname(response);
                                notifier(response.message);
                            });
                        } else {
                            notifier('Revisa los datos del formulario');
                        }
                    }
                    if (this.id.indexOf('unidadesColor') !== -1) {
                        var dpto1 = this.id.substring(this.id.indexOf('_') + 1);
                        var precioColor = $('#precioColor_' + dpto1).val();
                        var total1 = parseFloat(this.value) * parseFloat(precioColor);
                        $('#totalColor_' + dpto1).val(total1.toFixed(4));
                        var periodo1 = $('#periodo').val();
                        var tipo1 = 'Color';
                        if (validaContenidoImpresora(periodo1, dpto1, this.value, precioColor, total1, tipo1)) {
                            var valores1 = "periodo=" + periodo1 +
                                "&departamento=" + dpto1 +
                                "&unidades=" + this.value +
                                "&precio=" + precioColor +
                                "&total=" + total1.toFixed(4) +
                                "&tipo=" + tipo1;
                            direccionEnvio = '../dao/insert/altaGastosMaquinas.php';
                            $.post(direccionEnvio, valores1, function(response) {
                                response = get_hostname(response);
                                notifier(response.message);
                            });
                        } else {
                            notifier('Revisa los datos del formulario');
                        }
                    }
                });
            });
        }
    }
});