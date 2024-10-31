<?php

namespace Premmerce\Attributes\Frontend;

use  Premmerce\Attributes\Admin\Admin ;
use  Premmerce\Attributes\AttributesModel ;
use  Premmerce\Attributes\AttributesPlugin ;
use  Premmerce\Attributes\Admin\Tabs\Settings ;
use  Premmerce\SDK\V2\FileManager\FileManager ;
use  Premmerce\Attributes\AttributesModel as Model ;
use  Premmerce\Attributes\Integration\OceanWpIntegration ;
/**
 * Class Frontend
 *
 * @package Premmerce\Attributes\Frontend
 */
class Frontend
{
    /**
     * @var FileManager $fileManager
     */
    private  $fileManager ;
    /**
     * @var Frontend Instance
     */
    protected static  $instance ;
    /**
     * @var array
     */
    protected  $archives ;
    private function __construct()
    {
        $this->archives = AttributesModel::getArchive();
    }
    
    /**
     * @return Frontend self::$instance
     */
    public static function getInstance()
    {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Main method
     * @param FileManager $fileManager
     */
    public function run( FileManager $fileManager )
    {
        $this->fileManager = $fileManager;
        $this->hooks();
    }
    
    /**
     * Register all hooks
     */
    public function hooks()
    {
        // add types and terms
        add_filter(
            'wc_product_variable_attributes',
            array( $this, 'addTypesToAttributes' ),
            10,
            2
        );
        // main loop main attributes
        add_action( 'woocommerce_after_shop_loop_item', array( $this, 'registerLoopMainAttributes' ), 30 );
        add_action( 'premmerce_render_main_loop_attributes', array( $this, 'renderLoopMainAttributes' ) );
        //single product main attributes
        add_action( 'woocommerce_product_meta_start', array( $this, 'registerSingleMainAttributes' ) );
        add_action(
            'premmerce_render_main_single_attributes',
            array( $this, 'renderSingleMainAttributes' ),
            1,
            1
        );
        //register assets
        add_action( 'wp_enqueue_scripts', array( $this, 'registerAssets' ) );
        add_filter(
            'woocommerce_attribute_label',
            array( $this, 'renderAttributeDescription' ),
            10,
            2
        );
        //Single product variations
        add_action(
            'premmerce_render_advanced_variation',
            array( $this, 'renderAdvancedVariationTemplate' ),
            10,
            5
        );
        add_filter( 'woocommerce_get_script_data', array( $this, 'filterWooGetScriptData' ) );
        add_filter( 'premmerce_buy_now_catalog_show_button', '__return_true' );
        //Theme integration
        add_action( 'init', array( $this, 'checkIntegration' ) );
    }
    
    /**
     * Render custom variation wrapper
     * @param $attributes
     * @param $default_attributes
     * @param $product
     * @param $attribute_keys
     * @param $loop_product
     */
    public function renderAdvancedVariationTemplate(
        $attributes,
        $default_attributes,
        $product,
        $attribute_keys,
        $loop_product
    )
    {
        $this->fileManager->includeTemplate( 'frontend/variation-wrapper.php', array(
            'attributes'         => $attributes,
            'default_attributes' => $default_attributes,
            'product'            => $product,
            'attribute_keys'     => $attribute_keys,
            'loop_product'       => $loop_product,
        ) );
    }
    
    /**
     * Render description for attribute
     * @param string $html
     * @param string $label
     * @return string
     */
    public function renderAttributeDescription( $html, $label )
    {
        ob_start();
        $description = Admin::getAttributeDescription( 'pa_' . $label );
        if ( strip_tags( $description ) != '' ) {
            $this->fileManager->includeTemplate( 'frontend/attribute-description.php', array(
                'description' => $description,
            ) );
        }
        return $html . ob_get_clean();
    }
    
    /**
     * Register assets
     */
    public function registerAssets()
    {
        wp_enqueue_script( 'jquery' );
        wp_enqueue_style( 'premmerce-attributes-front-style', $this->fileManager->locateAsset( 'frontend/css/main.css' ) );
        wp_enqueue_script( 'premmerce-attributes-front-script', $this->fileManager->locateAsset( 'frontend/js/main.js' ) );
    }
    
    /**
     * Register <premmerce_render_main_loop_attributes> if main attributes exist at loop products page
     */
    public function registerLoopMainAttributes()
    {
        global  $product ;
        $attributes = Admin::getMainAttributes( $product->get_id() );
        $attributes = apply_filters( 'main_attributes_loop_filter', $attributes );
        if ( $attributes ) {
            do_action( 'premmerce_render_main_loop_attributes', $attributes );
        }
    }
    
    /**
     * Register <premmerce_render_main_single_attributes> if main attributes exist at single product page
     */
    public function registerSingleMainAttributes()
    {
        global  $product ;
        $attributes = Admin::getMainAttributes( $product->get_id() );
        $attributes = apply_filters( 'main_attributes_single_filter', $attributes );
        if ( $attributes ) {
            do_action( 'premmerce_render_main_single_attributes', $attributes );
        }
    }
    
    /**
     * Render attributes on loop products
     * @param array $attributes
     */
    public function renderLoopMainAttributes( $attributes )
    {
        $data = $this->attributeProcess( $attributes );
        $this->fileManager->includeTemplate( 'frontend/main-attributes-loop.php', array(
            'data' => $data,
        ) );
    }
    
    /**
     * Render attributes on single product page
     * @param array $attributes
     */
    public function renderSingleMainAttributes( $attributes )
    {
        $data = $this->attributeProcess( $attributes );
        $this->fileManager->includeTemplate( 'frontend/main-attributes-single.php', array(
            'data' => $data,
        ) );
    }
    
    /**
     * Structuring attributes
     *
     * @param array attr
     * @return array $data
     */
    public function attributeProcess( $attr )
    {
        
        if ( !is_array( $attr ) ) {
            $attributes[] = $attr;
        } else {
            $attributes = $attr;
        }
        
        $data = array();
        foreach ( $attributes as $attribute ) {
            
            if ( in_array( $attribute->taxonomy, $this->archives ) ) {
                $archive_link = get_term_link( $attribute );
                $full_line = '<a href="' . $archive_link . '">' . $attribute->name . '</a>';
                $data[$attribute->taxonomy][] = $full_line;
            } else {
                $data[$attribute->taxonomy][] = $attribute->name;
            }
        
        }
        return $data;
    }
    
    /**
     * Add types and terms to WC attributes at frontend catalog loop
     *
     * @param array $attributes
     * @param array $attribute_keys
     * @return array
     */
    public function addTypesToAttributes( $attributes, $attribute_keys )
    {
        global  $product ;
        $result = Model::getWhereNameIn( $attribute_keys );
        if ( !is_null( $result ) ) {
            foreach ( $result as $item ) {
                $attributes[$item->taxonomy_name]['type'] = $item->type;
                $attributes[$item->taxonomy_name]['terms'] = get_terms( array(
                    'taxonomy'   => $item->taxonomy_name,
                    'object_ids' => $product->get_id(),
                ) );
                // filter only product variation used attributes
                $attributes[$item->taxonomy_name]['terms'] = array_filter( $attributes[$item->taxonomy_name]['terms'], function ( $term ) use( $attributes, $item ) {
                    if ( in_array( $term->slug, $attributes[$item->taxonomy_name] ) ) {
                        return true;
                    }
                    return false;
                } );
            }
        }
        return $attributes;
    }
    
    public function filterWooGetScriptData( $params )
    {
        $params['i18n_make_a_selection_text'] = esc_html__( 'The selected variation is not available', 'premmerce-advanced-attributes' );
        return $params;
    }
    
    public function checkIntegration()
    {
        $theme = wp_get_theme();
        if ( 'oceanwp' == $theme->get_template() ) {
            new OceanWpIntegration( $this, $this->fileManager );
        }
    }

}