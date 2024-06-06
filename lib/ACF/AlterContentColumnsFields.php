<?php

use TMS\Theme\Base\Logger;
use Geniem\ACF\Field;

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
     * @param array  $fields Array of ACF fields.
     * @param string $key    Layout key.
     *
     * @return array
     */
    public function alter_fields( array $fields, string $key ) : array {

        $strings = [
            'aspect_ratio' => [
                'instructions' => 'Tekstiosio / kuvaosio. Ensimmäinen luku on tekstiosion koko, toinen kuvaosion.',
            ],
            'description' => [
                'label'        => 'Teksti',
                'instructions' => '',
            ],
            'link' => [
                'label'        => 'Linkkipainike',
                'instructions' => '',
            ],
        ];

        try {
            $fields['rows']->sub_fields['layout']->set_wrapper_width( 50 );
            $fields['rows']->sub_fields['aspect_ratio']->set_wrapper_width( 50 );
            $fields['rows']->sub_fields['aspect_ratio']->set_instructions( $strings['aspect_ratio']['instructions'] );

            // Remove original description field
            $fields['rows']->remove_field( 'description' );

            // Add wysiwyg field to replace description field
            $wysiwyg_field = ( new Field\Wysiwyg( $strings['description']['label'] ) )
                ->set_key( "{$key}_description" )
                ->set_name( 'description' )
                ->set_instructions( $strings['description']['instructions'] )
                ->set_wrapper_width( 55 )
                ->set_tabs( 'visual' )
                ->set_toolbar( [ 'bold', 'link' ] )
                ->redipress_include_search()
                ->disable_media_upload();

            // Add link field
            $link_field = ( new Field\Link( $strings['link']['label'] ) )
                ->set_key( "{$key}_link" )
                ->set_name( 'link' )
                ->set_wrapper_width( 67 )
                ->set_instructions( $strings['link']['instructions'] );

            $fields['rows']->add_field_after( $wysiwyg_field, 'image' );
            $fields['rows']->add_field( $link_field );

        }
        catch ( Exception $e ) {
            ( new Logger() )->error( $e->getMessage(), $e->getTrace() );
        }
        return $fields;
    }
}

( new AlterContentColumnsFields() );
