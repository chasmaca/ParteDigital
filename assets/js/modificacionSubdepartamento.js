$(document).ready(function ($) {

    cargarSelect('departamento', 'listadoDepartamentos');
    $(document).on("change", "select", function () {

        direccionEnvio = '../dao/select/cargarSelectSubdepartamento.php';
        let optionTextSub = "";
        let valor = "valor=" + this.value;

        if (this.id === 'departamento') {
            $.post(direccionEnvio, valor, function (response) {
                $('#subdepartamento').empty();
                $('#subdepartamento').append('<option value="0">Selecciona el Subdepartamento</option>');
                response = get_hostname(response);
                resultadoGlobal = response.data;
                for (var key in resultadoGlobal) {
                    if (resultadoGlobal.hasOwnProperty(key)) {
                        const id = JSON.stringify(resultadoGlobal[key].id).replace(/"/g, '').trim();
                        const texto = JSON.stringify(resultadoGlobal[key].nombre).replace(/"/g, '').trim();
                        optionTextSub += '<option value="' + id + '">' + texto + '</option>';
                    }
                }
                $('#subdepartamento').append(optionTextSub);
            });
        }
        
        if (this.id === 'subdepartamento') {
            direccionEnvio = '../dao/select/subdepartamentoPorId.php';
            valor = "id=" + $("#departamento").val() + "&id1=" + $("#subdepartamento").val();
            $.post(direccionEnvio, valor, function (response) {
                response = get_hostname(response);
                resultadoGlobal = response.data;
                if (Object.keys(resultadoGlobal).length > 0) {
                    $("#nombreHidden").val(resultadoGlobal[0].nombre);
                    $("#treintaBarraHidden").val(resultadoGlobal[0].treintaBarra);
                }
            });
        }
    });

    $(document).on("click", "button", function () {
        if (this.id === 'modificacionSubdepartamento') {
            if (validaModificacionSubdpto()) {
                mostrarSpin();
                direccionEnvio = '../dao/update/modificacionSubdepartamento.php';
    
                valor = 
                    "departamentoHidden=" + $("#departamento").val() +
                    "&subdepartamentoHidden=" + $("#subdepartamento").val() +
                    "&nombreHidden=" + $("#nombreHidden").val() +
                    "&treintaBarraHidden=" + $("#treintaBarraHidden").val();
    
                $.post(direccionEnvio, valor, function (response) {
                    borrarSpin();
                    response = get_hostname(response);
                    successNotifier(response.message);
                    $('#modificacionSubdepartamentoForm').trigger("reset");
                    vaciarSelect('departamento', 'Selecciona el Departamento')
                    cargarSelect('departamento', 'listadoDepartamentos');
                    vaciarSelect('subdepartamento', 'Selecciona el Subdepartamento');
                });
            } else {
                notifier('Revisa los datos del formulario');
            }

        }
    });
});

function validaModificacionSubdpto() {
    let valida = true;

    ($('#nombreHidden').val() === "" || $('#nombreHidden').val() === null) ? valida = false : valida = valida;
    ($('#treintaBarraHidden').val() === "" || $('#treintaBarraHidden').val() === null) ? valida = false : valida = valida;
    ($('#departamento').val() === "0") ? valida = false : valida = valida;
    ($('#subdepartamento').val() === "0") ? valida = false : valida = valida;

    return valida;
}