<?php

namespace LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap;

/**
 * Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class wysiwyg extends \com\ui\intf\wysiwyg {
	//--------------------------------------------------------------------------------
	// properties
	//--------------------------------------------------------------------------------
	protected $id;
	protected $height;
	protected $toolbar;
	protected $drag_and_drop;
	protected $focus;
	protected $color_arr;
	protected $color_name_arr;
	protected $font_size_arr;
	protected $font_size_default;
	protected $on_init;
	protected $on_focus;
	protected $on_change;
	protected $on_blur;
	protected $on_paste;
	protected $variable_plugin_data;
	protected $bodyimage_plugin_data;
	protected $create_input;
	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	/**
	 * Initializes the default options for this component. The given $id should be an
	 * element that will exist on the page already. If no $id is provided, a textarea
	 * input will be created with an auto generated id.
	 *
	 * @param string $id <p>@see set_id()</p>
	 * @param array $options[height] <p>@see set_height()</p>
	 * @param array $options[toolbar] <p>@see set_toolbar()</p>
	 * @param bool $options[drag_and_drop] <p>@see set_drag_and_drop()</p>
	 * @param bool $options[focus] <p>@see set_focus()</p>
	 * @param array $options[colors] <p>@see set_colors()</p>
	 * @param array $options[color_names] <p>@see set_color_names()</p>
	 * @param array $options[font_sizes] <p>@see set_font_sizes()</p>
	 * @param int $options[font_size_default] <p>default font size to display in dropdown</p>
	 * @param string $options[on_init] <p>@see set_on_init()</p>
	 * @param string $options[on_focus] <p>@see set_on_focus()</p>
	 * @param string $options[on_change] <p>@see set_on_change()</p>
	 * @param string $options[on_blur] <p>@see set_on_blur()</p>
	 * @param string $options[on_paste] <p>@see set_on_paste()</p>
	 * @param string $options[variable_plugin_data] <p>@see set_variable_plugin_data()</p>
	 * @param string $options[bodyimage_plugin_data] <p>@see set_bodyimage_plugin_data()</p>
	 */
	public function __construct($options = []) {
		// options
		$options = array_merge([
			"id" => false,
			"height" => 300,
			"toolbar" => [
				["style", ["bold", "italic", "underline", "clear", "hello"]],
				["fontsize", ["fontsize"]],
				["color", ["color"]],
				["para", ["ul", "ol", "paragraph"]],
				["insert", ["link"]],
				["table", ["table"]],
				['view', ['fullscreen']],
			],
			"drag_and_drop" => false,
			"focus" => false,
			"colors" => false,
			"color_names" => false,
			"font_sizes" => false,
			"font_size_default" => false,
			"on_init" => "$('.note-editor .note-handle').hide();", // hide image editing toolbar / popup
			"on_focus" => false,
			"on_change" => false,
			"on_blur" => false,
			"on_paste" => "
				// params
				var editor = $(this);
				
				// delay to allow paste to finish
				setTimeout(function() {
					// get contents
					var code = editor.summernote('code');
					
					// clean
					var cleaned = core.string.clean_wysiwyg_paste(code);
					
					// set contents
					editor.summernote('code', cleaned);
				}, 10); 
			",
			"variable_plugin_data" => false,
			"bodyimage_plugin_data" => false,
		], $options);

		// input
		$id = $options["id"];
		if (!$id) $this->set_create_input(true);

		// init
		$this->set_id($id);
		$this->set_height($options["height"]);
		$this->set_toolbar($options["toolbar"]);
		$this->set_focus($options["focus"]);

		if ($options["colors"]) {
			$this->set_colors($options["colors"]);
		}
		if ($options["color_names"]) {
			$this->set_color_names($options["color_names"]);
		}
		if ($options["font_sizes"]) {
			$this->set_font_sizes($options["font_sizes"]);
		}
		if ($options["font_size_default"]) {
			$this->set_font_size_default($options["font_size_default"]);
		}

		// callbacks
		$this->set_on_init($options["on_init"]);
		$this->set_on_focus($options["on_focus"]);
		$this->set_on_change($options["on_change"]);
		$this->set_on_blur($options["on_blur"]);
		$this->set_on_paste($options["on_paste"]);

		// plugins
		$this->set_variable_plugin_data($options["variable_plugin_data"]);
		$this->set_bodyimage_plugin_data($options["bodyimage_plugin_data"]);
	}
	//--------------------------------------------------------------------------------
	// setters
	//--------------------------------------------------------------------------------
	/**
	 * Sets the component id. This id should be unique accross the current session.
	 * Uniqueness is not checked.
	 *
	 * @param string $id <p>The unique identifier. If no id is provided, one will be generated automatically.</p>
	 */
	protected function set_id($id = false) {
		// auto generate when we have no id
		if (!$id) $id = \com\session::$current->session_uid;
		$this->id = $id;
	}
	//--------------------------------------------------------------------------------
	/**
	 * Sets the height of the whole editor.
	 *
	 * @param int $height <p>The height in pixels.</p>
	 */
	public function set_height($height) {
		$this->height = $height;
	}
	//--------------------------------------------------------------------------------
	/**
	 * Sets whether to generate a textrea input(true) or use an existing one(false).
	 *
	 * @param boolean $create_input <p>True or False.</p>
	 */
	public function set_create_input($create_input) {
		$this->create_input = $create_input;
	}
	//--------------------------------------------------------------------------------
	/**
	 * Sets which buttons should be available on the toolbar.
	 *
	 * @param array $toolbar
	 * <p>The toolbar should be given within an array. Each section with buttons are specified as per below:</p>
	 * <p>["style", ["style", "bold", "italic", "underline", "clear"]]</p>
	 * <p>["fontsize", ["fontsize"]]</p>
	 * <p>["color", ["color"]],</p>
	 * <p>["para", ["ul", "ol", "paragraph"]]</p>
	 * <p>["height", ["height"]]</p>
	 * <p>["insert", ["picture", "link"]]</p>
	 * <p>["table", ["table"]]</p>
	 * <p>["help", ["help"]]</p>
	 */
	public function set_toolbar($toolbar) {
		$this->toolbar = $toolbar;
	}
	//--------------------------------------------------------------------------------
	/**
	 * Sets whether or not image upload is allowed by drag and drop
	 *
	 * @param boolean drag_and_drop
	 */
	public function set_drag_and_drop($drag_and_drop) {
		$this->drag_and_drop = $drag_and_drop;
	}
	//--------------------------------------------------------------------------------
	/**
	 * Sets whether or not to initialize this wysiwyg with focus
	 *
	 * @param boolean focus
	 */
	public function set_focus($focus) {
		$this->focus = $focus;
	}
	//--------------------------------------------------------------------------------
	/**
	 * Sets custom color palette
	 *
	 * @param string[] color_arr
	 */
	public function set_colors($color_arr) {
		$this->color_arr = $color_arr;
	}
	//--------------------------------------------------------------------------------
	/**
	 * Sets custom color palette names
	 *
	 * @param string[] color_name_arr
	 */
	public function set_color_names($color_name_arr) {
		$this->color_name_arr = $color_name_arr;
	}
	//--------------------------------------------------------------------------------
	/**
	 * Sets custom font sizes
	 *
	 * @param string[] color_name_arr
	 */
	public function set_font_sizes($font_size_arr) {
		$this->font_size_arr = $font_size_arr;
	}
	//--------------------------------------------------------------------------------
	/**
	 * Sets font size default
	 *
	 * @param int size
	 */
	public function set_font_size_default($size) {
		$this->font_size_default = $size;
	}
	//--------------------------------------------------------------------------------
	/**
	 * Specify contents of callback to run on init of this wysiwyg
	 *
	 * @param string on_init
	 */
	public function set_on_init($on_init) {
		$this->on_init = $on_init;
	}
	//--------------------------------------------------------------------------------
	/**
	 * Specify contents of callback to run on focus of this wysiwyg
	 *
	 * @param string on_focus
	 */
	public function set_on_focus($on_focus) {
		$this->on_focus = $on_focus;
	}
	//--------------------------------------------------------------------------------
	/**
	 * Specify contents of callback to run on change of this wysiwyg
	 *
	 * @param string on_change
	 */
	public function set_on_change($on_change) {
		$this->on_change = $on_change;
	}
	//--------------------------------------------------------------------------------
	/**
	 * Specify contents of callback to run on blur of this wysiwyg
	 *
	 * @param string on_change
	 */
	public function set_on_blur($on_blur) {
		$this->on_blur = $on_blur;
	}
	//--------------------------------------------------------------------------------
	/**
	 * Specify contents of callback to run on paste of this wysiwyg
	 *
	 * @param string on_paste
	 */
	public function set_on_paste($on_paste) {
		$this->on_paste = $on_paste;
	}
	//--------------------------------------------------------------------------------
	/**
	 * Specify contents of callback to run on blur of this wysiwyg
	 *
	 * @param string on_change
	 */
	public function set_variable_plugin_data($variable_plugin_data) {
		$this->variable_plugin_data = $variable_plugin_data;
	}
	//--------------------------------------------------------------------------------
	public function set_bodyimage_plugin_data($bodyimage_plugin_data) {
		$this->bodyimage_plugin_data = $bodyimage_plugin_data;
	}
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	/**
	 * Builds and returns the html code that would display this component.
	 *
	 * @return string <p>The display HTML.</p>
	 */
	public function get() {
		// html
		$html = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();

		// container
		if ($this->create_input) {
			$html->textarea("#{$this->id}");
		}

		// only plugin buttons if data available
		if ($this->variable_plugin_data) {
			$this->toolbar[] = ["custom", ["customVariablesPlugin"]];
		}

		if ($this->bodyimage_plugin_data) {
			$this->toolbar[] = ["custom", ["bodyImagePlugin"]];
		}

		// js: callbacks
		$callbacks = [
			// native
			"*onBlur" => "!function(e) {
				// params
				var editor = $(this); // equivalent to $('#{$this->id}');
			
				// custom onBlur
				{$this->on_blur}
				
				// get contents
				var code = editor.summernote('code');
				
				// replace bullets
				code = code.replace(/\u2022/ig, '&bull;');
				if (code.replace(/<\/?(div|br|p|span)>/ig, '') == '') code = '';
				
				// set contents
				editor.val(code);
			}",
			"*onPaste" => "!function(e) { {$this->on_paste} }",
			"*onChange" => "!function(e) { {$this->on_change} }",
			"*onFocus" => "!function(e) { {$this->on_focus} }",
			"*onInit" => "!function(e) { {$this->on_init} }",

			// custom
			"*getVariablesPluginData" => $this->variable_plugin_data,
			"*getBodyImagePluginData" => $this->bodyimage_plugin_data,
		];

		// js: build options
		$options = [
			"*height" => $this->height,
			"*toolbar" => $this->toolbar,
			"*disableDragAndDrop" => !$this->drag_and_drop,
			"*dialogsInBody" => true, // https://github.com/summernote/summernote/issues/1857
			//"*disableResizeEditor" => true,
			"*focus" => $this->focus,
			"*callbacks" => "!".\LiquidedgeApp\Octoapp\app\app\js\js::create_options($callbacks),
			"*colors" => $this->color_arr,
			"*colorsName" => $this->color_name_arr,
		];

		// js: only add font size if available
		if ($this->font_size_arr && is_array($this->font_size_arr)) {
			$options["*fontSizes"] = array_map("strval", $this->font_size_arr); // ensure values are strings
		}

		// prep options for js
		$options = \LiquidedgeApp\Octoapp\app\app\js\js::create_options($options);

		\LiquidedgeApp\Octoapp\app\app\js\js::add_script("
		    $(function(){
		        $('#{$this->id}').summernote({$options});
		    })
		");

		// force style on variable plugin dropdowns to be scrollable
		\LiquidedgeApp\Octoapp\app\app\js\js::add_domready_script("$('.note-editor .note-dropdown-menu').has('.dropdown-item[data-event=\\\"variablesDropdown\\\"]').css({'max-height':'300px', 'overflow-y':'auto'});");

		// set font size dropdown default
		if (isset($this->font_size_default)) {
			\LiquidedgeApp\Octoapp\app\app\js\js::add_domready_script("$('.note-editor').css('font-size', '{$this->font_size_default}px');");
			\LiquidedgeApp\Octoapp\app\app\js\js::add_domready_script("$('#{$this->id}').summernote('fontSize', {$this->font_size_default});");
			\LiquidedgeApp\Octoapp\app\app\js\js::add_domready_script("$('#{$this->id}').summernote('formatPara');");
		}

		// done
		return $html->get_clean();
	}
	//--------------------------------------------------------------------------------
	public function build($options = []) {
		return $this->get();
	}
	//--------------------------------------------------------------------------------
}