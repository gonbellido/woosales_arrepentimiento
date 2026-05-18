<?php
/**
 * Template de la página de seguimiento público.
 *
 * Variables disponibles:
 * @var string|null $resultado  Datos de la reclamación o null
 * @var string      $codigo     Código buscado
 * @var string      $error      Mensaje de error si no se encontró
 *
 * @package WoosalesArrepentimiento
 */

if (!defined('ABSPATH')) {
    exit;
}
?>
<div class="wa-tracking-wrapper">
    <h2 class="wa-tracking-title"><?php esc_html_e('Seguimiento de Reclamación', 'boton-de-arrepentimiento-argentina-woosales-2'); ?></h2>
    <p><?php esc_html_e('Ingresá el código de trámite que recibiste por email para consultar el estado de tu reclamación.', 'boton-de-arrepentimiento-argentina-woosales-2'); ?></p>

    <form method="get" class="wa-tracking-form">
        <div class="wa-tracking-field">
            <input type="text" name="codigo" value="<?php echo esc_attr($codigo); ?>"
                   placeholder="<?php esc_attr_e('Código de trámite (ej: 847-4521-20260517)', 'boton-de-arrepentimiento-argentina-woosales-2'); ?>"
                   required>
            <button type="submit" class="wa-btn wa-btn-primary">
                <?php esc_html_e('Consultar', 'boton-de-arrepentimiento-argentina-woosales-2'); ?>
            </button>
        </div>
    </form>

    <?php if ($error): ?>
        <div class="wa-alert wa-alert-error">
            <p><?php echo esc_html($error); ?></p>
        </div>
    <?php endif; ?>

    <?php if ($resultado): ?>
        <div class="wa-tracking-result">
            <h3><?php esc_html_e('Detalle de tu Reclamación', 'boton-de-arrepentimiento-argentina-woosales-2'); ?></h3>

            <div class="wa-result-grid">
                <div class="wa-result-item">
                    <span class="wa-result-label"><?php esc_html_e('Código de Trámite', 'boton-de-arrepentimiento-argentina-woosales-2'); ?></span>
                    <span class="wa-result-value wa-code"><?php echo esc_html($resultado['codigo']); ?></span>
                </div>

                <div class="wa-result-item">
                    <span class="wa-result-label"><?php esc_html_e('Pedido', 'boton-de-arrepentimiento-argentina-woosales-2'); ?></span>
                    <span class="wa-result-value">#<?php echo esc_html($resultado['pedido']); ?></span>
                </div>

                <div class="wa-result-item">
                    <span class="wa-result-label"><?php esc_html_e('Fecha de Solicitud', 'boton-de-arrepentimiento-argentina-woosales-2'); ?></span>
                    <span class="wa-result-value"><?php echo esc_html($resultado['fecha_creacion']); ?></span>
                </div>

                <div class="wa-result-item wa-result-status">
                    <span class="wa-result-label"><?php esc_html_e('Estado Actual', 'boton-de-arrepentimiento-argentina-woosales-2'); ?></span>
                    <span class="wa-badge" style="background:<?php echo esc_attr($resultado['estado_color']); ?>">
                        <?php echo esc_html($resultado['estado_label']); ?>
                    </span>
                </div>
            </div>

            <?php if ($resultado['motivo_rechazo'] && $resultado['estado'] === WA_Status::RECHAZADA): ?>
                <div class="wa-rejection-reason">
                    <h4><?php esc_html_e('Motivo del Rechazo', 'boton-de-arrepentimiento-argentina-woosales-2'); ?></h4>
                    <p><?php echo esc_html($resultado['motivo_rechazo']); ?></p>
                </div>
            <?php endif; ?>

            <?php if (!empty($resultado['log'])): ?>
                <div class="wa-timeline">
                    <h4><?php esc_html_e('Historial de Estados', 'boton-de-arrepentimiento-argentina-woosales-2'); ?></h4>
                    <ul class="wa-timeline-list">
                        <?php foreach (array_reverse($resultado['log']) as $woosales_arg_entry): ?>
                            <li class="wa-timeline-item">
                                <span class="wa-timeline-date"><?php echo esc_html($woosales_arg_entry['fecha']); ?></span>
                                <span class="wa-timeline-change">
                                    <?php echo esc_html(WA_Status::label($woosales_arg_entry['de'])); ?>
                                    → <?php echo esc_html(WA_Status::label($woosales_arg_entry['a'])); ?>
                                </span>
                                <?php if (!empty($woosales_arg_entry['nota'])): ?>
                                    <span class="wa-timeline-note"><?php echo esc_html($woosales_arg_entry['nota']); ?></span>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>
