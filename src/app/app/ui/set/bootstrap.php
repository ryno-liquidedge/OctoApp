<?php

namespace LiquidedgeApp\Octoapp\app\app\ui\set;

/**
 * @package app\ui\set
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class bootstrap extends \com\ui\set\bootstrap {
	
	private string $dir_com_inc;
	private string $dir_app_app_inc;
	private string $dir_app_app_ui_inc;
	private string $dir_composer;
	
	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	protected function __construct($options = []) {
		// init
		$this->name = "Bootstrap V5";
		$this->dir_com_inc = \core::$folders->get_com()."/inc";
		$this->dir_composer = \core::$folders->get_app()."/inc/composer/vendor";
		$this->dir_app_app_inc = \LiquidedgeApp\Octoapp\Core::DIR_APP_APP_INC;
		$this->dir_app_app_ui_inc = \core::$folders->get_app_app()."/ui/inc";
	}
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

		$dir_arr = [
			__DIR__."/custom/$name.php" => "\\LiquidedgeApp\\Octoapp\\app\\app\\ui\\set\\custom\\$name",
			__DIR__."/bootstrap/$name.php" => "\\LiquidedgeApp\\Octoapp\\app\\app\\ui\\set\\bootstrap\\$name",
			"{$this->dir_app_app_inc}/ui/set/custom/app.ui.set.custom.$name.php" => "\\app\\ui\\set\\custom\\$name",
			"{$this->dir_app_app_inc}/ui/set/bootstrap/app.ui.set.bootstrap.$name.php" => "\\app\\ui\\set\\bootstrap\\$name",
		];

		foreach ($dir_arr as $dir => $class){
			if(file_exists($dir)) return $class;
		}

        //default to com
	    return "\\com\\ui\\set\\bootstrap\\{$name}";

    }
	//--------------------------------------------------------------------------------
	public function get_js_includes() {

		// init
		$js_arr = [

			//jquery
			"{$this->dir_app_app_ui_inc}/js/jquery-ui.min.js",
			"{$this->dir_app_app_ui_inc}/js/core/jquery.extend.js",

			// bootstrap
			"{$this->dir_composer}/twbs/bootstrap/dist/js/bootstrap.bundle.min.js",

			// amcharts
			"{$this->dir_com_inc}/amcharts/js/amcharts.min.js",
			"{$this->dir_com_inc}/amcharts/js/serial.min.js",
			"{$this->dir_com_inc}/amcharts/js/funnel.min.js",
			"{$this->dir_com_inc}/amcharts/js/gauge.min.js",
			"{$this->dir_com_inc}/amcharts/js/pie.min.js",
			"{$this->dir_com_inc}/amcharts/js/radar.min.js",
			"{$this->dir_com_inc}/amcharts/js/xy.min.js",
			"{$this->dir_com_inc}/amcharts/js/gantt.min.js",

			// nova
			"{$this->dir_app_app_ui_inc}/js/core/app.ui.bootstrap.core.js",
			"{$this->dir_app_app_ui_inc}/js/core/app.ui.bootstrap.table.js",
			"{$this->dir_app_app_ui_inc}/js/core/app.ui.bootstrap.panel.js",
			"{$this->dir_app_app_ui_inc}/js/core/app.ui.bootstrap.tab.js",
			"{$this->dir_app_app_ui_inc}/js/core/app.ui.bootstrap.form.js",
			"{$this->dir_app_app_ui_inc}/js/core/app.ui.bootstrap.ping.js",
			"{$this->dir_app_app_ui_inc}/js/core/app.ui.bootstrap.button.js",
			"{$this->dir_app_app_ui_inc}/js/core/app.ui.bootstrap.menu.js",
			"{$this->dir_app_app_ui_inc}/js/core/app.ui.bootstrap.popup.js",
			"{$this->dir_app_app_ui_inc}/js/core/app.ui.bootstrap.ui.js",

            //highlighter
			"{$this->dir_app_app_ui_inc}/js/highlighter.js",

			// bootstrap-datetimepicker & calendar
			"{$this->dir_app_app_ui_inc}/js/moment.js",
			"{$this->dir_app_app_inc}/datetimepicker/inc/js/tempusdominus-bootstrap-4.js",
			"{$this->dir_app_app_inc}/datetimepicker/inc/js/tempusdominus-bootstrap-4-append.js",

			//dropzone
			"{$this->dir_app_app_inc}/dropzone/inc/js/dropzone.min.js",
			"{$this->dir_app_app_inc}/dropzone/inc/js/cropper.min.js",
            "{$this->dir_app_app_inc}/dropzone/inc/js/jquery-cropper.min.js",

			//fancybox
			"{$this->dir_app_app_inc}/fancybox/inc/js/jquery.fancybox.min.js",
			"{$this->dir_app_app_inc}/fancybox/inc/js/jquery.fancybox.addon.js",

			// summernote
			"{$this->dir_app_app_inc}/summernote/summernote-lite.js",

			//parallax
			"{$this->dir_app_app_ui_inc}/js/aos.js",
			"{$this->dir_app_app_ui_inc}/js/bootstrap.parallax.js",

			//custom
			"{$this->dir_app_app_ui_inc}/js/bootstrap-multiselect.js",
			"{$this->dir_app_app_ui_inc}/js/system.js",

		];

		// done
		return $js_arr;
	}
	//--------------------------------------------------------------------------------
	public function get_css_includes() {
		// init
		$css_arr = [
			// bootstrap
			"{$this->dir_app_app_ui_inc}/css/bootstrap.css",
			"{$this->dir_app_app_ui_inc}/css/bootstrap.append.css",
			"{$this->dir_app_app_ui_inc}/css/bootstrap-grid.css",
			"{$this->dir_app_app_ui_inc}/css/bootstrap-reboot.css",

			"{$this->dir_app_app_ui_inc}/css/jquery-ui.min.css",

			// datetimepicker
			"{$this->dir_app_app_inc}/datetimepicker/inc/css/tempusdominus-bootstrap-4-build.css",

			// dropzone
			"{$this->dir_app_app_inc}/dropzone/inc/css/dropzone.css",
            "{$this->dir_app_app_inc}/dropzone/inc/css/cropper.css",

			//fancybox
			"{$this->dir_app_app_inc}/fancybox/inc/css/jquery.fancybox.css",
			"{$this->dir_app_app_inc}/fancybox/inc/css/jquery.fancybox.addon.css",

			// summernote
			"{$this->dir_app_app_inc}/summernote/summernote-lite.css",
			"{$this->dir_app_app_inc}/summernote/summernote.append.css",

			//custom
			"{$this->dir_app_app_ui_inc}/css/bootstrap-multiselect.css",
			"{$this->dir_app_app_ui_inc}/css/aos.css",
			"{$this->dir_app_app_ui_inc}/css/app_ui_standards.css",
			"{$this->dir_app_app_ui_inc}/css/app_ui.css",
			"{$this->dir_app_app_ui_inc}/css/system.css",
		];

		// done
		return $css_arr;
	}
	//--------------------------------------------------------------------------------
	public function get_css_print_includes() {
		// init
		$path_ui = \core::$folders->get_com()."/ui/inc";
		$css_arr = [];

		// done
		return $css_arr;
	}
	//--------------------------------------------------------------------------------
}