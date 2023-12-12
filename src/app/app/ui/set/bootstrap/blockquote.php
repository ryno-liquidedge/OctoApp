<?php

namespace LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap;

/**
 * @package app\ui\set\bootstrap
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class blockquote extends \com\ui\intf\element {
	//--------------------------------------------------------------------------------
	// fields
	//--------------------------------------------------------------------------------
	protected static $is_singleton = true;
	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	protected function __construct($options = []) {
		// init
		$this->name = "Blockquote";
	}
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	public function build($options = []) {

		$options = array_merge([
		    "quote" => false,
		    "caption" => false,

		    "/quote" => [".fs-6 text-dark mb-0" => true],
		    "/caption" => [],
		], $options);

		$buffer = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();

		$buffer->figure_();
			$buffer->blockquote_([".blockquote" => true, ]);

				$options["/quote"]["*"] = $options["quote"];
				$buffer->p($options["/quote"]);

			$buffer->_blockquote();

			if($options["caption"]){
				$buffer->figcaption_([".blockquote-footer" => true, ]);
					$options["/caption"]["*"] = $options["caption"];
					$buffer->span($options["/caption"]);
				$buffer->_figcaption();
			}

		$buffer->_figure();

		return $buffer->build();
	}
	//--------------------------------------------------------------------------------
}