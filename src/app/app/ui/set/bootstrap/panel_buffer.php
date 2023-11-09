<?php

namespace LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap;

/**
 * @package app\ui\set\bootstrap
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class panel_buffer extends \com\ui\intf\panel {
	//--------------------------------------------------------------------------------
	// fields
	//--------------------------------------------------------------------------------
    /**
     * @var \com\ui\intf\buffer
     */
  	protected $buffer;
  	protected $content;
  	protected $class_name = "com_panel"; // class name
  	protected $url_arr = []; // array of indexed urls
    protected $js_options = [];
    protected $js_done = '';
    public $no_refresh = false;
    protected $inline_loader = false;
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
			"class" => 'ui-panel',
			"url" => false,
			"autoscroll" => false,
			"no_overlay" => true,
			"delay" => false,
		], $options);

		// id
		$this->id = ($options["id"] ? $options["id"] : \com\session::$current->session_uid);

		// url
		if ($options["url"]) $this->start_index = $this->add_url($options["url"]);

		// class
		$this->class = $options["class"];
		$this->add_option("autoscroll", $options["autoscroll"]);
		$this->add_option("no_overlay", $options["no_overlay"]);

		$this->buffer = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();

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
    public function get_id() {
        return "panel_{$this->id}";
    }
	//--------------------------------------------------------------------------------
    public function on_complete($js) {
        $this->js_done = $js;
    }
	//--------------------------------------------------------------------------------
	/**
     * Writes the panel open div tag.
     */
  	public function start() {

  	    $js_options = \com\js::create_options($this->js_options);

  		// build js
		$SCRIPT_csrf = \core::$app->get_response()->get_csrf();
  		$SCRIPT_init = "var $this->id = new $this->class_name('$this->id', '{$SCRIPT_csrf}');";
  		foreach ($this->url_arr as $url_item) $SCRIPT_init .= "$this->id.addUrl('".strtr($url_item, ["'" => "\\'"])."');";
  		if (\core::$panel) $SCRIPT_init .= "$this->id.setParent('".\core::$panel."');";
  		if ($this->hidden_update) $SCRIPT_init .= "$this->id.hidden_update = true;";

  		if ($this->start_index !== false && !$this->content) $SCRIPT_init .= "$this->id.refresh($this->start_index, $js_options).done(function(){ {$this->js_done} });";

  		// view js
  		\com\js::add_script($SCRIPT_init);

  		// html
		$this->buffer->div_("#panel_{$this->id}", [
			"@class" => $this->class,
		]);
  	}
  	//--------------------------------------------------------------------------------

    /**
     * @param $html
     */
	public function add($html) {
		if(is_callable($html)) $html = $html();
        $this->content = $html;
    }
  	//--------------------------------------------------------------------------------

    /**
     * @param $html
     */
	public function add_data($id, $value) {
        $this->js_options["*{$id}"] = $value;
    }
  	//--------------------------------------------------------------------------------
  	/**
     * Writes the panel ending div tag.
     */
  	public function end() {
  		// end div
  		$this->buffer->_div();
  	}
  	//--------------------------------------------------------------------------------
    public function init() {
        $this->start();
        $this->buffer->add($this->content);
        if($this->inline_loader) $this->buffer->add($this->inline_loader);
        $this->end();
    }
  	//--------------------------------------------------------------------------------
  	/**
     * This function calls start() and end() directly after each other.
     */
  	public function display() {
		echo $this->build();
  	}
  	//--------------------------------------------------------------------------------
	public function build($options = []) {

  	    $this->init();

  		return $this->buffer->get_clean();
	}
  	//--------------------------------------------------------------------------------
	public function build_with_html($content, $options = []) {

  	    $this->content = $content;

  		return $this;
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
	public function enable_inline_loader() {
		$buffer = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();
		$buffer->div_([".row min-h-400px align-items-center fs-4 text-gray" => true]);
			$buffer->div_([".col-12 text-center" => true]);
				$buffer->span(["*" => "Loading... "]);
				$buffer->xicon("fa-spinner", [".fa-spin" => true]);
			$buffer->_div();
		$buffer->_div();
		$this->inline_loader = $buffer->build();
	}
  	//--------------------------------------------------------------------------------
}