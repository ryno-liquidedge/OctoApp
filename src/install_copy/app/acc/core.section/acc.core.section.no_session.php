<?php

namespace acc\core\section;

/**
 * @package acc\core\section
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class no_session extends \com\core\section\api {
    //--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	protected function __construct($options = []) {
		// options
		$options = array_merge([
		], $options);

		// init
		$this->name = "No Audit";
		$this->layout = "clean";
	}
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	public function load() {
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
		return false;
	}
	//--------------------------------------------------------------------------------
	public function has_session_log() {
		return false;
	}
    //--------------------------------------------------------------------------------
    public static function make($options = []) {
        return new static($options);
    }
    //--------------------------------------------------------------------------------
	public function auth_fail() {
		// go to access denied page if logged in
		throw \com\error\factory::make_component_exception("auth", "authfailed");
	}
	//--------------------------------------------------------------------------------
}