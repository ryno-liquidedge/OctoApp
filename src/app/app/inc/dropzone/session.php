<?php

namespace LiquidedgeApp\Octoapp\app\app\inc\dropzone;

class session extends \LiquidedgeApp\Octoapp\app\app\intf\standard {
    //--------------------------------------------------------------------------------
	public $id;
	public $dest;
	public $uploaded_files_arr = [];

	public $dropzone_id;
	public $element_id;
	public $folder;
	public $filetype_group;

	public $has_cropper = false;
	public $crop_width = 800;
	public $crop_height = 400;
	public $on_crop = "function(response, data){}";

	public $options = [];

	public $core_session;

    //--------------------------------------------------------------------------------
	public function __construct($options = []) {
		$options = array_merge([
		    "id" => "dropzone"
		], $options);

		$this->core_session = \core::$app->get_session();
		$this->init_session($options['id']);
	}
	//--------------------------------------------------------------------------------
	public function init_session($id){
		$this->id = $id;

		$instance = $this->core_session->get($id);
		if(!$instance){
			$instance = $this->core_session->{$this->id} = $this;
		}

		foreach (get_object_vars($instance) as $property => $value){

			if(!$property) continue;

			$value	= $this->{$property};
			if(is_object($instance) && property_exists($instance, $property)){
				$value	= $instance->{$property};
			}

			$this->{$property} = $value;
		}

		$instance->update();
	}
	//--------------------------------------------------------------------------------
	public function update(){
		$this->core_session->{$this->id} = $this;
	}
	//--------------------------------------------------------------------------------
	public function clear(){

		foreach ($this->uploaded_files_arr as $index => $files){
			$this->remove_uploaded_file($index);
		}

		//unset session
        if(isset($_SESSION[$this->id])){
            unset($_SESSION[$this->id]);
            unset($this->core_session->{$this->id});
        }

        //set defaults
        $default_arr = get_object_vars($this);
        foreach ($default_arr as $property => $default){
            $this->{$property} = $default;
        }

	}
	//--------------------------------------------------------------------------------
	public function add_uploaded_file($filename, $cropped_filename = false, $options = []){

		$options = array_merge([
		    "index" => basename($filename),
			"original" => $filename,
			"cropped" => $cropped_filename,
		], $options);

		$this->uploaded_files_arr[$options["index"]] = $options;

		return $options["index"];
	}
	//--------------------------------------------------------------------------------
	public function get_uploaded_file($index, $sub_index = false){

		if(isset($this->uploaded_files_arr[$index])){
			$result = $this->uploaded_files_arr[$index];
			if($sub_index && isset($result[$sub_index])) return $result[$sub_index];
			return $result;
		}
	}
	//--------------------------------------------------------------------------------
	public function remove_uploaded_file($index){

		if(isset($this->uploaded_files_arr[$index])){
			$data_arr = $this->uploaded_files_arr[$index];
			foreach ($data_arr as $file){
				if($file) $this->delete_file($file);
			}
			unset($this->uploaded_files_arr[$index]);
		}
	}
	//--------------------------------------------------------------------------------
	private function delete_file($filename) {
		//delete original
		if(file_exists($filename))
			@unlink($filename);
	}
	//--------------------------------------------------------------------------------

}
