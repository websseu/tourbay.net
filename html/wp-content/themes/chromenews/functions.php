<?php

/**
 * ChromeNews functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package ChromeNews
 */

/**
 * Define Theme Constants.
 */

defined('ESHF_COMPATIBILITY_TMPL') or define('ESHF_COMPATIBILITY_TMPL', 'chromenews');

/**
 * ChromeNews functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package ChromeNews
 */

if (!function_exists('chromenews_setup')):
  /**
   * Sets up theme defaults and registers support for various WordPress features.
   *
   * Note that this function is hooked into the after_setup_theme hook, which
   * runs before the init hook. The init hook is too late for some features, such
   * as indicating support for post thumbnails.
   */
  /**
   *
   */
  /**
   *
   */
  function chromenews_setup()
  {
    /*
         * Make theme available for translation.
         * Translations can be filed in the /languages/ directory.
         * If you're building a theme based on ChromeNews, use a find and replace
         * to change 'chromenews' to the name of your theme in all the template files.
         */
    load_theme_textdomain('chromenews', get_template_directory() . '/languages');

    // Add default posts and comments RSS feed links to head.
    add_theme_support('automatic-feed-links');

    /*
         * Let WordPress manage the document title.
         * By adding theme support, we declare that this theme does not use a
         * hard-coded <title> tag in the document head, and expect WordPress to
         * provide it for us.
         */
    add_theme_support('title-tag');

    /*
         * Enable support for Post Thumbnails on posts and pages.
         *
         * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
         */
    add_theme_support('post-thumbnails');
    // Add featured image sizes
    add_image_size('chromenews-featured', 1024, 0, false); // width, height, crop
    add_image_size('chromenews-large', 825, 575, true); // width, height, crop
    add_image_size('chromenews-medium', 590, 410, true); // width, height, crop


    /*
         * Enable support for Post Formats on posts and pages.
         *
         * @link https://developer.wordpress.org/themes/functionality/post-formats/
         */
    add_theme_support('post-formats', array('image', 'video', 'gallery'));

    // This theme uses wp_nav_menu() in one location.
    register_nav_menus(array(
      'aft-primary-nav' => esc_html__('Primary Menu', 'chromenews'),
      'aft-social-nav' => esc_html__('Social Menu', 'chromenews'),
      'aft-footer-nav' => esc_html__('Footer Menu', 'chromenews'),
    ));

    /*
         * Switch default core markup for search form, comment form, and comments
         * to output valid HTML5.
         */
    add_theme_support('html5', array(
      'search-form',
      'comment-form',
      'comment-list',
      'gallery',
      'caption',
    ));

    // Set up the WordPress core custom background feature.
    add_theme_support('custom-background', apply_filters('chromenews_custom_background_args', array(
      'default-color' => 'eeeeee',
      'default-image' => '',
    )));

    // Add theme support for selective refresh for widgets.
    add_theme_support('customize-selective-refresh-widgets');



    /**
     * Add support for core custom logo.
     *
     * @link https://codex.wordpress.org/Theme_Logo
     */
    add_theme_support('custom-logo', array(
      'flex-width' => true,
      'flex-height' => true,
    ));


    /*
	    * Add theme support for gutenberg block
	    */
    add_theme_support('align-wide');
    add_theme_support('responsive-embeds');
    add_theme_support('wp-block-styles');
    add_theme_support('appearance-tools');
    add_theme_support('custom-spacing');
    add_theme_support('custom-units');
    add_theme_support('custom-line-height');
    add_theme_support('border');
    add_theme_support('link-color');
  }
endif;
add_action('after_setup_theme', 'chromenews_setup');

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function chromenews_content_width()
{
  $GLOBALS['content_width'] = apply_filters('chromenews_content_width', 640);
}

add_action('after_setup_theme', 'chromenews_content_width', 0);


/**
 * Filter font variants to include only necessary ones.
 *
 * @param string $font The font string (e.g., "Roboto:400,300,700").
 * @return string Filtered font string with only the allowed variants.
 */
