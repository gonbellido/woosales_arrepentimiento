<?php
/**
 * Página de configuración del plugin.
 *
 * @package WoosalesArrepentimiento
 */

namespace WoosalesArrepentimiento;

if (!defined('ABSPATH')) {
    exit;
}

class WA_Settings
{
    public function __construct()
    {
        add_action('admin_menu', [$this, 'add_settings_page']);
        add_action('admin_init', [$this, 'register_settings']);
    }

    public function add_settings_page(): void
    {
        add_submenu_page(
            'edit.php?post_type=' . WA_Post_Type::CPT_SLUG,
            __('Configuración — Arrepentimiento', 'boton-de-arrepentimiento-argentina-woosales-2'),
            __('Configuración', 'boton-de-arrepentimiento-argentina-woosales-2'),
            'manage_options',
            'wa_settings',
            [$this, 'render_page']
        );
    }

    public function register_settings(): void
    {
        register_setting('wa_settings_group', 'wa_admin_email', [
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_email',
            'default'           => get_option('admin_email'),
        ]);

        register_setting('wa_settings_group', 'wa_pagina_seguimiento', [
            'type'              => 'integer',
            'sanitize_callback' => 'absint',
        ]);

        register_setting('wa_settings_group', 'woosales_arg_boton_footer', [
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => '1',
        ]);

        register_setting('wa_settings_group', 'wa_pagina_formulario', [
            'type'              => 'integer',
            'sanitize_callback' => 'absint',
        ]);

        register_setting('wa_settings_group', 'wa_boton_texto', [
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => 'Botón de Arrepentimiento',
        ]);

        register_setting('wa_settings_group', 'wa_texto_legal_tipo', [
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => 'servicios',
        ]);

        register_setting('wa_settings_group', 'wa_texto_legal_custom', [
            'type'              => 'string',
            'sanitize_callback' => 'wp_kses_post',
            'default'           => '',
        ]);
    }

    /**
     * Devuelve el HTML del aviso legal según la configuración.
     */
    public static function get_legal_text(): string
    {
        $tipo = get_option('wa_texto_legal_tipo', 'servicios');

        if ($tipo === 'custom') {
            $custom = get_option('wa_texto_legal_custom', '');
            if (!empty($custom)) {
                return wp_kses_post(wpautop($custom));
            }
        }

        if ($tipo === 'productos') {
            return self::texto_productos();
        }

        return self::texto_servicios();
    }

    private static function texto_productos(): string
    {
        return
            '<p><strong>' . esc_html__('A fin de ejercer su derecho de revocación de compra, tenga presente lo siguiente:', 'boton-de-arrepentimiento-argentina-woosales-2') . '</strong></p>' .
            '<p>' . esc_html__('El derecho de arrepentimiento (Ley 24.240) puede ejercerse dentro de los 10 días corridos desde la fecha de recepción del producto, sin necesidad de invocar causa alguna.', 'boton-de-arrepentimiento-argentina-woosales-2') . '</p>' .
            '<p>' . esc_html__('La devolución se realizará a través del mismo medio de pago utilizado para la compra. Tarjetas de Crédito y Débito: se acreditará en el resumen siguiente. QR y transferencia: hasta 45 días hábiles una vez recibidos CBU y CUIL.', 'boton-de-arrepentimiento-argentina-woosales-2') . '</p>' .
            '<p>' . esc_html__('El producto debe devolverse en el mismo estado en que fue recibido. Se reintegrará el monto total abonado.', 'boton-de-arrepentimiento-argentina-woosales-2') . '</p>' .
            '<blockquote class="wa-highlight">' . esc_html__('El derecho de arrepentimiento (Ley 24.240) podrá ejercerse dentro de los 10 días corridos desde la recepción del producto. El consumidor podrá devolver el producto sin necesidad de invocar causa alguna y solicitar la devolución íntegra del monto abonado.', 'boton-de-arrepentimiento-argentina-woosales-2') . '</blockquote>';
    }

