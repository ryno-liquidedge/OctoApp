<?php

namespace acc\core\instance;

/**
 * Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class dev extends \com\core\intf\instance {

	use \LiquidedgeApp\Octoapp\app\acc\core_instance\tra\def;

	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	protected function __construct() {
		// init
		$this->id = "";
		$this->name = "DEV PC";
		$this->code = "DP";
		$this->url = "http://";
	}
	//--------------------------------------------------------------------------------
	public function apply_options() {
		// init
		$this->apply_defaults();
		$this->apply_development_options();
		$this->apply_development_custom_options();
		$this->apply_content_security_policy();

		$this->db_type = "mysql";
		$this->db_host = "localhost";
		$this->db_name = "";
		$this->db_username = "root";
		$this->db_password = "root";

		$this->db_enabled = true;

		$this->email_force_to = "";
		$this->email_support_to = "";

		$this->php_exe = "C:/PHP/PHP8.1.11/php.exe";
		$this->php_ini = "C:/PHP/PHP8.1.11/php.ini";

	}
	//--------------------------------------------------------------------------------
}