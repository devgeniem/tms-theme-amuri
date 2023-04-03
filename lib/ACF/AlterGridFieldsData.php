<?php
/**
 *  Copyright (c) 2021. Geniem Oy
 */

use TMS\Theme\Base\Logger;

/**
 * Alter Grid Fields block, layout
 */
class AlterGridFields {

    /**
     * Constructor
     */
    public function __construct() {
        add_filter(
            'tms/block/grid/fields',
            [ $this, 'alter_fields' ],
            10,
            2
        );
        add_filter(
            'tms/acf/layout/_grid/fields',
            [ $this, 'alter_fields' ],
            10,
            2
        );
        add_filter(
            'tms/acf/block/grid/data',
            [ $this, 'alter_format' ],
            20
        );
        add_filter(
            'tms/acf/layout/grid/data',
            [ $this, 'alter_format' ],
            20
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
            $fields['repeater']->sub_fields['grid_item_custom']->sub_fields['description']->set_maxlength( 300 );
            $fields['repeater']->sub_fields['grid_item_custom']->sub_fields['description']->set_new_lines( 'br' );

        }
        catch ( Exception $e ) {
            ( new Logger() )->error( $e->getMessage(), $e->getTrace() );
        }
        return $fields;
    }

    /**
     * Format layout data. Replace BG colors.
     *
     * @param array $layout ACF Layout data.
     *
     * @return array
     */
    public function alter_format( array $layout ) : array {
        foreach ( $layout['repeater'] as $key => $item ) {
            $layout['repeater'][ $key ]['classes'] = str_replace( [ 'has-colors-primary'], [ 'has-colors-secondary' ], $layout['repeater'][ $key ]['classes'] ); // phpcs:ignore
            $layout['repeater'][ $key ]['button']  = 'is-primary has-text-weight-semibold';
        }
        return $layout;
    }
}

( new AlterGridFields() );
