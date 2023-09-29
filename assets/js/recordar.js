$(document).ready(function($) {

    $("#filtroButton").click(function() {
        if (validamosDatos()) {
            recoverId($("#email").val());
            new Noty({
                type: 'error',
                layout: 'topRight',
                theme: 'nest',
                text: "Se le ha enviado un email con la confirmación del cambio de contraseña.",
                timeout: '4000',
                progressBar: true,
                closeWith: ['click'],
                killer: true
            }).show();
        } else {
            new Noty({
                type: 'error',
                layout: 'topRight',
                theme: 'nest',
                text: "Problemas con el email. Revisa el formato",
                timeout: '4000',
                progressBar: true,
                closeWith: ['click'],
                killer: true
            }).show();
        }
    });
});

function validamosDatos() {
    var enviar = true;
    var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
    var campo = $("#email").val();

    enviar = (campo === "" || campo === null) ? false : true;
    enviar = (!campo.match(mailformat)) ? false : true;

    return enviar;
}

function recoverId(email) {
    var usuarioId = "";
    if (email !== "") {
        valor = "email=" + email;
        $.post('../dao/select/recuperaIdUsuario.php', valor, function(response) {
            response = get_hostname(response);
            valor = "operacion=cambioPassword&usuario=" + response.data[0].id + "&correo=" + email;
            $.post('../dao/operativa/envioMail.php', valor, function(response) {
                response = get_hostname(response);
                console.log(response);
            });
            // console.log("enviamos el enlace de recuperacion con www.elpartedigital.com/evolucion/formularios/nuevaPassword.html?token=" + response.data[0].id);
        });
    }
}

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