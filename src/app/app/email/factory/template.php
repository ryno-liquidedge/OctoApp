<?php

namespace LiquidedgeApp\Octoapp\app\app\email\factory;

/**
 * @package app\email\factory
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */

class template extends \LiquidedgeApp\Octoapp\app\app\intf\standard {
	//--------------------------------------------------------------------------------
  	// properties
  	//--------------------------------------------------------------------------------
	/**
	 * @var mixed
	 */
	private $body;

    protected $template_dir = false;

    //--------------------------------------------------------------------------------
  	// functions
  	//--------------------------------------------------------------------------------
    public function __construct($options = []) {

        $options = array_merge([
            "template" => "standard"
        ], $options);

        $this->template_dir = \core::$folders->get_app_app()."/email/factory/templates";

        $this->set_template($options["template"]);

    }
    //--------------------------------------------------------------------------------
	public function set_style($style) {
		$this->body = str_replace("%style%", \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->tag()->style(["*" => $style]), $this->body);
	}
    //--------------------------------------------------------------------------------
    public function set_template($template){

        $template_name = $template;

        $template = "$this->template_dir/$template_name.html";

        if(file_exists($template)){
            $this->body = file_get_contents("$this->template_dir/$template_name.html");
        }else{
            \LiquidedgeApp\Octoapp\app\app\error\error::create("The template could not be found.");
        }
    }
    //--------------------------------------------------------------------------------
    public function add_argument($field_name, $value) {
       $this->body = str_replace("%$field_name%", $value, $this->body);
    }
    //--------------------------------------------------------------------------------
    public function get_html() {
       return $this->body;
    }
	//--------------------------------------------------------------------------------
}