<?php
/**
 *  Copyright (c) 2022. Geniem Oy
 */

use TMS\Theme\Base\Logger;

/**
 * Alter Subpages Fields
 */
class AlterSubpagesFields {

    /**
     * Constructor
     */
    public function __construct() {
        add_filter(
            'tms/block/subpages/fields',
            [ $this, 'alter_fields' ],
            10,
            2
        );

        add_filter(
            'tms/acf/layout/_subpages/fields',
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
            $fields['background_color']
                ->set_choices( [
                    'coffee' => 'Kahvinruskea',
                    'creme'  => 'Kermankeltainen',
                ] );
        }
        catch ( Exception $e ) {
            ( new Logger() )->error( $e->getMessage(), $e->getTrace() );
        }
        return $fields;
    }
}

( new AlterSubpagesFields() );
