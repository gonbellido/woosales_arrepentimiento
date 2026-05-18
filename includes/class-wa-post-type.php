<?php
/**
 * Custom Post Type wa_reclamacion y metaboxes.
 *
 * @package WoosalesArrepentimiento
 */

namespace WoosalesArrepentimiento;

if (!defined('ABSPATH')) {
    exit;
}

class WA_Post_Type
{
    public const CPT_SLUG = 'wa_reclamacion';

    public function __construct()
    {
        add_action('init', [$this, 'register']);
        add_action('add_meta_boxes', [$this, 'add_metaboxes']);
        add_action('save_post_' . self::CPT_SLUG, [$this, 'save_metaboxes'], 10, 2);
    }

    /**
     * Registrar CPT.
     */
    public function register(): void
    {
        $labels = [
            'name'               => __('Reclamaciones', 'boton-de-arrepentimiento-argentina-woosales-2'),
            'singular_name'      => __('Reclamación', 'boton-de-arrepentimiento-argentina-woosales-2'),
            'menu_name'          => __('Reclamaciones', 'boton-de-arrepentimiento-argentina-woosales-2'),
            'add_new'            => __('Agregar Nueva', 'boton-de-arrepentimiento-argentina-woosales-2'),
            'add_new_item'       => __('Nueva Reclamación', 'boton-de-arrepentimiento-argentina-woosales-2'),
            'edit_item'          => __('Editar Reclamación', 'boton-de-arrepentimiento-argentina-woosales-2'),
            'view_item'          => __('Ver Reclamación', 'boton-de-arrepentimiento-argentina-woosales-2'),
            'all_items'          => __('Todas las Reclamaciones', 'boton-de-arrepentimiento-argentina-woosales-2'),
            'search_items'       => __('Buscar Reclamaciones', 'boton-de-arrepentimiento-argentina-woosales-2'),
            'not_found'          => __('No se encontraron reclamaciones.', 'boton-de-arrepentimiento-argentina-woosales-2'),
            'not_found_in_trash' => __('No hay reclamaciones en papelera.', 'boton-de-arrepentimiento-argentina-woosales-2'),
        ];

        $args = [
            'labels'              => $labels,
            'public'              => false,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'menu_icon'           => 'dashicons-feedback',
            'menu_position'       => 56,
            'capability_type'     => 'post',
            'hierarchical'        => false,
            'supports'            => ['title'],
            'has_archive'         => false,
            'exclude_from_search' => true,
            'publicly_queryable'  => false,
            'show_in_rest'        => false,
        ];

        register_post_type(self::CPT_SLUG, $args);
    }

    /**
     * Agregar metaboxes al editor de reclamación.
     */
    public function add_metaboxes(): void
    {
        add_meta_box(
            'wa_datos_reclamacion',
            __('Datos de la Reclamación', 'boton-de-arrepentimiento-argentina-woosales-2'),
            [$this, 'render_metabox_datos'],
            self::CPT_SLUG,
            'normal',
            'high'
        );

        add_meta_box(
            'wa_gestion_reclamacion',
            __('Gestión de Estado', 'boton-de-arrepentimiento-argentina-woosales-2'),
            [$this, 'render_metabox_gestion'],
            self::CPT_SLUG,
            'side',
            'high'
        );
    }

    /**
     * Render metabox con datos de la reclamación.
     */
    public function render_metabox_datos(\WP_Post $post): void
    {
        wp_nonce_field('wa_save_metabox', 'wa_metabox_nonce');

        $pedido_id   = get_post_meta($post->ID, '_wa_pedido_id', true);
        $codigo      = get_post_meta($post->ID, '_wa_codigo_tramite', true);
        $email       = get_post_meta($post->ID, '_wa_email_cliente', true);
        $estado      = get_post_meta($post->ID, '_wa_estado', true) ?: WA_Status::default();
        $motivo_rechazo = get_post_meta($post->ID, '_wa_motivo_rechazo', true);
        $fecha_pedido = get_post_meta($post->ID, '_wa_fecha_pedido', true);
        ?>
        <table class="form-table">
            <tr>
                <th><label><?php esc_html_e('Código de Trámite', 'boton-de-arrepentimiento-argentina-woosales-2'); ?></label></th>
                <td><strong><?php echo esc_html($codigo); ?></strong></td>
            </tr>
            <tr>
                <th><label><?php esc_html_e('Pedido WooCommerce', 'boton-de-arrepentimiento-argentina-woosales-2'); ?></label></th>
                <td>
                    <?php if ($pedido_id && ($order = wc_get_order($pedido_id))): ?>
                        <a href="<?php echo esc_url($order->get_edit_order_url()); ?>">
                            #<?php echo esc_html($pedido_id); ?> — <?php echo esc_html($order->get_billing_first_name() . ' ' . $order->get_billing_last_name()); ?>
                        </a>
                        — Total: <?php echo wp_kses_post($order->get_formatted_order_total()); ?>
                    <?php else: ?>
                        #<?php echo esc_html($pedido_id); ?>
                        <?php if ($pedido_id): ?>
                            <em>(<?php esc_html_e('Pedido no encontrado en WooCommerce', 'boton-de-arrepentimiento-argentina-woosales-2'); ?>)</em>
                        <?php endif; ?>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <th><label><?php esc_html_e('Email Cliente', 'boton-de-arrepentimiento-argentina-woosales-2'); ?></label></th>
                <td><a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a></td>
            </tr>
            <tr>
                <th><label><?php esc_html_e('Fecha del Pedido', 'boton-de-arrepentimiento-argentina-woosales-2'); ?></label></th>
                <td><?php echo esc_html($fecha_pedido); ?></td>
            </tr>
            <tr>
                <th><label><?php esc_html_e('Estado Actual', 'boton-de-arrepentimiento-argentina-woosales-2'); ?></label></th>
                <td>
                    <span class="wa-badge" style="background:<?php echo esc_attr(WA_Status::color($estado)); ?>">
                        <?php echo esc_html(WA_Status::label($estado)); ?>
                    </span>
                </td>
            </tr>
            <?php if ($estado === WA_Status::RECHAZADA && $motivo_rechazo): ?>
            <tr>
                <th><label><?php esc_html_e('Motivo de Rechazo', 'boton-de-arrepentimiento-argentina-woosales-2'); ?></label></th>
                <td style="color:#d9534f;"><?php echo esc_html($motivo_rechazo); ?></td>
            </tr>
            <?php endif; ?>
        </table>
        <?php
    }

