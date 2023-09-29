$('#section-home').ready(function($) {

    $("#loginButton").click(function() {
        if (validaCampos()) {
            $.post('dao/select/login.php', 'usuario=' + $('#loginEmail').val() + '&password=' + $('#loginPassword').val(), function(response) {
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
                    var role = response.data[0].role_id;
                    localStorage.setItem('usuarioId', response.data[0].usuario_id);
                    localStorage.setItem('usuarioEmail', response.data[0].logon);
                    localStorage.setItem('usuarioRole', role);
                    localStorage.setItem('usuarioNombre', response.data[0].nombre + ' ' + response.data[0].apellido);

                    if (role === 1) {
                        document.location.href = 'formularios/administrador.html';
                    }
                    if (role === 2) {
                        document.location.href = 'formularios/homeGestor.html';
                    }
                    if (role === 3) {
                        document.location.href = 'formularios/homeAutorizador.html';
                    }
                    if (role === 4) {
                        document.location.href = 'formularios/plantilla.html';
                    }
                    if (role === 5) {
                        document.location.href = 'formularios/homeConsulta.html';
                    }
                    if (role === 6) {
                        document.location.href = 'formularios/homeImpresoras.html';
                    }
                }
            });
        }

    });

    $("#remember").click(function() {
        console.log("recordamos la contraseña");
        document.location.href = "./formularios/recordarPassword.html";
    });

    $("#rememberCC").click(function() {
        console.log("recordamos el código");

    });

    $("#loginButtonSolicitud").click(function() {
        document.location.href = "./formularios/solicitud.html";
    });

});

