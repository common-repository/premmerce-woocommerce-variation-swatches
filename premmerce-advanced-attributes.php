<?php

use Premmerce\Attributes\AttributesPlugin;

/**
 *
 * Plugin Name:       Premmerce Variation Swatches for WooCommerce
 * Plugin URI:        https://premmerce.com
 * Description:       This plugin flexibly extends standard features of the WooCommerce attributes and variations.
 * Version:           1.2.2
 * Author:            Premmerce
 * Author URI:        https://premmerce.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       premmerce-advanced-attributes
 * Domain Path:       /languages
 *
 * WC requires at least: 3.0.0
 * WC tested up to: 7.3.0
 *
  */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! function_exists( 'premmerce_pwvs_fs' ) ) {

    call_user_func(function () {

        require_once plugin_dir_path(__FILE__) . 'vendor/autoload.php';
        require_once plugin_dir_path(__FILE__) . 'freemius.php';

        $main = new AttributesPlugin(__FILE__);

        register_activation_hook(__FILE__, [$main, 'activate']);

        register_deactivation_hook(__FILE__, [$main, 'deactivate']);

        premmerce_pwvs_fs()->add_action('after_uninstall', [AttributesPlugin::class, 'uninstall']);

        $main->run();
    });
}
