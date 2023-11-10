<?php

namespace LiquidedgeApp\Octoapp\app\app\user;

/**
 * @package app
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */

class user extends \com\user{
 	//--------------------------------------------------------------------------------
 	// functions
	//--------------------------------------------------------------------------------
  	public static function login_system($username, $password, $return_errorcode = false) {
		// check if we have an active session
		if (self::is_loggedin()) return $return_errorcode ? 6 : \com\http::go_error(6);

  		// authenticate
  	  	$person = self::authenticate($username, $password, "per_is_active = 1");

		// validate access
		$person = \core::$app->get_section()->validate_user($person);

		$authenticated = (bool)$person;

		// try and get account linked to given username if not authenticated
		if (!$person) {
			$SQL_username = dbvalue($username);
			$person = \core::$db->person->get_fromdb("per_username = $SQL_username");

			// check for inactive account
			if ($person && $person->per_is_active == 2) return $return_errorcode ? 20 : \com\http::go_error(20);

			// check for inactive account
			if ($person && !$person->per_is_active) return $return_errorcode ? 14 : \com\http::go_error(14);
		}

		// check if we have exceeded the login retry limit
		if ($person && $person->per_retry_count >= 3 && \LiquidedgeApp\Octoapp\app\app\date\date::minute_diff($person->per_retry_date, "now") <= 15) {
			// increment retry counter and reset retry date
			$person->per_retry_count += 1;
			$person->per_retry_date = \LiquidedgeApp\Octoapp\app\app\date\date::strtodatetime();
			$person->_auth = 1;
			$person->update();

			// log
			return $return_errorcode ? 11 : \com\http::go_error(11);
		}

	    if ($authenticated) {
	    	// login date
	    	$person->per_date_login_previous = $person->per_date_login;
	    	$person->per_date_login = \LiquidedgeApp\Octoapp\app\app\date\date::strtodatetime();
			$person->per_retry_count = 0;
			$person->per_retry_date = "null";
	    	$person->_auth = 1;
	    	$person->update();

			// force logout existing sessions
			self::expire_concurrent($person);

			// logged in
			\com\session::$current->regenerate_id([
				".ses_ref_person" => $person->id,
			]);
			\com\session::$current->core_is_loggedin = 1;

			// init user
			self::change_user($person);

			// home page
	    	return $return_errorcode ? 0 : \com\http::go_home();
	  	}

		// add retry count for failed password
		if ($person) {
			$person->per_retry_count += 1;
			$person->per_retry_date = \LiquidedgeApp\Octoapp\app\app\date\date::strtodatetime();
			$person->_auth = 1;
			$person->update();
		}

		// log failed login
        return $return_errorcode ? 1 : \com\http::go_error(1);
  	}
  	//--------------------------------------------------------------------------------
  	public static function login_frontend($username, $password, $return_errorcode = false) {
		// check if we have an active session
		if (self::is_loggedin()) return $return_errorcode ? 6 : \LiquidedgeApp\Octoapp\app\app\user\http::go_error_frontend(6);

  		// authenticate
  	  	$person = self::authenticate($username, $password, "per_is_active = 1");

		// validate access
		$person = \core::$app->get_section()->validate_user($person);

		$authenticated = (bool)$person;

		// try and get account linked to given username if not authenticated
		if (!$person) {
			$SQL_username = dbvalue($username);
			$person = \core::$db->person->get_fromdb("per_username = $SQL_username");

			// check for inactive account
			if ($person && $person->per_is_active == 2) return $return_errorcode ? 20 : \LiquidedgeApp\Octoapp\app\app\user\http::go_error_frontend(20);

			// check for inactive account
			if ($person && !$person->per_is_active) return $return_errorcode ? 14 : \LiquidedgeApp\Octoapp\app\app\user\http::go_error_frontend(14);
		}

		// check if we have exceeded the login retry limit
		if ($person && $person->per_retry_count >= 3 && \LiquidedgeApp\Octoapp\app\app\date\date::minute_diff($person->per_retry_date, "now") <= 15) {
			// increment retry counter and reset retry date
			$person->per_retry_count += 1;
			$person->per_retry_date = \LiquidedgeApp\Octoapp\app\app\date\date::strtodatetime();
			$person->_auth = 1;
			$person->update();

			// log
			return $return_errorcode ? 11 : \LiquidedgeApp\Octoapp\app\app\user\http::go_error_frontend(11);
		}

	    if ($authenticated) {

	        if(!$person->has_role(ACL_CODE_DEV) && $person->is_empty("per_password_date_created")){
			    message(false);
			    $person->per_password_date_created = \LiquidedgeApp\Octoapp\app\app\date\date::strtodatetime();
			    $person->update();
			    message(true);
            }

			if(!$person->is_empty("per_password_date_created")){
			    if(\LiquidedgeApp\Octoapp\app\app\date\date::strtodate("{$person->per_password_date_created} + 30 days") < \LiquidedgeApp\Octoapp\app\app\date\date::strtodate()){
			        // home page
                    return $return_errorcode ? 22 : \LiquidedgeApp\Octoapp\app\app\user\http::go_error_frontend(22);
                }
            }

	    	// login date
	    	$person->per_date_login_previous = $person->per_date_login;
	    	$person->per_date_login = \LiquidedgeApp\Octoapp\app\app\date\date::strtodatetime();
			$person->per_retry_count = 0;
			$person->per_retry_date = "null";
	    	$person->_auth = 1;
	    	$person->update();

			// force logout existing sessions
			self::expire_concurrent($person);

			// logged in
			\com\session::$current->regenerate_id([
				".ses_ref_person" => $person->id,
			]);
			\com\session::$current->core_is_loggedin = 1;


			// init user
			self::change_user($person);

			// home page
	    	return $return_errorcode ? 0 : \LiquidedgeApp\Octoapp\app\app\user\http::go_home_frontend();
	  	}

		// add retry count for failed password
		if ($person) {
			$person->per_retry_count += 1;
			$person->per_retry_date = \LiquidedgeApp\Octoapp\app\app\date\date::strtodatetime();
			$person->_auth = 1;
			$person->update();
		}

		// log failed login
        return $return_errorcode ? 1 : \LiquidedgeApp\Octoapp\app\app\user\http::go_error_frontend(1);
  	}
  	//--------------------------------------------------------------------------------
  	public static function logout_frontend() {

  	    self::remove_user_id_cookie();

  		\com\session::$current->destroy(["reason" => 3,	"redirect" => \LiquidedgeApp\Octoapp\app\app\user\http::get_session_redirect()]);
  	}
  	//--------------------------------------------------------------------------------
    public static function set_user_id_cookie() {

        if(!\com\user::$active) return;

        \LiquidedgeApp\Octoapp\app\app\user\http\cookie::make()->set(md5("user_id"), \com\user::$active_id, ["encrypt" => true, "expire" => time()+60*60*24*30]);
    }
    //--------------------------------------------------------------------------------
    public static function get_user_id_cookie() {

        return \LiquidedgeApp\Octoapp\app\app\user\http\cookie::make()->get(md5("user_id"), ["decrypt" => true]);

    }
    //--------------------------------------------------------------------------------
    public static function remove_user_id_cookie() {

        return \LiquidedgeApp\Octoapp\app\app\user\http\cookie::make()->remove_cookie(md5("user_id"));

    }
	//--------------------------------------------------------------------------------
    public static function init_user_id_cookie() {

  	    if(!self::$active && \LiquidedgeApp\Octoapp\app\app\user\user::get_user_id_cookie() && !isset($_SESSION["cookie_is_loaded"])){
            $_SESSION["cookie_is_loaded"] = true;
  	        \com\http::redirect("?c=index/xlogin_cookie");
        }
    }
    //--------------------------------------------------------------------------------
	public static function load_application(\com\router\int\request $request, \com\router\int\response $response, $options = []) {
		// options
		$options = array_merge([
		], $options);

		// init session
		$control = $request->get_control();
		switch ($control) { // !fix
			case "index/xconsole" : return; break;
			case "index/xfile" : $options["no_cache"] = true; break;

			case "index/xcomuploader/4" :
				$options["id"] = $request->get("session", \com\data::TYPE_ALPHANUM);
				break;
		}
		self::load("application", $options);

		// auth csrf token
		$current_csrf = \com\session::$current->session_csrf;
		$response->set_csrf($current_csrf);
		if ($request->get_method() != "GET") {
			$request_csrf = $request->get_csrf();

			// token mismatch
			if (self::$user_id) { // !fix
				if (!$request_csrf) {
					throw \LiquidedgeApp\Octoapp\app\app\user\error\factory::make_component_exception("auth", "csrfmissing");
				}
				elseif ($request_csrf != $current_csrf) {
					throw \LiquidedgeApp\Octoapp\app\app\user\error\factory::make_component_exception("auth", "changeduser");
				}
			}
		}
	}

	//--------------------------------------------------------------------------------
}