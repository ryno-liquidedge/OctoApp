<?php

namespace action\index;

/**
 * Action Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class xfile implements \com\router\int\action {
	//--------------------------------------------------------------------------------
	use \com\router\tra\action;
	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	protected function __construct() {
		$context = $this->request->get_context();
		if ($context == "application") \core::$app->set_section(\acc\core\section\application_no_audit::make());
		else if ($context == "website") \core::$app->set_section(\acc\core\section\website_no_audit::make());
		else if ($context == "no_audit") \core::$app->set_section(\acc\core\section\website_no_audit::make());
		else if ($context) \core::$app->set_section(\com\core\factory\section::make()->get($context));
		else \core::$app->set_section(\acc\core\section\website_no_audit::make());
	}
	//--------------------------------------------------------------------------------
	public function auth() {
		return true;
	}
	//--------------------------------------------------------------------------------
	public function run() {
		// params
		$name = $this->request->get("name", \com\data::TYPE_FILE, ["get" => true]);
		$themed = $this->request->get("themed", \com\data::TYPE_BOOL, ["get" => true]);
		$group = $this->request->get("group", \com\data::TYPE_DBIDENT, ["get" => true]);

		// stream file
		\com\asset::stream($name, $group, $themed);
		return "stream";
	}
	//--------------------------------------------------------------------------------
}