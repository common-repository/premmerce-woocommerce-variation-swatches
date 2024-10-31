<?php

namespace Premmerce\Attributes\Admin\Tabs;

use  Premmerce\Attributes\AttributesPlugin ;
use  Premmerce\Attributes\Admin\Tabs\Base\BaseSettings ;
class Settings extends BaseSettings
{
    /**
     * @var string
     */
    protected  $page = 'premmerce-advanced-attributes-admin-settings' ;
    /**
     * @var string
     */
    protected  $group = 'premmerce_advanced_attributes' ;
    /**
     * @var string
     */
    protected  $optionName = AttributesPlugin::OPTION_SETTINGS ;
    /**
     * Register hooks
     */
    public function init()
    {
        add_action( 'admin_init', array( $this, 'initSettings' ) );
        add_action( 'pre_update_option_' . $this->optionName, array( $this, 'checkBeforeSaveSettings' ) );
    }
    
    public static function googleFonts()
    {
        $fonts = array(
            'open_sans'         => array(
            'name'     => 'Open Sans',
            'url_name' => 'Open+Sans',
            'css'      => "'Open Sans', sans-serif",
            'wght'     => '400;700',
        ),
            'oswald'            => array(
            'name'     => 'Oswald',
            'url_name' => 'Oswald',
            'css'      => "'Oswald', sans-serif",
            'wght'     => '400;700',
        ),
            'source_sans_pro'   => array(
            'name'     => 'Source Sans Pro',
            'url_name' => 'Source+Sans+Pro',
            'css'      => "'Source Sans Pro', sans-serif",
            'wght'     => '400;700',
        ),
            'amatic_sc'         => array(
            'name'     => 'Amatic SC',
            'url_name' => 'Amatic+SC',
            'css'      => "'Amatic SC', cursive",
            'wght'     => '400;700',
        ),
            'caveat'            => array(
            'name'     => 'Caveat',
            'url_name' => 'Caveat',
            'css'      => "'Caveat', cursive",
            'wght'     => '400;700',
        ),
            'comfortaa'         => array(
            'name'     => 'Comfortaa',
            'url_name' => 'Comfortaa',
            'css'      => "'Comfortaa', cursive",
            'wght'     => '400;700',
        ),
            'roboto'            => array(
            'name'     => 'Roboto',
            'url_name' => 'Roboto',
            'css'      => "'Roboto', sans-serif",
            'wght'     => '400;700',
        ),
            'lato'              => array(
            'name'     => 'Lato',
            'url_name' => 'Lato',
            'css'      => "'Lato', sans-serif",
            'wght'     => '400;700',
        ),
            'courgette'         => array(
            'name'     => 'Courgette',
            'url_name' => 'Courgette',
            'css'      => "'Courgette', cursive",
            'wght'     => '400',
        ),
            'eb_garamond'       => array(
            'name'     => 'EB Garamond',
            'url_name' => 'EB+Garamond',
            'css'      => "'EB Garamond', serif",
            'wght'     => '400;700',
        ),
            'lora'              => array(
            'name'     => 'Lora',
            'url_name' => 'Lora',
            'css'      => "'Lora', serif",
            'wght'     => '400;700',
        ),
            'merriweather_sans' => array(
            'name'     => 'Merriweather Sans',
            'url_name' => 'Merriweather+Sans',
            'css'      => "'Merriweather Sans', sans-serif",
            'wght'     => '400;700',
        ),
            'playfair_display'  => array(
            'name'     => 'Playfair Display',
            'url_name' => 'Playfair+Display',
            'css'      => "'Playfair Display', serif",
            'wght'     => '400;700',
        ),
        );
        return $fonts;
    }
    
