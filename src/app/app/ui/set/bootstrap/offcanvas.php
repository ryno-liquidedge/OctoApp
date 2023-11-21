<?php

namespace LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap;

/**
 * Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class offcanvas extends \com\ui\intf\element {
	//--------------------------------------------------------------------------------
	// fields
	//--------------------------------------------------------------------------------
	protected $id;
	protected $trigger;
	protected $body = [];
	protected $title = [
		"*" => "",
		"size" => 5,
		"@id" => "",
		".offcanvas-title" => true,
	];

	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	protected function __construct($options = []) {

		$options = array_merge([
		    "id" => \app\str::get_random_id("offcanvas")
		], $options);

		// init
		$this->id = $options["id"];
		$this->name = "Offcanvas";
	}
	//--------------------------------------------------------------------------------
	/**
	 * @return mixed
	 */
	public function get_id() {
		return $this->id;
	}
	//--------------------------------------------------------------------------------
	public function set_title($title, $options = []) {
		$this->title = array_merge([
		    "*" => $title,
		    "size" => 5,
			"@id" => "{$this->id}Label",
			".offcanvas-title" => true,
		], $options);
	}
	//--------------------------------------------------------------------------------
	public function set_trigger($html) {
		$this->trigger = $html;
	}
	//--------------------------------------------------------------------------------
	public function set_body($fn, $options = []) {
		// options
		$options = array_merge([
		], $options);

		// done
		$this->body = [
			"fn" => $fn,
			"options" => $options,
		];
	}
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	public function build($options = []) {

		// options
		$options = array_merge([
		], $options);

		$buffer = \com\ui::make()->buffer();

		$buffer->add($this->trigger);

		$buffer->div_([".offcanvas offcanvas-start" => true, "@tabindex" => "-1", "@id" => $this->id, "@aria-labelledby" => "{$this->id}Label", ]);

			$buffer->div_([".offcanvas-header" => true, ]);
				if($this->title) $buffer->{"h{$this->title["size"]}"}($this->title);
				$buffer->button(["@type" => "button", ".btn-close" => true, "@data-bs-dismiss" => "offcanvas", "@aria-label" => "Close", ]);
			$buffer->_div();

			$buffer->div_([".offcanvas-body" => true, ]);
				// body
				if ($this->body) {
					$buffer->div_(".card-body", $this->body["options"]);
					call_user_func($this->body["fn"], $buffer);
					$buffer->_div();
				}
			$buffer->_div();
		$buffer->_div();

		return $buffer->build();

	}
	//--------------------------------------------------------------------------------
}