<?php
/**
 * Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Base\ACF;

use Geniem\ACF\ConditionalLogicGroup;
use Geniem\ACF\Exception;
use Geniem\ACF\Group;
use Geniem\ACF\RuleGroup;
use Geniem\ACF\Field;
use TMS\Theme\Base\Logger;
use TMS\Theme\Amuri\PostType;

/**
 * Class ManualEventGroup
 *
 * @package TMS\Theme\Base\ACF
 */
class ManualEventGroup {

    /**
     * ManualEventGroup constructor.
     */
    public function __construct() {
        add_action(
            'init',
            \Closure::fromCallable( [ $this, 'register_fields' ] )
        );
    }

    /**
     * Register fields
     */
    protected function register_fields() : void {
        try {
            $group_title = _x( 'Tiedot', 'theme ACF', 'tms-theme-amuri' );

            $field_group = ( new Group( $group_title ) )
                ->set_key( 'fg_manual_event_fields' );

            $rule_group = ( new RuleGroup() )
                ->add_rule( 'post_type', '==', PostType\ManualEvent::SLUG );

            $field_group
                ->add_rule_group( $rule_group )
                ->set_position( 'normal' )
                ->set_hidden_elements(
                    [
                        'discussion',
                        'comments',
                        'format',
                        'send-trackbacks',
                    ]
                );

            $field_group->add_fields(
                apply_filters(
                    'tms/acf/group/' . $field_group->get_key() . '/fields',
                    [
                        $this->get_event_tab( $field_group->get_key() ),
                    ]
                )
            );

            $field_group = apply_filters(
                'tms/acf/group/' . $field_group->get_key(),
                $field_group
            );

            $field_group->register();
        }
        catch ( Exception $e ) {
            ( new Logger() )->error( $e->getMessage(), $e->getTraceAsString() );
        }
    }

