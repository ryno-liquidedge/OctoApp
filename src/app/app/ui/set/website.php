<?php

namespace LiquidedgeApp\Octoapp\app\app\ui\set;

/**
 * @package app\ui\set
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class website extends \com\ui\set\bootstrap {
	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	protected function __construct($options = []) {
		// init
		$this->name = "Website";
	}
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	public function get($name, $options = []) {
	    $options_arr = array_merge([
	    ], $options);

		$class = $this->get_class_name($name);

		return $class::make($options);
	}
	//--------------------------------------------------------------------------------
    protected function get_class_name($name){

		//evaluate app - custom folder
	    if(file_exists(\core::$folders->get_app_app()."/ui/set/custom/app.ui.set.custom.$name.php")){
	        return "\\app\\ui\\set\\custom\\{$name}";
        }

	    //evaluate app - bootstrap folder
	    if(file_exists(\core::$folders->get_app_app()."/ui/set/bootstrap/app.ui.set.bootstrap.$name.php")){
	        return "\\app\\ui\\set\\bootstrap\\{$name}";
        }

        //default to com
	    return "\\com\\ui\\set\\bootstrap\\{$name}";

    }
	//--------------------------------------------------------------------------------
	public function get_js_includes() {
		// init
		$path_inc = \core::$folders->get_com()."/inc";
		$composer_inc = \core::$folders->get_app()."/inc/composer/vendor";
		$path_ui = \core::$folders->get_com()."/ui/inc";
		$path_js = \core::$folders->get_app_app()."/ui/inc/js";
		$js_arr = [
			//jquery
            "{$path_js}/jquery-ui.min.js",
			"{$path_js}/core/jquery.extend.js",

			// bootstrap
			"{$composer_inc}/twbs/bootstrap/dist/js/bootstrap.bundle.min.js",

            //underscore js
			"{$path_js}/underscore.js",

			// nova
			"{$path_js}/core/app.ui.bootstrap.core.js",
			"{$path_js}/core/app.ui.bootstrap.table.js",
			"{$path_js}/core/app.ui.bootstrap.panel.js",
			"{$path_js}/core/app.ui.bootstrap.tab.js",
			"{$path_js}/core/app.ui.bootstrap.form.js",
			"{$path_js}/core/app.ui.bootstrap.ping.js",
			"{$path_js}/core/app.ui.bootstrap.button.js",
			"{$path_js}/core/app.ui.bootstrap.menu.js",
			"{$path_js}/core/app.ui.bootstrap.popup.js",
			"{$path_js}/core/app.ui.bootstrap.lazy_loader.js",
			"{$path_js}/core/app.ui.bootstrap.ui.js",
			"{$path_js}/core/app.ui.bootstrap.calendar.js",

            //highlighter
			"{$path_js}/highlighter.js",

			// bootstrap-datetimepicker & calendar
			"{$path_js}/moment.js",
			\core::$folders->get_app_app()."/inc/datetimepicker/inc/js/tempusdominus-bootstrap-4.js",
			\core::$folders->get_app_app()."/inc/datetimepicker/inc/js/tempusdominus-bootstrap-4-append.js",

			//dropzone
			\core::$folders->get_app_app()."/inc/dropzone/inc/js/dropzone.min.js",
			\core::$folders->get_app_app()."/inc/dropzone/inc/js/cropper.min.js",
            \core::$folders->get_app_app()."/inc/dropzone/inc/js/jquery-cropper.min.js",

            //fancybox
			\core::$folders->get_app_app()."/inc/fancybox/inc/js/jquery.fancybox.min.js",
			\core::$folders->get_app_app()."/inc/fancybox/inc/js/jquery.fancybox.addon.js",

			"{$path_js}/jquery.checkboxes-1.2.2.js",

            //range slider
			"{$path_js}/ion.rangeslider.min.js",

			//parallax
			"{$path_js}/aos.js",
			"{$path_js}/bootstrap.parallax.js",

			//custom
			"{$path_js}/magicsuggest.js",
			"{$path_js}/bootstrap-multiselect.js",
			"{$path_js}/website.js",
		];

		// done
		return $js_arr;
	}
	//--------------------------------------------------------------------------------
	public function get_css_includes() {
		// init
		$path_css = \core::$folders->get_app_app()."/ui/inc/css";
		$path_inc = \core::$folders->get_com()."/inc";
		$path_ui = \core::$folders->get_com()."/ui/inc";
		$css_arr = [
			// bootstrap
			"{$path_css}/bootstrap.css",
			"{$path_css}/bootstrap.append.css",
			"{$path_css}/bootstrap-grid.css",
			"{$path_css}/bootstrap-reboot.css",
			"{$path_css}/bootstrap-icons.css",

            "{$path_css}/jquery-ui.min.css",

			// datetimepicker
			\core::$folders->get_app_app()."/inc/datetimepicker/inc/css/tempusdominus-bootstrap-4-build.css",

			// dropzone
			\core::$folders->get_app_app()."/inc/dropzone/inc/css/dropzone.css",
            \core::$folders->get_app_app()."/inc/dropzone/inc/css/cropper.css",

            //fancybox
			\core::$folders->get_app_app()."/inc/fancybox/inc/css/jquery.fancybox.css",
			\core::$folders->get_app_app()."/inc/fancybox/inc/css/jquery.fancybox.addon.css",

            //range slider
			"{$path_css}/ion.rangeslider.css",

			//custom
			"{$path_css}/magicsuggest.css",
			"{$path_css}/bootstrap-multiselect.css",
			"{$path_css}/aos.css",
			"{$path_css}/app_ui_standards.css",
			"{$path_css}/app_ui.css",
			"{$path_css}/website.css",
		];

		// done
		return $css_arr;
	}
	//--------------------------------------------------------------------------------
	public function get_css_print_includes() {
		// init
		$path_ui = \core::$folders->get_com()."/ui/inc";
		$css_arr = [
		];

		// done
		return $css_arr;
	}
	//--------------------------------------------------------------------------------
}