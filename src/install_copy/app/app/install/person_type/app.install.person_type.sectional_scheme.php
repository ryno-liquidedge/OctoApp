<?php
namespace app\install\person_type;

/**
 * @package app\install\person_type
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class sectional_scheme extends \app\install\person_type\master\main{
	//--------------------------------------------------------------------------------
	public function get_pty_code() {
		return "SECTIONAL_SCHEME";
	}
	//--------------------------------------------------------------------------------
	public function get_pty_is_individual() {
		return 0;
	}
	//--------------------------------------------------------------------------------
	public function get_pty_class() {
		return "sectional_scheme";
	}
	//--------------------------------------------------------------------------------
	public function get_pty_name() {
		return "Sectional scheme";
	}
	//--------------------------------------------------------------------------------
}
