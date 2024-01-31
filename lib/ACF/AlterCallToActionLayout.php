<?php
/**
 *  Copyright (c) 2021. Geniem Oy
 */

use Geniem\ACF\Field;
use Geniem\ACF\ConditionalLogicGroup;
use TMS\Theme\Base\Logger;

/**
 * Alter Call to Action Layout
 */
class AlterCallToActionLayout {

    /**
     * Constructor
     */
    public function __construct() {
        add_filter(
            'tms/acf/layout/_call_to_action/fields',
            [ $this, 'alter_fields' ],
            10,
            2
        );

        add_filter(
            'tms/acf/layout/call_to_action/data',
            [ $this, 'alter_format' ],
            20
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
            'round_image' => [
                'label'        => 'Pyöreä kuva',
                'instructions' => '',
            ],
            'wide_img' => [
                'label'        => 'Leveä kuva',
                'instructions' => 'Kuva täyttää 2/3 elementin leveydestä.',
            ],
        ];

        try {
            $round_image_field = ( new Field\TrueFalse( $strings['round_image']['label'] ) )
                ->set_key( "{$key}_round_image" )
                ->set_name( 'round_image' )
                ->use_ui()
                ->set_wrapper_width( 50 )
                ->set_instructions( $strings['round_image']['instructions'] );

            $aspect_ratio_field = ( new Field\TrueFalse( $strings['wide_img']['label'] ) )
                ->set_key( "{$key}_wide_img" )
                ->set_name( 'wide_img' )
                ->use_ui()
                ->set_wrapper_width( 33 )
                ->set_instructions( $strings['wide_img']['instructions'] );

            $rule_group_automatic = ( new ConditionalLogicGroup() )
            ->add_rule( $round_image_field, '!=', 1 );
            $aspect_ratio_field->add_conditional_logic( $rule_group_automatic );

            $fields['rows']->add_fields( [ $round_image_field, $aspect_ratio_field ] );

        }
        catch ( Exception $e ) {
            ( new Logger() )->error( $e->getMessage(), $e->getTrace() );
        }

        return $fields;
    }

    /**
     * Format layout data
     *
     * @param array $layout ACF Layout data.
     *
     * @return array
     */
    public function alter_format( array $layout ) : array {

        if ( empty( $layout['rows'] ) ) {
            return $layout;
        }

        foreach ( $layout['rows'] as $key => $row ) {
            if ( isset( $row['round_image'] ) && true === $row['round_image'] ) {
                $layout['rows'][ $key ]['image_class']       = 'has-round-mask is-square';
                $layout['rows'][ $key ]['img_column_class']  = 'is-6-desktop image-is-round';
                $layout['rows'][ $key ]['text_column_class'] = 'is-6-desktop';
                continue;
            }

            if ( isset( $row['wide_img'] ) && true === $row['wide_img'] ) {
                $layout['rows'][ $key ]['img_column_class']  = 'is-8-desktop image-is-wide';
                $layout['rows'][ $key ]['text_column_class'] = 'is-4-desktop';
                continue;
            }

            $layout['rows'][ $key ]['img_column_class']  = 'is-4-desktop';
            $layout['rows'][ $key ]['text_column_class'] = 'is-8-desktop';

        }

        return $layout;
    }
}

( new AlterCallToActionLayout() );
