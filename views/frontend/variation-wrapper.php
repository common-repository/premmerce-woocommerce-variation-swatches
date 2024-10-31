<?php

use Premmerce\Attributes\AttributesPlugin;

$has_filter = false;

if( preg_grep('/^filter/', array_keys( $_GET ) ) ){
    $has_filter = true;
    $product->set_default_attributes( [] );
}

if(!isset($loop_product)){
    $loop_product = false;
}

?>

<div class="pc-variations-table <?php echo $loop_product ? 'pc-variations-table--loop-product':'pc-variations-table--single-product'; ?> variations" data-variations-scope>

    <?php foreach ( $attributes as $attribute_name => $options ) : ?>

        <?php $select_id = 'product-' . $product->get_id() . '_' . $attribute_name; ?>

        <?php
            if(!isset($options['type'])) $options['type'] = 'default';
        ?>

        <div class="pc-variations-table__row pc-variations-table__row--type-<?php echo esc_attr($options['type']); ?>"
             data-attribute-select-row>
            <div class="pc-variations-table__column pc-variations-table__column--label">
                <label for="<?php echo esc_attr(sanitize_title( $select_id )); ?>">
                    <?php echo wc_attribute_label( $attribute_name ); ?>
                </label>
            </div>
            <div class="pc-variations-table__column pc-variations-table__column--value">

                <?php
                $type                  = isset($options['type']) ? $options['type'] : false;
                $has_custom_type       = in_array( $type, ['color', 'bicolor', 'image', 'label', 'radio'] );
                $filter                = false;
                $attribute_filter_name = 'filter_' . substr( $attribute_name, 3 );
                $settings              = get_option(AttributesPlugin::OPTION_SETTINGS, []);

                if( isset( $_GET[ $attribute_filter_name ] ) ){
                    $filter = $_GET[ $attribute_filter_name ];
                }

                if($type == 'radio' && $loop_product){
                    $has_custom_type = false;
                }

                if( $has_custom_type ){
                    wc_get_template( "variations/${type}.php", array(
                        'attribute_name'        => strtolower( urlencode( $attribute_name ) ),
                        'options'               => $options,
                        'default_attributes'    => $default_attributes,
                        'has_filter'            => $has_filter,
                        'filter'                => $filter,
                        'attribute_filter_name' => $attribute_filter_name,
                        'type'                  => $type,
                        'settings'              => $settings
                    ) );
                }
                ?>

                <div class="variants-select <?php echo $has_custom_type ? 'hidden':''; ?>">
                    <?php
                        unset($options['terms']);
                        $selected = isset( $_REQUEST[ 'attribute_' . sanitize_title( $attribute_name ) ] ) ? wc_clean( stripslashes( urldecode( $_REQUEST[ 'attribute_' . sanitize_title( $attribute_name ) ] ) ) ) : $product->get_variation_default_attribute( $attribute_name );
                        $selected = $has_filter ? $filter : $selected;
                        wc_dropdown_variation_attribute_options( array( 'id' => esc_attr(sanitize_title( $select_id )), 'options' => $options, 'attribute' => $attribute_name, 'product' => $product, 'selected' => $selected ) );
                    ?>
                </div>
            </div>

        </div>
    <?php endforeach;?>
</div>
