import * as fx from '../functions/functions.js';

$(document).ready(()=> {
    // log all users
    $(document).on('click', '#login', async (e) => {
        e.preventDefault();
        let remember = $("#remember").prop("checked") ? 1 : 0;

        const email = fx.get_value('email');
        const password = fx.get_value('password');

        if(!email && !password) {
            fx.show_message('Veuillez completer tous les champs svp', 'info');
            return;
        }
        const data = {
            email: fx.get_value('email'),
            password: fx.get_value('password'),
            remember: remember,
            action: 'login',
        };

        const url = fx.get_controller_url('api');
        const status = await fx.save(data, url, null, '#login');

        if (status) {
            setInterval(()=> {
                fx.redirect('./');
            }, 2000);
        }
    });


});