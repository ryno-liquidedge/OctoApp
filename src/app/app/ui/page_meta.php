<?php

namespace LiquidedgeApp\Octoapp\app\app\ui;

/**
 * @package app\ui
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */

class page_meta extends \LiquidedgeApp\Octoapp\app\app\intf\standard {

	/**
	 * @var null
	 */
	protected $title, $action, $resource, $name, $url, $canonical, $keywords, $description, $author;

	/**
	 * @var array
	 */
	protected $meta_arr = [];

	/**
	 * @var
	 */
	protected $page_image_url;
	protected $page_image_alt;

	protected $noindex = false;

	protected $enable_open_graph = true;
	protected $enable_facebook = true;
	protected $enable_twitter = true;
	protected $enable_google = true;
	protected $google_site_verification;

	protected $is_loaded = false;

	//--------------------------------------------------------------------------------
	protected function __construct($options = []) {
		$options = array_merge([
		    "action" => \core::$app->get_request()->get_resource(),
		    "resource" => \core::$app->get_request()->get_action(),
		], $options);

		$this->name = \core::$app->get_instance()->get_title();
		$this->author = "Liquid Edge";
		$this->url = \core::$app->get_instance()->get_url();
		$this->set_resource($options["resource"]);
		$this->set_action($options["action"]);
		$this->set_title([
		    $this->name,
		    \LiquidedgeApp\Octoapp\app\app\str\str::propercase($options["resource"]),
        ]);

		$this->page_image_url = \LiquidedgeApp\Octoapp\app\app\http\http::get_stream_url(":logo", ["absolute" => true,]);
		$this->google_site_verification = \core::$app->get_instance()->get_option("google.site.verification");

	}
	//--------------------------------------------------------------------------------
	/**
	 * @return array
	 */
	public function get_meta_arr(): array {
		return $this->meta_arr;
	}
	//--------------------------------------------------------------------------------

    /**
     * @param mixed $page_image_url
     * @param string $alt
     */
    public function set_page_image_url(string $page_image_url, $alt = "Page Image"): void {
        $this->page_image_url = $page_image_url;
        $this->page_image_alt = $alt;
    }
	//--------------------------------------------------------------------------------
	public function has_description(): bool {
		if(!$this->description) return false;
	}
	//--------------------------------------------------------------------------------
	public function has_title(): bool {
        if(!$this->title) return false;
	}
	//--------------------------------------------------------------------------------
	public function has_keywords(): bool {
        if(!$this->keywords) return false;
	}
	//--------------------------------------------------------------------------------
	public function has_canonical(): bool {
		if(!$this->canonical) return false;
	}
	//--------------------------------------------------------------------------------

	/**
	 * @param null $description
	 */
	public function set_description($description): void {
	    $this->description = $description;
	}
	//--------------------------------------------------------------------------------

	/**
	 * @param null $keywords
	 */
	public function set_keywords($keywords): void {
	    $this->keywords = $keywords;
	}
	//--------------------------------------------------------------------------------
	/**
	 * @param null $title
	 */
	public function set_title($title): void {

	    $title = \LiquidedgeApp\Octoapp\app\app\arr\arr::splat($title);

	    $this->title = implode(" | ", $title);
	}
	//--------------------------------------------------------------------------------
	/**
	 * @param bool $noindex
	 */
	public function set_noindex(bool $noindex): void {
		$this->noindex = $noindex;
	}
	//--------------------------------------------------------------------------------

	/**
	 * @param null $url
	 */
	public function set_url($url): void {
	    $this->url = $url;
	}
	//--------------------------------------------------------------------------------
	/**
	 * @param null $canonical
	 */
	public function set_canonical($canonical): void {
	    $this->canonical = $canonical;
	}
	//--------------------------------------------------------------------------------
	/**
	 * @param null $name
	 */
	public function set_name($name): void {
		$this->name = $name;
	}
	//--------------------------------------------------------------------------------
	/**
	 * @param null $resource
	 */
	public function set_resource($resource): void {
		$this->resource = str_replace(".", "\\", $resource);
	}
	//--------------------------------------------------------------------------------

