$('#section-filtro').ready(function($) {

    $.post('../dao/select/periodo.php', function(response) {
        var listadoPeriodos = '';
        response = get_hostname(response);
        listadoPeriodos = response.data;
        for (var key in listadoPeriodos) {
            if (listadoPeriodos.hasOwnProperty(key)) {
                var valorTexto = JSON.stringify(listadoPeriodos[key].mes_alta).replace(/\"/g, '') + '/' +
                    JSON.stringify(listadoPeriodos[key].anio_alta).replace(/\"/g, '');
                $('#periodo').append($('<option>', {
                    value: valorTexto,
                    text: valorTexto
                }));
            }
        }
    });

    $.post('../dao/select/asociacionDepartamento.php', function(response) {
        var listadoDepartamentos = '';
        response = get_hostname(response);
        listadoDepartamentos = response.data;
        for (var key in listadoDepartamentos) {
            if (listadoDepartamentos.hasOwnProperty(key)) {
                $('#departamento').append($('<option>', {
                    value: JSON.stringify(listadoDepartamentos[key].id).replace(/\"/g, ''),
                    text: JSON.stringify(listadoDepartamentos[key].nombre).replace(/\"/g, '')
                }));
            }
        }
    });

    $('#departamento').change(function() {
        $('#subdepartamento').empty();
        $('#subdepartamento').append($('<option>', {
            value: 0,
            text: 'Todos los Subdepartamentos'
        }));
        $.post('../dao/select/asociacionDepartamento.php', 'departamento=' + $('#departamento').val(), function(response) {
            var listadoSubdepartamentos = '';
            response = get_hostname(response);
            listadoSubdepartamentos = response.data;

            for (var key in listadoSubdepartamentos) {
                if (listadoSubdepartamentos.hasOwnProperty(key)) {
                    $('#subdepartamento').append($('<option>', {
                        value: JSON.stringify(listadoSubdepartamentos[key].id).replace(/\"/g, ''),
                        text: JSON.stringify(listadoSubdepartamentos[key].nombre).replace(/\"/g, '')
                    }));
                }
            }
        });
    });

    $('#filtroButton').click(function() {
        if (validarFiltros()) {
            $.post('../dao/select/informeGestor.php', $("#filtroGestor").serialize(), function(response) {

                var totalAbsoluto = 0;
                var resultadoGlobal = '';
                response = get_hostname(response);
                resultadoGlobal = response.data;

                var resultadoJSON = "[";

                if ($('#tipoHidden').val() === 'global') {
                    $("#gestorGlobalTable").css("display", "block");
                    $("#gestorDetalleTable").css("display", "none");

                    for (var key in resultadoGlobal) {
                        if (resultadoGlobal.hasOwnProperty(key)) {
                            if (resultadoGlobal[key].ceco !== '') {

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

                                var totalLinea = (parseFloat(lineaByN) + parseFloat(lineaColor) +
                                    parseFloat(lineaEncuadernacion) + parseFloat(lineaEncolado) +
                                    parseFloat(lineaVarios1) + parseFloat(lineaVarios2) +
                                    parseFloat(lineaMaquinas) + parseFloat(lineaImpresoras)).toFixed(4);

                                totalAbsoluto = parseFloat(totalAbsoluto) + parseFloat(totalLinea);

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
                                        "\", \"subtotal\": \"" + parseFloat(totalLinea).toFixed(4).toString().replace('.', ',') + "\"}";
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
                                        "\", \"subtotal\": \"" + parseFloat(totalLinea).toFixed(4).toString().replace('.', ',') + "\"}";
                                }
                            }
                        }
                    }
                    resultadoJSON += ",{ \"esb\" :\"\", \"nombre\": \"\", \"byn\": \"" +
                        "\", \"color\": \"\", \"encuadernacion\": \"\", \"varios\": \"" +
                        "\", \"maquinas\": \"\", \"impresoras\": \"\", \"subtotal\": \"" + parseFloat(totalAbsoluto).toFixed(4).toString().replace('.', ',') + "\"}";

                    resultadoJSON += "]";
                    var d = new Date().toISOString();
                    $('#messageImporte').css('display', 'block');
                    $("#cantidadInforme").text(totalAbsoluto.toFixed(2).toString().replace('.', ','));
                    $('#gestorGlobal').bootstrapTable('destroy'); //Destroy bootstrap table
                    $('#gestorGlobal').bootstrapTable({
                        pagination: true,
                        search: true,
                        sortable: true,
                        cache: false,
                        exportDataType: $('#gestorGlobal').val(),
                        exportTypes: ['csv', 'excel', 'pdf'],
                        exportOptions: {
                            fileName: 'Listado Global Repro-' + d
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
                    $('#gestorGlobal').bootstrapTable('refresh');
                }
                if ($('#tipoHidden').val() === 'detalle') {

                    $("#gestorGlobalTable").css("display", "none");
                    $("#gestorDetalleTable").css("display", "block");

                    for (var keyDetalle in resultadoGlobal) {
                        if (resultadoGlobal.hasOwnProperty(keyDetalle)) {
                            if (resultadoGlobal[keyDetalle].departamentos_desc !== '') {

                                var parte = JSON.stringify(resultadoGlobal[keyDetalle].codigo).replace(/\"/g, '');
                                var cecoDetalle = JSON.stringify(resultadoGlobal[keyDetalle].ceco).replace(/\"/g, '');
                                var departamentoDetalle = JSON.stringify(resultadoGlobal[keyDetalle].departamentos_desc).replace(/\"/g, '');
                                var subdepartamento = JSON.stringify(resultadoGlobal[keyDetalle].subdepartamentos_desc).replace(/\"/g, '');
                                var treinta = JSON.stringify(resultadoGlobal[keyDetalle].treinta).replace(/\"/g, '');

                                var lineaByNDetalle = (resultadoGlobal[keyDetalle].blancoNegro === '' || resultadoGlobal[keyDetalle].blancoNegro === null) ? 0 : JSON.stringify(resultadoGlobal[keyDetalle].blancoNegro).replace(/\"/g, '');
                                var lineaColorDetalle = (resultadoGlobal[keyDetalle].color === '' || resultadoGlobal[keyDetalle].color === null) ? 0 : JSON.stringify(resultadoGlobal[keyDetalle].color).replace(/\"/g, '');
                                var lineaEspiralDetalle = (resultadoGlobal[keyDetalle].espiral === '' || resultadoGlobal[keyDetalle].espiral === null) ? 0 : JSON.stringify(resultadoGlobal[keyDetalle].espiral).replace(/\"/g, '');
                                var lineaEncoladoDetalle = (resultadoGlobal[keyDetalle].encolado === '' || resultadoGlobal[keyDetalle].encolado === null) ? 0 : JSON.stringify(resultadoGlobal[keyDetalle].encolado).replace(/\"/g, '');
                                var lineaVarios1Detalle = (resultadoGlobal[keyDetalle].varios1 === '' || resultadoGlobal[keyDetalle].varios1 === null) ? 0 : JSON.stringify(resultadoGlobal[keyDetalle].varios1).replace(/\"/g, '');
                                var lineaVarios2Detalle = (resultadoGlobal[keyDetalle].varios2 === '' || resultadoGlobal[keyDetalle].varios2 === null) ? 0 : JSON.stringify(resultadoGlobal[keyDetalle].varios2).replace(/\"/g, '');

                                var totalLineaDetalle = parseFloat(lineaByNDetalle) +
                                    parseFloat(lineaColorDetalle) +
                                    parseFloat(lineaEspiralDetalle) +
                                    parseFloat(lineaEncoladoDetalle) +
                                    parseFloat(lineaVarios1Detalle) +
                                    parseFloat(lineaVarios2Detalle);

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
                                if (totalLineaDetalle !== parseFloat(0)) {
                                    totalAbsoluto = parseFloat(totalAbsoluto) + parseFloat(totalLineaDetalle);
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
                                            "\", \"subtotal\": \"" + parseFloat(totalLineaDetalle).toFixed(4).toString().replace('.', ',') + "\"}";
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
                                            "\", \"subtotal\": \"" + parseFloat(totalLineaDetalle).toFixed(4).toString().replace('.', ',') + "\"}";
                                    }
                                }

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
                        "\", \"subtotal\": \"" + parseFloat(totalAbsoluto).toFixed(4).toString().replace('.', ',') + "\"}";

                    resultadoJSON += "]";

                    $('#messageImporte').css('display', 'block');
                    $("#cantidadInforme").text(totalAbsoluto.toFixed(2).toString().replace('.', ','));
                    var fechaD = new Date().toISOString();
                    $('#gestorDetalle').bootstrapTable('destroy'); //Destroy bootstrap table
                    $('#gestorDetalle').bootstrapTable({
                        pagination: true,
                        search: true,
                        sortable: true,
                        cache: false,
                        exportDataType: $('#gestorDetalle').val(),
                        exportTypes: ['csv', 'excel', 'pdf'],
                        exportOptions: {
                            fileName: 'Listado Detallado Repro-' + fechaD
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

            });
            window.location.hash = "section-resultado";
        }
    });

    $('#cerrarSession').on('click', function() {
        window.localStorage.removeItem('usuarioNombre');
        window.localStorage.removeItem('usuarioRole');
        window.localStorage.removeItem('solicitudTrabajo');
        window.localStorage.removeItem('usuarioEmail');
        window.localStorage.removeItem('usuario');
        window.localStorage.removeItem('usuarioId');
        window.location.href = '../index.html';
    });
});

$('#refresh').click(function() {
    $('#gestorGlobal').bootstrapTable('refresh');
});

function validarFiltros() {

    if ($('#periodo').val() === '0') {
        new Noty({
            type: 'error',
            layout: 'topRight',
            theme: 'nest',
            text: 'Debes Seleccionar El Periodo',
            timeout: '4000',
            progressBar: true,
            closeWith: ['click'],
            killer: true
        }).show();
        return false;
    } else {
        $('#periodoHidden').val($('#periodo').val());
        $('#departamentoHidden').val($('#departamento').val());
        $('#subdepartamentoHidden').val($('#subdepartamento').val());
    }
    if (typeof $('input:radio[name=tipoInforme]:checked').val() === "undefined") {
        new Noty({
            type: 'error',
            layout: 'topRight',
            theme: 'nest',
            text: 'Debes Seleccionar El Tipo De Informe',
            timeout: '4000',
            progressBar: true,
            closeWith: ['click'],
            killer: true
        }).show();
        return false;
    } else {
        $('#tipoHidden').val($('input:radio[name=tipoInforme]:checked').val());
    }

    return true;
}

$('#section-resultado').ready(function($) {
    $('#messageImporte').css('display', 'none');
});

function get_hostname(response) {
    try {
        response = JSON.parse(response);
    } catch {
        response = response;
    }
    return response;
}

function detailFormatter(index, row) {
    var html = [];
    if (row.parte !== "treintabarraMaq" && row.parte !== "treintabarraImp") {
        html.push('<span style="font-weight:bold;">Nombre del Solicitante : </span><span id="nombreSolicitanteDF' + index + '"></span><br>');
        html.push('<span style="font-weight:bold;">Descripcion del Solicitante : </span><span id="descripcionDF' + index + '"></span><br>');
        html.push('<span style="font-weight:bold;">Fecha del trabajo : </span><span id="fechaSolicitudDF' + index + '"></span><br>');
        html.push('<span style="font-weight:bold;">Departamento de la Solicitud : </span><span id="departamentoSolicitudDF' + index + '"></span><br>');
        html.push('<span style="font-weight:bold;">Treinta Barra de la Solicitud : </span><span id="treintaSolicitudDF' + index + '"></span><br>');
        html.push('<span style="font-weight:bold;">Subdepartamento de la Solicitud : </span><span id="subdepartamentoSolicitudDF' + index + '"></span><br>');
        html.push('<span style="font-weight:bold;">Ceco de la Solicitud : </span><span id="cecoSolicitudDF' + index + '"></span><br>');
        html.push('<div id="capaVarios1DF' + index + '"><p><span style="font-weight:bold;">Varios 1:</span></p></div>');
        html.push('<div id="capaColorDF' + index + '"><p><span style="font-weight:bold;">Color:</span></p></div>');
        html.push('<div id="capaEncuadernacionesDF' + index + '"><p><span style="font-weight:bold;">Encuadernaciones:</span></p></div>');
        html.push('<div id="capaEncoladoDF' + index + '"><p><span style="font-weight:bold;">Encolado:</span></p></div>');
        html.push('<div id="capaByNDF' + index + '"><p><span style="font-weight:bold;">Blanco y Negro:</span></p></div>');
        html.push('<div id="capaVarios2DF' + index + '"><p><span style="font-weight:bold;">Varios 2:</span></p></div>');
        rellenaDatosDF(row.parte, index);
    }
    return html.join('');
}


function rellenaDatosDF(solicitud, indice) {
    $.post('../dao/select/cargarDetalleSolicitud.php', 'solicitud=' + solicitud, function(response) {
        response = get_hostname(response);
        var cabecera = response.cabecera;
        var varios1 = response.varios1;
        var color = response.color[0];
        var encolado = response.encolado[0];
        var encuadernacion = response.encuadernacion[0];
        var byn = response.byn[0];
        var varios2 = response.varios2[0];
        var varios2Extra = response.varios2Extra[0];
        var lineBreak = document.createElement('br');
        var salto = document.createElement("br");

        for (var key in cabecera) {
            if (cabecera.hasOwnProperty(key)) {
                var nombre = JSON.stringify(cabecera[key][0].nombre).replace(/\"/g, '');
                var fecha = JSON.stringify(cabecera[key][0].fecha).replace(/\"/g, '');
                var departamento = JSON.stringify(cabecera[key][0].departamento).replace(/\"/g, '');
                var treinta = JSON.stringify(cabecera[key][0].treinta).replace(/\"/g, '');
                var subdepartamento = JSON.stringify(cabecera[key][0].subdepartamento).replace(/\"/g, '');
                var ceco = JSON.stringify(cabecera[key][0].ceco).replace(/\"/g, '');
                var descripcion = JSON.stringify(cabecera[key][0].descripcion).replace(/\"/g, '');

                if (descripcion !== "" && descripcion !== null) {
                    descripcion = descripcion.replace(/\\r\\n/g, '.');
                }

                $("#nombreSolicitanteDF" + indice).text(nombre);
                $("#fechaSolicitudDF" + indice).text(fecha);
                $("#departamentoSolicitudDF" + indice).text(departamento);
                $("#treintaSolicitudDF" + indice).text(treinta);
                $("#subdepartamentoSolicitudDF" + indice).text(subdepartamento);
                $("#cecoSolicitudDF" + indice).text(ceco);
                $("#descripcionDF" + indice).text(descripcion);

            }
        }
        var listaVarios1 = varios1[0];

        for (var item in listaVarios1) {
            if (listaVarios1.hasOwnProperty(item)) {
                if (listaVarios1[item].descripcion !== null && listaVarios1[item].descripcion !== undefined) {
                    var material = JSON.stringify(listaVarios1[item].descripcion).replace(/\"/g, '');
                    var precio = JSON.stringify(listaVarios1[item].precio).replace(/\"/g, '');
                    var cantidad = JSON.stringify(listaVarios1[item].unidades).replace(/\"/g, '');
                    var total = JSON.stringify(listaVarios1[item].precioTotal).replace(/\"/g, '');
                    var textoFinal = "Se ha solicitado " + cantidad + " unidad/es de " + material + " que tiene un precio de " + precio.replace('.', ',') + "€ cada unidad por un total de " + total.replace('.', ',') + "€";
                    $("#capaVarios1DF" + indice).append(textoFinal);
                    $("#capaVarios1DF" + indice).append("<br>");
                }
            }
        }

        for (var itemColor in color) {
            if (color.hasOwnProperty(itemColor)) {
                if (color[itemColor].descripcion !== null && color[itemColor].descripcion !== undefined) {
                    var materialColor = JSON.stringify(color[itemColor].descripcion).replace(/\"/g, '');
                    var precioColor = JSON.stringify(color[itemColor].precio).replace(/\"/g, '');
                    var cantidadColor = JSON.stringify(color[itemColor].unidades).replace(/\"/g, '');
                    var totalColor = JSON.stringify(color[itemColor].precioTotal).replace(/\"/g, '');
                    var textoFinalColor = "Se ha solicitado " + cantidadColor + " unidad/es de " + materialColor + " que tiene un precio de " + precioColor.replace('.', ',') + "€ cada unidad por un total de " + totalColor.replace('.', ',') + "€";
                    $("#capaColorDF" + indice).append(textoFinalColor);
                    $("#capaColorDF" + indice).append("<br>");
                }
            }
        }

        for (var itemEncuadernacion in encuadernacion) {
            if (encuadernacion.hasOwnProperty(itemEncuadernacion)) {
                if (encuadernacion[itemEncuadernacion].descripcion !== null && encuadernacion[itemEncuadernacion].descripcion !== undefined) {
                    var materialEncuadernacion = JSON.stringify(encuadernacion[itemEncuadernacion].descripcion).replace(/\"/g, '');
                    var precioEncuadernacion = JSON.stringify(encuadernacion[itemEncuadernacion].precio).replace(/\"/g, '');
                    var cantidadEncuadernacion = JSON.stringify(encuadernacion[itemEncuadernacion].unidades).replace(/\"/g, '');
                    var totaEncuadernacion = JSON.stringify(encuadernacion[itemEncuadernacion].precioTotal).replace(/\"/g, '');
                    var textoFinalEncuadernacion = "Se ha solicitado " + cantidadEncuadernacion + " unidad/es de " + materialEncuadernacion + " que tiene un precio de " + precioEncuadernacion.replace('.', ',') + "€ cada unidad por un total de " + totaEncuadernacion.replace('.', ',') + "€";
                    $("#capaEncuadernacionesDF" + indice).append(textoFinalEncuadernacion);
                    $("#capaEncuadernacionesDF" + indice).append("<br>");
                }
            }
        }

        for (var itemEncolado in encolado) {
            if (encolado.hasOwnProperty(itemEncolado)) {
                if (encolado[itemEncolado].descripcion !== null && encolado[itemEncolado].descripcion !== undefined) {
                    var materialEncolado = JSON.stringify(encolado[itemEncolado].descripcion).replace(/\"/g, '');
                    var precioEncolado = JSON.stringify(encolado[itemEncolado].precio).replace(/\"/g, '');
                    var cantidadEncolado = JSON.stringify(encolado[itemEncolado].unidades).replace(/\"/g, '');
                    var totaEncolado = JSON.stringify(encolado[itemEncolado].precioTotal).replace(/\"/g, '');
                    var textoFinalEncolado = "Se ha solicitado " + cantidadEncolado + " unidad/es de " + materialEncolado + " que tiene un precio de " + precioEncolado.replace('.', ',') + "€ cada unidad por un total de " + totaEncolado.replace('.', ',') + "€";
                    $("#capaEncoladoDF" + indice).append(textoFinalEncolado);
                    $("#capaEncoladoDF" + indice).append("<br>");
                }
            }
        }

        for (var itemByN in byn) {
            if (byn.hasOwnProperty(itemByN)) {
                if (byn[itemByN].descripcion !== null && byn[itemByN].descripcion !== undefined) {
                    var materialByN = JSON.stringify(byn[itemByN].descripcion).replace(/\"/g, '');
                    var precioByN = JSON.stringify(byn[itemByN].precio).replace(/\"/g, '');
                    var cantidadByN = JSON.stringify(byn[itemByN].unidades).replace(/\"/g, '');
                    var totaByN = JSON.stringify(byn[itemByN].precioTotal).replace(/\"/g, '');
                    var textoFinalByN = "Se ha solicitado " + cantidadByN + " unidad/es de " + materialByN + " que tiene un precio de " + precioByN.replace('.', ',') + "€ cada unidad por un total de " + totaByN.replace('.', ',') + "€";
                    $("#capaByNDF" + indice).append(textoFinalByN);
                    $("#capaByNDF" + indice).append("<br>");
                }
            }
        }

        for (var itemVarios2 in varios2) {
            if (varios2.hasOwnProperty(itemVarios2)) {
                if (varios2[itemVarios2].descripcion !== null && varios2[itemVarios2].descripcion !== undefined) {
                    var materialVarios2 = JSON.stringify(varios2[itemVarios2].descripcion).replace(/\"/g, '');
                    var precioVarios2 = JSON.stringify(varios2[itemVarios2].precio).replace(/\"/g, '');
                    var cantidadVarios2 = JSON.stringify(varios2[itemVarios2].unidades).replace(/\"/g, '');
                    var totaVarios2 = JSON.stringify(varios2[itemVarios2].precioTotal).replace(/\"/g, '');
                    var textoFinalVarios2 = "Se ha solicitado " + cantidadVarios2 + " unidad/es de " + materialVarios2 + " que tiene un precio de " + precioVarios2.replace('.', ',') + "€ cada unidad por un total de " + totaVarios2.replace('.', ',') + "€";
                    $("#capaVarios2DF" + indice).append(textoFinalVarios2);
                    $("#capaVarios2DF" + indice).append("<br>");
                }
            }
        }

        for (var itemVarios2Extra in varios2Extra) {
            if (varios2Extra.hasOwnProperty(itemVarios2Extra)) {
                if (varios2Extra[itemVarios2Extra].descripcion !== null && varios2Extra[itemVarios2Extra].descripcion !== undefined) {
                    var materialVarios2Extra = JSON.stringify(varios2Extra[itemVarios2Extra].descripcion).replace(/\"/g, '');
                    var precioVarios2Extra = JSON.stringify(varios2Extra[itemVarios2Extra].precio).replace(/\"/g, '');
                    var cantidadVarios2Extra = JSON.stringify(varios2Extra[itemVarios2Extra].unidades).replace(/\"/g, '');
                    var totaVarios2Extra = JSON.stringify(varios2Extra[itemVarios2Extra].precioTotal).replace(/\"/g, '');
                    var textoFinalVarios2Extra = "Se ha solicitado " + cantidadVarios2Extra + " unidad/es de " + materialVarios2Extra + " que tiene un precio de " + precioVarios2Extra.replace('.', ',') + "€ cada unidad por un total de " + totaVarios2Extra.replace('.', ',') + "€";
                    $("#capaVarios2DF" + indice).append(textoFinalVarios2Extra);
                    $("#capaVarios2DF" + indice).append("<br>");
                }
            }
        }
    });
}