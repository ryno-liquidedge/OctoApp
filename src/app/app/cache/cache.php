<?php

namespace LiquidedgeApp\Octoapp\app\app\cache;
/**
 * @package app
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class cache extends \LiquidedgeApp\Octoapp\app\app\intf\standard {

	protected $cache;

    //--------------------------------------------------------------------------------
	protected function __construct($options = []) {

		$cache = \LiquidedgeApp\Octoapp\app\app\inc\scrapbook\cache::make($options);
		if($cache->is_installed()) $this->cache = $cache;
		else  $this->cache = \com\cache\provider\local::make();
	}
    //--------------------------------------------------------------------------------
    public function set($id, $value, $options = []) {
        $this->cache->set($id, $value, $options);
    }
    //--------------------------------------------------------------------------------
    public function get($key) {
        return $this->cache->get($key);
    }
    //--------------------------------------------------------------------------
	public function delete($key) {
		$this->cache->delete($key);
	}
    //--------------------------------------------------------------------------------
	public static function clear_cached_var($key, $options = []) {
		self::make($options)->delete($key);
	}
    //--------------------------------------------------------------------------------
	public static function get_cached_var($key, $fn, $options = []) {

	    $options = array_merge([
	        "force" => false,
	        "default" => false,
	    ], $options);

	    $cache = self::make($options);

		$result = $cache->get($key);
		if(!$result || $options["force"]){
			$result = $fn();
			$cache->set($key,  $result, $options);
		}

		if(isempty($result)) $result = $options["default"];

        return $result;
	}
    //--------------------------------------------------------------------------------
}