    /**
     * @return null
     */
    public function get_resource() {
        return $this->resource;
    }
	//--------------------------------------------------------------------------------
	/**
	 * @param null $action
	 */
	public function set_action($action): void {
		$this->action = $action;
	}
	//--------------------------------------------------------------------------------
	public function set_twitter_meta() {
		$this->add_meta("twitter:site", $this->url);
		$this->add_meta("twitter:card", $this->page_image_url);
		$this->add_meta("twitter:creator", $this->name);
		$this->add_meta("twitter:title", $this->title);
		$this->add_meta("twitter:description", $this->description);
		$this->add_meta("twitter:image:src", $this->page_image_url);
		$this->add_meta("twitter:image:alt", $this->page_image_alt);
	}
	//--------------------------------------------------------------------------------
	public function set_facebook_meta() {
		$this->add_meta("fb:admins", $this->url);
		$this->add_meta("fb:admins", false);
		$this->add_meta("fb:app_id", false);
		$this->add_meta("article:author", $this->author); 		// only for og:type article. Make empty string if not article ''.
		$this->add_meta("article:publisher", "");	// only for og:type article. Make empty string if not article ''.
	}
	//--------------------------------------------------------------------------------
	public function set_open_graph() {
	    $this->add_meta("og:url", $this->url);
		$this->add_meta("og:type", "website"); // website, article, product, book
		$this->add_meta("og:locale", "en_GB");
		$this->add_meta("og:title", $this->title);
		$this->add_meta("og:image", $this->page_image_url);
		$this->add_meta("og:image:alt", $this->page_image_alt);
		$this->add_meta("og:description", $this->description);
		$this->add_meta("og:site_name", $this->name);
	}
	//--------------------------------------------------------------------------------
	public function set_google_meta() {
		$this->add_meta(false, false, ['@rel' => 'author', '@href' => $this->author, "key" => "google:author"]);
		$this->add_meta(false, false, ['@rel' => 'publisher', '@href' => "Liquid Edge Solutions", "key" => "google:publisher"]);
		$this->add_meta(false, false, ['@itemprop' => 'name', '@content' => $this->name, "key" => "google:company"]);
		$this->add_meta(false, false, ['@itemprop' => 'description', '@content' => $this->description, "key" => "google:description"]);
		$this->add_meta(false, false, ['@itemprop' => 'image', '@content' => $this->page_image_url, "key" => "google:image"]);
	}
	//--------------------------------------------------------------------------------
	public function add_meta($name, $content, $options = []) {

		$options = array_merge([
		    "key" => $name
		], $options);

		if($name && !$content) return;

		$this->meta_arr[$options["key"]] = array_merge([
			"@name" => $name,
			"@content" => $content,
		], $options);

	}
	//--------------------------------------------------------------------------------
	public function load($options = []) {

	    $this->is_loaded = true;

        $this->add_meta(false, false, ['@charset' => 'utf-8', "key" => "charset"]);
        $this->add_meta("viewport", "width=device-width, initial-scale=1.0, shrink-to-fit=no");
        $this->add_meta(false, false, ['@http-equiv' => 'x-ua-compatible', '@content' => 'ie=edge', "key" => "http-equiv"]);
        $this->add_meta("secure:token", \core::$app->get_response()->get_csrf());
        $this->add_meta("google-site-verification", $this->google_site_verification);
        if($this->noindex) $this->add_meta("robots", "noindex, nofollow");

        $this->add_meta("title", $this->title);
        $this->add_meta("author", $this->author);
        $this->add_meta("description", $this->description);
        $this->add_meta("keywords", $this->keywords);
        $this->add_meta("canonical", $this->canonical);

        if($this->enable_open_graph) $this->set_open_graph();
        if($this->enable_facebook) $this->set_facebook_meta();
        if($this->enable_twitter) $this->set_twitter_meta();
        if($this->enable_google) $this->set_google_meta();

	}
	//--------------------------------------------------------------------------------
	public static function build($options = []) {

	    $options = array_merge([
		    "action" => \core::$app->get_request()->get_resource(),
		    "resource" => \core::$app->get_request()->get_action(),
		], $options);


	    $meta_arr = [];

		if($options["resource"] && $options["action"]){
			$namespace = "\\action\\".str_replace(".", "\\", $options["action"])."\\{$options["resource"]}";
			if (method_exists($namespace,"get_page_meta") && is_callable([$namespace, "get_page_meta"])){
				$meta_arr = \LiquidedgeApp\Octoapp\app\app\ui\arr::splat(call_user_func([$namespace, "get_page_meta"]));
			}
		}

		if(!$meta_arr) return "";

		$buffer = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();
		foreach ($meta_arr as $meta){
		    if($meta["@name"] == "title") $buffer->title(["*" => $meta["@content"]]);
			$buffer->meta($meta);
		}
		return $buffer->build();

	}
	//--------------------------------------------------------------------------------
}