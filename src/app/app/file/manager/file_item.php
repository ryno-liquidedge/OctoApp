<?php

namespace LiquidedgeApp\Octoapp\app\app\file\manager;

/**
 * Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class file_item extends \com\file\manager\file_item {
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	public function get_string() {
		$data = false;
		if ($this->file_item->file_data) {
			$data = gzuncompress(base64_decode($this->file_item->file_data->fid_data));
		}
		elseif ($this->file_item->fil_link_path) {
			$data = file_get_contents($this->parse_linkpath($this->file_item->fil_link_path));
		}

		return $data;
	}
	//--------------------------------------------------------------------------------
	public function save_to_file($filename) {
		$data = false;
		if ($this->file_item->file_data) {
			$data = gzuncompress(base64_decode($this->file_item->file_data->fid_data));
		}
		elseif ($this->file_item->fil_link_path) {
			$data = file_get_contents($this->parse_linkpath($this->file_item->fil_link_path));
		}

		\com\os::mkdir(dirname($filename));

		return file_put_contents($filename, $data);
	}
	//--------------------------------------------------------------------------------
	/**
	 * Streams the contents to the output buffer. Returns the string "stream" for use
	 * in actions to specify the stream layout.
	 *
	 * @return string
	 */
	public function stream($options = []) {
		return \LiquidedgeApp\Octoapp\app\app\http\http::stream($this->get_stream(), $this->get_filename(), $options);
	}
	//--------------------------------------------------------------------------------
}