    private static function texto_servicios(): string
    {
        return
            '<p><strong>' . esc_html__('A fin de ejercer su derecho de revocación de compra, tenga presente lo siguiente:', 'boton-de-arrepentimiento-argentina-woosales-2') . '</strong></p>' .
            '<p>' . esc_html__('El derecho de arrepentimiento (Ley 24.240) puede ejercerse dentro de los 10 días corridos desde la fecha de compra online, siempre que el servicio no haya sido prestado. Es fundamental completar la solicitud con al menos 48 horas hábiles de anticipación al inicio del servicio.', 'boton-de-arrepentimiento-argentina-woosales-2') . '</p>' .
            '<p>' . esc_html__('Si la fecha del servicio ya transcurrió, no será posible realizar el reintegro.', 'boton-de-arrepentimiento-argentina-woosales-2') . '</p>' .
            '<p>' . esc_html__('La devolución se realizará a través del mismo medio de pago utilizado para la compra. Tarjetas de Crédito y Débito: se acreditará en el resumen siguiente. QR y transferencia: hasta 45 días una vez recibidos CBU y CUIL.', 'boton-de-arrepentimiento-argentina-woosales-2') . '</p>' .
            '<p>' . esc_html__('Se reintegrará el monto total del pedido. No se realizarán reintegros parciales.', 'boton-de-arrepentimiento-argentina-woosales-2') . '</p>' .
            '<blockquote class="wa-highlight">' . esc_html__('El derecho de arrepentimiento (Ley 24.240) podrá ejercerse dentro de los 10 días corridos desde la fecha de compra, siempre que sea comunicado con una antelación mínima de 48 horas hábiles al inicio del servicio contratado. Una vez prestado el servicio, el derecho de arrepentimiento carecerá de validez.', 'boton-de-arrepentimiento-argentina-woosales-2') . '</blockquote>';
    }

