<?php

namespace acc\auth\access;

/**
 * Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class superadmin implements \com\auth\int\access {
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
            ACL_CODE_SUPERADMIN,
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