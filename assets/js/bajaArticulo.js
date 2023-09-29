$(document).ready(function($) {

    cargarSelect('tipo', 'listadoTipos');
    $(document).on("change", "select", function() {
        if (this.id === 'tipo') {
            direccionEnvio = '../dao/select/cargarSelectDetalle.php';
            var optionTextSub = "";
            var valor = "valor=" + this.value;
            $.post(direccionEnvio, valor, function(response) {
                response = get_hostname(response);
                resultadoGlobal = response.data;
                $("#articulo").empty();
                $('#articulo').append('<option value="0">Selecciona el articulo</option>');
                for (var key in resultadoGlobal) {
                    if (resultadoGlobal.hasOwnProperty(key)) {
                        var id = JSON.stringify(resultadoGlobal[key].id).replace(/"/g, '').trim();
                        var texto = JSON.stringify(resultadoGlobal[key].nombre).replace(/"/g, '').trim();
                        optionTextSub += '<option value="' + id + '">' + texto + '</option>';
                    }
                }
                $('#articulo').append(optionTextSub);
            });
        }
    });

    $('#bajaArticuloButton').click(function() {
        if (validaBorradoArticulo()) {
            mostrarSpin();
            direccionEnvio = '../dao/delete/borradoArticulo.php';
            valor = "tipoHidden=" + $("#tipo").val() + "&detalleHidden=" + $("#articulo").val();
            $.post(direccionEnvio, valor, function(response) {
                borrarSpin();
                response = get_hostname(response);
                successNotifier(response.message);
                $('#bajaArticuloForm').trigger("reset");

            });
        } else {
            notifier('Revisa los datos del formulario');
        }
    });
});


function validaBorradoArticulo() {
    var valida = true;

    ($('#tipo').val() === "0") ? valida = false: valida = valida; // jshint ignore:line
    ($('#articulo').val() === "0") ? valida = false: valida = valida; // jshint ignore:line

    return valida;
}