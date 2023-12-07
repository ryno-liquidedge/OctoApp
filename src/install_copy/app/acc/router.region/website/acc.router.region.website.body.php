<?php

namespace acc\router\region\website;

/**
 * @package acc\router\region\website
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
		$buffer = \com\ui::make()->buffer();

		// wrapper: start
        $buffer->div_("#panel_body", ["#display" => "none", ".display-on-load" => true]);
            $panel = \app\ui::make()->panel_buffer($_SERVER["REQUEST_URI"], [
                "html" => $this->content,
                "class" => "mod-wrapper",
                "id" => "mod",
            ]);
            $buffer->add($panel->build());
        // wrapper: end
        $buffer->_div();

		// done
		\LiquidedgeApp\Octoapp\app\app\js\js::set_script_top_force(false);
		return $buffer->get_clean();
	}
	//--------------------------------------------------------------------------------
	// static
	//--------------------------------------------------------------------------------
	/**
	 * @return \com\router\region\body
	 */
	public static function make($options = []) {
		return new static($options);
	}
	//--------------------------------------------------------------------------------
}