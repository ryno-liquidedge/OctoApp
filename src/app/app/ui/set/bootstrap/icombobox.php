<?php

namespace LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap;

/**
 * @package app\ui\set\bootstrap
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 *
 * http://nicolasbize.com/magicsuggest/doc.html#allowFreeEntries
 *
 */
class icombobox extends \com\ui\intf\element {
	//--------------------------------------------------------------------------------
	// fields
	//--------------------------------------------------------------------------------
	protected static $is_singleton = true;

	protected $options = null;
	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	protected function __construct() {
		$this->name = "Combobox Input";
	}
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	public function build($options = []) {
		// options
		$options = array_merge([
			"id" => false,
			"value" => false,
			"disabled" => false,
			"hide_button" => true,
			"truncate_text" => false,
			"!change" => "function(e,m){}",

			"data" => [],
			"params" => [
				"_csrf" => \core::$app->get_response()->get_csrf()
			],
			"url" => false,

			"*beforeSend" => "!function(xhr, settings) {}",
		], $options);

		// html
		$js_id = $options["id"];
		$buffer = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();

		$style_arr = [];
		$style_arr[] = ".ms-res-ctn{ margin-top: 5px; right: 0; }";

		if($options["truncate_text"]){
			$style_arr[] = ".ms-sel-item { white-space: nowrap; overflow: hidden; text-overflow: ellipsis; width: 100%;}";
			$style_arr[] = ".ms-sel-ctn { overflow: hidden; }";

		}
		if($options["hide_button"]) $style_arr[] = ".ms-trigger{ display:none; }";
		$buffer->style(["*" => implode(" ", $style_arr)]);

		$buffer->xitext($js_id, false, false, $options);

		$js_options = array_merge([
			"*name" => $options["id"],
			"*placeholder" => "Search...",
			"*expandOnFocus" => false,
			"*minChars" => 2,
			"*value" => $options["value"],
			"*displayField" => "value", //specifies the JSON property to be used for display.
			"*resultsField" => "id", //specifies the JSON property that contains the suggestions data.
			"*valueField" => "id", //specifies the JSON property to be used for value.
			"*allowFreeEntries" => false,
			"*disabled" => $options["disabled"],
		], $options);

		if($options["url"]){
			$js_options["*data"] = $options["url"];
			$js_options["*dataUrlParams"] = $options["params"];
			$js_options["*ajaxConfig"] = [
				"xhrFields" => ["withCredentials" => true]
			];
		}else{
			$js_options["*data"] = $options["data"];
		}

		$buffer->script(["*" => "
			var $js_id;	
			$(function() {
				$js_id = $('#{$js_id}').magicSuggest(". \LiquidedgeApp\Octoapp\app\app\js\js::create_options($js_options).");
				
				{$js_id}.setValue(".json_encode($options["value"]).");
				
				$($js_id).on('selectionchange', function(e,m){
					let onchange = {$options["!change"]}
					onchange.apply(this, [e,m]);
				});
				
				
			});
		"]);

		return $buffer->build();
	}
	//--------------------------------------------------------------------------------
}