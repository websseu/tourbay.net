<?php
if (!function_exists('chromenews_loop_list')) :
  /**
   * Banner Slider
   *
   * @since Newsical 1.0.0
   *
   */
  function chromenews_loop_list($chromenews_post_id, $chromenews_thumbnail_size = 'medium', $chromenews_count = 0, $show_cat = false, $show_meta = true, $show_excerpt = false, $big_img = false, $archive_content_view = 'archive-content-excerpt')
  {
    $chromenews_post_display = 'list-post';
    if ($big_img) {
      $chromenews_post_display = 'spotlight-post';
    }
    $chromenews_post_thumbnail = chromenews_the_post_thumbnail($chromenews_thumbnail_size, $chromenews_post_id, true);
    $chromenews_no_thumbnail_class = "has-post-image";
    if (!isset($chromenews_post_thumbnail) && empty($chromenews_post_thumbnail)) {
      $chromenews_no_thumbnail_class = "no-post-image";
    }
?>
    <div class="af-double-column list-style clearfix aft-list-show-image <?php echo esc_attr($chromenews_no_thumbnail_class); ?>">
      <div class="read-single color-pad">
        <div class="col-3 float-l pos-rel read-img read-bg-img">
          <a class="aft-post-image-link"
            href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr(get_the_title($chromenews_post_id)) ?>"></a>
          <?php
          if ($chromenews_post_thumbnail) {
            echo wp_kses_post($chromenews_post_thumbnail);
          }
          ?>
          <?php if (absint($chromenews_count) > 0): ?>
            <span class="trending-no"><?php echo esc_html($chromenews_count); ?></span>
          <?php endif; ?>
          <?php if ($big_img != false): ?>
            <div class="category-min-read-wrap af-cat-widget-carousel">
              <div class="post-format-and-min-read-wrap">
                <?php chromenews_post_format($chromenews_post_id); ?>
                <?php chromenews_count_content_words($chromenews_post_id); ?>
              </div>
              <div class="read-categories categories-inside-image">
                <?php chromenews_post_categories(); ?>
              </div>
            </div>
          <?php endif; ?>
        </div>
        <div class="col-66 float-l pad read-details color-tp-pad">
          <?php if ($big_img == false): ?>
            <?php if ($show_cat != false): ?>
              <div class="read-categories">
                <?php chromenews_post_categories(); ?>
              </div>
            <?php endif; ?>
          <?php endif; ?>

          <div class="read-title">
            <h3>
              <a href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr(get_the_title($chromenews_post_id)); ?>"><?php the_title(); ?></a>
            </h3>
          </div>
          <?php if ($show_meta != false): ?>
            <div class=" post-item-metadata entry-meta">
              <?php chromenews_post_item_meta($chromenews_post_display); ?>
              <?php chromenews_get_comments_views_share($chromenews_post_id); ?>
            </div>
          <?php endif; ?>

          <?php if ($show_excerpt != false):   ?>
            <div class="read-descprition full-item-discription">
              <div class="post-description">
                <?php
                if ($archive_content_view == 'archive-content-full') {
                  the_content();
                } else {
                  $chromenews_excerpt = chromenews_get_the_excerpt($chromenews_post_id);
                  if ($chromenews_excerpt) {
                    echo wp_kses_post($chromenews_excerpt);
                  }
                }
                ?>
              </div>
            </div>
          <?php endif; ?>

        </div>
      </div>
    </div>

<?php
  }
endif;
add_action('chromenews_action_loop_list', 'chromenews_loop_list', 10, 8);
