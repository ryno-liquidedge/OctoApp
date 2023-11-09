<?php

namespace LiquidedgeApp\Octoapp\app\app\error;

/**
 * File class.
 *
 * @package nova
 * @subpackage com
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 *
 */
class error extends \com\error {
	//--------------------------------------------------------------------------------
	/**
	 * Build and returns the error handling navigation bar for developer use.
	 *
	 * @author Niel Astle
	 */
	public static function navigation() {
		// check if errors should be shown
		if (!\core::$app->get_instance()->get_show_errors()) return;

		// build the HTML bar
		$HTML_bar = false;
		if (file_exists(\core::$folders->get_errors()."/error.xml.txt") || file_exists(\core::$folders->get_temp()."/console.txt") || file_exists(\core::$folders->get_temp()."/error.fatal.txt")) {
			$HTML_bar = '<div class="bar ui-debug-wrapper">';
			$HTML_bar .= \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->button("view error log", "core.browser.new_window('?c=index/verror_debug');");
			$HTML_bar .= \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->button("clear error log", "core.ajax.request('?c=index/xerror_clear', { no_overlay:true, csrf: '".\core::$app->get_response()->get_csrf()."' });$('div.bar').remove();");
			$HTML_bar .= '</div>';
		}

		// return
		return $HTML_bar;
	}
    //--------------------------------------------------------------------------------
}