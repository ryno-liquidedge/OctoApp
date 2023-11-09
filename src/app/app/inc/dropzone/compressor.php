<?php

namespace LiquidedgeApp\Octoapp\app\app\inc\dropzone;

/**
 * @package app\inc\dropzone
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class compressor extends \LiquidedgeApp\Octoapp\app\app\intf\standard {

	/**
	 * @var \Imagecow\Image
	 * https://github.com/oscarotero/imagecow
	 */
	protected $factory;

    //--------------------------------------------------------------------------------
    public function __construct($filename = null) {
    	if($filename) $this->from_file($filename);
    }
    //--------------------------------------------------------------------------------

	/**
	 * @param $filename
	 * @return $this
	 */
	public function from_file($filename) {

		$this->factory = \Imagecow\Image::fromFile($filename);
		return $this;
	}
    //--------------------------------------------------------------------------------

	/**
	 * @param $string
	 * @return $this
	 */
	public function from_string($string) {

		$this->factory = \Imagecow\Image::fromString($string);
		return $this;
	}
    //--------------------------------------------------------------------------------

	public function set_opacity(int $opacity) {

		$this->factory->opacity($opacity);
		return $this;
	}
    //--------------------------------------------------------------------------------

	/**
	 * @return $this
	 */
	public function auto_rotate() {
		$this->factory->autoRotate();
		return $this;
	}
    //--------------------------------------------------------------------------------

	/**
     * Set a default background color used to fill in some transformation functions.
     *
     * @param array $background The color in rgb, for example: array(0,127,34)
     *
     * @return self
     */
	public function set_background(array $background) {
		$this->factory->setBackground($background);

		return $this;
	}
    //--------------------------------------------------------------------------------
    public function save($filename) {

    	\com\os::mkdir(dirname($filename));
        $this->factory->save($filename);

        return $filename;
    }
    //--------------------------------------------------------------------------------
    public function getWidth() {
        return $this->factory->getWidth();
    }
    //--------------------------------------------------------------------------------
    public function rotate($angle = 90) {
        return $this->factory->rotate($angle);
    }
    //--------------------------------------------------------------------------------
    public function getHeight() {
        return $this->factory->getHeight();
    }
    //--------------------------------------------------------------------------------
    public function resizeToHeight($height) {
        $ratio = $height / $this->getHeight();
        $width = round($this->getWidth() * $ratio);
        $this->resize($width, $height);
    }
    //--------------------------------------------------------------------------------
    public function resizeToWidth($width) {
		$ratio = $width / $this->getWidth();
        $height = round($this->getHeight() * $ratio);
        $this->resize($width, $height);
    }
    //--------------------------------------------------------------------------------
    public function square($size) {
        $this->factory->resizeCrop($size, $size);
    }
    //--------------------------------------------------------------------------------
    public function scale($scale) {
        $width = $this->getWidth() * $scale / 100;
        $height = $this->getHeight() * $scale / 100;
        $this->resize($width, $height);
    }
    //--------------------------------------------------------------------------------
    public function resize($width, $height = 0, $cover = false) {
        $this->factory->resizeCrop($width, $height, $cover);
    }
    //--------------------------------------------------------------------------------
    public function resize_crop($width, $height, $x = 'center', $y = 'middle') {
        $this->factory->resizeCrop($width, $height, $x, $y);
    }
    //--------------------------------------------------------------------------------
    public function cut($x, $y, $width, $height) {
        $this->factory->resizeCrop($width, $height, $x, $y);
    }
    //--------------------------------------------------------------------------------
    public function maxarea($width, $height = null) {
        $this->factory->resizeCrop($width, $height);
    }
    //--------------------------------------------------------------------------------
    public function minarea($width, $height = null) {
        $height = $height ? $height : $width;

        if ($this->getWidth() < $width) {
            $this->resizeToWidth($width);
        }
        if ($this->getHeight() < $height) {
            $this->resizeToheight($height);
        }
    }
    //--------------------------------------------------------------------------------
    public function cutFromCenter($width, $height) {

        if ($width < $this->getWidth() && $width > $height) {
            $this->resizeToWidth($width);
        }
        if ($height < $this->getHeight() && $width < $height) {
            $this->resizeToHeight($height);
        }

        $x = ($this->getWidth() / 2) - ($width / 2);
        $y = ($this->getHeight() / 2) - ($height / 2);

        $this->cut($x, $y, $width, $height);
    }
    //--------------------------------------------------------------------------------
    public function cropToHeight($width, $height) {

        if ($height < $this->getHeight()) {
            $this->resizeToHeight($height);
        }

        $x = ($this->getWidth() / 2) - ($width / 2);
        $y = ($this->getHeight() / 2) - ($height / 2);

        $this->cut($x, $y, $width, $height);
    }
    //--------------------------------------------------------------------------------
    public function cropToWidth($width, $height) {

        if ($width < $this->getWidth()) {
            $this->resizeToWidth($width);
        }

        $x = ($this->getWidth() / 2) - ($width / 2);
        $y = ($this->getHeight() / 2) - ($height / 2);

        $this->cut($x, $y, $width, $height);
    }
    //--------------------------------------------------------------------------------

}
