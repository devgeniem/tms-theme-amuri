<?php

use TMS\Theme\Base\Logger;

/**
 * Alter Grid Fields block
 */
class AlterKeyFiguresFields {

    /**
     * Constructor
     */
    public function __construct() {
        add_filter(
            'tms/block/key_figures/fields',
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
            $fields['rows']->sub_fields['numbers']->sub_fields['background_color']
            ->set_choices( [
                'primary-invert' => 'Kahvinruskea',
                'primary'        => 'Kermankeltainen',
            ] );
        }
        catch ( Exception $e ) {
            ( new Logger() )->error( $e->getMessage(), $e->getTrace() );
        }
        return $fields;
    }
}

( new AlterKeyFiguresFields() );
