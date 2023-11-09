<?php

namespace LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap;

/**
 * @package app\ui\set\bootstrap
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class panel extends \com\ui\set\bootstrap\panel {
	//--------------------------------------------------------------------------------
	// fields
	//--------------------------------------------------------------------------------
  	protected $class_name = "com_panel"; // class name
  	protected $url_arr = []; // array of indexed urls
  	protected $js_options = [];
  	protected $pop = false;
	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	/**
	 * Creates the component.
	 *
	 * @param string $url <p>Specify a url that will be loaded when the panel is displayed - false creates an empty panel unless a $start_index is specified.</p>
	 * @param string $options[id] <p>Use this component id instead of auto generating an unique one.</p>
	 * @param string $options[class] <p>Use this as the class html attribute for the wrapper div.</p>
	 */
    public function __construct($options = []) {
		// options
		$options = array_merge([
			"id" => false,
			"class" => false,
			"url" => false,
		], $options);

		// id
		$this->id = ($options["id"] ? $options["id"] : \com\session::$current->session_uid);
		$this->pop = \core::$app->get_request()->get("pop", \com\data::TYPE_BOOL, ["trusted" => true]);

		// url
		if ($options["url"]) $this->start_index = $this->add_url($options["url"]);

		// class
		$this->class = $options["class"];


    }
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
    public function add_post_data($key, $value) {
        $this->js_options["*data"][$key] = $value;
    }
    //--------------------------------------------------------------------------------
    public function add_option($key, $value) {
        $this->js_options["*$key"] = $value;
    }
	//--------------------------------------------------------------------------------
	/**
     * Writes the panel open div tag.
     */
  	public function start() {

  		$this->js_options["*no_overlay"] = true;
  		$this->js_options["*autoscroll"] = false;

  	    $js_options = \com\js::create_options($this->js_options);

  		// build js
		$SCRIPT_csrf = \core::$app->get_response()->get_csrf();
  		$SCRIPT_init = "var $this->id = new $this->class_name('$this->id', '{$SCRIPT_csrf}');";
  		foreach ($this->url_arr as $url_item) $SCRIPT_init .= "$this->id.addUrl('".strtr($url_item, ["'" => "\\'"])."');";
  		if (\core::$panel) $SCRIPT_init .= "$this->id.setParent('".\core::$panel."');";
  		if ($this->hidden_update) $SCRIPT_init .= "$this->id.hidden_update = true;";

  		if ($this->start_index !== false) $SCRIPT_init .= "$this->id.refresh($this->start_index, $js_options);";

  		// view js
  		\com\js::add_script($SCRIPT_init);

  		// html
		echo \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->tag()->div_("#panel_{$this->id}", [
			"@class" => $this->class,
		]);
  	}
  	//--------------------------------------------------------------------------------
  	/**
     * Writes the panel ending div tag.
     */
  	public function end() {
  		// end div
  		echo \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->tag()->_div();
  	}
  	//--------------------------------------------------------------------------------
  	/**
     * This function calls start() and end() directly after each other.
     */
  	public function display() {
		$this->start();
		$this->end();
  	}
  	//--------------------------------------------------------------------------------
	public function build($options = []) {
  		ob_start();
  		$this->display();
  		return ob_get_clean();
	}
  	//--------------------------------------------------------------------------------
	/**
	 * Adds the given url to the panel's internal index.
	 *
	 * @param string $url <p>The url to add.</p>
	 * @return int <p>The index at which the url was added.</p>
	 */
  	public function add_url($url) {
  		$this->url_arr[] = $url;
  		return count($this->url_arr) - 1;
  	}
  	//--------------------------------------------------------------------------------
}