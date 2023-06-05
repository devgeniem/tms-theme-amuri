<?php
/**
 * Copyright (c) 2021. Geniem Oy
 */

namespace TMS\Theme\Amuri\PostType;

use TMS\Theme\Base\Logger;
use \TMS\Theme\Base\Interfaces\PostType;

/**
 * Manual Event CPT
 *
 * @package TMS\Theme\Amuri\PostType
 */
class ManualEvent implements PostType {

    /**
     * This defines the slug of this post type.
     */
    public const SLUG = 'manual-event-cpt';

    /**
     * This defines what is shown in the url. This can
     * be different than the slug which is used to register the post type.
     *
     * @var string
     */
    private $url_slug = 'manual-event';

    /**
     * Define the CPT description
     *
     * @var string
     */
    private $description = '';

    /**
     * This is used to position the post type menu in admin.
     *
     * @var int
     */
    private $menu_order = 41;

    /**
     * This defines the CPT icon.
     *
     * @var string
     */
    private $icon = 'dashicons-heart';

    /**
     * Constructor
     */
    public function __construct() {
        $this->description = _x( 'manual-event', 'theme CPT', 'tms-theme-amuri' );
    }

    /**
     * Add hooks and filters from this controller
     *
     * @return void
     */
    public function hooks() : void {
        add_action( 'init', \Closure::fromCallable( [ $this, 'register' ] ), 15 );
    }

    /**
     * Get post type slug
     *
     * @return string
     */
    public function get_post_type() : string {
        return static::SLUG;
    }

    /**
     * This registers the post type.
     *
     * @return void
     */
    private function register() {
        $labels = [
            'name'                  => 'Manuaaliset tapahtumat',
            'singular_name'         => 'Manuaalinen tapahtuma',
            'menu_name'             => 'Manuaaliset tapahtumat',
            'name_admin_bar'        => 'Manuaaliset tapahtumat',
            'archives'              => 'Arkistot',
            'attributes'            => 'Ominaisuudet',
            'parent_item_colon'     => 'Vanhempi:',
            'all_items'             => 'Kaikki',
            'add_new_item'          => 'Lisää uusi',
            'add_new'               => 'Lisää uusi',
            'new_item'              => 'Uusi',
            'edit_item'             => 'Muokkaa',
            'update_item'           => 'Päivitä',
            'view_item'             => 'Näytä',
            'view_items'            => 'Näytä kaikki',
            'search_items'          => 'Etsi',
            'not_found'             => 'Ei löytynyt',
            'not_found_in_trash'    => 'Ei löytynyt roskakorista',
            'featured_image'        => 'Kuva',
            'set_featured_image'    => 'Aseta kuva',
            'remove_featured_image' => 'Poista kuva',
            'use_featured_image'    => 'Käytä kuvana',
            'insert_into_item'      => 'Aseta julkaisuun',
            'uploaded_to_this_item' => 'Lisätty tähän julkaisuun',
            'items_list'            => 'Listaus',
            'items_list_navigation' => 'Listauksen navigaatio',
            'filter_items_list'     => 'Suodata listaa',
        ];

        $rewrite = [
            'slug'       => $this->url_slug,
            'with_front' => true,
            'pages'      => true,
            'feeds'      => true,
        ];

        $args = [
            'label'               => $labels['name'],
            'description'         => '',
            'labels'              => $labels,
            'supports'            => [ 'title', 'thumbnail', 'revisions' ],
            'hierarchical'        => false,
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'menu_position'       => $this->menu_order,
            'menu_icon'           => $this->icon,
            'show_in_admin_bar'   => true,
            'show_in_nav_menus'   => true,
            'can_export'          => true,
            'has_archive'         => false,
            'exclude_from_search' => false,
            'publicly_queryable'  => true,
            'rewrite'             => $rewrite,
            'capability_type'     => 'manual_event',
            'map_meta_cap'        => true,
            'show_in_rest'        => true,
        ];

        register_post_type( static::SLUG, $args );
    }

