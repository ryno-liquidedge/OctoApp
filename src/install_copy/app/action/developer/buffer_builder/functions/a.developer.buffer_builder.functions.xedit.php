<?php

namespace action\developer\buffer_builder\functions;

/**
 * Class vedit
 * @package action\system\setup\acl_role
 * @author Ryno Van Zyl
 */
class xedit implements \com\router\int\action {
	//--------------------------------------------------------------------------------
	use \com\router\tra\action;

	//--------------------------------------------------------------------------------
	public function auth() {
		return \core::$app->get_token()->check("dev");
	}

	//--------------------------------------------------------------------------------
	public function run() {

		$this->session->buffer_builder_html = $_REQUEST["html"];
	}
	//--------------------------------------------------------------------------------
}

