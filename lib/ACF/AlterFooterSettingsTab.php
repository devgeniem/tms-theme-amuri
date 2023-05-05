<?php

use Geniem\ACF\Field;

/**
 * Alter Grid Fields block
 */
class AlterFooterSettingsTab {

    /**
     * Constructor
     */
    public function __construct() {
        \add_filter(
            'tms/acf/group/fg_site_settings/fields',
            \Closure::fromCallable( [ $this, 'register_fields' ] ),
            10,
            2
        );
    }

    /**
     * Add fields to footer-tab in site settings
     *
     * @param array  $fields Array of ACF fields.
     * @param string $key    Layout key.
     *
     * @return array
     */
    public function register_fields( array $fields, string $key ) : array {

        $strings = [
            'second_contact_title' => [
                'title'        => 'Toisen yhteystiedon otsikko',
                'instructions' => 'Toinen yhteystieto-osio näytetään alatunnisteen vasemmassa reunassa, 
                ensimmäisten yhteystieto-kenttien vieressä',
            ],
            'second_address' => [
                'title'        => 'Toisen yhteystiedon osoite',
                'instructions' => '',
            ],
            'second_email' => [
                'title'        => 'Toisen yhteystiedon sähköposti',
                'instructions' => '',
            ],
            'second_phone' => [
                'title'        => 'Toisen yhteystiedon puhelinnumero',
                'instructions' => '',
            ],
        ];

        foreach ( $fields as $field ) {
            if ( $field->get_name() === 'Alatunniste' ) {
                $a = ( new Field\Text( $strings['second_contact_title']['title'] ) )
                    ->set_key( "{$key}_second_contact_title" )
                    ->set_name( 'second_contact_title' )
                    ->set_wrapper_width( 50 )
                    ->set_instructions( $strings['second_contact_title']['instructions'] );

                $s = ( new Field\Textarea( $strings['second_address']['title'] ) )
                    ->set_key( "{$key}_second_address" )
                    ->set_name( 'second_address' )
                    ->set_new_lines( 'wpautop' )
                    ->set_wrapper_width( 50 )
                    ->set_instructions( $strings['second_address']['instructions'] );

                $d = ( new Field\Email( $strings['second_email']['title'] ) )
                    ->set_key( "{$key}_second_email" )
                    ->set_name( 'second_email' )
                    ->set_wrapper_width( 50 )
                    ->set_instructions( $strings['second_email']['instructions'] );

                $f = ( new Field\Text( $strings['second_phone']['title'] ) )
                    ->set_key( "{$key}_second_phone" )
                    ->set_name( 'second_phone' )
                    ->set_wrapper_width( 50 )
                    ->set_instructions( $strings['second_phone']['instructions'] );

                $field->add_fields( [ $a, $s, $d, $f ] );
            }
        }

        return $fields;
    }
}

( new AlterFooterSettingsTab() );
