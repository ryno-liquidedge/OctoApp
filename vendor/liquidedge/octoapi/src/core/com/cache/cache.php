<?php

namespace octoapi\core\com\cache;


class cache extends \octoapi\core\com\intf\standard {

	/**
	 * @var \MatthiasMullie\Scrapbook\Adapters\MemoryStore
	 */
	protected $cache;

    //--------------------------------------------------------------------------
    protected function __construct($options = []) {

        $options = array_merge([
        ], $options);

        $filepath = \octoapi\OctoApi::$dir_temp."/octoapi/cahce";

        if (!file_exists($filepath)) mkdir($filepath, 0777, true);

		// create Flysystem object
		$adapter = new \League\Flysystem\Local\LocalFilesystemAdapter($filepath, null, LOCK_EX);
		$filesystem = new \League\Flysystem\Filesystem($adapter);

		// create Scrapbook KeyValueStore object
		$this->cache = new \MatthiasMullie\Scrapbook\Adapters\Flysystem($filesystem);

    }
	//--------------------------------------------------------------------------

	/**
	 * @param $key
	 * @param $value
	 * @param array $options
	 */
	public function add($key, $value, $options = []) {

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

	/**
	 * @param string $key
	 */
	public function clear($key = false) {
		if($key) $this->cache->delete("cache_".md5($key));
		else $this->cache->flush();
	}
	//--------------------------------------------------------------------------------
}