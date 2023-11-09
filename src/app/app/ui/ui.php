<?php

namespace LiquidedgeApp\Octoapp\app\app\ui;

/**
 * @package app
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */

class ui extends \com\ui {

    public static $bootstrap_color_arr = [
        "blue" => "blue",
        "indigo" => "indigo",
        "purple" => "purple",
        "pink" => "pink",
        "red" => "red",
        "orange" => "orange",
        "yellow" => "yellow",
        "green" => "green",
        "teal" => "teal",
        "cyan" => "cyan",
        "white" => "white",
        "gray" => "gray",
        "gray-dark" => "gray-dark",
        "primary" => "primary",
        "primary-dark" => "primary-dark",
        "secondary" => "secondary",
        "success" => "success",
        "info" => "info",
        "warning" => "warning",
        "danger" => "danger",
        "light" => "light",
        "dark" => "dark",
    ];

    public static $bootstrap_theme_arr = [
        "primary",
		"primary-dark",
		"secondary",
		"success",
		"info",
		"warning",
		"danger",
		"light",
		"dark",
    ];

    //--------------------------------------------------------------------------------
	/**
	 * @param array $options
	 * @return string
	 */
	public function loader($options = []) {
	    return \LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap\loader::make()->build($options);
	}
	//--------------------------------------------------------------------------------
    /**
     * @param bool $id
     * @param array $options
     * @return set\bootstrap\form2_buffer
     */
	public function form2_buffer($id = false, $options = []) {
		// options
  		$options["id"] = $id;

		// done
		return \LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap\form2_buffer::make($options);
	}
	//--------------------------------------------------------------------------------
    /**
     * @param array $options
     * @return set\bootstrap\html_buffer
     */
	public function html_buffer($options = []) {
		return \LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap\html_buffer::make($options);
	}
	//--------------------------------------------------------------------------------

	/**
	 * @param $text
	 * @param string $color
	 * @param false $icon
	 * @param array $options
	 * @return string
	 */
	public function docket($text, $icon = false, $color = "danger", $options = []) {

	    $options["text"] = $text;
	    $options["color"] = $color;
	    $options["icon"] = $icon;

	    return \LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap\docket::make()->build($options);
	}
	//--------------------------------------------------------------------------------
	public function share_btn_group($options = []) {

	    return \LiquidedgeApp\Octoapp\app\app\ui\set\custom\share_btn_group::make()->build($options);
	}
	//--------------------------------------------------------------------------------

	/**
	 * @param $text
	 * @param array $options
	 * @return mixed
	 */
	public function sup($text, $options = []) {

		$options = array_merge([
		    "pre_text" => false,
        	"/pre_text" => [],

        	"text" => $text,

        	"post_text" => false,
        	"/tepost_textxt" => [],
		], $options);

	    return \LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap\sup::make()->build($options);
	}
	//--------------------------------------------------------------------------------
	public function iselect_multi($id, $value_option_arr, $value = false, $label = false, $options = []) {
		// options
  		$options["id"] = $id;
  		$options["value"] = $value;
  		$options["label"] = $label;
  		$options["value_option_arr"] = $value_option_arr;

		// done
		return \LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap\iselect_multi::make()->build($options);
	}
	//--------------------------------------------------------------------------------
    /**
     * @param bool $url
     * @param array $options
     * @return set\bootstrap\panel_buffer|\com\ui\intf\panel
     */
	public function panel_buffer($url = false, $options = []) {

		$options = array_merge([
		    "id" => false,
		    "html" => false,
		    "url" => $url,
		], $options);

		if($options["html"]){
			unset($options["url"]);
			$panel_buffer = \LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap\panel_buffer::make($options);
			$panel_buffer->add_url($url);
			$panel_buffer = $panel_buffer->build_with_html($options["html"]);
			$panel_buffer->start_index = false;

		}else{
			$panel_buffer = \LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap\panel_buffer::make($options);
		}

		return $panel_buffer;

	}
	//--------------------------------------------------------------------------------

	/**
	 * @param array $options
	 * @return set\bootstrap\legend|\com\intf\standard
	 */
	public function legend($options = []) {

		return \LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap\legend::make($options);
	}
	//--------------------------------------------------------------------------------
    /**
     * @param $id
     * @param array $options
     * @return string
     */
	public function honeypot($id, $options = []) {

	    $options = array_merge([
	        "id" => $id
	    ], $options);

		return \LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap\honeypot::make()->build($options);
	}
	//--------------------------------------------------------------------------------

