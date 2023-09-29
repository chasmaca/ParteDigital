$(document).ready(function($) {

    cargarSelect('departamento', 'listadoDepartamentos');
    $('#altaSubdepartamentoButton').click(function() {
        if (validaAltaSubdepartamento()) {
            mostrarSpin();
            var valorFormulario = "departamentoHidden=" + $("#departamento").val() + "&nombreHidden=" + $("#nombreHidden").val() + "&treintaBarraHidden=" + $("#treintaBarraHidden").val();
            var direccionEnvio = '../dao/insert/altaSubdepartamento.php';
            $.post(direccionEnvio, valorFormulario, function(response) {
                borrarSpin();
                response = get_hostname(response);
                successNotifier("Subdepartamento Creado");
                $('#altaSubdepartamentoForm').trigger("reset");
                vaciarSelect('departamento', 'Selecciona el Departamento');
                cargarSelect('departamento', 'listadoDepartamentos');
            });
        } else {
            notifier('Revisa los datos del formulario. Recuerda que el TreintaBarra debe comenzar por 30/');
        }
    });
});

function validaAltaSubdepartamento() {
    var valida = true;
    ($('#nombreHidden').val() === "" || $('#nombreHidden').val() === null) ? valida = false: valida = valida; // jshint ignore:line
    ($('#treintaBarraHidden').val() === "" || $('#treintaBarraHidden').val() === null || $('#treintaBarraHidden').val().substring(0,3) != "30/") ? valida = false: valida = valida; // jshint ignore:line
    ($('#departamento').val() === "0") ? valida = false: valida = valida; // jshint ignore:line

    return valida;
}