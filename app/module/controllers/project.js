import * as fx from '../functions/functions.js';

$(document).ready(()=> {

    const path = window.location.pathname;
    const parts = path.split("-");

    let annee = 0;
    let promotion = 0;
    let page = '';

    const urlPage = window.location.pathname; // ex: /uacCollab/projects-2024-2025-PRO01
    const match = urlPage.match(/\/(projects)[-/]/);
    if (match) {
        page = match[1]; // Affiche "projects"
    }

    if (parts[1]) {
        annee = parts[1] + '-' + parts[2];
    }

    if (parts[3]) {
        promotion = parts[3];
    }

    get_etudiant_by_encadreur();
    get_data();
    get_conversation();
    get_conversation_group();
    get_count_convesation();
    get_promotion();
    get_annee();
    get_etudiant();
    get_admin_project();
    get_admin_project_recent();

    // Save or update project btn
    $(document).on('click', '#save', async (e) => {
        e.preventDefault();

        const data = {
            title: fx.get_value('titre'),
            description: fx.get_value('description'),
            etudiant: fx.get_value('etudiant'),
            action: 'save'
        };

        const url = fx.get_controller_url('project');
        const status = await fx.save(data, url, 'exampleModalToggle', '#save');

        if (status) {
            get_data();
            get_admin_project();
            get_admin_project_recent();
        }
    });

    // get promotion
    function get_promotion() {
        const data = {
            action: 'get_promotion'
        };

        const url = fx.get_controller_url('api');
        fx.fill_select(url, data, 'promotion');
    }

    // get etudiant
    function get_annee() {
        const data = {
            action: 'get_annee'
        };

        const url = fx.get_controller_url('api');
        fx.fill_select(url, data, 'annee');
    }

    // get etudiant
    function get_etudiant() {
        const data = {
            annee: annee,
            promotion: promotion,
            action: 'get_etudiant'
        };

        const url = fx.get_controller_url('api');
        fx.fill_select(url, data, 'etudiant');
    }

    // get all project by directeur
    function get_data() {
        const data = {
            action: 'load',
        };
        const url = fx.get_controller_url('project');
        const container = 'container';
        fx.handle_display({
            data: data, url: url, container: container
        });
    }

    function get_admin_project() {
        const data = {
            CodPro: promotion,
            AnneeAcad: annee,
            action: 'get_admin_project',
        };
        const url = fx.get_controller_url('project');
        const container = 'admin-data';
        fx.handle_display({
            data: data, url: url, container: container
        });
    }

    function get_admin_project_recent() {
        const data = {
            action: 'get_admin_project_recent',
        };
        const url = fx.get_controller_url('project');
        const container = 'admin-data-recent';
        fx.handle_display({
            data: data, url: url, container: container
        });
    }
    // Get the students affected for a project associated with a supervisor and an academic year.
    function get_etudiant_by_encadreur() {
        const data = {
            action: 'get_etudiant_by_encadreur'
        };

        const url = fx.get_controller_url('api');
        fx.fill_select(url, data, 'etudiant');
    }

    setInterval(()=> {
        get_conversation();
        get_conversation_group();
        get_count_convesation();
    }, 3000);

    function get_conversation() {
        const data = {
            action: 'get_conversation',
        };
        const url = fx.get_controller_url('project');
        const container = 'conversation';
        fx.handle_display({
            data: data, url: url, container: container
        });
    }

    function get_count_convesation() {
        const data = {
            action: 'get_count_convesation',
        };
        const url = fx.get_controller_url('project');
        const container = 'count_convesation';
        fx.handle_display({
            data: data, url: url, container: container
        });
    }

    function get_conversation_group() {
        const data = {
            action: 'get_conversation_group',
        };
        const url = fx.get_controller_url('project');
        const container = 'conversation-group';
        fx.handle_display({
            data: data, url: url, container: container
        });
    }

    $('#query').on('input', function() {
        const searchValue = $(this).val().trim(); // Récupérer la valeur de recherche
        let data = [];
        let container = '';
        if(page && page == 'projects') {
            data = {
                CodPro: promotion,
                AnneeAcad: annee,
                action: 'get_admin_project',
            };
            container = 'admin-data';
        } else {
            data = {
                action: 'get_admin_project_recent',
            };
            container = 'admin-data-recent';
        }
        const url = fx.get_controller_url('project');

        // Appeler la fonction handle_display avec les critères de recherche
        fx.handle_display({
            data: data,
            url: url,
            container: container,
            searchQuery: searchValue
        });
    });

    // Next event
    $(document).on('click', '#next', function() {
        const annee = fx.get_value('annee');
        const promotion = fx.get_value('promotion');
        if(promotion && annee) {
            fx.redirect('./projects-' + annee + '-' + promotion);
        } else {
            fx.show_message('Veuillez compléter les champs marqués par <b class="star">*</b>' + annee, 'info', 10);
        }
    });

    fx.attach_edit_delete_event('.update', ['id', 'titre', 'description', 'etudiant']);

});

