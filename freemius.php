<?php

if ( !function_exists( 'premmerce_pwvs_fs' ) ) {
    // Create a helper function for easy SDK access.
    function premmerce_pwvs_fs()
    {
        global  $premmerce_pwvs_fs ;
        
        if ( !isset( $premmerce_pwvs_fs ) ) {
            // Include Freemius SDK.
            require_once dirname( __FILE__ ) . '/freemius/start.php';
            $premmerce_pwvs_fs = fs_dynamic_init( array(
                'id'              => '2411',
                'slug'            => 'premmerce-woocommerce-variation-swatches',
                'type'            => 'plugin',
                'public_key'      => 'pk_fec133936b4c327762a9e26d862d9',
                'is_premium'      => false,
                'premium_suffix'  => '',
                'has_addons'      => false,
                'has_paid_plans'  => true,
                'trial'           => array(
                'days'               => 7,
                'is_require_payment' => true,
            ),
                'has_affiliation' => 'all',
                'menu'            => array(
                'slug'    => 'premmerce-advanced-attributes-admin',
                'support' => false,
                'pricing' => true,
                'contact' => false,
                'account' => false,
                'parent'  => array(
                'slug' => 'premmerce',
            ),
            ),
                'is_live'         => true,
            ) );
        }
        
        return $premmerce_pwvs_fs;
    }
    
    // Init Freemius.
    premmerce_pwvs_fs();
    // Signal that SDK was initiated.
    do_action( 'premmerce_pwvs_fs_loaded' );
}
