/**
 * wa-form.js — Manejo AJAX del formulario + Modal popup.
 */
(function ($) {
    'use strict';

    // ── Form submission (soporta múltiples instancias con clases) ──
    $(document).on('submit', '.wa-form', function (e) {
        e.preventDefault();

        var $form      = $(this);
        var $messages  = $form.find('.wa-messages');
        var $submitBtn = $form.find('.wa-submit-btn');

        if (!this.checkValidity()) {
            this.reportValidity();
            return;
        }

        $submitBtn.prop('disabled', true).text(WA_Form.text.sending);
        $messages.hide().removeClass('wa-alert wa-alert-success wa-alert-error wa-alert-warning').empty();

        var data = {
            action:         'wa_enviar_reclamacion',
            nonce:          WA_Form.nonce,
            pedido_id:       $form.find('.wa-input-pedido').val().trim(),
            nombre:          $form.find('.wa-input-nombre').val().trim(),
            email:           $form.find('.wa-input-email').val().trim(),
            fecha_reserva:   $form.find('.wa-input-fecha').val(),
            acepta_terminos: $form.find('.wa-input-terminos').is(':checked') ? 1 : 0,
            captcha_answer:  $form.find('.wa-input-captcha').val().trim(),
            captcha_token:   $form.find('.wa-input-captcha-token').val(),
            wa_website:      $form.find('#wa_website').val(),
        };

        $.post(WA_Form.ajax_url, data, function (response) {
            $submitBtn.prop('disabled', false).text(WA_Form.text.submit_btn);

            if (response.success) {
                var html = '<p><strong>' + WA_Form.text.success_title + '</strong></p>';
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

                $messages.addClass('wa-alert wa-alert-success').html(html).show();
                $form.find('.wa-legal-notice, .wa-form-field, .wa-form-actions, .wa-form-row').slideUp();
            } else {
                var html = '';
                if (response.data && response.data.errores) {
                    html = '<ul>';
                    response.data.errores.forEach(function (err) {
                        html += '<li>' + err + '</li>';
                    });
                    html += '</ul>';
                } else {
                    html = '<p>' + WA_Form.text.error_generic + '</p>';
                }
                $messages.addClass('wa-alert wa-alert-error').html(html).show();
            }

            // Scroll to messages
            $('html, body').animate({ scrollTop: $messages.offset().top - 100 }, 300);
        }).fail(function () {
            $submitBtn.prop('disabled', false).text(WA_Form.text.submit_btn);
            $messages.addClass('wa-alert wa-alert-error')
                .html('<p>' + WA_Form.text.error_generic + '</p>')
                .show();
        });
    });

    // ── Modal Popup ──
    window.WA_Modal = {
        open: function () {
            var $modal = $('#wa-modal');
            $modal.css({ display: 'flex', opacity: 0 }).animate({ opacity: 1 }, 200);
            $('body').css('overflow', 'hidden');
        },
        close: function () {
            var $modal = $('#wa-modal');
            $modal.animate({ opacity: 0 }, 150, function () {
                $modal.css('display', 'none');
            });
            $('body').css('overflow', '');
        }
    };

    // Close on overlay click
    $(document).on('click', '#wa-modal', function (e) {
        if (e.target === this) {
            window.WA_Modal.close();
        }
    });

    // Close on Escape key
    $(document).on('keydown', function (e) {
        if (e.key === 'Escape' && $('#wa-modal').is(':visible')) {
            window.WA_Modal.close();
        }
    });
})(jQuery);
