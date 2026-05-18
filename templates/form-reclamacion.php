<?php
/**
 * Template del formulario público de reclamación de arrepentimiento.
 *
 * Usa clases CSS en lugar de IDs para soportar múltiples instancias
 * (formulario inline + modal vía shortcode [wa_boton_arrepentimiento]).
 *
 * @package WoosalesArrepentimiento
 */

if (!defined('ABSPATH')) {
    exit;
}
?>
<div class="wa-form-wrapper">
    <h2 class="wa-form-title"><?php esc_html_e('Derecho de Arrepentimiento — Ley 24.240', 'woosales-arrepentimiento'); ?></h2>

    <div class="wa-legal-notice">
        <?php echo isset($wa_texto_legal) ? wp_kses_post($wa_texto_legal) : \WoosalesArrepentimiento\WA_Settings::get_legal_text(); ?>
    </div>

    <form class="wa-form" novalidate>
        <!-- Honeypot: oculto via CSS, no lo ve el usuario, bots lo llenan -->
        <div class="wa-hp-field" aria-hidden="true">
            <label for="wa_website"><?php esc_html_e('Sitio web', 'woosales-arrepentimiento'); ?></label>
            <input type="text" name="wa_website" id="wa_website" tabindex="-1" autocomplete="off" value="">
        </div>

        <div class="wa-form-field">
            <label for="" class="wa-label-pedido"><?php esc_html_e('Número de Pedido / Reserva *', 'woosales-arrepentimiento'); ?></label>
            <input type="text" class="wa-input-pedido" name="pedido_id" required
                   placeholder="<?php esc_attr_e('Ej: 4521', 'woosales-arrepentimiento'); ?>"
                   inputmode="numeric" pattern="[0-9]*">
            <span class="wa-field-hint"><?php esc_html_e('Lo encontrás en tu email de confirmación de compra.', 'woosales-arrepentimiento'); ?></span>
        </div>

        <div class="wa-form-row">
            <div class="wa-form-field">
                <label for="" class="wa-label-nombre"><?php esc_html_e('Nombre Completo *', 'woosales-arrepentimiento'); ?></label>
                <input type="text" class="wa-input-nombre" name="nombre" required
                       placeholder="<?php esc_attr_e('Tu nombre', 'woosales-arrepentimiento'); ?>">
            </div>

            <div class="wa-form-field">
                <label for="" class="wa-label-email"><?php esc_html_e('Email *', 'woosales-arrepentimiento'); ?></label>
                <input type="email" class="wa-input-email" name="email" required
                       placeholder="<?php esc_attr_e('tu@email.com', 'woosales-arrepentimiento'); ?>">
            </div>
        </div>

        <div class="wa-form-field">
            <label for="" class="wa-label-fecha"><?php esc_html_e('Fecha de la Reserva (opcional)', 'woosales-arrepentimiento'); ?></label>
            <input type="date" class="wa-input-fecha" name="fecha_reserva">
            <span class="wa-field-hint"><?php esc_html_e('Ayuda a validar si tu solicitud está dentro del plazo legal.', 'woosales-arrepentimiento'); ?></span>
        </div>

        <?php
        $wa_captcha_data = isset($wa_captcha) ? $wa_captcha : \WoosalesArrepentimiento\WA_Form_Handler::generar_captcha();
        ?>
        <div class="wa-form-field wa-captcha-field">
            <label><?php echo esc_html($wa_captcha_data['question']); ?> *</label>
            <input type="text" class="wa-input-captcha" name="captcha_answer" required
                   inputmode="numeric" pattern="[0-9]*" autocomplete="off"
                   placeholder="<?php esc_attr_e('Tu respuesta', 'woosales-arrepentimiento'); ?>">
            <input type="hidden" class="wa-input-captcha-token" name="captcha_token"
                   value="<?php echo esc_attr($wa_captcha_data['token']); ?>">
        </div>

        <div class="wa-form-field wa-checkbox-field">
            <label>
                <input type="checkbox" class="wa-input-terminos" name="acepta_terminos" required>
                <span><?php esc_html_e('He leído y acepto los términos y condiciones del derecho de arrepentimiento detallados arriba.', 'woosales-arrepentimiento'); ?></span>
            </label>
        </div>

        <div class="wa-form-actions">
            <button type="submit" class="wa-btn wa-btn-primary wa-submit-btn">
                <?php esc_html_e('Enviar Solicitud de Arrepentimiento', 'woosales-arrepentimiento'); ?>
            </button>
        </div>

        <div class="wa-messages" style="display:none;"></div>
    </form>
</div>
