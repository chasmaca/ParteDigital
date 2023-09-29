$(document).ready(function() {
    //Variable global con el id de solicitud
    var solicitudId = localStorage.getItem('solicitudTrabajo');
    var solicitudTrabajo = localStorage.getItem('solicitudTrabajo');

    //Variables con los mensajes de error
    var mensajeEstado = "Problemas con la actualizacion de estado de la solicitud, actualizar la p\u00E1gina.";
    var mensajeDepartamento = "Problemas con la recuperacion de los datos del departamento, actualizar la p\u00E1gina.";
    var mensajeSubDepartamento = "Problemas con la recuperacion de los datos del subdepartamento, actualizar la p\u00E1gina.";
    var mensajeSolicitante = "Problemas con la recuperacion de los datos del solicitante, actualizar la p\u00E1gina.";
    var mensajeVarios1 = "Problemas con la recuperacion de los datos Varios 1 para la solicitud, actualizar la p\u00E1gina.";
    var mensajeColor = "Problemas con la recuperacion de los datos Color para la solicitud, actualizar la p\u00E1gina.";
    var mensajeEspiral = "Problemas con la recuperacion de los datos Espiral para la solicitud, actualizar la p\u00E1gina.";
    var mensajeEncolado = "Problemas con la recuperacion de los datos Encolado para la solicitud, actualizar la p\u00E1gina.";
    var mensajeTrabajo = "Error al actualizar el trabajo, por favor, actualizar la p\u00E1gina.";
    var mensajeByN = "Problema al recuperar el listado de Blanco y Negro con los datos de la solicitud, por favor, actualizar la p\u00E1gina.";
    var mensajeVarios2 = "Problema al recuperar el listado de Varios2 con los datos de la solicitud, por favor, actualizar la p\u00E1gina.";
    var mensajeSubtotalVarios1 = "Problema al calcular el subtotal de Varios1, por favor, recarga la p\u00E1gina.";
    var mensajeNumerico = "Debe incluir un valor num\u00E9rico.";
    var mensajeSubtotalColor = "Problema al calcular el subtotal de Color, por favor, recarga la p\u00E1gina.";
    var mensajeSubtotalByN = "Problema al calcular el subtotal de Blanco y Negro, por favor, recarga la p\u00E1gina.";
    var mensajeSubtotalEncolado = "Problema al calcular el subtotal de Encolado, por favor, recarga la p\u00E1gina.";
    var mensajeSubtotalEspiral = "Problema al calcular el subtotal de Espiral, por favor, recarga la p\u00E1gina.";
    var mensajeGuardar = "Problema al guardar el trabajo, por favor, recarga la p\u00E1gina.";
    var mensajeSubtotales = "Problema al calcular los subtotales, por favor, recarga la p\u00E1gina.";
    var mensajeSumaVarios2 = "Problema al a\u00F1adir la nueva linea de Varios 2, por favor, recarga la p\u00E1gina.";
    var mensajeExisteVarios2 = "Se ha a\u00F1adido esta opci\u00F3n con anterioridad, por favor, revisa las lineas ya creadas.";
    var mensajeSubtotalVarios2 = "Problema al calcular el subtotal de Varios 2, por favor, recarga la p\u00E1gina.";
    var mensajeDescripcion = "Debe rellenar la descripci\u00F3n.";

    //Llamadas Ajax
    var recuperaDepartamento = function() {
        return $.getJSON("../dao/select/departamentoPorSolicitud.php", {
            "solicitudId": solicitudId
        });
    };

    var recuperaSubDepartamento = function() {
        return $.getJSON("../dao/select/subdepartamentoPorSolicitud.php", {
            "solicitudId": solicitudId
        });
    };

    var recuperaSolicitante = function() {
        return $.getJSON("../dao/select/solicitantePorSolicitud.php", {
            "solicitudId": solicitudId
        });
    };

    var recuperaVarios1 = function() {
        return $.getJSON("../dao/select/variosUnoJSON.php", {
            "solicitudId": solicitudId
        });
    };

    var recuperaVarios2 = function() {
        return $.getJSON("../dao/select/variosDosJSON.php", {
            "solicitudId": solicitudId,
            "accion": "combo"
        });
    };

    var recuperaByN = function() {
        return $.getJSON("../dao/select/blancoNegroJSON.php", {
            "solicitudId": solicitudId
        });
    };

    var recuperaColor = function() {
        return $.getJSON("../dao/select/colorJSON.php", {
            "solicitudId": solicitudId
        });
    };

    var recuperaEncolado = function() {
        return $.getJSON("../dao/select/encoladoJSON.php", {
            "solicitudId": solicitudId
        });
    };

    var recuperaEncuadernacion = function() {
        return $.getJSON("../dao/select/encuadernacionJSON.php", {
            "solicitudId": solicitudId
        });
    };

    var recuperaDetalleVarios2 = function(id) {
        return $.getJSON("../dao/select/detalleVarios2JSON.php", {
            "id": id
        });
    };

    var cargaVarios2 = function() {
        return $.getJSON("../dao/select/variosDosJSON.php", {
            "solicitudId": solicitudId,
            "accion": "tabla"
        });
    };

    var cargaVarios2Extra = function() {
        return $.getJSON("../dao/select/variosDosExtraJSON.php", {
            "solicitudId": solicitudId,
            "accion": "tabla"
        });
    };

    var operarVarios1 = function(detalle, unidades, total) {
        return $.getJSON("../dao/insert/colorJSON.php", {
            "solicitudId": solicitudId,
            "tipo": "3",
            "detalle": detalle,
            "unidades": unidades,
            "total": parseFloat(total).toFixed(4)
        });
    };

    var operarColor = function(detalle, unidades, total) {
        return $.getJSON("../dao/insert/colorJSON.php", {
            "solicitudId": solicitudId,
            "tipo": "4",
            "detalle": detalle,
            "unidades": unidades,
            "total": parseFloat(total).toFixed(4)
        });
    };

    var operarBlancoYNegro = function(detalle, unidades, total) {
        return $.getJSON("../dao/insert/colorJSON.php", {
            "solicitudId": solicitudId,
            "tipo": "5",
            "detalle": detalle,
            "unidades": unidades,
            "total": parseFloat(total).toFixed(4)
        });
    };

    var operarEncolado = function(detalle, unidades, total) {
        return $.getJSON("../dao/insert/colorJSON.php", {
            "solicitudId": solicitudId,
            "tipo": "2",
            "detalle": detalle,
            "unidades": unidades,
            "total": parseFloat(total).toFixed(4)
        });
    };

    var operarEncuadernacion = function(detalle, unidades, total) {
        return $.getJSON("../dao/insert/colorJSON.php", {
            "solicitudId": solicitudId,
            "tipo": "1",
            "detalle": detalle,
            "unidades": unidades,
            "total": parseFloat(total).toFixed(4)
        });
    };

    var operarVarios2Combo = function(detalle, unidades, total) {
        return $.getJSON("../dao/insert/colorJSON.php", {
            "solicitudId": solicitudId,
            "tipo": "6",
            "detalle": detalle,
            "unidades": unidades,
            "total": parseFloat(total).toFixed(4)
        });
    };

    var operarVarios2 = function(descripcion, unidades, precio, total) {
        return $.getJSON("../dao/insert/varios2JSON.php", {
            "solicitudId": solicitudId,
            "tipo": "7",
            "descripcion": descripcion,
            "unidades": unidades,
            "precio": parseFloat(precio).toFixed(4),
            "total": parseFloat(total).toFixed(4)
        });
    };

    var subtotalVarios2 = function() {
        return true;
    };

    var changeStatus = function(status) {
        return $.getJSON("../dao/update/solicitudStatusJSON.php", {
            "status": status,
            "solicitudId": solicitudId
        });

    };

    var insertaTrabajo = function() {
        return $.getJSON("../dao/insert/trabajo-old.php", {
            "solicitudId": solicitudId
        });
    };

    //cambiarSubTotales(varios1,varios2,color, blanco, totalEncuadernacion).done(function(response){
    var cambiarSubTotales = function(varios1, varios2, color, byn, espiral, encolado) {
        return $.getJSON("../dao/update/subtotalesTrabajo.php", {
            "solicitudId": solicitudId,
            "varios1": parseFloat(varios1).toFixed(4),
            "varios2": parseFloat(varios2).toFixed(4),
            "color": parseFloat(color).toFixed(4),
            "byn": parseFloat(byn).toFixed(4),
            "espiral": parseFloat(espiral).toFixed(4),
            "encolado": parseFloat(encolado).toFixed(4)
        });
    };

    /**
     * Cambiamos de estado al acceder a la ficha.
     */
    changeStatus(4).done(function(response) {

            if (!response.success) {
                error(mensajeEstado);
            } else {
                $("#error").hide();
            }
        }) //Error en la consulta o comunicacion con la bbdd.
        .fail(function(jqXHR, textStatus, errorThrown) {
            error(mensajeEstado);
        });

    /**
     * Recuperamos el departamento y el ceco
     */
    recuperaDepartamento().done(function(response) {
            if (!response.success) {
                error(mensajeDepartamento);
            } else {
                var array = $.map(response.data, function(value, index) {
                    return [value];
                });
                if (array.length == 0) {
                    error(mensajeDepartamento);
                } else {
                    departamentoId = array[0].departamento_id;
                    $("#departamentoLabel").append("<b>" + array[0].departamentos_desc + "</b>");
                    $("#cecoLabel").append("<b>" + array[0].ceco + "</b>");
                }
            }
        }) //Error en la consulta o comunicacion con la bbdd.
        .fail(function(jqXHR, textStatus, errorThrown) {
            error(mensajeDepartamento);
        });

    /**
     * Recuperamos el departamento y el ceco
     */
    recuperaSubDepartamento().done(function(response) {
            if (!response.success) {
                error(mensajeSubDepartamento);
            } else {
                var array = $.map(response.data, function(value, index) {
                    return [value];
                });
                if (array.length == 0) {
                    error(mensajeSubDepartamento);
                } else {
                    treinta = array[0].treintaBarra;
                    $("#subdepartamentoLabel").append("<b>" + array[0].subdepartamentos_desc + "</b>");
                    $("#proyectoLabel").append("<b>" + array[0].treintaBarra + "</b>");
                    var meses = new Array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
                    var diasSemana = new Array("Domingo", "Lunes", "Martes", "Mi\u00E9rcoles", "Jueves", "Viernes", "S\u00E1bado");
                    var f = new Date();
                    $("#fechaLabel").append("<b>" + diasSemana[f.getDay()] + ", " + f.getDate() + " de " + meses[f.getMonth()] + " de " + f.getFullYear() + "</b>");
                }
            }
        }) //Error en la consulta o comunicacion con la bbdd.
        .fail(function(jqXHR, textStatus, errorThrown) {
            error(mensajeSubDepartamento);
        });

    recuperaSolicitante().done(function(response) {
            if (!response.success) {
                error(mensajeSolicitante);
            } else {
                var array = $.map(response.data, function(value, index) {
                    return [value];
                });
                if (array.length == 0) {
                    error(mensajeSolicitante);
                } else {
                    $("#solicitanteLabel").append("<b>" + array[0].nombre + "</b>");
                }
            }
        }) //Error en la consulta o comunicacion con la bbdd.
        .fail(function(jqXHR, textStatus, errorThrown) {
            error(mensajeSolicitante);
        });

    /**
     * Recuperamos el listado de varios1 con los datos de la solicitud
     */
    recuperaVarios1().done(function(response) {
            if (!response.success) {
                error(mensajeVarios1);
            } else {
                var array = $.map(response.data, function(value, index) {
                    return [value];
                });
                if (array.length == 0) {
                    error(mensajeVarios1);
                } else {
                    for (var x = 0; x < array.length; x++) {

                        var unidades = array[x].unidades;

                        var total = array[x].precioTotal;

                        if (unidades == null)
                            unidades = 0;

                        if (total == null)
                            total = 0;
                        else
                            total = parseFloat(array[x].precioTotal).toFixed(4);

                        var precioCopia = parseFloat(array[x].precio).toFixed(4);

                        var newRowContent = "<tr class='trStyle'>" +
                            "<td>" + array[x].descripcion + "</td>" +
                            "<td><input type='text' id='unidades_" + array[x].detalle + "' value='" + unidades + "' style='width:100%' class='varios1' onkeypress='return (event.charCode >= 46 && event.charCode <= 57)'/></td>" +
                            "<td><span id='precio_" + array[x].detalle + "_varios1'>" + precioCopia + "</span></td>" +
                            "<td><input type='text' id='total_" + array[x].detalle + "_varios1' value='" + total + "' style='width:100%' readOnly='true' class='totalVarios1'/></td>" +
                            "</tr>";

                        if (newRowContent != null)
                            $(newRowContent).appendTo($("#varios1Table"));
                    }
                    var subtotalVarios1 = "<tr class='trStyle'>" +
                        "<td colspan='3'><div style='float: right;'>Subtotal:</div></td>" +
                        "<td><input type='text' id='subtotalVarios1' style='width:100%' readOnly='true' class='subtotal'/></td>" +
                        "</tr>";

                    $(subtotalVarios1).appendTo($("#varios1Table"));

                    setSubTotalVarios1();
                }
            }
        }) //Error en la consulta o comunicacion con la bbdd.
        .fail(function(jqXHR, textStatus, errorThrown) {
            error(mensajeVarios1);
        });

    /**
     * Recuperamos el listado de Color con los datos de la solicitud
     */
    recuperaColor().done(function(response) {
        if (!response.success) {
            error(mensajeColor);
        } else {
            var array = $.map(response.data, function(value, index) {
                return [value];
            });
            if (array.length == 0) {
                error(mensajeColor);
            } else {
                for (var x = 0; x < array.length; x++) {
                    var unidades = array[x].unidades;
                    var total = array[x].precioTotal;

                    if (unidades == null)
                        unidades = 0;

                    if (total == null)
                        total = 0;
                    else
                        total = parseFloat(array[x].precioTotal).toFixed(4);

                    var precioCopia = parseFloat(array[x].precio).toFixed(4);
                    var newRowContent = "<tr class='trStyle'>" +
                        "<td>" + array[x].descripcion + "</td>" +
                        "<td><input type='text' id='unidades_" + array[x].detalle + "' value='" + unidades + "' style='width:100%' class='color'  onkeypress='return (event.charCode >= 46 && event.charCode <= 57)'/></td>" +
                        "<td><span id='precio_" + array[x].detalle + "_color'>" + precioCopia + "</span></td>" +
                        "<td><input type='text' id='total_" + array[x].detalle + "_color' value='" + total + "' style='width:100%' readOnly='true' class='totalColor'/></td>" +
                        "</tr>";

                    if (newRowContent != null)
                        $(newRowContent).appendTo($("#colorTable"));
                }
                var subtotalColor = "<tr class='trStyle'>" +
                    "<td colspan='3'><div style='float: right;'>Subtotal:</div></td>" +
                    "<td><input type='text' id='subtotalColor' style='width:100%' readOnly='true' class='subtotal'/></td>" +
                    "</tr>";
                $(subtotalColor).appendTo($("#colorTable"));
                setSubTotalColor();
            }
        }
    }).fail(function(jqXHR, textStatus, errorThrown) {
        error(mensajeColor);
    });

    /**
     * Recuperamos el listado de Encuadernacion con los datos de la solicitud
     */
    recuperaEncuadernacion().done(function(response) {
        if (!response.success) {
            error(mensajeEspiral);
        } else {
            var array = $.map(response.data, function(value, index) {
                return [value];
            });
            if (array.length == 0) {
                error(mensajeEspiral);
            } else {
                for (var x = 0; x < array.length; x++) {
                    var unidades = array[x].unidades;
                    var total = array[x].precioTotal;

                    if (unidades == null)
                        unidades = 0;

                    if (total == null)
                        total = 0;
                    else
                        total = parseFloat(array[x].precioTotal).toFixed(4);

                    var precioCopia = parseFloat(array[x].precio).toFixed(4);
                    var newRowContent = "<tr class='trStyle'>" +
                        "<td>" + array[x].descripcion + "</td>" +
                        "<td><input type='text' id='unidades_" + array[x].detalle + "' value='" + unidades + "' style='width:100%' class='espiral'  onkeypress='return (event.charCode >= 46 && event.charCode <= 57)'/></td>" +
                        "<td><span id='precio_" + array[x].detalle + "_espiral'>" + precioCopia + "</span></td>" +
                        "<td><input type='text' id='total_" + array[x].detalle + "_espiral' value='" + total + "' style='width:100%' readOnly='true' class='totalEncuadernacion'/></td>" +
                        "</tr>";

                    if (newRowContent != null)
                        $(newRowContent).appendTo($("#encuadernacionTable"));
                }
                var subtotalEncuadernacion = "<tr class='trStyle'>" +
                    "<td colspan='3'><div style='float: right;'>Subtotal:</div></td>" +
                    "<td><input type='text' id='subtotalEncuadernacion' style='width:100%' readOnly='true' class='subtotal'/></td>" +
                    "</tr>";
                $(subtotalEncuadernacion).appendTo($("#encuadernacionTable"));
                setSubTotalEncuadernacion();
            }
        }
    }).fail(function(jqXHR, textStatus, errorThrown) {
        error(mensajeEspiral);
    });

    /**
     * Recuperamos el listado de Encolado con los datos de la solicitud
     */
    recuperaEncolado().done(function(response) {
        if (!response.success) {
            error(mensajeEncolado);
        } else {
            var array = $.map(response.data, function(value, index) {
                return [value];
            });
            if (array.length == 0) {
                error(mensajeEncolado);
            } else {
                for (var x = 0; x < array.length; x++) {
                    var unidades = array[x].unidades;
                    var total = array[x].precioTotal;

                    if (unidades == null)
                        unidades = 0;

                    if (total == null)
                        total = 0;
                    else
                        total = parseFloat(array[x].precioTotal).toFixed(4);

                    var precioCopia = parseFloat(array[x].precio).toFixed(4);

                    var newRowContent = "<tr class='trStyle'>" +
                        "<td>" + array[x].descripcion + "</td>" +
                        "<td><input type='text' id='unidades_" + array[x].detalle + "' value='" + unidades + "' style='width:100%' class='encolado'  onkeypress='return (event.charCode >= 46 && event.charCode <= 57)'/></td>" +
                        "<td><span id='precio_" + array[x].detalle + "_encolado'>" + precioCopia + "</span></td>" +
                        "<td><input type='text' id='total_" + array[x].detalle + "_encolado' value='" + total + "' style='width:100%' readOnly='true'  class='totalEncolado'/></td>" +
                        "</tr>";

                    if (newRowContent != null)
                        $(newRowContent).appendTo($("#encoladoTable"));
                }
                var subtotalEncolado = "<tr class='trStyle'>" +
                    "<td colspan='3'><div style='float: right;'>Subtotal:</div></td>" +
                    "<td><input type='text' id='subtotalEncolado' style='width:100%' readOnly='true' class='subtotal'/></td>" +
                    "</tr>";
                $(subtotalEncolado).appendTo($("#encoladoTable"));
                setSubTotalEncolado();
            }
        }
    }).fail(function(jqXHR, textStatus, errorThrown) {
        error(mensajeEncolado);
    });

    insertaTrabajo().done(function(response) {
        if (!response.success) {
            error(mensajeTrabajo);
        }
    }).fail(function(jqXHR, textStatus, errorThrown) {
        error(mensajeTrabajo);
    });


    /**
     * Recuperamos el listado de Blanco y Negro con los datos de la solicitud
     */
    recuperaByN().done(function(response) {
        if (!response.success) {
            error(mensajeByN);
        } else {
            var array = $.map(response.data, function(value, index) {
                return [value];
            });
            if (array.length == 0) {
                error(mensajeByN);
            } else {
                for (var x = 0; x < array.length; x++) {
                    var unidades = array[x].unidades;
                    var total = array[x].precioTotal;

                    if (unidades == null)
                        unidades = 0;

                    if (total == null)
                        total = 0;
                    else
                        total = parseFloat(array[x].precioTotal).toFixed(4);

                    var precioCopia = parseFloat(array[x].precio).toFixed(4);

                    var newRowContent = "<tr class='trStyle'>" +
                        "<td>" + array[x].descripcion + "</td>" +
                        "<td><input type='text' id='unidades_" + array[x].detalle + "' value='" + unidades + "' style='width:100%' class='blanco'  onkeypress='return (event.charCode >= 46 && event.charCode <= 57)'/></td>" +
                        "<td><span id='precio_" + array[x].detalle + "_blanco'>" + precioCopia + "</span></td>" +
                        "<td><input type='text' id='total_" + array[x].detalle + "_blanco' value='" + total + "' style='width:100%' readOnly='true' class='totalByN'/></td>" +
                        "</tr>";

                    if (newRowContent != null)
                        $(newRowContent).appendTo($("#blancoTable"));
                }
                var subtotalBlanco = "<tr class='trStyle'>" +
                    "<td colspan='3'><div style='float: right;'>Subtotal:</div></td>" +
                    "<td><input type='text' id='subtotalByN' style='width:100%' readOnly='true' class='subtotal'/></td>" +
                    "</tr>";
                $(subtotalBlanco).appendTo($("#blancoTable"));
                setSubTotalByN();
            }
        }
    }).fail(function(jqXHR, textStatus, errorThrown) {
        error(mensajeByN);
    });

    /**
     * Recuperamos el listado de Varios2 con los datos de la solicitud
     */
    recuperaVarios2().done(function(response) {
        if (!response.success) {
            error(mensajeVarios2);
        } else {
            var array = $.map(response.data, function(value, index) {
                return [value];
            });
            if (array.length == 0) {
                error(mensajeVarios2);
            } else {
                for (var x = 0; x < array.length; x++) {
                    $('#varios2Select').append($("<option></option>").attr("value", array[x].detalle).text(array[x].descripcion));
                }
            }
        }
    }).fail(function(jqXHR, textStatus, errorThrown) {
        error(mensajeVarios2);
    });

    /**
     * Cargamos Varios2
     */
    cargaVarios2().done(function(response) {
        if (!response.success) {
            error(mensajeVarios2);
        } else {
            var array = $.map(response.data, function(value, index) {
                return [value];
            });
            if (array.length > 0) {
                for (var x = 0; x < array.length; x++) {
                    var unidades = array[x].unidades;
                    var total = array[x].precioTotal;

                    if (unidades == null)
                        unidades = 0;

                    if (total == null)
                        total = 0;
                    else
                        total = parseFloat(array[x].precioTotal).toFixed(4);

                    if (total != 0 && unidades != 0) {
                        var precioCopia = parseFloat(array[x].precio).toFixed(4);

                        var newRowContent = "<tr class='trStyle'>" +
                            "<td><span id='descripcionVarios2' class='descripcionVarios2'>" + array[x].descripcion + "</span></td>" +
                            "<td><input type='text' id='unidades_" + array[x].detalle + "' value='" + unidades + "' style='width:100%' class='varios2Update'/></td>" +
                            "<td>" + precioCopia + "</td>" +
                            "<td><input type='text' id='totalVarios2_" + array[x].detalle + "' value='" + total + "' style='width:100%' class='totalVarios2' readonly/></td>" +
                            "</tr>";

                        if (newRowContent != null)
                            $(newRowContent).appendTo($("#tablaVarios2"));
                    }
                }
            }
            setSubTotalVarios2();
        }
    }).fail(function(jqXHR, textStatus, errorThrown) {
        error(mensajeVarios2);
    });

    /**
     * Cargamos Varios2Extra
     */
    cargaVarios2Extra().done(function(responsePeticion) {

            var response = get_hostname(responsePeticion);


            if (!response.success) {
                error(mensajeVarios2);
            } else {
                var array = $.map(response.data, function(value, index) {
                    return [value];
                });
                if (array.length > 0) {
                    for (var x = 0; x < array.length; x++) {
                        var unidades = array[x].unidades;
                        var total = array[x].precioTotal;
                        var detalle = array[x].detalle;
                        if (unidades == null)
                            unidades = 0;

                        if (total == null)
                            total = 0;
                        else
                            total = parseFloat(array[x].precioTotal).toFixed(4);

                        if (total != 0 && unidades != 0) {
                            var rowCount = $('#tablaVarios2 tr').length;

                            var precioCopia = parseFloat(array[x].precio).toFixed(4);

                            var newRowContent = "<tr class='trStyle'>" +
                                "<td><span id='descripcionVarios2_" + rowCount + "' class='descripcionVarios2'>" + array[x].descripcion + "</span></td>" +
                                "<td><input type='text' id='unidadesVarios2_" + rowCount + "' value='" + unidades + "' style='width:100%' class='varios2ExtraUpdate'/></td>" +
                                "<td><span id='precioVarios2_" + rowCount + "'>" + precioCopia + "</span></td>" +
                                "<td><input type='text' id='totalVarios2_" + rowCount + "' value='" + total + "' style='width:100%' class='totalVarios2' readonly/>" +
                                "<input type='hidden' id='idDetalle_" + rowCount + "' value='" + detalle + "' class='' readonly/>" +
                                "</td>" +
                                "</tr>";

                            if (newRowContent != null)
                                $(newRowContent).appendTo($("#tablaVarios2"));
                        }
                    }
                    setSubTotalVarios2();
                }
            }
        }) //Error en la consulta o comunicacion con la bbdd.
        .fail(function(jqXHR, textStatus, errorThrown) {
            error(mensajeVarios2);
        });

    /**
     * Actualizamos el importe total y realizamos la insercion en caso de modificacion de varios1
     */
    $(document).on("blur", ".varios1", function() {
        var valor = $(this).val();
        var id = $(this).attr("id");
        if (!isNaN(valor)) {
            id = id.replace(",", ".");
            var detalle = id.substring(id.indexOf("_") + 1, id.length);
            var cadenaSpan = "#precio_" + detalle + "_varios1";
            var precio = $(cadenaSpan).text();
            var precioTotal = (parseFloat(valor) * parseFloat(precio)).toFixed(4);
            $('#total_' + detalle + '_varios1').val(precioTotal);
            var mensaje = "";
            operarVarios1(detalle, valor, precioTotal).done(function(responseVarios1) {
                    if (!responseVarios1.success) {
                        error(mensajeSubtotalVarios1);
                    }
                }) //Error en la consulta o comunicacion con la bbdd.
                .fail(function(jqXHR, textStatus, errorThrown) {
                    error(mensajeSubtotalVarios1);
                });
            setSubTotalVarios1();
        } else {
            error(mensajeNumerico);
        }

    });

    /**
     * Actualizamos el importe total y realizamos la insercion en caso de modificacion de color
     */
    $(document).on("blur", ".color", function() {
        var valor = $(this).val();
        var id = $(this).attr("id");
        if (!isNaN(valor)) {
            var detalle = id.substring(id.indexOf("_") + 1, id.length);
            var cadenaSpan = "#precio_" + detalle + "_color";
            var precio = $(cadenaSpan).text();
            var precioTotal = (parseFloat(valor) * parseFloat(precio)).toFixed(4);
            $('#total_' + detalle + '_color').val(precioTotal);
            operarColor(detalle, valor, precioTotal).done(function(responseColor) {
                    if (!responseColor.success) {
                        error(mensajeSubtotalColor);
                    }
                }) //Error en la consulta o comunicacion con la bbdd.
                .fail(function(jqXHR, textStatus, errorThrown) {
                    error(mensajeSubtotalColor);
                });
            setSubTotalColor();
        } else {
            error(mensajeNumerico);
        }
    });

    /**
     * Actualizamos el importe total y realizamos la insercion en caso de modificacion de blanco y negro
     */
    $(document).on("blur", ".blanco", function() {
        var valor = $(this).val();
        var id = $(this).attr("id");
        if (!isNaN(valor)) {
            var detalle = id.substring(id.indexOf("_") + 1, id.length);
            var cadenaSpan = "#precio_" + detalle + "_blanco";
            var precio = $(cadenaSpan).text();
            var precioTotal = (parseFloat(valor) * parseFloat(precio)).toFixed(4);
            $('#total_' + detalle + '_blanco').val(precioTotal);
            operarBlancoYNegro(detalle, valor, precioTotal).done(function(responseColor) {
                    if (!responseColor.success) {
                        error(mensajeSubtotalByN);
                    }
                }) //Error en la consulta o comunicacion con la bbdd.
                .fail(function(jqXHR, textStatus, errorThrown) {
                    error(mensajeSubtotalByN);
                });
            setSubTotalByN();
        } else {
            error(mensajeNumerico);
        }
    });

    /**
     * Actualizamos el importe total y realizamos la insercion en caso de modificacion de encolado
     */
    $(document).on("blur", ".encolado", function() {
        var valor = $(this).val();
        var id = $(this).attr("id");
        if (!isNaN(valor)) {
            var detalle = id.substring(id.indexOf("_") + 1, id.length);
            var cadenaSpan = "#precio_" + detalle + "_encolado";
            var precio = $(cadenaSpan).text();
            var precioTotal = (parseFloat(valor) * parseFloat(precio)).toFixed(4);
            $('#total_' + detalle + '_encolado').val(precioTotal);
            operarEncolado(detalle, valor, precioTotal).done(function(responseColor) {
                    if (!responseColor.success) {
                        error(mensajeSubtotalEncolado);
                    }
                }) //Error en la consulta o comunicacion con la bbdd.
                .fail(function(jqXHR, textStatus, errorThrown) {
                    error(mensajeSubtotalEncolado);
                });
            setSubTotalEncolado();
        } else {
            error(mensajeNumerico);
        }
    });

    /**
     * Actualizamos el importe total y realizamos la insercion en caso de modificacion de encuadernacion
     */
    $(document).on("blur", ".espiral", function() {
        var valor = $(this).val();
        var id = $(this).attr("id");
        if (!isNaN(valor)) {
            var detalle = id.substring(id.indexOf("_") + 1, id.length);
            var cadenaSpan = "#precio_" + detalle + "_espiral";
            var precio = $(cadenaSpan).text();
            var precioTotal = (parseFloat(valor) * parseFloat(precio)).toFixed(4);
            $('#total_' + detalle + '_espiral').val(precioTotal);
            operarEncuadernacion(detalle, valor, precioTotal).done(function(responseColor) {
                    if (!responseColor.success) {
                        error(mensajeSubtotalEspiral);
                    }
                }) //Error en la consulta o comunicacion con la bbdd.
                .fail(function(jqXHR, textStatus, errorThrown) {
                    error(mensajeSubtotalEspiral);
                });
            setSubTotalEncuadernacion();
        } else {
            error(mensajeNumerico);
        }
    });

    /**
     * Accion que se desencadena cuando el usuario selecciona una opcion de la lista de va
     */
    $(document).on("change", "#varios2Select", function() {
        var valor = $('select[name=varios2Select] option:selected').val();
        var rowCount = $('#tablaVarios2 tr').length;
        var texto = $('select[name=varios2Select] option:selected').text();
        var flagExist = false;
        var allListElements = $(".descripcionVarios2");
        if (valor != 0) {
            for (var x = 0; x < allListElements.length; x++) {
                var opcion = allListElements[x];
                if (texto == opcion.textContent) {
                    flagExist = true;
                }
            }
            if (!flagExist) {
                recuperaDetalleVarios2(valor).done(function(responseVariosCombo) {
                        if (!responseVariosCombo.success) {
                            error(mensajeSumaVarios2);
                        } else {
                            var array = $.map(responseVariosCombo.data, function(value, index) {
                                return [value];
                            });

                            if (array.length > 0) {
                                for (var x = 0; x < array.length; x++) {

                                    var newRowContent =
                                        "<tr class='trStyle'>" +
                                        "<td><span id='descripcionVarios2' class='descripcionVarios2'>" + array[x].descripcion + "</span></td>" +
                                        "<td><input type='text' id='unidades_" + array[x].detalle + "' style='width:100%' onkeypress='return (event.charCode >= 46 && event.charCode <= 57)' class='varios2Combo'/></td>" +
                                        "<td> <span id='precio_" + array[x].detalle + "_extra'>" + array[x].precio + "</span></td>" +
                                        "<td><input type='text' id='total_" + array[x].detalle + "_extra' style='width:100%' readOnly='true' class='totalVarios2'/></td>" +
                                        "</tr>";

                                    if (newRowContent != null)
                                        $(newRowContent).appendTo($("#bodyVarios2"));
                                }
                            }
                            setSubTotalVarios2();
                        }
                    }) //Error en la consulta o comunicacion con la bbdd.
                    .fail(function(jqXHR, textStatus, errorThrown) {
                        error(mensajeSumaVarios2);
                    });

            } else {
                error(mensajeExisteVarios2);
            }
        }
    });

    /**
     * Accion cuando se pulsa al enlace para a\u00F1adir un nuevo elemento Varios 2 desde zero
     * A\u00F1ade una linea en blanco para que el usuario pueda incluir todo lo que considere.
     */
    $(document).on("click", "#sumarLinea", function() {
        var rowCount = $('#tablaVarios2 tr').length;

        var newRowContent = "<tr class='trStyle'>" +
            "<td><input type='text' id='descripcionVarios2_" + rowCount + "' style='width:100%' class='varios2Extra muestraVentana' /></td>" +
            "<td><input type='text' id='cantidadVarios2_" + rowCount + "' style='width:100%' onkeypress='return (event.charCode >= 46 && event.charCode <= 57)' class='varios2Extra'/></td>" +
            "<td><input type='text' id='precioVarios2_" + rowCount + "' style='width:100%' onkeypress='return (event.charCode >= 46 && event.charCode <= 57)' class='varios2Extra' /></td>" +
            "<td><input type='text' id='precioTotalVarios2_" + rowCount + "' style='width:100%' readOnly='true' class='totalVarios2'/></td>" +
            "</tr>";

        $(newRowContent).appendTo($("#bodyVarios2"));
        setSubTotalVarios2();
    });

    $(document).on("blur", ".muestraVentana", function() {

        var id = $(this).attr("id");
        var linea = id.substr(id.indexOf('_') + 1, id.length);
        var descripcion = $("#descripcionVarios2_" + linea).val();

    });

    $(document).on("click", ".listaTabla", function() {
        var lineaDestino = $(this).attr("target");
        var id = $(this).attr("id");
        var linea = id.substr(id.indexOf('_') + 1, id.length);
        var descripcion = $("#descripcionVarios2_" + lineaDestino).val($('#seleccionLista_' + linea).text());
        var precio = $("#precioVarios2_" + lineaDestino).val($('#precioLista_' + linea).text());
        $('#error').hide();
        $('#cantidadVarios2_' + lineaDestino).focus();
    });

    $(document).on("blur", ".varios2ExtraUpdate", function() {
        var id = $(this).attr("id");
        if (id.indexOf('precioTotalVarios2_') == -1 || id.indexOf('descripcionVarios2_') == -1) {
            var precioTotal = 0;
            var linea = id.substr(id.indexOf('_') + 1, id.length);
            var descripcion = $("#descripcionVarios2_" + linea).text();
            var unidades = $("#unidadesVarios2_" + linea).val();
            var precio = $("#precioVarios2_" + linea).text();
            var idActualizar = $("#idDetalle_" + linea).val();

            console.log(idActualizar);

            if (unidades != "" && precio != "") {
                precioTotal = (parseFloat(unidades) * parseFloat(precio)).toFixed(4);
                $("#totalVarios2_" + linea).val(precioTotal);

                $.ajax({
                    type: 'get',
                    dataType: 'json',
                    data: {
                        solicitud: solicitudId,
                        unidades: unidades,
                        precio: precio,
                        precioTotal: precioTotal,
                        descripcion: descripcion,
                        identificador: idActualizar
                    },
                    url: '../dao/update/updateVarios2.php',
                    success: function(response) {
                        if (!response.success) {
                            error(mensajeSubtotalVarios2);
                        }
                    },
                    error: function(xhr, status, error) {
                        var err = xhr.responseText;

                    }
                });
            }
            setSubTotalVarios2();
        }

    });

    /**
     * Valida y a\u00F1ade en las tablas detalle y trabajodetalle todo el contenido que el usuario ha incluido en las casillas en blanco de Varios2
     */
    $(document).on("blur", ".varios2Extra", function() {

        var id = $(this).attr("id");
        if (id.indexOf('precioTotalVarios2_') == -1 && id.indexOf('descripcionVarios2_') == -1) {
            var linea = id.substr(id.indexOf('_') + 1, id.length);
            var descripcion = $("#descripcionVarios2_" + linea).val();
            var unidades = $("#cantidadVarios2_" + linea).val();
            var precio = $("#precioVarios2_" + linea).val();
            var precioTotal = "";
            //Controlamos que los valores numericos esten completados
            if (unidades != "" && precio != "") {
                precioTotal = (parseFloat(unidades) * parseFloat(precio)).toFixed(4);
                $("#precioTotalVarios2_" + linea).val(precioTotal);
                if (descripcion == "") {
                    error(mensajeDescripcion);
                }
            }
            //Controlamos que todos los valores esten completados.
            if (descripcion != "" && unidades != "" && precio != "" && precioTotal != "") {
                operarVarios2(descripcion, unidades, precio, precioTotal).done(function(responseVariosExtra) {}) //Error en la consulta o comunicacion con la bbdd.
                    .fail(function(jqXHR, textStatus, errorThrown) {
                        error(mensajeSumaVarios2);
                    });
            }
            setSubTotalVarios2();
        }
    });

    /**
     * Actualizamos el valor del precio con las opciones del combo de varios2
     */
    $(document).on("blur", ".varios2Combo", function() {
        var valor = $(this).val();
        var id = $(this).attr("id");
        if (!isNaN(valor)) {
            var detalle = id.substring(id.indexOf("_") + 1, id.length);
            var cadenaSpan = "#precio_" + detalle + "_extra";
            var precio = $(cadenaSpan).text();
            var precioTotal = (parseFloat(valor) * parseFloat(precio)).toFixed(4);
            $('#total_' + detalle + '_extra').val(precioTotal);
            operarVarios2Combo(detalle, valor, precioTotal).done(function(responseColor) {
                    if (!responseColor.success) {
                        error(mensajeSubtotalVarios2);
                    }
                }) //Error en la consulta o comunicacion con la bbdd.
                .fail(function(jqXHR, textStatus, errorThrown) {
                    error(mensajeSubtotalVarios2);
                });
            setSubTotalVarios2();
        } else {
            error(mensajeNumerico);
        }
    });

    $(document).on("blur", ".varios2Update", function() {
        var valor = $(this).val();
        var id = $(this).attr("id");
        var detalle = id.substring(id.indexOf("_") + 1, id.length);
        var stringTotal = "totalVarios2_" + detalle;
        //var total = $(stringTotal).val();
        if (!isNaN(valor)) {

        }
    });

    /**El Subtotal de varios 2**/
    function setSubTotalVarios2() {
        var total = 0;
        var allListElements = $(".totalVarios2");
        for (var x = 0; x < allListElements.length; x++) {
            var opcion = allListElements[x];
            var precio = opcion.value;
            total = (parseFloat(total) + parseFloat(precio)).toFixed(4);
            if (isNaN(total))
                total = 0;
            if (total == "")
                total = 0;
        }
        $('#subtotalVarios2').val(total);
        setTotal();
    }

    /**El Subtotal de varios 1**/
    function setSubTotalVarios1() {
        var total = 0;
        var allListElements = $(".totalVarios1");
        for (var x = 0; x < allListElements.length; x++) {
            var opcion = allListElements[x];
            var precio = opcion.value;
            total = (parseFloat(total) + parseFloat(precio)).toFixed(4);
        }
        $('#subtotalVarios1').val(total);
        setTotal();
    }

    /**El Subtotal de Color**/
    function setSubTotalColor() {
        var total = 0;
        var allListElements = $(".totalColor");
        for (var x = 0; x < allListElements.length; x++) {
            var opcion = allListElements[x];
            var precio = opcion.value;
            total = (parseFloat(total) + parseFloat(precio)).toFixed(4);
        }
        $('#subtotalColor').val(total);
        setTotal();
    }

    /**El Subtotal de Encuadernacion**/
    function setSubTotalEncuadernacion() {
        var total = 0;
        var allListElements = $(".totalEncuadernacion");
        for (var x = 0; x < allListElements.length; x++) {
            var opcion = allListElements[x];
            var precio = opcion.value;
            total = (parseFloat(total) + parseFloat(precio)).toFixed(4);
        }
        $('#subtotalEncuadernacion').val(total);
        setTotal();
    }

    /**El Subtotal de Encolado**/
    function setSubTotalEncolado() {
        var total = 0;
        var allListElements = $(".totalEncolado");
        for (var x = 0; x < allListElements.length; x++) {
            var opcion = allListElements[x];
            var precio = opcion.value;
            total = (parseFloat(total) + parseFloat(precio)).toFixed(4);
        }
        $('#subtotalEncolado').val(total);
        setTotal();
    }

    /**El Subtotal de ByN**/
    function setSubTotalByN() {
        var total = 0;
        var allListElements = $(".totalByN");
        for (var x = 0; x < allListElements.length; x++) {
            var opcion = allListElements[x];
            var precio = opcion.value;
            total = (parseFloat(total) + parseFloat(precio)).toFixed(4);
        }
        $('#subtotalByN').val(total);
        setTotal();
    }

    function setTotal() {
        var varios1 = (isNaN($('#subtotalVarios1').val())) ? 0 : $('#subtotalVarios1').val();
        var varios2 = (isNaN($('#subtotalVarios2').val())) ? 0 : $('#subtotalVarios2').val();
        varios2 = ($('#subtotalVarios2').val() == "") ? 0 : $('#subtotalVarios2').val();
        var color = (isNaN($('#subtotalColor').val())) ? 0 : $('#subtotalColor').val();
        var blanco = (isNaN($('#subtotalByN').val())) ? 0 : $('#subtotalByN').val();
        var espiral = (isNaN($('#subtotalEncuadernacion').val())) ? 0 : $('#subtotalEncuadernacion').val();
        var encolado = (isNaN($('#subtotalEncolado').val())) ? 0 : $('#subtotalEncolado').val();
        var sumaTotal = (parseFloat(varios1) + parseFloat(varios2) + parseFloat(color) + parseFloat(blanco) + parseFloat(espiral) + parseFloat(encolado)).toFixed(4);
        $('#total').val(sumaTotal);
    }

    /**
     * Accion cuando se pulsa al enlace para a\u00F1adir un nuevo elemento Varios 2 desde zero
     * A\u00F1ade una linea en blanco para que el usuario pueda incluir todo lo que considere.
     */
    $(document).on("click", "#guardar", function() {
        /**
         * Cambiamos de estado al acceder a la ficha.
         */
        var varios1 = (isNaN($('#subtotalVarios1').val())) ? 0 : $('#subtotalVarios1').val();
        var varios2 = (isNaN($('#subtotalVarios2').val())) ? 0 : $('#subtotalVarios2').val();
        varios2 = ($('#subtotalVarios2').val() == "") ? 0 : $('#subtotalVarios2').val();
        var color = (isNaN($('#subtotalColor').val())) ? 0 : $('#subtotalColor').val();
        var blanco = (isNaN($('#subtotalByN').val())) ? 0 : $('#subtotalByN').val();
        var espiral = (isNaN($('#subtotalEncuadernacion').val())) ? 0 : $('#subtotalEncuadernacion').val();
        var encolado = (isNaN($('#subtotalEncolado').val())) ? 0 : $('#subtotalEncolado').val();
        var totalEncuadernacion = (parseFloat(espiral) + parseFloat(encolado)).toFixed(4);
        cambiarSubTotales(varios1, varios2, color, blanco, espiral, encolado).done(function(response) {

                if (!response.success) {
                    error(mensajeSubtotales);
                } else {
                    alert("Se va a Guardar el parte de trabajo de la solicitud " +
                        solicitudId + " Con un importe total de " + $('#total').val() +
                        " desglosado en Varios1:" + varios1 + " Varios 2:" + varios2 + " Color:" + color + " BlancoYNegro:" + blanco + " Espiral:" + espiral + " y encolado:" + encolado);

                    changeStatus(5).done(function(response) {
                            if (!response.success) {
                                error(mensajeSubtotales);
                            } else {
                                location.href = "../formularios/plantilla.html";
                            }
                        })
                        .fail(function(jqXHR, textStatus, errorThrown) {
                            error(mensajeSubtotales);
                        });
                }
            }) //Error en la consulta o comunicacion con la bbdd.
            .fail(function(jqXHR, textStatus, errorThrown) {
                error(mensajeSubtotales);
            });
    });

    $(document).on("click", "#volver", function() {
        /**
         * Cambiamos de estado al acceder a la ficha.
         */
        var varios1 = (isNaN($('#subtotalVarios1').val())) ? 0 : $('#subtotalVarios1').val();
        var varios2 = (isNaN($('#subtotalVarios2').val())) ? 0 : $('#subtotalVarios2').val();
        varios2 = ($('#subtotalVarios2').val() == "") ? 0 : $('#subtotalVarios2').val();
        var color = (isNaN($('#subtotalColor').val())) ? 0 : $('#subtotalColor').val();
        var blanco = (isNaN($('#subtotalByN').val())) ? 0 : $('#subtotalByN').val();
        var espiral = (isNaN($('#subtotalEncuadernacion').val())) ? 0 : $('#subtotalEncuadernacion').val();
        var encolado = (isNaN($('#subtotalEncolado').val())) ? 0 : $('#subtotalEncolado').val();

        var totalEncuadernacion = (parseFloat(espiral) + parseFloat(encolado)).toFixed(4);

        cambiarSubTotales(varios1, varios2, color, blanco, espiral, encolado).done(function(response) {

                if (!response.success) {
                    error(mensajeGuardar);
                } else {
                    alert("Se va a Volver el parte de trabajo de la solicitud " +
                        solicitudId + " Con un importe total de " + $('#total').val() +
                        " desglosado en Varios1:" + varios1 + " Varios 2:" + varios2 + " Color:" + color + " BlancoYNegro:" + blanco + " Espiral:" + espiral + " y encolado:" + encolado);
                    /**
                     * Cambiamos de estado al acceder a la ficha.
                     */
                    changeStatus(5).done(function(response) {

                            if (!response.success) {
                                error(mensajeGuardar);
                            } else {
                                location.href = "../formularios/plantilla.html";
                            }
                        })
                        .fail(function(jqXHR, textStatus, errorThrown) {
                            error(mensajeGuardar);
                        });

                }
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                error(mensajeGuardar);
            });
    });

    $(document).on("click", "#cerrar", function() {
        /**
         * Cambiamos de estado al acceder a la ficha.
         */
        var varios1 = (isNaN($('#subtotalVarios1').val())) ? 0 : $('#subtotalVarios1').val();
        var varios2 = (isNaN($('#subtotalVarios2').val())) ? 0 : $('#subtotalVarios2').val();
        varios2 = ($('#subtotalVarios2').val() == "") ? 0 : $('#subtotalVarios2').val();
        var color = (isNaN($('#subtotalColor').val())) ? 0 : $('#subtotalColor').val();
        var blanco = (isNaN($('#subtotalByN').val())) ? 0 : $('#subtotalByN').val();
        var espiral = (isNaN($('#subtotalEncuadernacion').val())) ? 0 : $('#subtotalEncuadernacion').val();
        var encolado = (isNaN($('#subtotalEncolado').val())) ? 0 : $('#subtotalEncolado').val();

        var totalEncuadernacion = (parseFloat(espiral) + parseFloat(encolado)).toFixed(4);

        cambiarSubTotales(varios1, varios2, color, blanco, espiral, encolado).done(function(response) {

                if (!response.success) {
                    error(mensajeGuardar);
                } else {
                    alert("Se va a Cerrar el parte de trabajo de la solicitud " +
                        solicitudId + " Con un importe total de " + $('#total').val() +
                        " desglosado en Varios1:" + varios1 + " Varios 2:" + varios2 + " Color:" + color + " BlancoYNegro:" + blanco + " Espiral:" + espiral + " y encolado:" + encolado);
                    /**
                     * Cambiamos de estado al acceder a la ficha.
                     */
                    changeStatus(6).done(function(response) {

                            if (!response.success) {
                                error(mensajeGuardar);
                            } else {
                                location.href = "../formularios/plantilla.html";
                            }
                        })
                        .fail(function(jqXHR, textStatus, errorThrown) {
                            error(mensajeGuardar);
                        });
                }
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                error(mensajeGuardar);
            });
    });


    function error(texto) {
        $("#error").empty();
        $('#error').css('display', 'block');
        $("#error").addClass("error");

        $("#error").append("<img src='./../images/close-button.png' id='close-button' style='margin-left: 95%;cursor:pointer;'>");
        $("#error").append("<center><p><h2>" + texto + "</h2></p></center>");
        $("#error").append("<center><p><h2><a id='cerrarPopup' style='color:white;cursor:pointer;'>Cerrar</a></h2></p></center>");

    }

    function errorTexto(texto) {
        $("#error").empty();
        $('#error').css('display', 'block');
        $("#error").addClass("error");

        $("#error").append("<img src='./../images/close-button.png' id='close-button' style='margin-left: 95%;cursor:pointer;'>");
        $("#error").append("<center><p><h2>" + texto + "</h2></p></center>");
        $("#error").append("<center><p><h2><a id='cerrarPopup' style='color:white;cursor:pointer;'>Cerrar</a></h2></p></center>");

    }

    $(document).on("click", "#close-button", function() {
        $("#error").hide();
    });

    $(document).on("click", "#close-button", function() {
        $("#error").hide();
    });

    $(document).on("click", "#cerrarPopup", function() {
        $("#error").hide();
    });

    function get_hostname(response) {
        try {
            response = JSON.parse(response);
        } catch (error) {
            response = response;
        } finally {
            return response;
        }
    }
});