<?php

namespace action\index;

/**
 * Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class xhome implements \com\router\int\action {
	//--------------------------------------------------------------------------------
	use \com\router\tra\action;
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	public function auth() {
		return true;
	}
	//--------------------------------------------------------------------------------
	public function run() {

	    if($this->token->check("system_users")) \com\http::redirect("?c=index/vhome");
	    else \com\http::redirect(\app\http::get_seo_url("ui_home"));

	}
	//--------------------------------------------------------------------------------
}