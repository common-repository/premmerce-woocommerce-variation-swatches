<?php if ( ! defined( 'WPINC' ) ) die; ?>
<div class="main-attributes-single">
    <?php foreach ( $data as $key => $terms ): ?>
        <?php echo wc_attribute_label( $key ) ?>: <b> <?php echo  join(', ', $terms ); ?></b> <br />
    <?php endforeach;?>
</div>
