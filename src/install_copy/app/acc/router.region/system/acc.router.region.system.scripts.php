<?php

namespace acc\router\region\system;

/**
 * @package acc\router\region\system
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class scripts implements \com\router\int\region {
	//--------------------------------------------------------------------------------
	use \com\router\tra\region;
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	public function render() {

		$section = \core::$app->get_section()->get_class();
		$js_version = \com\asset::get_js_version();

		$html = \com\ui::make()->buffer();
		$html->script(false, ["@type" => "text/javascript", "@src" => "https://cdn.jsdelivr.net/npm/fullcalendar/index.global.min.js"]);
		$html->script(false, ["@type" => "text/javascript", "@src" => "https://cdn.jsdelivr.net/npm/@fullcalendar/bootstrap5@6.1.8/index.global.min.js"]);
//	    $html->script(false, ["@type" => "text/javascript", "@src" => \app\http::get_stream_url(\core::$folders->get_app_app()."/ui/inc/js/fullcalendar-bs5-addon.js")]);
		$html->script(false, ["@type" => "text/javascript", "@src" => "index.php?c=index/xfile&context={$section}&name=js&v={$js_version}"]);
	    $html->add(\com\js::get_script());
	    $html->add(\com\js::get_domready());

	    $html->script(["*" => "core.overlay.hide();"]);

		return $html->get_clean();
	}
	//--------------------------------------------------------------------------------
	// static
	//--------------------------------------------------------------------------------
	/**
	 * @return scripts
	 */
	public static function make($options = []) {
		return new static($options);
	}
	//--------------------------------------------------------------------------------
}