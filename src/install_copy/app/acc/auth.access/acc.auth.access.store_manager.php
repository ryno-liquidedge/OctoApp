<?php

namespace acc\auth\access;

/**
 * @package acc\auth\access
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class store_manager implements \com\auth\int\access {
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
		return in_array($token->get_active_role(), [ACL_CODE_STORE_MANAGER, ACL_CODE_DEV]);
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