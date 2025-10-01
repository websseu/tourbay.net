<?php
/**
 * Register patterns
 *
 * @package ChromeNews
 */

 

function chromenews_register_patterns_categories(){
    register_block_pattern_category(
        'chromenews',
        array( 'label' => __( 'ChromeNews', 'chromenews' ) )
    );
    
}

add_action( 'init', 'chromenews_register_patterns_categories' );

function chromenews_register_patterns() {

    if ( ! function_exists( 'register_block_pattern' ) ) {
        return;
    }

    $chromenews_patterns = [  
        
        'hybridnews',        
        'hybridnews-section-3',        
        'hybridnews-section-4', 
        'hybridnews-section-1',      
        'hybridnews-section-2',
        'hybridnews-section-5',      
        'hybridnews-section-6',      
        'hybridnews-section-7',      
        'hybridnews-section-8'
                
               
    ];

    foreach ( $chromenews_patterns as $pattern ) {
        register_block_pattern(
            'chromenews/' . $pattern,
            require __DIR__ . '/patterns/' . $pattern . '.php'
        );
    }

    
}

add_action( 'init', 'chromenews_register_patterns' );