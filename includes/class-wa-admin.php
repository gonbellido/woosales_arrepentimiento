<?php
/**
 * Backoffice: columnas personalizadas, filtros, estilos admin.
 *
 * @package WoosalesArrepentimiento
 */

namespace WoosalesArrepentimiento;

if (!defined('ABSPATH')) {
    exit;
}

class WA_Admin
{
    public function __construct()
    {
        // Columnas del listado
        add_filter('manage_' . WA_Post_Type::CPT_SLUG . '_posts_columns', [$this, 'columns']);
        add_action('manage_' . WA_Post_Type::CPT_SLUG . '_posts_custom_column', [$this, 'render_column'], 10, 2);

        // Filtro por estado
        add_action('restrict_manage_posts', [$this, 'filter_dropdown']);
        add_filter('parse_query', [$this, 'filter_query']);

        // Estilos admin
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_assets']);

        // Cambio rápido de estado desde listado
        add_action('admin_post_wa_quick_status', [$this, 'handle_quick_status']);
        add_filter('post_row_actions', [$this, 'add_quick_actions'], 10, 2);
    }

    public function columns(array $columns): array
    {
        $nuevas = [];
        foreach ($columns as $key => $value) {
            $nuevas[$key] = $value;
            if ($key === 'title') {
                $nuevas['wa_codigo'] = __('Código', 'boton-de-arrepentimiento-argentina-woosales-2');
            }
        }
        $nuevas['wa_pedido']  = __('Pedido', 'boton-de-arrepentimiento-argentina-woosales-2');
        $nuevas['wa_cliente'] = __('Cliente', 'boton-de-arrepentimiento-argentina-woosales-2');
        $nuevas['wa_email']   = __('Email', 'boton-de-arrepentimiento-argentina-woosales-2');
        $nuevas['wa_estado']  = __('Estado', 'boton-de-arrepentimiento-argentina-woosales-2');
        $nuevas['wa_fecha']   = __('Fecha Pedido', 'boton-de-arrepentimiento-argentina-woosales-2');

        // Quitar columnas no necesarias
        unset($nuevas['date']);
        unset($nuevas['author']);

        return $nuevas;
    }

    public function render_column(string $column, int $post_id): void
    {
        switch ($column) {
            case 'wa_codigo':
                $codigo = get_post_meta($post_id, '_wa_codigo_tramite', true);
                echo '<code style="font-size:13px;">' . esc_html($codigo) . '</code>';
                break;

            case 'wa_pedido':
                $pedido_id = get_post_meta($post_id, '_wa_pedido_id', true);
                if ($pedido_id && ($order = wc_get_order($pedido_id))) {
                    printf(
                        '<a href="%s">#%s</a> — %s',
                        esc_url($order->get_edit_order_url()),
                        esc_html($pedido_id),
                        wp_kses_post($order->get_formatted_order_total())
                    );
                } else {
                    echo '#' . esc_html($pedido_id);
                }
                break;

            case 'wa_cliente':
                echo esc_html(get_post_meta($post_id, '_wa_nombre_cliente', true));
                break;

            case 'wa_email':
                $email = get_post_meta($post_id, '_wa_email_cliente', true);
                printf('<a href="mailto:%s">%s</a>', esc_attr($email), esc_html($email));
                break;

            case 'wa_estado':
                $estado = get_post_meta($post_id, '_wa_estado', true) ?: WA_Status::default();
                $color  = WA_Status::color($estado);
                $label  = WA_Status::label($estado);
                printf(
                    '<span class="wa-badge" style="background:%s;color:#fff;padding:4px 10px;border-radius:3px;font-size:12px;white-space:nowrap;">%s</span>',
                    esc_attr($color),
                    esc_html($label)
                );
                break;

            case 'wa_fecha':
                echo esc_html(get_post_meta($post_id, '_wa_fecha_pedido', true));
                break;
        }
    }

    /**
     * Dropdown para filtrar por estado.
     */
    public function filter_dropdown(string $post_type): void
    {
        if ($post_type !== WA_Post_Type::CPT_SLUG) {
            return;
        }

        $current = sanitize_text_field(wp_unslash($_GET['wa_estado'] ?? '')); // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only admin list filter, no state change.
        ?>
        <select name="wa_estado">
            <option value=""><?php esc_html_e('Todos los estados', 'boton-de-arrepentimiento-argentina-woosales-2'); ?></option>
            <?php foreach (WA_Status::labels() as $key => $label): ?>
                <option value="<?php echo esc_attr($key); ?>" <?php selected($current, $key); ?>>
                    <?php echo esc_html($label); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <?php
    }