    /**
     * @param $src
     * @param array $options
     * @return string
     */
	public function video($src, $options = []) {

	    $options = array_merge([
	        "@src" => $src
	    ], $options);

		return \LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap\video::make()->build($options);
	}
	//--------------------------------------------------------------------------------
	/**
	 * @param $src
	 * @param false $html
	 * @param array $options
	 * @return mixed
	 */
	public function parallax($src, $html = false, $options = []) {

		$options = array_merge([
		    "src" => $src,
		    "html" => $html,
		], $options);

		return \LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap\parallax::make()->build($options);
	}
	//--------------------------------------------------------------------------------
	public function ilabel($id, $value, $onclick, $options = []) {

		$options = array_merge([
		    "id" => $id,
		    "value" => $value,
		    "!click" => $onclick,
		], $options);

		return \LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap\ilabel::make()->build($options);
	}
	//--------------------------------------------------------------------------------
    /**
     * @param array $options
     * @return set\bootstrap\fancybox
     */
	public function fancybox($options = []) {

		return \LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap\fancybox::make($options);
	}
	//--------------------------------------------------------------------------------
    /**
     * @param array $options
     * @return set\bootstrap\image_card
     */
	public function image_card($options = []) {

		return \LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap\image_card::make($options);
	}
	//--------------------------------------------------------------------------------
	/**
	 * @param array $options
	 * @return set\bootstrap\card|\com\intf\standard
	 */
	public function card($options = []) {
		return \LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap\card::make($options);
	}
	//--------------------------------------------------------------------------------
	public function fieldset($header, $fn, $options = []) {

	    $options = array_merge([
	        "dropdown" => false
	    ], $options);

	    $fieldset = \LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap\fieldset::make();
	    $fieldset->header($header);
	    $fieldset->body($fn);
	    $fieldset->set_dropdown($options["dropdown"]);

		return $fieldset->build($options);
	}
	//--------------------------------------------------------------------------------
	/**
	 * @param array $options
	 * @return set\bootstrap\card_custom|\com\intf\standard
	 */
	public function card_custom($options = []) {
		return \LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap\card_custom::make($options);
	}
	//--------------------------------------------------------------------------------
    /**
     * @param array $options
     * @return set\custom\fancybox_manage
     */
	public function fancybox_manage($options = []) {

		return \LiquidedgeApp\Octoapp\app\app\ui\set\custom\fancybox_manage::make($options);
	}
	//--------------------------------------------------------------------------------
	public function div($options = []) {
		return \LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap\div::make()->build($options);
	}
	//--------------------------------------------------------------------------------
	public function ifile_single($label, $id, $folder, $filetype_group = 0, $file_item = false, $options = []) {

	    $options = array_merge([
	        "transparent" => false,
            "wrapper_height" => "300px",
			"wrapper_width" => "100%",
			"object_fit" => "cover",

            "max_files" => 5,
			"max_filesize" => 5,
            "note" => true,
			"/note" => [],
			"/img" => [],

	    ], $options);

	    $buffer = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();
	    if($file_item){
	        $options = array_merge([
	            "/" => [],
	            "/card" => [
	                ".bg-primary" => true,
	                ".text-white" => true,
                ],
	            "max_files" => 1,
	            "!crop" => "$('.replace-logo-card-{$id}').addClass('hover')",
                "!delete" => "function(file){ $('.replace-logo-card-{$id}').removeClass('hover'); }",
	        ], $options);

	        $options["/card"][".replace-logo-card-{$id}"] = true;
	        $options["/card"][".replace-logo-card"] = true;
	        $options["/card"][".from-b"] = true;
	        $options["/card"]["opacity"] = 100;

	        $options["/img"]["#height"] = $options["wrapper_height"];
	        $options["/img"]["#width"] = $options["wrapper_width"];
	        $options["/img"]["#object-fit"] = $options["object_fit"];

            $buffer->add(\LiquidedgeApp\Octoapp\app\app\ui\ui::make()->image_card()
                ->set_src(\LiquidedgeApp\Octoapp\app\app\http\http::get_stream_url($file_item), $options["/img"])
                ->set_hover_content(function() use($label, $id, $folder, $filetype_group, $options){
                    $options["wrapper_height"] = ((\LiquidedgeApp\Octoapp\app\app\str\str::strip_alpha($options["wrapper_height"]) / 100) * 80)."px";
                    $options["note"] = false;
                    return \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->ifile($label, $id, $folder, $filetype_group, false, $options);

                }, $options["/card"])
                ->build([".bg-dark" => $options["transparent"]])
            );

            $note_arr = [];
            if($options["max_files"] > 1) $note_arr[] = "Upload queue has been limited to {$options["max_files"]} files.";
            if($options["max_filesize"]) $note_arr[] = "Max file size: {$options["max_filesize"]}mb";

            if($note_arr) {
                $buffer->div_([".mt-2 fs-7" => true]);
                    $buffer->xnote(implode(" ", $note_arr), array_merge([".mb-3" => false], $options["/note"]));
                $buffer->_div();
            }

        }else{
            $buffer->xifile($label, $id, $folder, $filetype_group, false, $options);
        }

	    return $buffer->build();

    }
	//--------------------------------------------------------------------------------
	public function ifile($label, $id, $folder, $filetype_group = 0, $value = false, $options = []) {

		$options = array_merge([
		    "label" => $label,
		    "filetype_group" => $filetype_group,
			"value" => $value,

			"crop" => false,
			"!crop" => false,
			"crop_width" => 800,
			"crop_height" => 400,

			"max_files" => 5,
			"!complete" => "function(file, response){}",
			"!delete" => "function(file){}",

		], $options);

		return $this->idropzone($id, $folder, $options);
	}
	//--------------------------------------------------------------------------------
	public function advertisement_widget($adv_location, $options = []) {
		// options
  		$options["adv_location"] = $adv_location;

		return \LiquidedgeApp\Octoapp\app\app\ui\set\custom\advertisement_widget::make()->build($options);
	}
	//--------------------------------------------------------------------------------
	public function icolor($id, $value = false, $label = false, $options = []) {

		// options
  		$options["id"] = $id;
  		$options["value"] = $value;
  		$options["label"] = $label;

		return \LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap\icolor::make()->build($options);
	}
	//--------------------------------------------------------------------------------
	public function itime($id, $value = false, $label = false, $options = []) {

		// options
  		$options["id"] = $id;
  		$options["value"] = $value;
  		$options["label"] = $label;

		return \LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap\itime::make()->build($options);
	}
	//--------------------------------------------------------------------------------
	public function iswitch($id, $value_option_arr, $value = false, $label = false, $options = []) {
	    $value_option_arr = (bool) $value_option_arr;
	    $value = (bool) $value;
        return parent::iswitch($id, $value_option_arr, $value, $label, $options);
    }

