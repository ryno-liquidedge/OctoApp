<?php

namespace acc\auth\access;

/**
 * Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class admins implements \com\auth\int\access {
	//--------------------------------------------------------------------------------
	use \com\auth\tra\access;
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	/**
	 * @param \com\auth\token $token
	 */
	public function check($token) {
		return in_array($token->get_active_role(), ["DEV", "ADMIN"]);
	}
	//--------------------------------------------------------------------------------
	// static
	//--------------------------------------------------------------------------------
	/**
	 * @param array $options[] <p>No options available.</p>
	 * @return \acc\auth\access\admins
	 */
	public static function make($options = []) {
		return new \acc\auth\access\admins($options);
	}
	//--------------------------------------------------------------------------------
}