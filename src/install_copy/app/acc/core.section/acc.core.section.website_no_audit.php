<?php

namespace acc\core\section;

/**
 * @package acc\core\section
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class website_no_audit extends \com\core\section\website {
    //--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	protected function __construct($options = []) {

		// options
		$options = array_merge([
		    "layout" => "web"
		], $options);

		// init
		$this->name = "Website No Audit";
		$this->ui = \app\ui\set\website::make();
		$this->layout = $options["layout"];

	}
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	public function load() {
		\com\user::load_application(\core::$app->get_request(), \core::$app->get_response());
		\core::$app->set_token(new \com\auth\token());
		\core::$app->set_session(\com\session::$current);
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
	public function has_session_cache_limit() {
		return false;
	}
	//--------------------------------------------------------------------------------
	public function has_session_status_updates() {
		return false;
	}
	//--------------------------------------------------------------------------------
	public function has_session() {
		return true;
	}
	//--------------------------------------------------------------------------------
	public function has_session_log() {
		return false;
	}
	//--------------------------------------------------------------------------------
}