    /**
     * Aplicar filtro por estado en la query.
     */
    public function filter_query(\WP_Query $query): void
    {
        if (!is_admin() || !$query->is_main_query()) {
            return;
        }

        if ($query->get('post_type') !== WA_Post_Type::CPT_SLUG) {
            return;
        }

        $estado = sanitize_text_field(wp_unslash($_GET['wa_estado'] ?? '')); // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only admin list filter, no state change.
        if (!empty($estado) && in_array($estado, WA_Status::estados(), true)) {
            $query->set('meta_key', '_wa_estado');
            $query->set('meta_value', $estado);
        }

        // Ordenar por fecha de creación descendente por defecto
        if (!$query->get('orderby')) {
            $query->set('orderby', 'date');
            $query->set('order', 'DESC');
        }
    }

    /**
     * Agregar acciones rápidas en cada fila del listado.
     */
    public function add_quick_actions(array $actions, \WP_Post $post): array
    {
        if ($post->post_type !== WA_Post_Type::CPT_SLUG) {
            return $actions;
        }

        $estado_actual = get_post_meta($post->ID, '_wa_estado', true) ?: WA_Status::default();
        $transitions   = WA_Status::transitions()[$estado_actual] ?? [];

        foreach ($transitions as $nuevo_estado) {
            $url = wp_nonce_url(
                add_query_arg([
                    'action'   => 'wa_quick_status',
                    'post_id'  => $post->ID,
                    'estado'   => $nuevo_estado,
                ], admin_url('admin-post.php')),
                'wa_quick_status_' . $post->ID
            );
            $actions['wa_' . $nuevo_estado] = sprintf(
                '<a href="%s" style="color:%s;">%s → %s</a>',
                esc_url($url),
                esc_attr(WA_Status::color($nuevo_estado)),
                esc_html__('Marcar', 'boton-de-arrepentimiento-argentina-woosales-2'),
                esc_html(WA_Status::label($nuevo_estado))
            );
        }

        return $actions;
    }

    /**
     * Procesar cambio rápido de estado desde el listado.
     */
    public function handle_quick_status(): void
    {
        $post_id = absint(wp_unslash($_GET['post_id'] ?? 0));
        $estado  = sanitize_text_field(wp_unslash($_GET['estado'] ?? ''));
        $nonce   = sanitize_text_field(wp_unslash($_GET['_wpnonce'] ?? ''));

        if (!$post_id || !wp_verify_nonce($nonce, 'wa_quick_status_' . $post_id)) {
            wp_die(esc_html__('Acción no autorizada.', 'boton-de-arrepentimiento-argentina-woosales-2'));
        }

        if (!current_user_can('edit_post', $post_id)) {
            wp_die(esc_html__('No tenés permisos.', 'boton-de-arrepentimiento-argentina-woosales-2'));
        }

        $estado_actual = get_post_meta($post_id, '_wa_estado', true) ?: WA_Status::default();

        if (!WA_Status::can_transition($estado_actual, $estado) && !current_user_can('manage_options')) {
            wp_die(esc_html__('Transición de estado no permitida.', 'boton-de-arrepentimiento-argentina-woosales-2'));
        }

        update_post_meta($post_id, '_wa_estado', $estado);

        // Log
        $log = get_post_meta($post_id, '_wa_log_estados', true) ?: [];
        $log[] = [
            'fecha' => current_time('Y-m-d H:i:s'),
            'de'    => $estado_actual,
            'a'     => $estado,
            // translators: %s is the user's display name.
            'nota'  => sprintf(__('Cambio rápido desde listado por %s', 'boton-de-arrepentimiento-argentina-woosales-2'), wp_get_current_user()->display_name),
            'user'  => get_current_user_id(),
        ];
        update_post_meta($post_id, '_wa_log_estados', $log);

        wp_safe_redirect(wp_get_referer() ?: admin_url('edit.php?post_type=' . WA_Post_Type::CPT_SLUG));
        exit;
    }

    /**
     * Estilos del admin.
     */
    public function enqueue_admin_assets(string $hook): void
    {
        $screen = get_current_screen();

        if (!$screen || $screen->post_type !== WA_Post_Type::CPT_SLUG) {
            return;
        }

        wp_enqueue_style(
            'wa-admin-css',
            WOOSALES_ARG_PLUGIN_URL . 'assets/css/wa-admin.css',
            [],
            WOOSALES_ARG_VERSION
        );

        wp_enqueue_script(
            'wa-admin-js',
            WOOSALES_ARG_PLUGIN_URL . 'assets/js/wa-admin.js',
            ['jquery'],
            WOOSALES_ARG_VERSION,
            true
        );
        wp_localize_script('wa-admin-js', 'WA_Admin', [
            'confirm_status' => __('¿Confirmás cambiar el estado?', 'boton-de-arrepentimiento-argentina-woosales-2'),
        ]);
    }
}
