<?php
/**
 * Loader: autoload simple y registro central de hooks.
 *
 * @package WoosalesArrepentimiento
 */

namespace WoosalesArrepentimiento;

if (!defined('ABSPATH')) {
    exit;
}

class WA_Loader
{
    private static array $instances = [];

    public static function init(): void
    {
        // Orden de carga importa: Status y PostType antes que los demás.
        $classes = [
            'class-wa-status.php'      => WA_Status::class,
            'class-wa-post-type.php'   => WA_Post_Type::class,
            'class-wa-form-handler.php' => WA_Form_Handler::class,
            'class-wa-email.php'       => WA_Email::class,
            'class-wa-tracking.php'    => WA_Tracking::class,
            'class-wa-admin.php'       => WA_Admin::class,
            'class-wa-settings.php'    => WA_Settings::class,
            'class-wa-review.php'      => WA_Review::class,
        ];

        foreach ($classes as $file => $class) {
            require_once WA_PLUGIN_DIR . 'includes/' . $file;
        }

        // Construir cada clase
        self::$instances['status']       = new WA_Status();
        self::$instances['post_type']    = new WA_Post_Type();
        self::$instances['form_handler'] = new WA_Form_Handler();
        self::$instances['email']        = new WA_Email();
        self::$instances['tracking']     = new WA_Tracking();
        self::$instances['admin']        = new WA_Admin();
        self::$instances['settings']     = new WA_Settings();
        self::$instances['review']       = new WA_Review();
    }

    public static function get(string $key): ?object
    {
        return self::$instances[$key] ?? null;
    }
}
