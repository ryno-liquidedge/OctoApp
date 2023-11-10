<?php

namespace acc\core\section;

/**
 * Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class website extends \com\core\section\website {
	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	protected function __construct($options = []) {
		// options
		$options = array_merge([
		    "layout" => "web"
		], $options);

		// init
		$this->name = "Website";
		$this->ui = \app\ui\set\website::make();
		$this->layout = $options["layout"];
	}
	//--------------------------------------------------------------------------------
	public function auth_fail() {
		// go to access denied page if logged in
		if (\com\user::is_loggedin()) {
			throw \com\error\factory::make_component_exception("auth", "authfailed");
		}
		else {
			// go to login page if not logged in
			\com\http::redirect(\app\http::get_seo_url("ui_home"));
		}
	}
	//--------------------------------------------------------------------------------
}