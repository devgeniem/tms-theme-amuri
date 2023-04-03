<?php

// Require the child theme autoloader.
require_once __DIR__ . '/lib/autoload.php';

// Require the main theme autoloader.
require_once get_template_directory() . '/lib/autoload.php';

/**
 * Add core/html block to use
 */
add_filter( 'tms/gutenberg/blocks', function ( $allowed_blocks ) {
    $allowed_blocks['core/html'] = [];

    return $allowed_blocks;
} );

// Child theme setup
( new \TMS\Theme\Amuri\ThemeController() );

