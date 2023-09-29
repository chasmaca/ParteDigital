$('#section-solicitud').ready(function($) {

    var usuarioId = localStorage.getItem('usuarioId');
    var usuarioEmail = localStorage.getItem('usuarioEmail');
    var usuarioRole = localStorage.getItem('usuarioRole');
    var usuarioName = localStorage.getItem('usuarioNombre');

    if (localStorage.getItem('usuarioRole') !== "3") {
        new Noty({
            type: 'error',
            layout: 'topRight',
            theme: 'nest',
            text: 'Rol Incorrecto. Será redirigido a la página de login.',
            timeout: '4000',
            progressBar: true,
            closeWith: ['click'],
            killer: true
        }).show();

        window.setTimeout(function() {
            document.location.href = '../index.html';
        }, 2000);
    }

    $.post('../dao/select/solicitudesAutorizador.php', 'usuario=' + usuarioId, function(response) {
        response = get_hostname(response);
        var listadoSolicitudes = response.data;
        var resultadoJSON = "[";

        for (var key in listadoSolicitudes) {
            if (listadoSolicitudes.hasOwnProperty(key)) {

                var solicitudId = JSON.stringify(listadoSolicitudes[key].solicitud_id).replace(/\"/g, '').trim();
                var departamentoId = JSON.stringify(listadoSolicitudes[key].departamento_id).replace(/\"/g, '').trim();
                var departamentoName = JSON.stringify(listadoSolicitudes[key].departamentos_desc).replace(/\"/g, '').trim();
                var subdepartamentoId = JSON.stringify(listadoSolicitudes[key].subdepartamento_id).replace(/\"/g, '').trim();
                var subdepartamentoName = JSON.stringify(listadoSolicitudes[key].subdepartamentos_desc).replace(/\"/g, '').trim();
                var nombreSolicitante = JSON.stringify(listadoSolicitudes[key].nombre).replace(/\"/g, '').trim();
                var descripcionSolicitante = JSON.stringify(listadoSolicitudes[key].descripcion_solicitante).replace(/\"/g, '').trim();
                var emailSolicitante = JSON.stringify(listadoSolicitudes[key].email_solicitante).replace(/\"/g, '').trim();
                var fechaCreacion = JSON.stringify(listadoSolicitudes[key].fecha_alta).replace(/\"/g, '').trim();
                var textoEnlace = "<a id='aprobarSol_" + solicitudId + "' class='enlace_operacion'>Aprobar</a> / <a id='rechazarSol_" + solicitudId + "' class='enlace_operacion'  data-toggle='modal' data-target='#exampleModalLive'>Rechazar</a>";

                if (resultadoJSON === '[') {
                    resultadoJSON += "{ \"solicitud\" :\"" + solicitudId +
                        "\", \"solicitante\": \"" + nombreSolicitante +
                        "\", \"fecha\": \"" + fechaCreacion +
                        "\", \"departamento\": \"" + departamentoName +
                        "\", \"subdepartamento\": \"" + subdepartamentoName +
                        "\", \"departamentoId\": \"" + departamentoId +
                        "\", \"subdepartamentoId\": \"" + subdepartamentoId +
                        "\", \"comentarios\": \"" + descripcionSolicitante +
                        "\", \"email\": \"" + emailSolicitante +
                        "\", \"acciones\": \"" + textoEnlace + "\"}";
                } else {
                    resultadoJSON += ",{ \"solicitud\" :\"" + solicitudId +
                        "\", \"solicitante\": \"" + nombreSolicitante +
                        "\", \"fecha\": \"" + fechaCreacion +
                        "\", \"departamento\": \"" + departamentoName +
                        "\", \"subdepartamento\": \"" + subdepartamentoName +
                        "\", \"departamentoId\": \"" + departamentoId +
                        "\", \"subdepartamentoId\": \"" + subdepartamentoId +
                        "\", \"comentarios\": \"" + descripcionSolicitante +
                        "\", \"email\": \"" + emailSolicitante +
                        "\", \"acciones\": \"" + textoEnlace + "\"}";
                }
            }
        }
        resultadoJSON += "]";
        $('#listadoSolicitud').bootstrapTable('destroy'); //Destroy bootstrap table
        $('#listadoSolicitud').bootstrapTable({
            pagination: true,
            search: true,
            sortable: true,
            cache: false,
            columns: [
                { field: 'solicitud', title: 'Solicitud' },
                { field: 'solicitante', title: 'Solicitante' },
                { field: 'fecha', title: 'Fecha' },
                { field: 'acciones', title: 'Acciones' }
            ],
            data: JSON.parse(resultadoJSON)
        });
    });

    $.post('../dao/select/informeAutorizadorMesCurso.php', 'usuario=' + usuarioId, function(response) {
        response = get_hostname(response);
        var listadoSolicitudes = response.data;
        var resultadoJSON = "[";
        var totalFinal = 0;
        for (var key in listadoSolicitudes) {
            if (listadoSolicitudes.hasOwnProperty(key)) {
                var solicitudId = JSON.stringify(listadoSolicitudes[key].solicitud_id).replace(/\"/g, '').trim();
                var departamentos_desc = JSON.stringify(listadoSolicitudes[key].departamentos_desc).replace(/\"/g, '').trim();
                var subdepartamento_desc = JSON.stringify(listadoSolicitudes[key].subdepartamento_desc).replace(/\"/g, '').trim();
                var solicitante = JSON.stringify(listadoSolicitudes[key].nombre_solicitante).replace(/\"/g, '').trim() + ' ' + JSON.stringify(listadoSolicitudes[key].apellidos_solicitante).replace(/\"/g, '').trim();
                var descripcionSolicitante = JSON.stringify(listadoSolicitudes[key].descripcion_solicitante).replace(/\"/g, '').trim();
                var validador = JSON.stringify(listadoSolicitudes[key].nombre).replace(/\"/g, '').trim() + ' ' + JSON.stringify(listadoSolicitudes[key].apellido).replace(/\"/g, '').trim();
                var status_desc = JSON.stringify(listadoSolicitudes[key].status_desc).replace(/\"/g, '').trim();
                var precioVarios = JSON.stringify(listadoSolicitudes[key].precioVarios).replace(/\"/g, '').trim();
                var precioByN = JSON.stringify(listadoSolicitudes[key].precioByN).replace(/\"/g, '').trim();
                var precioColor = JSON.stringify(listadoSolicitudes[key].precioColor).replace(/\"/g, '').trim();
                var precioEncuadernacion = JSON.stringify(listadoSolicitudes[key].precioEncuadernacion).replace(/\"/g, '').trim();
                var total = (parseFloat(precioVarios) + parseFloat(precioByN) + parseFloat(precioColor) + parseFloat(precioEncuadernacion)).toFixed(4);
                totalFinal = (parseFloat(totalFinal) + parseFloat(total)).toFixed(4);

                if (resultadoJSON === '[') {
                    resultadoJSON += "{ \"solicitudId\" :\"" + solicitudId +
                        "\", \"departamentos_desc\": \"" + departamentos_desc +
                        "\", \"subdepartamento_desc\": \"" + subdepartamento_desc +
                        "\", \"solicitante\": \"" + solicitante +
                        "\", \"descripcionSolicitante\": \"" + descripcionSolicitante +
                        "\", \"validador\": \"" + validador +
                        "\", \"status_desc\": \"" + status_desc +
                        "\", \"precioVarios\": \"" + precioVarios +
                        "\", \"precioByN\": \"" + precioByN +
                        "\", \"precioColor\": \"" + precioColor +
                        "\", \"precioEncuadernacion\": \"" + precioEncuadernacion + "\"" +
                        ", \"total\":\"" + total.replace('.', ',') + "\"" +
                        " }";
                } else {
                    resultadoJSON += ",{ \"solicitudId\" :\"" + solicitudId +
                        "\", \"departamentos_desc\": \"" + departamentos_desc +
                        "\", \"subdepartamento_desc\": \"" + subdepartamento_desc +
                        "\", \"solicitante\": \"" + solicitante +
                        "\", \"descripcionSolicitante\": \"" + descripcionSolicitante +
                        "\", \"validador\": \"" + validador +
                        "\", \"status_desc\": \"" + status_desc +
                        "\", \"precioVarios\": \"" + precioVarios +
                        "\", \"precioByN\": \"" + precioByN +
                        "\", \"precioColor\": \"" + precioColor +
                        "\", \"precioEncuadernacion\": \"" + precioEncuadernacion + "\"" +
                        ", \"total\":\"" + total.replace('.', ',') + "\"" +
                        " }";
                }
            }
        }

        resultadoJSON += "]";

        $('#listadoMesActual').bootstrapTable('destroy'); //Destroy bootstrap table
        $('#listadoMesActual').bootstrapTable({
            pagination: true,
            search: true,
            sortable: true,
            cache: false,
            columns: [
                { field: 'solicitudId', title: 'Solicitud' },
                { field: 'departamentos_desc', title: 'Departamento' },
                { field: 'subdepartamento_desc', title: 'Subdepartamento' },
                { field: 'solicitante', title: 'Solicitante' },
                { field: 'descripcionSolicitante', title: 'Descripcion' },
                // { field: 'validador', title: 'Validador' },
                //  { field: 'status_desc', title: 'Estado' },
                { field: 'precioVarios', title: 'Importe Varios' },
                { field: 'precioByN', title: 'Importe ByN' },
                { field: 'precioColor', title: 'Importe Color' },
                { field: 'precioEncuadernacion', title: 'Importe Encuadernación' },
                { field: 'total', title: 'TOTAL LINEA', footerFormatter: totalFinal.toString().replace('.', ',') }
            ],
            data: JSON.parse(resultadoJSON)
        });
    });

    $(document).on('click', '.deploy_layer', function() {
        var capaPresentar = this.id.substr(this.id.indexOf('_') + 1);
        var iPresentar = 'i_' + capaPresentar;
        if ($('#' + capaPresentar).css("display") === 'none') {
            $('#' + iPresentar).removeClass("fa-plus");
            $('#' + iPresentar).addClass("fa-minus");
            $('#' + capaPresentar).css("display", "contents");
        } else {
            $('#' + iPresentar).addClass("fa-plus");
            $('#' + iPresentar).removeClass("fa-minus");
            $('#' + capaPresentar).css("display", "none");
        }
    });

    $(document).on('click', '.enlace_operacion', function() {
        var operacion = this.id.substr(0, this.id.indexOf('_'));
        var idSolicitud = this.id.substr(this.id.indexOf('_') + 1);
        if (operacion === 'aprobarSol') {
            $.post('../dao/update/statusSolicitud.php', 'idSolicitud=' + idSolicitud + "&operacion=2", function(response) {
                response = get_hostname(response);
                valorCorreo = "operacion=aprobarSolicitud&solicitud=" + idSolicitud;
                $.post('../dao/operativa/envioMail.php', valorCorreo, function(response) {
                    response = get_hostname(response);
                    document.location.href = "homeAutorizador.html";
                }).fail(function() {
                    document.location.href = "homeAutorizador.html";
                });
                if (response.success === false) {
                    new Noty({
                        type: 'error',
                        layout: 'topRight',
                        theme: 'nest',
                        text: response.message,
                        timeout: '4000',
                        progressBar: true,
                        closeWith: ['click'],
                        killer: true
                    }).show();
                }
            });
        } else {
            $("button").on("click", function() {
                if (this.id === 'comentarioRechazo') {
                    var motivo = $('#razonRechazo').val();
                    $.post('../dao/update/statusSolicitud.php', 'idSolicitud=' + idSolicitud + "&operacion=3&motivo=" + motivo, function(response) {
                        response = get_hostname(response);
                        if (response.success === false) {
                            new Noty({
                                type: 'error',
                                layout: 'topRight',
                                theme: 'nest',
                                text: response.message,
                                timeout: '4000',
                                progressBar: true,
                                closeWith: ['click'],
                                killer: true
                            }).show();
                        } else {
                            if (response.success === true) {
                                valorCorreo = "operacion=rechazarSolicitud&solicitud=" + idSolicitud + "&motivo=" + motivo;
                                $.post('../dao/operativa/envioMail.php', valorCorreo, function(response) {
                                    response = get_hostname(response);
                                    document.location.href = "homeAutorizador.html";
                                }).fail(function() {
                                    document.location.href = "homeAutorizador.html";
                                });
                            }
                        }
                    });
                }
            });
        }
    });
});

$('#section-filtro').ready(function($) {
    $.post('../dao/select/periodo.php', function(response) {
        response = get_hostname(response);
        var listadoPeriodos = response.data;
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
    var usuarioSession = localStorage.getItem('usuarioId');
    $('#usuarioSession').val(usuarioSession);
    $.post('../dao/select/asociacionAutorizador.php', 'autorizador=' + usuarioSession, function(response) {
        response = get_hostname(response);
        var listadoDepartamentos = response.data;
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
            response = get_hostname(response);
            var listadoSubdepartamentos = response.data;
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
            $.post('../dao/select/informeAutorizador.php', $("#filtroAutorizador").serialize(), function(response) {
                var totalAbsoluto = 0;
                response = get_hostname(response);
                var resultadoGlobal = response.data;
                var resultadoJSON = "[";
                var tipoInforme = "";

                for (var keyDetalle in resultadoGlobal) {
                    if (resultadoGlobal.hasOwnProperty(keyDetalle)) {
                        if ($('input:radio[name=tipoInforme]:checked').val() === 'global') {
                            tipoInforme = "global";

                            var ceco = JSON.stringify(resultadoGlobal[keyDetalle].ceco).replace(/\"/g, '').trim();
                            var departamento = JSON.stringify(resultadoGlobal[keyDetalle].departamentos_desc).replace(/\"/g, '');
                            var lineaByN = (resultadoGlobal[keyDetalle].blancoNegro === '') ? 0 : JSON.stringify(resultadoGlobal[keyDetalle].blancoNegro).replace(/\"/g, '');
                            var lineaColor = (resultadoGlobal[keyDetalle].color === '') ? 0 : JSON.stringify(resultadoGlobal[keyDetalle].color).replace(/\"/g, '');
                            var lineaEncuadernacion = (resultadoGlobal[keyDetalle].espiral === '') ? 0 : JSON.stringify(resultadoGlobal[keyDetalle].espiral).replace(/\"/g, '');
                            var lineaEncolado = (resultadoGlobal[keyDetalle].encolado === '') ? 0 : JSON.stringify(resultadoGlobal[keyDetalle].encolado).replace(/\"/g, '');
                            var lineaVarios1 = (resultadoGlobal[keyDetalle].varios1 === '') ? 0 : JSON.stringify(resultadoGlobal[keyDetalle].varios1).replace(/\"/g, '');
                            var lineaVarios2 = (resultadoGlobal[keyDetalle].varios2 === '') ? 0 : JSON.stringify(resultadoGlobal[keyDetalle].varios2).replace(/\"/g, '');

                            var totalLinea = (parseFloat(lineaByN) + parseFloat(lineaColor) +
                                parseFloat(lineaEncuadernacion) + parseFloat(lineaEncolado) +
                                parseFloat(lineaVarios1) + parseFloat(lineaVarios2)).toFixed(4);

                            totalAbsoluto = parseFloat(totalAbsoluto) + parseFloat(totalLinea);

                            if (resultadoJSON === '[') {
                                resultadoJSON += "{ \"esb\" :\"" + ceco + "\", \"nombre\": \"" + departamento +
                                    "\", \"byn\": \"" + parseFloat(lineaByN).toFixed(4).toString().replace('.', ',') +
                                    "\", \"color\": \"" + parseFloat(lineaColor).toFixed(4).toString().replace('.', ',') +
                                    "\", \"encuadernacion\": \"" + parseFloat(lineaEncuadernacion).toFixed(4).toString().replace('.', ',') +
                                    "\", \"encolado\": \"" + parseFloat(lineaEncolado).toFixed(4).toString().replace('.', ',') +
                                    "\", \"varios1\": \"" + parseFloat(lineaVarios1).toFixed(4).toString().replace('.', ',') +
                                    "\", \"varios2\": \"" + parseFloat(lineaVarios2).toFixed(4).toString().replace('.', ',') +
                                    "\", \"subtotal\": \"" + parseFloat(totalLinea).toFixed(4).toString().replace('.', ',') + "\"}";
                            } else {
                                resultadoJSON += ",{ \"esb\" :\"" + ceco + "\", \"nombre\": \"" + departamento +
                                    "\", \"byn\": \"" + parseFloat(lineaByN).toFixed(4).toString().replace('.', ',') +
                                    "\", \"color\": \"" + parseFloat(lineaColor).toFixed(4).toString().replace('.', ',') +
                                    "\", \"encuadernacion\": \"" + parseFloat(lineaEncuadernacion).toFixed(4).toString().replace('.', ',') +
                                    "\", \"encolado\": \"" + parseFloat(lineaEncolado).toFixed(4).toString().replace('.', ',') +
                                    "\", \"varios1\": \"" + parseFloat(lineaVarios1).toFixed(4).toString().replace('.', ',') +
                                    "\", \"varios2\": \"" + parseFloat(lineaVarios2).toFixed(4).toString().replace('.', ',') +
                                    "\", \"subtotal\": \"" + parseFloat(totalLinea).toFixed(4).toString().replace('.', ',') + "\"}";
                            }

                        } else {
                            tipoInforme = "detalle";
                            var parte = JSON.stringify(resultadoGlobal[keyDetalle].solicitud).replace(/\"/g, '');
                            var cecoDetalle = JSON.stringify(resultadoGlobal[keyDetalle].ceco).replace(/\"/g, '');
                            var departamentoDetalle = JSON.stringify(resultadoGlobal[keyDetalle].departamento).replace(/\"/g, '');
                            var subdepartamento = JSON.stringify(resultadoGlobal[keyDetalle].subdepartamento).replace(/\"/g, '');
                            var treinta = JSON.stringify(resultadoGlobal[keyDetalle].treintabarra).replace(/\"/g, '');
                            var fecha = JSON.stringify(resultadoGlobal[keyDetalle].fecha).replace(/\"/g, '');

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

                            totalAbsoluto = (parseFloat(totalAbsoluto) + parseFloat(totalLineaDetalle)).toFixed(4);

                            if (totalLineaDetalle !== parseFloat(0)) {
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

                if (tipoInforme === "detalle") {
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
                } else {
                    resultadoJSON += ",{ \"esb\" :\"\", \"nombre\": \"\", \"byn\": \"" +
                        "\", \"color\": \"\", \"encuadernacion\": \"\", \"varios\": \"" +
                        "\", \"impresoras\": \"\", \"maquinas\": \"\", \"subtotal\": \"" + parseFloat(totalAbsoluto).toFixed(4).toString().replace('.', ',') + "\"}";

                }


                resultadoJSON += "]";

                var d = new Date().toISOString();
                $('#messageImporte').css('display', 'block !important');
                $('#messageImporte').css('color', 'black');
                if (totalAbsoluto === 0) {
                    totalAbsoluto = totalAbsoluto.toFixed(4);
                }
                $("#cantidadInforme").text(totalAbsoluto.toString().replace('.', ','));
                if ($('input:radio[name=tipoInforme]:checked').val() === 'global') {
                    $('#listadoValidador').bootstrapTable('destroy'); //Destroy bootstrap table
                    $('#listadoValidador').bootstrapTable({
                        pagination: true,
                        search: true,
                        sortable: true,
                        cache: false,
                        exportDataType: $('#listadoValidador').val(),
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
                            { field: 'subtotal', title: 'Total' }
                        ],
                        data: JSON.parse(resultadoJSON)
                    });
                } else {
                    $('#listadoValidador').bootstrapTable('destroy'); //Destroy bootstrap table
                    $('#listadoValidador').bootstrapTable({
                        pagination: true,
                        search: true,
                        sortable: true,
                        cache: false,
                        exportDataType: $('#listadoValidador').val(),
                        exportTypes: ['csv', 'excel', 'pdf'],
                        exportOptions: {
                            fileName: 'Listado Detallado Repro-' + d
                        },
                        columns: [
                            { field: 'parte', title: 'PARTE' },
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
            window.location.hash = "section-listado";
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
    $(document).on("change", "select", function() {
        var destino = "sub" + this.id;
        if (this.id.indexOf('departamentoSolicitud') === 0) {
            $.post('../dao/select/asociacionDepartamento.php', 'departamento=' + this.value, function(response) {
                $('#' + destino).empty();
                response = get_hostname(response);
                var listadoSubdepartamentos = response.data;
                for (var key in listadoSubdepartamentos) {
                    if (listadoSubdepartamentos.hasOwnProperty(key)) {
                        var identificador = JSON.stringify(listadoSubdepartamentos[key].id).replace(/\"/g, '');
                        var valor = JSON.stringify(listadoSubdepartamentos[key].nombre).replace(/\"/g, '');
                        if (subdepartamento === identificador) {
                            $('#' + destino).append('<option value="' + identificador + '" selected="selected">' + valor + '</option>');
                        } else {
                            $('#' + destino).append('<option value="' + identificador + '">' + valor + '</option>');
                        }
                    }
                }
            });
        }
    });

    $(document).on("click", "button", function() {
        if (this.id.indexOf('actualizaSolicitud_') === 0) {
            var idSolicitud = this.id.substring(this.id.indexOf('_') + 1);
            var departamentoName = 'departamentoSolicitud' + idSolicitud;
            var subdepartamentoName = 'subdepartamentoSolicitud' + idSolicitud;
            $.post('../dao/update/modificacionSolicitud.php', 'solicitud=' + idSolicitud + '&departamento=' + $('#' + departamentoName).val() + '&subdepartamento=' + $('#' + subdepartamentoName).val(), function(response) {
                response = get_hostname(response);
                new Noty({
                    type: 'error',
                    layout: 'topRight',
                    theme: 'nest',
                    text: response.message,
                    timeout: '4000',
                    progressBar: true,
                    closeWith: ['click'],
                    killer: true
                }).show();
            });
        }
    });
});


function detailFormatterSolicitud(index, row) {
    var html = [];
    var solicitud = row.solicitud;
    html.push('<p><span style="font-weight:bold;">Departamento:</span><select id="departamentoSolicitud' + solicitud + '" name="departamentoSolicitud' + solicitud + '"></select></p>');
    cargarDepartamentos(solicitud, row.departamentoId);
    html.push('<p><span style="font-weight:bold;">Subdepartamento:</span><select id="subdepartamentoSolicitud' + solicitud + '" name="subdepartamentoSolicitud' + solicitud + '"></select></p>');
    cargarSubdepartamentos(solicitud, row.departamentoId, row.subdepartamentoId);
    html.push('<p><span style="font-weight:bold;">Comentarios:</span>' + row.comentarios + '</p>');
    html.push('<p><span style="font-weight:bold;">Email:</span>' + row.email + '</p>');
    html.push('<p><button type="button" value="Actualizar Solicitud" id="actualizaSolicitud_' + solicitud + '" name="actualizaSolicitud_' + solicitud + '" class="btn btn-secondary btn-lg pb_btn-pill smoothscroll">ACTUALIZAR</button>');
    return html.join('');
}

function detailFormatter(index, row) {
    var html = [];
    if (row.parte !== null && row.parte !== undefined) {
        html.push('<span style="font-weight:bold;">Nombre del Solicitante : </span><span id="nombreSolicitanteDF' + index + '"></span><br>');
        html.push('<span style="font-weight:bold;">Fecha de la Solicitud : </span><span id="fechaSolicitudDF' + index + '"></span><br>');
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
        $('#usuarioHidden').val(localStorage.getItem('usuarioId'));
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


function cargarDetalleSolicitud(solicitud) {
    $.post('../dao/select/cargarDetalleSolicitud.php', 'solicitud=' + solicitud, function(response) {
        response = get_hostname(response);
        return response;
    });
}

function get_hostname(response) {
    try {
        response = JSON.parse(response);
    } catch (error) {
        response = response;
    }
    return response;
}

function cargarDepartamentos(solicitud, departamento) {
    var contenidoCombo = "";
    $.post('../dao/select/asociacionAutorizador.php', 'autorizador=' + localStorage.getItem('usuarioId'), function(response) {
        response = get_hostname(response);
        var listadoDepartamentos = response.data;
        for (var key in listadoDepartamentos) {
            if (listadoDepartamentos.hasOwnProperty(key)) {
                var identificador = JSON.stringify(listadoDepartamentos[key].id).replace(/\"/g, '');
                if (departamento === identificador) {
                    $('#departamentoSolicitud' + solicitud).append('<option value="' + identificador + '" selected="selected">' + JSON.stringify(listadoDepartamentos[key].nombre).replace(/\"/g, '') + '</option>');
                } else {
                    $('#departamentoSolicitud' + solicitud).append('<option value="' + identificador + '">' + JSON.stringify(listadoDepartamentos[key].nombre).replace(/\"/g, '') + '</option>');
                }
            }
        }
    });
}

function cargarSubdepartamentos(solicitud, departamento, subdepartamento) {
    $.post('../dao/select/asociacionDepartamento.php', 'departamento=' + departamento, function(response) {
        response = get_hostname(response);
        var listadoSubdepartamentos = response.data;
        for (var key in listadoSubdepartamentos) {
            if (listadoSubdepartamentos.hasOwnProperty(key)) {
                var identificador = JSON.stringify(listadoSubdepartamentos[key].id).replace(/\"/g, '');
                if (subdepartamento === identificador) {
                    $('#subdepartamentoSolicitud' + solicitud).append('<option value="' + identificador + '" selected="selected">' + JSON.stringify(listadoSubdepartamentos[key].nombre).replace(/\"/g, '') + '</option>');
                } else {
                    $('#subdepartamentoSolicitud' + solicitud).append('<option value="' + identificador + '">' + JSON.stringify(listadoSubdepartamentos[key].nombre).replace(/\"/g, '') + '</option>');
                }
            }
        }
    });
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

                $("#nombreSolicitanteDF" + indice).text(nombre);
                $("#fechaSolicitudDF" + indice).text(fecha);
                $("#departamentoSolicitudDF" + indice).text(departamento);
                $("#treintaSolicitudDF" + indice).text(treinta);
                $("#subdepartamentoSolicitudDF" + indice).text(subdepartamento);
                $("#cecoSolicitudDF" + indice).text(ceco);

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
                    var textoFinal = "Se ha solicitado " + cantidad + " unidad/es de " + material + " que tiene un precio de " + precio + "€ cada unidad por un total de " + total + "€";
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
                    var textoFinalColor = "Se ha solicitado " + cantidadColor + " unidad/es de " + materialColor + " que tiene un precio de " + precioColor + "€ cada unidad por un total de " + totalColor + "€";
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
                    var textoFinalEncuadernacion = "Se ha solicitado " + cantidadEncuadernacion + " unidad/es de " + materialEncuadernacion + " que tiene un precio de " + precioEncuadernacion + "€ cada unidad por un total de " + totaEncuadernacion + "€";
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
                    var textoFinalEncolado = "Se ha solicitado " + cantidadEncolado + " unidad/es de " + materialEncolado + " que tiene un precio de " + precioEncolado + "€ cada unidad por un total de " + totaEncolado + "€";
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
                    var textoFinalByN = "Se ha solicitado " + cantidadByN + " unidad/es de " + materialByN + " que tiene un precio de " + precioByN + "€ cada unidad por un total de " + totaByN + "€";
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
                    var textoFinalVarios2 = "Se ha solicitado " + cantidadVarios2 + " unidad/es de " + materialVarios2 + " que tiene un precio de " + precioVarios2 + "€ cada unidad por un total de " + totaVarios2 + "€";
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
                    var textoFinalVarios2Extra = "Se ha solicitado " + cantidadVarios2Extra + " unidad/es de " + materialVarios2Extra + " que tiene un precio de " + precioVarios2Extra + "€ cada unidad por un total de " + totaVarios2Extra + "€";
                    $("#capaVarios2DF" + indice).append(textoFinalVarios2Extra);
                    $("#capaVarios2DF" + indice).append("<br>");
                }
            }
        }
    });
}