    //--------------------------------------------------------------------------------
	public function idropzone($id, $folder, $options = []) {

		$options = array_merge([
			"filetype_group" => 0,

			"crop" => false,
			"!crop" => false,
			"crop_width" => 800,
			"crop_height" => 400,

			"max_files" => 5,
			"max_filesize" => 5,
			"!complete" => "function(file, response){}",
			"!delete" => "function(file){}",
		], $options);

		// options
		$options["id"] = $id;
		$options["folder"] = $folder;

		// done
		return  \LiquidedgeApp\Octoapp\app\app\inc\dropzone\dropzone::make(["id" => $id])->build($options);
	}
	//--------------------------------------------------------------------------------
	public function note($note_arr, $options = []) {
		// options
  		$options["note_arr"] = $note_arr;

		// done
		return $this->set->get("note")->build($options);
	}
	//--------------------------------------------------------------------------------

	/**
	 * @param $id
	 * @param array $options
	 * @return set\bootstrap\map
	 */
	public function gmap($id, $options = []) {

		$options["id"] = $id;

		$map = \LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap\map::make($options);

        $map->add_custom_icon("standard", \LiquidedgeApp\Octoapp\app\app\http\http::get_stream_url(\core::$folders->get_root_files()."/img/custom-marker.png", ["absolute" => true]));
        $map->add_custom_icon("standard_hover", \LiquidedgeApp\Octoapp\app\app\http\http::get_stream_url(\core::$folders->get_root_files()."/img/custom-marker-hover.png", ["absolute" => true]));

        return $map;
	}
	//--------------------------------------------------------------------------------

