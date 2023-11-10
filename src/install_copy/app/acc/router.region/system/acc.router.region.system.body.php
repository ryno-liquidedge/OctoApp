<?php

namespace acc\router\region\system;

/**
 * @package acc\router\region\system
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class body implements \com\router\int\region {
	//--------------------------------------------------------------------------------
	use \com\router\tra\region;
	//--------------------------------------------------------------------------------
	// properties
	//--------------------------------------------------------------------------------
	protected $content = null;
	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	protected function __construct($options = []) {
		// options
		$options = array_merge([
			"content" => false,
		], $options);

		// init
		$this->content = $options["content"];
	}
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	public function render() {
		// html
		$html = \com\ui::make()->buffer();
		\com\js::set_script_top_force(true);

		// wrapper: start
		$html->div_("#panel_body", [".px-2" => true]);

		// buffer/panel: start
		ob_start();
		$panel = \com\ui::make()->panel(false, ["class" => "mod-wrapper", "id" => "mod"]);
		$panel->add_url($_SERVER["REQUEST_URI"]);
		$panel->start();

		// content
		echo $this->content;

		// buffer/panel: end
		$panel->end();
		$html->add(ob_get_clean());

		// wrapper: end
		$html->_div();

		// done
		\com\js::set_script_top_force(false);
		return $html->get_clean();
	}
	//--------------------------------------------------------------------------------
	// static
	//--------------------------------------------------------------------------------
	/**
	 * @param array $options
	 * @return static
	 */
	public static function make($options = []) {
		return new static($options);
	}
	//--------------------------------------------------------------------------------
}