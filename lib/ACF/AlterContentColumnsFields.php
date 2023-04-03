<?php

use TMS\Theme\Base\Logger;

/**
 * Alter Grid Fields block
 */
class AlterContentColumnsFields {

    /**
     * Constructor
     */
    public function __construct() {
        add_filter(
            'tms/acf/layout/_content_columns/fields',
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

        $strings = [
            'aspect_ratio' => [
                'instructions' => 'Tekstiosio / kuvaosio. Ensimmäinen luku on tekstiosion koko, toinen kuvaosion.',
            ],
        ];

        try {
            $fields['rows']->sub_fields['layout']->set_wrapper_width( 50 );
            $fields['rows']->sub_fields['aspect_ratio']->set_wrapper_width( 50 );
            $fields['rows']->sub_fields['aspect_ratio']->set_instructions( $strings['aspect_ratio']['instructions'] );
        }
        catch ( Exception $e ) {
            ( new Logger() )->error( $e->getMessage(), $e->getTrace() );
        }
        return $fields;
    }
}

( new AlterContentColumnsFields() );