	/**
	 * @param array $options
	 * @return set\bootstrap\map_static|\com\intf\standard
	 */
	public function gmap_static($options = []) {
		return \LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap\map_static::make($options);
	}
	//--------------------------------------------------------------------------------
    /**
     * @param $html
     * @param string $width
     * @param string $height
     * @param array $options
     * @return mixed
     */
	public function gmap_code($html, $width = "100%", $height = "350px", $options = []) {
	    $options = array_merge([
	        "html" => $html,
			"width" => $width,
			"height" => $height,
	    ], $options);
		return \LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap\map_code::make()->build($options);
	}
	//--------------------------------------------------------------------------------

	/**
	 * @param array $options
	 * @return set\bootstrap\carousel|\com\intf\standard
	 */
	public function carousel($options = []) {

		return \LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap\carousel::make($options);
	}
	//--------------------------------------------------------------------------------

	/**
	 * @param $width
	 * @param $height
	 * @param array $options
	 * @return set\bootstrap\flip_card|\com\intf\standard
	 */
	public function flip_card($width, $height, $options = []) {

		$options = array_merge([
		    "width" => $width,
			"height" => $height,
		], $options);

		return \LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap\flip_card::make($options);
	}
	//--------------------------------------------------------------------------------

    /**
     * @param array $options
     * @return set\bootstrap\collapse|\com\intf\standard
     */
	public function collapse($options = []) {

		return \LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap\collapse::make($options);
	}
	//--------------------------------------------------------------------------------

	/**
	 * @param $amount
	 * @param array $options
	 * @return string
	 */
	public function price($amount, $options = []) {

		$options["amount"] = $amount;

		return \LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap\price::make()->build($options);
	}
	//--------------------------------------------------------------------------------

	/**
	 * @param $text
	 * @param array $options
	 * @return mixed
	 */
	public function text($text, $options = []) {

		$options["text"] = $text;
		$options[".fs-7 font-weight-300"] = true;

		return \LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap\text::make()->build($options);
	}
	//--------------------------------------------------------------------------------
	/**
	 * @param $id
	 * @param int $value
	 * @param array $options
	 * @return false|string|null
	 */
	public function icounter($id, $value = 0, $options = []) {

		$options = array_merge([
		    "id" => $id,
		    "value" => $value,
		    "!change" => false,
			"min" => 0,
			"max" => null,
		], $options);

	    return \LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap\icounter::make()->build($options);
	}
	//--------------------------------------------------------------------------------

    /**
     * @param array $options
     * @return set\bootstrap\menu_left|\com\intf\standard
     */
	public function menu_left($options = []) {
	    return \LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap\menu_left::make($options);
    }

	//--------------------------------------------------------------------------------

	/**
	 * @param $text
	 * @param array $options
	 * @return bool|string
	 */
	public function readmore($text, $options = []) {

		$options = array_merge([
			"length" => 200,
		], $options);

		$readmore = \LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap\readmore::make();
		$readmore->set_text($text);
		$readmore->set_length($options["length"]);

		return $readmore->build($options);
	}
	//--------------------------------------------------------------------------------
	/**
	 * @param $url
	 * @param $html
	 * @param array $options
	 * @return set\bootstrap\panel_buffer|\com\ui\intf\panel
	 */
	public function panel_buffer_with_html($url, $html, $options = []) {

		$options = array_merge([
			"id" => false,
		], $options);

		// options
		$options["url"] = $url;

		$panel_buffer = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->panel_buffer(false, $options);
		$panel_buffer->add_url($url);
		return $panel_buffer->build_with_html($html);
	}
	//--------------------------------------------------------------------------------

	/**
	 * @param array $options
	 * @return set\bootstrap\scrolltotop
	 */
	public function scrolltotop($options = []) {

		return \LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap\scrolltotop::make($options);
	}
	//--------------------------------------------------------------------------------

	/**
	 * @param $stream
	 * @param array $options
	 * @return \LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap\tab_static
	 */
	public function tab_static($options = []) {

		return \LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap\tab_static::make($options);
	}
	//--------------------------------------------------------------------------------
	/**
	 * @param array $options
	 * @return set\bootstrap\ul|\com\intf\standard
	 */
	public function ul($options = []) {

		$options_arr = array_merge([
		], $options);

		return \LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap\ul::make();
	}
	//--------------------------------------------------------------------------------

	/**
	 * @param array $options
	 * @return string
	 */
	public function pagination($options = []) {

		$options = array_merge([
		    "total" => 200,
		    "page" => 1,
		    "maxVisible" => 5,
			"!click" => "function(page){}",
		], $options);

		return \LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap\pagination::make()->build($options);
	}
    //--------------------------------------------------------------------------------

