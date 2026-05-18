<?php
/**
 * Integración con Elementor: 3 widgets (Botón, Formulario, Seguimiento).
 *
 * @package WoosalesArrepentimiento
 */

namespace WoosalesArrepentimiento;

if (!defined('ABSPATH')) {
    exit;
}

class WA_Elementor
{
    public function __construct()
    {
        add_action('elementor/widgets/register', [$this, 'register_widgets']);
        add_action('elementor/frontend/after_enqueue_styles', [$this, 'enqueue_frontend_assets']);
    }

    public function enqueue_frontend_assets(): void
    {
        wp_enqueue_style('wa-form-css', WOOSALES_ARG_PLUGIN_URL . 'assets/css/wa-form.css', [], WOOSALES_ARG_VERSION);
    }

    public function register_widgets($manager): void
    {
        if (!$manager || !method_exists($manager, 'register')) {
            return;
        }
        $manager->register(new WA_Elementor_Widget_Button());
        $manager->register(new WA_Elementor_Widget_Form());
        $manager->register(new WA_Elementor_Widget_Tracking());
    }
}

/**
 * Widget: Botón de Arrepentimiento (abre modal con formulario).
 */
class WA_Elementor_Widget_Button extends \Elementor\Widget_Base
{
    private static bool $modal_rendered = false;

    public function get_name(): string
    {
        return 'wa_boton_arrepentimiento';
    }

    public function get_title(): string
    {
        return __('Botón Arrepentimiento', 'boton-de-arrepentimiento-argentina-woosales');
    }

    public function get_icon(): string
    {
        return 'eicon-button';
    }

    public function get_categories(): array
    {
        return ['general'];
    }

    public function get_keywords(): array
    {
        return ['arrepentimiento', 'boton', 'ley 24240', 'woocommerce'];
    }

