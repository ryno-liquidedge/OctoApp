<?php
namespace app\install\person_type;
/**
 * @package app\install\person_type
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class non_resident_individual extends \app\install\person_type\master\main{
	//--------------------------------------------------------------------------------
	public function get_pty_code() {
		return "NON_RESIDENT_INDIVIDUAL";
	}
	//--------------------------------------------------------------------------------
	public function get_pty_is_individual() {
		return 1;
	}
	//--------------------------------------------------------------------------------
	public function get_pty_class() {
		return "non_resident_individual";
	}
	//--------------------------------------------------------------------------------
	public function get_pty_name() {
		return "Non-resident individual";
	}
	//--------------------------------------------------------------------------------
}
