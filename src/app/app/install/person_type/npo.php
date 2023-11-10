<?php
namespace LiquidedgeApp\Octoapp\app\app\install\person_type;

/**
 * @package app\install\person_type
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class npo extends \LiquidedgeApp\Octoapp\app\app\install\person_type\master\main{
	//--------------------------------------------------------------------------------
	public function get_pty_code() {
		return "NPO";
	}
	//--------------------------------------------------------------------------------
	public function get_pty_is_individual() {
		return 0;
	}
	//--------------------------------------------------------------------------------
	public function get_pty_class() {
		return "npo";
	}
	//--------------------------------------------------------------------------------
	public function get_pty_name() {
		return "Non-profit organization";
	}
	//--------------------------------------------------------------------------------
}
