<?php
/**
 * Solicitud de review en WordPress.org — Admin Notice Dismissible.
 *
 * @package WoosalesArrepentimiento
 */

namespace WoosalesArrepentimiento;

if (!defined('ABSPATH')) {
    exit;
}

class WA_Review
{
    private const OPTION_INSTALL_DATE = 'wa_install_date';
    private const OPTION_DISMISSED    = 'wa_review_dismissed';

    public function __construct()
    {
        $this->maybe_set_install_date();

        add_action('admin_notices', [$this, 'maybe_show_notice']);
        add_action('wp_ajax_wa_dismiss_review', [$this, 'handle_dismiss']);
    }

    /**
     * Guardar fecha de instalación si no existe.
     */
    private function maybe_set_install_date(): void
    {
        if (!get_option(self::OPTION_INSTALL_DATE)) {
            add_option(self::OPTION_INSTALL_DATE, time());
        }
    }

    /**
     * Mostrar notice después de 7 días de uso, solo si no fue dismiss.
     */
    public function maybe_show_notice(): void
    {
        // Solo en pantallas del plugin o dashboard
        $screen = get_current_screen();
        if (!$screen) {
            return;
        }

        $allowed = ['dashboard', 'plugins', 'edit-wa_reclamacion', 'wa_reclamacion'];
        if (!in_array($screen->id, $allowed, true) && !in_array($screen->post_type ?? '', ['wa_reclamacion'], true)) {
            return;
        }

        $dismissed = get_option(self::OPTION_DISMISSED, '');
        if ($dismissed === 'done' || $dismissed === 'never') {
            return;
        }

        // Si eligió "recordar después", esperar 30 días más
        if ($dismissed && is_numeric($dismissed)) {
            if (time() < (int) $dismissed) {
                return;
            }
        }

        $install_date = (int) get_option(self::OPTION_INSTALL_DATE, time());
        $days_active  = (int) ((time() - $install_date) / DAY_IN_SECONDS);

        if ($days_active < 7) {
            return;
        }

        ?>
        <div class="notice notice-info is-dismissible wa-review-notice" style="display:flex;align-items:flex-start;gap:12px;padding:16px;">
            <div style="font-size:32px;line-height:1;">⭐</div>
            <div style="flex:1;">
                <p style="font-size:14px;margin:0 0 8px;">
                    <strong><?php esc_html_e('¿Te está sirviendo el Botón de Arrepentimiento Argentina?', 'boton-de-arrepentimiento-argentina-woosales'); ?></strong>
                </p>
                <p style="margin:0 0 12px;">
                    <?php esc_html_e('¡Nos ayudaría mucho que nos dejes una reseña en WordPress.org! Es gratis y lleva 1 minuto. Tu valoración ayuda a mantener el plugin actualizado.', 'boton-de-arrepentimiento-argentina-woosales'); ?>
                </p>
                <p style="margin:0;display:flex;gap:8px;flex-wrap:wrap;">
                    <a href="https://wordpress.org/support/plugin/woosales_arrepentimiento/reviews/#new-post"
                       target="_blank" rel="noopener"
                       class="button button-primary"
                       data-wa-dismiss="done">
                        <?php esc_html_e('⭐ ¡Dejar una reseña!', 'boton-de-arrepentimiento-argentina-woosales'); ?>
                    </a>
                    <a href="#" class="button" data-wa-dismiss="+30days">
                        <?php esc_html_e('Recordarme en 30 días', 'boton-de-arrepentimiento-argentina-woosales'); ?>
                    </a>
                    <a href="#" class="wa-dismiss-link" data-wa-dismiss="done">
                        <?php esc_html_e('Ya dejé mi reseña ✌️', 'boton-de-arrepentimiento-argentina-woosales'); ?>
                    </a>
                    <a href="#" class="wa-dismiss-link" data-wa-dismiss="never">
                        <?php esc_html_e('No mostrar más', 'boton-de-arrepentimiento-argentina-woosales'); ?>
                    </a>
                </p>
            </div>
        </div>
        <script>
        jQuery(function($){
            $('.wa-review-notice [data-wa-dismiss]').on('click', function(e){
                var dismissType = $(this).data('wa-dismiss');
                var value = dismissType === '+30days' ? <?php echo (int) strtotime('+30 days'); ?> : dismissType;

                $.post(ajaxurl, {
                    action: 'wa_dismiss_review',
                    dismiss: value,
                    nonce: '<?php echo esc_js(wp_create_nonce("wa_review_nonce")); ?>'
                });

                $('.wa-review-notice').fadeOut(300, function(){ $(this).remove(); });

                if (dismissType === 'done') {
                    window.open($(this).attr('href') || 'https://wordpress.org/support/plugin/woosales_arrepentimiento/reviews/#new-post', '_blank');
                }
                e.preventDefault();
            });
        });
        </script>
        <?php
    }

    /**
     * AJAX handler para guardar dismiss.
     */
    public function handle_dismiss(): void
    {
        check_ajax_referer('wa_review_nonce', 'nonce');

        $dismiss = sanitize_text_field(wp_unslash($_POST['dismiss'] ?? 'done'));
        $allowed = ['done', 'never', '+30days'];

        if (in_array($dismiss, $allowed, true)) {
            update_option(self::OPTION_DISMISSED, $dismiss);
        } elseif (is_numeric($dismiss)) {
            update_option(self::OPTION_DISMISSED, (int) $dismiss);
        }

        wp_die();
    }
}
