<?php

namespace LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap;

/**
 * @package app\ui\set\bootstrap
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class carousel extends \com\ui\intf\element {

	protected $item_arr = [];

	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	protected function __construct($options = []) {
		// init
		$this->name = "Carousel";
	}
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	public function add_item($src, $options = []) {
		$this->item_arr[] = array_merge([
			"html" => false,
		    "@src" => $src,
		    "caption" => false,
		    "caption_body" => false,
			"/caption_body" => [],
		], $options);
	}
	//--------------------------------------------------------------------------------
	public function add_html($html, $options = []) {

		if(is_callable($html)) $html = $html();

		$this->item_arr[] = array_merge([
		    "@src" => false,
		    "html" => $html,
		    "caption" => false,
		    "caption_body" => false,
		    "/caption_body" => [],
		], $options);
	}
	//--------------------------------------------------------------------------------
	public function build($options = []) {
		// options
		$options = array_merge([
			"@id" => \LiquidedgeApp\Octoapp\app\app\str\str::get_random_id("carousel"),
			".carousel" => true,
			".slide" => sizeof($this->item_arr) > 1,
			".carousel-fade" => false,
			"@data-bs-ride" => "carousel",

			"enable_indicators" => true,
			"enable_controls" => true,
			"enable_gallery" => false,

			"/carousel-inner" => [],
  		], $options);

		if(!$this->item_arr) return "";

		$first_index = \LiquidedgeApp\Octoapp\app\app\arr\arr::get_first_index($this->item_arr);
		$buffer = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();

		$buffer->div_($options);
			if($options["enable_indicators"] && sizeof($this->item_arr) > 1){
				$buffer->ol_([".carousel-indicators d-none d-md-flex" => true, ]);
					foreach ($this->item_arr as $key => $item){
					    $buffer->button([
					        "@type" => "button",
					        "@data-bs-target" => "#{$options["@id"]}",
					        "@data-bs-slide-to" => $key,
					        ".active" => $first_index == $key,
					        "@aria-current" => $first_index == $key,
					        "@aria-label" => "Slide ".($key+1),
                        ]);
//						$buffer->li(["@data-bs-target" => "#{$options["@id"]}", "@data-bs-slide-to" => $key, ".active" => $first_index == $key, ]);
					}
				$buffer->_ol();
			}

			$options["/carousel-inner"][".carousel-inner"] = true;

			$buffer->div_($options["/carousel-inner"]);

				$fn_add_item = function($item, $count) use(&$buffer, $first_index){

					$item = array_merge([
					    ".d-block" => true,
						"@alt" => "Slide {$count}"
					], $item);

					if($item["@src"] || $item["html"]){
						$buffer->div_([".carousel-item" => true, ".active" => $first_index == $count]);

							if($item["html"]){
								$buffer->add($item["html"]);
							}else{
								$item["/caption_body"][".carousel-caption"] = true;
								$item["/caption_body"][".d-block"] = true;
								$buffer->div_($item["/caption_body"]);
									if($item["caption"]) $buffer->h5(["*" => $item["caption"]]);
									if($item["caption_body"]) {
										$content_body = is_callable($item["caption_body"]) ? $item["caption_body"]() : $item["caption_body"];
										$buffer->add($content_body);
									}
								$buffer->_div();
								$buffer->img($item);
							}
						$buffer->_div();
					}
				};

				foreach ($this->item_arr as $key => $item){
					$fn_add_item($item, $key);
				}
			$buffer->_div();

			if(sizeof($this->item_arr) > 1 && $options["enable_controls"]){
				$buffer->a_([".carousel-control-prev" => true, "@href" => "#{$options["@id"]}", "@role" => "button", "@data-bs-slide" => "prev", ]);
					$buffer->span([".carousel-control-prev-icon" => true, "@aria-hidden" => "true", ]);
					$buffer->span_([".sr-only" => true, ]);
						$buffer->add("Previous");
					$buffer->_span();
				$buffer->_a();

				$buffer->a_([".carousel-control-next" => true, "@href" => "#{$options["@id"]}", "@role" => "button", "@data-bs-slide" => "next", ]);
					$buffer->span([".carousel-control-next-icon" => true, "@aria-hidden" => "true", ]);
					$buffer->span_([".sr-only" => true, ]);
						$buffer->add("Next");
					$buffer->_span();
				$buffer->_a();
			}
		$buffer->_div();

		if($options["enable_gallery"] && sizeof($this->item_arr) > 1){


		    $buffer->div_([".container-fluid my-2 p-0" => true]);
                $buffer->div_([".row" => true]);
                    $buffer->div_([".col-12" => true]);
                        $buffer->div_([".carousel-gallery-wrapper row g-1 flex-nowrap overflow-auto pb-1" => true]);
                            foreach ($this->item_arr as $key => $item){
                                $buffer->div_([".col" => true]);
                                    $buffer->ximage($item["@src"], [
                                        ".w-100 clip-image h-100 min-w-100px" => true,
                                        ".zoom zoom-sm" => !\LiquidedgeApp\Octoapp\app\app\http\http::is_mobile(),
                                        "@type" => "button",
                                        "@data-bs-target" => "#{$options["@id"]}",
                                        "@data-bs-slide-to" => $key,
                                        ".active" => $first_index == $key,
                                        "@aria-current" => $first_index == $key,
                                        "@aria-label" => "Slide ".($key+1),
                                    ]);
                                $buffer->_div();
                            }
                        $buffer->_div();
                    $buffer->_div();
                $buffer->_div();
		    $buffer->_div();

		    \com\js::add_script("
				$(function(){
					$('.carousel-gallery-wrapper').on('mousewheel DOMMouseScroll', function(event){

						var delta = Math.max(-1, Math.min(1, (event.originalEvent.wheelDelta || -event.originalEvent.detail)));
						let el = $(this);
						
						$(this).scrollLeft( $(this).scrollLeft() - ( delta * 100 ) );
						event.preventDefault();
				
					});
				
					let slider = $('.carousel-gallery-wrapper')[0];
					let isDown = false;
					let startX;
					let scrollLeft;
			
					if(!slider) return;
			
					slider.addEventListener('mousedown', (e) => {
						let target = $(e.target);
						if(!target.closest('.carousel-gallery-thumb-card').length) return;
			
						isDown = true;
						slider.classList.add('active');
						startX = e.pageX - slider.offsetLeft;
						scrollLeft = slider.scrollLeft;
					});
					slider.addEventListener('mouseleave', () => {
						isDown = false;
						slider.classList.remove('active');
					});
					slider.addEventListener('mouseup', () => {
						isDown = false;
						slider.classList.remove('active');
					});
					slider.addEventListener('mousemove', (e) => {
						if (!isDown) return;
						e.preventDefault();
						const x = e.pageX - slider.offsetLeft;
						const walk = (x - startX) * 1.5; //scroll-fast
						slider.scrollLeft = scrollLeft - walk;
					});
				})
			");

//			$buffer->div_([".carousel-gallery-wrapper mt-2" => true]);
//			foreach ($this->item_arr as $item){
//					$buffer->div_([".carousel-gallery-thumb-card me-2" => true]);
//						$buffer->div([
//							"*" => \app\inc\fancybox\fancybox::get_stream($item["@src"]),
//							"#height" => "100px", "#width" => "150px",
//						]);
//					$buffer->_div();
//			}
//			$buffer->_div();

		}

		return $buffer->build();

	}
	//--------------------------------------------------------------------------------
}