function chromenews_filter_font_variants($font) {
  if (empty($font) || strpos($font, ':') === false) {
    return $font; // Return as is if no variants exist.
  }

  list($font_name, $variants) = explode(':', $font);

  $allowed_variants = array('400', '700');
  $font_variants = explode(',', $variants);
  $filtered_variants = array_intersect($font_variants, $allowed_variants);

  return !empty($filtered_variants) ? $font_name . ':' . implode(',', $filtered_variants) : $font_name;
}

/**
 * Generate the Google Fonts URL based on theme options and locale.
 *
 * @since 1.0.0
 * @return string Google Fonts URL or empty string if using system fonts.
 */
function chromenews_fonts_url() {
  $global_font_family_type = chromenews_get_option('global_font_family_type');

  // Only load Google Fonts if selected
  if ($global_font_family_type !== 'google') {
    return '';
  }

  $fonts_url = '';
  $fonts = array();
  $subsets = array('latin');

  $locale = get_locale();
  $subset_mapping = array(
    'cs' => 'latin-ext',
    'pl' => 'latin-ext',
    'hu' => 'latin-ext',
    'ru' => 'cyrillic',
    'el' => 'greek',
    'vi' => 'vietnamese',
  );

  foreach ($subset_mapping as $lang_code => $subset) {
    if (strpos($locale, $lang_code) !== false) {
      $subsets[] = $subset;
      break;
    }
  }

  // Get font options and apply filtering
  $site_title_font = chromenews_filter_font_variants(chromenews_get_option('site_title_font'));
  $primary_font    = chromenews_filter_font_variants(chromenews_get_option('primary_font'));
  $secondary_font  = chromenews_filter_font_variants(chromenews_get_option('secondary_font'));

  foreach (array($site_title_font, $primary_font, $secondary_font) as $font) {
    if (!empty($font) && 'off' !== sprintf(_x('on', '%s font: on or off', 'chromenews'), $font)) {
      $fonts[] = $font;
    }
  }

  $fonts = array_unique($fonts);

  if (!empty($fonts)) {
    $fonts_url = add_query_arg(array(
      'family'  => implode('|', $fonts),
      'subset'  => implode(',', array_unique($subsets)),
      'display' => 'swap',
    ), 'https://fonts.googleapis.com/css');
  }

  return esc_url($fonts_url);
}

/**
 * Add preconnect links for Google Fonts domains to improve performance.
 *
 * @param array  $urls          URLs to print for resource hints.
 * @param string $relation_type The relation type of the URLs (e.g., 'preconnect').
 * @return array Filtered URLs.
 */
function chromenews_add_preconnect_links($urls, $relation_type) {
  if ('preconnect' === $relation_type && chromenews_get_option('global_font_family_type') === 'google') {
    $urls[] = 'https://fonts.googleapis.com';
    $urls[] = 'https://fonts.gstatic.com';
  }

  return $urls;
}
add_filter('wp_resource_hints', 'chromenews_add_preconnect_links', 10, 2);

/**
 * Preload Google Fonts stylesheets in the <head> for performance.
 */
function chromenews_preload_google_fonts() {
  $fonts_url = chromenews_fonts_url();

  if ($fonts_url) {
    printf(
      "<link rel='preload' href='%s' as='style' onload=\"this.onload=null;this.rel='stylesheet'\" type='text/css' media='all' crossorigin='anonymous'>\n",
      esc_url($fonts_url)
    );
  }
}
add_action('wp_head', 'chromenews_preload_google_fonts', 1);

/**
 * Enqueue the theme's Google Fonts stylesheet with additional optimization.
 */
function chromenews_enqueue_google_fonts() {
  $fonts_url = chromenews_fonts_url();

  if ($fonts_url) {
    wp_enqueue_style('chromenews-google-fonts', $fonts_url, array(), null);
  }
}
add_action('wp_enqueue_scripts', 'chromenews_enqueue_google_fonts');




/**
 * Load Init for Hook files.
 */
require get_template_directory() . '/inc/custom-style.php';

/**
 * Enqueue styles.
 */

