<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package ChromeNews
 */

?>


<?php if (is_singular()): ?>
    <div class="color-pad">
        <div class="entry-content read-details">

            <?php
    if (has_excerpt($post->ID)):

?>
                <div class="post-excerpt">
                    <?php echo wp_kses_post(get_the_excerpt($post->ID)); ?>
                </div>
            <?php
    endif; ?>

            <?php
    the_content(sprintf(
        wp_kses(
        /* translators: %s: Name of current post. Only visible to screen readers */
        __('Continue reading<span class="screen-reader-text"> "%s"</span>', 'chromenews'),
        array(
        'span' => array(
            'class' => array(),
        ),
    )
    ),
        get_the_title()
    )); ?>
            <?php if (is_single()): ?>
                <div class="post-item-metadata entry-meta">
                    <?php chromenews_post_item_tag(); ?>
                </div>
            <?php
    endif; ?>
            <?php
    $social_share_icon_opt = chromenews_get_option('single_post_social_share_view');
    if ($social_share_icon_opt == 'after-content') {
        chromenews_single_post_social_share_icons($post->ID);
    }
?>
            <?php

$previous_post_thumb = '';
$next_post_thumb = '';
$previous_no_thumb_class = "has-post-image";
// Previous/next post navigation.
$previous_post = get_previous_post();
$previous_post_thumb = '';
if (isset($previous_post->ID)) {
    $previous_post_thumb = chromenews_the_post_thumbnail('thumbnail', $previous_post->ID, true);
    
    if (!isset($previous_post_thumb) && empty($previous_post_thumb)) {
        $previous_no_thumb_class = "no-post-image";
    }
}

$next_post = get_next_post();
$next_post_thumb = '';
$next_no_thumb_class = "has-post-image";
if (isset($next_post->ID)) {
    $next_post_thumb = chromenews_the_post_thumbnail('thumbnail', $next_post->ID, true);
    
    if (!isset($next_post_thumb) && empty($next_post_thumb)) {
        $next_no_thumb_class = "no-post-image";
    }
}


the_post_navigation( array(
    'next_text' => sprintf(
        '<span class="meta-nav" aria-hidden="true">%s</span> ' .
        '<span class="screen-reader-text">%s</span> ' .
        '<span class="chromenews-next-post %s"><h4><span class="post-title">%%title</span></h4><span class="post-image">%s</span></span>',
        esc_html__( 'Next', 'chromenews' ),
        esc_html__( 'Next post:', 'chromenews' ),
        esc_attr( $next_no_thumb_class ),
        $next_post_thumb
    ),
    'prev_text' => sprintf(
        '<span class="meta-nav" aria-hidden="true">%s</span> ' .
        '<span class="screen-reader-text">%s</span> ' .
        '<span class="chromenews-prev-post %s"><h4><span class="post-title">%%title</span></h4><span class="post-image">%s</span></span>',
        esc_html__( 'Previous', 'chromenews' ),
        esc_html__( 'Previous post:', 'chromenews' ),
        esc_attr( $previous_no_thumb_class ),
        $previous_post_thumb
    ),
    /* translators: Hidden heading for the post navigation section. */
    'screen_reader_text' => esc_html__( 'Post navigation', 'chromenews' ),
) );



?>
            <?php wp_link_pages(array(
        'before' => '<div class="page-links">' . esc_html__('Pages:', 'chromenews'),
        'after' => '</div>',
    ));
?>
        </div><!-- .entry-content -->
    </div>
<?php
else:



    do_action('chromenews_action_archive_layout');

endif;
