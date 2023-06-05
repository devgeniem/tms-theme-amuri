<?php

use DustPress\Query;
use TMS\Theme\Amuri\PostType;
use TMS\Theme\Amuri\PostType\ManualEvent;
use TMS\Theme\Base\Logger;
use TMS\Theme\Base\Formatters\EventsFormatter;

/**
 * Copyright (c) 2023. Geniem Oy
 * Template Name: Tapahtumalistaus (yhdistetty)
 */

/**
 * The PageCombinedEventsList class.
 */
class PageCombinedEventsList extends PageEventsSearch {
    /**
     * Template
     */
    const TEMPLATE = 'models/page-combined-events-list.php';

    /**
     * Return form fields.
     *
     * @return array
     */
    public function form() {
        return [];
    }

    /**
     * Description text
     */
    public function description() : ?string {
        return get_field( 'description' );
    }

    /**
     * Get no results text
     *
     * @return string
     */
    public function no_results() : string {
        return __( 'No results', 'tms-theme-base' );
    }

    /**
     * Get events
     */
    public function events() : ?array {
        try {
            $response = $this->get_events();

            return $response['events'] ?? [];
        }
        catch ( Exception $e ) {
            ( new Logger() )->error( $e->getMessage(), $e->getTrace() );
        }

        return null;
    }

    /**
     * Get events.
     *
     * @return array
     */
    protected function get_events() : array {
        $params = [
            'keyword'     => get_field( 'keyword' ),
            'page_size'   => 200, // Use an arbitrary limit as a sanity check.
            'show_images' => get_field( 'show_images' ),
            'start'       => 'today',
            'end'         => '',
            'location'    => '',
            'publisher'   => '',
            'sort'        => '',
            'text'        => '',
            'page'        => 1,
        ];

        $formatter         = new EventsFormatter();
        $params            = $formatter->format_query_params( $params );
        $params['include'] = 'organization,location,keywords';

        $cache_group = 'page-combined-events-list';
        $cache_key   = md5( wp_json_encode( $params ) );
        $response    = wp_cache_get( $cache_key, $cache_group );

        if ( empty( $response ) ) {
            $response           = $this->do_get_events( $params );
            $response['events'] = array_merge( $response['events'], $this->get_manual_events() );

            // Sort events by start datetime objects.
            usort( $response['events'], function( $a, $b ) {
                return $a['start_date_raw'] <=> $b['start_date_raw'];
            } );

            if ( ! empty( $response ) ) {
                wp_cache_set(
                    $cache_key,
                    $response,
                    $cache_group,
                    MINUTE_IN_SECONDS * 15
                );
            }
        }

        return $response;
    }

    /**
     * Get manual events.
     *
     * @return array
     */
    protected function get_manual_events() : array {
        $args = [
            'post_type'      => PostType\ManualEvent::SLUG,
            'posts_per_page' => 200,
            'fields'         => 'ids',
            'meta_query'     => [
                [
                    'key'     => 'start_datetime',
                    'value'   => date( 'Y-m-d' ),
                    'compare' => '>=',
                ],
            ],
        ];

        $query = new WP_Query( $args );

        $events = array_map( function ( $id ) {
            $event        = (object) get_fields( $id );
            $event->title = get_the_title( $id );
            $event->url   = get_permalink( $id );
            $event->image = has_post_thumbnail( $id ) ? get_the_post_thumbnail_url( $id, 'medium_large' ) : null;

            return ManualEvent::normalize_event( $event );
        }, $query->posts );

        return $events;
    }
}
