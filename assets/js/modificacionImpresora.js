$(document).ready(function ($) {

    const formulario = document.activeElement.id;
    if (formulario === 'modificacionImpresora') {
        cargarSelect('impresora', 'listadoImpresoras');
        $(document).on("change", "select", function () {
            if (this.id === 'impresora') {
                direccionEnvio = '../dao/select/impresoraPorId.php';
                valor = "id=" + $("#impresora").val();
                $.post(direccionEnvio, valor, function (response) {
                    response = get_hostname(response);
                    $("#edificio").val(response.data[0].edificio);
                    $("#fecha").val(response.data[0].fecha);
                    $("#impresora").val(response.data[0].id);
                    $("#maquina").val(response.data[0].maquina);
                    $("#modelo").val(response.data[0].modelo);
                    $("#serie").val(response.data[0].serie);
                    $("#ubicacion").val(response.data[0].ubicacion);
                });
            }
        });

        $('#modificacionImpresoraButton').click(function(){
                if (validaModificacionImpresora()) {
                    mostrarSpin();
                    direccionEnvio = '../dao/update/modificacionImpresora.php';
                    valor = "impresoraHidden=" + $("#impresora").val() +
                        "&modeloHidden=" + $("#modelo").val() +
                        "&edificioHidden=" + $("#edificio").val() +
                        "&ubicacionHidden=" + $("#ubicacion").val() +
                        "&fechaHidden=" + $("#fecha").val() +
                        "&serieHidden=" + $("#serie").val() +
                        "&maquinaHidden=" + $("#maquina").val();

                    $.post(direccionEnvio, valor, function (response) {
                        borrarSpin();
                        response = get_hostname(response);
                        successNotifier(response.message);
                        $('#modificacionImpresoraForm').trigger("reset");
                    });
                } else {
                    notifier('Revisa los datos del formulario');
                    
                }
        });
    }

});

function validaModificacionImpresora() {
    let valida = true;

    ($("#impresora").val() === '0') ? valida = false : valida = valida;
    ($("#modelo").val() === '' || $("#modelo").val() === null) ? valida = false : valida = valida;
    ($("#edificio").val() === '' || $("#edificio").val() === null) ? valida = false : valida = valida;
    ($("#ubicacion").val() === '' || $("#ubicacion").val() === null) ? valida = false : valida = valida;
    ($("#fecha").val() === '' || $("#fecha").val() === null) ? valida = false : valida = valida;
    (/^(\d{1,2})(\/|-)(\d{1,2})(\/|-)(\d{4})$/.test($('#fecha').val()) === false) ? valida = false : valida = valida;
    ($("#serie").val() === '' || $("#serie").val() === null) ? valida = false : valida = valida;
    ($("#maquina").val() === '' || $("#maquina").val() === null) ? valida = false : valida = valida;

    return valida;

}