    /**
     * Render metabox de gestión de estado.
     */
    public function render_metabox_gestion(\WP_Post $post): void
    {
        $estado_actual = get_post_meta($post->ID, '_wa_estado', true) ?: WA_Status::default();
        $transitions   = WA_Status::transitions()[$estado_actual] ?? [];
        $log           = get_post_meta($post->ID, '_wa_log_estados', true) ?: [];
        ?>
        <p>
            <label for="wa_nuevo_estado"><strong><?php esc_html_e('Cambiar a:', 'boton-de-arrepentimiento-argentina-woosales-2'); ?></strong></label>
            <select name="wa_nuevo_estado" id="wa_nuevo_estado" style="width:100%;margin-top:4px;">
                <option value="">— <?php esc_html_e('Sin cambio', 'boton-de-arrepentimiento-argentina-woosales-2'); ?> —</option>
                <?php foreach ($transitions as $estado): ?>
                    <option value="<?php echo esc_attr($estado); ?>">
                        <?php echo esc_html(WA_Status::label($estado)); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </p>

        <p id="wa_motivo_rechazo_wrap" style="display:none;">
            <label for="wa_motivo_rechazo"><strong><?php esc_html_e('Motivo de Rechazo (obligatorio):', 'boton-de-arrepentimiento-argentina-woosales-2'); ?></strong></label>
            <textarea name="wa_motivo_rechazo" id="wa_motivo_rechazo" rows="3" style="width:100%;margin-top:4px;"></textarea>
        </p>

        <p>
            <label for="wa_nota_interna"><strong><?php esc_html_e('Nota Interna:', 'boton-de-arrepentimiento-argentina-woosales-2'); ?></strong></label>
            <textarea name="wa_nota_interna" id="wa_nota_interna" rows="2" style="width:100%;margin-top:4px;"></textarea>
        </p>

        <?php if (!empty($log)): ?>
        <hr>
        <p><strong><?php esc_html_e('Historial de cambios:', 'boton-de-arrepentimiento-argentina-woosales-2'); ?></strong></p>
        <ul style="font-size:12px;max-height:150px;overflow-y:auto;">
            <?php foreach (array_reverse($log) as $entry): ?>
                <li>
                    <?php echo esc_html($entry['fecha']); ?>:
                    <?php echo esc_html(WA_Status::label($entry['de'])); ?>
                    → <?php echo esc_html(WA_Status::label($entry['a'])); ?>
                    <?php if (!empty($entry['nota'])): ?>
                        <br><em><?php echo esc_html($entry['nota']); ?></em>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
        <?php endif; ?>

        <?php
    }

    /**
     * Guardar metaboxes.
     */
    public function save_metaboxes(int $post_id, \WP_Post $post): void
    {
        if (!isset($_POST['wa_metabox_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['wa_metabox_nonce'])), 'wa_save_metabox')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        // Cambio de estado
        if (!empty($_POST['wa_nuevo_estado'])) {
            $nuevo_estado = sanitize_text_field(wp_unslash($_POST['wa_nuevo_estado']));
            $estado_actual = get_post_meta($post_id, '_wa_estado', true) ?: WA_Status::default();

            // Si es rechazada, validar motivo
            if ($nuevo_estado === WA_Status::RECHAZADA && empty($_POST['wa_motivo_rechazo'])) {
                return; // No guardar sin motivo
            }

            if (WA_Status::can_transition($estado_actual, $nuevo_estado) || current_user_can('manage_options')) {
                update_post_meta($post_id, '_wa_estado', $nuevo_estado);

                // Guardar motivo de rechazo
                if ($nuevo_estado === WA_Status::RECHAZADA && !empty($_POST['wa_motivo_rechazo'])) {
                    update_post_meta($post_id, '_wa_motivo_rechazo', sanitize_textarea_field(wp_unslash($_POST['wa_motivo_rechazo'])));
                }

                // Log del cambio
                $log = get_post_meta($post_id, '_wa_log_estados', true) ?: [];
                $log[] = [
                    'fecha' => current_time('Y-m-d H:i:s'),
                    'de'    => $estado_actual,
                    'a'     => $nuevo_estado,
                    'nota'  => !empty($_POST['wa_nota_interna']) ? sanitize_textarea_field(wp_unslash($_POST['wa_nota_interna'])) : '',
                    'user'  => get_current_user_id(),
                ];
                update_post_meta($post_id, '_wa_log_estados', $log);

                // Si hay nota interna pero no cambio de estado, igual la guardamos
            } elseif (!empty($_POST['wa_nota_interna'])) {
                $log = get_post_meta($post_id, '_wa_log_estados', true) ?: [];
                $log[] = [
                    'fecha' => current_time('Y-m-d H:i:s'),
                    'de'    => $estado_actual,
                    'a'     => $estado_actual,
                    'nota'  => sanitize_textarea_field(wp_unslash($_POST['wa_nota_interna'])),
                    'user'  => get_current_user_id(),
                ];
                update_post_meta($post_id, '_wa_log_estados', $log);
            }
        }
    }
}
