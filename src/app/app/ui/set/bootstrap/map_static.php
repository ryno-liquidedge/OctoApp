<?php

namespace LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap;

/**
 * @package app\ui\set\bootstrap
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class map_static extends \com\ui\intf\element {
	//--------------------------------------------------------------------------------
	// fields
	//--------------------------------------------------------------------------------

	private $url = "https://maps.google.com/maps";
	private $width = "100%";
	private $height = "450";
	private $zoom = 12;

	private array $marker = [];
	private array $options = [];

	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	protected function __construct($options = []) {
		$this->options = $options;
	}
	//--------------------------------------------------------------------------------
	public function build_params() {

	    $params = [];
	    $params["width"] = $this->width;
	    $params["height"] = $this->height;
	    $params["hl"] = "en";
	    $params["q"] = "{$this->marker["latitude"]},{$this->marker["longitude"]}+({$this->marker["title"]})";
	    $params["z"] = $this->zoom;
	    $params["ie"] = "UTF8";
	    $params["iwloc"] = "B";
	    $params["output"] = "embed";

		return htmlentities(http_build_query($params));
	}
	//--------------------------------------------------------------------------------
	public function build($options = []) {

		$options = array_merge([
			"@width" => $this->width,
			"@height" => $this->height,
			"@frameborder" => "0",
			"@scrolling" => "no",
			"@marginheight" => "0",
			"@marginwidth" => "0",
			"@src" => $this->url,
			"allowfullscreen" => "true",
			"params" => [],
		], $options, $this->options);


		$options["@src"] = "{$options["@src"]}?{$this->build_params()}";

		$buffer = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();
		$buffer->div_([".google-maps" => true]);
			$buffer->iframe($options);
		$buffer->_div();

		return $buffer->build();
	}
	//--------------------------------------------------------------------------------

	/**
	 * @param $title
	 * @param $latitude
	 * @param $longitude
	 * @param array $options
	 */
	public function set_marker($title, $latitude, $longitude, $options = []) {
		$this->marker = array_merge([
            "title" => $title,

            "latitude" => $latitude,
            "longitude" => $longitude,
        ], $options);
	}
	//--------------------------------------------------------------------------------

	/**
	 * @param int $zoom
	 */
	public function set_zoom(int $zoom): void {
		$this->zoom = $zoom;
	}
	//--------------------------------------------------------------------------------

	/**
	 * @param string $width
	 */
	public function set_width(string $width): void {
		$this->width = $width;
	}
	//--------------------------------------------------------------------------------

	/**
	 * @param string $height
	 */
	public function set_height(string $height): void {
		$this->height = $height;
	}
	//--------------------------------------------------------------------------------
}