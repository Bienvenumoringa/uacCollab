import * as fx from '../functions/functions.js';

$(document).ready(() => {
    // Get project id from url
    const path = window.location.pathname;
    const parts = path.split("-");
    let confirme = 0;
    if (parts[1]){
        confirme = parts[1];
    }

    // confirmer collaboration
    $(document).on('click', '#confirme', async (e) => {
        e.preventDefault();

        const data = {
            id: confirme,
            status: 0,
            action: 'confirme'
        };

        const url = fx.get_controller_url('project-encadreur');
        const status = await fx.save(data, url, null,'confirme');

        if (status) {
            // Redirection vers la page de confirmation
            window.location.href = "./";
        }
    });

});
