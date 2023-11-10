<?php

namespace acc\router\region\website;

/**
 * @package acc\router\region\website
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class banner implements \com\router\int\region {

	private $request;

	/**
	 * @var \app\session\search
	 */
	private $search_session;

	//--------------------------------------------------------------------------------
	use \com\router\tra\region;
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	public function render() {

		// html
		\com\js::set_script_top_force(true);
		$buffer = \app\ui::make()->buffer();

		$buffer->div(["@id" => "page-top"]);

		// done
		return $buffer->build();
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