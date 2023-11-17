<?php

namespace acc\core\instance;

/**
 * Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class staging extends \com\core\intf\instance {

	use \LiquidedgeApp\Octoapp\app\acc\core_instance\tra\def;

	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	protected function __construct() {
		// init
		$this->id = "";
		$this->name = "Staging";
		$this->code = "S";
		$this->url = "https://";
	}
	//--------------------------------------------------------------------------------
	public function apply_options() {
		// init
		$this->apply_defaults();
		$this->apply_test_options();
		$this->apply_test_custom_options();
		$this->apply_content_security_policy();

		$this->db_type = "mysql";
		$this->db_host = "";
		$this->db_name = "";
		$this->db_username = "";
		$this->db_password = "";

		$this->db_enabled = true;

		$this->email_force_to = "green.test@liquidedge.co.za";
		$this->email_support_to = "green.test@liquidedge.co.za";

		$this->php_exe = "/usr/bin/php-wrapper";
		$this->php_ini = "";

		$this->set_option("app.website.enable_url_rewrite", false);
		$this->set_option("url.absolute", false);

		/**
            DB
            DB:	 	temp1ptxmgt_db1
            User: 	tempptxmgt_1
            Pass: 	6S7J6onB528036

            SSH
            User:	tempptxmgt
            Pass:	8Z032l2847dYT0
         */

	}
	//--------------------------------------------------------------------------------
}