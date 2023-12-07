<?php

namespace acc\router\region\website;

/**
 * @package acc\router\region\website
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class navbar implements \com\router\int\region {

    protected $control = false;
    protected $slug = false;

	//--------------------------------------------------------------------------------
	use \com\router\tra\region;
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	public function render() {

	    $this->control = \core::$app->get_request()->get_control();
	    $this->slug = \app\http::get_slug();

        // html
		$buffer = \app\ui::make()->buffer();
		\LiquidedgeApp\Octoapp\app\app\js\js::set_script_top_force(true);


		$buffer->section_([".border-bottom border-3 border-secondary" => true]);
            $navbar = \app\ui::make()->navbar();
            $navbar->set_brand(\app\ui::make()->image(\app\http::get_stream_url(\db_settings::get_logo()), [".img-fluid img-logo show-overlay" => true]), \app\http::get_seo_url("ui_home"));
            foreach ($this->get_nav_links() as $link_data){
            	$link_data[".show-overlay"] = true;
                $navbar->add_item($link_data["label"], $link_data["url"], $link_data);
            }
            $buffer->add($navbar->build([
                "@id" => "website_nav",
                ".shadow-sm" => true,
                ".navbar navbar-expand-lg sticky-top bg-body" => true,
                "/container" => ["@class" => "container-lg"],
            ]));

		$buffer->_section();

		// done
		return $buffer->build();
	}
	//--------------------------------------------------------------------------------
    public function get_nav_links() {
        $link_arr = [];

        $fn_add_link = function($label, $role, $url, $options = [])use(&$link_arr){

            $options = array_merge([
                "context" => false,
                "param" => false,
                "auth" => false,
            ], $options);

            if($options["auth"] && !\core::$app->get_token()->check($options["auth"]))
                return;

            if($options["context"]){
                $url = \app\http::get_seo_url($options["context"], $options["param"]);
                if(!isset($options[".active"])) $options[".active"] = $this->control == \app\http::get_seo_control($options["context"]);
            }

            $link_arr[] = array_merge([
                "label" => $label,
                "role" => $role,
                "url" => $url,
                "separator" => "|",
                ".text-uppercase" => true,
            ], $options);
        };

        $fn_add_link("Home", false, false, ["context" => "ui_home"]);
        $fn_add_link("About", false, false, ["context" => "ui_about"]);
        $fn_add_link("Contact", false, false, ["context" => "ui_contact"]);


        return $link_arr;
    }
	//--------------------------------------------------------------------------------
	// static
	//--------------------------------------------------------------------------------
    /**
     * @param array $options
     * @return static
     */
	public static function make($options = []) {
		return new static($options);
	}
	//--------------------------------------------------------------------------------
}