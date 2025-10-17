import * as fx from '../functions/functions.js';

$(document).ready(function() {
    // Get projet from url
    const path = window.location.pathname;
    const parts = path.split("-");

    let projet = 0;

    if (parts[1]) {
        projet = parts[1] + '-' + parts[2];
    }

    get_enseigant();
    get_project_by_id();
    get_project_encadreur_by_id();
    // get encadreur
    function get_enseigant() {
        const data = {
            action: 'get_enseigant'
        };

        const url = fx.get_controller_url('api');
        fx.fill_select(url, data, 'encadreur');
    }
    // get project by id
    function get_project_by_id(){
        const data = {
            action: 'get_project_by_id',
            projet: projet
        };

        const url = fx.get_controller_url('project-encadreur');
        fx.handle_display({
            data: data, url: url, container: 'hearder'
        });
    }
    // get project_encadreur by id
    function get_project_encadreur_by_id() {
        const data = {
            action: 'get_project_encadreur',
            projet: projet
        };

        const url = fx.get_controller_url('project-encadreur');
        fx.handle_display({
            data: data, url: url, container: 'get_all'
        });
    }
    function get_project_by_id(){
        const data = {
            action: 'get_project_by_id',
            projet: projet
        };

        const url = fx.get_controller_url('project-encadreur');
        fx.handle_display({
            data: data, url: url, container: 'hearder'
        });
    }
    // Save or update project btn
    $(document).on('click', '#save', async (e) => {
        e.preventDefault();

        const data = {
            projet: projet,
            enseignant: fx.get_value('encadreur'),
            id: fx.get_value('id'),
            role: fx.get_value('role'),
            action: 'save'
        };

        const url = fx.get_controller_url('project-encadreur');
        const status = await fx.save(data, url, 'exampleModalToggle', '#save');

        if (status) {
            get_enseigant();
            get_project_by_id();
            get_project_encadreur_by_id();
        }
    });

    fx.attach_edit_delete_event('.confirm', ['id_confirm']);

    $(document).on('click', '.confirm', async function(e) {
        e.preventDefault();

        // Buscar el atributo data-label directamente del botón si existe
        let label = $(this).closest('tr').find('[data-label]').data('label');
        let id = $(this).closest('tr').find('[data-id]').data('id');
        let status = $(this).closest('tr').find('[data-status]').data('status');
        $('#confirm_message').html(label);
        $('#id_confirm').val(id);
        $('#status').val(status);

    });


    $(document).on('click', '#confirm', async (e) => {
        e.preventDefault();
        const data = {
            id: fx.get_value('id_confirm'),
            status: fx.get_value('status'),
            action: 'confirme'
        };

        const url = fx.get_controller_url('project-encadreur');
        const status = await fx.save(data, url, null,'#confirme');

        if (status) {
            // Redirection vers la page de confirmation
            get_project_encadreur_by_id();
            $('#cofirmModal').modal('hide');
        }

    });


});