<?php

namespace TMS\Theme\Amuri\ACF;

use Closure;
use Geniem\ACF\Field;
use Geniem\ACF\Exception;
use TMS\Theme\Base\Logger;

/**
 * AlterSettingsGroup
 */
class AlterSettingsGroup {

    /**
     * Constructor
     */
    public function __construct() {
        add_filter(
            'tms/acf/group/fg_site_settings/fields',
            [ $this, 'remove_theme_color_setting' ],
            100
        );

        add_filter(
            'tms/acf/group/fg_site_settings/fields',
            Closure::fromCallable( [ $this, 'register_theme_tabs' ] ),
            10,
            2
        );
    }

    /**
     * Remove color tab from settings
     *
     * @param Field\Tab[] $fields Array of settings tabs.
     *
     * @return array
     */
    public function remove_theme_color_setting( $fields ) {
        foreach ( $fields as $field ) {
            if ( $field->get_name() === 'Teeman ulkoasu' ) {
                $field->remove_field( 'theme_color' );
            }
        }

        return $fields;
    }

    /**
     * Register fields
     *
     * @param array  $fields Field group fields.
     * @param string $key    Field group key.
     *
     * @return array
     */
    protected function register_theme_tabs( array $fields, string $key ) : array {

        return $fields;
    }
}

( new AlterSettingsGroup() );
