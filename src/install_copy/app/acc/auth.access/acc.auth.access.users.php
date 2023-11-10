<?php

namespace acc\auth\access;

/**
 * Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class users implements \com\auth\int\access {
	//--------------------------------------------------------------------------------
	use \com\auth\tra\access;
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	/**
	 * @param \com\auth\token $token
	 */
	public function check($token) {
		return in_array($token->get_active_role(), ACL_AUTH_USERS);
	}
	//--------------------------------------------------------------------------------
	// static
	//--------------------------------------------------------------------------------
	/**
	 * @param array $options[] <p>No options available.</p>
	 * @return \acc\auth\access\users
	 */
	public static function make($options = []) {
		return new \acc\auth\access\users($options);
	}
	//--------------------------------------------------------------------------------
}