add_action('wp_enqueue_scripts', 'chromenews_style_files');
function chromenews_style_files()
{

  $min = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';

  wp_enqueue_style('chromenews-icons', get_template_directory_uri() . '/assets/icons/style.css');


  // $fonts_url = chromenews_fonts_url();
  $chromenews_version =  wp_get_theme()->get('Version');

  // if (!empty($fonts_url)) {
  //     wp_enqueue_style('chromenews-google-fonts', $fonts_url, array(), null);
  // }

  /**
   * Load WooCommerce compatibility file.
   */
  if (class_exists('WooCommerce')) {
    wp_enqueue_style('chromenews-woocommerce-style', get_template_directory_uri() . '/woocommerce.css');

    $font_path = WC()->plugin_url() . '/assets/fonts/';
    $inline_font = '@font-face {
			font-family: "star";
			src: url("' . $font_path . 'star.eot");
			src: url("' . $font_path . 'star.eot?#iefix") format("embedded-opentype"),
				url("' . $font_path . 'star.woff") format("woff"),
				url("' . $font_path . 'star.ttf") format("truetype"),
				url("' . $font_path . 'star.svg#star") format("svg");
			font-weight: normal;
			font-style: normal;
		}';

    wp_add_inline_style('chromenews-woocommerce-style', $inline_font);
  }


  wp_enqueue_style('chromenews-style', get_template_directory_uri() . '/style' . $min . '.css', array(), $chromenews_version);
  wp_add_inline_style('chromenews-style', chromenews_custom_style());

  if (is_rtl() && is_child_theme()) {
    wp_enqueue_style(
      'chromenews-rtl',
      get_template_directory_uri() . '/rtl.css',
      array(), // Load after other styles
      $chromenews_version
    );
  }
}

/**
 * Enqueue scripts.
 */

