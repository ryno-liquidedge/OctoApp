<?php

namespace acc\core\section;

/**
 * @package acc\core\section
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class no_audit extends \com\core\section\website {
    //--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	protected function __construct($options = []) {
		// options
		$options = array_merge([
		    "ui" => \app\ui\set\bootstrap::make()
		], $options);

		// init
		$this->name = "No Audit";
		$this->ui = $options["ui"];
		$this->layout = "web";
	}
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	public function load() {
		try{
		    @header('X-Robots-Tag: "none, noindex, nofollow, noarchive, nosnippet, noodp, notranslate, noimageindex"');
		    \com\user::load_application(\core::$app->get_request(), \core::$app->get_response(), ["throw_exceptions" => true]);
            \core::$app->set_token(new \com\auth\token());
            \core::$app->set_session(\com\session::$current);
        }catch (\com\api\exception\http_400 $ex){
		    switch ($ex->get_short_code()){
                case "session--expired":
                case "session--notfound":
                case "session--inactive":
                    $url = "/index.php?c=index/xhome";
                    break;
                default: $url = "index.php?c=index/vlogin"; break;
            }

            return \com\http::redirect($url);
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
	public function auth_fail() {
		// go to access denied page if logged in
		if (\com\user::is_loggedin()) {
			throw \com\error\factory::make_component_exception("auth", "authfailed");
		}
		else {
			// go to login page if not logged in
            $link_home = "/index.php?c=index/xhome";
			\com\http::redirect($link_home);
		}
	}
	//--------------------------------------------------------------------------------
}