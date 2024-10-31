<?php

use Premmerce\Attributes\Admin\Admin;

function premmerce_get_main_attributes( $postId ){
    return Admin::getMainAttributes( $postId );
}

function premmerce_get_attribute_description( $taxonomy ){
    return Admin::getAttributeDescription( $taxonomy );
}

function premmerce_get_variation_active_class($default_attributes, $attribute_name, $filter, $attribute_filter_name, $option, $has_filter){
    $has_default = in_array( $attribute_name, array_keys( $default_attributes ) ) ? true : false;
    $has_chosen  = isset($_REQUEST[ 'attribute_'. $attribute_name ]);
    $active_class = '';

    if($has_chosen && $_REQUEST[ 'attribute_'. $attribute_name ] == $option->slug){
        $active_class = 'variable-active';
    } else if( $filter ){
        if( strtolower(urlencode($_REQUEST[ $attribute_filter_name ])) == $option->slug){
            $active_class = 'variable-active';
        }
    } else {
        if( $has_default && ! $has_filter && !$has_chosen){
            if(  $option->slug == $default_attributes[$attribute_name] ){
                $active_class = 'variable-active';
            }
        }
    }

    return $active_class;
}