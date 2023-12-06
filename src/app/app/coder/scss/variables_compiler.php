<?php

namespace LiquidedgeApp\Octoapp\app\app\coder\scss;

//--------------------------------------------------------------------------------
class variables_compiler extends \LiquidedgeApp\Octoapp\app\app\intf\standard {

	public string $bs_primary;
	public string $bs_secondary;
	public string $bs_success;
	public string $bs_info;
	public string $bs_danger;
	public string $bs_warning;
	public string $bs_light;
	public string $bs_dark;

    //--------------------------------------------------------------------------------
	protected function __construct($options = []) {

		$fn_color = function($key){ return \db_settings::get_value($key); };
		$this->bs_primary = $fn_color(SETTING_BS_PRIMARY);
		$this->bs_secondary = $fn_color(SETTING_BS_SECONDARY);
		$this->bs_success = $fn_color(SETTING_BS_SUCCESS);
		$this->bs_info = $fn_color(SETTING_BS_INFO);
		$this->bs_warning = $fn_color(SETTING_BS_WARNING);
		$this->bs_danger = $fn_color(SETTING_BS_DANGER);
		$this->bs_light = $fn_color(SETTING_BS_LIGHT);
		$this->bs_dark = $fn_color(SETTING_BS_DARK);
	}

	//--------------------------------------------------------------------------------
	public function run() {
		$start_string = "// scss-docs-start theme-color-variables";
		$end_string = "// scss-docs-end theme-color-variables";
		$replace_string = <<<EOD
		// scss-docs-start theme-color-variables
		\$primary:       {$this->bs_primary} !default;
		\$secondary:     {$this->bs_secondary} !default;
		\$success:       {$this->bs_success} !default;
		\$info:          {$this->bs_info} !default;
		\$warning:       {$this->bs_warning} !default;
		\$danger:        {$this->bs_danger} !default;
		\$light:         {$this->bs_light} !default;
		\$dark:          {$this->bs_dark} !default;
		// scss-docs-end theme-color-variables
		EOD;

		$file = \core::$folders->get_app_app()."/ui/inc/scss/bootstrap/_variables.scss";
		$file_contents = file_get_contents($file);
		$length = strpos($file_contents, $end_string)+strlen($end_string) - strpos($file_contents, $start_string);

		$file_contents = substr_replace($file_contents, $replace_string, strpos($file_contents, $start_string), $length);

		file_put_contents($file, $file_contents);
	}
    //--------------------------------------------------------------------------------
}