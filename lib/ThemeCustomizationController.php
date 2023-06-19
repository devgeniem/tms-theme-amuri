<?php

namespace TMS\Theme\Amuri;

use TMS\Theme\Amuri\PostType\ManualEvent;
use WP_post;
use function add_filter;

/**
 * Class ThemeCustomizationController
 *
 * @package TMS\Theme\Base
 */
class ThemeCustomizationController implements \TMS\Theme\Base\Interfaces\Controller {

    /**
     * Add hooks and filters from this controller
     *
     * @return void
     */
    public function hooks() : void {
        add_filter(
            'tms/single/related_display_categories',
            '__return_false',
        );

        add_filter( 'tms/theme/search/search_item', [ $this, 'event_search_classes' ] );
        add_filter( 'tms/theme/nav_parent_link_is_trigger_only', '__return_true' );

        add_filter( 'tms/theme/header/colors', [ $this, 'header' ] );
        add_filter( 'tms/theme/footer/colors', [ $this, 'footer' ] );
        add_filter( 'tms/theme/search/search_item', [ $this, 'search_classes' ] );

        add_filter( 'tms/theme/single_blog/classes', [ $this, 'single_blog_classes' ] );
        add_filter( 'comment_form_submit_button', [ $this, 'comments_submit' ], 15, 0 );
        add_filter( 'comment_reply_link', [ $this, 'reply_link_classes' ], 15, 1 );

        add_filter(
            'tms/acf/block/subpages/data',
            [ $this, 'alter_block_subpages_data' ],
            30
        );

        add_filter( 'tms/theme/error404/search_link', [ $this, 'error404_search_link' ] );
        add_filter( 'tms/acf/block/material/data', function ( $data ) {
            $data['button_classes'] = 'is-primary';

            return $data;
        } );

        add_filter(
            'tms/acf/group/fg_page_components/rules',
            \Closure::fromCallable( [ $this, 'alter_component_rules' ] )
        );

        add_filter( 'tms/theme/gutenberg/excluded_templates', [ $this, 'excluded_templates' ] );

        add_filter(
            'tms/theme/layout_events/events',
            \Closure::fromCallable( [ $this, 'layout_events_events' ] ),
            10,
            2
        );
    }

    /**
     * Header
     *
     * @param array $colors Color classes.
     *
     * @return array Array of customized colors.
     */
    public function header( $colors ) : array {
        $colors['nav']['container']            = 'has-background-primary has-border-primary';
        $colors['search_popup_container']      = 'has-text-primary-invert';
        $colors['lang_nav']['link__default']   = 'has-text-primary';
        $colors['lang_nav']['link__active']    = 'has-background-primary has-text-primary-invert';
        $colors['lang_nav']['dropdown_toggle'] = 'is-outlined is-small';
        $colors['fly_out_nav']['inner']        = 'has-text-primary-invert';
        $colors['search_button']               = 'is-primary-invert';

        return $colors;
    }

    /**
     * Footer
     *
     * @param array $classes Footer classes.
     *
     * @return array
     */
    public function footer( array $classes ) : array {
        $classes['container']   = '';
        $classes['back_to_top'] = 'is-outlined is-primary-invert';
        $classes['link']        = 'has-text-paragraph';
        $classes['link_icon']   = 'is-secondary';

        return $classes;
    }

    /**
     * Search classes.
     *
     * @param array $classes Search view classes.
     *
     * @return array
     */
    public function search_classes( $classes ) : array {
        $classes['event_search_section'] = '';

        return $classes;
    }

    /**
     * Override event item classes.
     *
     * @param array $classes Classes.
     *
     * @return array
     */
    public function single_blog_classes( $classes ) : array {
        $classes['info_section']         = '';
        $classes['info_section_authors'] = '';
        $classes['info_section_button']  = 'is-primary';

        return $classes;
    }

    /**
     * Override comment form submit button.
     *
     * @return string
     */
    public function comments_submit() : string {
        return sprintf(
            '<button name="submit" type="submit" id="submit" class="button button--icon is-primary" >%s %s</button>', // phpcs:ignore
            __( 'Send Comment', 'tms-theme-base' ),
            '<svg class="icon icon--arrow-right icon--large">
                <use xlink:href="#icon-arrow-right"></use>
            </svg>'
        );
    }

