$(document).ready(function ($) {
    
    localStorage.setItem('solicitudTrabajo',null);

    if (localStorage.getItem('usuarioRole') !== "4") {
        new Noty({
            type: 'error',
            layout: 'topRight',
            theme: 'nest',
            text: 'Rol Incorrecto. Será redirigido a la página de login.',
            timeout: '4000',
            progressBar: true,
            closeWith: ['click'],
            killer: true
        }).show();

        window.setTimeout(function() {
            document.location.href = '../index.html';
        }, 2000);
    }

    $.post('../dao/select/cargarTrabajo.php', function (response) {
        response = get_hostname(response);
        var listadoSolicitudes = response.data;
        var resultadoPendiente = "[";
        var resultadoActivo = "[";
        var resultadoGuardado = "[";

        for (var key in listadoSolicitudes) {
            if (listadoSolicitudes.hasOwnProperty(key)) {
                var descripcion = JSON.stringify(listadoSolicitudes[key].descripcion).replace(/\"/g, '').trim();
                var fechaAlta = JSON.stringify(listadoSolicitudes[key].fechaAlta).replace(/\"/g, '').trim();
                var id = JSON.stringify(listadoSolicitudes[key].id).replace(/\"/g, '').trim();
                var nombreDepartamento = JSON.stringify(listadoSolicitudes[key].nombreDepartamento).replace(/\"/g, '').trim();
                var plantilla = JSON.stringify(listadoSolicitudes[key].plantilla).replace(/\"/g, '').trim();
                var solicitante = JSON.stringify(listadoSolicitudes[key].solicitante).replace(/\"/g, '').trim();
                var status = JSON.stringify(listadoSolicitudes[key].status).replace(/\"/g, '').trim();

                if (status === '2'){
                    if (resultadoPendiente !== "[") {
                        resultadoPendiente += ",";
                    }
                    resultadoPendiente += "{ \"id\" :\"" + id +
                                            "\", \"departamento\": \"" + nombreDepartamento +
                                            "\", \"solicitante\": \"" + solicitante +
                                            "\", \"fechaAlta\": \"" + fechaAlta +
                                            "\", \"descripcion\": \"" + descripcion +
                                            "\", \"operaciones\": \"<span id='"+id+"' style='cursor:pointer' class='trabajoClass'>Realizar Trabajo</span>\"}";
                } else if(status === '4'){
                    if (resultadoActivo !== "[") {
                        resultadoActivo += ",";
                    }
                    resultadoActivo += "{ \"id\" :\"" + id +
                                        "\", \"departamento\": \"" + nombreDepartamento +
                                        "\", \"solicitante\": \"" + solicitante +
                                        "\", \"fechaAlta\": \"" + fechaAlta +
                                        "\", \"descripcion\": \"" + descripcion +
                                        "\", \"plantilla\": \"" + plantilla +
                                        "\", \"operaciones\": \"<span id='"+id+"' style='cursor:pointer' class='trabajoClass'>Realizar Trabajo</span>\"}";
                } else {
                    if (resultadoGuardado !== "[") {
                        resultadoGuardado += ",";
                    }
                    resultadoGuardado += "{ \"id\" :\"" + id +
                                        "\", \"departamento\": \"" + nombreDepartamento +
                                        "\", \"solicitante\": \"" + solicitante +
                                        "\", \"fechaAlta\": \"" + fechaAlta +
                                        "\", \"descripcion\": \"" + descripcion +
                                        "\", \"operaciones\": \"<span id='"+id+"' style='cursor:pointer' class='trabajoClass'>Realizar Trabajo</span>\"}";
                }
                
            }
        }

        resultadoPendiente += "]";
        resultadoActivo += "]";
        resultadoGuardado += "]";

        $('#pendientes').bootstrapTable('destroy'); //Destroy bootstrap table
        $('#pendientes').bootstrapTable({
            pagination: false,
            search: false,
            sortable: false,
            cache: false,
            //   escape: true,
            columns: [
                { field: 'id', title: 'Solicitud' },
                { field: 'departamento', title: 'Departamento' },
                { field: 'solicitante', title: 'Solicitante' },
                { field: 'fechaAlta', title: 'Fecha de Alta' },
                { field: 'descripcion', title: 'Descripcion' },
                { field: 'operaciones', title: 'Operaciones' }],
            data: JSON.parse(resultadoPendiente)
        });



        $('#activos').bootstrapTable('destroy'); //Destroy bootstrap table
        $('#activos').bootstrapTable({
            pagination: false,
            search: false,
            sortable: false, 
            cache: false,
            //   escape: true,
            columns: [
                { field: 'id', title: 'Solicitud' },
                { field: 'departamento', title: 'Departamento' },
                { field: 'solicitante', title: 'Solicitante' },
                { field: 'fechaAlta', title: 'Fecha de Alta' },
                { field: 'descripcion', title: 'Descripcion' },
                { field: 'plantilla', title: 'Plantilla' },
                { field: 'operaciones', title: 'Operaciones' }],
            data: JSON.parse(resultadoActivo)
        });

        $('#guardados').bootstrapTable('destroy'); //Destroy bootstrap table
        $('#guardados').bootstrapTable({
            pagination: false,
            search: false,
            sortable: false,
            cache: false,
            //   escape: true,
            columns: [
                { field: 'id', title: 'Solicitud' },
                { field: 'departamento', title: 'Departamento' },
                { field: 'solicitante', title: 'Solicitante' },
                { field: 'fechaAlta', title: 'Fecha de Alta' },
                { field: 'descripcion', title: 'Descripcion' },
                { field: 'operaciones', title: 'Operaciones' }],
            data: JSON.parse(resultadoGuardado)
        });
    });

    /** */
    $(document).on('click', '.trabajoClass', function() {
        localStorage.setItem('solicitudTrabajo',this.id);
        window.location.href='detalleTrabajo.html';
    });

    $('#cerrarSession').on('click', function() {
        window.localStorage.removeItem('usuarioNombre');
        window.localStorage.removeItem('usuarioRole');
        window.localStorage.removeItem('solicitudTrabajo');
        window.localStorage.removeItem('usuarioEmail');
        window.localStorage.removeItem('usuario');
        window.localStorage.removeItem('usuarioId');
        window.location.href='../index.html';
    });

});

function get_hostname(response) {
    try{
        response = JSON.parse(response);    
    }catch{
        response = response;    
    }
    return response;
    // if (document.location.hostname === 'www.elpartedigital.com') {
    //     response = JSON.parse(response);
    // }
    // return response;
}