import * as fx from '../functions/functions.js';

$(document).ready(function() {
    // Get promotion & annee from url
    const path = window.location.pathname;
    const parts = path.split("-");

    let annee = 0;
    let promotion = 0;

    if (parts[1]) {
        annee = parts[1] + '-' + parts[2];
    }

    if (parts[3]) {
        promotion = parts[3];
    }

    get_data();
    get_annee();
    get_promotion();
    get_enseigant();
    get_etudiant();
    get_conversation();
    get_conversation_group();

    // Save or update affectation btn
    $(document).on('click', '#save', async (e) => {
        e.preventDefault();

        const data = {
          annee: annee,
          promotion: promotion,
          etudiant: fx.get_value('etudiant'),
          encadreur: fx.get_value('encadreur'),
          action: 'save',
        };

        const url = fx.get_controller_url('affectation');
        const status = await fx.save(data, url, 'exampleModalToggle', '#save');

        if (status) {
          get_data();
        }
    });

    // get all affectations by annee
    function get_data() {
        const data = {
            annee: annee,
            promotion: promotion,
            action: 'load',
        };
        const url = fx.get_controller_url('affectation');
        const container = 'container';
        fx.handle_display({
            data: data, url: url, container: container
        });
    }

    $('#search').on('input', function() {
        const searchValue = $(this).val().trim(); // Récupérer la valeur de recherche
        const data = {
            annee: annee,
            promotion: promotion,
            action: 'load',
        };

        const url = fx.get_controller_url('affectation');
        const container = 'container';

        // Appeler la fonction handle_display avec les critères de recherche
        fx.handle_display({
            data: data,
            url: url,
            container: container,
            searchQuery: searchValue
        });
    });

    // get promotion
    function get_promotion() {
        const data = {
            action: 'get_promotion'
        };

        const url = fx.get_controller_url('api');
        fx.fill_select(url, data, 'promotion');
    }

    // get encadreur
    function get_enseigant() {
        const data = {
            action: 'get_enseigant'
        };

        const url = fx.get_controller_url('api');
        fx.fill_select(url, data, 'encadreur');
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

    // get etudiant
    function get_annee() {
        const data = {
            action: 'get_annee'
        };

        const url = fx.get_controller_url('api');
        fx.fill_select(url, data, 'annee');
    }

    // Next event
    $(document).on('click', '#next', function() {
        const annee = fx.get_value('annee');
        const promotion = fx.get_value('promotion');
        if(promotion) {
            fx.redirect('./affectations-' + annee + '-' + promotion);
        } else {
            fx.show_message('Veuillez compléter les champs marqués par <b class="star">*</b>' + annee, 'info', 10);
        }
    });

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

    setInterval(()=>{
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
});