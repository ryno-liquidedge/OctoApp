<?php

namespace LiquidedgeApp\Octoapp\app\app\seo;


class coder extends \LiquidedgeApp\Octoapp\app\app\intf\standard {

    //--------------------------------------------------------------------------------
	// fields
	//--------------------------------------------------------------------------------
	/**
	 * @var \LiquidedgeApp\Octoapp\app\app\seo\seo|\com\intf\standard
	 */
	protected $seo;

	protected $options = null;

	protected $file;

	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	public function __construct($options = []) {

	    $options = array_merge([
	    ], $options);

		// init
		$this->name = "htaccess";
		$this->options = $options;

		$this->seo = \LiquidedgeApp\Octoapp\app\app\seo\seo::make();

	}
	//--------------------------------------------------------------------------------
    private function start() {
        $this->file = fopen(\core::$folders->get_root()."/../.htaccess", "w+");
    }
    //--------------------------------------------------------------------------------
    public function end() {
        fclose($this->file);
    }
    //--------------------------------------------------------------------------------
    public function add($mixed) {

        $arr = \LiquidedgeApp\Octoapp\app\app\arr\arr::splat($mixed);

        foreach ($arr as $line) fwrite($this->file, "$line\n");

    }
	//--------------------------------------------------------------------------------
    public function get_server_name() {
        $server_name = isset($_SERVER["SERVER_NAME"]) ? $_SERVER["SERVER_NAME"] : false;
		if(!$server_name){
            $url = \core::$app->get_instance()->get_url();
            $server_name = str_replace(["http://", "https://", "/"], "", $url);
        }

        return $server_name;
    }
	//--------------------------------------------------------------------------------
    public function newline() {
        $this->add("");
    }
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	public function build($options = []) {

		// options
		$options = array_merge([
		], $this->options, $options);

		$server_name = $this->get_server_name();

		if($server_name){

		    $this->start();

            $this->add("<IfModule mod_rewrite.c>");
            $this->newline();

            $this->add("# Enable Rewrite Engine");
            $this->add("RewriteEngine On");
            $this->add("RewriteBase /");
            $this->newline();

            $this->add("RewriteCond %{HTTPS} off [OR]");
            $this->add("RewriteCond %{HTTP_HOST} ^www\.".str_replace(".", "\\.", $server_name)." [NC]");
            $this->add("RewriteRule (.*) https://{$server_name}/$1 [L,R=301]");
            $this->newline();

            if($this->seo->is_seo_enabled()){

            	$this->add("#---------------------------------------------------------------------------");
                $this->add("# urls");
                $this->add("#---------------------------------------------------------------------------");

            	foreach ($this->seo->get_item_arr() as $item){

					$seo_value = $item["seo_value"];
					$url = $item["url"];
					$has_param = $item["has_param"];
					if(substr($url, 0, 3) == "?c=") $url = "index.php{$url}";

            		if($has_param) $this->add("RewriteRule {$seo_value}/(.*) {$url}&slug=$1 [QSA,L]");
            		else $this->add("RewriteRule {$seo_value} $url [QSA,L]");
				}

            	$this->add("#---------------------------------------------------------------------------");
            	$this->newline();
            }


            $this->add("RewriteCond %{REQUEST_URI} !^/root/");
            $this->add("RewriteRule ^(.*)$ /root/$1 [QSA,L]");
            $this->newline();

            $this->add("RewriteCond %{REQUEST_FILENAME} !-f");
            $this->add("RewriteCond %{REQUEST_FILENAME} !-d");
            $this->add("RewriteRule ^(.*)$ index.php?$1 [L,QSA]");
            $this->newline();

            $this->add("</IfModule>");

            $this->end();
        }
	}
	//--------------------------------------------------------------------------------
}