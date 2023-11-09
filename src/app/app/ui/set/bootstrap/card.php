<?php

namespace LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap;

/**
 * Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class card extends \com\ui\intf\card {
	//--------------------------------------------------------------------------------
	// properties
	//--------------------------------------------------------------------------------
  	protected $body = null;
  	protected $header = null;
  	protected $options = null;
  	protected $image = null;
	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
    protected function __construct($options = []) {
    	$this->name = "Card";
    	$this->options = $options;
    }
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	public function build($options = []) {
		// options
		$options = array_merge([
		], $this->options, $options);

		// html
		$html = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->html_buffer();
		$html->div_(".card", $options);
		{
		    //image
            if($this->image){
                $this->image[".card-img-top w-100 d-block fit-cover"] = true;
                $html->img($this->image);
            }

			// header
			if ($this->header) {
				$html->div(".card-header ^{$this->header["header"]}", $this->header["options"]);
			}

			// body
			if ($this->body) {
				$html->div_(".card-body", $this->body["options"]);
				call_user_func($this->body["fn"], $html);
				$html->_div();
			}
		}
		$html->_div();

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
    public function image($src, $options = []) {
        $this->image = array_merge([
            "@src" => $src
        ], $options);
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