<?php
/**
 * Plugin Name:     Botón de Arrepentimiento Argentina — WooSales
 * Plugin URI:      https://woosales.pro/plugin_arrepentimiento_argentina
 * Description:     Botón de Arrepentimiento Argentina para WooCommerce. Cumplí con la Ley 24.240. Usá [wa_formulario_arrepentimiento] para mostrar el formulario y [wa_seguimiento] para el tracking. Código de trámite inmediato y gestión simple.
 * Version:         1.0.0
 * Author:          WooSales.pro
 * Author URI:      https://woosales.pro
 * License:         GPL-2.0+
 * License URI:     http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:     woosales-arrepentimiento
 * Domain Path:     /languages
 * Requires at least: 5.8
 * Requires PHP:      7.4
 * WC requires at least: 5.0
 * WC tested up to:   9.4
 * Requires Plugins:  woocommerce
 */

if (!defined('ABSPATH')) {
    exit;
}

define('WA_VERSION', '1.0.0');
define('WA_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('WA_PLUGIN_URL', plugin_dir_url(__FILE__));
define('WA_PLUGIN_BASENAME', plugin_basename(__FILE__));

/**
 * Declarar compatibilidad con HPOS (High-Performance Order Storage)
 * y otras features modernas de WooCommerce.
 */
add_action('before_woocommerce_init', function () {
    if (class_exists(\Automattic\WooCommerce\Utilities\FeaturesUtil::class)) {
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('custom_order_tables', __FILE__, true);
    }
});

/**
 * Verifica que WooCommerce esté activo.
 */
function wa_check_woocommerce(): void
{
    if (!class_exists('WooCommerce')) {
        add_action('admin_notices', function () {
            printf(
                '<div class="notice notice-error"><p>%s</p></div>',
                esc_html__('WooSales Arrepentimiento requiere WooCommerce activo.', 'woosales-arrepentimiento')
            );
        });
        return;
    }

    require_once WA_PLUGIN_DIR . 'includes/class-wa-loader.php';
    \WoosalesArrepentimiento\WA_Loader::init();
}

add_action('plugins_loaded', 'wa_check_woocommerce', 20);

/**
 * Botón de Arrepentimiento en el footer.
 */
function wa_boton_footer(): void
{
    $activado = get_option('wa_boton_footer', '1');
    if ($activado !== '1') {
        return;
    }

    $pagina_id = get_option('wa_pagina_formulario', '');
    $url = $pagina_id ? get_permalink((int) $pagina_id) : '#';
    $texto = get_option('wa_boton_texto', 'Botón de Arrepentimiento');

    if (!$pagina_id) {
        return; // Sin página configurada, no mostrar
    }

    ?>
    <div class="wa-footer-btn-wrap">
        <a href="<?php echo esc_url($url); ?>" class="wa-footer-btn" title="<?php esc_attr_e('Ejercer derecho de arrepentimiento — Ley 24.240', 'woosales-arrepentimiento'); ?>">
            ↺ <?php echo esc_html($texto); ?>
        </a>
    </div>
    <?php
}
add_action('wp_footer', 'wa_boton_footer', 100);

// Encolar CSS del botón footer globalmente
function wa_enqueue_footer_css(): void
{
    $activado = get_option('wa_boton_footer', '1');
    if ($activado === '1') {
        wp_enqueue_style('wa-form-css', WA_PLUGIN_URL . 'assets/css/wa-form.css', [], WA_VERSION);
    }
}
add_action('wp_enqueue_scripts', 'wa_enqueue_footer_css');

/**
 * Agregar enlaces "Documentación" y "FAQ" en el listado de plugins.
 */
function wa_plugin_action_links(array $links): array
{
    $landing = 'https://woosales.pro/plugin_arrepentimiento_argentina';

    $custom = [
        'docs' => sprintf(
            '<a href="%s" target="_blank" rel="noopener" style="font-weight:600;">%s</a>',
            esc_url($landing),
            esc_html__('📖 Documentación', 'woosales-arrepentimiento')
        ),
        'faq' => sprintf(
            '<a href="%s#faq" target="_blank" rel="noopener">%s</a>',
            esc_url($landing),
            esc_html__('❓ FAQ', 'woosales-arrepentimiento')
        ),
    ];

    return array_merge($custom, $links);
}
add_filter('plugin_action_links_' . WA_PLUGIN_BASENAME, 'wa_plugin_action_links');

/**
 * Agregar fila de metadatos debajo del plugin en el listado.
 */
function wa_plugin_row_meta(array $links, string $file): array
{
    if ($file !== WA_PLUGIN_BASENAME) {
        return $links;
    }

    $landing = 'https://woosales.pro/plugin_arrepentimiento_argentina';

    $links[] = sprintf(
        '<a href="%s" target="_blank" rel="noopener">%s</a>',
        esc_url($landing),
        esc_html__('Ver detalles y documentación', 'woosales-arrepentimiento')
    );

    return $links;
}
add_filter('plugin_row_meta', 'wa_plugin_row_meta', 10, 2);