    protected function register_controls(): void
    {
        $this->start_controls_section('content_section', [
            'label' => __('Contenido', 'boton-de-arrepentimiento-argentina-woosales'),
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('button_text', [
            'label'   => __('Texto del botón', 'boton-de-arrepentimiento-argentina-woosales'),
            'type'    => \Elementor\Controls_Manager::TEXT,
            'default' => __('Botón de Arrepentimiento', 'boton-de-arrepentimiento-argentina-woosales'),
        ]);

        $this->end_controls_section();

        $this->start_controls_section('style_section', [
            'label' => __('Estilo', 'boton-de-arrepentimiento-argentina-woosales'),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('button_bg_color', [
            'label'     => __('Color de fondo', 'boton-de-arrepentimiento-argentina-woosales'),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => '#d32f2f',
            'selectors' => ['{{WRAPPER}} .wa-popup-trigger' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_control('button_text_color', [
            'label'     => __('Color de texto', 'boton-de-arrepentimiento-argentina-woosales'),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => '#ffffff',
            'selectors' => ['{{WRAPPER}} .wa-popup-trigger' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name'     => 'button_typography',
            'selector' => '{{WRAPPER}} .wa-popup-trigger',
        ]);

        $this->add_group_control(\Elementor\Group_Control_Border::get_type(), [
            'name'     => 'button_border',
            'selector' => '{{WRAPPER}} .wa-popup-trigger',
        ]);

        $this->add_control('button_border_radius', [
            'label'      => __('Radio de borde', 'boton-de-arrepentimiento-argentina-woosales'),
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors'  => ['{{WRAPPER}} .wa-popup-trigger' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('button_padding', [
            'label'      => __('Padding', 'boton-de-arrepentimiento-argentina-woosales'),
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em'],
            'selectors'  => ['{{WRAPPER}} .wa-popup-trigger' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();
    }

    protected function render(): void
    {
        $settings = $this->get_settings_for_display();
        $texto    = !empty($settings['button_text']) ? $settings['button_text'] : __('Botón de Arrepentimiento', 'boton-de-arrepentimiento-argentina-woosales');

        wp_enqueue_style('wa-form-css', WOOSALES_ARG_PLUGIN_URL . 'assets/css/wa-form.css', [], WOOSALES_ARG_VERSION);
        wp_enqueue_script('wa-form-js', WOOSALES_ARG_PLUGIN_URL . 'assets/js/wa-form.js', ['jquery'], WOOSALES_ARG_VERSION, true);
        wp_localize_script('wa-form-js', 'WA_Form', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('wa_form_nonce'),
            'text'     => [
                'success_title'   => __('¡Solicitud Enviada!', 'boton-de-arrepentimiento-argentina-woosales'),
                'error_generic'   => __('Ocurrió un error. Intentalo de nuevo.', 'boton-de-arrepentimiento-argentina-woosales'),
                'error_order'     => __('El número de pedido no es válido.', 'boton-de-arrepentimiento-argentina-woosales'),
                'error_duplicate' => __('Ya existe una reclamación activa para este pedido.', 'boton-de-arrepentimiento-argentina-woosales'),
                'sending'         => __('Enviando...', 'boton-de-arrepentimiento-argentina-woosales'),
                'submit_btn'      => __('Enviar Solicitud de Arrepentimiento', 'boton-de-arrepentimiento-argentina-woosales'),
            ],
        ]);

        printf(
            '<button type="button" class="wa-popup-trigger" onclick="WA_Modal.open()">%s</button>',
            esc_html($texto)
        );

        if (!self::$modal_rendered) {
            self::$modal_rendered = true;
            $wa_texto_legal = WA_Settings::get_legal_text();
            $wa_captcha     = WA_Form_Handler::generar_captcha();
            ?>
            <div class="wa-modal-overlay" id="wa-modal" style="display:none;">
                <div class="wa-modal-box">
                    <button type="button" class="wa-modal-close" onclick="WA_Modal.close()" aria-label="<?php esc_attr_e('Cerrar', 'boton-de-arrepentimiento-argentina-woosales'); ?>">&times;</button>
                    <div class="wa-modal-body">
                        <?php include WOOSALES_ARG_PLUGIN_DIR . 'templates/form-reclamacion.php'; ?>
                    </div>
                </div>
            </div>
            <?php
        }
    }

    protected function content_template(): void
    {
        ?>
        <button type="button" class="wa-popup-trigger">{{{ settings.button_text }}}</button>
        <?php
    }
}

/**
 * Widget: Formulario de Arrepentimiento completo.
 */
class WA_Elementor_Widget_Form extends \Elementor\Widget_Base
{
    public function get_name(): string
    {
        return 'wa_formulario_arrepentimiento';
    }

    public function get_title(): string
    {
        return __('Formulario Arrepentimiento', 'boton-de-arrepentimiento-argentina-woosales');
    }

    public function get_icon(): string
    {
        return 'eicon-form-horizontal';
    }

    public function get_categories(): array
    {
        return ['general'];
    }

    public function get_keywords(): array
    {
        return ['arrepentimiento', 'formulario', 'ley 24240', 'woocommerce'];
    }

    protected function register_controls(): void
    {
        $this->start_controls_section('content_section', [
            'label' => __('Información', 'boton-de-arrepentimiento-argentina-woosales'),
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('info_notice', [
            'type'            => \Elementor\Controls_Manager::RAW_HTML,
            'raw'             => __('<strong>Formulario de Arrepentimiento (Ley 24.240)</strong><br>Muestra el formulario completo con aviso legal. No requiere configuración adicional.', 'boton-de-arrepentimiento-argentina-woosales'),
            'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
        ]);

        $this->end_controls_section();
    }

    protected function render(): void
    {
        wp_enqueue_style('wa-form-css', WOOSALES_ARG_PLUGIN_URL . 'assets/css/wa-form.css', [], WOOSALES_ARG_VERSION);
        wp_enqueue_script('wa-form-js', WOOSALES_ARG_PLUGIN_URL . 'assets/js/wa-form.js', ['jquery'], WOOSALES_ARG_VERSION, true);
        wp_localize_script('wa-form-js', 'WA_Form', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('wa_form_nonce'),
            'text'     => [
                'success_title'   => __('¡Solicitud Enviada!', 'boton-de-arrepentimiento-argentina-woosales'),
                'error_generic'   => __('Ocurrió un error. Intentalo de nuevo.', 'boton-de-arrepentimiento-argentina-woosales'),
                'error_order'     => __('El número de pedido no es válido.', 'boton-de-arrepentimiento-argentina-woosales'),
                'error_duplicate' => __('Ya existe una reclamación activa para este pedido.', 'boton-de-arrepentimiento-argentina-woosales'),
                'sending'         => __('Enviando...', 'boton-de-arrepentimiento-argentina-woosales'),
                'submit_btn'      => __('Enviar Solicitud de Arrepentimiento', 'boton-de-arrepentimiento-argentina-woosales'),
            ],
        ]);

        $wa_texto_legal = WA_Settings::get_legal_text();
        $wa_captcha     = WA_Form_Handler::generar_captcha();
        include WOOSALES_ARG_PLUGIN_DIR . 'templates/form-reclamacion.php';
    }

    protected function content_template(): void
    {
        ?>
        <div style="background:#f0f0f0;padding:20px;text-align:center;color:#555;border:2px dashed #ccc;border-radius:6px;">
            <strong><?php esc_html_e('Formulario de Arrepentimiento', 'boton-de-arrepentimiento-argentina-woosales'); ?></strong><br>
            <small><?php esc_html_e('(se muestra en el frontend)', 'boton-de-arrepentimiento-argentina-woosales'); ?></small>
        </div>
        <?php
    }
}

/**
 * Widget: Página de seguimiento de reclamación.
 */
class WA_Elementor_Widget_Tracking extends \Elementor\Widget_Base
{
    public function get_name(): string
    {
        return 'wa_seguimiento_reclamacion';
    }

    public function get_title(): string
    {
        return __('Seguimiento Arrepentimiento', 'boton-de-arrepentimiento-argentina-woosales');
    }

    public function get_icon(): string
    {
        return 'eicon-search';
    }

    public function get_categories(): array
    {
        return ['general'];
    }

    public function get_keywords(): array
    {
        return ['arrepentimiento', 'seguimiento', 'codigo', 'tramite', 'ley 24240'];
    }

    protected function register_controls(): void
    {
        $this->start_controls_section('content_section', [
            'label' => __('Información', 'boton-de-arrepentimiento-argentina-woosales'),
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('info_notice', [
            'type'            => \Elementor\Controls_Manager::RAW_HTML,
            'raw'             => __('<strong>Seguimiento de Reclamación</strong><br>El cliente ingresa su código de trámite y consulta el estado. No requiere configuración adicional.', 'boton-de-arrepentimiento-argentina-woosales'),
            'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
        ]);

        $this->end_controls_section();
    }

    protected function render(): void
    {
        wp_enqueue_style('wa-form-css', WOOSALES_ARG_PLUGIN_URL . 'assets/css/wa-form.css', [], WOOSALES_ARG_VERSION);

        $codigo    = sanitize_text_field(wp_unslash($_GET['codigo'] ?? '')); // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Public tracking lookup, read-only.
        $resultado = null;
        $error     = '';

        if (!empty($codigo)) {
            $posts = get_posts([
                'post_type'      => WA_Post_Type::CPT_SLUG,
                'meta_key'       => '_wa_codigo_tramite',
                'meta_value'     => $codigo,
                'posts_per_page' => 1,
                'post_status'    => ['private', 'publish'],
            ]);

            if (empty($posts)) {
                $error = __('No se encontró ninguna reclamación con ese código de trámite.', 'boton-de-arrepentimiento-argentina-woosales');
            } else {
                $post   = $posts[0];
                $estado = get_post_meta($post->ID, '_wa_estado', true) ?: WA_Status::default();
                $resultado = [
                    'codigo'         => $codigo,
                    'pedido'         => get_post_meta($post->ID, '_wa_pedido_id', true),
                    'estado'         => $estado,
                    'estado_label'   => WA_Status::label($estado),
                    'estado_color'   => WA_Status::color($estado),
                    'motivo_rechazo' => get_post_meta($post->ID, '_wa_motivo_rechazo', true),
                    'fecha_creacion' => get_the_date('d/m/Y H:i', $post),
                    'log'            => get_post_meta($post->ID, '_wa_log_estados', true) ?: [],
                ];
            }
        }

        include WOOSALES_ARG_PLUGIN_DIR . 'templates/tracking-page.php';
    }

    protected function content_template(): void
    {
        ?>
        <div style="background:#f0f0f0;padding:20px;text-align:center;color:#555;border:2px dashed #ccc;border-radius:6px;">
            <strong><?php esc_html_e('Seguimiento de Reclamación', 'boton-de-arrepentimiento-argentina-woosales'); ?></strong><br>
            <small><?php esc_html_e('(el cliente ingresa su código de trámite)', 'boton-de-arrepentimiento-argentina-woosales'); ?></small>
        </div>
        <?php
    }
}
