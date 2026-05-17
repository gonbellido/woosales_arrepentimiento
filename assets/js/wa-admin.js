/**
 * wa-admin.js — Funcionalidad JS del panel de administración.
 */
(function ($) {
    'use strict';

    // Confirmación para acciones rápidas de estado
    $(document).on('click', '.row-actions a[href*="wa_quick_status"]', function (e) {
        var label = $(this).text().trim();
        var msg = (typeof WA_Admin !== 'undefined' && WA_Admin.confirm_status)
            ? WA_Admin.confirm_status + ' ' + label + '?'
            : '¿Confirmás cambiar esta reclamación a: ' + label + '?';
        if (!confirm(msg)) {
            e.preventDefault();
        }
    });

    // Metabox: mostrar/ocultar campo de motivo de rechazo
    $(document).ready(function () {
        var $select = $('#wa_nuevo_estado');
        if (!$select.length) {
            return;
        }
        $select.on('change', function () {
            if ($(this).val() === 'rechazada') {
                $('#wa_motivo_rechazo_wrap').show();
                $('#wa_motivo_rechazo').prop('required', true);
            } else {
                $('#wa_motivo_rechazo_wrap').hide();
                $('#wa_motivo_rechazo').prop('required', false);
            }
        }).trigger('change');
    });
})(jQuery);
