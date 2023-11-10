<?php
namespace app\install\acl_role;

/**
 * @package app\install\acl_role
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class agent_office_manager extends \app\install\acl_role\master\main{
	//--------------------------------------------------------------------------------
	public function get_acl_code() {
		return "AGENT_OFFICE_MANAGER";
	}
	//--------------------------------------------------------------------------------
	public function get_acl_is_locked() {
		return 0;
	}
	//--------------------------------------------------------------------------------
	public function get_acl_level() {
		return 48.50000;
	}
	//--------------------------------------------------------------------------------
	public function get_acl_name() {
		return "Agent office manager";
	}
	//--------------------------------------------------------------------------------
}
