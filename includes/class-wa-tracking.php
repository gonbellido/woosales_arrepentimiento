<?php
/**
 * Página pública de seguimiento vía shortcode [wa_seguimiento].
 *
 * @package WoosalesArrepentimiento
 */

namespace WoosalesArrepentimiento;

if (!defined('ABSPATH')) {
    exit;
}

class WA_Tracking
{
    public function __construct()
    {
        add_shortcode('wa_seguimiento', [$this, 'render_tracking']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
    }

    public function enqueue_assets(): void
    {
        // wa-form-css is registered by WA_Form_Handler; enqueue only when shortcode present
        if (is_singular() && has_shortcode(get_post()->post_content ?? '', 'wa_seguimiento')) {
            wp_enqueue_style('wa-form-css');
        }
    }

    public function render_tracking(): string
    {
        wp_enqueue_style('wa-form-css');
        $codigo = sanitize_text_field(wp_unslash($_GET['codigo'] ?? '')); // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Public tracking lookup, read-only.
        $resultado = null;
        $error = '';

        if (!empty($codigo)) {
            $resultado = $this->buscar_reclamacion($codigo);
            if (!$resultado) {
                $error = __('No se encontró ninguna reclamación con ese código de trámite.', 'boton-de-arrepentimiento-argentina-woosales-2');
            }
        }

        ob_start();
        include WOOSALES_ARG_PLUGIN_DIR . 'templates/tracking-page.php';
        return ob_get_clean();
    }

    private function buscar_reclamacion(?string $codigo): ?array
    {
        if (empty($codigo)) {
            return null;
        }

        $posts = get_posts([
            'post_type'      => WA_Post_Type::CPT_SLUG,
            'meta_key'       => '_wa_codigo_tramite',
            'meta_value'     => $codigo,
            'posts_per_page' => 1,
            'post_status'    => ['private', 'publish'],
        ]);

        if (empty($posts)) {
            return null;
        }

        $post = $posts[0];
        $estado  = get_post_meta($post->ID, '_wa_estado', true) ?: WA_Status::default();
        $pedido  = get_post_meta($post->ID, '_wa_pedido_id', true);
        $log     = get_post_meta($post->ID, '_wa_log_estados', true) ?: [];
        $motivo  = get_post_meta($post->ID, '_wa_motivo_rechazo', true);

        return [
            'codigo'          => $codigo,
            'pedido'          => $pedido,
            'estado'          => $estado,
            'estado_label'    => WA_Status::label($estado),
            'estado_color'    => WA_Status::color($estado),
            'motivo_rechazo'  => $motivo,
            'fecha_creacion'  => get_the_date('d/m/Y H:i', $post),
            'log'             => $log,
        ];
    }
}
