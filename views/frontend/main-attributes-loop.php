<?php if ( ! defined( 'WPINC' ) ) die; ?> 

<div class="main-attributes-loop">
    <?php foreach ($data as $key => $attribute): ?>
        <?php echo wc_attribute_label( $key ) ?>: <b> <?php echo join(', ', $attribute); ?></b> <br />
    <?php endforeach;?>
</div>
