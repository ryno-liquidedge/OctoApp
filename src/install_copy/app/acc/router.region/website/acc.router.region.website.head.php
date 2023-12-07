<?php

namespace acc\router\region\website;

/**
 * @package acc\router\region\website
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
		$html = \app\ui::make()->buffer();

		//meta info
		$html->add(\app\ui\page_meta::build());

		// general
		$html->meta(false, ["@http-equiv" => "Content-Type", "@content" => "text/html; charset=utf-8"]);
		$html->meta(false, ["@name" => "viewport", "@content" => "width=device-width, initial-scale=1.0, shrink-to-fit=no"]);

		//meta info
//		\app\http\page_meta::make()->build($html);

		// stylesheets and favicon
		$css_version = \com\asset::get_css_version();
		$section = \core::$app->get_section()->get_class();
		$html->link(false, ["@rel" => "shortcut icon", "@type" => "image/x-icon", "@href" => \db_settings::get_favicon_stream()]);
		$html->link(false, ["@rel" => "stylesheet", "@type" => "text/css", "@media" => "screen,print", "@href" => "index.php?c=index/xfile&context=website_no_audit&name=css&v={$css_version}"]);
		$html->link(false, ["@rel" => "stylesheet", "@type" => "text/css", "@media" => "print", "@href" => "index.php?c=index/xfile&context=website_no_audit&name=css&v={$css_version}"]);
		$html->link(false, ["@rel" => "stylesheet", "@type" => "text/css", "@media" => "print", "@href" => "index.php?c=index/xfile&context=website_no_audit&name=cssprint&v={$css_version}"]);

		$html->link(false, ["@rel" => "preconnect", "@href" => "https://fonts.googleapis.com"]);
		$html->link(false, ["@rel" => "preconnect", "@href" => "https://fonts.gstatic.com", "@crossorigin" => true]);
		$html->link(false, ["@rel" => "stylesheet", "@href" => "https://fonts.googleapis.com/css?family=Play"]);
		$html->link(false, ["@rel" => "stylesheet", "@href" => "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.2/css/all.min.css"]);
//		$html->link(false, ["@rel" => "stylesheet", "@href" => "https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css"]);


		//init script
		\LiquidedgeApp\Octoapp\app\app\js\js::add_script("//custom script");
		$html->script(false, ["@type" => "text/javascript", "@src" => "https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"]);

		//google recaptcha
		$html->add(\app\captcha::get_html());

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
	 * @param array $options
	 * @return static
	 */
	public static function make($options = []) {
		return new static($options);
	}
	//--------------------------------------------------------------------------------
}