<?php
/**
 * Template del formulario público de reclamación de arrepentimiento.
 *
 * Variables disponibles: ninguna, se usa el shortcode directamente.
 *
 * @package WoosalesArrepentimiento
 */

if (!defined('ABSPATH')) {
    exit;
}
?>
<div class="wa-form-wrapper" id="wa-form-reclamacion">
    <h2 class="wa-form-title"><?php esc_html_e('Derecho de Arrepentimiento — Ley 24.240', 'woosales-arrepentimiento'); ?></h2>

    <div class="wa-legal-notice">
        <p><strong><?php esc_html_e('A fin de ejercer su derecho de revocación de compra, es fundamental tener presente lo siguiente:', 'woosales-arrepentimiento'); ?></strong></p>

        <p><?php esc_html_e('Para SERVICIOS POR EL DÍA: no más de 10 días corridos desde el momento en que realizó la compra online. En caso de que la fecha de la reserva ya haya transcurrido, no será posible realizar un reintegro. Por este motivo, es fundamental que complete la solicitud al menos 24 horas antes del servicio.', 'woosales-arrepentimiento'); ?></p>

        <p><?php esc_html_e('Para SERVICIOS DE ALOJAMIENTO: no más de 10 días corridos desde el momento en que se realizó el pago, ni menos de 10 días antes de la fecha de ingreso a nuestro hotel.', 'woosales-arrepentimiento'); ?></p>

        <p><?php esc_html_e('La devolución se realizará a través del medio de pago utilizado para la compra. Tarjetas de Crédito y Débito: se acreditará en el resumen siguiente ya que se solicitará una anulación de la compra. QR y transferencia: demora de 45 días una vez recibidos el CBU y CUIL.', 'woosales-arrepentimiento'); ?></p>

        <p><?php esc_html_e('Se reintegrará el monto total del pedido realizado. No se realizarán reintegros parciales.', 'woosales-arrepentimiento'); ?></p>

        <blockquote class="wa-highlight">
            <?php esc_html_e('El derecho de arrepentimiento (Ley 24.240) podrá ejercerse dentro de los 10 días corridos desde la fecha de compra, siempre que el mismo sea comunicado con una antelación mínima de 48 horas hábiles al inicio del servicio contratado. Una vez prestado el servicio, o si el plazo de 10 días excede la fecha de la reserva, el derecho de arrepentimiento carecerá de validez, rigiéndose la cancelación por nuestras políticas comerciales vigentes.', 'woosales-arrepentimiento'); ?>
        </blockquote>
    </div>

    <form id="wa-form" class="wa-form" novalidate>
        <div class="wa-form-field">
            <label for="wa-pedido"><?php esc_html_e('Número de Pedido / Reserva *', 'woosales-arrepentimiento'); ?></label>
            <input type="text" id="wa-pedido" name="pedido_id" required
                   placeholder="<?php esc_attr_e('Ej: 4521', 'woosales-arrepentimiento'); ?>"
                   inputmode="numeric" pattern="[0-9]*">
            <span class="wa-field-hint"><?php esc_html_e('Lo encontrás en tu email de confirmación de compra.', 'woosales-arrepentimiento'); ?></span>
        </div>

        <div class="wa-form-row">
            <div class="wa-form-field">
                <label for="wa-nombre"><?php esc_html_e('Nombre Completo *', 'woosales-arrepentimiento'); ?></label>
                <input type="text" id="wa-nombre" name="nombre" required
                       placeholder="<?php esc_attr_e('Tu nombre', 'woosales-arrepentimiento'); ?>">
            </div>

            <div class="wa-form-field">
                <label for="wa-email"><?php esc_html_e('Email *', 'woosales-arrepentimiento'); ?></label>
                <input type="email" id="wa-email" name="email" required
                       placeholder="<?php esc_attr_e('tu@email.com', 'woosales-arrepentimiento'); ?>">
            </div>
        </div>

        <div class="wa-form-field">
            <label for="wa-fecha-reserva"><?php esc_html_e('Fecha de la Reserva (opcional)', 'woosales-arrepentimiento'); ?></label>
            <input type="date" id="wa-fecha-reserva" name="fecha_reserva">
            <span class="wa-field-hint"><?php esc_html_e('Ayuda a validar si tu solicitud está dentro del plazo legal.', 'woosales-arrepentimiento'); ?></span>
        </div>

        <div class="wa-form-field wa-checkbox-field">
            <label>
                <input type="checkbox" id="wa-terminos" name="acepta_terminos" required>
                <span><?php esc_html_e('He leído y acepto los términos y condiciones del derecho de arrepentimiento detallados arriba.', 'woosales-arrepentimiento'); ?></span>
            </label>
        </div>

        <div class="wa-form-actions">
            <button type="submit" class="wa-btn wa-btn-primary" id="wa-submit-btn">
                <?php esc_html_e('Enviar Solicitud de Arrepentimiento', 'woosales-arrepentimiento'); ?>
            </button>
        </div>

        <div id="wa-form-messages" class="wa-messages" style="display:none;"></div>
    </form>
</div>
