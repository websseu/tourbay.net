<?php
// Frontpage

$wp_customize->selective_refresh->add_partial('aft_custom_link', array(
    'selector' => 'div.custom-menu-link i',     
));

$wp_customize->selective_refresh->add_partial('enable_site_mode_switch', array(
    'selector' => 'span.aft-icon-circle',     
));

$wp_customize->selective_refresh->add_partial('top_header_time_format', array(
    'selector' => 'span.topbar-date',     
));

$wp_customize->selective_refresh->add_partial('select_header_image_mode', array(
    'selector' => 'div.mid-header-wrapper div.mid-header div.container-wrapper',     
));

$wp_customize->selective_refresh->add_partial('frontpage_popular_tags_section_title', array(
    'selector' => 'div.aft-popular-taxonomies-lists',     
));

$wp_customize->selective_refresh->add_partial('flash_news_title', array(
    'selector' => 'div.exclusive-posts',     
));

$wp_customize->selective_refresh->add_partial('select_main_banner_layout_section', array(
    'selector' => 'div.aft-banner-main',     
));

$wp_customize->selective_refresh->add_partial('main_trending_news_section_title', array(
    'selector' => 'div.aft-banner-trending',     
));

$wp_customize->selective_refresh->add_partial('main_editors_picks_section_title', array(
    'selector' => 'div.aft-banner-editor-picks',     
));

$wp_customize->selective_refresh->add_partial('featured_news_section_title', array(
    'selector' => 'div.af-main-banner-featured-posts',     
));

$wp_customize->selective_refresh->add_partial('archive_layout', array(
    'selector' => 'div#aft-archive-wrapper',     
));

$wp_customize->selective_refresh->add_partial('frontpage_latest_posts_section_title', array(
    'selector' => 'div.af-main-banner-latest-posts div.widget-title-section',     
));

$wp_customize->selective_refresh->add_partial('footer_background_image', array(
    'selector' => 'div.primary-footer div.container-wrapper',     
));

$wp_customize->selective_refresh->add_partial('footer_copyright_text', array(
    'selector' => 'div.site-info',     
));

//Single Posts

$wp_customize->selective_refresh->add_partial('single_show_featured_image', array(
    'selector' => 'article.af-single-article',     
));

$wp_customize->selective_refresh->add_partial('frontpage_sticky_sidebar_position', array(
    'selector' => '.section-block-upper div#secondary.sidebar-area',     
));

$wp_customize->selective_refresh->add_partial('select_breadcrumb_mode', array(
    'selector' => 'div.af-breadcrumbs',     
));

$wp_customize->selective_refresh->add_partial('global_single_content_mode', array(
    'selector' => 'article.af-single-article div.entry-content',     
));

$wp_customize->selective_refresh->add_partial('single_show_tags_list', array(
    'selector' => 'article.af-single-article div.post-item-metadata span.tags-links',     
));

$wp_customize->selective_refresh->add_partial('single_related_posts_title', array(
    'selector' => 'div.af-reated-posts',     
));
