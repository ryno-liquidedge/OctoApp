<?php

namespace octoapi;

include_once __DIR__."/core/core.php";


class OctoApi extends \octoapi\core\com\intf\standard {

    protected static $config;
    public static bool $debug = false;

    public static string $dir_root = __DIR__;
    public static string $dir_files = __DIR__."/files";
    public static string $dir_temp = __DIR__."/files/temp";

    public static bool $enable_cache = false;
    public static int $cache_expire = 3600;

    //------------------------------------------------------------------------------------------------------------------
    // construct
    //------------------------------------------------------------------------------------------------------------------
    protected function __construct($options = []) {

        $options = array_merge([
            "url" => "",
            "username" => "",
            "password" => "",
        ], $options);

        self::$config = \octoapi\core\com\config\config::make($options);

    }
    //------------------------------------------------------------------------------------------------------------------

    public function set_debug(bool $bool, string $debug_file_dir = "") {
        self::$debug = $bool;
        if($debug_file_dir) $this->set_debug_dir($debug_file_dir);
    }
    //------------------------------------------------------------------------------------------------------------------
	/**
	 * cache api calls
	 * @param bool $enable_cache
	 */
	public function enable_cache(bool $enable_cache): void {
		self::$enable_cache = $enable_cache;
	}
    //------------------------------------------------------------------------------------------------------------------
	/**
	 * sets the cache expiry in seconds
	 * @param int $cache_expire
	 */
	public function set_cache_expire(int $cache_expire): void {
		self::$cache_expire = $cache_expire;
	}
    //------------------------------------------------------------------------------------------------------------------

    public function set_debug_dir(string $dir) {
        self::$dir_temp = $dir;
    }
    //------------------------------------------------------------------------------------------------------------------

    public static function set_config($config): void {
        self::$config = $config;
    }
    //------------------------------------------------------------------------------------------------------------------
    public static function get_config() {
        return self::$config;
    }
    //------------------------------------------------------------------------------------------------------------------
    public function product(): \octoapi\action\product\factory\main {
        return \octoapi\action\product\factory\main::make();
    }
    //------------------------------------------------------------------------------------------------------------------
    public function person(): \octoapi\action\person\factory\main {
        return \octoapi\action\person\factory\main::make();
    }
    //------------------------------------------------------------------------------------------------------------------
    public function company(): \octoapi\action\company\factory\main {
        return \octoapi\action\company\factory\main::make();
    }
    //------------------------------------------------------------------------------------------------------------------
	public function clear_cache($id = false){
		\octoapi\core\com\cache\cache::make()->clear($id);
    }
    //------------------------------------------------------------------------------------------------------------------

	/**
	 * pings the api server to test connectivity
	 * @return bool
	 */
    public function ping(): bool {
        $response = $this->company()->get()->setup();
        return !$response->has_error();
    }
    //------------------------------------------------------------------------------------------------------------------

    /**
     * @param array $options
     * @return core\com\intf\standard|core\com\octo\response
     * @throws \Exception
     */
    public function call($options = []) {

        $options = array_merge([
            "method" => "GET",
            "action" => false,
            "page_size" => 100,
            "offset" => 0,
        ], $options);

        $api_options = \octoapi\core\com\octo\api_options::make();
        $api_options->set_method($options["method"]);
        $api_options->set_action($options["action"]);
        $api_options->apply_options($options);

        $service = \octoapi\core\com\octo\service::make();
        $service->set_options($api_options);
        $response = $service->call($options);

        $this->process_response($response);
        return $response;
    }
    //------------------------------------------------------------------------------------------------------------------
    /**
     * @param $response
     * @throws \Exception
     */
    public function process_response($response) {

        if($response->get_response_code() != 0){
            $error_data = $response->parse_error_code($response->get_response_code());

            throw new \Exception("Error {$error_data["http_code"]}: ".$error_data["message"], $error_data["http_code"]);
        }

    }
    //------------------------------------------------------------------------------------------------------------------
}