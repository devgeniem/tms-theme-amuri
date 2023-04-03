<?php

namespace TMS\Theme\Amuri;

use TMS\Theme\Base\Interfaces;

/**
 * ThemeController
 */
class ThemeController extends \TMS\Theme\Base\ThemeController {

    /**
     * Init classes
     */
    protected function init_classes() : void {
        $classes = [
            Assets::class,
            ACFController::class,
            PostTypeController::class,
            TaxonomyController::class,
            Localization::class,
            FormatterController::class,
            ThemeCustomizationController::class,
            ThemeSupports::class,
            RolesController::class,
        ];

        array_walk( $classes, function ( $class ) {
            $instance = new $class();

            if ( $instance instanceof Interfaces\Controller ) {
                $instance->hooks();
            }
        } );

    }
}
