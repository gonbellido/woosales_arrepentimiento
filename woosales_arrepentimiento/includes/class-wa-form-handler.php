<?php
/**
 * Maneja el shortcode del formulario público y la creación de reclamaciones vía AJAX.
 *
 * @package WoosalesArrepentimiento
 */

namespace WoosalesArrepentimiento;

if (!defined('ABSPATH')) {
    exit;
}

class WA_Form_Handler
{
    public function __construct()
    {
        add_shortcode('wa_formulario_arrepentimiento', [$this, 'render_form']);
        add_action('wp_ajax_wa_enviar_reclamacion', [$this, 'handle_submission']);
        add_action('wp_ajax_nopriv_wa_enviar_reclamacion', [$this, 'handle_submission']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
    }

    /**
     * Encolar assets del formulario.
     */
    public function enqueue_assets(): void
    {
        if (is_singular() && has_shortcode(get_post()->post_content ?? '', 'wa_formulario_arrepentimiento')) {
            wp_enqueue_style('wa-form-css', WA_PLUGIN_URL . 'assets/css/wa-form.css', [], WA_VERSION);
            wp_enqueue_script('wa-form-js', WA_PLUGIN_URL . 'assets/js/wa-form.js', ['jquery'], WA_VERSION, true);
            wp_localize_script('wa-form-js', 'WA_Form', [
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce'    => wp_create_nonce('wa_form_nonce'),
                'text'     => [
                    'success_title'   => __('¡Solicitud Enviada!', 'woosales-arrepentimiento'),
                    'error_generic'   => __('Ocurrió un error. Intentalo de nuevo.', 'woosales-arrepentimiento'),
                    'error_order'     => __('El número de pedido no es válido.', 'woosales-arrepentimiento'),
                    'error_duplicate' => __('Ya existe una reclamación activa para este pedido.', 'woosales-arrepentimiento'),
                    'sending'         => __('Enviando...', 'woosales-arrepentimiento'),
                ],
            ]);
        }
    }

    /**
     * Renderizar formulario vía shortcode.
     */
    public function render_form(): string
    {
        ob_start();
        include WA_PLUGIN_DIR . 'templates/form-reclamacion.php';
        return ob_get_clean();
    }

    /**
     * Generar código de trámite único.
     * Formato: {3digitos}-{nro_pedido}-{fechaYmd}
     */
    private function generar_codigo(string $pedido_id): string
    {
        $fecha = current_time('Ymd');

        // Intentar hasta 5 veces para evitar colisiones
        for ($i = 0; $i < 5; $i++) {
            $rand    = str_pad((string) wp_rand(0, 999), 3, '0', STR_PAD_LEFT);
            $codigo  = $rand . '-' . $pedido_id . '-' . $fecha;

            // Verificar unicidad
            $existente = get_posts([
                'post_type'      => WA_Post_Type::CPT_SLUG,
                'meta_key'       => '_wa_codigo_tramite',
                'meta_value'     => $codigo,
                'posts_per_page' => 1,
                'post_status'    => ['private', 'publish'],
                'fields'         => 'ids',
            ]);

            if (empty($existente)) {
                return $codigo;
            }
        }

        // Si después de 5 intentos colisiona (extremadamente improbable), usar microtime
        $rand   = str_pad((string) wp_rand(0, 999), 3, '0', STR_PAD_LEFT);
        return $rand . '-' . $pedido_id . '-' . $fecha . '-' . substr((string) microtime(true), -4);
    }

    /**
     * Validar fechas contra la Ley 24.240.
     * Retorna array con advertencias, o vacío si está ok.
     */
    private function validar_fechas_legales(string $pedido_id, string $fecha_reserva = ''): array
    {
        $advertencias = [];
        $order = wc_get_order($pedido_id);

        if (!$order) {
            return $advertencias; // Sin pedido no podemos validar
        }

        $fecha_compra = $order->get_date_created();
        if (!$fecha_compra) {
            return $advertencias;
        }

        $fecha_compra_obj = $fecha_compra->date('Y-m-d');
        $hoy = current_time('Y-m-d');
        $dias_transcurridos = (int) (strtotime($hoy) - strtotime($fecha_compra_obj)) / DAY_IN_SECONDS;

        // Regla de 10 días corridos desde compra
        if ($dias_transcurridos > 10) {
            $advertencias[] = sprintf(
                __('Han transcurrido %d días desde la compra. El plazo legal de 10 días corridos (Ley 24.240) podría haber vencido.', 'woosales-arrepentimiento'),
                $dias_transcurridos
            );
        }

        // Validación extra si hay fecha de reserva
        if (!empty($fecha_reserva)) {
            $fecha_reserva_obj = date('Y-m-d', strtotime($fecha_reserva));
            $dias_para_reserva = (int) (strtotime($fecha_reserva_obj) - strtotime($hoy)) / DAY_IN_SECONDS;

            if ($dias_para_reserva < 0) {
                $advertencias[] = __('La fecha de la reserva ya transcurrió. El derecho de arrepentimiento podría no ser aplicable.', 'woosales-arrepentimiento');
            } elseif ($dias_para_reserva < 2) {
                $advertencias[] = __('Faltan menos de 48 horas hábiles para el inicio del servicio. El derecho de arrepentimiento podría no ser aplicable.', 'woosales-arrepentimiento');
            }
        }

        return $advertencias;
    }

    /**
     * Procesar envío AJAX del formulario.
     */
    public function handle_submission(): void
    {
        check_ajax_referer('wa_form_nonce', 'nonce');

        $pedido_id      = sanitize_text_field(wp_unslash($_POST['pedido_id'] ?? ''));
        $email          = sanitize_email(wp_unslash($_POST['email'] ?? ''));
        $nombre         = sanitize_text_field(wp_unslash($_POST['nombre'] ?? ''));
        $fecha_reserva  = sanitize_text_field(wp_unslash($_POST['fecha_reserva'] ?? ''));
        $acepta_terminos = !empty($_POST['acepta_terminos']);

        // Validaciones básicas
        $errores = [];

        if (empty($pedido_id) || !is_numeric($pedido_id)) {
            $errores[] = __('Ingresá un número de pedido válido.', 'woosales-arrepentimiento');
        }

        if (empty($email) || !is_email($email)) {
            $errores[] = __('Ingresá un email válido.', 'woosales-arrepentimiento');
        }

        if (empty($nombre)) {
            $errores[] = __('Ingresá tu nombre.', 'woosales-arrepentimiento');
        }

        if (!$acepta_terminos) {
            $errores[] = __('Debés aceptar los términos y condiciones.', 'woosales-arrepentimiento');
        }

        if (!empty($errores)) {
            wp_send_json_error(['errores' => $errores]);
        }

        // Verificar duplicado activo para este pedido
        $existente = get_posts([
            'post_type'      => WA_Post_Type::CPT_SLUG,
            'meta_key'       => '_wa_pedido_id',
            'meta_value'     => $pedido_id,
            'posts_per_page' => 1,
            'post_status'    => 'private',
            'fields'         => 'ids',
        ]);

        if (!empty($existente)) {
            $codigo_existente = get_post_meta($existente[0], '_wa_codigo_tramite', true);
            wp_send_json_error([
                'errores' => [__('Ya existe una reclamación activa para este pedido.', 'woosales-arrepentimiento')],
                'codigo_existente' => $codigo_existente,
            ]);
        }

        // Validar fechas legales
        $advertencias_fecha = $this->validar_fechas_legales($pedido_id, $fecha_reserva);

        // Precargar datos del pedido si existe
        $order = wc_get_order($pedido_id);
        $fecha_pedido = $order ? $order->get_date_created()?->date('d/m/Y') : '—';
        $nombre_pedido = $order ? ($order->get_billing_first_name() . ' ' . $order->get_billing_last_name()) : '';

        // Si el nombre no coincide con el del pedido, usar el ingresado
        if (empty($nombre) && !empty($nombre_pedido)) {
            $nombre = $nombre_pedido;
        }

        // Generar código de trámite
        $codigo = $this->generar_codigo($pedido_id);

        // Crear CPT
        $post_id = wp_insert_post([
            'post_type'    => WA_Post_Type::CPT_SLUG,
            'post_title'   => sprintf(
                'Reclamación #%s — %s',
                $pedido_id,
                $nombre
            ),
            'post_status'  => 'private',
            'post_content' => '',
            'meta_input'   => [
                '_wa_pedido_id'      => $pedido_id,
                '_wa_codigo_tramite' => $codigo,
                '_wa_email_cliente'  => $email,
                '_wa_nombre_cliente' => $nombre,
                '_wa_estado'         => WA_Status::default(),
                '_wa_fecha_reserva'  => $fecha_reserva,
                '_wa_fecha_pedido'   => $fecha_pedido,
                '_wa_advertencias'   => $advertencias_fecha,
            ],
        ]);

        if (is_wp_error($post_id)) {
            wp_send_json_error(['errores' => [__('Error al crear la reclamación.', 'woosales-arrepentimiento')]]);
        }

        // Enviar emails
        $email_handler = WA_Loader::get('email');
        if ($email_handler) {
            $email_handler->enviar_cliente($post_id, $codigo, $email, $nombre);
            $email_handler->enviar_admin($post_id, $codigo, $pedido_id, $nombre, $email);
        }

        // Construir enlace de seguimiento
        $pagina_seguimiento = get_option('wa_pagina_seguimiento', '');
        $enlace_seguimiento = $pagina_seguimiento
            ? add_query_arg('codigo', $codigo, get_permalink((int) $pagina_seguimiento))
            : '';

        wp_send_json_success([
            'codigo'             => $codigo,
            'enlace_seguimiento' => $enlace_seguimiento,
            'advertencias'       => $advertencias_fecha,
            'estado'             => WA_Status::label(WA_Status::default()),
        ]);
    }
}
