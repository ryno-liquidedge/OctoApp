<?php

namespace LiquidedgeApp\Octoapp\app\app\seo;


class seo extends \LiquidedgeApp\Octoapp\app\app\intf\standard {

	/**
	 * @var array
	 */
	protected $item_arr = [];

	/**
	 * @var string[]
	 */
	public static $whitelist_arr = [
		"website.index/reset_password",
		"website.index/error",
	];

	/**
	 * @var bool|mixed|null
	 */
	protected $is_seo_enabled = false;
	protected $is_whitlist = false;

    //--------------------------------------------------------------------------
	/**
	 * seo constructor.
	 * @param array $options
	 */
	protected function __construct($options = []) {
		$options = array_merge([
		    "is_seo_enabled" => $this->is_url_rewrite_enabled()
		], $options);

		$this->is_seo_enabled = $options["is_seo_enabled"];

	}
	//--------------------------------------------------------------------------
	public static function is_url_rewrite_enabled(){
		return \core::$app->get_instance()->get_option("app.website.enable_url_rewrite");
	}
	//--------------------------------------------------------------------------
	public static function is_whitelisted_url($control){

		return in_array($control, self::$whitelist_arr);
	}
	//--------------------------------------------------------------------------
	public function add_item($context, $control, $seo_value, $options = []) {
		$options = array_merge([
			"url" => "/index.php?c={$control}",
			"has_param" => false,
		], $options);

		$options["context"] = $context;
		$options["control"] = $control;
		$options["seo_value"] = $seo_value;

		$this->item_arr[] = $options;
	}
	//--------------------------------------------------------------------------
	/**
	 * @return bool|mixed|null
	 */
	public function is_seo_enabled(): ?bool {
		return $this->is_seo_enabled;
	}
	//--------------------------------------------------------------------------
	/**
	 * @return array
	 */
	public function get_item_arr(): array {
		return $this->item_arr;
	}
	//--------------------------------------------------------------------------
	/**
	 * @return \com\context
	 */
	public function get_context($context){

		$context = new \LiquidedgeApp\Octoapp\app\app\context\context($context);

		//default
		$context->filter_default("url", ["standard" => "/index.php?c={$context->get_type()}", "seo" => "/home"]);

		//init items
		foreach ($this->item_arr as $item){

			$key = $item["context"];
			$control = $item["control"];
			$seo_value = $item["seo_value"];
			$url = $item["url"];

			$context->filter_type($key, "url", [ "standard" => $url, "seo" => $seo_value ]);
			$context->filter_type($key, "control", $control);
			$context->filter_type($control, "url", [ "standard" => $url, "seo" => $seo_value ]);
			$context->filter_type($control, "control", $control);
		}

		return $context;

	}
	//--------------------------------------------------------------------------
	public function get_url($context, $mixed = false, $options = []){

    	$dbobj = false;
		if ($mixed) {
			if ($mixed instanceof \com\db\row) $dbobj = $mixed;
			if (is_string($mixed)) $dbobj = $mixed;
			if (is_numeric($mixed)) $options["key"] = $mixed;
		}

        return $this->get_seo_url($context, $dbobj, $options);

    }
	//--------------------------------------------------------------------------
	private function get_seo_url($context, $dbobj = false, $options = []) {

		$options = array_merge([
	        "key" => false,
	        "data" => [],
	        "absolute_url" => \core::$app->get_instance()->get_option("url.absolute"),
	        "context" => false,
	    ], $options);


		$context = $this->get_context($context);

		if($options["context"]){
			$options["data"]["context"] = $options["context"];
		}

		//determine what type of URL we want to return
	    $type = $this->is_seo_enabled ? "seo" : "standard";

	    $data = "";
	    if($options["key"]) $data = "/{$options["key"]}";

	    if($options["data"]){
	    	$data .= ($this->is_seo_enabled ? "?" : "&").http_build_query($options["data"]);
        }

	    //determine the url to return
	    $url = $context->url[$type];

	    if(!$this->is_seo_enabled && \core::$app->get_instance()->get_option("url.sub.folder.fix")) {
	        $url = str_replace("/index.php?c=", "?c=", $url);
        }

	    //append optional db data
        if($dbobj){
            $slug = false;
            if($dbobj instanceof \com\db\row){
            	if(property_exists($dbobj->db, "slug")) $slug = $dbobj->get_seo_name();
            	else $slug = $url .= "/{$dbobj->id}";
			} else if(is_string($dbobj)) $slug = $dbobj;

            if($this->is_seo_enabled && $slug) $url .= "/{$slug}";
            else if($slug) $url .= "&slug={$slug}";
        }

        if($options["absolute_url"]){
        	return \core::$app->get_instance()->get_url()."{$url}{$data}";
		}

        return "{$url}{$data}";
	}
    //--------------------------------------------------------------------------
	public static function make($options = []) {
		$instance = parent::make($options);

		$instance->add_item("ui_home", "website.index/home", "/home");
		$instance->add_item("ui_faqs", "website.index/faqs", "/faqs");
		$instance->add_item("ui_contact", "website.index/contact", "/contact");
		$instance->add_item("ui_login", "index/vlogin", "/login");
		$instance->add_item("ui_register", "website.index/register", "/register");
		$instance->add_item("ui_error", "website.index/error", "/error", ["has_param" => true]);
		$instance->add_item("ui_message", "website.index/message", "/message", ["has_param" => true]);
		$instance->add_item("ui_change_password", "website.index.modal/reset_password", "/change_password");
		$instance->add_item("ui_policy", "website.index/policy", "/policy", ["has_param" => true]);
		$instance->add_item("ui_logout", "website.index.functions/xlogout", "/logout");
		$instance->add_item("ui_about", "website.index/about", "/about-us");
		$instance->add_item("ui_news", "website.index/news", "/news");
		$instance->add_item("ui_services", "website.index/services", "/services", ["has_param" => true]);

		return $instance;

	}
	//--------------------------------------------------------------------------
}