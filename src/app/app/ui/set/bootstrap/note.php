<?php

namespace LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap;

/**
 * Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class note extends \com\ui\intf\element {
	//--------------------------------------------------------------------------------
	// fields
	//--------------------------------------------------------------------------------
	protected static $is_singleton = true;
	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	protected function __construct($options = []) {
		// init
		$this->name = "Note";
	}
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	public function build($options = []) {
		// public function note($note_arr) {

		// options
		$options = array_merge([
			".text-muted" => true,
			".mb-3" => true,
			"note_arr" => [],
		], $options);

		// init
		$note_arr = $options["note_arr"];

		// params
		$note_arr = \LiquidedgeApp\Octoapp\app\app\arr\arr::splat($note_arr);

		// view each note
		$formatted_note_arr = [];
		foreach ($note_arr as $note_item) {
			// make sure we have only one dash
			$formatted_note_arr[] = preg_replace('/^\- /i', '', $note_item);
		}

		$html = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();
		$html->div_($options);
			$html->add(implode("<br>", $formatted_note_arr));
		$html->_div();

		return $html->get_clean();
	}
	//--------------------------------------------------------------------------------
}