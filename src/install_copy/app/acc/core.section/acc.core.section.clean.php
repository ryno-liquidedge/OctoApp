<?php

namespace acc\core\section;

/**
 * @package acc\core\section
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class clean extends \com\core\section\api {
    //--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	protected function __construct($options = []) {
		// options
		$options = array_merge([
		], $options);

		// init
		$this->name = "Clean";
		$this->layout = "clean";
	}
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	public function load() {
	}
	//--------------------------------------------------------------------------------
	public function auth_fail() {
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
}