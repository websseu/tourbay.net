<?php
/**
 * ChromeNews and Blockspare content pattern.
 *
 * @package ChromeNews
 */

return array(
	'title'      => __( 'HybridNews Post Carousel', 'chromenews' ),
    'categories' => array( 'chromenews' ),
	'content'    => '<!-- wp:group {"align":"wide","className":"pattern-row","layout":{"type":"constrained"}} -->
    <div class="wp-block-group alignwide pattern-row">
    
    <!-- wp:group {"align":"full","layout":{"type":"constrained"}} -->
    <div class="wp-block-group alignfull"><!-- wp:heading {"align":"full"} -->
    <h2 class="wp-block-heading alignfull">' . esc_html__( 'Post Carousel', 'chromenews' ) . '</h2>
    <!-- /wp:heading -->
    
    <!-- wp:blockspare/latest-posts-block-carousel-grid {"uniqueClass":"blockspare-eec4157b-b0ef-4","postsToShow":5,"linkColor":"#505050","align":"full","marginTop":0,"marginBottom":28,"backGroundColor":"#ffffff","categoryTextColor":"#ffffff","categoryBackgroundColor":"#003bb3","gutterSpace":3,"numberofSlide":4,"titleOnHoverColor":"#003bb3","animation":"AFTfadeInLeft"} /--></div>
    <!-- /wp:group -->
    
    </div>
    <!-- /wp:group -->',
	
);
