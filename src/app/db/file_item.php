<?php

namespace LiquidedgeApp\Octoapp\app\db;

/**
 * Database Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class file_item extends \com\core\db\file_item {

	use \LiquidedgeApp\Octoapp\app\app\db\tra\table;

    //--------------------------------------------------------------------------------
    // properties
    //--------------------------------------------------------------------------------

	public $name = "file_item";
	public $key = "fil_id";
	public $display = "fil_name";

	public $display_name = "file item";

	public $field_arr = array( // FIELDNAME => DISPLAY[0] DEFAULT[1] TYPE[2] REFERENCE[3]
		"fil_id"				=> array("id"			, "null", DB_KEY),
		"fil_name"				=> array("name"			, ""	, DB_STRING),
		"fil_date_added"		=> array("date added"	, "null", DB_DATETIME),
		"fil_date_updated"		=> array("date updated"	, "null", DB_DATETIME),
		"fil_filename"			=> array("filename"		, ""	, DB_STRING),
		"fil_link_path"			=> array("link path"	, ""	, DB_STRING),
		"fil_source_host"		=> array("source host"	, ""	, DB_STRING),
		"fil_size"				=> array("size"			, 0		, DB_BYTES),
		"fil_ref_person_added"	=> array("person added"	, "null", DB_REFERENCE, "person"),
		"fil_ref_file_item"		=> array("file"			, "null", DB_REFERENCE, "file_item"),
		"fil_version"			=> array("version"		, ""	, DB_STRING),
		"fil_date_version"		=> array("date version"	, "null", DB_DATETIME),
		"fil_extension"			=> array("extension"	, ""	, DB_STRING),
		"fil_ref_file_data"		=> array("data"			, "null", DB_REFERENCE, "file_data"),
		"fil_is_deleted"		=> array("is deleted"	, 0		, DB_BOOL),
        "fil_is_compressed"		=> array("is compressed", 0		, DB_BOOL),
		"fil_remote_id"			=> array("remote id"	, 0		, DB_INT),
		"fil_old_id"			=> array("old db id"	, 0		, DB_INT),
	);
	//--------------------------------------------------------------------------------
    // events
	//--------------------------------------------------------------------------------
	public function on_auth(&$file_item, $user, $role) {
		return in_array($role, ["DEV", "ADMIN"]);
	}
	//--------------------------------------------------------------------------------
	public function on_auth_use(&$file_item, $user, $role) {
		return $this->auth_for($file_item, $user, $role);
	}
	//--------------------------------------------------------------------------------
    public function stream($file_item, $options = []) {

	    $options = array_merge([
		    "file_item" => $file_item,
		    "download" => false,
	    ], $options);

    	$manager = \com\file\manager\file_item::make($options);
    	$manager->stream($options);

    	return "stream";
    }
	//--------------------------------------------------------------------------------
	public function get_base64_stream($file_item) {

    	if ($file_item->file_data) {
			$data = gzuncompress(base64_decode($file_item->file_data->fid_data));
			return "data:image/{$file_item->fil_extension};base64, ".base64_encode($data);
		}

	}
	//--------------------------------------------------------------------------------
	public function get_buffer_data($file_item) {
    	if ($file_item->file_data) {
			return gzuncompress(base64_decode($file_item->file_data->fid_data));
		}
	}
	//--------------------------------------------------------------------------------
	public function tinify($file_item) {

	    if($file_item->fil_is_compressed == 1) return;
	    if(!\core::$app->get_instance()->get_option("tinify.api.key")) return;

        $tinify = \LiquidedgeApp\Octoapp\app\app\inc\tinify\tinify::make();
        $tinify->set_from_buffer($file_item->get_buffer_data());
        $tinify->set_to_file(\core::$folders->get_temp()."/compressed/{$file_item->id}/{$file_item->fil_name}");
        $filename = $tinify->compress();

        $manager = \com\file\manager\file_item::make(["file_item" => $file_item,]);
        $manager->save_from_file($filename);

        $file_item->fil_is_compressed = 1;
        $file_item->update();

        \com\os::removedir(dirname($filename));

	}
 	//--------------------------------------------------------------------------------
	public function duplicate($file_item) {

		$manager_original = \com\file\manager\file_item::make(["file_item" => $file_item,]);

		$manager = \com\file\manager\file_item::make();
		return $manager->save($manager_original->get_stream(), [
			"filename" => $file_item->fil_name,
			"filesize" => $file_item->fil_size,
		]);
	}
 	//--------------------------------------------------------------------------------

	/**
	 * @param $file_item
	 * @param int $width
	 * @param int $height
	 * @param array $options
	 * @return false|mixed|db_file_item
	 */
	public function crop($file_item, int $width, int $height, $options = []) {

		$options = array_merge([
		    "dir" => \core::$folders->get_temp("file_item/{$file_item->id}")
		], $options);

		try{
			\com\os::mkdir($options["dir"]);

			$manager = \app\file\manager\file_item::make(["file_item" => $file_item,]);
			$temp_file = "{$options["dir"]}/{$file_item->fil_filename}";
			$temp_file_cropped = "{$options["dir"]}/cropped_{$file_item->fil_filename}";

			$manager->save_to_file($temp_file);

			if(filesize($temp_file) > 0){
				$compressor = \LiquidedgeApp\Octoapp\app\app\inc\dropzone\compressor::make();
				$compressor->from_file($temp_file);
				$compressor->resize_crop($width, $height);
				$compressor->save($temp_file_cropped);

				$manager = \app\file\manager\file_item::make();
				$file_item = $manager->save_from_file($temp_file_cropped);
			}

			\LiquidedgeApp\Octoapp\app\app\os\os::removedir($options["dir"]);

			return $file_item;

		}catch(\Exception $ex){
			\LiquidedgeApp\Octoapp\app\app\error\error::create($ex);
		}
	}
 	//--------------------------------------------------------------------------------
	public function save_to_local_path($file_item, $filename = false, $options = []) {

		$options = array_merge([
		    "dir" => \core::$folders->get_temp("file_item/{$file_item->id}")
		], $options);

		\LiquidedgeApp\Octoapp\app\app\os\os::mkdir($options["dir"]);

		if(!$filename) $filename = "{$options["dir"]}/{$file_item->fil_filename}";

		$file_item->file_data = \core::dbt("file_data")->get_fromdb($file_item->fil_ref_file_data);

		$manager = \app\file\manager\file_item::make(["file_item" => $file_item,]);
		$manager->save_to_file($filename);

		return $filename;
	}
 	//--------------------------------------------------------------------------------
}