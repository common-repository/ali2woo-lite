jQuery(function ($) {
    $(".a2wl_reset_order_data").on('click', function(event){
        event.preventDefault();
        const id = $('#post_ID').val();

        const data = {
            'action': 'a2wl_reset_order_data',
            'id': id,
            'ali2woo_nonce': a2wl_order_edit_script.nonce_action,
        };

        $.post(a2wl_order_edit_script.ajaxurl, data).done(function (response) {
            const json = JSON.parse(response);
            if (json.state !== 'ok') {
                alert('error');
                console.log(json);
            } else {
                window.location.reload();
            }
        }).fail(function (xhr, status, error) {
            console.log(error);
        });
    });   	
});
