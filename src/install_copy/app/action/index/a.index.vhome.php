<?php

namespace action\index;

/**
 * Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class vhome implements \com\router\int\action {
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
		if(!$this->token->check("system_users")) \com\http::redirect("?c=index/vlogin");


		$buffer = \app\ui::make()->html_buffer();
		$buffer->form("");

		$buffer->itext("Test", "test", false, ["required" => true]);

		$buffer->submit_button_js();
		$buffer->flush();

	}
	//--------------------------------------------------------------------------------
}