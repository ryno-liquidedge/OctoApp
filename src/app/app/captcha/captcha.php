<?php

namespace LiquidedgeApp\Octoapp\app\app\captcha;

/**
 * Captcha helper functions.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class captcha {
	//--------------------------------------------------------------------------------
	public static function get_html() {
		// add our custom options
		if(\core::$app->get_instance()->get_recaptcha_site_key()){
			$buffer = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();

			$buffer->script(["@src" => "https://www.google.com/recaptcha/api.js?render=".\core::$app->get_instance()->get_recaptcha_site_key()]);

			return $buffer->build();

		}
	}
    //--------------------------------------------------------------------------------
	public static function is_valid() {
		// params
		if(\core::$app->get_instance()->is_environment("DEV"))
			return true;

		$g_recaptcha_response = \core::$app->get_request()->get("g-recaptcha-response", \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_TEXT);
		if (!$g_recaptcha_response) return false;

		$post_data = [
			"secret" => \core::$app->get_instance()->get_recaptcha_secret_key(),
			"response" => $g_recaptcha_response,
			"remoteip" => \com\http::get_ip(),
		];

		$post_string = http_build_query($post_data);

		$recaptcha_response = \com\http::curl("https://www.google.com/recaptcha/api/siteverify", [
			">CURLOPT_POST" => 1,
			">CURLOPT_POSTFIELDS" => $post_string,
			">CURLOPT_HTTPHEADER" => [
				"Content-Type: application/x-www-form-urlencoded",
				"Content-Length: " . strlen($post_string),
			]
		]);

		if (isset($recaptcha_response["body"]) && is_string($recaptcha_response["body"])) {
			$response = json_decode($recaptcha_response["body"]);
			if (json_last_error() == JSON_ERROR_NONE) return $response->success;
		}

		return false;
	}
    //--------------------------------------------------------------------------------
}