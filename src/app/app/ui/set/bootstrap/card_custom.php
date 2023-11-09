<?php

namespace LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap;

/**
 * @package app\ui\set\bootstrap
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class card_custom extends \com\ui\intf\element {
	//--------------------------------------------------------------------------------
	// properties
	//--------------------------------------------------------------------------------
	/**
	 * @var \com\ui\intf\buffer
	 */
    protected $buffer;

	/**
	 * @var mixed
	 */
    protected $html;

	/**
	 * @var array
	 */
    protected $link_arr = [];

	/**
	 * @var \com\ui\intf\dropdown
	 */
    protected $dropdown;

    private $heading;
	private $sub_heading;
	private $icon;
	private $color;

    protected $options = [
        "/card" => [
            ".card" => true,
            ".shadow" => true
        ],
        "/card-heading" => [
            ".card-heading py-3" => true,
        ],
        "/card-body" => [
            ".card-body" => true,
        ],
        "/heading" => [],
        "/sub_heading" => [],
        "/color" => [],
        "/icon" => [],
        "/dropdown" => [],
    ];

	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	public function init_options($key, $options = []) {
		return $this->options[$key] = array_merge($this->options[$key], $options);
	}
    //--------------------------------------------------------------------------------

	/**
	 * @param $dropdown
	 * @param array $options
	 * @return $this
	 */
    public function set_dropdown($dropdown, $options = []): card_custom {

    	$options = array_merge([
    	    "label" => false
    	], $options);

    	if(is_callable($dropdown)) $dropdown = $dropdown();

        $this->dropdown = $dropdown;
        $this->options["/dropdown"] = $options;

        return $this;
    }
    //--------------------------------------------------------------------------------

	/**
	 * @param $heading
	 * @param array $options
	 * @return $this
	 */
    public function set_heading($heading, $options = []): card_custom {

        $options = array_merge([
            "sub_heading" => false
        ], $options);

        $this->heading = $heading;
        $this->sub_heading = $options["sub_heading"];

        return $this;

    }
    //--------------------------------------------------------------------------------

	/**
	 * @param $icon
	 * @param array $options
	 * @return $this
	 */
    public function set_icon($icon, $options = []): card_custom {
        $this->icon = $icon;
        $this->options["/icon"] = array_merge($options, $this->options["/icon"]);

        return $this;
    }
    //--------------------------------------------------------------------------------

	/**
	 * @param $color
	 * @param array $options
	 * @return $this
	 */
    public function set_color($color, $options = []): card_custom {
        $this->color = $color;
        $this->options["/color"] = array_merge($options, $this->options["/color"]);

        return $this;
    }
	//--------------------------------------------------------------------------------

	/**
	 * @param $buffer
	 * @param $html
	 * @return $this
	 */
    public function parse_html(&$buffer, $html): card_custom {

        if(is_string($html)) $buffer->add($html);
        if(is_callable($html)){
            $buffer->div_([".card-custom-html" => true]);
                $html($buffer);
            $buffer->_div();
        }

        return $this;
    }
	//--------------------------------------------------------------------------------

	/**
	 * @param $mixed
	 * @return $this
	 */
    public function set_html($mixed) {

		if(is_callable($mixed)) $mixed = $mixed();

        $this->html = $mixed;

        return $this;
    }
    //--------------------------------------------------------------------------------

	/**
	 * @param $label
	 * @param false $onclick
	 * @param array $options
	 * @return card_custom
	 */
    public function add_link($label, $onclick = false, $options = []): card_custom {
        $this->link_arr[] = array_merge([
            "label" => $label,
            "@href" => "#!",
            "!click" => $onclick,
        ], $options);

        return $this;
    }
    //--------------------------------------------------------------------------------
    public function build($options = []) {

        $options = array_merge([
            "heading" => $this->heading,
            "sub_heading" => $this->sub_heading,
            "icon" => $this->icon,
            "color" => $this->color,
            "html" => $this->html,
            "!click" => false,
        ], $options);


        $sub_heading = $options["sub_heading"];
        $heading = $options["heading"];
        $icon = $options["icon"];
        $color = $options["color"];
        $html = $options["html"];

        if($options["!click"]){
            $this->options["/card"]["!click"] = $options["!click"];
            $this->options["/card"][".pointer"] = true;
        }

        if(!$heading && !$html) return "";

        //build
        $buffer = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();

        $buffer->div_($this->init_options("/card", [".border-{$color} border-4 border-start" => $color]));

            if($heading || $this->dropdown){
                $buffer->div_([".card-heading px-3 pt-2 d-flex justify-content-between align-items-center" => true]);
                    if($heading) $buffer->xheader(4, $heading, $sub_heading, [".text-{$color} font-weight-bold m-0" => true, "html" => true]);
                    else $buffer->xheader(4, "", false, [".text-{$color} font-weight-bold m-0" => true, "html" => true]);

                    if($this->dropdown){

                        if(is_callable($this->dropdown)) {
                            $FN = $this->dropdown;
                            $this->dropdown = $FN();
                        }

                        if($this->dropdown instanceof \com\ui\intf\element){
                            $buffer->xlink($this->dropdown, $this->options["/dropdown"]["label"], ["icon" => "fa-bars float-right", "icon_left" => false, ".text-gray" => true]);
                        }
                    }


                $buffer->_div();
            }

            $buffer->div_($this->init_options("/card-body"));

                $buffer->div_([".row align-items-center no-gutters" => true]);
                    $buffer->div_([".col" => true]);

                        //html
                        if($html) $this->parse_html($buffer, $html);

                        //links
                        foreach ($this->link_arr as $link){
                            $link[".card-link"] = true;
                            $buffer->a("*{$link["label"]}", $link);
                        }
                    $buffer->_div();

                    if($icon){
                        $buffer->div_([".col-auto" => true]);
                            $buffer->i([".fas {$icon} fa-2x text-gray-300" => true]);
                        $buffer->_div();
                    }

                $buffer->_div();
            $buffer->_div();
        $buffer->_div();

        return $buffer->build();

    }
	//--------------------------------------------------------------------------------
}