<?php

namespace acc\auth\access;

/**
 * @package acc\auth\access
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class full_user implements \com\auth\int\access {
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

	    $active_user = $token->get_active();
	    if(!$active_user) return false;

        $role_list = $active_user->get_role_list();

		return (array_key_exists(ACL_CODE_WAREHOUSE, $role_list) && array_key_exists(ACL_CODE_CLIENT, $role_list)) || $token->get_active_role() == ACL_CODE_DEV;
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