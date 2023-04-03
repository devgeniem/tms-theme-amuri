<?php

namespace TMS\Theme\Amuri;

/**
 * Autoloader for theme library classes.
 *
 * @link https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader-examples.md
 * @param string $class The fully-qualified class name.
 * @return void
 */
function theme_library_loader( $class = '' ) {

    // Theme namespace prefix.
    $prefix = 'TMS\\Theme\\Amuri\\';

    // Does the class use the namespace prefix?
    $len = strlen( $prefix );
    if ( strncmp( $prefix, $class, $len ) !== 0 ) {
        // No, move to the next registered autoloader.
        return;
    }

    // Get the relative class name.
    $relative_class = substr( $class, $len );

    // Replace the namespace prefix with the base directory, replace namespace
    // separators with directory separators in the relative class name, append
    // with .php.
    $child_theme_file = __DIR__ . '/' . str_replace( '\\', '/', $relative_class ) . '.php';

    // If the file exists, require it.
    if ( file_exists( $child_theme_file ) ) {
        require_once $child_theme_file;
    }
}

// Register the theme autoloader.
spl_autoload_register( __NAMESPACE__ . '\\theme_library_loader' );

