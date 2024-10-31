<?php namespace Premmerce\Attributes;

use Premmerce\SDK\V2\FileManager\FileManager;
use Premmerce\SDK\V2\Notifications\AdminNotifier;
use Premmerce\Attributes\Admin\Admin;
use Premmerce\Attributes\Frontend\Frontend;
use Premmerce\Attributes\AttributesModel as Model;

/**
 * Class AttributesPlugin
 *
 * @package Premmerce\Attributes
 */
class AttributesPlugin
{
    const OPTION_SETTINGS = 'premmerce_advanced_attributes_settings';

    const PLAN_FREE = 'free';

    const PLAN_PREMIUM = 'premium';

    const COLOR = 'color';

    const FIRST_COLOR = 'first_color';

    const SECOND_COLOR = 'second_color';

    const THUMBNAIL_ID = 'thumbnail_id';

    /**
     * @var  FileManager
     */
    private $fileManager;

    /**
     * @var AdminNotifier $notifier
     */
    private $notifier;

    /**
     * AttributesPlugin constructor.
     *
     * @param string $mainFile
     */
    public function __construct($mainFile)
    {
        $this->fileManager = new FileManager($mainFile, 'premmerce-advanced-attributes');
        $this->notifier    = new AdminNotifier();

        add_action('plugins_loaded', array( $this, 'loadTextDomain' ));
        add_action('admin_init', array( $this, 'checkRequirePlugins' ));

        add_filter('woocommerce_locate_template', array( $this, 'addLocateWcTemplate' ), 10, 3);
        premmerce_pwvs_fs()->add_filter('freemius_pricing_js_path', array($this, 'cutomFreemiusPricingPage'));
    }

    public function cutomFreemiusPricingPage($default_pricing_js_path)
    {
        $pluginDir = $this->fileManager->getPluginDirectory();
        $pricing_js_path = $pluginDir . '/assets/admin/js/pricing-page/freemius-pricing.js';

        return $pricing_js_path;
    }

    /**
     * Add plugin path like overriding woocommerce template
     *
     * @param $template
     * @param $template_name
     * @param $template_path
     * @return string
     */
    public function addLocateWcTemplate($template, $template_name, $template_path)
    {
        global $woocommerce;

        $_template = $template;

        if (! $template_path) {
            $template_path = $woocommerce->template_url;
        }

        $plugin_path  = $this->fileManager->getPluginDirectory() . 'views/woocommerce' . DIRECTORY_SEPARATOR;

        $template = locate_template(
            array(
                $template_path . $template_name,
                $template_name
            )
        );

        if (! $template && file_exists($plugin_path . $template_name)) {
            $template = $plugin_path . $template_name;
        }

        if (! $template) {
            $template = $_template;
        }

        return $template;
    }

    /**
     * Run plugin part
     */
    public function run()
    {
        $valid = count($this->validateRequiredPlugins()) === 0;

        ( new Updater() )->update();

        if ($valid) {
            if (is_admin()) {
                new Admin($this->fileManager);
            }

            if (!is_admin() || wp_doing_ajax()) {
                $frontend = Frontend::getInstance();

                $GLOBALS['premmerce_advanced_attributes_frontend'] = $frontend;

                $frontend->run($this->fileManager);
            }
        }
    }

    /**
     * Load plugin translations
     */
    public function loadTextDomain()
    {
        $name = $this->fileManager->getPluginName();

        load_plugin_textdomain('premmerce-advanced-attributes', false, $name . '/languages/');
    }

    /**
     * Fired when the plugin is activated
     */
    public function activate()
    {
        Model::createTable();

        add_option('premmerce_main_attributes', array(), null, true);
    }

    /**
     * Fired when the plugin is deactivated
     */
    public function deactivate()
    {
    }

    /**
     * Validate required plugins
     *
     * @return array
     */
    private function validateRequiredPlugins()
    {
        $plugins = array();

        if (!function_exists('is_plugin_active')) {
            include_once(ABSPATH . 'wp-admin/includes/plugin.php');
        }

        /**
         * Check if WooCommerce is active
         **/
        if (!(is_plugin_active('woocommerce/woocommerce.php') || is_plugin_active_for_network('woocommerce/woocommerce.php'))) {
            $plugins[] = '<a target="_blank" href="https://wordpress.org/plugins/woocommerce/">WooCommerce</a>';
        }

        return $plugins;
    }

    /**
     * Check required plugins and push notifications
     */
    public function checkRequirePlugins()
    {
        $message = __('The %s plugin requires %s plugin to be active!', 'premmerce-advanced-attributes');

        $plugins = $this->validateRequiredPlugins();

        if (count($plugins)) {
            foreach ($plugins as $plugin) {
                $error = sprintf($message, 'Premmerce Variation Swatches for WooCommerce', $plugin);
                $this->notifier->push($error, AdminNotifier::ERROR, false);
            }
        }
    }

    /**
     * Fired during plugin uninstall
     */
    public static function uninstall()
    {
        Model::dropTable();

        delete_option('premmerce_main_taxonomy');
    }
}
