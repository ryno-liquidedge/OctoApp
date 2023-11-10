<?php
namespace app\install\acl_role;

/**
 * @package app\install\acl_role
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class branch_employee extends \app\install\acl_role\master\main{
	//--------------------------------------------------------------------------------
	public function get_acl_code() {
		return "BRANCH_EMPLOYEE";
	}
	//--------------------------------------------------------------------------------
	public function get_acl_is_locked() {
		return 0;
	}
	//--------------------------------------------------------------------------------
	public function get_acl_level() {
		return 45.00000;
	}
	//--------------------------------------------------------------------------------
	public function get_acl_name() {
		return "Branch employee";
	}
	//--------------------------------------------------------------------------------
}
