<?php

namespace acc\core\instance;

/**
 * Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class live extends \com\core\intf\instance {

	use \LiquidedgeApp\Octoapp\app\acc\core_instance\tra\def;

	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	protected function __construct() {
		// init
		$this->id = "";
		$this->name = "Live";
		$this->code = "S";
		$this->url = "https://";
	}
	//--------------------------------------------------------------------------------
	public function apply_options() {
		// init
		$this->apply_defaults();
		$this->apply_live_options();
		$this->apply_live_custom_options();
		$this->apply_content_security_policy();


		$this->db_type = "mysql";
		$this->db_host = "";
		$this->db_name = "";
		$this->db_username = "";
		$this->db_password = "";

		$this->db_enabled = true;

		$this->email_force_to = "green.live@liquidedge.co.za";
		$this->email_support_to = "green.live@liquidedge.co.za";

		$this->php_exe = "/usr/bin/php-wrapper";
		$this->php_ini = "";

		$this->set_option("app.website.enable_url_rewrite", false);

	}
	//--------------------------------------------------------------------------------
}