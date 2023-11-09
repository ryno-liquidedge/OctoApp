<?php

namespace LiquidedgeApp\Octoapp\app\app\js;

/**
 * Class js
 * @package app
 */
class js extends \com\js{
	//--------------------------------------------------------------------------------
	/**
	 * Creates an ajax link string to be used with javascript events. The link will use
	 * the core.ajax.request function to send the query. When using HTTP POST with no form
	 * will automatically send the CSRF token with the request as well.
	 *
	 * @param string $url <p>The URL to send the ajax query to.</p>
	 *
	 * @param string|bool $options[*update] <p>HTML container id to load the resulting content of the ajax query into (Remember the # in front). Giving a (bool)true will default to the current panel wrapper id.</p>
	 * @param string $options[*method] <p>The HTTP method to use, defaults to POST.</p>
	 * @param bool $options[*no_overlay] <p>Set to true for not rendering the white container overlay.</p>
	 * @param string|array|bool $options[*form] <p>The form to include in the post data of the request. Can send multiple forms by providing an array of form ids (Remember the # in front). Setting to (bool)true will use the current active form.</p>
	 * @param string|array $options[*data] <p>Extra post encoded data to send with the request. Accepts an array of index value pairs. When setting a string value, make sure it is properly formatted with http_build_query().</p>
	 * @param array $options[*get] <p>An array of HTML input ids to send with the request (Remember the # in front). The values of these inputs will be sent as key value pairs using the elements id and value. These are added to the URL when the event is triggered.</p>
	 * @param string $options[*success] <p>The javascript function to run when the ajax call successfully completes.</p>
	 * @param string|bool $options[*confirm] <p>The message to display before running the query. This will provide a confirm popup. Choosing OK on this popup will send the query, otherwise call the function [*cancel_confirm]. Giving (bool)true will generate a default message.</p>
	 * @param string $options[*cancel_confirm] <p>The javascript function to call when Cancel is chosen on the confirm popup.</p>
	 *
	 * @return string <p>Javasctipt code to execute an ajax request.</p>
	 */
	public static function ajax($url, $options = []) {
		$options = array_merge([
			"*update" => null,
			"*updateComplete" => null,
			"*method" => null,
			"*no_overlay" => null,
			"*confirm" => null,
			"*form" => null,
			"*data" => null,
			"*get" => null,
			"*beforeSend" => null,
			"*autoscroll" => null,
			"*success" => "function(response){ core.ajax.process_response(response); }",
			"*complete" => null,
			"*done" => null,
			"*enable_reset_html" => null,
			"*cancel_confirm" => null,

			"form_validate_url" => false,
			"captcha_site_key" => false,
			"cid" => false,
			"form" => false,
			"enable_error_popup" => true,
			"enable_error_feedback" => true,
			"auto_focus_on_error" => false,
		], $options);

		if($options["form"]){
			$options["*form"] = "#{$options["form"]->id_form}";
			$options["cid"] = $options["form"]->cid;
			$options["form_validate_url"] = $options["form"]->validate_url;
		}

		// url
		if (substr($url, 0, 1) == "/") $url = "?c=".substr($url, 1);
		$JS_url = \com\str::escape_singlequote($url);

		// method
		if ($options["*method"]) $options["*method"] = strtoupper($options["*method"]);

		// update
		if ($options["*update"] === true) $options["*update"] = "#panel_".\core::$panel;

		// success
		if ($options["*updateComplete"] && substr($options["*updateComplete"], 0, 9) == "function(") $options["*updateComplete"] = "!{$options["*updateComplete"]}";
		if ($options["*beforeSend"] && substr($options["*beforeSend"], 0, 9) == "function(") $options["*beforeSend"] = "!{$options["*beforeSend"]}";
		if ($options["*success"] && substr($options["*success"], 0, 9) == "function(") $options["*success"] = "!{$options["*success"]}";
		if ($options["*complete"] && substr($options["*complete"], 0, 9) == "function(") $options["*complete"] = "!{$options["*complete"]}";
		if ($options["*done"] && substr($options["*done"], 0, 9) == "function(") $options["*done"] = "!{$options["*done"]}";
		if ($options["*cancel_confirm"] && substr($options["*cancel_confirm"], 0, 9) == "function(") $options["*cancel_confirm"] = "!{$options["*cancel_confirm"]}";

		// confirm
		if ($options["*confirm"] === true) $options["*confirm"] = "Are your sure you want to continue?";

		// data
		if ($options["*data"] && is_array($options["*data"])) $options["*data"] = http_build_query($options["*data"]);

		// form
		if ($options["*form"] === true) {
			if (\com\ui\helper::$current_form) $options["*form"] = "#".\com\ui\helper::$current_form->id_form;
			else $options["*form"] = null;
		}

		// csrf
		if ($options["*method"] != "GET" && $options["*form"] === null) $options["*csrf"] = \core::$app->get_response()->get_csrf();

		// js options
		$JS_options = self::create_options($options);

		// build javascript
		$cid = "";
		if($options["cid"] && $options["*form"]){
			$cid_options = js::create_options([
				"*enable_error_popup" => $options["enable_error_popup"],
				"*auto_focus_on_error" => $options["auto_focus_on_error"],
				"*enable_error_feedback" => $options["enable_error_feedback"],
			]);
			$cid = "if({$options["cid"]}.validate({$cid_options})) ";
		}


		$JS_string = "{$cid}core.ajax.request('{$JS_url}', {$JS_options});";

		if($options["form_validate_url"]){
			$JS_string = "{$cid}core.ajax.request_function('{$options["form_validate_url"]}', function(response){
				if(response.code == 1){ core.browser.alert(response.message); }
				else if(response.code == 0){ $JS_string }
			}, { form:'{$options["*form"]}' });";
		}

		if($options["captcha_site_key"]){
			$el = "<input>";
			$JS_string = "
				if (typeof grecaptcha != 'undefined') {
					event.preventDefault();
					grecaptcha.ready(function() {
						grecaptcha.execute('{$options["captcha_site_key"]}', {action: 'submit'}).then(function(token) {
							$('{$el}').attr({
								type: 'hidden',
								id: 'g-recaptcha-response',
								name: 'g-recaptcha-response',
								value: token,
							}).appendTo('{$options["*form"]}');
							
						  	$JS_string
						});
					});
				}else{ $JS_string }
			";
		}


		// done
		return $JS_string;
	}
	//--------------------------------------------------------------------------------
    public static function request_function($url, $function, $options = []) {

		$options = array_merge([
		    "validate" => false,
		    "validate_ok_callback" => false,
		], $options);

		if(is_callable($function)) $function = $function();

		if($options["validate"]){
			$options["*success"] = "function(response){
				if(response.code === 0) {
					$function
				}else{
					util.process_response(response);
				}
			}";
		}else{
			$options["*success"] = "function(response){ $function }";
		}

		return self::ajax($url, $options);

	}
	//--------------------------------------------------------------------------------
    public static function parse_id($id) {
		return strtr($id, ["[" => "\\\\[", "]" => "\\\\]", "." => "\\\\."]);
	}
	//--------------------------------------------------------------------------------
  	public static function add_script($event_script, $options = []) {
		// options
		$options = array_merge([
			"key" => \LiquidedgeApp\Octoapp\app\app\str\str::get_random_alpha(7, ["lowercase" => true]),
			"after" => false,
			"top" => false,
		], $options);

		if (self::$script_top_force) {
			$options["top"] = true;
		}

		if ($options["after"]) self::$script_after[$options["key"]] = $event_script;
		elseif ($options["top"]) self::$script_top[$options["key"]] = $event_script;
		else self::$script[$options["key"]] = $event_script;
  	}
	//--------------------------------------------------------------------------------
}