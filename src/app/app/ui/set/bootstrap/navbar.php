<?php

namespace LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap;

/**
 * Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class navbar extends \com\ui\set\bootstrap\navbar {
	//--------------------------------------------------------------------------------
	// properties
	//--------------------------------------------------------------------------------
	public $options = [];
	public $item_arr = [];
	private $brand = [];
	public $html = false;
	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	public function __construct($options = []) {
		// init
        $this->name = "Navbar";
		$this->html = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();
		$this->options = $options;
	}
	//--------------------------------------------------------------------------------
    public function set_brand($html, $link, $options = []) {
        $this->brand = array_merge([
            "html" => $html,
            "link" => $link,
            "/wrapper" => [".p-0" => true],
        ], $options);
    }
	//--------------------------------------------------------------------------------
	/**
	 * Adds a link to the navbar. When no link is provided, 'javascript:void(0);' will
	 * be used as link instead. To add submenus, add them with /'s inbetween as the label.
	 * For example: Home/Profile will create a submenu item called Profile under the
	 * Home menu. When specifying a full path starting with http as the link, the link
	 * will open with target=_blank when clicked. Indicators for non-LIVE environment
	 * and DEV only roles will be added to the label text for easy identification.
	 *
	 * @param string $label <p>The text label. Seperate sub menus with a '/'.</p>
	 * @param string $link <p>The link to go to when clicked. Anything that would be valid for an href attribute, is acceptable.</p>
	 * @param string $options[icon] <p>The icon name to use.</p>
	 * @param string $options[role] <p>The item will only be added if the logged-in role matches this value. More than one role seperated with a space.</p>
	 * @param string $options[environment] <p>The item will only be added if the current environment matches this value. Seperate more than one environment with a space.</p>
	 *
	 * @return boolean <p>True if item was added and false if not.</p>
	 */
	public function add_item($label, $link = false, $options = []) {
		// options
		$options = array_merge([
			"icon" => false,
			"role" => false,
			"token" => false,
			"environment" => false,
			"separator" => "/",
//			".py-3" => true,
			"/li" => [],
			"/icon" => [],
		], $options);

		// check role
		if ($options["role"] && !\com\user::auth_for($options["role"])) return false;
		if ($options["token"] && !\core::$app->get_token()->check($options["token"])) return false;

		// check environment
		if ($options["environment"] && !\core::auth_environment($options["environment"])) return false;

		// sub menu item
		$label_arr = explode($options["separator"], $label);
		$count = count($label_arr);
		$current_item = &$this->item_arr;

		for ($i = 1; $i <= $count; $i++) {
			$current_label = $label_arr[$i - 1];
			if ($i == $count) {
				// label
				$label_text = $current_label;
				if ($options["environment"] && $options["environment"] != "LIVE") $label_text .= " - Env:[{$options["environment"]}]";
				if ($options["role"] == "DEV") $label_text .= " - Role:[{$options["role"]}]";

				// add item
				$current_item[$current_label] = [
					"label" => $label_text,
					"link" => $link,
					"icon" => $options["icon"],
					"/icon" => $options["/icon"],
					"submenu" => [],
					"/options" => $options,
				];
			}
			else {
				if (!isset($current_item[$current_label])) return false;
				$current_item = &$current_item[$current_label]["submenu"];
			}
		}

		// done
		return true;
	}

	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	public function build($options = []) {

	    $options = array_merge([
		    ".navbar navbar-light navbar-expand-lg" => true,
		    ".sticky-top" => false,
			"@role" => "navigation",
			"/ul" => [".navbar-nav" => true],
			"/collapse" => [".justify-content-end" => true],
			"/container" => [".container-fluid" => true],
		], $options, $this->options);

	    $buffer = $this->html;
		$item_arr = $this->item_arr;
		$tid = \com\session::$current->session_uid;

        $buffer->nav_($options);
            $buffer->div_($options["/container"]);
                if($this->brand){
                    $this->brand["/wrapper"][".navbar-brand"] = true;
                    $this->brand["/wrapper"]["@href"] = $this->brand["link"];
                    $buffer->a_($this->brand["/wrapper"]);
                        $buffer->add($this->brand["html"]);
                    $buffer->_a();
                }

                $buffer->button_([".navbar-toggler" => true, "@data-bs-toggle" => "collapse", "@data-bs-target" => "#{$tid}", ]);
                    $buffer->span([".visually-hidden" => true, "*" => "Toggle navigation"]);
                    $buffer->span([".navbar-toggler-icon" => true, ]);
                $buffer->_button();
                
                $options["/collapse"]["@id"] = $tid;
                $options["/collapse"][".collapse"] = true;
                $options["/collapse"][".navbar-collapse"] = true;
                
                $buffer->div_($options["/collapse"]);
                
                    $buffer->ul_($options["/ul"]);
                        $this->dropdown($buffer, $item_arr);
                    $buffer->_ul();

                    \events::on_view_navigation($buffer);
                            
                $buffer->_div();
            $buffer->_div();
        $buffer->_nav();

        return $buffer->get_clean();

    }
	//--------------------------------------------------------------------------------
	protected function dropdown(&$html, $item_arr, $level = 0) {
		// get keys
		$item_keys = array_keys($item_arr);

		foreach ($item_arr as $item_key => $item_item) {
			// get numeric index
			$key_index = array_search($item_key, $item_keys);

			// divider
			if (preg_match("/^-$/", $item_item["label"])) {
				$html->div(".dropdown-divider");
				continue;
			}

			// subheader
			if (preg_match("/^-.*/", $item_item["label"])) {
				// label
				$label = substr($item_item["label"], 1);

				// divider, only if this is not the first item
				if ($key_index) $html->div(".dropdown-divider");

				// item
				$html->h6_(".dropdown-header");
					if ($item_item["icon"]) $html->xicon($item_item["icon"], ["space" => true]);
					$html->content($label);
				$html->_h6();
				continue;
			}

			// submenu
			if ($item_item["submenu"]) {
				if (!$level) {
					$html->li_(array_merge([".nav-item dropdown me-2" => true], $item_item["/options"]["/li"]));
						$tid = \com\session::$current->session_uid;
						$html->xbutton($item_item["label"], false, array_merge([
							"@id" => $tid,
							"@class" => 'dropdown-toggle nav-link',
							"@role" => "button",
							"@data-bs-toggle" => "dropdown",
							"@aria-haspopup" => "true",
							"@aria-expanded" => "false",
							"caret" => true,
							"icon" => $item_item["icon"],
							"/icon" => [".me-2" => false]
						], $item_item["/options"]));

						$html->ul_(".dropdown-menu", ["@aria-labelledby" => $tid, ".m-0 shadow fast"=>true, ".fade-in bottom" => !\LiquidedgeApp\Octoapp\app\app\http\http::is_mobile()]);
							$this->dropdown($html, $item_item["submenu"], $level + 1);
						$html->_ul();
					$html->_li();
				}
				else {
					$html->li_(".dropdown-submenu");
						$html->a(".dropdown-item .dropdown-toggle ^{$item_item["label"]}", ["@href" => "#"]);
						$html->ul_(".dropdown-menu");
							$this->dropdown($html, $item_item["submenu"], $level + 1);
						$html->_ul();
					$html->_li();
				}
			}
			else {
				if (!$level) $html->li_(".nav-item .me-2");
				$this->link($html, $item_item, $level);
				if (!$level) $html->_li();
			}
		}
	}
	//--------------------------------------------------------------------------------
	protected function link(&$html, $item, $level) {
		// target
		$options = isset($item["/options"]) ? $item["/options"] : [];

//		if (preg_match("/^http/i", $item["link"])) $options["@target"] = "_blank";

		// href
		$href = ($item["link"] ? $item["link"] : "#");

		// tab index
		$options["@tabindex"] = ($level ? -1 : false);

		// icon
		$options["icon"] = $item["icon"];
		$options["/icon"] = array_merge([".me-2" => true], $item["/icon"]);
		if (!$level) {
			$options[".nav-link"] = true;
		}
		else $options[".dropdown-item"] = true;

		// html
		$html->xlink($href, $item["label"], $options);
	}
	//--------------------------------------------------------------------------------
}