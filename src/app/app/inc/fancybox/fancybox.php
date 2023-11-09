<?php
namespace LiquidedgeApp\Octoapp\app\app\inc\fancybox;

/**
 * @package LiquidedgeApp\Octoapp\app\app\inc\fancybox
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
//--------------------------------------------------------------------------------
class fancybox extends \LiquidedgeApp\Octoapp\app\app\intf\standard {
 	//--------------------------------------------------------------------------------
 	// properties
	//--------------------------------------------------------------------------------
	/**
	 * @var
	 */
    protected $buffer;

    protected $default_options = [
        "/img" => [
            "@data-width" => false,
            "@data-height" => false,
            "@data-fancybox" => "gallery",
            "@data-caption" => "",
        ],
        "/thumb" => [],
    ];

 	//--------------------------------------------------------------------------------
 	// functions
	//--------------------------------------------------------------------------------
    public function __construct() {

        $this->buffer = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();

    }
    //--------------------------------------------------------------------------------
    public function add_image($src, $src_thumb, $options = []) {
        $this->buffer->add($this->build_image_html($src, $src_thumb, $options));
    }
    //--------------------------------------------------------------------------------
    public function build_image_html($src, $src_thumb, $options = []) {

        $options = array_merge([
            "group" => "gallery",
            "caption" => false,
            "!click" => false,
            "/img" => [],
            "/thumb" => [],
        ], $options);

        //set thumbnail options
        $img = array_merge($this->default_options["/img"], $options["/img"]);
        $thumb = array_merge($this->default_options["/thumb"], $options["/thumb"]);

        if(!$src_thumb) $src_thumb = $src;

        $img["@href"] = $src;
        $thumb["@src"] = $src_thumb;

        $buffer = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();
        $buffer->a_($img);
            $buffer->img($thumb);
        $buffer->_a();

        return $buffer->build();

    }
    //--------------------------------------------------------------------------------
    public function build($options = []) {

        return $this->buffer->get_clean();

    }
    //--------------------------------------------------------------------------------
    // static
    //--------------------------------------------------------------------------------
    public static function stream($src, $src_thumb, $options = []) {

	    echo self::get_stream($src, $src_thumb, $options);

        return "stream";
    }
    //--------------------------------------------------------------------------------
    public static function get_stream($src, $src_thumb = false, $options = []) {

        if(!$src_thumb) $src_thumb = $src;

        $fancybox = \LiquidedgeApp\Octoapp\app\app\inc\fancybox\fancybox::make();
	    $fancybox->add_image($src, $src_thumb, $options);

	    return $fancybox->build();

    }
    //--------------------------------------------------------------------------------
}