<?php

namespace LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap;

/**
 * Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class video extends \com\ui\intf\element {
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
	        "@id" => \LiquidedgeApp\Octoapp\app\app\str\str::get_random_id("media"),
	        "@src" => false,
	        "@loop" => "loop",
	        "@autoplay" => "",
	        "@playsinline" => "",
	        "@muted" => "",
	        "@preload" => "none",

	        "type" => "video/mp4",
	    ], $options);

		// init
        $buffer = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();
        $buffer->video_($options);
            $buffer->source(["@type" => $options["type"], "@src" => $options["@src"], ]);
        $buffer->_video();
		return $buffer->get_clean();
	}
	//--------------------------------------------------------------------------------
}