    /**
     * Get event tab
     *
     * @param string $key Field group key.
     *
     * @return Field\Tab
     * @throws Exception In case of invalid option.
     */
    protected function get_event_tab( string $key ) : ?Field\Tab {
        $strings = [
            'tab'                => 'Tapahtuma',
            'short_description'  => [
                'label'        => 'Lyhyt kuvaus',
                'instructions' => '',
            ],
            'description'        => [
                'label'        => 'Kuvaus',
                'instructions' => '',
            ],
            'start_datetime'     => [
                'label'        => 'Aloitusajankohta',
                'instructions' => '',
            ],
            'end_datetime'       => [
                'label'        => 'Päättymisajankohta',
                'instructions' => '',
            ],
            'location'           => [
                'label'       => 'Sijainti',
                'name'        => [
                    'label'        => 'Sijainti',
                    'instructions' => '',
                ],
                'description' => [
                    'label'        => 'Sijainnin kuvaus',
                    'instructions' => '',
                ],
                'extra_info'  => [
                    'label'        => 'Sijainnin lisätiedot',
                    'instructions' => '',
                ],
                'info_url'    => [
                    'label'        => 'Sijainnin lisätietolinkki',
                    'instructions' => '',
                ],
            ],
            'price'              => [
                'label'       => 'Hinta',
                'is_free'     => [
                    'label'        => 'Ilmainen tapahtuma?',
                    'instructions' => '',
                ],
                'price'       => [
                    'label'        => 'Hinta',
                    'instructions' => '',
                ],
                'description' => [
                    'label'        => 'Hinnan kuvaus',
                    'instructions' => '',
                ],
                'info_url'    => [
                    'label'        => 'Hinnan lisätietolinkki',
                    'instructions' => '',
                ],
            ],
            'provider'           => [
                'label' => 'Järjestäjä',
                'name'  => [
                    'label'        => 'Järjestäjä',
                    'instructions' => '',
                ],
                'email' => [
                    'label'        => 'Sähköposti',
                    'instructions' => '',
                ],
                'phone' => [
                    'label'        => 'Puhelin',
                    'instructions' => '',
                ],
                'link'  => [
                    'label'        => 'WWW-osoite',
                    'instructions' => '',
                ],
            ],
            'is_virtual_event'   => [
                'label'        => 'Virtuaalitapahtuma?',
                'instructions' => '',
            ],
            'virtual_event_link' => [
                'label'        => 'Virtuaalitapahtuman linkki',
                'instructions' => '',
            ],
        ];

        try {
            $tab = ( new Field\Tab( $strings['tab'] ) )
                ->set_placement( 'left' );

            $description = ( new Field\Wysiwyg( $strings['description']['label'] ) )
                ->set_key( "{$key}_description" )
                ->set_name( 'description' )
                ->set_instructions( $strings['description']['instructions'] )
                ->disable_media_upload();

            $short_description = ( new Field\Textarea( $strings['short_description']['label'] ) )
                ->set_key( "{$key}_short_description" )
                ->set_name( 'short_description' )
                ->set_instructions( $strings['short_description']['instructions'] );

            $start_datetime = ( new Field\DateTimePicker( $strings['start_datetime']['label'] ) )
                ->set_key( "{$key}_start_datetime" )
                ->set_name( 'start_datetime' )
                ->set_instructions( $strings['start_datetime']['instructions'] )
                ->set_display_format( 'j.n.Y H:i' )
                ->set_return_format( 'Y-m-d H:i:s' );

            $end_datetime = ( new Field\DateTimePicker( $strings['end_datetime']['label'] ) )
                ->set_key( "{$key}_end_datetime" )
                ->set_name( 'end_datetime' )
                ->set_instructions( $strings['end_datetime']['instructions'] )
                ->set_display_format( 'j.n.Y H:i' )
                ->set_return_format( 'Y-m-d H:i:s' );

            $location_name = ( new Field\Text( $strings['location']['name']['label'] ) )
                ->set_key( "{$key}_location_name" )
                ->set_name( 'location_name' )
                ->set_instructions( $strings['location']['name']['instructions'] );

            $location_description = ( new Field\Textarea( $strings['location']['description']['label'] ) )
                ->set_key( "{$key}_location_description" )
                ->set_name( 'location_description' )
                ->set_instructions( $strings['location']['description']['instructions'] );

            $location_extra_info = ( new Field\Textarea( $strings['location']['extra_info']['label'] ) )
                ->set_key( "{$key}_location_extra_info" )
                ->set_name( 'location_extra_info' )
                ->set_instructions( $strings['location']['extra_info']['instructions'] );

            $location_info_url = ( new Field\Link( $strings['location']['info_url']['label'] ) )
                ->set_key( "{$key}_location_info_url" )
                ->set_name( 'location_info_url' )
                ->set_instructions( $strings['location']['info_url']['instructions'] );

            $price_is_free = ( new Field\TrueFalse( $strings['price']['is_free']['label'] ) )
                ->set_key( "{$key}_price_is_free" )
                ->set_name( 'price_is_free' )
                ->set_instructions( $strings['price']['is_free']['instructions'] )
                ->use_ui();

            $price_price = ( new Field\Text( $strings['price']['price']['label'] ) )
                ->set_key( "{$key}_price_price" )
                ->set_name( 'price_price' )
                ->set_instructions( $strings['price']['price']['instructions'] );

            $price_description = ( new Field\Textarea( $strings['price']['description']['label'] ) )
                ->set_key( "{$key}_price_description" )
                ->set_name( 'price_description' )
                ->set_instructions( $strings['price']['description']['instructions'] );

            $price_info_url = ( new Field\Link( $strings['price']['info_url']['label'] ) )
                ->set_key( "{$key}_price_info_url" )
                ->set_name( 'price_info_url' )
                ->set_instructions( $strings['price']['info_url']['instructions'] );

            $provider_name = ( new Field\Text( $strings['provider']['name']['label'] ) )
                ->set_key( "{$key}_provider_name" )
                ->set_name( 'provider_name' )
                ->set_instructions( $strings['provider']['name']['instructions'] );

            $provider_email = ( new Field\Email( $strings['provider']['email']['label'] ) )
                ->set_key( "{$key}_provider_email" )
                ->set_name( 'provider_email' )
                ->set_instructions( $strings['provider']['email']['instructions'] );

            $provider_phone = ( new Field\Text( $strings['provider']['phone']['label'] ) )
                ->set_key( "{$key}_provider_phone" )
                ->set_name( 'provider_phone' )
                ->set_instructions( $strings['provider']['phone']['instructions'] );

            $provider_link = ( new Field\Link( $strings['provider']['link']['label'] ) )
                ->set_key( "{$key}_provider_link" )
                ->set_name( 'provider_link' )
                ->set_instructions( $strings['provider']['link']['instructions'] );

            $is_virtual_event = ( new Field\TrueFalse( $strings['is_virtual_event']['label'] ) )
                ->set_key( "{$key}_is_virtual_event" )
                ->set_name( 'is_virtual_event' )
                ->set_instructions( $strings['is_virtual_event']['instructions'] )
                ->use_ui();

            $virtual_event_link = ( new Field\Link( $strings['virtual_event_link']['label'] ) )
                ->set_key( "{$key}_virtual_event_link" )
                ->set_name( 'virtual_event_link' )
                ->set_instructions( $strings['virtual_event_link']['instructions'] );

            $location_group = ( new Field\Group( $strings['location']['label'] ) )
                ->set_key( "{$key}_location" )
                ->set_name( 'location' )
                ->add_fields( [
                    $location_name,
                    $location_description,
                    $location_extra_info,
                    $location_info_url,
                ] );

            $price_group = ( new Field\Group( $strings['price']['label'] ) )
                ->set_key( "{$key}_price" )
                ->set_name( 'price' )
                ->add_fields( [
                    $price_price,
                    $price_description,
                    $price_info_url,
                ] );

            $provider_group = ( new Field\Group( $strings['provider']['label'] ) )
                ->set_key( "{$key}_provider" )
                ->set_name( 'provider' )
                ->add_fields( [
                    $provider_name,
                    $provider_email,
                    $provider_phone,
                    $provider_link,
                ] );

            $rule_group_has_price        = ( new ConditionalLogicGroup() )
                ->add_rule( $price_is_free->get_key(), '!=', '1' );
            $rule_group_is_virtual_event = ( new ConditionalLogicGroup() )
                ->add_rule( $is_virtual_event->get_key(), '==', '1' );

            $price_group->add_conditional_logic( $rule_group_has_price );
            $virtual_event_link->add_conditional_logic( $rule_group_is_virtual_event );

            $tab->add_fields( [
                $description,
                $short_description,
                $start_datetime,
                $end_datetime,
                $location_group,
                $price_is_free,
                $price_group,
                $provider_group,
                $is_virtual_event,
                $virtual_event_link,
            ] );

            return $tab;
        }
        catch ( \Exception $e ) {
            ( new Logger() )->error( $e->getMessage(), $e->getTrace() );
        }

        return null;
    }
}

( new ManualEventGroup() );
