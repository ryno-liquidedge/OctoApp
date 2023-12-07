<?php

namespace LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap;

/**
 * Class html
 * @package app\ui\set\bootstrap
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */

class html_buffer extends \com\ui\set\bootstrap\html {
	//--------------------------------------------------------------------------------
	// properties
	//--------------------------------------------------------------------------------
	/**
	 * @var \com\ui\intf\grid
	 */
	protected $grid = null;
	/**
	 * @var \com\ui
	 */
	protected $ui = null;
	/**
	 * @var \com\ui\intf\tag
	 */
	protected $tag = null;

    /**
     * @var \com\ui\intf\buffer|null
     */
	protected $buffer = null;
	/**
	 * Toolbar component
	 * @var \com\ui\intf\toolbar
	 */
	public $toolbar = false;
	/**
	 * Menu component
	 * @var \com\ui\intf\menu
	 */
	public $menu = false;
	public $menu_disable_collapse = false;
	public $menu_disable_headers = true;

	public $wrapper = false; // is the containing div added

    public $id = false; // unique key used for wrapper block
    public $header_width = false; // field header width
    protected $label_width = false; // field label width
    protected $label_col = false; // field col size 1-12
	public $ignore_columns = false; // when set to true, no column functions will be executed
	public $vertical_view = false;
	/**
	 * @var \com\ui\intf\panel
	 */
	public $menu_panel = false;
	/**
	 * @var \com\ui\intf\listgroup
	 */
	public $listgroup = false;

    protected $column = false; // is there an open column
    protected $column_count = 0; // how many columns created

    protected $form_context = false; // was the form created from within a "column"