    /**
     * Normalize event data with data from LinkedEvents.
     *
     * @param object $event The event object.
     *
     * @return array
     */
    public static function normalize_event( $event ) {

        // Format location data.
        $location = [
            'name'        => $event->location['location_name'] ?? null,
            'description' => $event->location['location_description'] ?? null,
            'extra_info'  => $event->location['location_extra_info'] ?? null,
            'info_url'    => [
                'title' => $event->location['location_info_url']['title'] ?? null,
                'url'   => $event->location['location_info_url']['url'] ?? null,
            ],
        ];

        // Format price data.
        $price = [
            [
                'price'       => $event->price_is_free
                    ? __( 'Free', 'tms-theme-base' )
                    : $event->price['price_price'] ?? null,
                'description' => $event->price['price_description'] ?? null,
                'info_url'    => [
                    'title' => $event->price['price_info_url']['title'] ?? null,
                    'url'   => $event->price['price_info_url']['url'] ?? null,
                ],
            ],
        ];

        // Format provider data.
        $provider = [
            'name'  => $event->provider['provider_name'] ?? null,
            'email' => $event->provider['provider_email'] ?? null,
            'phone' => $event->provider['provider_phone'] ?? null,
            'link'  => [
                'title' => $event->provider['provider_link']['title'] ?? null,
                'url'   => $event->provider['provider_link']['url'] ?? null,
            ],
        ];

        return [
            'name'               => $event->title ?? null,
            'short_description'  => $event->short_description ?? null,
            'description'        => nl2br( $event->description ) ?? null,
            'date_title'         => __( 'Dates', 'tms-theme-base' ),
            'date'               => static::get_event_date( $event ),
            'time_title'         => __( 'Time', 'tms-theme-base' ),
            'time'               => static::get_event_time( $event ),
            // Include raw dates for possible sorting.
            'start_date_raw'     => static::get_as_datetime( $event->start_datetime ),
            'end_date_raw'       => static::get_as_datetime( $event->end_datetime ),
            'location_title'     => __( 'Location', 'tms-theme-base' ),
            'location'           => $location,
            'price_title'        => __( 'Price', 'tms-theme-base' ),
            'price'              => $price,
            'provider_title'     => __( 'Organizer', 'tms-theme-base' ),
            'provider'           => $provider,
            'image'              => $event->image ?? null,
            'url'                => $event->url ?? null,
            'is_virtual_event'   => $event->is_virtualevent ?? false,
            'virtual_event_link' => $event->virtual_event_link ?? null,
        ];
    }

    /**
     * Get event date
     *
     * @param object $event Event object.
     *
     * @return string|null
     */
    protected static function get_event_date( $event ) {
        if ( empty( $event->start_datetime ) ) {
            return null;
        }

        $start_time  = static::get_as_datetime( $event->start_datetime );
        $end_time    = static::get_as_datetime( $event->end_datetime );
        $date_format = get_option( 'date_format' );

        if ( $start_time && $end_time && $start_time->diff( $end_time )->days >= 1 ) {
            return sprintf(
                '%s - %s',
                $start_time->format( $date_format ),
                $end_time->format( $date_format )
            );
        }

        return $start_time->format( $date_format );
    }

    /**
     * Get event time
     *
     * @param object $event Event object.
     *
     * @return string|null
     */
    protected static function get_event_time( $event ) {
        if ( empty( $event->start_datetime ) ) {
            return null;
        }

        $start_time  = static::get_as_datetime( $event->start_datetime );
        $end_time    = static::get_as_datetime( $event->end_datetime );
        $time_format = 'H.i';

        if ( $start_time && $end_time ) {
            return sprintf(
                '%s - %s',
                $start_time->format( $time_format ),
                $end_time->format( $time_format )
            );
        }

        return $start_time->format( $time_format );
    }

    /**
     * Get string as date time.
     *
     * @param string $value Date time string.
     *
     * @return \DateTime|null
     */
    protected static function get_as_datetime( $value ) {
        try {
            // Manual event dates are set in Helsinki timezone, so let's enforce that for sorting purposes.
            $dt = new \DateTime( $value, new \DateTimeZone( 'Europe/Helsinki' ) );

            return $dt;
        }
        catch ( \Exception $e ) {
            ( new Logger() )->error( $e->getMessage(), $e->getTrace() );
        }

        return null;
    }
}
