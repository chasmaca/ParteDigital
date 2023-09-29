$(document).ready(function ($) {

    cargarSelect('departamento', 'listadoDepartamentos');
    $(document).on("change", "select", function () {
        var valorFormulario = $('#modificacionDepartamentoForm').serialize();
        var direccionEnvio = '../dao/select/departamentoPorId.php';
        $.post(direccionEnvio, valorFormulario, function (response) {
            response = get_hostname(response);
            if (Object.keys(response.data).length > 0) {
                $("#nombre").val(response.data[0].nombre);
                $("#ceco").val(response.data[0].ceco);
            }
        });
    });

    $('#modificacion-Departamento').click(function(){
        if (validaModificacionDepartamento()) {
            mostrarSpin();
            const direccionEnvio = '../dao/update/modificacionDepartamento.php';
            const valorFormulario = 'departamentoHidden=' + $('#departamento').val() +
                '&nombreHidden=' + $("#nombre").val() +
                '&cecoHidden=' + $("#ceco").val();

            $.post(direccionEnvio, valorFormulario, function (response) {
                response = get_hostname(response);
                borrarSpin();
                successNotifier(response.message);
                $('#modificacionDepartamentoForm').trigger("reset");
                vaciarSelect('departamento', 'Selecciona el Departamento')
                cargarSelect('departamento', 'listadoDepartamentos');
            });
        } else {
            notifier('Revisa los datos del formulario');
        }
    });
});

function validaModificacionDepartamento() {
    let valida = true;
    ($('#departamento').val() === "0") ? valida = false : valida = valida;
    ($('#nombre').val() === "" || $('#nombre').val() === null) ? valida = false : valida = valida;
    ($('#ceco').val() === "" || $('#ceco').val() === null) ? valida = false : valida = valida;
    return valida;
}