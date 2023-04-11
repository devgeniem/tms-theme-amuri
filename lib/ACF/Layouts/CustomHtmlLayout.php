<?php
/**
 * Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Amuri\ACF\Layouts;

use Geniem\ACF\Exception;
use Geniem\ACF\Field\Flexible\Layout;
use Geniem\ACF\Field;
use TMS\Theme\Base\Logger;

/**
 * Class CustomHtmlLayout
 *
 * @package TMS\Theme\Amuri\ACF\Layouts
 */
class CustomHtmlLayout extends Layout {

    /**
     * Layout key
     */
    const KEY = '_html';

    /**
     * Create the layout
     *
     * @param string $key Key from the flexible content.
     */
    public function __construct( string $key ) {
        parent::__construct(
            'Custom HTML',
            $key . self::KEY,
            'html'
        );

        $this->add_layout_fields();
    }

    /**
     * Add layout fields
     *
     * @return void
     */
    private function add_layout_fields() : void {
        $key = $this->get_key();

        $strings = [
            'html' => [
                'label'        => 'HTML Koodi',
                'instructions' => '',
            ],
        ];

        if ( user_can( get_current_user_id(), 'unfiltered_html' ) ) {
            $html_field = ( new Field\Textarea( $strings['html']['label'] ) )
                ->set_key( "{$key}_html" )
                ->set_name( 'html' )
                ->set_instructions( $strings['html']['instructions'] );

            try {
                $this->add_fields(
                    apply_filters(
                        'tms/acf/layout/' . $this->get_key() . '/fields',
                        [ $html_field ]
                    )
                );
            }
            catch ( Exception $e ) {
                ( new Logger() )->error( $e->getMessage(), $e->getTrace() );
            }
        }
    }
}
