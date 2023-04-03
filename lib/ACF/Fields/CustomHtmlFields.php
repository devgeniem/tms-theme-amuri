<?php
/**
 * Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Amuri\ACF\Fields;

use Geniem\ACF\Field;
use TMS\Theme\Base\Logger;
use TMS\Theme\Amuri\PostType;

/**
 * Class CustomHtmlFields
 *
 * @package TMS\Theme\Amuri\ACF\Fields
 */
class CustomHtmlFields extends \Geniem\ACF\Field\Group {

    /**
     * The constructor for field.
     *
     * @param string $label Label.
     * @param null   $key   Key.
     * @param null   $name  Name.
     */
    public function __construct( $label = '', $key = null, $name = null ) {
        parent::__construct( $label, $key, $name );

        try {
            $this->add_fields( $this->sub_fields() );
        }
        catch ( \Exception $e ) {
            ( new Logger() )->error( $e->getMessage(), $e->getTrace() );
        }
    }

    /**
     * This returns all sub fields of the parent groupable.
     *
     * @return array
     * @throws \Geniem\ACF\Exception In case of invalid ACF option.
     */
    protected function sub_fields() : array {
        $strings = [
            'custom_html' => [
                'label'        => 'Custom HTML',
                'instructions' => '',
            ],
        ];

        $key = $this->get_key();

        $html_field = ( new Field\Wysiwyg( $strings['custom_html']['label'] ) )
            ->set_key( "${key}_custom_html" )
            ->set_name( 'custom_html' )
            ->set_required()
            ->set_instructions( $strings['custom_html']['instructions'] );

        return [
            $html_field,
        ];
    }
}
