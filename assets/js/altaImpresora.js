$(document).ready(function($) {

    $('#altaImpresoraButton').click(function() {
        if (validaAltaImpresora()) {
            mostrarSpin();
            direccionEnvio = '../dao/insert/altaImpresora.php';
            valor = 'modeloHidden=' + $("#modelo").val() +
                '&edificioHidden=' + $("#edificio").val() +
                '&ubicacionHidden=' + $("#ubicacion").val() +
                '&fechaHidden=' + $("#fecha").val() +
                '&serieHidden=' + $("#serie").val() +
                '&maquinaHidden=' + $("#maquina").val();

            $.post(direccionEnvio, valor, function(response) {
                borrarSpin();
                response = get_hostname(response);
                successNotifier(response.message);
                $('#altaImpresoraForm').trigger("reset");

            });

        } else {
            notifier('Revisa los datos del formulario');
        }
    });
});

function validaAltaImpresora() {
    var valida = true;

    ($("#modelo").val() === '' || $("#modelo").val() === null) ? valida = false: valida = valida; // jshint ignore:line
    ($("#edificio").val() === '' || $("#edificio").val() === null) ? valida = false: valida = valida; // jshint ignore:line
    ($("#ubicacion").val() === '' || $("#ubicacion").val() === null) ? valida = false: valida = valida; // jshint ignore:line
    ($("#fecha").val() === '' || $("#fecha").val() === null) ? valida = false: valida = valida; // jshint ignore:line
    (/^(\d{1,2})(\/|-)(\d{1,2})(\/|-)(\d{4})$/.test($('#fecha').val()) === false) ? valida = false: valida = valida; // jshint ignore:line
    ($("#serie").val() === '' || $("#serie").val() === null) ? valida = false: valida = valida; // jshint ignore:line
    ($("#maquina").val() === '' || $("#maquina").val() === null) ? valida = false: valida = valida; // jshint ignore:line

    return valida;
}