	/**
	 * @var form2_buffer
	 */
    public $form = false;
    public $options = [];
    //--------------------------------------------------------------------------------
	// magic
    //--------------------------------------------------------------------------------
	protected function __construct($options = []) {
		// init
		$this->ui = \LiquidedgeApp\Octoapp\app\app\ui\ui::make();
		$this->tag = $this->ui->tag();
		$this->grid = $this->ui->grid();
		$this->options = $options;

		// params
    	$this->ignore_columns = \core::$app->get_request()->get("comhtmlignorecolumns", \LiquidedgeApp\Octoapp\app\app\data\data::TYPE_BOOL, ["get" => true]);

		// unique id
    	$this->id = \com\session::$current->session_uid;
    	$this->buffer = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();

		// start
    	$this->start();
    }
    //--------------------------------------------------------------------------------
    /**
     * @param $name
     * @param $arguments
     * @return \com\ui\intf\buffer|null
     */
	public function __call($name, $arguments) {
	    call_user_func_array([$this->buffer, $name], $arguments);

        return $this->buffer;
    }
    //--------------------------------------------------------------------------------
	public function __destruct() {
		// close all outstanding elements
		$this->end();
	}
	//--------------------------------------------------------------------------------
	public function build($options = []) {
		// inline output
		$this->end();
        return $this->buffer->get_clean();
	}
    //--------------------------------------------------------------------------------
	public function flush($options = []) {
		// inline output
		$this->end();
        return $this->buffer->flush();
	}
    //--------------------------------------------------------------------------------
	// functions: form
    //--------------------------------------------------------------------------------
	public function form($action_url, $validate_url = false, $id = false, $options = []) {
		// options
		$options = array_merge([
			"horizontal" => true,
		], $options);

		// create form
		$this->form = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->form2_buffer($id);
		$this->form->action = $action_url;
		$this->form->validate_url = $validate_url;
		$this->form->horizontal = $options["horizontal"];
		$this->form->start($this->buffer);

		$this->form_context = ($this->column ? "column" : false);

	}
    //--------------------------------------------------------------------------------
	public function table_form_end() {
		// toolbar
		$this->close_toolbar();

		// form
		//$this->form_end();
	}
	//--------------------------------------------------------------------------------
	public function form_end() {
		// check if we have a form
		if (!$this->form) return;

		// close form
		$this->form->end($this->buffer);
		$this->form = false;
		$this->form_context = false;
	}
    //--------------------------------------------------------------------------------
	public function set_label_col($nr) {
		$this->label_col = $nr;
		$this->label_width = "100%";
	}
    //--------------------------------------------------------------------------------
    // functions: toolbar
    //--------------------------------------------------------------------------------
	protected function add_toolbar() {
		// check if we have a toolbar already
		if ($this->toolbar) return false;
		$this->toolbar = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->toolbar([".mb-3" => true]);
	}
    //--------------------------------------------------------------------------------
	public function close_toolbar() {
		// check if we have a toolbar
		if (!$this->toolbar) return false;

		// flush and clean
        $this->buffer->add($this->toolbar->build());
		$this->toolbar = false;
	}
    //--------------------------------------------------------------------------------
	public function button($label, $onclick = false, $options = []) {

		$options = array_merge([
			".me-2" => true
		], $options);

		// toolbar
		$this->buffer->xbutton($label, $onclick, $options);
	}
	//--------------------------------------------------------------------------------
	public function submit($label, $options = []) {
		// options
		$options = array_merge([
			"url" => false,
			"function" => "requestRefresh",
			"new_url" => false,
			"confirm_text" => false,
			"overwrite" => false,
			"inter_js" => false,
		], $options);

		// done
		return $this->submitbutton($label, $options["url"], $options["function"], $options["new_url"], $options["confirm_text"], $options["overwrite"], $options, $options["inter_js"]);
	}
	//--------------------------------------------------------------------------------
    public function get_submit_js($options = []) {

        $options = array_merge([
            "label" => "Save Changes",
            "icon" => "fa-save",
            "form" => $this->form,
            "enable_error_popup" => true,
            "auto_focus_on_error" => true,
            "enable_error_feedback" => true,
            "*no_overlay" => true,
            ".btn-submit" => true,
            "*data" => [
                "current_panel" => \core::$app->get_request()->get_panel(),
            ],
            "*beforeSend" => "function(){
                core.overlay.show();
            }",
            "*success" => "function(response){
                core.ajax.process_response(response);
                if(!response.no_overlay) core.overlay.hide();
            }",
        ], $options);

        return \LiquidedgeApp\Octoapp\app\app\js\js::ajax($this->form->action, $options);
    }
	//--------------------------------------------------------------------------------
    public function submit_button_js($options = []) {

        $options = array_merge([
            "label" => "Save Changes",
            "icon" => "fa-save",
            "form" => $this->form,
            "enable_error_popup" => true,
            "auto_focus_on_error" => true,
            "enable_error_feedback" => true,
            "*no_overlay" => true,
            ".btn-submit" => true,
            "*data" => [
                "current_panel" => \core::$app->get_request()->get_panel(),
            ],
            "*beforeSend" => "function(){
                core.overlay.show();
            }",
            "*success" => "function(response){
                core.ajax.process_response(response);
                if(!response.no_overlay) core.overlay.hide();
            }",
        ], $options);

        $this->button($options["label"], $this->get_submit_js($options), $options);
    }
	//--------------------------------------------------------------------------------
	public function submitbutton($label, $url = false, $function = "requestRefresh", $new_url = false, $confirm_text = false, $overwrite = false, $options = [], $inter_js = false) {
		// check if we have a form
		if (!\com\ui\helper::$current_form) return false;

		// button id
		if (!isset($options["@id"])) {
			$options["@id"] = \com\session::$current->session_uid;
			\com\ui\helper::$current_form->submit_button_id = $options["@id"];
		}

		$options = array_merge([
		    "icon" => "fa-save",
		    ".btn-form-submit" => true,
		], $options);

		// add button
		$this->button($label, \com\ui\helper::$current_form->get_submit_js($url, $function, $new_url, $confirm_text, $overwrite, $inter_js), $options);
	}
    //--------------------------------------------------------------------------------
	public function downloadbutton($label, $url, $options = []) {
		// check if we have a form
		if (!\com\ui\helper::$current_form) return false;

		// add button
		$form = \com\ui\helper::$current_form;
		$this->button($label, "if ({$form->cid}.validate()) { $('#{$form->id_form}').attr('action', '{$url}').removeAttr('onsubmit').submit().attr('onsubmit', 'return false;'); }", $options);
	}
    //--------------------------------------------------------------------------------
	public function nav($html, $options = []) {
		// toolbar
		$this->add_toolbar();
		$this->toolbar->add($html, $options);
	}
    //--------------------------------------------------------------------------------
	// functions: menu
	//--------------------------------------------------------------------------------
	protected function add_menu() {
		// check if we have a menu already
		if ($this->menu) return false;
		$this->menu = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->menu([
			"disable_collapse" => $this->menu_disable_collapse,
			"disable_headers" => $this->menu_disable_headers,
		]);
	}
	//--------------------------------------------------------------------------------
	public function close_menu() {
		// check if we have a menu
		if (!$this->menu) return false;

		// flush and clean
        $this->buffer->add($this->menu->build());
		$this->menu = false;
	}
    //--------------------------------------------------------------------------------
    // functions: layout
    //--------------------------------------------------------------------------------
	public function start() {
		// check if our wrapper has been added
		if ($this->wrapper) return;

		// html
        $this->buffer->div_("#{$this->id}", $this->options);
		$this->wrapper = true;
	}
    //--------------------------------------------------------------------------------
	public function end() {
		// toolbar
		$this->close_toolbar();

		// listgroup
		$this->close_listgroup();
		$this->close_menu();

		// column
		$this->end_column();
		$this->column_count = 0;
		$this->grid->_container();

		// end form
		$this->form_end();

		// close wrapper
		if ($this->wrapper) {
		    $this->buffer->_div();
			$this->wrapper = false;
		}
	}
    //--------------------------------------------------------------------------------
	public function display($obj) {
		if ($obj instanceof \com\ui\intf\table && $obj->enable_nav) {
			$obj->html = $this;
			$obj->display();
			return;
		}

		// toolbar
		$this->close_toolbar();

		if(is_callable($obj)){
		    $this->buffer->add($obj());
        }

		// string
		if (is_string($obj)) {
		    $this->buffer->add($obj);
			return;
		}

		// \com\ui\intf\buffer integration
		if ($obj instanceof \com\ui\intf\buffer) {
		    $this->buffer->add($obj->get_clean());
			return;
		}

		// \com\ui\intf\element integration
		if ($obj instanceof \com\ui\intf\element) {
		    $this->buffer->add($obj->build());
			return;
		}

		// html
//		$obj->display();
	}
    //--------------------------------------------------------------------------------
	public function end_column_row() {
		$this->end_column();

		// end row
        $this->buffer->_div();
	}
	//--------------------------------------------------------------------------------
	public function end_column() {
		// form
		if ($this->form_context == "column") {
			$this->form_end();
		}

		// check if we have a column
		if (!$this->column) return;

		// listgroup
		$this->close_listgroup();
		$this->close_menu();

		// end col
        $this->buffer->_div();

		// save column status
		$this->column = false;
	}
    //--------------------------------------------------------------------------------
	public function space($height = 10) {
		// toolbar
		$this->close_toolbar();
		if ($this->menu) {
			return;
		}

		// html
        $this->buffer->xspace($height);
	}
    //--------------------------------------------------------------------------------
	public function column($options = []) {
		// options
		$options = array_merge([
			"width" => false,
		], $options);

		// toolbar
		$this->close_toolbar();

		// ignore columns
		if ($this->ignore_columns) return;

		// column
		$this->end_column();

		// display row if this is the first column
		if (!$this->column_count) {
		    $this->buffer->div_(".row");
		}

		// width
		static $previous_width;
		if ($options["width"]) {
			if (!$previous_width) $previous_width = $options["width"];

			if (is_numeric($options["width"])) {
				if ($options["width"] <= 12) $options[".col-md-{$options["width"]}"] = true;
				else $options["#width"] = "{$options["width"]}px";
			}
			elseif (in_array($options["width"], ["tiny", "small", "medium", "large", "huge", "full"])) {
				switch ($options["width"]) {
					case "tiny" :
						//$options["#width"] = "200px !important";
						$options[".col-md-2"] = true;
						break;

					case "small" : $options[".col-md-4"] = true; break;
					case "medium" : $options[".col-md-6"] = true; break;
					case "large" : $options[".col-md-8"] = true; break;
					case "huge" : $options[".col-md-8"] = true; break;
					case "full" : $options[".col-md-12"] = true; break;
				}
			}
			else $options["#width"] = $options["width"];
		}
		else {
			switch ($previous_width) {
				case "tiny" : $options[".col-md-10"] = true; break;
				case "small" : $options[".col-md-8"] = true; break;
				case "medium" : $options[".col-md-6"] = true; break;
				case "large" : $options[".col-md-4"] = true; break;
				case "huge" : $options[".col-md-4"] = true; break;
				default : $options[".col-md-6"] = true; break;
			}
			/*$options[".col-md"] = true;
			$options[".overflow-auto"] = true;*/
		}

		// html
        $this->buffer->div_($options);

		// save column status
		$this->column = true;
		$this->column_count++;
	}
    //--------------------------------------------------------------------------------
	public function panel($url, $options = []) {
		// toolbar
		$this->close_toolbar();

		// column
		$this->column($options);

        $panel = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->panel($url);
        $this->buffer->add($panel->build());
        return $panel->id;
	}
    //--------------------------------------------------------------------------------
    // functions: listgroup
    //--------------------------------------------------------------------------------
	public function close_listgroup() {
		if (!$this->listgroup) return;

		$this->buffer->add($this->listgroup->build());
		$this->listgroup = false;
	}
    //--------------------------------------------------------------------------------
    public function menu($label, $onclick = false, $icon = false, $options = []) {
		// menu
		$this->add_menu();
		$this->menu->add_link($label, $onclick, $icon, $options);

		// auto-load first item if panel is specified
		static $active_menu_count = 0;
		if ($this->menu_panel) {
			if ($onclick && !$active_menu_count) {
				\LiquidedgeApp\Octoapp\app\app\js\js::add_script($onclick, ["after" => true]);
				$active_menu_count++;
			}
		}
	}
    //--------------------------------------------------------------------------------
    // functions: header
    //--------------------------------------------------------------------------------
	public function header($size, $label, $sub_label = false, $options = []) {
		// toolbar
		$this->close_toolbar();

		// listgroup
		$this->close_listgroup();

		// absorb if within menu
		if ($this->menu) {
			if ($this->menu_disable_headers) {
				if ($label) {
					$this->menu->set_last_header($label);
					$this->menu->add_link($label, false, "caret-right", ["/icon" => [".float-right" => true]]);
				}
				return;
			}
			else {
				$this->menu_disable_collapse = true;
				$this->close_menu();
			}
		}

		$this->buffer->xheader($size, $label, $sub_label, $options);
	}
    //--------------------------------------------------------------------------------
	public function note($note_arr, $options = []) {
		// toolbar
		$this->close_toolbar();
		$this->buffer->xnote($note_arr, $options);
	}
    //--------------------------------------------------------------------------------
	public function end_header() {
		// toolbar
		$this->close_toolbar();
	}
    //--------------------------------------------------------------------------------
    // functions: inputs
    //--------------------------------------------------------------------------------
	public function itext($label, $name, $value = false, $options = []) {
		// toolbar
		$this->close_toolbar();

		// options
		$this->apply_options($options);

		// html
        $this->buffer->xitext($name, $value, $label, $options);
	}
	//--------------------------------------------------------------------------------
	public function iswitch($label, $id, $value = false, $default = false, $options = []) {
		// toolbar
		$this->close_toolbar();

		// options
		$this->apply_options($options);

		// html
		$this->buffer->xiswitch($id, $value, $default, $label, $options);
	}
    //--------------------------------------------------------------------------------
	public function ipercentage($label, $name, $value = false, $options = []) {
		// toolbar
		$this->close_toolbar();

		// options
		$this->apply_options($options);

		// html
        $this->buffer->xipercentage($name, $value, $label, $options);
	}
    //--------------------------------------------------------------------------------
	public function icurrency($label, $name, $value = false, $options = []) {
		// toolbar
		$this->close_toolbar();

		// options
		$this->apply_options($options);

		// html
        $this->buffer->xicurrency($name, $value, $label, $options);
	}
    //--------------------------------------------------------------------------------
	public function iselect($label, $name, $value_arr, $value = false, $options = []) {
		// toolbar
		$this->close_toolbar();

		// options
		$this->apply_options($options);

		// html
        $this->buffer->xiselect($name, $value_arr, $value, $label, $options);
	}
    //--------------------------------------------------------------------------------
	public function icheckbox($label, $id, $value = false, $default = false, $options = []) {
		// toolbar
		$this->close_toolbar();

		// options
		$this->apply_options($options);

		// html
        $this->buffer->xicheckbox($id, $value, $default, $label, $options);
	}
    //--------------------------------------------------------------------------------
	public function iradio($label, $id, $value = false, $default = false, $options = []) {
		// toolbar
		$this->close_toolbar();

		// options
		$this->apply_options($options);

		// html
        $this->buffer->xiradio($id, $value, $default, $label, $options);
	}
    //--------------------------------------------------------------------------------
	public function iiconpicker($label, $id, $value = false, $options = []) {

		$this->close_toolbar();

		// options
		$this->apply_options($options);

		$this->buffer->xiiconpicker($id, $value, $label, $options);
	}
    //--------------------------------------------------------------------------------
	public function icolorpicker($label, $id, $value = false, $options = []) {

		$this->close_toolbar();

		// options
		$this->apply_options($options);

		$this->buffer->xicolorpicker($label, $id,$value, $options);
	}
    //--------------------------------------------------------------------------------
	public function iyearmonth($label, $name, $value = false, $options = []) {
		// toolbar
		$this->close_toolbar();

		// options
		$this->apply_options($options);

		// html
        $this->buffer->xiyearmonth($name, $value, $label, $options);
	}
	//--------------------------------------------------------------------------------
	public function itime($label, $name, $value = false, $options = []) {
		// toolbar
		$this->close_toolbar();

		// options
		$this->apply_options($options);
		// html
        $this->buffer->xitime($name, $value, $label, $options);
	}
    //--------------------------------------------------------------------------------
	public function idate($label, $name, $value = false, $options = []) {
		// toolbar
		$this->close_toolbar();

		// options
		$this->apply_options($options);
		// html
        $this->buffer->xidate($name, $value, $label, $options);
	}
    //--------------------------------------------------------------------------------
	public function idatetime($label, $name, $value = false, $options = []) {
		// toolbar
		$this->close_toolbar();

		// options
		$this->apply_options($options);

		// html
		$this->buffer->xidatetime($name, $value, $label, $options);
	}
    //--------------------------------------------------------------------------------
	public function iaddress($label, $address, $options = []) {
		$options = array_merge([
		    "add_type_arr" => [1 => "Physical"],
		    "input_arr" => [
				"show_line1_line" => "hide",
				"show_line2_line" => "hide",
				"show_line3_line" => "hide",
				"show_unit_line" => "show",
				"show_streetnr_line" => "show",
				"show_attention_line" => "hide",
				"show_pobox_line" => "hide",
				"show_postnet_line" => "hide",
				"show_clusterbox_line" => "hide",
				"show_city_line" => "show",
				"show_province_line" => "show",
				"show_gps_line" => "show",
				"@disabled" => true,
			],
		], $options);

		// toolbar
		$this->close_toolbar();

		// header
		$this->header(3, $label);

		// html
		$this->buffer->xiaddress($address, $options);
		$this->space();
	}
    //--------------------------------------------------------------------------------
	public function ihidden($name, $value) {
		// toolbar
		$this->close_toolbar();

		// html
		$this->buffer->xihidden($name, $value);
	}
    //--------------------------------------------------------------------------------
	public function itextarea($label, $id, $value = false, $rows = 10, $options = []) {
		// toolbar
		$this->close_toolbar();

		// options
		$this->apply_options($options);

		if($this->form) $options["horizontal"] = $this->form->horizontal;


        $this->buffer->xitextarea($id, $value, $label, $rows, $options);
	}
    //--------------------------------------------------------------------------------
	public function form_input($input, $options = []) {
		// toolbar
		$this->close_toolbar();

		// options
		$this->apply_options($options);

		//html
		$this->buffer->xform_input($input, $options);
	}
    //--------------------------------------------------------------------------------
	/**
	* Renders a Dropdown file upload input within the current html structure.
	*
	* @param [string] $label - input label
	* @param [string] $id - input unique id
	* @param [string] $folder - the folder where the files should be uploaded to (No trailing slash)
	* @param \com\os\int\filetype_group $filetype_group
	* @param [string] $value - the default value
	*
	* @param [array] $options prefixitems - array of index => value items to add to the start of the drop down
	*/
	public function ifile($label, $id, $folder, $filetype_group, $value = false, $options = []) {
		// toolbar
		$this->close_toolbar();
        $this->buffer->xifile($label, $id, $folder, $filetype_group, $value, $options);
	}
    //--------------------------------------------------------------------------------
	public function ifile2($label, $id, $folder, $filetype_group, $value = false, $options = []) {
		// toolbar
		$this->close_toolbar();
        $this->buffer->xifile2($label, $id, $folder, $filetype_group, $value, $options);
	}
    //--------------------------------------------------------------------------------
	public function ifile_single($label, $id, $folder, $filetype_group, $value = false, $options = []) {
		// toolbar
		$this->close_toolbar();
        $this->buffer->xifile_single($label, $id, $folder, $filetype_group, $value, $options);
	}
    //--------------------------------------------------------------------------------
	public function value($label, $value, $type = false, $options = []) {
		// options
		$options = array_merge([
			"trid" => false,
		], $options);

		if ($this->menu) {
			$this->menu->add_value($this->menu->get_last_header(), $label, $value, $type);
			return;
		}

		// vertical
		if ($this->vertical_view) {
			return $this->value_vertical($label, $value, $type, $options);
		}

		// toolbar
		$this->close_toolbar();

		// options
		$this->apply_options($options);
        $this->buffer->xform_value3($label, $value, $type, $options);
	}
    //--------------------------------------------------------------------------------
	public function value_vertical($label, $value, $type = false, $options = []) {
		// toolbar
		$this->close_toolbar();
        $this->buffer->xform_value2($label, $value, $type, $options);
	}
    //--------------------------------------------------------------------------------
    public function dbvalue($obj, $field, $options = []) {
		// toolbar
		$this->close_toolbar();

		if ($this->menu) {
			$this->menu->add_dbvalue($this->menu->get_last_header(), $obj, $field, $options = []);
			return;
		}

		// set
        $options["vertical"] = $this->vertical_view;
        $this->buffer->xdbvalue($obj, $field, $options);
	}
    //--------------------------------------------------------------------------------
    public function auto_value($obj, $field, $options = []) {
		// toolbar
		$this->close_toolbar();

		if ($this->menu) {
			$this->menu->add_dbvalue($this->menu->get_last_header(), $obj, $field, $options = []);
			return;
		}

		// set
        $options["vertical"] = $this->vertical_view;
        $this->buffer->xauto_value($obj, $field, $options);
	}
    //--------------------------------------------------------------------------------
	public function dbinput($dbentry, $field, $options = []) {
		// options
		$options = array_merge([
			"view" => false,
			"readonly" => false,
			"label" => false,
			"filetype_group" => false,
			"auth" => false,
		], $options);

		// auth
		if ($options["auth"]) {
			if (!\core::$app->get_token()->check($options["auth"])) return false;
		}

		// toolbar
		$this->close_toolbar();

		// options
		$this->apply_options($options);

        $this->buffer->xdbinput($dbentry, $field, $options);
	}
    //--------------------------------------------------------------------------------
	public function auto_input($obj, $field, $options = []) {
		// options
		$options = array_merge([
			"view" => false,
			"readonly" => false,
			"label" => false,
			"filetype_group" => false,
			"auth" => false,
		], $options);

		// auth
		if ($options["auth"]) {
			if (!\core::$app->get_token()->check($options["auth"])) return false;
		}

		// toolbar
		$this->close_toolbar();

        $this->buffer->xauto_input($obj, $field, $options);
	}
    //--------------------------------------------------------------------------------
    // functions: other
    //--------------------------------------------------------------------------------
	public function message($text, $color = false, $options = []) {
		// toolbar
		$this->close_toolbar();

		// html
		$this->buffer->xmessage($text, $color, $options);
	}
    //--------------------------------------------------------------------------------
	public function hash($name) {
		// toolbar
		$this->close_toolbar();

		// html
		$this->buffer->xhash($name);
	}
	//--------------------------------------------------------------------------------
	public function image($src, $options = []) {
		// toolbar
		$this->close_toolbar();

		// html
		$this->buffer->ximage($src, $options);
	}
    //--------------------------------------------------------------------------------
    // functions: helpers
    //--------------------------------------------------------------------------------
	public function apply_options(&$options = []) {
		// label width
		if ($this->label_width) {
			$options["label_width"] = $this->label_width;
		}
		elseif ($this->header_width) {
			$options["label_width"] = "{$this->header_width}px";
		}

		// label col
		if ($this->label_col && !isset($options["label_col"])) {
			$options["label_col"] = $this->label_col;
		}

		// wrapper id
		if (isset($options["trid"])) {
			$options["wrapper_id"] = $options["trid"];
			unset($options["trid"]);
		}

		if($this->form) $options["horizontal"] = $this->form->horizontal;

	}
	//--------------------------------------------------------------------------------
	// functions: grid
	//--------------------------------------------------------------------------------
	public function container_($pattern = false, $options = []) {
		$this->grid->container_($pattern, $options);
	}
	//--------------------------------------------------------------------------------
	public function _container() {
		$this->grid->_container();
	}
	//--------------------------------------------------------------------------------
	public function row_($pattern = false, $options = []) {
		$this->grid->row_($pattern, $options);
	}
	//--------------------------------------------------------------------------------
	public function _row() {
    	$this->grid->_row();
	}
	//--------------------------------------------------------------------------------
	public function col_($pattern = false, $options = []) {
		$this->close_toolbar();
		$this->close_listgroup();
		$this->close_menu();
		$this->grid->col_($pattern, $options);
	}
	//--------------------------------------------------------------------------------
	public function _col() {
		$this->grid->_col();
	}
	//--------------------------------------------------------------------------------
}