	/**
	 * @param $id
	 * @param array $options
	 * @return set\bootstrap\calendar|\com\intf\standard
	 */
	public function calendar($id, $options = []) {

		$options = array_merge([
			"id" => $id
		], $options);

		return \LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap\calendar::make($options);
	}
    //--------------------------------------------------------------------------------
	public function modal($options = []) {

		$options = array_merge([
		], $options);

		return \LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap\modal::make($options);
	}
    //--------------------------------------------------------------------------------

	/**
	 * @param false $id
	 * @param array $options
	 * @return \com\ui\intf\table | \LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap\table
	 */
	public function table($id = false, $options = []) {
		return parent::table($id, $options);
	}
	//--------------------------------------------------------------------------------
	/**
	 * @param array $options
	 * @return set\bootstrap\dropdown|\com\intf\standard
	 */
	public function dropdown($options = []) {
		return \LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap\dropdown::make($options);
	}
	//--------------------------------------------------------------------------------
	/**
	 * @param array $options
	 * @return string
	 */
	public function dropdown_email($email, $label = false, $icon = false, $options = []) {

		if (!$label) $label = $email;

		$options["title"] = $label;
		$options["email"] = $email;
		$options["icon"] = $icon;

		return \LiquidedgeApp\Octoapp\app\app\ui\set\custom\dropdown_email::make()->build($options);
	}
	//--------------------------------------------------------------------------------
	public function icon_toggle_password(array $target_arr, $options = []) {

		$options["@data-target"] = implode(",", $target_arr);

	    return \LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap\icon_toggle_password::make()->build($options);
	}
	//--------------------------------------------------------------------------------
	public function mailchimp_signup($options = []) {

		$options = array_merge([
		    "action" => \db_settings::get_value(SETTING_MAILCHIMP_FORM_ACTION),
		    "honeypot" => \db_settings::get_value(SETTING_MAILCHIMP_HONEYPOT_ID),
		], $options);

	    return \LiquidedgeApp\Octoapp\app\app\ui\set\custom\mailchimp_signup::make()->build($options);
	}
	//--------------------------------------------------------------------------------
    /**
     * @param $icon
     * @param array $options
     * @return string
     */
    public function icon_block($icon, $options = []) {
	    $options = array_merge([
	        "icon" => $icon,
	        "color" => "green",
			"background" => "white",
	    ], $options);

		return \LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap\icon_block::make()->build($options);
    }
	//--------------------------------------------------------------------------------

    /**
     * @param $id
     * @param $value
     * @param $label
     * @param $data_type
     * @param array $options
     * @return string
     */
    public function input_by_type($id, $value, $label, $data_type, $options = []) {
	    $options = array_merge([
	        "id" => $id,
			"label" => $label,
			"value" => $value,
			"value_arr" => [],
			"type" => $data_type,
	    ], $options);

		return \LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap\input_by_type::make()->build($options);
    }
	//--------------------------------------------------------------------------------

	/**
	 * @param $id
	 * @param false $value
	 * @param array $options
	 * @return mixed
	 */
    public function icombobox($id, $value = false, $options = []) {
	    $options = array_merge([
	        "id" => $id,
	        "value" => $value,
	        "data" => [],
	    ], $options);

		return \LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap\icombobox::make()->build($options);
    }
	//--------------------------------------------------------------------------------

    public function dropdown_theme_selector($options = []) {
		return \LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap\dropdown_theme_selector::make()->build($options);
    }
	//--------------------------------------------------------------------------------

