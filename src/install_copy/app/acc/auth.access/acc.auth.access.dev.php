<?php

namespace acc\auth\access;

/**
 * Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class dev implements \com\auth\int\access {
	//--------------------------------------------------------------------------------
	use \com\auth\tra\access;
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	/**
	 * @param \com\auth\token $token
	 */
	public function check($token) {
		return ($token->get_active_role() == "DEV");
	}
	//--------------------------------------------------------------------------------
	// static
	//--------------------------------------------------------------------------------
	/**
	 * @param array $options[] <p>No options available.</p>
	 * @return \acc\auth\access\dev
	 */
	public static function make($options = []) {
		return new \acc\auth\access\dev($options);
	}
	//--------------------------------------------------------------------------------
}