<?php

namespace LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap;

/**
 * Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class fieldset extends \com\ui\intf\element {
	//--------------------------------------------------------------------------------
	// properties
	//--------------------------------------------------------------------------------
  	protected $body = null;
  	protected $header = null;
  	protected $options = null;

  	/**
	 * @var \com\ui\intf\dropdown
	 */
    protected $dropdown;
	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
    protected function __construct($options = []) {
    	$this->name = "Fieldset";
    	$this->options = $options;
    }
	//--------------------------------------------------------------------------------
	// functions
    //--------------------------------------------------------------------------------

	/**
	 * @param $dropdown
	 * @param array $options
	 * @return $this
	 */
    public function set_dropdown($dropdown, $options = []) {

    	$options = array_merge([
    	    "label" => false
    	], $options);

    	if(is_callable($dropdown)) $dropdown = $dropdown();

        $this->dropdown = $dropdown;
        $this->options["/dropdown"] = $options;

        return $this;
    }
	//--------------------------------------------------------------------------------
	public function build($options = []) {
		// options
		$options = array_merge([
		    "no_border" => false,
		    "/header" => [],
		    "/body" => [],
		], $this->options, $options);

		if($options["no_border"]){
            $options[".border-0"] = true;
        }

		// html
		$html = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();
		$html->fieldset_(".ui-fieldset", $options);
		{
			// header
			if ((isset($this->header["header"]) && $this->header["header"]) || $this->dropdown) {
			    $html->div_(".fieldset-header .d-flex", array_merge($this->header["options"], $options["/header"]));
			        $html->add($this->header["header"]);

			        if($this->dropdown){

                        if(is_callable($this->dropdown)) {
                            $FN = $this->dropdown;
                            $this->dropdown = $FN();
                        }

                        if($this->dropdown instanceof \com\ui\intf\element){
                            $this->dropdown->set_option(".ms-auto", true);
                            $html->xlink($this->dropdown, false, ["icon" => "fa-bars", "icon_left" => false, ".text-gray" => true]);
                        }
                    }
			    $html->_div();
			}

			// body
			if ($this->body) {
				$html->div_(".fieldset-body", array_merge($this->body["options"], $options["/body"]));
				call_user_func($this->body["fn"], $html);
				$html->_div();
			}
		}
		$html->_fieldset();

		// done
		return $html->build();
	}
	//--------------------------------------------------------------------------------
	public function body($fn, $options = []) {
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
	public function header($header, $options = []) {
		$this->header = [
			"header" => $header,
			"options" => $options,
		];
	}
  	//--------------------------------------------------------------------------------
}