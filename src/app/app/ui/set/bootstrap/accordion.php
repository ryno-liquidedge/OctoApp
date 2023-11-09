<?php

namespace LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap;

/**
 * Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class accordion extends \com\ui\intf\accordion {
	//--------------------------------------------------------------------------------
	// fields
	//--------------------------------------------------------------------------------
	protected $item_arr = [];
	protected $options = null;
	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	public function __construct($options = []) {
		// init
		$this->name = "Accordion";
		$this->options = $options;
		$this->id = \LiquidedgeApp\Octoapp\app\app\str\str::get_random_id("accordion");
	}
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	public function build($options = []) {
		// options
		$options = array_merge([
			"/card" => [],
			"/card-header" => [],
		], $this->options, $options);

		// buffer
		$buffer = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();

		$buffer->div_([".accordion" => true, "@id" => $this->id, ]);
		    foreach ($this->item_arr as $item_item) {
				$this->build_item($buffer, array_merge($item_item, $options));
			}
        $buffer->_div();

		// done
		return $buffer->get();
	}
	//--------------------------------------------------------------------------------
	public function add($label, $content, $options = []) {
		$this->item_arr[] = array_merge([
			"label" => $label,
			"content" => is_callable($content) ? $content() : $content,
			"show" => false,

			"/card" => [],
			"/card-header" => [],
		], $options);

	}
	//--------------------------------------------------------------------------------
	// internal
	//--------------------------------------------------------------------------------
	/**
	 * @param \com\ui\intf\buffer $buffer
	 * @param string[] $item
	 */
	protected function build_item($buffer, $item) {

	    $id = \LiquidedgeApp\Octoapp\app\app\str\str::get_random_id("accordion_item");

        $buffer->div_([".accordion-item" => true, ]);
            $buffer->h2_([".accordion-header" => true, ]);
                $buffer->button_([".accordion-button" => true, "@type" => "button", "@data-bs-toggle" => "collapse", "@data-bs-target" => "#{$id}", ".collapsed" => !$item["show"], "@aria-expanded" => $item["show"] ? "true" : "false", "@aria-controls" => $id, ]);
                $buffer->add($item["label"]);
                $buffer->_button();
            $buffer->_h2();

            $buffer->div_(["@id" => $id, ".accordion-collapse collapse" => true, "@data-bs-parent" => "#{$this->id}", ".show" => $item["show"]]);
                $buffer->div_([".accordion-body" => true, ]);
                    $buffer->add($item["content"]);
                $buffer->_div();
            $buffer->_div();
        $buffer->_div();

	}
	//--------------------------------------------------------------------------------
}