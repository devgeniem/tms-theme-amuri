<?php
/**
 *  Copyright (c) 2021. Geniem Oy
 */

use TMS\Theme\Base\Logger;

/**
 * Alter Image Banner Fields
 */
class AlterImageBannerFields {

    /**
     * Constructor
     */
    public function __construct() {
        add_filter(
            'tms/acf/layout/_image_banner/fields',
            [ $this, 'alter_fields' ],
            10,
            2
        );
    }

    /**
     * Alter fields
     *
     * @param array $fields Array of ACF fields.
     *
     * @return array
     */
    public function alter_fields( array $fields ) : array {
        try {
            $fields['align']
            ->set_choices( [
                'has-text-left'     => 'Vasen',
                'has-text-right'    => 'Oikea',
                'has-text-centered' => 'Keskitetty',
            ] );
        }
        catch ( Exception $e ) {
            ( new Logger() )->error( $e->getMessage(), $e->getTrace() );
        }
        return $fields;
    }
}

( new AlterImageBannerFields() );
