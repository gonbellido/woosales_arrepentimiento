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
    <h2 class="wa-form-title"><?php esc_html_e('Derecho de Arrepentimiento — Ley 24.240', 'boton-de-arrepentimiento-argentina-woosales-2'); ?></h2>

    <div class="wa-legal-notice">
        <?php echo isset($wa_texto_legal) ? wp_kses_post($wa_texto_legal) : wp_kses_post(\WoosalesArrepentimiento\WA_Settings::get_legal_text()); ?>
    </div>

    <form class="wa-form" novalidate>
        <!-- Honeypot: fuera de pantalla, no visible para humanos -->
        <div class="wa-hp-field" aria-hidden="true" style="position:absolute;left:-9999px;top:-9999px;height:0;overflow:hidden;opacity:0;pointer-events:none;">
            <label for="wa_website"><?php esc_html_e('Sitio web', 'boton-de-arrepentimiento-argentina-woosales-2'); ?></label>
            <input type="text" name="wa_website" id="wa_website" tabindex="-1" autocomplete="off" value="">
        </div>

        <div class="wa-form-field">
            <label for="" class="wa-label-pedido"><?php esc_html_e('Número de Pedido / Reserva *', 'boton-de-arrepentimiento-argentina-woosales-2'); ?></label>
            <input type="text" class="wa-input-pedido" name="pedido_id" required
                   placeholder="<?php esc_attr_e('Ej: 4521', 'boton-de-arrepentimiento-argentina-woosales-2'); ?>"
                   inputmode="numeric" pattern="[0-9]*">
            <span class="wa-field-hint"><?php esc_html_e('Lo encontrás en tu email de confirmación de compra.', 'boton-de-arrepentimiento-argentina-woosales-2'); ?></span>
        </div>

        <div class="wa-form-row">
            <div class="wa-form-field">
                <label for="" class="wa-label-nombre"><?php esc_html_e('Nombre Completo *', 'boton-de-arrepentimiento-argentina-woosales-2'); ?></label>
                <input type="text" class="wa-input-nombre" name="nombre" required
                       placeholder="<?php esc_attr_e('Tu nombre', 'boton-de-arrepentimiento-argentina-woosales-2'); ?>">
            </div>

            <div class="wa-form-field">
                <label for="" class="wa-label-email"><?php esc_html_e('Email *', 'boton-de-arrepentimiento-argentina-woosales-2'); ?></label>
                <input type="email" class="wa-input-email" name="email" required
                       placeholder="<?php esc_attr_e('tu@email.com', 'boton-de-arrepentimiento-argentina-woosales-2'); ?>">
            </div>
        </div>

        <div class="wa-form-field">
            <label for="" class="wa-label-fecha"><?php esc_html_e('Fecha de la Reserva (opcional)', 'boton-de-arrepentimiento-argentina-woosales-2'); ?></label>
            <input type="date" class="wa-input-fecha" name="fecha_reserva">
            <span class="wa-field-hint"><?php esc_html_e('Ayuda a validar si tu solicitud está dentro del plazo legal.', 'boton-de-arrepentimiento-argentina-woosales-2'); ?></span>
        </div>

        <?php
        $woosales_arg_captcha_data = isset($wa_captcha) ? $wa_captcha : \WoosalesArrepentimiento\WA_Form_Handler::generar_captcha();
        ?>
        <div class="wa-form-field wa-captcha-field">
            <label><?php echo esc_html($woosales_arg_captcha_data['question']); ?> *</label>
            <input type="text" class="wa-input-captcha" name="captcha_answer" required
                   inputmode="numeric" pattern="[0-9]*" autocomplete="off"
                   placeholder="<?php esc_attr_e('Tu respuesta', 'boton-de-arrepentimiento-argentina-woosales-2'); ?>">
            <input type="hidden" class="wa-input-captcha-token" name="captcha_token"
                   value="<?php echo esc_attr($woosales_arg_captcha_data['token']); ?>">
        </div>

        <div class="wa-form-field wa-checkbox-field">
            <label>
                <input type="checkbox" class="wa-input-terminos" name="acepta_terminos" required>
                <span><?php esc_html_e('He leído y acepto los términos y condiciones del derecho de arrepentimiento detallados arriba.', 'boton-de-arrepentimiento-argentina-woosales-2'); ?></span>
            </label>
        </div>

        <div class="wa-form-actions">
            <button type="submit" class="wa-btn wa-btn-primary wa-submit-btn">
                <?php esc_html_e('Enviar Solicitud de Arrepentimiento', 'boton-de-arrepentimiento-argentina-woosales-2'); ?>
            </button>
        </div>

        <div class="wa-messages" style="display:none;"></div>
    </form>
</div>
