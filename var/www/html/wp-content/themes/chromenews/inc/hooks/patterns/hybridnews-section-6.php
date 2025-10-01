<?php
/**
 * ChromeNews and Blockspare content pattern.
 *
 * @package ChromeNews
 */

return array(
	'title'      => __( 'HybridNews Featured News', 'chromenews' ),
    'categories' => array( 'chromenews' ),
	'content'    => '<!-- wp:group {"align":"wide","className":"pattern-row","layout":{"type":"constrained"}} -->
    <div class="wp-block-group alignwide pattern-row">
    
    <!-- wp:group {"align":"full","layout":{"type":"constrained"}} -->
    <div class="wp-block-group alignfull"><!-- wp:heading {"align":"full"} -->
    <h2 class="wp-block-heading alignfull">' . esc_html__( 'Featured News', 'chromenews' ) . '</h2>
    <!-- /wp:heading -->
    
    <!-- wp:blockspare/blockspare-latest-posts-grid {"categories":[],"taxType":"","uniqueClass":"blockspare-84a98b29-8639-4","linkColor":"#505050","columns":4,"align":"full","imageSize":"medium","marginTop":0,"marginBottom":28,"backGroundColor":"#ffffff","categoryBorderRadius":1,"titleOnHoverColor":"#404040","animation":"AFTfadeInDown","gutterSpace":15} /--></div>
    <!-- /wp:group -->    
    </div>
    <!-- /wp:group -->',
	
);
