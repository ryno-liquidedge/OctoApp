<?php

require_once '../../vendor/autoload.php';

//$service = \octoapi\core\com\octo\service::make();
//$service->test_cache();

// create Flysystem object
$adapter = new \League\Flysystem\Local\LocalFilesystemAdapter(sys_get_temp_dir(), null, LOCK_EX);
$filesystem = new \League\Flysystem\Filesystem($adapter);
// create Scrapbook cache object
$cache = new \MatthiasMullie\Scrapbook\Adapters\Flysystem($filesystem);

// create Simplecache object from cache engine
$simplecache = new \MatthiasMullie\Scrapbook\Psr16\SimpleCache($cache);

// get value from cache
$value = $simplecache->get('key');

// ... or store a new value to cache
$simplecache->set('key', 'updated-value');