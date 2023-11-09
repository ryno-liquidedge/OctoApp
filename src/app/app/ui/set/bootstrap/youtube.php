<?php

namespace LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap;

/**
 * Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class youtube extends \com\ui\intf\element {
	//--------------------------------------------------------------------------------
	// properties
	//--------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	public function __construct($options = []) {

		// init
        $this->name = "video";

	}
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	public function build($options = []) {

	    $options = array_merge([
	        "link" => false,
            "@allowfullscreen" => true,
            "@frameborder" => "0",
            "width" => "100%",
            "height" => "400px",
        ], $options);


	    $youtube_id = \LiquidedgeApp\Octoapp\app\app\ui\inc\youtube\youtube::get_youtube_id_from_link($options["link"]);
        $options["@width"] = $options["width"];
        $options["#width"] = $options["width"];

        $options["@height"] = $options["height"];
        $options["#height"] = $options["height"];

        $options["@src"] = "https://www.youtube.com/embed/{$youtube_id}";

		// init
        $buffer = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();
        $buffer->iframe($options);
		return $buffer->get_clean();
	}
	//--------------------------------------------------------------------------------
}