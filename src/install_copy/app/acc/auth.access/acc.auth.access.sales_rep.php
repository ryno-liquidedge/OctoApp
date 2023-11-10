<?php

namespace acc\auth\access;

/**
 * Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class sales_rep implements \com\auth\int\access {
	//--------------------------------------------------------------------------------
	use \com\auth\tra\access;
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	/**
	 * @param \com\auth\token $token
	 * @return bool
	 */
	public function check($token) {
		return in_array($token->get_active_role(), [
		    ACL_CODE_DEV,
            ACL_CODE_ADMIN,
            ACL_CODE_SUPERADMIN,
            ACL_CODE_SALES_REP,
        ]);
	}
	//--------------------------------------------------------------------------------
	// static
	//--------------------------------------------------------------------------------
	/**
	 * @param array $options
	 * @return static
	 */
	public static function make($options = []) {
		return new static($options);
	}
	//--------------------------------------------------------------------------------
}