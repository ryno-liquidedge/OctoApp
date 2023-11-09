<?php

namespace LiquidedgeApp\Octoapp\app\app\inc\scrapbook;

/**
 * @package app\inc\scrapbook
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 *
 * https://www.scrapbook.cash/
 *
 */

class cache extends \LiquidedgeApp\Octoapp\app\app\intf\standard {

	/**
	 * @var \MatthiasMullie\Scrapbook\Adapters\MemoryStore
	 */
	protected $cache;

	/**
	 * @var \League\Flysystem\Local\LocalFilesystemAdapter
	 */
	protected $adapter;

	/**
	 * @var \League\Flysystem\Filesystem
	 */
	protected $filesystem;

    //--------------------------------------------------------------------------
    protected function __construct($options = []) {

        $options = array_merge([
        	"adapter" => "memory"
        ], $options);

        if($this->is_installed()){
			switch ($options["adapter"]){
				case "memory": $this->use_memory_adapter(); break;
				case "file_system": $this->use_file_system_adapter(); break;
			}
		}

    }
	//--------------------------------------------------------------------------
	public function is_installed() {
		return file_exists(\core::$folders->get_app()."/inc/composer/vendor/matthiasmullie/scrapbook/src/KeyValueStore.php");
	}
	//--------------------------------------------------------------------------
	public function use_memory_adapter() {
		// create Scrapbook cache object
		$this->cache =  new \MatthiasMullie\Scrapbook\Adapters\MemoryStore();
	}
	//--------------------------------------------------------------------------
	public function use_file_system_adapter() {
		$filepath = \core::$folders->get_temp()."/cache";

        if (!file_exists($filepath)) mkdir($filepath, 0777, true);

		// create Flysystem object
		$this->adapter = new \League\Flysystem\Local\LocalFilesystemAdapter($filepath, null, LOCK_EX);
		$this->filesystem = new \League\Flysystem\Filesystem($this->adapter);

		// create Scrapbook KeyValueStore object
		$this->cache = new \MatthiasMullie\Scrapbook\Adapters\Flysystem($this->filesystem);
	}
	//--------------------------------------------------------------------------

	/**
	 * @param $key
	 * @param $value
	 * @param array $options
	 */
	public function set($key, $value, $options = []) {

		$options = array_merge([
		    "cache_expire" => 0,
		], $options);

		$key = "cache_".md5($key);
		$this->cache->set($key, $value, $options["cache_expire"]);
	}
	//--------------------------------------------------------------------------

	/**
	 * @param string $key
	 * @return mixed
	 */
	public function get(string $key) {

		$key = "cache_".md5($key);
		return $this->cache->get($key);
	}
	//--------------------------------------------------------------------------
	public function delete(string $key) {

		$key = "cache_".md5($key);
		$this->cache->delete($key);
	}
	//--------------------------------------------------------------------------

	/**
	 * @param string $key
	 */
	public function clear() {
		$this->cache->flush();
	}
	//--------------------------------------------------------------------------------
}