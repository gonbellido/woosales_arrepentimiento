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
            __('Configuración — Arrepentimiento', 'woosales-arrepentimiento'),
            __('Configuración', 'woosales-arrepentimiento'),
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

        register_setting('wa_settings_group', 'wa_boton_footer', [
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
    }

    public function render_page(): void
    {
        ?>
        <div class="wrap">
            <h1><?php esc_html_e('Configuración — Reclamaciones de Arrepentimiento', 'woosales-arrepentimiento'); ?></h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('wa_settings_group');
                do_settings_sections('wa_settings_group');
                ?>
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="wa_admin_email"><?php esc_html_e('Email del Administrador', 'woosales-arrepentimiento'); ?></label>
                        </th>
                        <td>
                            <input type="email" name="wa_admin_email" id="wa_admin_email"
                                   value="<?php echo esc_attr(get_option('wa_admin_email', get_option('admin_email'))); ?>"
                                   class="regular-text">
                            <p class="description"><?php esc_html_e('Email donde se notificarán las nuevas reclamaciones.', 'woosales-arrepentimiento'); ?></p>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            <label for="wa_pagina_formulario"><?php esc_html_e('Página del Formulario', 'woosales-arrepentimiento'); ?></label>
                        </th>
                        <td>
                            <?php
                            wp_dropdown_pages([
                                'name'              => 'wa_pagina_formulario',
                                'id'                => 'wa_pagina_formulario',
                                'selected'          => get_option('wa_pagina_formulario', ''),
                                'show_option_none'  => __('— Seleccionar —', 'woosales-arrepentimiento'),
                                'option_none_value' => '',
                            ]);
                            ?>
                            <p class="description"><?php esc_html_e('Página donde colocaste el shortcode [wa_formulario_arrepentimiento]. El botón del footer redirigirá a esta página.', 'woosales-arrepentimiento'); ?></p>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            <label for="wa_pagina_seguimiento"><?php esc_html_e('Página de Seguimiento', 'woosales-arrepentimiento'); ?></label>
                        </th>
                        <td>
                            <?php
                            wp_dropdown_pages([
                                'name'              => 'wa_pagina_seguimiento',
                                'id'                => 'wa_pagina_seguimiento',
                                'selected'          => get_option('wa_pagina_seguimiento', ''),
                                'show_option_none'  => __('— Seleccionar —', 'woosales-arrepentimiento'),
                                'option_none_value' => '',
                            ]);
                            ?>
                            <p class="description"><?php esc_html_e('Página donde colocaste el shortcode [wa_seguimiento]. Se usará en los enlaces de los emails.', 'woosales-arrepentimiento'); ?></p>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            <label for="wa_boton_footer"><?php esc_html_e('Botón en Footer', 'woosales-arrepentimiento'); ?></label>
                        </th>
                        <td>
                            <select name="wa_boton_footer" id="wa_boton_footer">
                                <option value="1" <?php selected(get_option('wa_boton_footer', '1'), '1'); ?>>
                                    <?php esc_html_e('Mostrar botón en el footer', 'woosales-arrepentimiento'); ?>
                                </option>
                                <option value="0" <?php selected(get_option('wa_boton_footer', '1'), '0'); ?>>
                                    <?php esc_html_e('No mostrar (usar solo shortcode)', 'woosales-arrepentimiento'); ?>
                                </option>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            <label for="wa_boton_texto"><?php esc_html_e('Texto del Botón', 'woosales-arrepentimiento'); ?></label>
                        </th>
                        <td>
                            <input type="text" name="wa_boton_texto" id="wa_boton_texto"
                                   value="<?php echo esc_attr(get_option('wa_boton_texto', 'Botón de Arrepentimiento')); ?>"
                                   class="regular-text">
                        </td>
                    </tr>

                    <tr>
                        <th scope="row"><?php esc_html_e('Shortcodes Disponibles', 'woosales-arrepentimiento'); ?></th>
                        <td>
                            <p><code>[wa_formulario_arrepentimiento]</code> — <?php esc_html_e('Formulario completo con texto legal', 'woosales-arrepentimiento'); ?></p>
                            <p><code>[wa_boton_arrepentimiento texto="Botón de Arrepentimiento"]</code> — <?php esc_html_e('Botón que abre el formulario en un popup', 'woosales-arrepentimiento'); ?></p>
                            <p><code>[wa_seguimiento]</code> — <?php esc_html_e('Página de seguimiento por código', 'woosales-arrepentimiento'); ?></p>
                        </td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>

            <div style="margin-top:30px;padding:20px;background:#fff;border:1px solid #ccd0d4;border-left:4px solid #0073aa;border-radius:4px;">
                <p style="margin:0;font-size:14px;">
                    <strong>🚀 <?php esc_html_e('¿Necesitás más funcionalidades para tu WooCommerce en Argentina?', 'woosales-arrepentimiento'); ?></strong>
                </p>
                <p style="margin:8px 0 0;color:#555;">
                    <?php esc_html_e('En', 'woosales-arrepentimiento'); ?>
                    <a href="https://woosales.pro?utm_source=plugin&utm_medium=settings&utm_campaign=arrepentimiento" target="_blank" rel="noopener">
                        <strong>WooSales.pro</strong></a>
                    <?php esc_html_e('desarrollamos soluciones a medida: facturación electrónica, integración con Mercado Pago, envíos, gestión de stock y mucho más.', 'woosales-arrepentimiento'); ?>
                </p>
                <p style="margin:8px 0 0;">
                    <a href="https://woosales.pro/contacto?utm_source=plugin&utm_medium=settings&utm_campaign=arrepentimiento"
                       target="_blank" rel="noopener" class="button">
                        <?php esc_html_e('Contactar a WooSales.pro', 'woosales-arrepentimiento'); ?>
                    </a>
                </p>
            </div>

            <div style="margin-top:16px;text-align:center;font-size:12px;color:#999;">
                <p>
                    <?php esc_html_e('Botón de Arrepentimiento Argentina', 'woosales-arrepentimiento'); ?>
                    v<?php echo esc_html(WA_VERSION); ?>
                    &mdash;
                    <?php esc_html_e('Desarrollado con ❤️ por', 'woosales-arrepentimiento'); ?>
                    <a href="https://woosales.pro?utm_source=plugin&utm_medium=settings&utm_campaign=arrepentimiento" target="_blank" rel="noopener">WooSales.pro</a>
                    |
                    <a href="https://wordpress.org/support/plugin/woosales_arrepentimiento/reviews/#new-post" target="_blank" rel="noopener">
                        <?php esc_html_e('Dejar una reseña ⭐', 'woosales-arrepentimiento'); ?>
                    </a>
                </p>
            </div>
        </div>
        <?php
    }
}
