<?php

namespace LiquidedgeApp\Octoapp\app\db;

/**
 * Database Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */

class acl_role extends \com\core\db\acl_role {

	use \LiquidedgeApp\Octoapp\app\app\db\tra\table;

    //--------------------------------------------------------------------------------
    // properties
    //--------------------------------------------------------------------------------

 	//--------------------------------------------------------------------------------
	// functions
 	//--------------------------------------------------------------------------------
	/**
	 * @param \com\db\table $obj
	 * @param db_person $user
	 * @param string $role
	 * @return \com\auth\access|void
	 */
    public function on_auth(&$obj, $user, $role) {
        return \core::$app->get_token()->check('dev');
    }
    //--------------------------------------------------------------------------------

	/**
	 * @param \com\db\table $obj
	 * @param db_person $user
	 * @param string $role
	 * @return \com\auth\access|void
	 */
    public function on_auth_use(&$obj, $user, $role) {
        return \core::$app->get_token()->check('dev');
    }
 	//--------------------------------------------------------------------------------
}