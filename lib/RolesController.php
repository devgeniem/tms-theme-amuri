<?php
/**
 *  Copyright (c) 2022. Geniem Oy
 */

namespace TMS\Theme\Amuri;

use Geniem\Role;
use TMS\Theme\Base\Interfaces\Controller;

/**
 * RolesController
 */
class RolesController implements Controller {

    /**
     * Hooks
     */
    public function hooks() : void {
        add_filter( 'tms/roles/super_administrator', [ $this, 'modify_super_administrator_caps' ] );
        add_filter( 'tms/roles/admin', [ $this, 'modify_admin_caps' ] );
        add_filter( 'tms/roles/editor', [ $this, 'modify_editor_caps' ] );
        add_filter( 'tms/roles/author', [ $this, 'modify_author_caps' ] );
        add_filter( 'tms/roles/contributor', [ $this, 'modify_contributor_caps' ] );
    }

    /**
     * Modify Super Administrator caps
     *
     * @param Role $role Instance of \Geniem\Role.
     */
    public function modify_super_administrator_caps( Role $role ) {

        return $role;
    }

    /**
     * Modify Administrator caps
     *
     * @param Role $role Instance of \Geniem\Role.
     */
    public function modify_admin_caps( Role $role ) {

        return $role;
    }

    /**
     * Modify Editor caps
     *
     * @param Role $role Instance of \Geniem\Role.
     */
    public function modify_editor_caps( Role $role ) {

        return $role;
    }

    /**
     * Modify Author caps
     *
     * @param Role $role Instance of \Geniem\Role.
     */
    public function modify_author_caps( Role $role ) {

        return $role;
    }

    /**
     * Modify Contributor caps
     *
     * @param Role $role Instance of \Geniem\Role.
     */
    public function modify_contributor_caps( Role $role ) {

        return $role;
    }
}
