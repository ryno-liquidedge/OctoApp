<?php

namespace LiquidedgeApp\Octoapp\app\app\inc\dropzone;

/**
 * @package app\dropzone
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class cropper extends \LiquidedgeApp\Octoapp\app\app\intf\standard {
	//--------------------------------------------------------------------------------
	// fields
	//--------------------------------------------------------------------------------
	/**
	 * 0: no restrictions
	 * 1: restrict the crop box not to exceed the size of the canvas.
	 * 2: restrict the minimum canvas size to fit within the container. If the proportions of the canvas and the container differ, the minimum canvas will be surrounded by extra space in one of the dimensions.
	 * 3: restrict the minimum canvas size to fill fit the container. If the proportions of the canvas and the container are different, the container will not be able to fit the whole canvas in one of the dimensions.
	 * @var int
	 */
	protected $view_mode = 0;

	protected $id = "";
	protected $id_wrapper = "";

	protected $src = "";
	protected $dest = "";

	protected $id_form = "";
	protected $action = "";

	/**
	 * function(response, data){}
	 * @var string
	 */
	protected $on_success = "function(response, data){}";

	/**
	 * function(data){}
	 * @var string
	 */
	protected $on_cancel = "function(data){}";

	/**
	 * function(data){}
	 * @var string
	 */
	protected $before_send = "function(data){
	    core.form.set_button_loading('.btn-crop');
	}";

	protected $width = 800;
	protected $height = 400;

	protected $data_arr = [];
	protected $options = [];

	protected $enable_loader = true;
	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	protected function __construct($options = []) {
		// init
		$this->name = "Cropper";
		$this->options = $options;

		$this->init($options);
	}
	//--------------------------------------------------------------------------------
	private function init($options = []){

		// options
		$options = array_merge([
			"id" => "cropper_". \LiquidedgeApp\Octoapp\app\app\str\str::get_random_alpha(5, ["lowercase" => true,]),
			"src" => false,
			"dest" => false,
			"width" => 800,
			"height" => 400,
			"view_mode" => $this->view_mode,
			"action" => "?c=dropzone/xcrop",
			"!beforeSend" => false,
			"!success" => false,
  		], $options);

		$this->set_id($options["id"]);
		$this->set_src($options["src"]);
		$this->set_dest($options["dest"]);
		$this->set_action($options["action"]);
		$this->set_on_success($options["!success"]);
		$this->set_view_mode($options["view_mode"]);
		$this->set_width($options["width"]);
		$this->set_height($options["height"]);
		$this->set_height($options["height"]);

	}
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	public function build($options = []) {
		// options
		$options = array_merge([
  		], $options, $this->options);

		$buffer = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();
		$buffer->div_([".d-flex justify-content-end mb-2" => true]);
			$buffer->xbutton("Crop", $this->build_submit_js(), ["icon" => "fa-save", ".btn-success mr-1 me-1 btn-crop" => true]);
			$buffer->xbutton(false, $this->build_cancel_js(), ["icon" => "fa-times", "/icon" => [".mr-2" => false], ".btn-warning" => true]);
		$buffer->_div();
		$buffer->div_([".ui-cropper" => true, "@id" => $this->id_wrapper]);

			if($this->enable_loader) $this->build_loader($buffer);

			$image_wrapper_options = [];
			$image_wrapper_options[".ui-cropper-image-wrapper"] = true;
			if($this->enable_loader) $image_wrapper_options["#opacity"] = "0";
			if($this->height) {
				$height = \LiquidedgeApp\Octoapp\app\app\data\data::parse_float($this->height);
				$image_wrapper_options["#min-height"] = "400px";
				if($height > 400 && $height < 600) $image_wrapper_options["#min-height"] = "{$this->height}px";
			}
			$image_wrapper_options["#max-height"] = "600px";

			$buffer->div_($image_wrapper_options);
				$buffer->img(["@id" => $this->id, "@src" => $this->src]);
			$buffer->_div();
		$buffer->_div();

		$js_options = [];
		$js_options["*responsive"] = true;
		$js_options["*checkOrientation"] = true;
		$js_options["*minContainerWidth"] = 400;
		$js_options["*minContainerHeight"] = 200;
		$js_options["*wheelZoomRatio"] = 0.1;
		$js_options["*rotatable"] = false;
		$js_options["*viewMode"] = $this->view_mode;
		$js_options["*aspectRatio"] = "!{$this->parse_ratio()}";
		$js_options["*crop"] = "!function(event){
            core.form.unset_button_loading('.btn-crop');
			$.each(event.detail, function( index, value ) {
				let el = $('#crop_'+index);
				if(el.length) el.val(value);
				else fn_input('crop_'+index+'[{$this->id}]', value);
			});
		}";

		$buffer->script(["*" => "
			var {$this->id};
			$(function(){
				//build form
				var form = document.createElement('form');
				form.setAttribute('method', 'POST');
				form.setAttribute('action', '{$this->action}');
				form.setAttribute('id', '{$this->id_form}');
				form.setAttribute('name', '{$this->id_form}');
				
				let fn_input = function(id, value, force){
					// Create an input element for Full Name
					var FN = force ? false : document.getElementById(id);
					if(FN){
						FN.setAttribute('value', value);
					}else{
						FN = document.createElement('input');
						FN.setAttribute('type', 'hidden');
						FN.setAttribute('name', id);
						FN.setAttribute('id', id);
						FN.setAttribute('value', value);
						form.appendChild(FN);
					}
					return FN;
				};
				
				fn_input('_csrf', '".\core::$app->get_response()->get_csrf()."', true);
				fn_input('cropper_id', '{$this->id}');
				fn_input('desired_width[{$this->id}]', '{$this->width}');
				fn_input('desired_height[{$this->id}]', '{$this->height}');
				
				document.getElementsByTagName('body')[0].appendChild(form);
				
				//init
				let cropper = $('#{$this->id}').cropper(".\com\js::create_options($js_options).");
				
				// Get the Cropper.js instance after initialized
				{$this->id} = cropper.data('cropper');
				
				$('.ui-cropper-loader').fadeOut(function(){
					$('.ui-cropper-image-wrapper').css('opacity', 1);
				});
			})
		"]);

		return $buffer->build();
	}

	//--------------------------------------------------------------------------------
	public function parse_ratio() {
		$separator = " / ";
		$gcd = function ($a, $b) use (&$gcd) {
			return ($a % $b) ? $gcd($b, $a % $b) : $b;
		};
		$g = $gcd($this->width, $this->height);
		return $this->width / $g . $separator . $this->height / $g;
	}
	//--------------------------------------------------------------------------------
	private function build_cancel_js() {

		$this->data_arr["src"] = $this->src;

		$js = [];
		$js[] = "let data = {$this->id}.getData();";
		foreach ($this->data_arr as $key => $value){
			$js[] = "data.$key = '{$value}';";
		}

		$js = implode("\n", $js);


		return "
			let data = {};
			if({$this->id}){
			
				try{
					{$js}
					let dropzone = eval(data.dropzone_id);
					if(dropzone){
						let uploadedFiles = dropzone.getAcceptedFiles();
						
						if(uploadedFiles){
							$.each(uploadedFiles, function( index, file ) {
								if(file.index === data.index){
									dropzone.removeFile(file);
								}
							});
						}
					}
				}catch(ex){}
			}
			
			
			let fn = {$this->on_cancel};
			if(typeof fn == 'function'){
				fn.apply(this, [data]);
			}
			core.browser.close_popup();
				
		";
	}
	//--------------------------------------------------------------------------------
	private function build_submit_js() {
		return \com\js::ajax($this->action, [
			"*form" => "#{$this->id_form}",
			"*data" => $this->data_arr,
			"*beforeSend" => "!{$this->before_send}",
			"*success" => "function(response){
				let data = {$this->id}.getData();
				let fn = {$this->on_success};
				if(typeof fn == 'function'){
					fn.apply(this, [response, data]);
				}
				core.browser.close_popup();
			}",
		]);
	}
	//--------------------------------------------------------------------------------
	/**
	 * @param string $before_send
	 */
	public function set_before_send(string $before_send): void {
		if($before_send)
			$this->before_send = $before_send;
	}
	//--------------------------------------------------------------------------------
	/**
	 * @param string $on_success
	 */
	public function set_on_success(string $on_success): void {
		if($on_success)
			$this->on_success = $on_success;
	}
	//--------------------------------------------------------------------------------
	/**
	 * @param string $on_cancel
	 */
	public function set_on_cancel(string $on_cancel): void {
		if($on_cancel)
			$this->on_cancel = $on_cancel;
	}
	//--------------------------------------------------------------------------------

	/**
	 * @param string $id
	 */
	public function set_id(string $id): void {
		$this->id = $id;
		$this->id_form = "form_{$id}";
		$this->id_wrapper = "wrapper_{$id}";

		$this->add_data("id", $this->id);
	}
	//--------------------------------------------------------------------------------

	/**
	 * @param string $src
	 */
	public function set_src(string $src): void {
		$this->src = $src;
	}
	//--------------------------------------------------------------------------------
	/**
	 * @param string $dest
	 */
	public function set_dest(string $dest): void {
		$this->dest = $dest;
	}
	//--------------------------------------------------------------------------------

	/**
	 * @param $key
	 * @param $value
	 */
	public function add_data($key, $value){
		$this->data_arr[$key] = $value;
	}
	//--------------------------------------------------------------------------------

	/**
	 * @param int $action
	 */
	public function set_action($action): void {
		$this->action = $action;
	}
	//--------------------------------------------------------------------------------

	/**
	 * @param int $view_mode
	 */
	public function set_view_mode(int $view_mode): void {
		$this->view_mode = $view_mode;
	}
	//--------------------------------------------------------------------------------

	/**
	 * @param int $width
	 */
	public function set_width(int $width): void {
		$this->width = $width;
	}
	//--------------------------------------------------------------------------------

	/**
	 * @param int $height
	 */
	public function set_height(int $height): void {
		$this->height = $height;
	}
	//--------------------------------------------------------------------------------
    private function build_loader(&$buffer) {
        $buffer->div_([".ui-cropper-loader" => true, "#position" => "absolute", "#z-index" => "99", "#width" => "95%"]);
            $buffer->div_(["#text-align" => "center", "#margin-top" => "160px"]);
                $buffer->xicon("fa-spinner", [".fa-spin" => true, "#color" => "#bc2d15", "#font-size" => "50px"]);
            $buffer->_div();
        $buffer->_div();
    }
	//--------------------------------------------------------------------------------
}