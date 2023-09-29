$(document).ready(function($) {

    var solicitudTrabajo = localStorage.getItem('solicitudTrabajo');
    var ceco = "";
    var treinta = "";
    var departamentoId = "";
    var subdepartamentoId = "";

    $(".popup-with-zoom-anim").magnificPopup({
        type: "inline",
        fixedContentPos: false,
        fixedBgPos: true,
        overflowY: "auto",
        closeBtnInside: true,
        preloader: false,
        midClick: true,
        removalDelay: 300,
        mainClass: "my-mfp-zoom-in"
    });

    $.post('../dao/select/cargarNavSolicitud.php', 'solicitud=' + solicitudTrabajo, function(response) {
        response = get_hostname(response);
        var cabecera = response.data;
        for (var key in cabecera) {
            if (cabecera.hasOwnProperty(key)) {
                var nombreSolicitante = JSON.stringify(cabecera[key].nombre).replace(/\"/g, '').trim().toLowerCase();
                var departamentoSolicitante = JSON.stringify(cabecera[key].departamento).replace(/\"/g, '').trim().toLowerCase();
                var subdepartamentoSolicitante = JSON.stringify(cabecera[key].subdepartamento).replace(/\"/g, '').trim().toLowerCase();
                var fechaSolicitante = JSON.stringify(cabecera[key].fecha).replace(/\"/g, '').trim();
                ceco = JSON.stringify(cabecera[key].ceco).replace(/\"/g, '').trim();
                treinta = JSON.stringify(cabecera[key].treinta).replace(/\"/g, '').trim();
                departamentoId = JSON.stringify(cabecera[key].departamentoId).replace(/\"/g, '').trim();
                subdepartamentoId = JSON.stringify(cabecera[key].subdepartamentoId).replace(/\"/g, '').trim();
                $('#navNombre').text(nombreSolicitante.charAt(0).toUpperCase() + nombreSolicitante.slice(1));
                $('#navFecha').text(fechaSolicitante);
                $('#navSubdepartamento').text(subdepartamentoSolicitante.charAt(0).toUpperCase() + subdepartamentoSolicitante.slice(1));
                $('#navDepartamento').text(departamentoSolicitante.charAt(0).toUpperCase() + departamentoSolicitante.slice(1));
            }
        }
        insertaTrabajo(solicitudTrabajo, ceco, treinta, departamentoId, subdepartamentoId);
    });

    cargaSubtotales(solicitudTrabajo);

    $('#capaVarios1').on('click', function(e) {
        $("#capaBoton").css("display", "none");
        $("#tituloSeccion").text("Varios 1");
        $.post('../dao/select/cargarArticulos.php', 'solicitud=' + solicitudTrabajo + '&tipo=3', function(response) {
            var varios1JSON = '[';
            var arrayVarios1 = [];
            response = get_hostname(response);
            var listadoSolicitudes = response.data;
            var resultado = rellenarListado(listadoSolicitudes, varios1JSON, arrayVarios1);
            arrayVarios1 = resultado[1];
            varios1JSON = resultado[0];
            completarTabla(varios1JSON);
        });
    });

    $('#capaVarios2').on('click', function(e) {
        $('#capaBoton').css('display', 'block');
        $("#tituloSeccion").text("Varios 2");
        $.post('../dao/select/cargarArticulos.php', 'solicitud=' + solicitudTrabajo + '&tipo=6', function(response) {
            response = get_hostname(response);
            var arrayVarios2 = [];
            var varios2JSON = '[';
            var listadoSolicitudes = response.data;
            var resultado = rellenarListado(listadoSolicitudes, varios2JSON, arrayVarios2);
            arrayVarios2 = resultado[1];
            varios2JSON = resultado[0];
            completarTabla(varios2JSON);
        });
    });

    $('#capaColor').on('click', function(e) {
        $('#capaBoton').css('display', 'none');
        $("#tituloSeccion").text("Color");
        $.post('../dao/select/cargarArticulos.php', 'solicitud=' + solicitudTrabajo + '&tipo=4', function(response) {
            response = get_hostname(response);
            var arrayColor = [];
            var colorJSON = '[';
            var listadoSolicitudes = response.data;
            var resultado = rellenarListado(listadoSolicitudes, colorJSON, arrayColor);
            arrayColor = resultado[1];
            colorJSON = resultado[0];
            completarTabla(colorJSON);
        });
    });

    $('#capaByN').on('click', function(e) {
        $('#capaBoton').css('display', 'none');
        $("#tituloSeccion").text("Blanco y Negro");
        $.post('../dao/select/cargarArticulos.php', 'solicitud=' + solicitudTrabajo + '&tipo=5', function(response) {
            response = get_hostname(response);
            var arrayByN = [];
            var bynJSON = '[';
            var listadoSolicitudes = response.data;
            var resultado = rellenarListado(listadoSolicitudes, bynJSON, arrayByN);
            arrayByN = resultado[1];
            bynJSON = resultado[0];
            completarTabla(bynJSON);
        });
    });

    $('#capaEncolado').on('click', function(e) {
        $('#capaBoton').css('display', 'none');
        $("#tituloSeccion").text("Encolado");
        $.post('../dao/select/cargarArticulos.php', 'solicitud=' + solicitudTrabajo + '&tipo=2', function(response) {
            response = get_hostname(response);
            var arrayEncolado = [];
            var encoladoJSON = '[';
            var listadoSolicitudes = response.data;
            var resultado = rellenarListado(listadoSolicitudes, encoladoJSON, arrayEncolado);
            arrayEncolado = resultado[1];
            encoladoJSON = resultado[0];
            completarTabla(encoladoJSON);
        });
    });

    $('#capaEspiral').on('click', function(e) {
        $('#capaBoton').css('display', 'none');
        $("#tituloSeccion").text("Espiral");
        $.post('../dao/select/cargarArticulos.php', 'solicitud=' + solicitudTrabajo + '&tipo=1', function(response) {
            response = get_hostname(response);
            var arrayEspiral = [];
            var espiralJSON = '[';
            var listadoSolicitudes = response.data;
            var resultado = rellenarListado(listadoSolicitudes, espiralJSON, arrayEspiral);
            espiralJSON = resultado[0];
            arrayEspiral = resultado[1];
            completarTabla(espiralJSON);
        });
    });

    $(document).on('blur', '.inputCantidad', function() {
        var parametros = this.id.split('-');
        var valorCampo = this.value;
        var valorCampoDestino = parseFloat(valorCampo) * parseFloat(parametros[3]);
        var campoDestino = 'total-' + parametros[1] + '-' + parametros[2];
        $('#' + campoDestino).val(valorCampoDestino.toFixed(2));
        guardarLinea(parametros[1], parametros[2], valorCampo, valorCampoDestino, solicitudTrabajo);
    });

    $('#botonVolver').on('click', function(e) {
        document.location.href = 'homePlantilla.html';
    });

    $('#botonGuardar').on('click', function(e) {
        actualizarEstado('5', solicitudTrabajo);
    });

    $('#botonCerrar').on('click', function(e) {
        actualizarEstado('6', solicitudTrabajo);

    });

    // Append table with add row form on add new button click
    $(".add-new").click(function() {
        var row = '<tr>' +
            '<td><input type="text" class="form-control varios2Extra" name="descripcion" id="descripcion"></td>' +
            '<td><input type="text" class="form-control varios2Extra" name="cantidad" id="cantidad"></td>' +
            '<td><input type="text" class="form-control varios2Extra" name="precio" id="precio"></td>' +
            '<td><input type="text" class="form-control" name="total" id="total" readonly></td>' +
            '</tr>';
        $("#trabajo").append(row);
    });

    $(document).on("blur", ".varios2Extra", function() {
        var enviar = true;
        var nombre = this;
        var numero = 0;
        var valor = 0;
        if (nombre.id === 'descripcion') {
            if (nombre.value === '' || nombre.value === null) {
                enviar = false;
            }
        }
        if (nombre.id === 'cantidad') {
            if (isNaN(nombre.value)) {
                enviar = false;
            } else {
                if (nombre.value % 1 !== 0) {
                    enviar = false;
                } else {
                    numero = nombre.value;
                }
            }
        }
        if (nombre.id === 'precio') {
            if (isNaN(nombre.value)) {
                enviar = false;
            }
        }

        if (enviar) {
            var precioTotal = 0;
            if ($("#cantidad").val() !== '' && $("#precio").val() !== '') {
                precioTotal = (parseFloat($("#cantidad").val()) * parseFloat($("#precio").val())).toFixed(4);
                insertarVarios2Extra($("#descripcion").val(), $("#precio").val());
            }
            $("#total").val(precioTotal);

        }
    });
});

function completarTabla(jsonQuery) {
    $('#trabajo').bootstrapTable('destroy'); //Destroy bootstrap table
    $('#trabajo').bootstrapTable({
        cache: false,
        classes: 'table-bordered table-sm table-striped table-hover table-borderless',
        columns: [
            { field: 'nombreJSON', title: 'Descripcion' },
            { field: 'cajaCantidad', title: 'Cantidad' },
            { field: 'precioJSON', title: 'Precio' },
            { field: 'cajaTotal', title: 'Total' }
        ],
        data: JSON.parse(jsonQuery)
    });
}

function get_hostname(response) {
    try {
        response = JSON.parse(response);
    } catch (excepcion) {
        response = response;
    }
    return response;

}

function insertaTrabajo(solicitud, ceco, treinta, departamentoId, subdepartamentoId) {
    $.post('../dao/select/comprobarTrabajo.php', 'solicitud=' + solicitud, function(response) {
        response = get_hostname(response);
        var listadoTrabajos = response.data;
        var actualizamos = false;
        for (var key in listadoTrabajos) {
            actualizamos = true;
            break;
        }
        if (!actualizamos) {
            $.post('../dao/insert/trabajo.php', 'solicitud=' + solicitud + '&ceco=' + ceco + '&treinta=' + treinta + '&departamento=' + departamentoId + '&subdepartamento=' + subdepartamentoId, function(response) {
                response = get_hostname(response);
                if (response.success !== true) {
                    new Noty({
                        type: 'error',
                        layout: 'topRight',
                        theme: 'nest',
                        text: "Error Creando el Trabajo",
                        timeout: '4000',
                        progressBar: true,
                        closeWith: ['click'],
                        killer: true
                    }).show();
                }
            });
        } else {
            $.post('../dao/update/trabajo.php', 'solicitud=' + solicitud + '&status=4&usuario=' + localStorage.getItem('usuarioId'), function(response) {
                response = get_hostname(response);
                if (response.success !== true) {
                    new Noty({
                        type: 'error',
                        layout: 'topRight',
                        theme: 'nest',
                        text: "Error Actualizando el estado del Trabajo",
                        timeout: '4000',
                        progressBar: true,
                        closeWith: ['click'],
                        killer: true
                    }).show();
                }
            });
        }
    });
}

function actualizarEstado(estado, solicitud) {
    $.post('../dao/update/trabajo.php', 'solicitud=' + solicitud + '&status=' + estado + '&usuario=' + localStorage.getItem('usuarioId'), function(response) {
        response = get_hostname(response);
        if (response.success !== true) {
            new Noty({
                type: 'error',
                layout: 'topRight',
                theme: 'nest',
                text: "Error Actualizando el estado del Trabajo",
                timeout: '4000',
                progressBar: true,
                closeWith: ['click'],
                killer: true
            }).show();
        } else {
            document.location.href = 'homePlantilla.html';
        }
    });
}

function guardarLinea(tipo, detalle, cantidad, total, solicitud) {
    $.post('../dao/select/comprobarLinea.php', 'solicitud=' + solicitud + '&tipo=' + tipo + '&detalle=' + detalle, function(response) {
        response = get_hostname(response);
        var listadoTrabajos = response.data;
        var actualizamos = false;
        for (var key in listadoTrabajos) {
            actualizamos = true;
            break;
        }
        if (actualizamos) {
            $.post('../dao/update/lineaTrabajo.php', 'solicitud=' + solicitud + '&tipo=' + tipo + '&detalle=' + detalle + '&unidades=' + cantidad + '&total=' + total, function(response) {
                response = get_hostname(response);
                if (response.success !== true) {
                    new Noty({
                        type: 'error',
                        layout: 'topRight',
                        theme: 'nest',
                        text: "Error Creando la Linea",
                        timeout: '4000',
                        progressBar: true,
                        closeWith: ['click'],
                        killer: true
                    }).show();
                }
            });
        } else {
            $.post('../dao/insert/lineaTrabajo.php', 'solicitud=' + solicitud + '&tipo=' + tipo + '&detalle=' + detalle + '&unidades=' + cantidad + '&total=' + total, function(response) {
                response = get_hostname(response);
                if (response.success !== true) {
                    new Noty({
                        type: 'error',
                        layout: 'topRight',
                        theme: 'nest',
                        text: "Error Creando la Linea",
                        timeout: '4000',
                        progressBar: true,
                        closeWith: ['click'],
                        killer: true
                    }).show();
                }
            });
        }

        cargaSubtotales(solicitud);
    });
}

function rellenarListado(listadoSolicitudes, listadoJSON, listadoArray) {

    for (var key in listadoSolicitudes) {
        if (listadoSolicitudes.hasOwnProperty(key)) {
            var tipo = JSON.stringify(listadoSolicitudes[key].tipo_id).replace(/\"/g, '').trim();
            var detalle = JSON.stringify(listadoSolicitudes[key].detalle_id).replace(/\"/g, '').trim();
            var descripcion = JSON.stringify(listadoSolicitudes[key].descripcion).replace(/\"/g, '').trim();
            var precio = JSON.stringify(listadoSolicitudes[key].precio).replace(/\"/g, '').trim();
            var linea = { tipoJSON: tipo, detalleJSON: detalle, nombreJSON: descripcion, precioJSON: precio };
            var unidades = JSON.stringify(listadoSolicitudes[key].unidades).replace(/\"/g, '').trim();
            var precioTotal = JSON.stringify(listadoSolicitudes[key].precioTotal).replace(/\"/g, '').trim();

            var cadena = '{\"tipoJSON\":\"' + tipo +
                '\",\"detalleJSON\":\"' + detalle +
                '\",\"nombreJSON\":\"' + descripcion +
                '\",\"precioJSON\":\"' + precio +
                '\",\"cajaCantidad\":\"<input type=\'text\' id=\'precio-' + tipo + '-' + detalle + '-' + precio + '\' value=\'' + unidades + '\' style=\'width: 50px\' class=\'inputCantidad\'>\",' +
                '\"cajaTotal\":\"<input type=\'text\' id=\'total-' + tipo + '-' + detalle + '\' readonly value=\'' + precioTotal + '\' style=\'width: 50px\'>\"}';

            if (listadoJSON !== '[') {
                listadoJSON += ',';
            }

            listadoJSON += cadena;
            listadoArray.push(linea);
        }
    }
    listadoJSON += ']';

    return [listadoJSON, listadoArray];
}

function insertarVarios2Extra(descripcion, precio) {
    var identificador = 0;
    $.post('../dao/insert/altaVarios2.php', 'descripcion=' + descripcion + '&precio=' + precio, function(response) {
        response = get_hostname(response);
        if (response.success !== true) {
            new Noty({
                type: 'error',
                layout: 'topRight',
                theme: 'nest',
                text: "Error Creando la Linea",
                timeout: '4000',
                progressBar: true,
                closeWith: ['click'],
                killer: true
            }).show();
        } else {
            identificador = response.message;
        }

        $("#descripcion").attr("id", "descripcion-7-" + identificador);
        $("#precio").attr("id", "precio-7-" + identificador);
        $("#total").attr("id", "total-7-" + identificador);
        $("#cantidad").attr("id", "cantidad-7-" + identificador);

        guardarLinea('7', identificador, $("#cantidad-7-" + identificador).val(), $("#total-7-" + identificador).val(), localStorage.getItem('solicitudTrabajo'));
        cargaSubtotales(localStorage.getItem('solicitudTrabajo'));
    });
}

function cargaSubtotales(solicitudTrabajo) {
    $.post('../dao/select/subtotalSolicitud.php', 'solicitud=' + solicitudTrabajo, function(response) {
        var precioByN = 0;
        var precioColor = 0;
        var precioEncuadernacion = 0;
        var precioVarios = 0;
        var precioEspiral = 0;
        var precioEncolado = 0;
        var precioVarios1 = 0;
        var precioVarios2 = 0;

        response = get_hostname(response);
        var cabecera = response.data;
        for (var key in cabecera) {
            if (cabecera.hasOwnProperty(key)) {
                var tipo = JSON.stringify(cabecera[key].tipo_id).replace(/\"/g, '').trim();
                var importe = JSON.stringify(cabecera[key].preciototal).replace(/\"/g, '').trim();

                //Espiral
                if (tipo === '1') {

                    precioEspiral = parseFloat(precioEspiral) + parseFloat(importe);
                    precioEncuadernacion = parseFloat(precioEncuadernacion) + parseFloat(importe);
                }
                //Encolado
                if (tipo === '2') {
                    precioEncolado = parseFloat(precioEncolado) + parseFloat(importe);
                    precioEncuadernacion = parseFloat(precioEncuadernacion) + parseFloat(importe);
                }
                //Varios1
                if (tipo === '3') {
                    precioVarios1 = parseFloat(precioVarios1) + parseFloat(importe);
                    precioVarios = parseFloat(precioVarios) + parseFloat(importe);
                }
                //Color
                if (tipo === '4') {
                    precioColor = parseFloat(precioColor) + parseFloat(importe);
                }
                //ByN
                if (tipo === '5') {
                    precioByN = parseFloat(precioByN) + parseFloat(importe);
                }
                //Varios 2
                if (tipo === '6') {
                    precioVarios2 = parseFloat(precioVarios2) + parseFloat(importe);
                    precioVarios = parseFloat(precioVarios) + parseFloat(importe);
                }
                //Varios 2 Extra
                if (tipo === '7') {
                    precioVarios2 = parseFloat(precioVarios2) + parseFloat(importe);
                    precioVarios = parseFloat(precioVarios) + parseFloat(importe);
                }
            }
        }

        $("#color").val(precioColor);
        $("#negro").val(precioByN);
        $("#espiral").val(precioEspiral);
        $("#encolado").val(precioEncolado);
        $("#varios1").val(precioVarios1);
        $("#varios2").val(precioVarios2);
        $("#varios").val(precioVarios);
        $("#encuadernacion").val(precioEncuadernacion);

        $.post('../dao/update/modificaSubtotales.php', 'color=' + precioColor + '&negro=' + precioByN + '&espiral=' + precioEspiral +
            '&encolado=' + precioEncolado + '&varios1=' + precioVarios1 + '&varios2=' + precioVarios2 +
            '&varios=' + precioVarios + '&encuadernacion=' + precioEncuadernacion + '&solicitud=' + solicitudTrabajo,
            function(response) {
                response = get_hostname(response);
                if (response.success !== true) {
                    new Noty({
                        type: 'error',
                        layout: 'topRight',
                        theme: 'nest',
                        text: "Error Creando la Linea",
                        timeout: '4000',
                        progressBar: true,
                        closeWith: ['click'],
                        killer: true
                    }).show();
                }
            });
    });

}