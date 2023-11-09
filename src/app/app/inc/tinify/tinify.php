<?php
namespace LiquidedgeApp\Octoapp\app\app\inc\tinify;

/**
 * Model: string
 *
 * https://fancyapps.com/fancybox/3/

 *///--------------------------------------------------------------------------------
class tinify extends \LiquidedgeApp\Octoapp\app\app\intf\standard {
 	//--------------------------------------------------------------------------------
 	// properties
	//--------------------------------------------------------------------------------
    protected $to_file;

    /**
     * @var \Tinify\Source
     */
    protected $source;
 	//--------------------------------------------------------------------------------
 	// functions
	//--------------------------------------------------------------------------------

	public function __construct($options = []) {

	    $options = array_merge([
	        "api_key" => \core::$app->get_instance()->get_option("tinify.api.key")
	    ], $options);

	    if($options["api_key"]) \Tinify\setKey($options["api_key"]);
    }
    //--------------------------------------------------------------------------------
    public function set_from_file($file_from) {
        $this->source = \Tinify\fromFile($file_from);
    }
    //--------------------------------------------------------------------------------
    public function set_from_buffer($file_contents) {
        $this->source = \Tinify\fromBuffer($file_contents);
    }
    //--------------------------------------------------------------------------------
    public function set_from_url($url) {
        $this->source = \Tinify\fromUrl($url);
    }
    //--------------------------------------------------------------------------------
    public function set_to_file($new_filename) {
        $this->to_file = $new_filename;
    }
    //--------------------------------------------------------------------------------

	/**
	 * https://tinypng.com/developers/reference/php
	 * @param $to_filename
	 * @param $options
	 */
    public function resize($to_filename, $options) {
		$options = array_merge([
		    "method" => "cover", //scale|fit|cover
			"width" => 0,
			"height" => 0
		], $options);

		\com\os::mkdir(dirname($to_filename));

        $resized = $this->source->resize($options);
		$resized->toFile($to_filename);
    }
    //--------------------------------------------------------------------------------
    public function compress() {

	    if(!$this->source) return \com\error::create("No source file found");
	    if(!$this->to_file) return \com\error::create("No dest file found");

	    \com\os::mkdir(dirname($this->to_file));

	    $this->source->toFile($this->to_file);

	    return $this->to_file;
    }
    //--------------------------------------------------------------------------------
}