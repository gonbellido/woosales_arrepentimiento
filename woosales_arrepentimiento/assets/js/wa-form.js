/**
 * wa-form.js — Manejo AJAX del formulario de arrepentimiento.
 */
(function ($) {
    'use strict';

    const $form = $('#wa-form');
    const $messages = $('#wa-form-messages');
    const $submitBtn = $('#wa-submit-btn');

    if (!$form.length) return;

    $form.on('submit', function (e) {
        e.preventDefault();

        // Validación HTML5 nativa
        if (!this.checkValidity()) {
            this.reportValidity();
            return;
        }

        $submitBtn.prop('disabled', true).text(WA_Form.text.sending);
        $messages.hide().removeClass('wa-alert-success wa-alert-error wa-alert-warning').empty();

        const data = {
            action: 'wa_enviar_reclamacion',
            nonce: WA_Form.nonce,
            pedido_id: $('#wa-pedido').val().trim(),
            nombre: $('#wa-nombre').val().trim(),
            email: $('#wa-email').val().trim(),
            fecha_reserva: $('#wa-fecha-reserva').val(),
            acepta_terminos: $('#wa-terminos').is(':checked') ? 1 : 0,
        };

        $.post(WA_Form.ajax_url, data, function (response) {
            $submitBtn.prop('disabled', false).text('Enviar Solicitud de Arrepentimiento');

            if (response.success) {
                let html = '<p><strong>' + WA_Form.text.success_title + '</strong></p>';
                html += '<span class="wa-code-display">' + response.data.codigo + '</span>';
                html += '<p>Estado: <strong>' + response.data.estado + '</strong></p>';

                if (response.data.enlace_seguimiento) {
                    html += '<p><a href="' + response.data.enlace_seguimiento + '" target="_blank">Consultar estado de mi reclamación</a></p>';
                }

                html += '<p style="font-size:13px;color:#888;">Recibirás un email con esta información.</p>';

                if (response.data.advertencias && response.data.advertencias.length > 0) {
                    html += '<hr><p style="font-weight:600;color:#e65100;">Advertencias:</p><ul>';
                    response.data.advertencias.forEach(function (adv) {
                        html += '<li style="font-size:13px;color:#e65100;">' + adv + '</li>';
                    });
                    html += '</ul>';
                }

                $messages.addClass('wa-alert-success').html(html).show();
                $form.slideUp();
            } else {
                let html = '';
                if (response.data && response.data.errores) {
                    html = '<ul>';
                    response.data.errores.forEach(function (err) {
                        html += '<li>' + err + '</li>';
                    });
                    html += '</ul>';
                } else {
                    html = '<p>' + WA_Form.text.error_generic + '</p>';
                }
                $messages.addClass('wa-alert-error').html(html).show();
            }

            $('html, body').animate({ scrollTop: $messages.offset().top - 100 }, 300);
        }).fail(function () {
            $submitBtn.prop('disabled', false).text('Enviar Solicitud de Arrepentimiento');
            $messages.addClass('wa-alert-error')
                .html('<p>' + WA_Form.text.error_generic + '</p>')
                .show();
        });
    });
})(jQuery);
