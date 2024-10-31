<?php
use Premmerce\Attributes\AttributesPlugin;
?>
<div class="pc-variation-color">
    <div class="pc-variation-color__list" data-variation-control--scope>
        <?php
        $additionalClasses  = '';
        $additionalClasses .= isset($settings['variation_form']) ? ' premmerce-' . $settings['variation_form'] : '';

        foreach ( $options['terms'] as $key => $option ):

            $active_class = premmerce_get_variation_active_class($default_attributes, $attribute_name, $filter, $attribute_filter_name, $option, $has_filter);

            $firstColor   = get_term_meta( $option->term_id, AttributesPlugin::FIRST_COLOR, true );
            $secondColor  = get_term_meta( $option->term_id, AttributesPlugin::SECOND_COLOR, true );

            $firstColor  = $firstColor ? $firstColor : '#96588a';
            $secondColor = $secondColor ? $secondColor : '#96588a';
            ?>

            <div class="pc-variation-color__control <?php echo esc_attr($active_class) . esc_attr($additionalClasses); ?>"
                 data-variation-control
                 data-variable-type="<?php echo esc_attr($type); ?>"
                 data-select="<?php echo esc_attr($option->slug); ?>"
                 data-attritute="<?php echo esc_attr($attribute_name); ?>"
                 title="<?php echo $option->name; ?>">
                    <span class="pc-variation-color__variable-color <?php echo esc_attr($additionalClasses); ?>"
                          <?php printf(
                                'style="background: linear-gradient(135deg, %1$s %2$s, %3$s %4$s)";',
                                $firstColor, '50%', $secondColor, '50%'
                            );
                        ?>
                    >

                    </span>
            </div>

        <?php endforeach; ?>
    </div>
</div>
