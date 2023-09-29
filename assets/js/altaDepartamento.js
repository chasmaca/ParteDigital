$(document).ready(function($) {

    $('#altaDepartamentoButton').click(function() {
        if (validaAltaDepartamento()) {
            mostrarSpin();
            var valorFormulario = "nombreHidden=" + $('#nombreDepartamento').val() + "&cecoHidden=" + $('#cecoDepartamento').val();
            var direccionEnvio = '../dao/insert/altaDepartamento.php';
            $.post(direccionEnvio, valorFormulario, function(response) {
                borrarSpin();
                response = get_hostname(response);
                successNotifier(response.message);
                $('#altaDepartamentoForm').trigger("reset");
            });
        } else {
            notifier('Revisa los datos del formulario. Recuerda que el ceco debe comenzar por ESB');
        }
    });

    function validaAltaDepartamento() {
        var valida = true;
        ($('#nombreDepartamento').val() === "" || $('#nombreDepartamento').val() === null) ? valida = false: valida = valida; // jshint ignore:line
        ($('#cecoDepartamento').val() === "" || $('#cecoDepartamento').val() === null || $('#cecoDepartamento').val().substring(0,3) != "ESB") ? valida = false: valida = valida; // jshint ignore:line
        return valida;
    }
});