    /**
     * Customize reply link.
     *
     * @param string $link The HTML markup for the comment reply link.
     *
     * @return string
     */
    public function reply_link_classes( string $link ) : string {
        return str_replace( 'comment-reply-link', 'comment-reply-link is-small', $link );
    }

    /**
     * Alter subpages classes.
     *
     * @param array $data Block data.
     *
     * @return mixed
     */
    public function alter_block_subpages_data( $data ) {
        if ( empty( $data['subpages'] ) ) {
            return $data;
        }

        $icon_colors_map = [
            'black'     => 'is-secondary-invert',
            'white'     => 'is-primary',
            'primary'   => 'is-black-invert',
            'secondary' => 'is-secondary-invert',
        ];

        $icon_color_key = $data['background_color'] ?? 'black';

        $data['icon_classes'] = $icon_colors_map[ $icon_color_key ];

        return $data;
    }

    /**
     * Override event item classes.
     *
     * @param array $classes Classes.
     *
     * @return array
     */
    public function event_search_classes( $classes ) : array {
        $classes['search_form'] = 'events__search-form';

        return $classes;
    }

    /**
     * Override event search link classes.
     *
     * @param array $link Link details.
     *
     * @return array
     */
    public function error404_search_link( $link ) : array {
        $link['classes'] = '';

        return $link;
    }

    /**
     * Hide components from PageCombinedEventsList template.
     *
     * @param array $rules ACF group rules.
     *
     * @return array
     */
    public function alter_component_rules( array $rules ) : array {
        $rules[] = [
            'param'    => 'page_template',
            'operator' => '!=',
            'value'    => \PageCombinedEventsList::TEMPLATE,
        ];

        return $rules;
    }

    /**
     * Exclude Gutenberg from theme-specific templates.
     *
     * @param array $templates The templates array.
     *
     * @return array
     */
    public function excluded_templates( array $templates ) : array {
        $templates[] = \PageCombinedEventsList::TEMPLATE;

        return $templates;
    }

    /**
     * Filter events for events highlight layout.
     * Add manual events to the list, sort by start date and return correct amount.
     *
     * @param array $events The events.
     * @param array $layout Layout options.
     * @return void
     */
    public function layout_events_events( $events, $layout ) {
        $curdate    = date( 'Y-m-d' );
        $start_date = $layout['starts_today'] ? $curdate : $layout['start'];
        $start_date = $start_date ?: $curdate;
        $end_date   = $layout['end'];
        $count      = $layout['page_size'] ?: 10;
        $args       = [
            'post_type'      => PostType\ManualEvent::SLUG,
            'posts_per_page' => $count,
            'fields'         => 'ids',
            'meta_query'     => [
                'relation' => 'AND',
                [
                    'key'     => 'start_datetime',
                    'value'   => $start_date,
                    'compare' => '>=',
                ],
            ],
        ];

        if ( ! empty( $end_date ) ) {
            $args['meta_query'][] = [
                'key'     => 'end_datetime',
                'value'   => $end_date,
                'compare' => '<=',
            ];
        }

        $query = new \WP_Query( $args );

        // Return original events if no manual events found.
        if ( empty( $query->posts ) ) {
            return $events;
        }

        // Normalize the manual events.
        $manual_events = array_map( function ( $id ) {
            $event        = (object) get_fields( $id );
            $event->id    = $id;
            $event->title = get_the_title( $id );
            $event->url   = get_permalink( $id );
            $event->image = has_post_thumbnail( $id ) ? get_the_post_thumbnail_url( $id, 'medium_large' ) : null;

            return ManualEvent::normalize_event( $event );
        }, $query->posts );

        // Merge manual events with original events.
        $events = array_merge( $events, $manual_events );

        // Sort events by start datetime objects.
        usort( $events, function( $a, $b ) {
            return $a['start_date_raw'] <=> $b['start_date_raw'];
        } );

        // Return correct amount of events.
        return array_slice( $events, 0, $count );
    }
}