function chromenews_scripts()
{

  $min = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';
  $chromenews_version =  wp_get_theme()->get('Version');

  wp_enqueue_script('jquery');
  wp_enqueue_script('jquery-cookie', get_template_directory_uri() . '/assets/jquery.cookie.js');
  wp_enqueue_script('chromenews-toggle-script', get_template_directory_uri() . '/assets/toggle-script.js', array('jquery-cookie'), $chromenews_version, true);
  wp_enqueue_script('chromenews-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20151215', true);
  wp_enqueue_script('chromenews-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), $chromenews_version, true);
  wp_enqueue_script('chromenews-script', get_template_directory_uri() . '/admin-dashboard/dist/chromenews_scripts.build.js', array('jquery'), $chromenews_version, true);

  $top_header_time_format = chromenews_get_option('top_header_time_format');
  $localized_time_format = array();
  if ($top_header_time_format == 'en-US' || $top_header_time_format == 'en-GB') {
    $localized_time_format['format'] = $top_header_time_format;
    wp_localize_script('chromenews-script', 'AFlocalizedTime', $localized_time_format);
  }

  if (is_singular() && comments_open() && get_option('thread_comments')) {
    wp_enqueue_script('comment-reply');
  }

  if (is_active_sidebar('express-off-canvas-panel')) {
    wp_enqueue_style('sidr', get_template_directory_uri() . '/assets/sidr/css/jquery.sidr.dark.css', array(), $chromenews_version);
    wp_enqueue_script('sidr', get_template_directory_uri() . '/assets/sidr/js/jquery.sidr' . $min . '.js', array('jquery'), $chromenews_version, true);
  }

  if (is_page_template('tmpl-front-page.php') || is_front_page() || is_home()) {

    $show_main_news_section = chromenews_get_option('show_main_news_section');
    $show_flash_news_section = chromenews_get_option('show_flash_news_section');
    $main_banner_layout = chromenews_get_option('select_main_banner_layout_section');
    if ($show_flash_news_section) {
      wp_enqueue_script('marquee', get_template_directory_uri() . '/assets/marquee/jquery.marquee.js', array('jquery'), $chromenews_version, true);
    }

    if ($show_main_news_section) {
      wp_enqueue_style('slick', get_template_directory_uri() . '/assets/slick/css/slick' . $min . '.css', array(), $chromenews_version);
      wp_enqueue_script('slick', get_template_directory_uri() . '/assets/slick/js/slick' . $min . '.js', array('jquery'), $chromenews_version, true);
    }
    wp_enqueue_script('bootstrap', get_template_directory_uri() . '/assets/bootstrap/js/bootstrap' . $min . '.js', array('jquery'), $chromenews_version, true);
  }

  if (!is_singular()) {

    wp_enqueue_script('matchheight', get_template_directory_uri() . '/assets/jquery-match-height/jquery.matchHeight' . $min . '.js', array('jquery'), $chromenews_version, true);
  }

  if (has_block('gallery') || is_active_widget(false, false, 'media_gallery')) {

    wp_enqueue_script('magnific-popup', get_template_directory_uri() . '/assets/magnific-popup/jquery.magnific-popup' . $min . '.js', array('jquery'), $chromenews_version, true);
    wp_enqueue_style('magnific-popup', get_template_directory_uri() . '/assets/magnific-popup/magnific-popup.css', array(), $chromenews_version);
  }
}

add_action('wp_enqueue_scripts', 'chromenews_scripts');


/**
 * Enqueue admin scripts and styles.
 *
 * @since ChromeNews 1.0.0
 */
function chromenews_admin_scripts($hook)
{
  if ('widgets.php' === $hook) {
    wp_enqueue_media();
    wp_enqueue_script('chromenews-widgets', get_template_directory_uri() . '/assets/widgets.js', array('jquery'), '1.0.0', true);
  }
  wp_enqueue_style('chromenews-notice', get_template_directory_uri() . '/assets/css/notice.css');
}

add_action('admin_enqueue_scripts', 'chromenews_admin_scripts');





/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';


/**
 * Custom Multi Author tags for this theme.
 */
require get_template_directory() . '/inc/multi-author.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-images.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';


/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer/customizer.php';

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/init.php';





/**
 * Load Jetpack compatibility file.
 */
if (defined('JETPACK__VERSION')) {
  require get_template_directory() . '/inc/jetpack.php';
}

/**
 * Load WooCommerce compatibility file.
 */
if (class_exists('WooCommerce')) {
  require get_template_directory() . '/inc/woocommerce.php';
}

/**
 * Descriptions on Header Menu
 * @author AF themes
 * @param string $item_output , HTML outputp for the menu item
 * @param object $item , menu item object
 * @param int $depth , depth in menu structure
 * @param object $args , arguments passed to wp_nav_menu()
 * @return string $item_output
 */
function chromenews_header_menu_desc($item_output, $item, $depth, $args)
{

  if (isset($args->theme_location) && 'aft-primary-nav' == $args->theme_location && $item->description)
    $item_output = str_replace('</a>', '<span class="menu-description">' . $item->description . '</span></a>', $item_output);

  return $item_output;
}

add_filter('walker_nav_menu_start_el', 'chromenews_header_menu_desc', 10, 4);

function chromenews_menu_notitle($menu)
{
  return $menu = preg_replace('/ title=\"(.*?)\"/', '', $menu);
}
add_filter('wp_nav_menu', 'chromenews_menu_notitle');
add_filter('wp_page_menu', 'chromenews_menu_notitle');
add_filter('wp_list_categories', 'chromenews_menu_notitle');

add_action('after_setup_theme', 'chromenews_transltion_init');


function chromenews_transltion_init()
{
  load_theme_textdomain('chromenews', false, get_template_directory()  . '/languages');
}

function chromenews_enqueue_custom_fonts() {
    // custom-fonts.css 불러오기
    wp_enqueue_style(
        'chromenews-custom-fonts',
        get_template_directory_uri() . '/assets/css/custom-fonts.css',
        array(),
        null
    );
}
add_action('wp_enqueue_scripts', 'chromenews_enqueue_custom_fonts');

add_action('after_setup_theme', function() {
    add_editor_style('assets/css/custom-fonts.css');
});