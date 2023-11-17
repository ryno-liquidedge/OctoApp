<?php

namespace acc\core\instance;

/**
 * Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class ryno extends \com\core\intf\instance {

	use \LiquidedgeApp\Octoapp\app\acc\core_instance\tra\def;

	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	protected function __construct() {
		// init
		$this->id = "RYNO-PC/C:/inetpub/wwwroot/test_app/root";
		$this->name = "Ryno Work PC";
		$this->code = "R";
		$this->url = "http://test_app.local";
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
		$this->db_name = "loc_test_app";
		$this->db_username = "root";
		$this->db_password = "root";

		$this->db_enabled = true;

		$this->email_force_to = "ryno@liquidedge.co.za";
		$this->email_support_to = "ryno@liquidedge.co.za";

		$this->php_exe = "C:/PHP/PHP8.1.11/php.exe";
		$this->php_ini = "C:/PHP/PHP8.1.11/php.ini";

	}
	//--------------------------------------------------------------------------------
}