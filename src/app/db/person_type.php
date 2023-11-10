<?php

namespace LiquidedgeApp\Octoapp\app\db;

/**
 * Database Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class person_type extends \com\core\db\person_type {

	use \LiquidedgeApp\Octoapp\app\app\db\tra\table;

 	//--------------------------------------------------------------------------------
	// functions
 	//--------------------------------------------------------------------------------
    public function on_auth(&$obj, $user, $role) {
        return \core::$app->get_token()->check('users');
    }

    //--------------------------------------------------------------------------------
    public function on_auth_use(&$obj, $user, $role) {
        return \core::$app->get_token()->check('users');
    }
 	//--------------------------------------------------------------------------------
}