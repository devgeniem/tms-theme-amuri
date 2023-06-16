<?php
/**
 * Copyright (c) 2023. Geniem Oy
 */

namespace TMS\Theme\Amuri\Taxonomy;

use \TMS\Theme\Base\Interfaces\Taxonomy;
use TMS\Theme\Amuri\PostType\ManualEvent;
use TMS\Theme\Base\Traits\Categories;

/**
 * This class defines the taxonomy.
 *
 * @package TMS\Theme\Amuri\Taxonomy
 */
class ManualEventCategory implements Taxonomy {

    use Categories;

    /**
     * This defines the slug of this taxonomy.
     */
    const SLUG = 'manual-event-category';

    /**
     * Add hooks and filters from this controller
     *
     * @return void
     */
    public function hooks() : void {
        add_action( 'init', \Closure::fromCallable( [ $this, 'register' ] ), 15 );
    }

    /**
     * This registers the post type.
     *
     * @return void
     */
    private function register() {
        $labels = [
            'name'                       => 'Tapahtumakategoriat',
            'singular_name'              => 'Tapahtumakategoria',
            'menu_name'                  => 'Tapahtumakategoriat',
            'all_items'                  => 'Kaikki tapahtumakategoriat',
            'new_item_name'              => 'Lisää uusi tapahtumakategoria',
            'add_new_item'               => 'Lisää uusi tapahtumakategoria',
            'edit_item'                  => 'Muokkaa tapahtumakategoriaa',
            'update_item'                => 'Päivitä tapahtumakategoria',
            'view_item'                  => 'Näytä tapahtumakategoria',
            'separate_items_with_commas' => \__( 'Erottele kategoriat pilkulla', 'tms-theme-base' ),
            'add_or_remove_items'        => \__( 'Lisää tai poista kategoria', 'tms-theme-base' ),
            'choose_from_most_used'      => \__( 'Suositut kategoriat', 'tms-theme-base' ),
            'popular_items'              => \__( 'Suositut kategoriat', 'tms-theme-base' ),
            'search_items'               => 'Etsi kategoria',
            'not_found'                  => 'Ei tuloksia',
            'no_terms'                   => 'Ei tuloksia',
            'items_list'                 => 'Tapahtumakategoriat',
            'items_list_navigation'      => 'Tapahtumakategoriat',
        ];

        $args = [
            'labels'            => $labels,
            'hierarchical'      => true,
            'public'            => true,
            'show_ui'           => true,
            'show_admin_column' => true,
            'show_in_nav_menus' => false,
            'show_tagcloud'     => false,
            'show_in_rest'      => true,
        ];

        register_taxonomy( self::SLUG, [ ManualEvent::SLUG ], $args );
    }
}
