<?php

namespace LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap;

/**
 * @package app\ui\set\bootstrap
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class modal extends \com\ui\intf\element {
	//--------------------------------------------------------------------------------
	// fields
	//--------------------------------------------------------------------------------
	protected $id;
	protected $trigger = false;

	public $enable_header = true;
	public $enable_footer = true;


	protected $title;
	protected $body;
	protected $footer;

	protected $options = [];

	/**
	 * @var \com\ui\intf\buffer
	 */
	protected $buffer;
	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	protected function __construct($options = []) {
		// init
		$this->name = "Modal";

		//buffer
		$this->buffer = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();


		$options = array_merge([
		    "id" => \LiquidedgeApp\Octoapp\app\app\str\str::get_random_id("modal")
		], $options);

		$this->id = $options["id"];
	}
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	/**
	 * @return mixed
	 */
	public function get_id() {
		return $this->id;
	}
	//--------------------------------------------------------------------------------

	/**
	 * @param mixed $title
	 */
	public function set_title($title): void {
		$this->title = $title;
	}
	//--------------------------------------------------------------------------------
	/**
	 * @param mixed $footer
	 */
	public function set_footer($footer): void {
		$this->footer = $footer;
	}
	//--------------------------------------------------------------------------------

	/**
	 * @param mixed $body
	 */
	public function set_body($body): void {
		$this->body = $body;
	}
	//--------------------------------------------------------------------------------
	public function set_trigger($html) {
		$this->trigger = $html;
	}
	//--------------------------------------------------------------------------------
	public function build($options = []) {
		// options
		$options = array_merge([
			"@tabindex" => "-1"
  		], $options);

		$options["@id"] = $this->id;
		$options["@aria-labelledby"] = "{$this->id}_label";
		$options["@aria-hidden"] = true;
		$options[".modal"] = true;
		$options[".fade"] = true;

		// trigger
		if($this->trigger) $this->buffer->add($this->trigger);

		$this->buffer->div_($options);
			$this->buffer->div_([".modal-dialog" => true, ]);
				$this->buffer->div_([".modal-content" => true, ]);
					if($this->enable_header){
						$this->buffer->div_([".modal-header" => true, ]);
							if($this->trigger) $this->buffer->xheader(5, $this->title, false, [".modal-title" => true, "@id" => $options["@aria-labelledby"]]);
							$this->buffer->button([".btn-close" => true, "@data-bs-dismiss" => "modal", "@aria-label" => "Close"]);
						$this->buffer->_div();
					}

					$this->buffer->div_([".modal-body" => true, ]);

						$fn_body = $this->body;
						if(is_callable($fn_body)) $fn_body($this->buffer);
						else $this->buffer->add($fn_body);

					$this->buffer->_div();

					if($this->enable_footer && $this->footer){
						$this->buffer->div_([".modal-footer" => true, ]);

							$fn_footer = $this->footer;
							if(is_callable($fn_footer)) $fn_footer($this->buffer);
							else $this->buffer->add($fn_footer);

						$this->buffer->_div();
					}

				$this->buffer->_div();
			$this->buffer->_div();
		$this->buffer->_div();

		return $this->buffer->build();

		// done
	}
	//--------------------------------------------------------------------------------
}