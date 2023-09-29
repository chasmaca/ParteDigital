$(document).ready(function ($) {

    cargarSelect('tipo', 'listadoTipos');
    
    $(document).on("change", "select", function () {
        if (this.id === 'tipo') {
            direccionEnvio = '../dao/select/cargarSelectDetalle.php';
            let optionTextSub = "";
            let valor = "valor=" + this.value;
            $.post(direccionEnvio, valor, function (response) {
                response = get_hostname(response);
                resultadoGlobal = response.data;
                $("#articulo").empty();
                $('#articulo').append('<option value="0">Selecciona el articulo</option>');
                for (var key in resultadoGlobal) {
                    if (resultadoGlobal.hasOwnProperty(key)) {
                        const id = JSON.stringify(resultadoGlobal[key].id).replace(/"/g, '').trim();
                        const texto = JSON.stringify(resultadoGlobal[key].nombre).replace(/"/g, '').trim();
                        optionTextSub += '<option value="' + id + '">' + texto + '</option>';
                    }
                }
                $('#articulo').append(optionTextSub);
            });
        }
        if (this.id === 'articulo') {
            direccionEnvio = '../dao/select/articuloPorId.php';
            valor = "id=" + $("#tipo").val() + "&id1=" + $("#articulo").val();
            $.post(direccionEnvio, valor, function (response) {
                response = get_hostname(response);
                resultadoGlobal = response.data;
                $("#descripcion").val(resultadoGlobal[0].nombre);
                $("#precio").val(resultadoGlobal[0].precio);
            });
        }
    });

    $('#modificacionArticuloButton').click(function(){
        direccionEnvio = '../dao/update/modificacionArticulo.php';
        valor = "tipoHidden=" + $("#tipo").val() +
                "&detalleHidden=" + $("#articulo").val() +
                "&nombreHidden=" + $("#descripcion").val() +
                "&precioHidden=" + $("#precio").val();
        if (validaModificacionArticulo()) {
            mostrarSpin();
            $.post(direccionEnvio, valor, function (response) {
                borrarSpin();
                response = get_hostname(response);
                successNotifier(response.message);
                $('#modificacionArticuloForm').trigger("reset");
            });
    } else {
            notifier('Revisa los datos del formulario');
        }
    });
});

function validaModificacionArticulo() {
    let valida = true;

    ($('#tipo').val() === '0') ? valida = false : valida = valida;
    ($('#articulo').val() === '0') ? valida = false : valida = valida;
    ($('#descripcion').val() === "" || $('#descripcion').val() === null) ? valida = false : valida = valida;
    ($('#precio').val() === "" || $('#precio').val() === null) ? valida = false : valida = valida;
    (isNaN($('#precio').val()))  ? valida = false : valida = valida;

    return valida;
}
