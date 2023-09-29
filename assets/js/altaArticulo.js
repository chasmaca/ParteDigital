$(document).ready(function($) {

    cargarSelect('tipo', 'listadoTipos');

    $('#altaProductoButton').click(function() {
        if (validaAltaArticulo()) {
            mostrarSpin();
            var valorFormulario = "tipoHidden=" + $("#tipo").val() + "&nombreHidden=" + $("#descripcion").val() + "&precioHidden=" + $("#precio").val();
            var direccionEnvio = '../dao/insert/altaArticulo.php';
            $.post(direccionEnvio, valorFormulario, function(response) {
                borrarSpin();
                response = get_hostname(response);
                successNotifier(response.message);
                $('#altaProductoForm').trigger("reset");

            }).fail(function(error) {
                console.log(error);
            });
        } else {
            notifier('Revisa los datos del formulario');
        }
    });
});

function validaAltaArticulo() {
    var valida = true;
    ($('#descripcion').val() === "" || $('#descripcion').val() === null) ? valida = false: valida = valida; // jshint ignore:line
    ($('#precio').val() === "" || $('#precio').val() === null) ? valida = false: (isNaN($('#precio').val())) ? valida = false : valida = valida; // jshint ignore:line
    ($('#tipo').val() === "0") ? valida = false: valida = valida; // jshint ignore:line
    return valida;
}