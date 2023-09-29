$(document).ready(function($) {

    cargarSelect('rol', 'listadoRoles');
    $(document).on("change", "select", function() {
        if (this.id === 'rol') {
            if (this.value === '3') {
                $('#capasRol').css('display', 'block');
                cargarSelect('departamento', 'listadoDepartamentos');
            } else {
                $('#capasRol').css('display', 'none');
            }
        }
    });

    $(document).on("click", "select", function() {
        if (this.id === 'departamento') {
            return !$('#departamento option:selected').appendTo('#seleccionado');
        }
        if (this.id === 'seleccionado') {
            return !$('#seleccionado option:selected').remove()('#departamento');
        }
    });

    $('#altaUsuarioButton').click(function() {
        if (validaAltaUsuario()) {
            mostrarSpin();
            direccionEnvio = '../dao/insert/altaUsuario.php';
            selectValueSelected = "";
            $("#seleccionado > option").each(function() {
                selectValueSelected += this.value + ',';
            });

            valor = "nombreHidden=" + $("#nombre").val() +
                "&apellidoHidden=" + $("#apellido").val() +
                "&emailHidden=" + $("#login").val() +
                "&passwordHidden=" + $("#password").val() +
                "&rolHidden=" + $("#rol").val() +
                "&destinoHidden=" + selectValueSelected.slice(0, -1);


            $.post(direccionEnvio, valor, function(response) {
                borrarSpin();
                response = get_hostname(response);
                successNotifier(response.message);
                $('#altaUsuarioForm').trigger("reset");
                cargarSelect('departamento', 'listadoDepartamentos');
                $('#seleccionado').empty();
                $('#capasRol').css('display', 'none');
            });
        } else {
            notifier('Revisa los datos del formulario');
        }
    });

});

function validaAltaUsuario() {
    var valida = true;

    ($('#nombre').val() === "" || $('#nombre').val() === null) ? valida = false: valida = valida; // jshint ignore:line
    ($('#apellido').val() === "" || $('#apellido').val() === null) ? valida = false: valida = valida; // jshint ignore:line
    ($('#login').val() === "" || $('#login').val() === null) ? valida = false: valida = valida; // jshint ignore:line
    (/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i.test($('#login').val()) === false) ? valida = false: valida = valida; // jshint ignore:line
    ($('#password').val() === "" || $('#password').val() === null) ? valida = false: valida = valida; // jshint ignore:line
    ($('#rol').val() === "0") ? valida = false: valida = valida; // jshint ignore:line
    ($('#rol').val() === "3") ? ($('#seleccionado').val() === "" || $('#seleccionado').val() === null) ? valida = false: valida = valida: valida = valida; // jshint ignore:line

    return valida;
}