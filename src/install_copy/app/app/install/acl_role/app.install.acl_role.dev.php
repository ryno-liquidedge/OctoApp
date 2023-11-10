<?php
namespace app\install\acl_role;

/**
 * @package app\install\acl_role
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class dev extends \app\install\acl_role\master\main{
	//--------------------------------------------------------------------------------
	public function get_acl_code() {
		return "DEV";
	}
	//--------------------------------------------------------------------------------
	public function get_acl_is_locked() {
		return 1;
	}
	//--------------------------------------------------------------------------------
	public function get_acl_level() {
		return 0.00000;
	}
	//--------------------------------------------------------------------------------
	public function get_acl_name() {
		return "Developer";
	}
	//--------------------------------------------------------------------------------
}
