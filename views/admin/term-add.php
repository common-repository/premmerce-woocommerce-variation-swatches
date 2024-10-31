<?php if ( ! defined( 'WPINC' ) ) die; ?>

<?php
if( 'image' == $taxonomy->type ): ?>
    $fileManager->includeTemplate( 'admin/term-types/image.php' );
<?php endif; ?>