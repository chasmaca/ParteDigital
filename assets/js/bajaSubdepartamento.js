$(document).ready(function($) {

    cargarSelect('departamento', 'listadoDepartamentos');
    $(document).on("change", "select", function() {
        if (this.id === 'departamento') {
            direccionEnvio = '../dao/select/cargarSelectSubdepartamento.php';
            var optionTextSub = "";
            var valor = "valor=" + this.value;
            $.post(direccionEnvio, valor, function(response) {
                $('#subdepartamento').empty();
                $('#subdepartamento').append('<option value="0">Selecciona el Subdepartamento</option>');
                response = get_hostname(response);
                resultadoGlobal = response.data;
                for (var key in resultadoGlobal) {
                    if (resultadoGlobal.hasOwnProperty(key)) {
                        var id = JSON.stringify(resultadoGlobal[key].id).replace(/"/g, '').trim();
                        var texto = JSON.stringify(resultadoGlobal[key].nombre).replace(/"/g, '').trim();
                        optionTextSub += '<option value="' + id + '">' + texto + '</option>';
                    }
                }
                $('#subdepartamento').append(optionTextSub);
            });
        }
    });

    $('#borrado-Subdepartamento').click(function() {
        if (validaBorradoSubdpto()) {
            mostrarSpin();
            direccionEnvio = '../dao/delete/borradoSubdepartamento.php';
            valor = "departamentoHidden=" + $("#departamento").val() + "&subdepartamentoHidden=" + $("#subdepartamento").val();
            $.post(direccionEnvio, valor, function(response) {
                borrarSpin();
                response = get_hostname(response);
                successNotifier("Subdepartamento Eliminado");
                $('#borradoSubdepartamentoForm').trigger("reset");
                vaciarSelect('departamento', 'Selecciona el Departamento');
                cargarSelect('departamento', 'listadoDepartamentos');
                vaciarSelect('subdepartamento', 'Selecciona el Subdepartamento');
            });
        } else {
            notifier('Revisa los datos del formulario');
        }
    });

});

function validaBorradoSubdpto() {
    var valida = true;
    ($('#departamento').val() === "0") ? valida = false: valida = valida; // jshint ignore:line
    ($('#subdepartamento').val() === "0") ? valida = false: valida = valida; // jshint ignore:line
    return valida;
}