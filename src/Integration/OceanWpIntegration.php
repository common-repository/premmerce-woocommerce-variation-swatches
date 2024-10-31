<?php namespace Premmerce\Attributes\Integration;

use Premmerce\SDK\V2\FileManager\FileManager;

class OceanWpIntegration
{
    /**
     * @var FileManager
     */
    private $fileManager;

    /**
     * OceanWpIntegration constructor.
     *
     * @param $frontend
     * @param $fileManager
     */
    public function __construct($frontend, $fileManager)
    {
        $this->fileManager = $fileManager;

        add_action('wp_enqueue_scripts', array($this, 'registerAssets'));
        add_filter('ocean_localize_array', array($this, 'filterThemeLocalize'));

        remove_action('woocommerce_after_shop_loop_item', array($frontend, 'registerLoopMainAttributes'), 30);
        add_action('ocean_after_archive_product_add_to_cart', array($frontend, 'registerLoopMainAttributes'), 10);
    }

    public function registerAssets()
    {
        wp_enqueue_style(
            'premmerce_attributes_ocean_wp_style',
            $this->fileManager->locateAsset('frontend/integration-css/ocean-wp.css'),
            array(),
            false
        );
    }

    public function filterThemeLocalize($localizeArray)
    {
        if (is_string($localizeArray['customSelects'])) {
            $localizeArray['customSelects'] = $localizeArray['customSelects'] . ', .pc-variations-table .variants-select select';
        }

        return $localizeArray;
    }
}
