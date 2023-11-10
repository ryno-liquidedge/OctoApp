<?php

namespace acc\auth\access;

/**
 * Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class system_users implements \com\auth\int\access {
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
            ACL_CODE_ADMIN_SUPPORT,
            ACL_CODE_SUPERADMIN,
            ACL_CODE_CLIENT,
            ACL_CODE_AGENT_OFFICE_MANAGER,
            ACL_CODE_AGENT,
            ACL_CODE_AGENT_OWNER,
            ACL_CODE_AGENT_SUPER_ADMIN,
            ACL_CODE_AGENT_SENIOR,
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