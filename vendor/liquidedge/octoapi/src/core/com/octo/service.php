<?php

namespace octoapi\core\com\octo;


class service extends \octoapi\core\com\intf\standard {

    /**
     * @var \octoapi\core\com\config\config
     */
	protected $config;

    /**
     * @var api_options
     */
	protected $options;

	protected $action;

    //options
	protected $method = "GET";

	/**
	 * Octo Service username
	 * @var String
	 */
	protected $username;

	/**
	 * Octo Service password
	 * @var String
	 */
	protected $password;

	/**
	 * Octo Service base URL
	 * @var String
	 */
	protected $baseurl;

	/**
	 * response data
	 * @var string
	 */
	protected $response = false;

	/**
	 * @var \octoapi\core\com\cache\cache
	 */
	protected $cache;

	public $debug = false;

    //--------------------------------------------------------------------------
    protected function __construct($options = []) {

        $options = array_merge([
            "debug" => \octoapi\OctoApi::$debug
        ], $options);

        $this->debug = $options["debug"];
        $this->cache = \octoapi\core\com\cache\cache::make();

    }
    //--------------------------------------------------------------------------

    /**
     * @param $options
     * @return $this
     */
    public function set_options(api_options $options): service {
        if($options){
            $this->options = $options;
            $this->set_action($this->options->get_action());
            $this->set_method($this->options->get_method());
            $this->set_config($this->options->get_config());
        }
        return $this;
    }
    //--------------------------------------------------------------------------
    public function set_config($config) {
        $this->config = $config;
        $this->set_baseurl($this->config->get_url());
        $this->set_password($this->config->get_password());
        $this->set_username($this->config->get_username());
    }
    //--------------------------------------------------------------------------

    /**
     * @param $action
     * @return $this
     */
    public function set_action($action): service {
        $this->action = $action;
        return $this;
    }
	//--------------------------------------------------------------------------
	/**
	 * @param string $method
	 */
	public function set_method(string $method): void {
		$this->method = $method;
	}
	//--------------------------------------------------------------------------
	/**
	 * @param String $username
	 */
	public function set_username(string $username): void {
		$this->username = $username;
	}
	//--------------------------------------------------------------------------
	/**
	 * @param String $password
	 */
	public function set_password(string $password): void {
		$this->password = $password;
	}
	//--------------------------------------------------------------------------
	/**
	 * @param String $baseurl
	 */
	public function set_baseurl(string $baseurl): void {
		$this->baseurl = $baseurl;
	}
	//--------------------------------------------------------------------------

    /**
     * @param array $options
     * @return \octoapi\core\com\intf\standard|response
     * @throws \Exception
     */
	public function call($options = []) {

	    $options = array_merge([
			"cache_id" => null,
			"enable_cache" => \octoapi\OctoApi::$enable_cache,
			"cache_expire" => \octoapi\OctoApi::$cache_expire,
	    ], $options, $this->options->get_options());

		if (!$this->baseurl) throw new \Exception("Service base URL cannot be empty");
		if (!$this->username) throw new \Exception("Service username cannot be empty");
		if (!$this->password) throw new \Exception("Service password cannot be empty");

		$separator = substr($this->baseurl, (strlen($this->baseurl) - 1), strlen($this->baseurl)) == "/" ? "" : "/";
		$url = "{$this->baseurl}{$separator}{$this->action}";
		$cache_id = is_null($options["cache_id"]) ? $url : $options["cache_id"];


		if($options["enable_cache"]){
			$response = $this->cache->get($cache_id);
			if($response){
				$response->set_source("cache");
				return $response;
			}
		}

		$this->response = \octoapi\core\com\rest\rest::call($this->method, $url, $options, [
			"username" => $this->username,
			"password" => $this->password,
			">CURLOPT_TIMEOUT" => 108000    //30 HOURS
		]);

		$response = \octoapi\core\com\octo\response::make($this->response);

		if($this->debug) $this->print_debug([
			"url" => $url,
			"options" => $options,
			"meta" => $response->get_response_meta(),
			"data" => $response->get_response_data(),
		]);

		if($options["enable_cache"]){
			$this->cache->add($cache_id, $response, [
				"cache_expire" => $options["cache_expire"]
			]);
		}

		return $response;
	}
	//--------------------------------------------------------------------------
	public function print_debug($item_arr = []) {

		$fn_add_data = function($title, $mixed){
			\octoapi\core\com\debug\debug::console("//------------------------------------------");
			\octoapi\core\com\debug\debug::console("//$title");
			\octoapi\core\com\debug\debug::console("//------------------------------------------");
			\octoapi\core\com\debug\debug::console($mixed);
			\octoapi\core\com\debug\debug::console("\n\n");
		};

		foreach ($item_arr as $title => $mixed){
			$fn_add_data($title, $mixed);
		}
	}
	//--------------------------------------------------------------------------------
}