    public static function getMainStaticSettings()
    {
        $variationForms = array(
            'square' => __( 'Square', 'premmerce-advanced-attributes' ),
            'circle' => __( 'Circle', 'premmerce-advanced-attributes' ),
        );
        $fontWeightVariation = array(
            'normal' => __( 'Normal', 'premmerce-advanced-attributes' ),
            'bold'   => __( 'Bold', 'premmerce-advanced-attributes' ),
        );
        $fontFamilyVariation = array(
            'default' => __( 'Default', 'premmerce-advanced-attributes' ),
        );
        $fonts = self::googleFonts();
        if ( !empty($fonts) ) {
            foreach ( $fonts as $key => $font ) {
                $fontFamilyVariation[$key] = $font['name'];
            }
        }
        $settings = array(
            'general_styles' => array(
            'label'  => __( 'General Styles', 'premmerce-advanced-attributes' ),
            'fields' => array(
            'variation_form'       => array(
            'plan'         => AttributesPlugin::PLAN_PREMIUM,
            'type'         => 'select',
            'options'      => $variationForms,
            'multiple'     => false,
            'title'        => __( 'Variation forms', 'premmerce-advanced-attributes' ),
            'help'         => __( 'Color, bicolor and image form.', 'premmerce-advanced-attributes' ),
            'help_premium' => __( 'It is only available in the premium version.', 'premmerce-filter' ),
        ),
            'default_border_color' => array(
            'plan'     => AttributesPlugin::PLAN_PREMIUM,
            'class'    => 'default-swatch-border-color',
            'type'     => 'colorpicker',
            'multiple' => false,
            'title'    => __( 'Default Swatch Border Color', 'premmerce-advanced-attributes' ),
        ),
            'active_border_color'  => array(
            'plan'     => AttributesPlugin::PLAN_PREMIUM,
            'class'    => 'active-swatch-border-color',
            'type'     => 'colorpicker',
            'multiple' => false,
            'title'    => __( 'Active Swatch Border Color', 'premmerce-advanced-attributes' ),
        ),
            'hover_border_color'   => array(
            'plan'     => AttributesPlugin::PLAN_PREMIUM,
            'class'    => 'hover-swatch-border-color',
            'type'     => 'colorpicker',
            'multiple' => false,
            'title'    => __( 'Swatch Hover Color', 'premmerce-advanced-attributes' ),
        ),
            'border_width'         => array(
            'plan'        => AttributesPlugin::PLAN_PREMIUM,
            'type'        => 'number',
            'placeholder' => 1,
            'title'       => __( 'Border width', 'premmerce-advanced-attributes' ),
        ),
            'variation_padding'    => array(
            'plan'        => AttributesPlugin::PLAN_PREMIUM,
            'type'        => 'number',
            'placeholder' => 4,
            'title'       => __( 'Variation padding', 'premmerce-advanced-attributes' ),
        ),
        ),
        ),
            'label_styles'   => array(
            'label'  => __( 'Label Styles', 'premmerce-advanced-attributes' ),
            'fields' => array(
            'label_font_family' => array(
            'plan'     => AttributesPlugin::PLAN_PREMIUM,
            'type'     => 'select',
            'options'  => $fontFamilyVariation,
            'multiple' => false,
            'title'    => __( 'Labels Font Family', 'premmerce-advanced-attributes' ),
        ),
            'label_font_weight' => array(
            'plan'     => AttributesPlugin::PLAN_PREMIUM,
            'type'     => 'select',
            'options'  => $fontWeightVariation,
            'multiple' => false,
            'title'    => __( 'Labels Font weight', 'premmerce-advanced-attributes' ),
        ),
            'label_font_size'   => array(
            'plan'        => AttributesPlugin::PLAN_PREMIUM,
            'type'        => 'number',
            'placeholder' => 14,
            'title'       => __( 'Labels Font size', 'premmerce-advanced-attributes' ),
        ),
            'label_padding_tb'  => array(
            'plan'        => AttributesPlugin::PLAN_PREMIUM,
            'type'        => 'number',
            'placeholder' => 5,
            'title'       => __( 'Labels Padding Top/Bottom', 'premmerce-advanced-attributes' ),
        ),
            'label_padding_lr'  => array(
            'plan'        => AttributesPlugin::PLAN_PREMIUM,
            'type'        => 'number',
            'placeholder' => 5,
            'title'       => __( 'Labels Padding Left/Right', 'premmerce-advanced-attributes' ),
        ),
        ),
        ),
        );
        return $settings;
    }
    
    /**
     * Init settings
     */
    public function initSettings()
    {
        register_setting( $this->group, $this->optionName );
        $settings = self::getMainStaticSettings();
        $this->registerSettings( $settings, $this->page, $this->optionName );
    }
    
    /**
     * @return string
     */
    public function getLabel()
    {
        return __( 'Settings', 'premmerce-advanced-attributes' );
    }
    
    /**
     * @return string
     */
    public function getName()
    {
        return 'settings';
    }
    
    /**
     * @return bool
     */
    public function valid()
    {
        return true;
    }
    
    public static function renderGoogleFontFamily()
    {
        $renderGoogleFontFamily = '';
        $fonts = self::googleFonts();
        foreach ( $fonts as $key => $font ) {
            $renderGoogleFontFamily .= ".{$key}_option { font-family: {$font['css']}; } ";
        }
        return $renderGoogleFontFamily;
    }

}