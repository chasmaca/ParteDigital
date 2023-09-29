$(document).ready(function($) {

    var idUsuario = "";
    var sPageURL = window.location.search.substring(1);
    var sURLVariables = sPageURL.split('&');
    for (var i = 0; i < sURLVariables.length; i++) {
        var sParameterName = sURLVariables[i].split('=');
        idUsuario = sParameterName[1];
    }

    if (idUsuario !== "") {
        idUsuario = idUsuario.substring(0, idUsuario.indexOf('-'));
        valor = "id=" + idUsuario;
        $.post('../dao/select/recuperaEmailUsuario.php', valor, function(response) {
            response = get_hostname(response);
            $("#email").val(response.data[0].logon);
            $("#idHidden").val(idUsuario);
        });
    }

    $("#filtroButton").click(function() {
        console.log("Llamamos al cambio de contraseña");
        if (validaPassword()) {
            valor = "id=" + idUsuario + "&password=" + $("#password").val() + "&reenter=" + $("#reenty").val();
            $.post('../dao/update/cambioPassword.php', valor, function(response) {
                response = get_hostname(response);
                new Noty({
                    type: 'error',
                    layout: 'topRight',
                    theme: 'nest',
                    text: "Password Actualizada con Éxito",
                    timeout: '4000',
                    progressBar: true,
                    closeWith: ['click'],
                    killer: true
                }).show();
            });
        } else {
            new Noty({
                type: 'error',
                layout: 'topRight',
                theme: 'nest',
                text: "Problemas las passwords",
                timeout: '4000',
                progressBar: true,
                closeWith: ['click'],
                killer: true
            }).show();
        }
    });
});

function get_hostname(response) {
    try {
        response = JSON.parse(response);
    } catch {
        response = response;
    }
    return response;
    // if (document.location.hostname === 'www.elpartedigital.com') {
    //     response = JSON.parse(response);
    // }
    // return response;
}


function validaPassword() {
    var continuar = true;
    var password = $("#password").val();
    var reentry = $("#reenty").val();

    if (password === "" || reentry === "") {
        continuar = false;
    }

    if (password !== reentry) {
        continuar = false;
    }

    return continuar;
}