<?php

namespace LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap;

/**
 * @package app\ui\set\bootstrap
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class icon extends \com\ui\intf\element {
	//--------------------------------------------------------------------------------
	// fields
	//--------------------------------------------------------------------------------
	protected static $is_singleton = true;

	protected $options = null;
	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	protected function __construct() {
		$this->name = "Icon";
	}
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	public function build($options = []) {
		// options
		$options = array_merge([
			"icon" => false,
			"space" => false, // !remove
			".me-2" => true,
		], $options);

		// icon and style
		$icon = $options["icon"];
		$icon_style = false;
		$wrapper_options = [];

		// glyphicon mappings -- !remove
		$map_arr = [
			"pencil" => "fas-pencil-alt",
			"stats" => "fas-chart-line",
			"remove" => "fas-times",
			"refresh" => "fas-sync",
			"floppy-disk" => "fas-save",
			"resize-vertical" => "fas-bars",
			"send" => "fas-share-square",
			"earphone" => "fas-phone-square",
			"off" => "fas-sign-out-alt",
			"ok" => "fas-check",
			"pushpin" => "fas-thumbtack",
			"question-sign" => "fas-question-circle",
			"tower" => "fas-lock",
			"usd" => "fas-dollar-sign",
			"export" => "fas-file-download",
			"ban-circle" => "fas-ban",
			"flash" => "fas-bell",
			"fa-hand-o-right" => "fas-hand-holding-usd",
			"phone-alt" => "fas-phone",
			"fa-pencil-square-o" => "fas-signature",
			"fa-money" => "fas-coins",
			"picture" => "fas-image",
			"collapse-down" => "fas-caret-down",
			"expand" => "fas-caret-right",
			"download-alt" => "fas-file-download",
			"warning-sign" => "fas-exclamation-triangle",
			"fa-arrows" => "fas-bars",
			"admin" => "fas-shield-alt",
			"zoom-in" => "fas-eye",
			"fullscreen" => "fas-expand",
			"floppy-save" => "fas-save",
			"fa-mail-reply" => "fas-reply",
			"fa-mail-reply-all" => "fas-reply-all",
			"fa-mail-forward" => "fas-forward",
			"plus-sign" => "fas-plus-circle",
			"minus-sign" => "fas-minus-circle",
			"fa-file-image-o" => "fas-file-image",
			"fa-tags" => "fas-tags",
			"clock" => "fas-clock",
			"time" => "fas-stopwatch",
			"euro" => "fas-euro-sign",
			"ok-circle" => "fas-check-circle",
			"remove-circle" => "fas-times-circle",
			"ok-sign" => "fas-check-square",
			"remove-sign" => "fas-window-close",
			"fa-cloud-download" => "fas-cloud-download-alt",
			"info-sign" => "fas-info-circle",
			"transfer" => "fas-exchange-alt",
			"exclamation-sign" => "fas-exclamation-circle",
			"import" => "fas-file-import",
			"folder-close" => "fas-folder",
			"indent-left" => "fas-indent",
			"log-out" => "fas-sign-out-alt",
			"dashboard" => "fas-tachometer-alt",
			"fa-stack-exchange" => "layer-group",
			"fa-file-excel-o" => "fas-file-excel",
			"new-window" => "external-link-alt",
			"fa-line-chart" => "fa-chart-line",
			"resize-full" => "fas-arrows-alt-h",
			"fa-refresh" => "fas-sync-alt",
			"fa-exchange" => "fas-exchange-alt",
		];
		if (isset($map_arr[$icon])) $icon = $map_arr[$icon];

		// templates
		switch ($icon) {
			case ":ok" :
				$icon = "fas-check";
				$options[".text-success"] = true;
				break;

			case ":notok" :
				$icon = "fas-times";
				$options[".text-danger"] = true;
				break;
		}

		// find the style that was used
		$style_map_arr = [
		    "fab-" => ".fab .fa-",
			"fas-" => ".fas .fa-",
			"fal-" => ".fal .fa-",
			"far-" => ".far .fa-",
			"fa-" => ".fas .fa-",
//			"(.)" => ".fas .fa-",
		];
		foreach ($style_map_arr as $style_map_index => $style_map_item) {
			if (!preg_match("/^{$style_map_index}/i",$icon)) continue;

			$icon_style = $style_map_item;
			$icon = preg_replace("/^{$style_map_index}/i", "\\1", $icon);
		}

		if(isset($options[".fab"]) || strpos($icon, ".fab") !== false){
		    $icon_style = ".fab .fa-";
        }

		if(!$icon_style) $icon_style = ".fas .fa-";

		// no icon
		if (!$icon) {
			return "";
		}
		
		// tooltip
		if (!empty($options["@title"])) {
			$wrapper_options["@title"] = $options["@title"];
			unset($options["@title"]);
		}
		
		// float
		if (!empty($options[".float-right"])) {
			$wrapper_options[".float-right"] = $options[".float-right"];
			unset($options[".float-right"]);
		}

		// html
		$html = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();
		$html->span_($wrapper_options);
		{
			$html->span("{$icon_style}{$icon}", $options);
		}
		$html->_span();
		return $html->get_clean();
	}
	//--------------------------------------------------------------------------------
}