	/**
	 * @param $text
	 * @param false $label
	 * @param array $options
	 * @return string
	 */
	public function copy_text($text, $label = false, $options = []) {

		$options["text"] = $text;
		$options["label"] = $label;

		// done
		return \LiquidedgeApp\Octoapp\app\app\ui\set\custom\copy_text::make()->build($options);
	}
	//--------------------------------------------------------------------------------
    /**
     * @param $url
     * @param array $options
     * @return string
     */
	public function js_popup($url, $options = []) {

	    $options = array_merge([
	        "url" => $url,
            "fullscreen" => false,
            "panel" => \core::$app->get_request()->get_panel(),

            "*class" => null,
            "*title" => "Alert",
            "*hide_header" => false,
            "*hide_footer" => true,
            "*closable" => true,
            "*backdrop" => "static", // true | false | 'static'

            "*width" => "modal-lg",
            "background" => "bg-white",
            "*height_class" => "min-h-400px",
            "*loading_content" => false,

	    ], $options);

	    $options["*height_class"] .= " {$options["background"]}";
	    if(!is_string($options["*loading_content"]) && is_callable($options["*loading_content"])) $options["*loading_content"] = $options["*loading_content"]();
	    if(!$options["*loading_content"]) $options["*loading_content"] .= "<div class='{$options["*height_class"]} bg-white'></div>";

	    return \LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap\js_popup::make()->build($options);
	}
    //--------------------------------------------------------------------------------
    /**
     * @param array $options
     * @return set\bootstrap\iupload|\com\intf\standard
     */
	public function iupload($options = []) {
		return \LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap\iupload::make($options);
	}
	//--------------------------------------------------------------------------------
    /**
     * @param array $options
     * @return set\bootstrap\breadcrumb|\com\intf\standard
     */
	public function breadcrumb($options = []) {
		return \LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap\breadcrumb::make($options);
	}
	//--------------------------------------------------------------------------------

    /**
     * @param $data
     * @param array $options
     * @return set\custom\property_card|\com\intf\standard
     */
	public function property_card($data, $options = []) {

	    $options = array_merge([
	        "data" => $data
	    ], $options);

		return \LiquidedgeApp\Octoapp\app\app\ui\set\custom\property_card::make()->build($options);
	}
	//--------------------------------------------------------------------------------

    /**
     * @param $data
     * @param array $options
     * @return set\custom\newsletter_card|\com\intf\standard
     */
	public function newsletter_card($data, $options = []) {

	    $options = array_merge([
	        "data" => $data
	    ], $options);

		return \LiquidedgeApp\Octoapp\app\app\ui\set\custom\newsletter_card::make()->build($options);
	}
	//--------------------------------------------------------------------------------

    /**
     * @param $id
     * @param array $options
     * @return mixed
     */
	public function irange($id, $options = []) {

		$options = array_merge([
		    "id" => $id,
		    "value_from" => 0,
		    "value_to" => 1000,
		    "min" => 0,
		    "max" => 1000,
            "step" => 1,
            "/" => [],
		], $options);

		return \LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap\irange::make()->build($options);

	}
	//--------------------------------------------------------------------------------

    /**
     * @param array $options
     * @return string|void
     */
	public function category_filter($options = []) {

		return \LiquidedgeApp\Octoapp\app\app\ui\set\custom\category_filter::make()->build($options);

	}
	//--------------------------------------------------------------------------------

    /**
     * @param array $options
     * @return string|void
     */
	public function newsletter_filter($options = []) {

		return \LiquidedgeApp\Octoapp\app\app\ui\set\custom\newsletter_filter::make()->build($options);

	}
	//--------------------------------------------------------------------------------

    /**
     * @param array $options
     * @return string|void
     */
	public function search_bar($options = []): string {

		return \LiquidedgeApp\Octoapp\app\app\ui\set\custom\search_bar::make()->build($options);

	}
	//--------------------------------------------------------------------------------
	/**
	 * @return \LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap\navbar
	 */
	public function navbar($options = []) {
		return $this->set->get("navbar", $options);
	}
	//--------------------------------------------------------------------------------

    /**
     * @param $key
     * @param bool $value
     * @param array $options
     * @return string
     */
	public function isetting($key, $options = []) {

	    $options = array_merge([
			"key" => $key,
			"value" => false,
			"label" => false,
			"required" => false,
		], $options);

		return \LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap\isetting::make()->build($options);
	}
	//--------------------------------------------------------------------------------

    /**
     * @param $key
     * @param bool $value
     * @param array $options
     * @return string
     */
	public function iproperty($dbentry, $key, $options = []) {

	    $options = array_merge([
			"dbentry" => $dbentry,
			"key" => $key,
			"value" => false,
			"label" => false,
			"required" => false,
		], $options);

		return \LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap\iproperty::make()->build($options);
	}
	//--------------------------------------------------------------------------------

    /**
     * @param $link
     * @param array $options
     * @return string
     */
	public function youtube($link, $options = []) {

	    $options = array_merge([
			"link" => $link,
			"width" => "100%",
            "height" => "400px",
		], $options);

		return \LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap\youtube::make()->build($options);
	}
	//--------------------------------------------------------------------------------
}