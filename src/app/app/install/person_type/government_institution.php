<?php
namespace LiquidedgeApp\Octoapp\app\app\install\person_type;

/**
 * @package app\install\person_type
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class government_institution extends \LiquidedgeApp\Octoapp\app\app\install\person_type\master\main{
	//--------------------------------------------------------------------------------
	public function get_pty_code() {
		return "GOVERNMENT_INSTITUTION";
	}
	//--------------------------------------------------------------------------------
	public function get_pty_is_individual() {
		return 0;
	}
	//--------------------------------------------------------------------------------
	public function get_pty_class() {
		return "government_institution";
	}
	//--------------------------------------------------------------------------------
	public function get_pty_name() {
		return "Government institution";
	}
	//--------------------------------------------------------------------------------
}
