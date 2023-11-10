<?php
namespace LiquidedgeApp\Octoapp\app\app\install\person_type;

/**
 * @package app\install\person_type
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class individual extends \LiquidedgeApp\Octoapp\app\app\install\person_type\master\main{
	//--------------------------------------------------------------------------------
	public function get_pty_code() {
		return "INDIVIDUAL";
	}
	//--------------------------------------------------------------------------------
	public function get_pty_is_individual() {
		return 1;
	}
	//--------------------------------------------------------------------------------
	public function get_pty_class() {
		return "individual";
	}
	//--------------------------------------------------------------------------------
	public function get_pty_name() {
		return "Individual";
	}
	//--------------------------------------------------------------------------------
}
