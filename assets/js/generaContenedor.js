var colorCopia = "";
var bynCopia = "";

$(document).ready(function($) {
    $("#altaDepartamento").click(function() {
        limpiarContenedor();
        $("#contenedor").load("altaDepartamento.html");
    });

    $("#bajaDepartamento").click(function() {
        limpiarContenedor();
        $("#contenedor").load("bajaDepartamento.html");
    });

    $("#modificacionDepartamento").click(function() {
        limpiarContenedor();
        $("#contenedor").load("modificacionDepartamento.html");
    });

    $("#consultaDepartamento").click(function() {
        limpiarContenedor();
        $("#contenedor").load("consultaDepartamento.html");
    });

    $("#altaSubdepartamento").click(function() {
        limpiarContenedor();
        $("#contenedor").load("altaSubdepartamento.html");
    });

    $("#bajaSubdepartamento").click(function() {
        limpiarContenedor();
        $("#contenedor").load("bajaSubdepartamento.html");
    });

    $("#modificacionSubdepartamento").click(function() {
        limpiarContenedor();
        $("#contenedor").load("modificacionSubdepartamento.html");
    });

    $("#gestionFichero").click(function() {
        limpiarContenedor();
        $("#contenedor").load("cargaMasiva.html");
    });


    $("#altaUsuario").click(function() {
        limpiarContenedor();
        $("#contenedor").load("altaUsuario.html");
    });

    $("#bajaUsuario").click(function() {
        limpiarContenedor();
        $("#contenedor").load("bajaUsuario.html");
    });

    $("#consultaUsuario").click(function() {
        limpiarContenedor();
        $("#contenedor").load("consultaUsuario.html");
    });

    $("#modificacionUsuario").click(function() {
        limpiarContenedor();
        $("#contenedor").load("modificacionUsuario.html");
    });

    $("#trabajos").click(function() {
        limpiarContenedor();
        $("#contenedor").load("trabajos.html");
    });

    $("#informes").click(function() {
        limpiarContenedor();
        $("#contenedor").load("informes.html");

        var formulario = document.activeElement.id;
        if (formulario === 'informes') {
            var checked = "";
            $(document).on("click", "#radio1", function(event) {
                checked = "global";
            });
            $(document).on("click", "#radio2", function(event) {
                checked = "detalle";
            });
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
            $(document).on("change", "#departamento", function() {
                $('#subdepartamento').empty();
                var optionTextSub = "";
                //$('#subdepartamento').append(optionTextSub);
                direccionEnvio = '../dao/select/cargarSelectSubdepartamento.php';
                var valor = "valor=" + this.value;
                $.post(direccionEnvio, valor, function(response) {
                    response = get_hostname(response);
                    resultadoGlobal = response.data;
                    optionTextSub += '<option value="0">Selecciona Subdepartamento</option>';
                    for (var key in resultadoGlobal) {
                        if (resultadoGlobal.hasOwnProperty(key)) {
                            var id = JSON.stringify(resultadoGlobal[key].id).replace(/"/g, '').trim();
                            var texto = JSON.stringify(resultadoGlobal[key].nombre).replace(/"/g, '').trim();
                            optionTextSub += '<option value="' + id + '">' + texto + '</option>';
                        }
                    }
                    $('#subdepartamento').append(optionTextSub);
                });
            });

            $(document).on("click", "#listadoInformeButton", function() {
                direccionEnvio = '../dao/select/informeGestor.php';
                var valor = "periodoHidden=" + $("#periodo").val() + "&departamentoHidden=" + $("#departamento").val() + "&subdepartamentoHidden=" + $("#subdepartamento").val() + "&tipoHidden=" + checked;
                var tipo = checked;
                var totalFinal = 0;
                if (validaInformes()) {
                    mostrarSpin();
                    $.post(direccionEnvio, valor, function(response) {
                        response = get_hostname(response);
                        resultadoGlobal = response.data;
                        var resultadoJSON = "[";
                        var total = 0;

                        if (tipo === 'global') {
                            for (var key in resultadoGlobal) {
                                if (resultadoGlobal.hasOwnProperty(key)) {

                                    var ceco = JSON.stringify(resultadoGlobal[key].ceco).replace(/\"/g, '').trim();
                                    var departamento = JSON.stringify(resultadoGlobal[key].departamentos_desc).replace(/\"/g, '');
                                    var lineaByN = (resultadoGlobal[key].blancoNegro === '') ? 0 : JSON.stringify(resultadoGlobal[key].blancoNegro).replace(/\"/g, '');
                                    var lineaColor = (resultadoGlobal[key].color === '') ? 0 : JSON.stringify(resultadoGlobal[key].color).replace(/\"/g, '');
                                    var lineaEncuadernacion = (resultadoGlobal[key].espiral === '') ? 0 : JSON.stringify(resultadoGlobal[key].espiral).replace(/\"/g, '');
                                    var lineaEncolado = (resultadoGlobal[key].encolado === '') ? 0 : JSON.stringify(resultadoGlobal[key].encolado).replace(/\"/g, '');
                                    var lineaVarios1 = (resultadoGlobal[key].varios1 === '') ? 0 : JSON.stringify(resultadoGlobal[key].varios1).replace(/\"/g, '');
                                    var lineaVarios2 = (resultadoGlobal[key].varios2 === '') ? 0 : JSON.stringify(resultadoGlobal[key].varios2).replace(/\"/g, '');
                                    var lineaMaquinas = (resultadoGlobal[key].maquinas === '') ? 0 : JSON.stringify(resultadoGlobal[key].maquinas).replace(/\"/g, '');
                                    var lineaImpresoras = (resultadoGlobal[key].impresoras === '') ? 0 : JSON.stringify(resultadoGlobal[key].impresoras).replace(/\"/g, '');


                                    total = (parseFloat(lineaByN) + parseFloat(lineaColor) + parseFloat(lineaEncuadernacion) + parseFloat(lineaEncolado) + parseFloat(lineaVarios1) + parseFloat(lineaVarios2) + parseFloat(lineaMaquinas) + parseFloat(lineaImpresoras)).toFixed(4);
                                    totalFinal = (parseFloat(totalFinal) + parseFloat(total)).toFixed(4);

                                    if (total !== parseFloat(0).toFixed(4)) {
                                        if (resultadoJSON === '[') {
                                            resultadoJSON += "{ \"esb\" :\"" + ceco + "\", \"nombre\": \"" + departamento +
                                                "\", \"byn\": \"" + parseFloat(lineaByN).toFixed(4).toString().replace('.', ',') +
                                                "\", \"color\": \"" + parseFloat(lineaColor).toFixed(4).toString().replace('.', ',') +
                                                "\", \"encuadernacion\": \"" + parseFloat(lineaEncuadernacion).toFixed(4).toString().replace('.', ',') +
                                                "\", \"encolado\": \"" + parseFloat(lineaEncolado).toFixed(4).toString().replace('.', ',') +
                                                "\", \"varios1\": \"" + parseFloat(lineaVarios1).toFixed(4).toString().replace('.', ',') +
                                                "\", \"varios2\": \"" + parseFloat(lineaVarios2).toFixed(4).toString().replace('.', ',') +
                                                "\", \"maquinas\": \"" + parseFloat(lineaMaquinas).toFixed(4).toString().replace('.', ',') +
                                                "\", \"impresoras\": \"" + parseFloat(lineaImpresoras).toFixed(4).toString().replace('.', ',') +
                                                "\", \"subtotal\": \"" + parseFloat(total).toFixed(4).toString().replace('.', ',') + "\"}";
                                        } else {
                                            resultadoJSON += ",{ \"esb\" :\"" + ceco + "\", \"nombre\": \"" + departamento +
                                                "\", \"byn\": \"" + parseFloat(lineaByN).toFixed(4).toString().replace('.', ',') +
                                                "\", \"color\": \"" + parseFloat(lineaColor).toFixed(4).toString().replace('.', ',') +
                                                "\", \"encuadernacion\": \"" + parseFloat(lineaEncuadernacion).toFixed(4).toString().replace('.', ',') +
                                                "\", \"encolado\": \"" + parseFloat(lineaEncolado).toFixed(4).toString().replace('.', ',') +
                                                "\", \"varios1\": \"" + parseFloat(lineaVarios1).toFixed(4).toString().replace('.', ',') +
                                                "\", \"varios2\": \"" + parseFloat(lineaVarios2).toFixed(4).toString().replace('.', ',') +
                                                "\", \"maquinas\": \"" + parseFloat(lineaMaquinas).toFixed(4).toString().replace('.', ',') +
                                                "\", \"impresoras\": \"" + parseFloat(lineaImpresoras).toFixed(4).toString().replace('.', ',') +
                                                "\", \"subtotal\": \"" + parseFloat(total).toFixed(4).toString().replace('.', ',') + "\"}";
                                        }
                                    }
                                }
                            }
                            resultadoJSON += ",{ \"esb\" :\"\", \"nombre\": \"\", \"byn\": \"" +
                                "\", \"color\": \"\", \"encuadernacion\": \"\", \"varios\": \"" +
                                "\", \"maquinas\": \"\", \"impresoras\": \"\", \"subtotal\": \"" + parseFloat(totalFinal).toFixed(4).toString().replace('.', ',') + "\"}";
                            resultadoJSON += "]";

                            borrarSpin();
                            var date1 = new Date().toISOString();
                            $('#listadoInformeTable').bootstrapTable('destroy');
                            $('#listadoInformeTable').bootstrapTable({
                                search: true,
                                sortable: true,
                                cache: true,
                                //  exportDataType: $('#listadoInformeTable').val(),
                                exportTypes: ['csv', 'excel', 'pdf'],
                                exportOptions: {
                                    fileName: 'Informe Administrador' + date1
                                },
                                columns: [{ field: 'esb', title: 'ESB' },
                                    { field: 'nombre', title: 'Nombre' },
                                    { field: 'byn', title: 'Blanco y Negro' },
                                    { field: 'color', title: 'Color' },
                                    { field: 'encuadernacion', title: 'Encuadernacion' },
                                    { field: 'encolado', title: 'Encolado' },
                                    { field: 'varios1', title: 'Varios 1' },
                                    { field: 'varios2', title: 'Varios 2' },
                                    { field: 'maquinas', title: 'Maquinas' },
                                    { field: 'impresoras', title: 'Impresoras' },
                                    { field: 'subtotal', title: 'Total' }
                                ],
                                data: JSON.parse(resultadoJSON)
                            });
                        } else {
                            var checkDetalle = "";
                            for (var keyDetalle in resultadoGlobal) {
                                if (resultadoGlobal.hasOwnProperty(keyDetalle)) {

                                    var parte = JSON.stringify(resultadoGlobal[keyDetalle].codigo).replace(/\"/g, '');
                                    var cecoDetalle = JSON.stringify(resultadoGlobal[keyDetalle].ceco).replace(/\"/g, '');
                                    var departamentoDetalle = JSON.stringify(resultadoGlobal[keyDetalle].departamentos_desc).replace(/\"/g, '');
                                    var subdepartamento = JSON.stringify(resultadoGlobal[keyDetalle].subdepartamentos_desc).replace(/\"/g, '');
                                    var treinta = JSON.stringify(resultadoGlobal[keyDetalle].treinta).replace(/\"/g, '');
                                    var nombre = "";
                                    var descripcion = "";
                                    if (resultadoGlobal[keyDetalle].nombre !== "" && resultadoGlobal[keyDetalle].nombre) {
                                        nombre = JSON.stringify(resultadoGlobal[keyDetalle].nombre).replace(/\"/g, '');
                                    } else {
                                        nombre = 'N/A';
                                    }

                                    if (resultadoGlobal[keyDetalle].descripcion !== "" && resultadoGlobal[keyDetalle].descripcion) {
                                        descripcion = JSON.stringify(resultadoGlobal[keyDetalle].descripcion).replace(/\"/g, '');
                                    } else {
                                        descripcion = 'N/A';
                                    }
                                    var lineaByNDetalle = (resultadoGlobal[keyDetalle].blancoNegro === '' || resultadoGlobal[keyDetalle].blancoNegro === null) ? 0 : JSON.stringify(resultadoGlobal[keyDetalle].blancoNegro).replace(/\"/g, '');
                                    var lineaColorDetalle = (resultadoGlobal[keyDetalle].color === '' || resultadoGlobal[keyDetalle].color === null) ? 0 : JSON.stringify(resultadoGlobal[keyDetalle].color).replace(/\"/g, '');
                                    var lineaEspiralDetalle = (resultadoGlobal[keyDetalle].espiral === '' || resultadoGlobal[keyDetalle].espiral === null) ? 0 : JSON.stringify(resultadoGlobal[keyDetalle].espiral).replace(/\"/g, '');
                                    var lineaEncoladoDetalle = (resultadoGlobal[keyDetalle].encolado === '' || resultadoGlobal[keyDetalle].encolado === null) ? 0 : JSON.stringify(resultadoGlobal[keyDetalle].encolado).replace(/\"/g, '');
                                    var lineaVarios1Detalle = (resultadoGlobal[keyDetalle].varios1 === '' || resultadoGlobal[keyDetalle].varios1 === null) ? 0 : JSON.stringify(resultadoGlobal[keyDetalle].varios1).replace(/\"/g, '');
                                    var lineaVarios2Detalle = (resultadoGlobal[keyDetalle].varios2 === '' || resultadoGlobal[keyDetalle].varios2 === null) ? 0 : JSON.stringify(resultadoGlobal[keyDetalle].varios2).replace(/\"/g, '');

                                    total = parseFloat(lineaByNDetalle) +
                                        parseFloat(lineaColorDetalle) +
                                        parseFloat(lineaEspiralDetalle) +
                                        parseFloat(lineaEncoladoDetalle) +
                                        parseFloat(lineaVarios1Detalle) +
                                        parseFloat(lineaVarios2Detalle);

                                    totalFinal = (parseFloat(totalFinal) + parseFloat(total)).toFixed(4);

                                    if (resultadoJSON === '[') {
                                        resultadoJSON += "{ \"parte\" :\"" + parte + "\", \"esb\" :\"" + cecoDetalle +
                                            "\", \"departamento\": \"" + departamentoDetalle + "\", \"subdepartamento\": \"" + subdepartamento +
                                            "\", \"nombre\": \"" + nombre + "\", \"descripcion\": \"" + descripcion +
                                            "\", \"varios1\": \"" + parseFloat(lineaVarios1Detalle).toFixed(4).toString().replace('.', ',') +
                                            "\", \"color\": \"" + parseFloat(lineaColorDetalle).toFixed(4).toString().replace('.', ',') +
                                            "\", \"espiral\": \"" + parseFloat(lineaEspiralDetalle).toFixed(4).toString().replace('.', ',') +
                                            "\", \"encolado\": \"" + parseFloat(lineaEncoladoDetalle).toFixed(4).toString().replace('.', ',') +
                                            "\", \"blancoNegro\": \"" + parseFloat(lineaByNDetalle).toFixed(4).toString().replace('.', ',') +
                                            "\", \"varios2\": \"" + parseFloat(lineaVarios2Detalle).toFixed(4).toString().replace('.', ',') +
                                            "\", \"subtotal\": \"" + parseFloat(total).toFixed(4).toString().replace('.', ',') + "\"}";
                                    } else {
                                        resultadoJSON += ",{ \"parte\" :\"" + parte + "\", \"esb\" :\"" + cecoDetalle +
                                            "\", \"departamento\": \"" + departamentoDetalle + "\", \"subdepartamento\": \"" + subdepartamento +
                                            "\", \"nombre\": \"" + nombre + "\", \"descripcion\": \"" + descripcion +
                                            "\", \"varios1\": \"" + parseFloat(lineaVarios1Detalle).toFixed(4).toString().replace('.', ',') +
                                            "\", \"color\": \"" + parseFloat(lineaColorDetalle).toFixed(4).toString().replace('.', ',') +
                                            "\", \"espiral\": \"" + parseFloat(lineaEspiralDetalle).toFixed(4).toString().replace('.', ',') +
                                            "\", \"encolado\": \"" + parseFloat(lineaEncoladoDetalle).toFixed(4).toString().replace('.', ',') +
                                            "\", \"blancoNegro\": \"" + parseFloat(lineaByNDetalle).toFixed(4).toString().replace('.', ',') +
                                            "\", \"varios2\": \"" + parseFloat(lineaVarios2Detalle).toFixed(4).toString().replace('.', ',') +
                                            "\", \"subtotal\": \"" + parseFloat(total).toFixed(4).toString().replace('.', ',') + "\"}";
                                    }
                                }
                            }
                            resultadoJSON += ",{ \"parte\" :\"" +
                                "\", \"esb\" :\"" +
                                "\", \"departamento\": \"" +
                                "\", \"subdepartamento\": \"" +
                                "\", \"nombre\": \"" +
                                "\", \"descripcion\": \"" +
                                "\", \"varios1\": \"" +
                                "\", \"color\": \"" +
                                "\", \"espiral\": \"" +
                                "\", \"encolado\": \"" +
                                "\", \"blancoNegro\": \"" +
                                "\", \"varios2\": \"" +
                                "\", \"subtotal\": \"" + parseFloat(totalFinal).toFixed(4).toString().replace('.', ',') + "\"}";

                            resultadoJSON += "]";
                            borrarSpin();
                            var date2 = new Date().toISOString();
                            $('#listadoInformeTable').bootstrapTable('destroy');
                            $('#listadoInformeTable').bootstrapTable({
                                // pagination: true,
                                search: true,
                                sortable: true,
                                cache: true,
                                // exportDataType: $('#listadoInformeTable').val(),
                                exportTypes: ['csv', 'excel', 'pdf'],
                                exportOptions: {
                                    fileName: 'Informe Administrador' + date2
                                },
                                columns: [{ field: 'parte', title: 'PARTE' },
                                    { field: 'esb', title: 'ESB' },
                                    { field: 'departamento', title: 'Departamento' },
                                    { field: 'subdepartamento', title: 'Subdepartamento' },
                                    { field: 'nombre', title: 'Solicitante' },
                                    //      { field: 'descripcion', title: 'Descripcion' },
                                    { field: 'varios1', title: 'Varios 1' },
                                    { field: 'color', title: 'Color' },
                                    { field: 'espiral', title: 'Encuadernacion' },
                                    { field: 'encolado', title: 'Encolado' },
                                    { field: 'blancoNegro', title: 'Blanco y Negro' },
                                    { field: 'varios2', title: 'Varios 2' },
                                    { field: 'subtotal', title: 'Total' }
                                ],
                                data: JSON.parse(resultadoJSON)
                            });
                        }
                        $(document).on("click", "button", function() {
                            if (this.id.indexOf('ver-') !== -1) {
                                var parte = this.id.substring(4);
                                direccionEnvio = '../dao/select/consultaDetalleParte.php';
                                valor = "parte=" + parte;

                            }
                        });

                    });
                } else {
                    notifier('Revisa los datos del formulario');
                }
            });
        }
    });

    $("#altaImpresora").click(function() {
        limpiarContenedor();
        $("#contenedor").load("altaImpresora.html");
    });

    $("#bajaImpresora").click(function() {
        limpiarContenedor();
        $("#contenedor").load("bajaImpresora.html");
    });

    $("#modificacionImpresora").click(function() {
        limpiarContenedor();
        $("#contenedor").load("modificacionImpresora.html");
    });

    $("#consultaImpresora").click(function() {
        limpiarContenedor();
        $("#contenedor").load("consultaImpresora.html");

    });

    $("#gastosMaquina").click(function() {
        limpiarContenedor();
        $("#contenedor").load("gastosMaquina.html");
    });

    $("#gastosImpresora").click(function() {
        limpiarContenedor();
        $("#contenedor").load("gastosImpresora.html");

    });

    $("#passwordMaestra").click(function() {
        limpiarContenedor();
        $("#contenedor").load("passwordMaestra.html");
        var formulario = document.activeElement.id;
        if (formulario === 'passwordMaestra') {
            $(document).on("click", "button", function() {
                if (this.id === 'passwordMaestraButton') {
                    if (validaPasswordMaestra()) {
                        direccionEnvio = '../dao/insert/altaPassword.php';
                        valor = "masterPassword1Hidden=" + $("#passwordMaestraText").val() +
                            "&masterPassword2Hidden=" + $("#reenterPasswordText").val();
                        $.post(direccionEnvio, valor, function(response) {
                            response = get_hostname(response);
                            notifier(response.message);
                        });

                    } else {
                        notifier('Revisa los datos del formulario');
                    }
                }
            });
        }
    });

    $("#cerrarMes").click(function() {
        limpiarContenedor();
        $("#contenedor").load("cerrarMes.html");
        var formulario = document.activeElement.id;
        if (formulario === 'cerrarMes') {
            cargarComboPeriodo();
            $(document).on("click", "button", function() {
                if (this.id === 'cerrarMesButton') {
                    if (validaCierre()) {
                        direccionEnvio = '../dao/update/cerrarMes.php';
                        valor = "periodo=" + $("#periodo").val();
                        $.post(direccionEnvio, valor, function(response) {
                            response = get_hostname(response);
                            notifier(response.errorMessage);
                        });
                    } else {
                        notifier('Revisa los datos del formulario');
                    }
                }
            });
        }
    });

    $("#altaArticulo").click(function() {
        limpiarContenedor();
        $("#contenedor").load("altaArticulo.html");

    });

    $("#bajaArticulo").click(function() {
        limpiarContenedor();
        $("#contenedor").load("bajaArticulo.html");

    });

    $("#modificacionArticulo").click(function() {
        limpiarContenedor();
        $("#contenedor").load("modificacionArticulo.html");

    });
});

function cargarSelect(id, parametro) {
    var uppercaseId = id.charAt(0).toUpperCase() + id.slice(1);
    var classToCall = '../dao/select/cargarSelect' + uppercaseId + '.php';
    var valor = null;
    if (parametro !== null) {
        valor = 'valor=' + parametro;
    }

    $.post(classToCall, valor, function(response) {
        response = get_hostname(response);
        var listadoResponse = response.data;
        for (var key in listadoResponse) {
            if (listadoResponse.hasOwnProperty(key)) {
                $('#' + id).append($('<option>', {
                    value: JSON.stringify(listadoResponse[key].id).replace(/\"/g, ''),
                    text: JSON.stringify(listadoResponse[key].nombre).replace(/\"/g, '')
                }));
            }
        }
    });
}

function vaciarSelect(id, parametro) {
    $('#' + id).empty().append('<option selected="selected" value="test">' + parametro + '</option>');
}

function vaciarSelectMultiple(id) {
    $('#' + id + " option[value]").remove();
    $('#' + id + " option:selected").prop("selected", false);

}

function limpiarContenedor() {
    $("#contenedor").empty();
}

function get_hostname(response) {
    try {
        response = JSON.parse(response);
    } catch (exception) {
        response = response;
    }
    return response;

}

function recuperaPrecioByN() {
    var classToCall = '../dao/select/recuperaPrecioByN.php';
    return $.ajax(classToCall, function(response) {
        response = get_hostname(response);
    });
}

function recuperaPrecioColor() {
    var classToCall = '../dao/select/recuperaPrecioColor.php';
    return $.ajax(classToCall, function(response) {
        response = get_hostname(response);
    });
}

function cargarComboPeriodo() {
    var direccionEnvio = '../dao/select/cargarSelectPeriodo.php';
    var optionText = "";
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
        $('#periodo').append(optionText);
    });
}

function notifier(mensaje) {
    new Noty({
        type: 'error',
        layout: 'topRight',
        theme: 'nest',
        text: mensaje,
        timeout: '4000',
        progressBar: true,
        closeWith: ['click'],
        killer: true
    }).show();
}

function successNotifier(mensaje) {
    new Noty({
        type: 'success',
        layout: 'topRight',
        theme: 'nest',
        text: mensaje,
        timeout: '4000',
        progressBar: true,
        closeWith: ['click'],
        killer: true
    }).show();
}

function mostrarSpin() {
    $('.overlayTransparent').show();
    $('.spin').spin();
    $('.spin').spin('show');
}

function borrarSpin() {
    $('.spin').spin('hide');
    $('.overlayTransparent').hide();
}

function validaInformes() {
    var continuar = true;
    if (!$("#detalle").is(':checked') && !$("#global").is(':checked')) {
        continuar = false;
    }

    if ($("#periodo").val() === '0') { continuar = false; }
    return continuar;
}

function validaCierre() {
    var continuar = true;
    if ($("#periodo").val() === '0') { continuar = false; }
    return continuar;
}

function validaPasswordMaestra() {
    var continuar = true;

    if ($("#passwordMaestraText").val() === '' || $("#passwordMaestraText").val() === null) { continuar = false; }
    if ($("#reenterPasswordText").val() === '' || $("#reenterPasswordText").val() === null) { continuar = false; }
    if ($("#passwordMaestraText").val() !== $("#reenterPasswordText").val()) { continuar = false; }

    return continuar;
}

function validaContenidoImpresora(periodo, departamento, unidades, precio, total, tipo) {
    var continuar = true;

    if (periodo === '0') { continuar = false; }
    if (departamento === '' || departamento === null) { continuar = false; }
    if (unidades === '' || unidades === null) { continuar = false; } else {
        var patron = /^\d*$/;
        if (!patron.test(unidades)) {
            continuar = false;
        }
    }
    if (precio === '' || precio === null) { continuar = false; }
    if (total === '' || total === null) { continuar = false; }
    return continuar;
}