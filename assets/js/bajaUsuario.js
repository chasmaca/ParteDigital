$(document).ready(function($) {

    cargarSelect('usuario', 'listadoUsuarios');

    $('#bajaUsuarioButton').click(function() {
        if (validaBorradoUsuario()) {
            mostrarSpin();
            direccionEnvio = '../dao/delete/borradoUsuario.php';
            valor = "usuarioHidden=" + $("#usuario").val();
            $.post(direccionEnvio, valor, function(response) {
                borrarSpin();
                response = get_hostname(response);
                successNotifier(response.message);
                $('#bajaUsuarioForm').trigger("reset");
            });
        } else {
            notifier('Revisa los datos del formulario');
        }
    });

});

function validaBorradoUsuario() {

    var valida = true;
    ($('#usuario').val() === "0") ? valida = false: valida = valida; // jshint ignore:line
    return valida;

}