$(document).ready(function($) {

    cargarSelect('departamento', 'listadoDepartamentos');
    $(document).on("change", "select", function() {
        var valorFormulario = $('#borradoDepartamentoForm').serialize();
        var direccionEnvio = '../dao/select/departamentoPorId.php';
        $.post(direccionEnvio, valorFormulario, function(response) {
            response = get_hostname(response);
            if (Object.keys(response.data).length > 0) {
                $("#nombreHidden").val(response.data[0].nombre);
                $("#cecoHidden").val(response.data[0].ceco);
            }
        });
    });

    $('#borrado-Departamento').click(function() {
        if (validaBorradoDpto()) {
            mostrarSpin();
            var direccionEnvio = '../dao/delete/borradoDepartamento.php';
            var valorFormulario = 'departamento=' + $('#departamento').val();
            $.post(direccionEnvio, valorFormulario, function(response) {
                borrarSpin();
                response = get_hostname(response);
                successNotifier("Departamento Eliminado");
                $('#borradoDepartamentoForm').trigger("reset");
                vaciarSelect('departamento', 'Selecciona el Departamento');
                cargarSelect('departamento', 'listadoDepartamentos');
            });
        } else {
            notifier('Revisa los datos del formulario');
        }
    });

});

function validaBorradoDpto() {
    var valida = true;
    ($('#departamento').val() === '0') ? valida = false: valida = valida; // jshint ignore:line
    return valida;
}