    public function render_page(): void
    {
        ?>
        <div class="wrap">
            <h1><?php esc_html_e('Configuración — Reclamaciones de Arrepentimiento', 'boton-de-arrepentimiento-argentina-woosales-2'); ?></h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('wa_settings_group');
                do_settings_sections('wa_settings_group');
                ?>
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="wa_admin_email"><?php esc_html_e('Email del Administrador', 'boton-de-arrepentimiento-argentina-woosales-2'); ?></label>
                        </th>
                        <td>
                            <input type="email" name="wa_admin_email" id="wa_admin_email"
                                   value="<?php echo esc_attr(get_option('wa_admin_email', get_option('admin_email'))); ?>"
                                   class="regular-text">
                            <p class="description"><?php esc_html_e('Email donde se notificarán las nuevas reclamaciones.', 'boton-de-arrepentimiento-argentina-woosales-2'); ?></p>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            <label for="wa_pagina_formulario"><?php esc_html_e('Página del Formulario', 'boton-de-arrepentimiento-argentina-woosales-2'); ?></label>
                        </th>
                        <td>
                            <?php
                            wp_dropdown_pages([
                                'name'              => 'wa_pagina_formulario',
                                'id'                => 'wa_pagina_formulario',
                                'selected'          => absint(get_option('wa_pagina_formulario', 0)),
                                'show_option_none'  => esc_html__('— Seleccionar —', 'boton-de-arrepentimiento-argentina-woosales-2'),
                                'option_none_value' => '',
                            ]);
                            ?>
                            <p class="description"><?php esc_html_e('Página donde colocaste el shortcode [wa_formulario_arrepentimiento]. El botón del footer redirigirá a esta página.', 'boton-de-arrepentimiento-argentina-woosales-2'); ?></p>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            <label for="wa_pagina_seguimiento"><?php esc_html_e('Página de Seguimiento', 'boton-de-arrepentimiento-argentina-woosales-2'); ?></label>
                        </th>
                        <td>
                            <?php
                            wp_dropdown_pages([
                                'name'              => 'wa_pagina_seguimiento',
                                'id'                => 'wa_pagina_seguimiento',
                                'selected'          => absint(get_option('wa_pagina_seguimiento', 0)),
                                'show_option_none'  => esc_html__('— Seleccionar —', 'boton-de-arrepentimiento-argentina-woosales-2'),
                                'option_none_value' => '',
                            ]);
                            ?>
                            <p class="description"><?php esc_html_e('Página donde colocaste el shortcode [wa_seguimiento]. Se usará en los enlaces de los emails.', 'boton-de-arrepentimiento-argentina-woosales-2'); ?></p>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            <label for="woosales_arg_boton_footer"><?php esc_html_e('Botón en Footer', 'boton-de-arrepentimiento-argentina-woosales-2'); ?></label>
                        </th>
                        <td>
                            <select name="woosales_arg_boton_footer" id="woosales_arg_boton_footer">
                                <option value="1" <?php selected(get_option('woosales_arg_boton_footer', '1'), '1'); ?>>
                                    <?php esc_html_e('Mostrar botón en el footer', 'boton-de-arrepentimiento-argentina-woosales-2'); ?>
                                </option>
                                <option value="0" <?php selected(get_option('woosales_arg_boton_footer', '1'), '0'); ?>>
                                    <?php esc_html_e('No mostrar (usar solo shortcode)', 'boton-de-arrepentimiento-argentina-woosales-2'); ?>
                                </option>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            <label for="wa_boton_texto"><?php esc_html_e('Texto del Botón', 'boton-de-arrepentimiento-argentina-woosales-2'); ?></label>
                        </th>
                        <td>
                            <input type="text" name="wa_boton_texto" id="wa_boton_texto"
                                   value="<?php echo esc_attr(get_option('wa_boton_texto', 'Botón de Arrepentimiento')); ?>"
                                   class="regular-text">
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            <label for="wa_texto_legal_tipo"><?php esc_html_e('Texto Legal del Formulario', 'boton-de-arrepentimiento-argentina-woosales-2'); ?></label>
                        </th>
                        <td>
                            <?php $tipo_actual = get_option('wa_texto_legal_tipo', 'servicios'); ?>
                            <select name="wa_texto_legal_tipo" id="wa_texto_legal_tipo">
                                <option value="servicios" <?php selected($tipo_actual, 'servicios'); ?>>
                                    <?php esc_html_e('Versión Servicios (viajes, turnos, reservas)', 'boton-de-arrepentimiento-argentina-woosales-2'); ?>
                                </option>
                                <option value="productos" <?php selected($tipo_actual, 'productos'); ?>>
                                    <?php esc_html_e('Versión Productos (e-commerce, venta de artículos)', 'boton-de-arrepentimiento-argentina-woosales-2'); ?>
                                </option>
                                <option value="custom" <?php selected($tipo_actual, 'custom'); ?>>
                                    <?php esc_html_e('Personalizado (escribí tu propio texto)', 'boton-de-arrepentimiento-argentina-woosales-2'); ?>
                                </option>
                            </select>
                            <p class="description"><?php esc_html_e('Elegí el texto que mejor describe tu negocio. Aplica la Ley 24.240 según el tipo de producto o servicio.', 'boton-de-arrepentimiento-argentina-woosales-2'); ?></p>
                        </td>
                    </tr>

                    <tr id="wa_texto_custom_row" style="<?php echo $tipo_actual === 'custom' ? '' : 'display:none;'; ?>">
                        <th scope="row">
                            <label for="wa_texto_legal_custom"><?php esc_html_e('Texto Personalizado', 'boton-de-arrepentimiento-argentina-woosales-2'); ?></label>
                        </th>
                        <td>
                            <textarea name="wa_texto_legal_custom" id="wa_texto_legal_custom"
                                      rows="10" class="large-text"><?php echo esc_textarea(get_option('wa_texto_legal_custom', '')); ?></textarea>
                            <p class="description"><?php esc_html_e('Podés usar HTML básico (p, strong, em, ul, li, blockquote). Este texto reemplaza completamente el aviso legal del formulario.', 'boton-de-arrepentimiento-argentina-woosales-2'); ?></p>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row"><?php esc_html_e('Shortcodes Disponibles', 'boton-de-arrepentimiento-argentina-woosales-2'); ?></th>
                        <td>
                            <p><code>[wa_formulario_arrepentimiento]</code> — <?php esc_html_e('Formulario completo con texto legal', 'boton-de-arrepentimiento-argentina-woosales-2'); ?></p>
                            <p><code>[wa_boton_arrepentimiento texto="Botón de Arrepentimiento"]</code> — <?php esc_html_e('Botón que abre el formulario en un popup', 'boton-de-arrepentimiento-argentina-woosales-2'); ?></p>
                            <p><code>[wa_seguimiento]</code> — <?php esc_html_e('Página de seguimiento por código', 'boton-de-arrepentimiento-argentina-woosales-2'); ?></p>
                        </td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>

            <script>
            jQuery(function($){
                $('#wa_texto_legal_tipo').on('change', function(){
                    if ($(this).val() === 'custom') {
                        $('#wa_texto_custom_row').show();
                    } else {
                        $('#wa_texto_custom_row').hide();
                    }
                });
            });
            </script>

            <div style="margin-top:30px;padding:20px;background:#fff;border:1px solid #ccd0d4;border-left:4px solid #0073aa;border-radius:4px;">
                <p style="margin:0;font-size:14px;">
                    <strong>🚀 <?php esc_html_e('¿Necesitás más funcionalidades para tu WooCommerce en Argentina?', 'boton-de-arrepentimiento-argentina-woosales-2'); ?></strong>
                </p>
                <p style="margin:8px 0 0;color:#555;">
                    <?php esc_html_e('En', 'boton-de-arrepentimiento-argentina-woosales-2'); ?>
                    <a href="https://woosales.pro?utm_source=plugin&utm_medium=settings&utm_campaign=arrepentimiento" target="_blank" rel="noopener">
                        <strong>WooSales.pro</strong></a>
                    <?php esc_html_e('desarrollamos soluciones a medida: facturación electrónica, integración con Mercado Pago, envíos, gestión de stock y mucho más.', 'boton-de-arrepentimiento-argentina-woosales-2'); ?>
                </p>
                <p style="margin:8px 0 0;">
                    <a href="https://woosales.pro/contacto?utm_source=plugin&utm_medium=settings&utm_campaign=arrepentimiento"
                       target="_blank" rel="noopener" class="button">
                        <?php esc_html_e('Contactar a WooSales.pro', 'boton-de-arrepentimiento-argentina-woosales-2'); ?>
                    </a>
                </p>
            </div>

            <div style="margin-top:16px;text-align:center;font-size:12px;color:#999;">
                <p>
                    <?php esc_html_e('Botón de Arrepentimiento Argentina', 'boton-de-arrepentimiento-argentina-woosales-2'); ?>
                    v<?php echo esc_html(WOOSALES_ARG_VERSION); ?>
                    &mdash;
                    <?php esc_html_e('Desarrollado con ❤️ por', 'boton-de-arrepentimiento-argentina-woosales-2'); ?>
                    <a href="https://woosales.pro?utm_source=plugin&utm_medium=settings&utm_campaign=arrepentimiento" target="_blank" rel="noopener">WooSales.pro</a>
                    |
                    <a href="https://wordpress.org/support/plugin/woosales_arrepentimiento/reviews/#new-post" target="_blank" rel="noopener">
                        <?php esc_html_e('Dejar una reseña ⭐', 'boton-de-arrepentimiento-argentina-woosales-2'); ?>
                    </a>
                </p>
            </div>
        </div>
        <?php
    }
}
