<?php

namespace LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap;

/**
 * Class card_group
 * @package app\ui\set\bootstrap
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class collapse extends \LiquidedgeApp\Octoapp\app\app\ui\intf\element {

    //--------------------------------------------------------------------------------
	// fields
	//--------------------------------------------------------------------------------
	protected $title;
	protected $item_arr = [];
	protected $options = [];
	protected $id;
	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	protected function __construct($options = []) {
	    $options = array_merge([
	        "id" => \LiquidedgeApp\Octoapp\app\app\str\str::get_random_id("collpase"),
	    ], $options);

		// init
        $this->set_id($options["id"]);
		$this->name = "Collapse";
		$this->options = $options;
	}
	//--------------------------------------------------------------------------------
	public function add_link($href, $label, $options = []) {
		$this->item_arr[] = array_merge( [
		    "id" => "link_".sizeof($this->item_arr),
			"label" => $label,
			"@href" => $href,
			"icon" => false,
			"type" => "link",
			".collapse-item" => true,
		], $options);
	}
	//--------------------------------------------------------------------------------

    /**
     * @param mixed $id
     */
    public function set_id($id): void {
        $this->id = $id;
    }
	//--------------------------------------------------------------------------------

    /**
     * @return mixed
     */
    public function get_id() {
        return $this->id;
    }
	//--------------------------------------------------------------------------------
	public function add_divider($options = []) {
		$this->item_arr[] = array_merge( [
		    "id" => "divider_".sizeof($this->item_arr),
			"type" => "divider",
		], $options);
	}
	//--------------------------------------------------------------------------------
	public function add_html($html, $options = []) {

	    if(!is_string($html) && is_callable($html)){
		    $html = $html();
        }

		$this->item_arr[] = array_merge( [
		    "id" => "html_".sizeof($this->item_arr),
			"html" => $html,
			"type" => "html",
		], $options);
	}
	//--------------------------------------------------------------------------------
	public function add_header($size, $title, $options = []) {
		$this->item_arr[] = array_merge([
		    "id" => "header_".sizeof($this->item_arr),
			"size" => $size,
			"title" => $title,
			"type" => "header",
			".collapse-header" => true
		], $options);
	}
	//--------------------------------------------------------------------------------
	/**
	 * @param string $title
	 */
	public function set_title(string $title): void {
		$this->title = $title;
	}
    //--------------------------------------------------------------------------------
	public function build($options = []) {
		$options = array_merge([
		    "title" => $this->title,
		    "icon" => false,
		    "/link" => [],
		    "/collapse_inner" => [
		    	".py-2 rounded" => true,
			],
		], $options, $this->options);

		$buffer = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();

		$buffer->div_([".collapse-wrapper" => true]);

			$link = $options["/link"];
			$link[".nav-link collapsed"] = true;
			$link["@data-bs-target"] = "#{$this->id}";
			$link["@aria-bs-controls"] = $this->id;
			$link["@href"] = "#";
			$link["@data-bs-toggle"] = "collapse";
			$link["@aria-expanded"] = "false";
			$link["icon"] = $options["icon"];

			$buffer->xlink("#", $options["title"], $link);

			$options["@id"] = $this->id;
			$options[".collapse"] = true;
			$buffer->div_($options);

				$collapse_inner = $options["/collapse_inner"];
				$collapse_inner[".collapse-inner"] = true;

				$buffer->div_($collapse_inner);
					foreach ($this->item_arr as $item){
						switch ($item['type']){
							case "link": $buffer->xlink($item["@href"], $item["label"], $item); break;
							case "divider": $buffer->div([".collapse-divider" => true]); break;
							case "header": $buffer->xheader($item["size"], $item["title"], false, $item); break;
							case "html": $buffer->add($item["html"]); break;
						}
					}

				$buffer->_div();
			$buffer->_div();

		$buffer->_div();

		return $buffer->build();

	}
	//--------------------------------------------------------------------------------
}