$('#section-solicitud').ready(function($) {
    $.post('dao/select/usuariosMaquetado.php', 'tipo=aprobador', function(response) {
        response = get_hostname(response);
        var listadoUsuarios = response.data;
        for (var key in listadoUsuarios) {
            if (listadoUsuarios.hasOwnProperty(key)) {
                $('#autorizador').append($('<option>', {
                    value: JSON.stringify(listadoUsuarios[key].id).replace(/\"/g, ''),
                    text: JSON.stringify(listadoUsuarios[key].nombre).replace(/\"/g, '')
                }));
            }
        }
    });

    $('#autorizador').change(function() {
        $('#departamento').empty();
        $('#departamento').append($('<option>', {
            value: 0,
            text: 'Departamento'
        }));
        $.post('dao/select/asociacionAutorizador.php', 'autorizador=' + $('#autorizador').val(), function(response) {
            response = get_hostname(response);
            var listadoUsuarios = response.data;
            for (var key in listadoUsuarios) {
                if (listadoUsuarios.hasOwnProperty(key)) {
                    $('#departamento').append($('<option>', {
                        value: JSON.stringify(listadoUsuarios[key].id).replace(/\"/g, ''),
                        text: JSON.stringify(listadoUsuarios[key].nombre).replace(/\"/g, '')
                    }));
                }
            }
        });
    });

    $('#departamento').change(function() {
        $('#subdepartamento').empty();
        $('#subdepartamento').append($('<option>', {
            value: 0,
            text: 'Subdepartamento'
        }));
        $.post('dao/select/asociacionAutorizador.php', 'departamento=' + $('#departamento').val(), function(response) {
            response = get_hostname(response);
            var listadoUsuarios = response.data;
            for (var key in listadoUsuarios) {
                if (listadoUsuarios.hasOwnProperty(key)) {
                    $('#subdepartamento').append($('<option>', {
                        value: JSON.stringify(listadoUsuarios[key].id).replace(/\"/g, ''),
                        text: JSON.stringify(listadoUsuarios[key].nombre).replace(/\"/g, '')
                    }));
                }
            }
        });
    });

    $('#solicitudButton').click(function() {
        if (validamosDatos()) {
            $.post('dao/insert/solicitudMaquetada.php', $("#solicitudForm").serialize(), function(response) {
                    response = get_hostname(response);
                    if (response.success === true) {
                        $.post('utiles/envioMailParametros.php', $("#solicitudForm").serialize(), function(responseMail) {
                            responseMail = get_hostname(responseMail);
                            new Noty({
                                type: 'info',
                                layout: 'topRight',
                                theme: 'nest',
                                text: responseMail.message,
                                timeout: '4000',
                                progressBar: true,
                                closeWith: ['click'],
                                killer: true
                            }).show();

                        });

                        $('#solicitudForm').trigger("reset");

                        new Noty({
                            type: 'info',
                            layout: 'topRight',
                            theme: 'nest',
                            text: response.message,
                            timeout: '4000',
                            progressBar: true,
                            closeWith: ['click'],
                            killer: true
                        }).show();


                        limpiarCampos();
                    } else {
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
                })
                .fail(function(response) {
                    new Noty({
                        type: 'error',
                        layout: 'topRight',
                        theme: 'nest',
                        text: response.responseText,
                        timeout: '4000',
                        progressBar: true,
                        closeWith: ['click'],
                        killer: true
                    }).show();
                });
        }
    });

});

function validaCampos() {
    let avanzamos = true;
    if ($('#loginEmail').val() === '') {
        avanzamos = false;
        new Noty({
            type: 'error',
            layout: 'topRight',
            theme: 'nest',
            text: 'Debes rellenar el usuario',
            timeout: '4000',
            progressBar: true,
            closeWith: ['click'],
            killer: true
        }).show();
    }

    if ($('#loginPassword').val() === '') {
        avanzamos = false;
        new Noty({
            type: 'error',
            layout: 'topRight',
            theme: 'nest',
            text: 'Debes rellenar la password',
            timeout: '4000',
            progressBar: true,
            closeWith: ['click'],
            killer: true
        }).show();
    }

    return avanzamos;
}

function validamosDatos() {
    if ($('#autorizador').val() === '0') {
        new Noty({
            type: 'error',
            layout: 'topRight',
            theme: 'nest',
            text: 'Debes Completar El Autorizador',
            timeout: '4000',
            progressBar: true,
            closeWith: ['click'],
            killer: true
        }).show();
        return false;
    } else {
        $("#solAuth").val($('#autorizador').val());
    }
    if ($('#departamento').val() === '0') {
        new Noty({
            type: 'error',
            layout: 'topRight',
            theme: 'nest',
            text: 'Debes Completar El Departamento',
            timeout: '4000',
            progressBar: true,
            closeWith: ['click'],
            killer: true
        }).show();

        return false;
    } else {
        $("#solDpto").val($('#departamento').val());
    }
    if ($('#subdepartamento').val() === '0') {
        new Noty({
            type: 'error',
            layout: 'topRight',
            theme: 'nest',
            text: 'Debes Completar El Subdepartamento',
            timeout: '4000',
            progressBar: true,
            closeWith: ['click'],
            killer: true
        }).show();


        return false;
    } else {
        $("#solSubdpto").val($('#subdepartamento').val());
    }

    if ($('#fname').val() === '') {
        new Noty({
            type: 'error',
            layout: 'topRight',
            theme: 'nest',
            text: 'Debes Completar Tu Nombre',
            timeout: '4000',
            progressBar: true,
            closeWith: ['click'],
            killer: true
        }).show();


        return false;
    } else {
        $("#solName").val($('#fname').val());
    }

    if ($('#lname').val() === '') {
        new Noty({
            type: 'error',
            layout: 'topRight',
            theme: 'nest',
            text: 'Debes Completar Tu Apellido',
            timeout: '4000',
            progressBar: true,
            closeWith: ['click'],
            killer: true
        }).show();

        return false;
    } else {
        $("#solSurname").val($('#lname').val());
    }

    if ($('#email').val() === '') {
        new Noty({
            type: 'error',
            layout: 'topRight',
            theme: 'nest',
            text: 'Debes Completar Tu Email',
            timeout: '4000',
            progressBar: true,
            closeWith: ['click'],
            killer: true
        }).show();

        return false;
    }

    if (!validateEmail($('#email').val())) {
        new Noty({
            type: 'error',
            layout: 'topRight',
            theme: 'nest',
            text: 'El Email Debe Tener Un Formato Válido',
            timeout: '4000',
            progressBar: true,
            closeWith: ['click'],
            killer: true
        }).show();

        return false;
    } else {
        $("#solEmail").val($('#email').val());
    }

    if ($('#comment').val() === '') {
        new Noty({
            type: 'error',
            layout: 'topRight',
            theme: 'nest',
            text: 'Debes Completar Tus Comentarios',
            timeout: '4000',
            progressBar: true,
            closeWith: ['click'],
            killer: true
        }).show();
        return false;
    } else {
        $("#solComment").val($('#comment').val());
    }
    return true;
}

function validateEmail(email) {
    var emailReg = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
    return emailReg.test(email);
}

function limpiarCampos() {
    $('#autorizador').val('0');
    $('#departamento').val('0');
    $('#subdepartamento').val('0');
    $('#fname').val('');
    $('#lname').val('');
    $('#email').val('');
    $('#comment').val('');
}

function get_hostname(response) {
    try {
        response = JSON.parse(response);
    } catch {
        response = response;
    }
    return response;
}