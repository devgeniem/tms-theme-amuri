<?php

namespace TMS\Theme\Amuri;

use Closure;
use TMS\Theme\Base\Interfaces\Controller;

/**
 * Class ThemeSupports
 *
 * @package TMS\Theme\Sara_Hilden
 */
class ThemeSupports implements Controller {

    /**
     * Initialize the class' variables and add methods
     * to the correct action hooks.
     *
     * @return void
     */
    public function hooks() : void {
        add_filter(
            'query_vars',
            Closure::fromCallable( [ $this, 'query_vars' ] )
        );

        // Allow custom url links to be added in menus
        \add_filter(
            'tms/theme/remove_custom_links',
            '__return_false'
        );
    }

    /**
     * Append custom query vars
     *
     * @param array $vars Registered query vars.
     *
     * @return array
     */
    protected function query_vars( $vars ) {

        return $vars;
    }
}
