<?php

namespace LiquidedgeApp\Octoapp\app\app\inc\dropzone;

/**
 * @package app\dropzone
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class dropzone extends \LiquidedgeApp\Octoapp\app\app\intf\standard {

	/**
	 * @var session
	 */
	protected $session;

	protected $options = [];

	//--------------------------------------------------------------------------------
	protected function __construct($options = []) {

		$options = array_merge([
		    "id" => false
		], $options);

		// init
		$this->name = "Input Dropzone";
		$this->options = $options;


		$this->session =  session::make(["id" => $options["id"]]);

	}
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	/**
	 * @return array
	 */
	public function get_uploaded_files() {
		return $this->session->uploaded_files_arr;
	}
	//--------------------------------------------------------------------------------
	public function build($options = []) {

		// options
		$options = array_merge([
			"label" => false,
			"id" => false,
			"folder" => false,
			"filetype_group" => false,
			"value" => false,
			"disabled" => false,
			"data" => [],
			"/" => [],

			"crop" => false,
			"!crop" => false,
			"crop_width" => 800,
			"crop_height" => 400,

			"wrapper_height" => "200px",
			"wrapper_width" => "100%",
			"note" => true,
			"/note" => [],
			"max_files" => 5,
			"max_filesize" => 5,
			"!success" => "function(file, response){}",
			"!error" => "function(){}",
			"!complete" => "function(){}",
			"!delete" => "function(file){}",
			"required" => false,
			"help" => false,
			"wrapper_id" => false,
			"label_width" => false,
			"label_col" => false,
			"label_html" => false,
			"modal_width" => "modal-xl",

			"auto_resize_image" => false,
			"auto_resize_max_width" => false,
			"auto_resize_max_height" => false,

			"btn_only" => false,
			"hide_uploads" => false,
			"js_id" => false,
			"url" => "?c=dropzone/xupload",
			"url_delete" => "?c=dropzone/xdelete",

  		], $options, $this->options);

		$id = $options["id"];
		if(!$options["js_id"]) $js_id = "js_{$id}";
		$url = $options["url"];
		$label = $options["label"];
		if($label === false) {
		    if($options["max_files"] == 1) $label = "Click here to upload";
            else $label = "Drop files here to upload";
        }
		$folder = $options["folder"];
		$filetype_group = $options["filetype_group"];
		$data = $options["data"];
		$wrapper_id = !$options["wrapper_id"] ? "{$js_id}_wrapper" : $options["wrapper_id"];
		$js_hidden_field = "
			$('<input>').attr({
				type: 'hidden',
				id: '{$id}['+name+']',
				name: '{$id}['+name+']',
				value: name,
				class: 'input-{$id}'
			}).appendTo('#$wrapper_id');
		";

		if(!$options["auto_resize_max_width"] && !isnull($options["auto_resize_max_width"])) $options["auto_resize_max_width"] = $options["crop_width"];
		if(!$options["auto_resize_max_height"] && !isnull($options["auto_resize_max_height"])) $options["auto_resize_max_height"] = $options["crop_height"];

		// file list
		if (is_numeric($filetype_group)) {
			switch ($filetype_group) {
				case 0 : $filetype_group = \com\os\filetype_group\whitelisted::make(); break;
				case 1 : $filetype_group = \com\os\filetype_group\csv::make(); break;
				case 2 : $filetype_group = \com\os\filetype_group\pdf::make(); break;
				case 4 : $filetype_group = \com\os\filetype_group\images::make(); break;
				case 8 : $filetype_group = \com\os\filetype_group\compressed::make(); break;
				case 16 : $filetype_group = \com\os\filetype_group\office::make(); break;
			}
		}

		//init session
		$this->session->dropzone_id = $id;
		$this->session->element_id = $id;
		$this->session->folder = $folder;
		$this->session->filetype_group = $filetype_group->get_class();
		$this->session->has_cropper = $options["crop"];
		$this->session->crop_width = $options["crop_width"];
		$this->session->crop_height = $options["crop_height"];
		$this->session->on_crop = $options["!crop"];
		$this->session->options = $options;
		$this->session->update();

		$buffer = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();
		$buffer->div_(["@id" => $wrapper_id, "@data-session-id" => $id, ".w-100" => true]);

			$options["/"]["@id"] = $js_id;
			$options["/"][".dropzone"] = true;
			$options["/"][".disabled"] = $options["disabled"];
			$options["/"]["#height"] = $options["wrapper_height"];
			$options["/"]["#width"] = $options["wrapper_width"];

			$class_arr = \LiquidedgeApp\Octoapp\app\app\arr\arr::extract_signature_items(".", $options);
			foreach ($class_arr as $class => $value) $options["/"][".{$class}"] = $value;

			$style_arr = \LiquidedgeApp\Octoapp\app\app\arr\arr::extract_signature_items("#", $options);
			foreach ($style_arr as $style => $value) $options["/"]["#{$style}"] = $value;

			$buffer->div($options["/"]);

			$buffer->xihidden("dropzone_session_id[{$id}]", $id);

			if(is_string($options["note"])){
				$buffer->xnote($options["note"], array_merge([".mb-3" => false], $options["/note"]));
			}else if($options["note"] === true){
                $note_arr = [];
                if($options["max_files"] > 1) $note_arr[] = "Upload queue has been limited to {$options["max_files"]} files.";
                if($options["max_filesize"]) $note_arr[] = "Max file size: {$options["max_filesize"]}mb";

				if($note_arr) {
					$buffer->div_([".mt-2 fs-7" => true]);
						$buffer->xnote(implode(" ", $note_arr), array_merge([".mb-3" => false], $options["/note"]));
					$buffer->_div();
				}
			}


			$existing_files = [];
			foreach ($this->session->uploaded_files_arr as $index => $files){

				$name = basename($files["original"]);
				$buffer->xihidden("{$id}[$name]", $name, [".input-{$id}" => true]);

				$existing_files[$index] = [
					"filename" => basename($files["original"]),
					"name" => basename($files["original"]),
					"index" => $index,
					"size" => filesize($files["original"]),
					"path" => $files["original"],
					"type" => mime_content_type($files["original"]),
					"accepted" => true,
				];
			}
			$existing_files_json = json_encode($existing_files);

			$has_reached_max = sizeof($this->session->uploaded_files_arr) >= $options["max_files"];
			$js_options = [];
			$data = json_encode($data);
			$js_options["*url"] = $url;
			$js_options["*dictDefaultMessage"] = $label;
			$js_options["*acceptedFiles"] = str_replace("*", "", str_replace(";", ",", $filetype_group->get_flash_string()));
			$js_options["*maxFiles"] = $options["max_files"];
			$js_options["*maxFilesize"] = $options["max_filesize"];
			$js_options["*parallelUploads"] = $options["max_files"];
			$js_options["*autoProcessQueue"] = true;
			$js_options["*addRemoveLinks"] = true;

			if($options["auto_resize_image"]){
				if(!isnull($options["auto_resize_max_width"])) $js_options["*resizeWidth"] = $options["auto_resize_max_width"];
				if(!isnull($options["auto_resize_max_height"])) $js_options["*resizeHeight"] = $options["auto_resize_max_height"];
				$js_options["*resizeMethod"] = 'contain';
				$js_options["*resizeQuality"] = 1.0;

			}

			$js_options["*init"] = "!function(){
			
				let instance = this;
				instance.loaded_file_count = 0;
				let oncomplete = {$options["!complete"]};
				let onerror = {$options["!error"]};
				let onsuccess = {$options["!success"]};
				let ondelete = {$options["!delete"]};
				let id_generator = function () {
					var S4 = function () {
						return (((1 + Math.random()) * 0x10000) | 0).toString(16).substring(1);
					};
					return 'id_'+(S4() + S4() + '_' + S4() + '_' + S4() + '_' + S4() + '_' + S4() + S4() + S4());
				};
				
				".($options["disabled"] ? "this.disable();" : "")."
				
				$.each($existing_files_json, function(key,value) {
					var mockFile = value;
					instance.emit('addedfile', mockFile);
					instance.emit('thumbnail', mockFile, '/index.php?c=dropzone/xstream&session_id={$id}&index='+value.index);
					instance.emit('complete', mockFile);
					
					instance.loaded_file_count += 1;
				});
				
				instance.getTotalFiles = function(){
				    return $(instance.element).find('.dz-preview').length;
				}
				
				if($existing_files_json){
					$('.dz-image img').css('height', '100%');
					$('.dz-image img').css('width', '100%');
					$('.dz-image img').css('object-fit', 'cover');
				}
				
				this.on('error', function(file, message) {
					if(!$('#dropzone_alert').length){
						core.browser.alert(message, 'Alert', {
							id:'dropzone_alert',
							ok_callback: onerror,
						}); 
					}
				});
				
				this.on('sending', function(file, xhr, formData) {
				
					core.overlay.show();
					
					file.index = id_generator();
					
					let formDataObj = $data;
					formData.append('session_id', '{$id}');
					formData.append('filename', file.name);
					formData.append('index', file.index);
					formData.append('_csrf', '".\core::$app->get_response()->get_csrf()."');
					jQuery.each( formDataObj, function( k, v ) {
						formData.append(k, v);
					});
				});
				
				
				this.on('queuecomplete', function () {
                    if(this.getActiveFiles().length == 0) {
                        // Files are done
                        oncomplete.apply(this);
                    }
                    
                    
                    ".($options["crop"] ? "" : "core.overlay.hide();")."
                    
				});
				
				this.on('success', function (file, response) {
					let dropzone = this;
					file.session_id = '{$id}';
					let { type, size, session_id } = file;
					let name = file.upload ? file.upload.filename : response.uploaded_file;
					
					if(response.uploaded_file){
						name = response.uploaded_file;
					}
					
					$js_hidden_field
					
					".($options["hide_uploads"] ? "this.removeAllFiles(true);" : "")."
					".($options["crop"] ? "setTimeout(function(){
						core.browser.popup('?c=dropzone/crop&session_id={$id}&index='+file.index, {
							class:'ui-cropper', 
							width:'{$options["modal_width"]}', 
							hide_header:true, 
							backdrop: 'static',
							on_close: function(){
								dropzone.removeFile(file);
							}
						});
					}, 500)" : "")."
					
					$(file.previewElement).attr('data-index', file.index);
					
					onsuccess.apply(this, [file, response]);
					
					if(this.getAcceptedFiles().length >= instance.options.maxFiles){
						this.disable();
					}
					
				});
				
				this.on('removedfile', function (file) {
				
					$('.input-{$id}').each(function(index, value) {    
						let el = $(this);    
						if(el.val() === file.name){
							el.remove();
							return;
						}
					});
				
					". \LiquidedgeApp\Octoapp\app\app\js\js::ajax("?c=dropzone/xdelete", [
						"*data" => "!{session_id:'{$id}', filename:file.name, index:file.index}",
						"*success" => "!ondelete.apply(this, [file])"
					])."
					
					this.enable();
					
					$(instance.element).find('.dz-message').toggleClass('d-none', instance.getTotalFiles() > 0)
				});
				
			}";

			$js_options = \LiquidedgeApp\Octoapp\app\app\js\js::create_options($js_options);

			$buffer->script(["*" => "
			
				var $id;
				$(function(){
					$id = new Dropzone('#{$js_id}', $js_options);
					
					setTimeout(function(){
						".($options["crop"] ? "$('.dz-hidden-input').removeAttr('multiple')" : "")."
					}, 500);
				});
			"]);

			if($has_reached_max && $options["max_files"] > 1){
				$buffer->script(["*" => "
					$(function(){
						setTimeout(function(){
							$id.disable();
						}, 0);
					});
				"]);
			}
		$buffer->_div();


		return $buffer->build();
	}
	//--------------------------------------------------------------------------------

	/**
	 * @param $id
	 * @param $fn callable function($uploaded_file, $key){}
	 * @param array $options
	 * @return bool
	 */
    public static function process_upload($id, callable $fn, $options = []): bool {

	    $options = array_merge([
	        "clear" => true
	    ], $options);

        $dropzone_session =  session::make(["id" => $id]);

        if(!$dropzone_session->uploaded_files_arr) return false;

        if($dropzone_session->uploaded_files_arr){
            array_walk($dropzone_session->uploaded_files_arr, $fn);
        }
        if($options["clear"]) $dropzone_session->clear();

        /**
         * //usage
            \app\inc\dropzone\dropzone::process_upload("logo", function($uploaded_file, $key){

                $original = $uploaded_file["original"];
                $cropped = $uploaded_file["cropped"];

                $manager = \com\file\manager\file_item::make();
                $manager->save_from_file($original);
                $file_item_original = $manager->get_file_item();

                $manager = \com\file\manager\file_item::make();
                $manager->save_from_file($cropped);
                $file_item_cropped = $manager->get_file_item();

                console($uploaded_file);
            });
         *
         */

        return true;
    }
	//--------------------------------------------------------------------------------
}