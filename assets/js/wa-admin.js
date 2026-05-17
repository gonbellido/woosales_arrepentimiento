/**
 * wa-admin.js — Funcionalidad JS del panel de administración.
 */
(function ($) {
    'use strict';

    // Confirmación para acciones rápidas de estado
    $(document).on('click', '.row-actions a[href*="wa_quick_status"]', function (e) {
        var label = $(this).text().trim();
        if (!confirm('¿Confirmás cambiar esta reclamación a: ' + label + '?')) {
            e.preventDefault();
        }
    });
})(jQuery);
