$(document).ready(function($) {

    cargarSelect('usuario', 'listadoUsuarios');
    cargarSelect('rol', 'listadoRoles');
    $(document).on("change", "select", function() {
        if (this.id === 'usuario') {
            direccionEnvio = '../dao/select/usuarioPorId.php';
            valor = "id=" + $("#usuario").val();
            $.post(direccionEnvio, valor, function(response) {
                response = get_hostname(response);
                $("#nombre").val(response.data[0].nombre);
                $("#apellido").val(response.data[0].apellido);
                $("#login").val(response.data[0].email);
                $("#password").val(response.data[0].password);
                $("#rol").val(response.data[0].rol);
                if (response.data[0].rol === "3") {
                    $('#capasRol').css('display', 'block');
                    cargarSelect('departamento', 'listadoDepartamentos');
                    vaciarSelectMultiple('departamentoUsuario');
                    cargarSelect('departamentoUsuario', $("#usuario").val());

                } else {
                    $('#capasRol').css('display', 'none');
                }
            });
        }
    });

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
            return !$('#departamento option:selected').remove().appendTo('#departamentoUsuario');
        }
        if (this.id === 'departamentoUsuario') {
            return !$('#departamentoUsuario option:selected').remove().appendTo('#departamento');
        }
    });

    $('#modificacionUsuarioButton').click(function() {
        mostrarSpin();
        direccionEnvio = '../dao/update/modificacionUsuario.php';
        selectValueSelected = "";
        $("#departamentoUsuario > option").each(function() {
            selectValueSelected += this.value + ',';
        });

        valor = "usuarioHidden=" + $("#usuario").val() +
            "&nombreHidden=" + $("#nombre").val() +
            "&apellidoHidden=" + $("#apellido").val() +
            "&emailHidden=" + $("#login").val() +
            "&passwordHidden=" + $("#password").val() +
            "&rolHidden=" + $("#rol").val() +
            "&destinoHidden=" + selectValueSelected.slice(0, -1);

        $.post(direccionEnvio, valor, function(response) {
            borrarSpin();
            response = get_hostname(response);
            successNotifier(response.message);
            $('#modificacionUsuarioForm').trigger("reset");
            cargarSelect('departamento', 'listadoDepartamentos');
            $('#seleccionado').empty();
            $('#capasRol').css('display', 'none');
        });
    });
});