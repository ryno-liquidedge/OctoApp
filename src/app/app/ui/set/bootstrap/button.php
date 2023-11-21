<?php

namespace LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap;

/**
 * Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class button extends \com\ui\intf\element {
	//--------------------------------------------------------------------------------
	// fields
	//--------------------------------------------------------------------------------
	protected static $is_singleton = true;
	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	protected function __construct($options = []) {
		// init
		$this->name = "Button";
	}
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	public function build($options = []) {
		// public static function button($label, $onclick = false, $options = []) {

		// options
		$options = array_merge([
			"label" => false,
			"onclick" => false,

			"@id" => false,
			"@disabled" => false,

			"caret" => false,
			"confirm" => false,
			"icon" => false,
			"icon_right" => false,
			"/icon" => [],
			"tooltip" => false,
			"msgdisabled" => false,
			"selected" => false,
			"badge" => false,
			"no_ucfirst" => false,
		], $options);

		// init
		$label = $options["label"];
		$onclick = $options["onclick"];
		$has_label = (bool)$label;

		// short code onclick
		if ($label !== false && $onclick === false) {
			switch ($label) {
				case "back"	:
				case "cancel" :
				case "return" :
					$onclick = \core::$panel.".back();";
					break;

				case "print" : $onclick = "window.print();"; break;
				case "refresh" : $onclick = \core::$panel.".refresh();"; break;

				case "close" :
					$onclick = "if ($('#popup_content').length) core.browser.close_popup(); else window.close();";
					break;

				case "download" :
					$csrf = \core::$app->get_response()->get_csrf();
					$form_id = \com\ui\helper::$current_form->id_form;
					$onclick = "core.overlay.show(); setTimeout(function() { core.ajax.request('?c=index/xvoid', { csrf: '{$csrf}', complete: function() { core.overlay.hide(); } }); }, 250); $('#{$form_id}').removeAttr('onsubmit').submit().attr('onsubmit', 'return false;');";
					break;
			}
		}

		// short code icon
		if (!$options["icon"]) {
			switch ($label) {
				case "back"	:
				case "cancel" :
				case "return" :
					$options["icon"] = "chevron-left";
					break;

				case "print" :
					$options["icon"] = "print";
					break;

				case "refresh" :
					$options["icon"] = "refresh";
					break;

				case "close" :
					$options["icon"] = "remove";
					break;

				case "download" :
					$options["icon"] = "download";
					break;
			}
		}

		// label
		if (!$options["no_ucfirst"]) {
			$label = ucfirst($label);
		}

		// disabled message
		if ($options["msgdisabled"] && $options["msgdisabled"] !== true) {
			$options["msgdisabled"] = \com\arr::splat($options["msgdisabled"]);
			$options["@disabled"] = true;
			$options["tooltip"] = $options["msgdisabled"];
		}

		// disabled
  		if ($options["@disabled"]) {
  			$onclick = false;
			$options["selected"] = false;
  		}

		// onclick
		if ($onclick instanceof \com\ui\intf\dropdown) {
			$options["@data-bs-toggle"] = "dropdown";
			if (!$options["icon_right"]) $options["caret"] = true;
			$options["@aria-haspopup"] = "true";
			$options["@aria-expanded"] = "false";
			if (!isset($options["@data-boundary"])) $options["@data-boundary"] = "viewport";
		}
  		elseif ($onclick instanceof \app\ui\set\bootstrap\offcanvas) {

			$options["@data-bs-toggle"] = "offcanvas";
			$options["@href"] = "#{$onclick->get_id()}";
			$options["@role"] = "button";
			$options["@aria-controls"] = $onclick->get_id();
			$options["@data-bs-target"] = "#{$onclick->get_id()}";

		}elseif ($onclick instanceof \app\ui\set\bootstrap\modal) {
			$options[".btn-modal"] = true;
			$options["@data-bs-toggle"] = "modal";
			$options["@data-bs-target"] = "#{$onclick->get_id()}";

			if (!$options["icon_right"]) $options["caret"] = true;

			// onclick
  		}elseif ($onclick) {
			// confirm
			if ($options["confirm"]) {
				$onclick_text = ($options["confirm"] === true ? "Are you sure you want to continue?" : strtr($options["confirm"], ["'" => "\\'"]));
				$onclick = "core.browser.confirm('{$onclick_text}', function() { $onclick });";
			}

			// onclick
			$options["!click"] = $onclick;
			$options["@data-loading-text"] = "{$label} ...";
  		}

		// tooltip
 		if ($options["tooltip"]) {
 			if (is_array($options["tooltip"])) $options["tooltip"] = implode("<br />", $options["tooltip"]);
 			if (!$options["@id"]) $options["@id"] = \com\session::$current->session_uid;
			$options["@title"] = $options["tooltip"];

			\com\ui::make()->tooltip();
 		}

		// html
		$html = \com\ui::make()->buffer();

		// default icons
		if (!$options["icon"]) {
			if (substr($label, 0, 2) == "< ") {
				$options["icon"] = "chevron-left";
				$label = substr($label, 2);
			}
		}

		// button options
		$options[".btn"] = true;
		$options["@type"] = "button"; // prevents forms in IE triggering submit on enter keypress in input field

		// disabled
  		if ($options["@disabled"]) {
			$options[".disabled"] = true;
  		}
		else $options[".disabled"] = false;

		// selected
        $use_default = true;
        foreach (\app\ui::$bootstrap_color_arr as $color){

            if(\app\arr::arr_contains_signature_item(".btn-{$color}", $options) || \app\arr::arr_contains_signature_item(".btn-outline-{$color}", $options)) {
                $use_default = false;
                break;
            }
        }

		if($use_default) $options[".btn-primary"] = true;
		if ($options["selected"]) $options[".active"] = true;

		// tooltip on disabled button
		$tooltip_fix = false;
		if ($options[".disabled"] && $options["tooltip"]) {
			$tooltip_fix = true;
			$html->div_(".d-flex", [".disabled"=>true, "@title" => $options["tooltip"], "@tabindex" => 0, "@data-toggle" => "tooltip"]);
			$options["tooltip"] = false;
			$options["#pointer-events"] = "none";
		}

		// view
		$html->button_(false, $options);

			// caret
			if (!$label && $options["caret"]) {
				$html->span(".caret");
			}

			// icon
			if ($options["icon"]) {
				$options["/icon"] = array_merge([
					".me-2" => $has_label,
					"space" => (bool)$label,
				], $options["/icon"]);
				$html->xicon($options["icon"], $options["/icon"]);
			}

			// label
			$html->add($label);

			// badge
			if ($options["badge"] !== false) {
				if (!($options["badge"] instanceof \com\ui\intf\element)) {
					$options["badge"] = \com\ui::make()->badge($options["badge"], "dark", [".ms-2" => true]);
				}
				$html->add($options["badge"]);
			}

			// icon
			if ($options["icon_right"]) {
				$options["/icon"] = array_merge([
					".ms-2" => $has_label,
				], $options["/icon"]);
				$html->content(" ")->xicon($options["icon_right"], $options["/icon"]);
			}

			// caret
			if ($label && $options["caret"]) {
				$html->content(" ")->span(".caret");
			}

		$html->_button();

		// tooltip on disabled button
		if ($tooltip_fix && $options["tooltip"]) {
			$html->_div();
		}

		// dropdown
		if ($onclick instanceof \com\ui\intf\dropdown) {
			$onclick->set_trigger($html->get_clean());
			return $onclick->get();
		}else if($onclick instanceof \app\ui\set\bootstrap\modal){
			$onclick->set_trigger($html->get_clean());
			return $onclick->build();
		}else if ($onclick instanceof \app\ui\set\bootstrap\offcanvas) {
			$onclick->set_trigger($html->get_clean());
			return $onclick->build();
		}

		// done
		return $html->get_clean();
	}
	//--------------------------------------------------------------------------------
}