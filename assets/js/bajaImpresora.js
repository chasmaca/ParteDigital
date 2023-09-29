$(document).ready(function($) {

    var formulario = document.activeElement.id;
    if (formulario === 'bajaImpresora') {
        cargarSelect('impresora', 'listadoImpresoras');

        $('#bajaImpresoraButton').click(function() {
            if (validaBajaImpresora()) {
                mostrarSpin();
                direccionEnvio = '../dao/delete/borradoImpresora.php';
                valor = "impresoraHidden=" + $("#impresora").val();
                $.post(direccionEnvio, valor, function(response) {
                    borrarSpin();
                    response = get_hostname(response);
                    successNotifier(response.message);
                    $('#bajaImpresoraForm').trigger("reset");
                });
            } else {
                notifier('Revisa los datos del formulario');
            }
        });
    }
});

function validaBajaImpresora() {
    var valida = true;

    ($("#impresora").val() === '0') ? valida = false: valida = valida; // jshint ignore:line

    return valida;
}