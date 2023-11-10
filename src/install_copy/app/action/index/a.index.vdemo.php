<?php

namespace action\index;

/**
 * Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class vdemo implements \com\router\int\action {

	protected $color_map = [
		"blue",
		"indigo",
		"purple",
		"pink",
		"red",
		"orange",
		"yellow",
		"green",
		"teal",
		"cyan",
		"white",
		"gray",
		"gray-dark",
		"night",
		"dream",
		"dream-dark",
		"sky",
		"velvet",
		"fresh",
		"rose",
		"cream",
	];

	protected $color_theme = [
		"primary",
		"primary-dark",
		"secondary",
		"success",
		"info",
		"warning",
		"danger",
		"light",
		"dark",
	];

	//--------------------------------------------------------------------------------
	use \com\router\tra\action;
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	public function auth() {
		return true;
	}
	//--------------------------------------------------------------------------------
	public function run() {


		$buffer = \app\ui::make()->html_buffer();

		$buffer->div_([".container" => true]);
			$buffer->div_([".row" => true]);
				$buffer->div_([".col-12" => true]);
					$buffer->form("");

					$buffer->header(2, "Forms");
					$buffer->form("");
					$buffer->button("cancel", "core.browser.close_popup()", ["icon" => "fa-times", ".btn-custom" => true]);
					$buffer->submitbutton("Save Changes", false, "parent.requestRefresh", false, false, false, ["icon" => "fa-save", ".btn-success" => true], "core.browser.close_popup();");
					$buffer->button("Download PDF Example", "document.location='?c=xdev.functions/xdownload_pdf_example'");

					// header
					$buffer->header(3, "General Details");
					$buffer->itext(false, "text_test", false);
					$buffer->itext("test text", "text_test", false, ["required" => true, "append" => "R", "prepend" => "p"]);
					$buffer->iselect("test select", "test_select", range(1, 10));
					$buffer->iradio("test radio", "radio", range(1, 10), 1, ["inline" => true]);
					$buffer->icheckbox("test check", "test_check", range(1, 10), 1, ["inline" => true]);
					$buffer->iswitch("test switch", "switch");
					$buffer->itime("itime", "itime", false, ["!change" => "console.log('do ajax')",]);
					$buffer->idate("idate", "idate", \com\date::strtodate());
					$buffer->idatetime("idatetime", "idatetime");
					$buffer->iyearmonth("iyearmonth", "iyearmonth");

					$address = \com\user::$active->get_address(1);
					$buffer->iaddress("iaddress_standard", $address, ["type" => "standard",]);

					$buffer->note("this is a test message");
					$buffer->xbadge("test");

					$buffer->div_([".row" => true]);
					    $buffer->div_([".col-12" => true]);
					    	$toolbar = \app\ui::make()->toolbar();
							$toolbar->add_button(".btn-primary", false, [".btn-primary" => true]);
							$toolbar->add_button(".btn-secondary", false, [".btn-secondary" => true]);
							$toolbar->add_button(".btn-info", false, [".btn-info" => true]);
							$toolbar->add_button(".btn-success", false, [".btn-success" => true]);
							$toolbar->add_button(".btn-warning", false, [".btn-warning" => true]);
							$toolbar->add_button(".btn-danger", false, [".btn-danger" => true]);
							$buffer->add($toolbar->build());
					    $buffer->_div();
					$buffer->_div();

					$buffer->div_([".row" => true]);
					    $buffer->div_([".col-12" => true]);
					    	$toolbar = \app\ui::make()->toolbar();
							$toolbar->add_button(".btn-outline-primary", false, [".btn-outline-primary" => true]);
							$toolbar->add_button(".btn-outline-secondary", false, [".btn-outline-secondary" => true]);
							$toolbar->add_button(".btn-outline-info", false, [".btn-outline-info" => true]);
							$toolbar->add_button(".btn-outline-success", false, [".btn-outline-success" => true]);
							$toolbar->add_button(".btn-outline-warning", false, [".btn-outline-warning" => true]);
							$toolbar->add_button(".btn-outline-danger", false, [".btn-outline-danger" => true]);
							$buffer->add($toolbar->build());
					    $buffer->_div();
					$buffer->_div();

				$buffer->_div();
			$buffer->_div();
		$buffer->_div();


		$buffer->div_([".row my-4" => true, ]);
			$buffer->div_([".col-12" => true, ]);


				$fn_block = function($color)use(&$buffer){
				    $buffer->div_([".p-3 bg-{$color}" => true, ".text-white" => !in_array($color, ["white", "light", "cream"])]);
                        $buffer->add($color);
				    $buffer->_div();
                };

				$buffer->xheader(1, "Theme Colors");
				foreach ($this->color_theme as $color){
                    $fn_block($color);
                }

				$buffer->xheader(1, "Base Colors", false, ['.mt-4' => true]);
				foreach ($this->color_map as $color){
                    $fn_block($color);
                }

			$buffer->_div();
		$buffer->_div();

		$buffer->div_([".row my-4" => true, ]);
			$buffer->div_([".col-md" => true, ]);

				$buffer->add(\app\ui::make()->card_custom()
					->set_heading("Card Title")
					->set_color("yellow")
					->set_dropdown(function (){
						$dropdown = \app\ui::make()->dropdown();
						$dropdown->add_link("Btn 1", "{$this->request->get_panel()}.refresh()");
						$dropdown->add_link("Btn 2", "{$this->request->get_panel()}.refresh()");
						$dropdown->add_link("Btn 3", "{$this->request->get_panel()}.refresh()");
						$dropdown->add_link("Btn 4", "{$this->request->get_panel()}.refresh()");
						return $dropdown;
					})
					->set_html(function() {
						return \app\ui::make()->header(3, "This is the body");
					})
					->build()
				);

			$buffer->_div();

			$buffer->div_([".col-md" => true, ]);
				$buffer->add(\app\ui::make()->card_custom()
					->set_heading("Card Title")
					->set_color("danger")
					->set_dropdown(function (){
						$dropdown = \app\ui::make()->dropdown();
						$dropdown->add_link("Btn 1", "{$this->request->get_panel()}.refresh()");
						$dropdown->add_link("Btn 2", "{$this->request->get_panel()}.refresh()");
						$dropdown->add_link("Btn 3", "{$this->request->get_panel()}.refresh()");
						$dropdown->add_link("Btn 4", "{$this->request->get_panel()}.refresh()");
						return $dropdown;
					})
					->set_html(function() {
						return \app\ui::make()->header(3, "This is the body");
					})
					->build()
				);
			$buffer->_div();

			$buffer->div_([".col-md" => true, ]);
				$buffer->add(\app\ui::make()->card_custom()
					->set_heading("Card Title")
					->set_color("blue")
					->set_dropdown(function (){
						$dropdown = \app\ui::make()->dropdown();
						$dropdown->add_link("Btn 1", "{$this->request->get_panel()}.refresh()");
						$dropdown->add_link("Btn 2", "{$this->request->get_panel()}.refresh()");
						$dropdown->add_link("Btn 3", "{$this->request->get_panel()}.refresh()");
						$dropdown->add_link("Btn 4", "{$this->request->get_panel()}.refresh()");
						return $dropdown;
					})
					->set_html(function() {
						return \app\ui::make()->header(3, "This is the body");
					})
					->build()
				);
		$buffer->_div();

		$buffer->div_([".row my-4" => true, ]);
			$buffer->div_([".col-12" => true, ]);
				$buffer->xparallax(\app\http::get_stream_url(\core::$folders->get_root_files()."/img/demo/pic4.jpg"));
			$buffer->_div();
		$buffer->_div();

		$buffer->div_([".row my-4" => true, ]);
			$buffer->div_([".col-12" => true, ]);
				$carousel = \app\ui::make()->carousel();
				$carousel->add_item(\app\http::get_stream_url(\core::$folders->get_root_files()."/img/demo/pic1.jpg"), [".img-fluid clip-image" => true]);
				$carousel->add_item(\app\http::get_stream_url(\core::$folders->get_root_files()."/img/demo/pic2.jpg"), [".img-fluid clip-image" => true]);
				$carousel->add_item(\app\http::get_stream_url(\core::$folders->get_root_files()."/img/demo/pic3.jpg"), [".img-fluid clip-image" => true]);
				$carousel->add_item(\app\http::get_stream_url(\core::$folders->get_root_files()."/img/demo/pic4.jpg"), [".img-fluid clip-image" => true]);
				$buffer->add($carousel->build([
					"/carousel-inner" => [
						".h-70vh" => true
					],
				]));
			$buffer->_div();
		$buffer->_div();

		$buffer->div_([".row" => true, ]);
			$buffer->div_([".col-md-4" => true, ".col-xl-3" => true, ".monochromatic-blue" => true, ]);
				$buffer->img([".w-100" => true, "@src" => \app\http::get_stream_url(\core::$folders->get_root_files()."/img/demo/pic1.jpg"), ]);
				$buffer->p_();
					$buffer->add("...");
				$buffer->_p();
			$buffer->_div();

			$buffer->div_([".col-md-4" => true, ".col-xl-3" => true, ".monochromatic-brown" => true, ]);
				$buffer->img([".w-100" => true, "@src" => \app\http::get_stream_url(\core::$folders->get_root_files()."/img/demo/pic1.jpg"), ]);
				$buffer->p_();
					$buffer->add("...");
				$buffer->_p();
			$buffer->_div();

			$buffer->div_([".col-md-4" => true, ".col-xl-3" => true, ".monochromatic-fresh" => true, ]);
				$buffer->img([".w-100" => true, "@src" => \app\http::get_stream_url(\core::$folders->get_root_files()."/img/demo/pic1.jpg"), ]);
				$buffer->p_();
					$buffer->add("...");
				$buffer->_p();
			$buffer->_div();

			$buffer->div_([".col-md-4" => true, ".col-xl-3" => true, ".monochromatic-gold" => true, ]);
				$buffer->img([".w-100" => true, "@src" => \app\http::get_stream_url(\core::$folders->get_root_files()."/img/demo/pic1.jpg"), ]);
				$buffer->p_();
					$buffer->add("...");
				$buffer->_p();
			$buffer->_div();

			$buffer->div_([".col-md-4" => true, ".col-xl-3" => true, ".monochromatic-gray" => true, ]);
				$buffer->img([".w-100" => true, "@src" => \app\http::get_stream_url(\core::$folders->get_root_files()."/img/demo/pic1.jpg"), ]);
					$buffer->p_();
					$buffer->add("...");
				$buffer->_p();
			$buffer->_div();

			$buffer->div_([".col-md-4" => true, ".col-xl-3" => true, ".monochromatic-green" => true, ]);
				$buffer->img([".w-100" => true, "@src" => \app\http::get_stream_url(\core::$folders->get_root_files()."/img/demo/pic1.jpg"), ]);
				$buffer->p_();
					$buffer->add("...");
				$buffer->_p();
			$buffer->_div();

			$buffer->div_([".col-md-4" => true, ".col-xl-3" => true, ".monochromatic-red" => true, ]);
				$buffer->img([".w-100" => true, "@src" => \app\http::get_stream_url(\core::$folders->get_root_files()."/img/demo/pic1.jpg"), ]);
				$buffer->p_();
					$buffer->add("...");
				$buffer->_p();
			$buffer->_div();

			$buffer->div_([".col-md-4" => true, ".col-xl-3" => true, ".monochromatic-purple" => true, ]);
				$buffer->img([".w-100" => true, "@src" => \app\http::get_stream_url(\core::$folders->get_root_files()."/img/demo/pic1.jpg"), ]);
				$buffer->p_();
					$buffer->add("...");
				$buffer->_p();
			$buffer->_div();
		$buffer->_div();


		$buffer->div_([".row" => true, ]);
			$buffer->div_([".col-md" => true, ]);
				$buffer->add(\app\ui::make()->image_card()
					->set_src(\app\http::get_stream_url(\core::$folders->get_root_files()."/img/demo/pic1.jpg"))
					->set_hover_content(function(){
						$buffer = \com\ui::make()->buffer();
						$buffer->p_([".display-7" => true, ".text-light" => true, ]);
							$buffer->add("It is a rose!");
						$buffer->_p();

						return $buffer->build();
					})
					->build()
				);
			$buffer->_div();

			$buffer->div_([".col-md" => true, ]);
				$buffer->add(\app\ui::make()->image_card()
					->set_src(\app\http::get_stream_url(\core::$folders->get_root_files()."/img/demo/pic2.jpg"))
					->set_hover_content(function(){
						$buffer = \com\ui::make()->buffer();
						$buffer->p_([".display-7" => true, ".text-light" => true, ]);
							$buffer->add("It is a rose!");
						$buffer->_p();

						return $buffer->build();
					}, [".from-t" => true, ".bg-rose" => true])
					->build()
				);
			$buffer->_div();

			$buffer->div_([".col-md" => true, ]);
			
				$buffer->add(\app\ui::make()->image_card()
					->set_src(\app\http::get_stream_url(\core::$folders->get_root_files()."/img/demo/pic2.jpg"))
					->set_hover_content(function(){
						$buffer = \com\ui::make()->buffer();
						$buffer->p_([".display-7" => true, ".text-light" => true, ]);
							$buffer->add("It is a rose!");
						$buffer->_p();
						$buffer->p_([".text-light" => true, ]);
							$buffer->add("It is a paragraph!");
						$buffer->_p();
						$buffer->p_();
							$buffer->a_([".btn" => true, ".btn-warning" => true, "@href" => "#", ]);
								$buffer->add("Button");
							$buffer->_a();
						$buffer->_p();

						return $buffer->build();
					}, [".from-b bg-danger" => true, "opacity" => 75])
					->build()
				);
			$buffer->_div();
		$buffer->_div();

		$buffer->div_([".row" => true, ]);
			$buffer->div_([".col-md grayscale" => true, ]);
				$buffer->img([".w-100" => true, "@src" => \app\http::get_stream_url(\core::$folders->get_root_files()."/img/demo/pic1.jpg"), ]);
				$buffer->p_();
					$buffer->add("Photo: pic1.jpg");
				$buffer->_p();
			$buffer->_div();

			$buffer->div_([".col-md hover-color" => true, ]);
				$buffer->img([".w-100" => true, "@src" => \app\http::get_stream_url(\core::$folders->get_root_files()."/img/demo/pic1.jpg"), ]);
				$buffer->p_();
					$buffer->add("Photo: pic1.jpg");
				$buffer->_p();
			$buffer->_div();

			$buffer->div_([".col-md hover-grayscale" => true, ]);
				$buffer->img([".w-100" => true, "@src" => \app\http::get_stream_url(\core::$folders->get_root_files()."/img/demo/pic1.jpg"), ]);
				$buffer->p_();
					$buffer->add("Photo: pic1.jpg");
				$buffer->_p();
			$buffer->_div();
		$buffer->_div();


		$buffer->div_([".row" => true, ]);
			$buffer->div_([".col-md" => true, ".hover-zoom-sm" => true, ]);
				$buffer->div_([".o-hidden" => true, ]);
					$buffer->img([".w-100" => true, "@src" => \app\http::get_stream_url(\core::$folders->get_root_files()."/img/demo/pic1.jpg"), ]);
				$buffer->_div();
				$buffer->p_([".display-9" => true, ".text-secondary" => true, ]);
					$buffer->add("Photo: pic1.jpg");
				$buffer->_p();
			$buffer->_div();

			$buffer->div_([".col-md" => true, ".hover-zoom" => true, ]);
				$buffer->div_([".o-hidden" => true, ]);
					$buffer->img([".w-100" => true, "@src" => \app\http::get_stream_url(\core::$folders->get_root_files()."/img/demo/pic1.jpg"), ]);
				$buffer->_div();
				$buffer->p_([".display-9" => true, ".text-secondary" => true, ]);
					$buffer->add("Photo: pic1.jpg");
				$buffer->_p();
			$buffer->_div();

			$buffer->div_([".col-md" => true, ".hover-zoom-lg" => true, ]);
				$buffer->div_([".o-hidden" => true, ]);
					$buffer->img([".w-100" => true, "@src" => \app\http::get_stream_url(\core::$folders->get_root_files()."/img/demo/pic1.jpg"), ]);
				$buffer->_div();
				$buffer->p_([".display-9" => true, ".text-secondary" => true, ]);
					$buffer->add("Photo: pic1.jpg");
				$buffer->_p();
			$buffer->_div();
		$buffer->_div();

		$buffer->div_([".container mt-4 mb-5" => true]);
		    $buffer->div_([".row" => true]);
		        $buffer->div_([".col-3" => true]);
		        	$flip_card = \app\ui::make()->flip_card("100%", "250px");
		        	$flip_card->set_content_front(function(){
		        		return \app\ui::make()->image(\app\http::get_stream_url(\core::$folders->get_root_files()."/img/demo/pic1.jpg"), [".clip-image" => true, "#min-height" => "250px"]);
					});
		        	$flip_card->set_content_back("test", [".bg-red text-white" => true]);
		        	$buffer->add($flip_card->build());
		        $buffer->_div();
		        $buffer->div_([".col-3" => true]);
		            $flip_card = \app\ui::make()->flip_card("100%", "250px");
		            $flip_card->set_content_front(function(){
		        		return \app\ui::make()->image(\app\http::get_stream_url(\core::$folders->get_root_files()."/img/demo/pic2.jpg"), [".clip-image" => true, "#min-height" => "250px"]);
					});
		        	$flip_card->set_content_back("test", [".bg-orange text-white" => true]);
		        	$buffer->add($flip_card->build());
		        $buffer->_div();
		        $buffer->div_([".col-3" => true]);
		            $flip_card = \app\ui::make()->flip_card("100%", "250px");
		            $flip_card->set_content_front(function(){
		        		return \app\ui::make()->image(\app\http::get_stream_url(\core::$folders->get_root_files()."/img/demo/pic3.jpg"), [".clip-image" => true, "#min-height" => "250px"]);
					});
		        	$flip_card->set_content_back("test", [".bg-green text-white" => true]);
		        	$buffer->add($flip_card->build());
		        $buffer->_div();
		        $buffer->div_([".col-3" => true]);
		            $flip_card = \app\ui::make()->flip_card("100%", "250px");
		            $flip_card->set_content_front(function(){
		        		return \app\ui::make()->image(\app\http::get_stream_url(\core::$folders->get_root_files()."/img/demo/pic4.jpg"), [".clip-image" => true, "#min-height" => "250px"]);
					});
		        	$flip_card->set_content_back("test", [".bg-purple text-white" => true]);
		        	$buffer->add($flip_card->build());
		        $buffer->_div();
		    $buffer->_div();
		$buffer->_div();

		$buffer->div_([".container" => true]);
			$buffer->div_([".row" => true]);
				$buffer->div_([".col-12" => true]);

					$fn_array_rand = function($arr, $int = 5){
						return array_rand(array_flip($arr), $int);
					};

					$buffer->div_([".mb-7" => true]);
						$h_color_map = $fn_array_rand($this->color_map);
						array_walk($h_color_map, function($color)use(&$buffer, $h_color_map){
							$options = [".p-3 mb-2 bg-{$color} text-white" => !in_array($color, ["white", "cream"])];
							$options["^"] = \app\ui::make()->tag()->div($options);
							$buffer->div($options);
						});
					$buffer->_div();

					$buffer->div_([".mb-7" => true]);
						$h_color_map = $fn_array_rand($this->color_map);
						array_walk($h_color_map, function($color, $index)use(&$buffer, $h_color_map){
							$options = [".dividered dividered-$color" => true];
							if(rand(0, 2) == 1) $options[".dividered-animated"] = true;
							$buffer->header(3, "UI Header ".implode(array_keys($options)), false, $options);
						});
					$buffer->_div();

					$fn_border = function($color, $size, $options = []) use(&$buffer){
						$options = array_merge([
						    ".border-$size" => true,
						    ".border-$color" => true,
						], $options);

						$options[".p-3 bg-light mb-3"] = true;
						$options["^"] = \app\ui::make()->tag()->div($options);
						$buffer->div($options);
					};

					$buffer->div_([".mb-7" => true]);
						foreach ($fn_array_rand($this->color_map) as $color) $fn_border($color, 4, [".border rounded-6" => true]);
					$buffer->_div();

					$buffer->div_([".mb-7" => true]);
						foreach ($fn_array_rand($this->color_map, 3) as $color) $fn_border($color, 4, [".border-start" => true]);
					$buffer->_div();

					$buffer->div_([".mb-7" => true]);
						foreach ($fn_array_rand($this->color_map, 3) as $color) $fn_border($color, 4, [".border-end" => true]);
					$buffer->_div();

					$buffer->div_([".mb-7" => true]);
						foreach ($fn_array_rand($this->color_map, 3) as $color) $fn_border($color, 4, [".border-top" => true]);
					$buffer->_div();

					$buffer->div_([".mb-7" => true]);
						foreach ($fn_array_rand($this->color_map, 3) as $color) $fn_border($color, 4, [".border-bottom" => true]);
					$buffer->_div();

				$buffer->_div();
			$buffer->_div();
		$buffer->_div();

		$buffer->flush();

	}
	//--------------------------------------------------------------------------------
}