<?php

namespace acc\router\region\system;

/**
 * @package acc\router\region\system
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class head implements \com\router\int\region {
	//--------------------------------------------------------------------------------
	use \com\router\tra\region;
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	public function render() {
		// html
		$html = \com\ui::make()->buffer();

		// general
		$title = \db_settings::get_value(SETTING_COMPANY_NAME, [
			"default" => \core::$app->get_instance()->get_title()
		]);
		$html->title("^{$title}");
		$html->meta(false, ["@http-equiv" => "Content-Type", "@content" => "text/html; charset=utf-8"]);
		$html->meta(false, ["@name" => "viewport", "@content" => "width=device-width, initial-scale=1.0, shrink-to-fit=no"]);

		// stylesheets and favicon
		$css_version = \com\asset::get_css_version();
		$url = \core::$app->get_instance()->get_url();
		$section = \core::$app->get_section()->get_class();
		$html->link(false, ["@rel" => "shortcut icon", "@type" => "image/x-icon", "@href" => \db_settings::get_favicon_stream()]);
		$html->link(false, ["@rel" => "stylesheet", "@type" => "text/css", "@media" => "screen,print", "@href" => "index.php?c=index/xfile&context={$section}&name=css&v={$css_version}"]);
		$html->link(false, ["@rel" => "stylesheet", "@type" => "text/css", "@media" => "print", "@href" => "index.php?c=index/xfile&context={$section}&name=css&v={$css_version}"]);
		$html->link(false, ["@rel" => "stylesheet", "@type" => "text/css", "@media" => "print", "@href" => "index.php?c=index/xfile&context={$section}&name=cssprint&v={$css_version}"]);
		$html->link(false, ["@rel" => "stylesheet", "@href" => "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.2/css/all.min.css"]);

		// javascript
		$js_version = \com\asset::get_js_version();
		$html->script(false, ["@type" => "text/javascript", "@src" => "https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"]);

		// for internet explorer versions lower than 9
		$html->add("
			<!--[if lt IE 9]>
				<script src=\"https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js\"></script>
				<script src=\"https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js\"></script>
			<![endif]-->
		");

		$html->xloader();

		// done
		return $html->get_clean();
	}
	//--------------------------------------------------------------------------------
	// static
	//--------------------------------------------------------------------------------
	/**
	 * @return \com\router\region\head
	 */
	public static function make($options = []) {
		return new static($options);
	}
	//--------------------------------------------------------------------------------
}