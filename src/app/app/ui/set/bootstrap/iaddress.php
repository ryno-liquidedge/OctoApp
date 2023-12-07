<?php

namespace LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap;

/**
 * Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class iaddress extends \com\ui\intf\element {

	protected $js_id;

	//--------------------------------------------------------------------------------
	// fields
	//--------------------------------------------------------------------------------
	protected static $is_singleton = true;
	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	protected function __construct($options = []) {
		// init
		$this->name = "Address input";
		$this->js_id = \LiquidedgeApp\Octoapp\app\app\str\str::get_random_id("address");
	}
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	public function build($options = []) {
		$options = array_merge([
		    "index" => false,
		    "type" => false,
		], $options);

		switch ($options["type"]){
			case "simple": return $this->get_simple($options);
			case "autocomplete": return $this->get_autocomplete($options);
			case "google_api": return $this->get_google_api($options);
			case "website": return $this->get_website($options);
			default: return $this->get_standard($options);
		}
	}
	//--------------------------------------------------------------------------------
	public function get_website($options) {
		$options = array_merge([
		    "index" => false,
			"address" => false,
			"required" => true,
		], $options);

		$address = \core::dbt("address")->splat($options["address"]);
		$suburb = $address->is_empty("add_ref_suburb") ? false : \core::dbt("suburb")->get_fromdb($address->add_ref_suburb);
		$town = $address->is_empty("add_ref_town") ? false : \core::dbt("town")->get_fromdb($address->add_ref_town);
		$province = $address->is_empty("add_ref_province") ? false : \core::dbt("province")->get_fromdb($address->add_ref_province);
		$country = $address->is_empty("add_ref_country") ? false : \core::dbt("country")->get_fromdb($address->add_ref_country);
		$index = $options["index"] !== false ? $options["index"] : $address->id;

		$buffer = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->html_buffer();
		$buffer->ihidden("source[{$index}]", "website");
		$buffer->xihidden("add_type[{$index}]", 1);

		$buffer->itext("Street Address", "add_street[{$index}]", $address->add_street, ["required" => true, "required_icon" => false, "label_col" => 4, "focus" => true]);
        $buffer->itext("Suburb", "add_ref_suburb[{$index}]", ($suburb ? $suburb->name : false), [
            "required" => false,
            "required_icon" => false,
            "label_col" => 4,
            "autocomplete" => "/index.php?c=index/xauto_suburb",
            "autocomplete_select_complete" => "
                setTimeout(function(){
                    $(event.target).val(ui.item.sub_name);
                    $('#tow_name\\\[{$index}\\\]').val(ui.item.tow_name);
                    $('#tow_id\\\[{$index}\\\]').val(ui.item.tow_id);
                    $('#add_code\\\[{$index}\\\]').val(ui.item.sub_residential_code);
                }, 10);
            ",
            "@placeholder" => "e.g. aurora, durbanville",
            "autocomplete_value" => false,
        ]);
        $buffer->ihidden("add_ref_town[[{$index}]", ($town ? $town->id : false));
        $buffer->itext("City", "tow_name[{$index}]", ($town ? $town->name : false), [
            "@disabled" => true,
            "label_col" => 4,
            "@placeholder" => "Please select a suburb first",
        ]);
        $buffer->itext("Postal Code", "add_code[{$index}]", ($address->add_code), ["required" => true, "required_icon" => false, "label_col" => 4, "width" => "50%", "limit" => "numeric_positive"]);

		return $buffer->build();
	}
	//--------------------------------------------------------------------------------
	public function get_google_api($options) {
		$options = array_merge([
			"address" => false,
			"map_id" => $this->js_id."_map",
			"center" => [],
			"marker" => [],
			"bounds" => ["lat" => -33.915538, "lng" => 18.6560594], //centered on cape town
			"enable_map" => true,
			"required" => true,
			"!click_map" => "function(data, map){}",
			"/map" => [],
		], $options);



		$address = \core::dbt("address")->splat($options["address"]);
		$suburb = $address->is_empty("add_ref_suburb") ? false : \core::dbt("suburb")->get_fromdb($address->add_ref_suburb);
		$town = $address->is_empty("add_ref_town") ? false : \core::dbt("town")->get_fromdb($address->add_ref_town);
		$province = $address->is_empty("add_ref_province") ? false : \core::dbt("province")->get_fromdb($address->add_ref_province);
		$country = $address->is_empty("add_ref_country") ? false : \core::dbt("country")->get_fromdb($address->add_ref_country);

		if($address->add_gps_lat && $address->add_gps_lng){
			$options["center"] = ["lat" => $address->add_gps_lat, "lng" => $address->add_gps_lng];
			$options["marker"] = ["lat" => $address->add_gps_lat, "lng" => $address->add_gps_lng];
		}
		if(!$options["center"]) $options["center"] = $options["bounds"];

		$buffer = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();
		$buffer->xihidden("source", "google_maps_api");
		$buffer->xihidden("add_type", 1);

		$buffer->div_([".row" => true]);
			$buffer->div_([".col" => true]);
				$buffer->xihidden("add_gps_lat", $address->add_gps_lat);
				$buffer->xihidden("add_gps_lng", $address->add_gps_lng);
				$buffer->div_(".form-row .row .g-2 .mb-2");
					$buffer->div_(".col-3")->xitext("apt-input", $address->add_streetnr, "Unit / Street nr.", ["label_hidden" => true, "@placeholder" => "Unit / Str nr.", "limit" => "alpha_numeric", ".ui-w-full" => true, "required" => $options["required"]])->_div();
					$buffer->div_(".col")->xitext("location-input", $address->add_street, "Street", ["label_hidden" => true, "@placeholder" => "Street", ".ui-w-full" => true, "required" => $options["required"]])->_div();
				$buffer->_div();
				$buffer->xitext("sublocality_level_2-input", $suburb ? $suburb->name : false, false, ["@placeholder"=>"City", ".mb-2"=>true, "@disabled"=>true]);
				$buffer->xitext("locality-input", $town ? $town->name : false, false, ["@placeholder"=>"City", ".mb-2"=>true, "@disabled"=>true]);
				$buffer->div_([".d-flex mb-2" => true]);
					$buffer->xitext("administrative_area_level_1-input", ($province) ? $province->name : false, false, ["@placeholder"=>"State/Province", "@disabled"=>true, "/wrapper" => [".align-items-center me-2 w-100" => true],]);
					$buffer->xitext("postal_code-input", $address->add_code, false, ["@placeholder"=>"Zip/Postal code", ".half-input" => true, "@disabled" => true]);
				$buffer->_div();
				$buffer->xitext("country-input", $country ? $country->name : false, false, ["@placeholder"=>"Country", "@disabled"=>true]);
			$buffer->_div();
			if($options["enable_map"]){
				$buffer->div_([".col-12 col-md-5" => true]);

					$options["/map"][".map w-100 min-h-200px"] = true;
					$options["/map"]["@id"] = $options["map_id"];
					$buffer->div($options["/map"]);

				$buffer->_div();
			}
		$buffer->_div();

		$ini_options = \LiquidedgeApp\Octoapp\app\app\js\js::create_options([
			"*ctaTitle" => "Save",
			"*mapsApiKey" => \core::$app->get_instance()->get_option("google.maps.api.key"),
			"*capabilities" => [
				"addressAutocompleteControl" => true,
				"mapDisplayControl" => true,
				"ctaControl" => true,
			],
			"*mapOptions" => [
				"center" => $options["center"],
				"fullscreenControl" => true,
				"mapTypeControl" => false,
				"streetViewControl" => false,
				"zoom" => 11,
				"zoomControl" => true,
				"maxZoom" => 22,
			],
		]);
		$addressNameFormat = \LiquidedgeApp\Octoapp\app\app\js\js::create_options([
			'*street_number' => 'short_name',
			'*route' => 'long_name',
			'*locality' => 'long_name',
			'*sublocality_level_2' => 'long_name',
			'*administrative_area_level_1' => 'long_name',
			'*country' => 'long_name',
			'*postal_code' => 'short_name',
		]);
		$buffer->script(["*" => "
			var {$this->js_id};
			function initAutocompleteMap() {
			  const position = ".json_encode($options["bounds"]).";
			  const CONFIGURATION = $ini_options;
			  const componentForm = [
			    'location',
			    'sublocality_level_2',
			    'locality',
			    'administrative_area_level_1',
			    'country',
			    'postal_code',
			  ];
			
			  const getFormInputElement = (component) => document.getElementById(component + '-input');
			  {$this->js_id} = new google.maps.Map(document.getElementById(\"{$options["map_id"]}\"), {
			    zoom: CONFIGURATION.mapOptions.zoom,
			    center: position,
			    mapTypeControl: false,
			    fullscreenControl: CONFIGURATION.mapOptions.fullscreenControl,
			    zoomControl: CONFIGURATION.mapOptions.zoomControl,
			    streetViewControl: CONFIGURATION.mapOptions.streetViewControl
			  });
			  
			  google.maps.Map.prototype.markers = new Array();
			  google.maps.Map.prototype.addMarker = function(position, options) {
				// options
				var options = $.extend({
					key: Object.values(position).join('-'),
					title: null,
					label: null,
					position: position,
					map: {$this->js_id},
				}, (options == undefined ? {} : options));
				
				var marker = new google.maps.Marker(options);  
				if (options.complete) options.complete.apply(this, [marker, options]);
				{$this->js_id}.markers.push(marker);
				
				return marker;
				
			};
			google.maps.Map.prototype.clearMarkers = function() {
				for(var i=0; i<{$this->js_id}.markers.length; i++){
					{$this->js_id}.markers[i].setMap(null);
				}
				{$this->js_id}.markers = new Array();
			};
			google.maps.Map.prototype.getReverseGeocodingData = function(lat, lng, data) {
				var latlng = new google.maps.LatLng(lat, lng);
				var geocoder = new google.maps.Geocoder();
				
				var getValue = function(field, address){
					let value;
					address.address_components.every(function(item, index) {
						if(item.types.includes(field)){
							value = item.long_name;
							return false;
						}
						return true;
					});
					return value;
				};
				
				geocoder.geocode({ 'latLng': latlng },  (results, status) =>{
					if (status !== google.maps.GeocoderStatus.OK) core.browser.alert(status);
					
					if (status == google.maps.GeocoderStatus.OK) {
						
						document.getElementById('add_gps_lat').value = latlng.lat();
						document.getElementById('add_gps_lng').value = latlng.lng();
						
						results.every(function(item, index) {
							if(
								item.types.includes('route') || 
								item.types.includes('sublocality') || 
								item.types.includes('sublocality_level_2') ||
								item.types.includes('administrative_area_level_1') ||
								item.types.includes('country') ||
								item.types.includes('postal_code')
							){
								getFormInputElement('location').value = getValue('route', item);
								getFormInputElement('sublocality_level_2').value = getValue('sublocality', item);
								getFormInputElement('locality').value = getValue('locality', item);
								getFormInputElement('administrative_area_level_1').value = getValue('administrative_area_level_1', item);
								getFormInputElement('country').value = getValue('country', item);
								getFormInputElement('postal_code').value = getValue('postal_code', item);
								return false;
							}
							return true;
						});
					}
				});
				
			};
			google.maps.event.addListener({$this->js_id}, 'click', function(e) {
				let position = {
					lat:e.latLng.lat(), 
					lng:e.latLng.lng()
				};
				
				{$this->js_id}.clearMarkers();
				setTimeout(function(){
					{$this->js_id}.addMarker(e.latLng);
					{$this->js_id}.panTo(position);
					let data = {
						position:position, 
						address:{}
					};
					{$this->js_id}.getReverseGeocodingData(position.lat, position.lng, data);
					
					setTimeout(function(){
						let fn = eval({$options['!click_map']});
						fn.apply(this, [data, {$this->js_id}]);
					}, 200);
				}, 100);
				
				
			});
			
			setTimeout(function(){
				let add_gps_lat = parseFloat(".($address->add_gps_lat ? $address->add_gps_lat : 0).");
				let add_gps_lng = parseFloat(".($address->add_gps_lng ? $address->add_gps_lng : 0).");
				if(add_gps_lat && add_gps_lng){
					let position = {lat:add_gps_lat, lng:add_gps_lng};
					{$this->js_id}.addMarker(position);
					{$this->js_id}.panTo(position);
					{$this->js_id}.setZoom(16);
				}
			}, 100);
			
			  const marker = new google.maps.Marker({map: {$this->js_id}, draggable: false});
			  const autocompleteInput = getFormInputElement('location');
			  const autocomplete = new google.maps.places.Autocomplete(autocompleteInput, {
			    fields: [\"address_components\", \"geometry\", \"name\"],
			    types: [\"address\"],
			    bounds: new google.maps.LatLngBounds(position),
			  });
			  autocomplete.addListener('place_changed', function () {
			    marker.setVisible(false);
			    const place = autocomplete.getPlace();
			    if (!place.geometry) {
			      // User entered the name of a Place that was not suggested and
			      // pressed the Enter key, or the Place Details request failed.
			      core.browser.alert('No details available for input: \'' + place.name + '\'');
			      return;
			    }
			    renderAddress(place);
			    fillInAddress(place);
			  });
			
			  function fillInAddress(place) {  // optional parameter
			    const addressNameFormat = $addressNameFormat;
			    const getAddressComp = function (type) {
			      for (const component of place.address_components) {
			        if (component.types[0] === type) {
			          return component[addressNameFormat[type]];
			        }
			      }
			      return '';
			    };
			    getFormInputElement('location').value = getAddressComp('street_number') + ' ' + getAddressComp('route');
			    for (const component of componentForm) {
			      // Location field is handled separately above as it has different logic.
			      if (component !== 'location') {
			        getFormInputElement(component).value = getAddressComp(component);
			        document.getElementById('add_gps_lat').value = place.geometry.location.lat();
			        document.getElementById('add_gps_lng').value = place.geometry.location.lng();
			      }
			    }
			  }
			
			  function renderAddress(place) {
				{$this->js_id}.clearMarkers();
				{$this->js_id}.addMarker(place.geometry.location);
				{$this->js_id}.panTo(place.geometry.location);
			  }
			}
			
			function loadJS() {
                // set loaded flag
                core.workspace.google_places_maps_loaded = 1;

                // load scripts
                // google maps api
                var script = document.createElement('script');
                script.type = 'text/javascript';
                script.src = 'https://maps.googleapis.com/maps/api/js?libraries=places&components=country:za&callback=initAutocompleteMap&solution_channel=GMP_QB_addressselection_v1_cABC&key=".\core::$app->get_instance()->get_google_maps_api_key()."';
                document.body.appendChild(script);

                // google marker clusterer
                var script = document.createElement('script');
                script.type = 'text/javascript';
                script.src = 'https://cdn.rawgit.com/googlemaps/js-marker-clusterer/gh-pages/src/markerclusterer.js';
                document.body.appendChild(script);
            }
			
			$(function(){
                (core.workspace.google_places_maps_loaded ? initAutocompleteMap() : loadJS())
            });
			
		"]);

		return $buffer->build();
	}
	//--------------------------------------------------------------------------------
	public function get_simple($options) {
		$options = array_merge([
			"address" => false,

			"prepend" => false,
			"wrapper_id" => false,
			"add_type_arr" => [],
			"required" => false,
			"label_col" => 3,
		], $options);

		// params
		$address = \core::dbt("address")->splat($options["address"]);

		$buffer = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();

		$data_id = \LiquidedgeApp\Octoapp\app\app\str\str::get_random_id("address");

		$buffer->xihidden("add_type[{$address->id}]", 1);
		$buffer->xihidden("add_nomination[{$address->id}]", 1);
		$buffer->div_([".ui-address-wrapper" => true, "@data-id" => $data_id]);
			$buffer->xform_input(function($html) use (&$address, $options) {
				$html->div_(".form-row .row");
					$html->div_(".col-sm-3 .mb-3 .mb-sm-0")->xitext("add_streetnr[{$address->id}]", $address->add_streetnr, "Street nr.", ["label_hidden" => true, "@placeholder" => "Street nr.", "limit" => "alpha_numeric", ".ui-w-full" => true, "required" => $options["required"]])->_div();
					$html->div_(".col")->xitext("add_street[{$address->id}]", $address->add_street, "Street", ["label_hidden" => true, "@placeholder" => "Street", ".ui-w-full" => true, "required" => $options["required"]])->_div();
				$html->_div();
			}, ["label" => "Street nr. / Street", "wrapper_id" => "add__streetnr_line[{$address->id}]", "label_col" => $options["label_col"]]);

			$fn_add_input = function($label, $id, $value, $options = [])use(&$buffer, &$address){
				$buffer->xform_input(function($html) use (&$address, $label, $id, $value, $options) {
					$options = array_merge([
					    "@placeholder" => $label,
						".ui-w-full" => true,
						"required" => $options["required"]
					], $options);

					$html->div_(".form-row .row");
						$html->div_(".col")->xitext("{$id}[{$address->id}]", $value, $label, $options + ["label_hidden" => true])->_div();
					$html->_div();
				}, ["label" => $label, "wrapper_id" => "add__{$id}[{$address->id}]", "required" => $options["required"]]);
			};

			$fn_add_input("Country", "country", ($address->country ? $address->country->con_name : false), $options);
			$fn_add_input("Province", "province", ($address->province ? $address->province->name : false), $options);
			$fn_add_input("Town", "town", ($address->town ? $address->town->name : false), $options);
			$fn_add_input("Suburb", "suburb", ($address->suburb ? $address->suburb->name : false), $options);
			$fn_add_input("Code", "add_code", $address->add_code, array_merge(["limit" => "numeric", "width" => "medium"], $options));

		$buffer->_div();

		return $buffer->build();

	}
	//--------------------------------------------------------------------------------
	public function get_autocomplete($options) {
		$options = array_merge([
			"address" => false,

			"prepend" => false,
			"wrapper_id" => false,
			"add_type_arr" => [],
		], $options);

		// params
		$address = \core::dbt("address")->splat($options["address"]);

		$fn_add_autocompleter = function($id, $options = []){

			$options = array_merge([
				"label" => false,
			    "value" => false,
				"autocomplete_value" => false,
			    "url" => false,

				"required" => true,
				"label_col" => 4,
				"/wrapper" => [],
			], $options);

			return \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->iautocompleter($id, $options);
		};

		$buffer = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();

		$data_id = \LiquidedgeApp\Octoapp\app\app\str\str::get_random_id("address");

		$buffer->div_([".ui-address-wrapper" => true, "@data-id" => $data_id]);
			$buffer->xform_input(function($html) use (&$address) {
				$html->div_(".form-row .row");
					$html->div_(".col-sm-3")->xitext("add_streetnr[{$address->id}]", $address->add_streetnr, false, ["@placeholder" => "Street nr.", "limit" => "alpha_numeric", ".ui-w-full" => true])->_div();
					$html->div_(".col-sm-9")->xitext("add_street[{$address->id}]", $address->add_street, false, ["@placeholder" => "Street", ".ui-w-full" => true])->_div();
				$html->_div();
			}, ["label" => "Street nr. / Street", "wrapper_id" => "add__streetnr_line[{$address->id}]"]);

			$buffer->xform_input(function($buffer) use (&$address, $fn_add_autocompleter) {
				$buffer->div_(".form-row .row");
					$buffer->div_([".col-sm-12" => true]);
						$buffer->add($fn_add_autocompleter("add_ref_country_{$this->js_id}", [
							"label" => "Country",
							"label_hidden" => true,
							"url" => "?c=address/xget_country_list&translate=true",
							"autocomplete_select_complete" => "
								add_ref_province_{$this->js_id}.reset();
								add_ref_town_{$this->js_id}.reset();
								add_ref_suburb_{$this->js_id}.reset();
								if(ui){
									add_ref_province_{$this->js_id}.enable();
									add_ref_province_{$this->js_id}.focus();
								}else{
									add_ref_province_{$this->js_id}.disable();
								}
							"
						]));
					$buffer->_div();
				$buffer->_div();
			}, ["label" => "Country", "wrapper_id" => "add__ref_country[{$address->id}]", "/wrapper" => [".mb-0" => true]]);

			$buffer->xform_input(function($buffer) use (&$address, $fn_add_autocompleter, $data_id) {
				$buffer->div_(".form-row .row");
					$buffer->div_([".col-sm-12" => true]);
						$buffer->add($fn_add_autocompleter("add_ref_province_{$this->js_id}", [
							"url" => "?c=address/xget_province_list&translate=true&con_id='+add_ref_country_{$this->js_id}.value()+'",
							"@disabled" => true,
							"@placeholder" => "Search Province",
							"autocomplete_select_complete" => "
								add_ref_town_{$this->js_id}.reset();
								add_ref_suburb_{$this->js_id}.reset();
								if(ui){
									add_ref_town_{$this->js_id}.enable();
									add_ref_town_{$this->js_id}.focus();
								}else{
									add_ref_town_{$this->js_id}.disable();
								}
							"
						]));
					$buffer->_div();
				$buffer->_div();
			}, ["label" => "Province", "wrapper_id" => "add__ref_province[{$address->id}]", "/wrapper" => [".mb-0" => true]]);

			$buffer->xform_input(function($buffer) use (&$address, $fn_add_autocompleter, $data_id) {
				$buffer->div_(".form-row .row");
					$buffer->div_([".col-sm-12" => true]);
						$buffer->add($fn_add_autocompleter("add_ref_town_{$this->js_id}", [
							"url" => "?c=address/xget_town_list&translate=true&con_id='+add_ref_country_{$this->js_id}.value()+'&prv_id='+add_ref_province_{$this->js_id}.value()+'",
							"@placeholder" => "Search Town",
							"@disabled" => true,
							"autocomplete_select_complete" => "
								add_ref_suburb_{$this->js_id}.reset();
								if(ui){
									add_ref_suburb_{$this->js_id}.enable();
									add_ref_suburb_{$this->js_id}.focus();
								}else{
									add_ref_suburb_{$this->js_id}.disable();
								}
							"
						]));
					$buffer->_div();
				$buffer->_div();
			}, ["label" => "Town", "wrapper_id" => "add__ref_province[{$address->id}]", "/wrapper" => [".mb-0" => true]]);

			$buffer->xform_input(function($buffer) use (&$address, $fn_add_autocompleter, $data_id) {
				$buffer->div_(".form-row .row");
					$buffer->div_([".col-sm-12" => true]);
						$buffer->add($fn_add_autocompleter("add_ref_suburb_{$this->js_id}", [
							"url" => "?c=address/xget_suburb_list&translate=true&'+$('.ui-address-wrapper[data-id={$data_id}] *').serialize()+'",
							"@placeholder" => "Search Suburb",
							"@disabled" => true,
						]));
					$buffer->_div();
				$buffer->_div();
			}, ["label" => "Suburb", "wrapper_id" => "add__ref_suburb[{$address->id}]", "/wrapper" => [".mb-0" => true]]);
		$buffer->_div();

		return $buffer->build();

	}
	//--------------------------------------------------------------------------------
	public function get_standard($options = []) {
		// public static function iaddress($address, $label = false, $options = []) {

		// options
  		$options = array_merge([
			"address" => false,

			"prepend" => false,
			"wrapper_id" => false,
			"add_type_arr" => [1 => "Physical",],
			"enable_unit" => false,
			"input_arr" => [
				"show_line1_line" => null,
				"show_line2_line" => null,
				"show_line3_line" => null,
				"show_unit_line" => null,
				"show_streetnr_line" => null,
				"show_attention_line" => null,
				"show_pobox_line" => null,
				"show_postnet_line" => null,
				"show_clusterbox_line" => null,
				"show_city_line" => null,
				"show_province_line" => null,
				"show_gps_line" => null,
				"@disabled" => true,
			],
  		], $options);

		$address = $options["address"];

		// params
		$address = \core::dbt("address")->splat($address);

		// context
		$context_arr = [
			1 => [ // residential
				"show_line1_line" => "show",
				"show_line2_line" => "show",
				"show_line3_line" => "hide",
				"show_unit_line" => "hide",
				"show_streetnr_line" => "hide",
				"show_attention_line" => "hide",
				"show_pobox_line" => "hide",
				"show_postnet_line" => "hide",
				"show_clusterbox_line" => "hide",
				"show_city_line" => "show",
				"show_province_line" => "show",
				"show_gps_line" => "hide",
				"@disabled" => true,
			],
			2 => [ // pobox
				"show_line1_line" => "hide",
				"show_line2_line" => "hide",
				"show_line3_line" => "hide",
				"show_unit_line" => "hide",
				"show_streetnr_line" => "hide",
				"show_attention_line" => "show",
				"show_pobox_line" => "show",
				"show_postnet_line" => "hide",
				"show_clusterbox_line" => "hide",
				"show_city_line" => "show",
				"show_province_line" => "show",
				"show_gps_line" => "hide",
				"@disabled" => true,
			],
			3 => [ // privatebag
				"show_line1_line" => "hide",
				"show_line2_line" => "hide",
				"show_line3_line" => "hide",
				"show_unit_line" => "hide",
				"show_streetnr_line" => "hide",
				"show_attention_line" => "show",
				"show_pobox_line" => "hide",
				"show_postnet_line" => "show",
				"show_clusterbox_line" => "hide",
				"show_city_line" => "show",
				"show_province_line" => "show",
				"show_gps_line" => "hide",
				"@disabled" => true,
			],
			4 => [ // copy
				"show_line1_line" => "hide",
				"show_line2_line" => "hide",
				"show_line3_line" => "hide",
				"show_unit_line" => "hide",
				"show_streetnr_line" => "hide",
				"show_attention_line" => "hide",
				"show_pobox_line" => "hide",
				"show_postnet_line" => "hide",
				"show_clusterbox_line" => "hide",
				"show_city_line" => "hide",
				"show_province_line" => "hide",
				"show_gps_line" => "hide",
				"@disabled" => true,
			],
			5 => [ // international
				"show_line1_line" => "show",
				"show_line2_line" => "show",
				"show_line3_line" => "show",
				"show_unit_line" => "hide",
				"show_streetnr_line" => "hide",
				"show_attention_line" => "hide",
				"show_pobox_line" => "hide",
				"show_postnet_line" => "hide",
				"show_clusterbox_line" => "hide",
				"show_city_line" => "show",
				"show_province_line" => "show",
				"show_gps_line" => "hide",
				"@disabled" => false,
			],
			6 => [ // cluster box
				"show_line1_line" => "hide",
				"show_line2_line" => "hide",
				"show_line3_line" => "hide",
				"show_unit_line" => "hide",
				"show_streetnr_line" => "hide",
				"show_attention_line" => "show",
				"show_pobox_line" => "hide",
				"show_postnet_line" => "hide",
				"show_clusterbox_line" => "show",
				"show_city_line" => "show",
				"show_province_line" => "show",
				"show_gps_line" => "hide",
				"@disabled" => true,
			],
		];

		foreach ($context_arr as $index => $data_arr){
			foreach ($data_arr as $field => $option){
				$data_arr[$field] = is_null($options["input_arr"][$field]) ? $option : $options["input_arr"][$field];
			}

			$context_arr[$index] = $data_arr;
		}

		$context = $context_arr[$address->add_type];

		// script
		$JS = "
			core.workspace.add_type__onchange = function(id, skip_default) {
				var add_type = $('#add_type\\\\[' + id + '\\\\]').val();
				switch (add_type) {
				";

					// build script changes based on type
					foreach ($context_arr as $context_index => $context_item) {
						$JS .= "
							case '{$context_index}' :
								$('#add__line1_line\\\\[' + id + '\\\\]').{$context_arr[$context_index]["show_line1_line"]}();
								$('#add__line2_line\\\\[' + id + '\\\\]').{$context_arr[$context_index]["show_line2_line"]}();
								$('#add__line3_line\\\\[' + id + '\\\\]').{$context_arr[$context_index]["show_line3_line"]}();
								$('#add__unit_line\\\\[' + id + '\\\\]').{$context_arr[$context_index]["show_unit_line"]}();
								$('#add__streetnr_line\\\\[' + id + '\\\\]').{$context_arr[$context_index]["show_streetnr_line"]}();
								$('#add__attention_line\\\\[' + id + '\\\\]').{$context_arr[$context_index]["show_attention_line"]}();
								$('#add__pobox_line\\\\[' + id + '\\\\]').{$context_arr[$context_index]["show_pobox_line"]}();
								$('#add__postnet_line\\\\[' + id + '\\\\]').{$context_arr[$context_index]["show_postnet_line"]}();
								$('#add__clusterbox_line\\\\[' + id + '\\\\]').{$context_arr[$context_index]["show_clusterbox_line"]}();
								$('#add__town_line\\\\[' + id + '\\\\]').{$context_arr[$context_index]["show_city_line"]}();
								$('#add__province_line\\\\[' + id + '\\\\]').{$context_arr[$context_index]["show_province_line"]}();
								$('#add__gps_line\\\\[' + id + '\\\\]').{$context_arr[$context_index]["show_gps_line"]}();
								break;
							";
					}

				$JS .= "
				}

				if (add_type == 5) {
					$('#add_code\\\\[' + id + '\\\\]').attr('disabled', false);
					$('#add_code\\\\[' + id + '\\\\]').attr('readonly', false);
				}
				else {
					$('#add_code\\\\[' + id + '\\\\]').attr('disabled', true);
					$('#add_code\\\\[' + id + '\\\\]').attr('readonly', true);
				}

				if (add_type != $('#add_type\\\\[' + id + '\\\\]').attr('defaultvalue')) {
					$('#add_line1\\\\[' + id + '\\\\]').val('');
					$('#add_line2\\\\[' + id + '\\\\]').val('');
					$('#add_line3\\\\[' + id + '\\\\]').val('');
					$('#add_unitnr\\\\[' + id + '\\\\]').val('');
					$('#add_floor\\\\[' + id + '\\\\]').val('');
					$('#add_building\\\\[' + id + '\\\\]').val('');
					$('#add_farm\\\\[' + id + '\\\\]').val('');
					$('#add_streetnr\\\\[' + id + '\\\\]').val('');
					$('#add_street\\\\[' + id + '\\\\]').val('');
					$('#add_development\\\\[' + id + '\\\\]').val('');
					$('#add_attention\\\\[' + id + '\\\\]').val('');
					$('#add_pobox\\\\[' + id + '\\\\]').val('');
					$('#add_postnet\\\\[' + id + '\\\\]').val('');
					$('#add_privatebag\\\\[' + id + '\\\\]').val('');
					$('#add_clusterbox\\\\[' + id + '\\\\]').val('');
					$('#add__town\\\\[' + id + '\\\\]').val('');
					$('#add__suburb\\\\[' + id + '\\\\]').val('');
					$('#add_code\\\\[' + id + '\\\\]').val('');
					$('#add__province\\\\[' + id + '\\\\]').val('');
					$('#add__country\\\\[' + id + '\\\\]').val('');
					$('#add_gps_lat\\\\[' + id + '\\\\]').val('');
					$('#add_gps_lng\\\\[' + id + '\\\\]').val('');

					$('#add_ref_suburb\\\\[' + id + '\\\\]').val('null');
					$('#add_ref_town\\\\[' + id + '\\\\]').val('null');
					$('#add_ref_province\\\\[' + id + '\\\\]').val('null');
					$('#add_ref_country\\\\[' + id + '\\\\]').val('null');
				}
				else if(!skip_default) {
					$('#add_unitnr\\\\[' + id + '\\\\]').val($('#add_unitnr\\\\[' + id + '\\\\]').prop('defaultValue'));
					$('#add_line1\\\\[' + id + '\\\\]').val($('#add_line1\\\\[' + id + '\\\\]').prop('defaultValue'));
					$('#add_line2\\\\[' + id + '\\\\]').val($('#add_line2\\\\[' + id + '\\\\]').prop('defaultValue'));
					$('#add_line3\\\\[' + id + '\\\\]').val($('#add_line3\\\\[' + id + '\\\\]').prop('defaultValue'));
					$('#add_unitnr\\\\[' + id + '\\\\]').val($('#add_unitnr\\\\[' + id + '\\\\]').prop('defaultValue'));
					$('#add_floor\\\\[' + id + '\\\\]').val($('#add_floor\\\\[' + id + '\\\\]').prop('defaultValue'));
					$('#add_building\\\\[' + id + '\\\\]').val($('#add_building\\\\[' + id + '\\\\]').prop('defaultValue'));
					$('#add_farm\\\\[' + id + '\\\\]').val($('#add_farm\\\\[' + id + '\\\\]').prop('defaultValue'));
					$('#add_streetnr\\\\[' + id + '\\\\]').val($('#add_streetnr\\\\[' + id + '\\\\]').prop('defaultValue'));
					$('#add_street\\\\[' + id + '\\\\]').val($('#add_street\\\\[' + id + '\\\\]').prop('defaultValue'));
					$('#add_development\\\\[' + id + '\\\\]').val($('#add_development\\\\[' + id + '\\\\]').prop('defaultValue'));
					$('#add_attention\\\\[' + id + '\\\\]').val($('#add_attention\\\\[' + id + '\\\\]').prop('defaultValue'));
					$('#add_pobox\\\\[' + id + '\\\\]').val($('#add_pobox\\\\[' + id + '\\\\]').prop('defaultValue'));
					$('#add_postnet\\\\[' + id + '\\\\]').val($('#add_postnet\\\\[' + id + '\\\\]').prop('defaultValue'));
					$('#add_privatebag\\\\[' + id + '\\\\]').val($('#add_privatebag\\\\[' + id + '\\\\]').prop('defaultValue'));
					$('#add_clusterbox\\\\[' + id + '\\\\]').val($('#add_clusterbox\\\\[' + id + '\\\\]').prop('defaultValue'));
					$('#add__town\\\\[' + id + '\\\\]').val($('#add__town\\\\[' + id + '\\\\]').prop('defaultValue'));
					$('#add__suburb\\\\[' + id + '\\\\]').val($('#add__suburb\\\\[' + id + '\\\\]').prop('defaultValue'));
					$('#add_code\\\\[' + id + '\\\\]').val($('#add_code\\\\[' + id + '\\\\]').prop('defaultValue'));
					$('#add__province\\\\[' + id + '\\\\]').val($('#add__province\\\\[' + id + '\\\\]').prop('defaultValue'));
					$('#add__country\\\\[' + id + '\\\\]').val($('#add__country\\\\[' + id + '\\\\]').prop('defaultValue'));
					$('#add_gps_lat\\\\[' + id + '\\\\]').val($('#add_gps_lat\\\\[' + id + '\\\\]').prop('defaultValue'));
					$('#add_gps_lng\\\\[' + id + '\\\\]').val($('#add_gps_lng\\\\[' + id + '\\\\]').prop('defaultValue'));

					$('#add_ref_suburb\\\\[' + id + '\\\\]').val($('#add_ref_suburb\\\\[' + id + '\\\\]').attr('defaultvalue'));
					$('#add_ref_town\\\\[' + id + '\\\\]').val($('#add_ref_town\\\\[' + id + '\\\\]').attr('defaultvalue'));
					$('#add_ref_province\\\\[' + id + '\\\\]').val($('#add_ref_province\\\\[' + id + '\\\\]').attr('defaultvalue'));
					$('#add_ref_country\\\\[' + id + '\\\\]').val($('#add_ref_country\\\\[' + id + '\\\\]').attr('defaultvalue'));
				}
			}

			core.workspace.add_type__onchange({$address->id});
			";
		\LiquidedgeApp\Octoapp\app\app\js\js::add_script($JS);

		// html
		$html = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();

		// prepend
		if ($options["prepend"]) {
			$options["prepend"]($html);
		}

		// wrapper: start
		if ($options["wrapper_id"]) {
			$html->div_("#{$options["wrapper_id"]}");
		}

		// type
		if(!$options["add_type_arr"]) $options["add_type_arr"] = $address->db->add_type;
		$html->div_([".d-none" => sizeof($options["add_type_arr"]) <= 1]);
			$html->xiselect("add_type[{$address->id}]", $options["add_type_arr"], $address->add_type, "Type", [
				"savedefault" => true,
				"exclude" => "0".($address->add_nomination == "1" ? ",4" : false),
				"!change" => "core.workspace.add_type__onchange({$address->id});",
			]);
		$html->_div();

		// lines
		$html->xitext("add_line1[{$address->id}]", $address->add_line1, "Line 1", ["@placeholder" => "Line 1", "wrapper_id" => "add__line1_line[{$address->id}]", ".ui-w-full" => true]);
		$html->xitext("add_line2[{$address->id}]", $address->add_line2, "Line 2", ["@placeholder" => "Line 2", "wrapper_id" => "add__line2_line[{$address->id}]", ".ui-w-full" => true]);
		$html->xitext("add_line3[{$address->id}]", $address->add_line3, "Line 3", ["@placeholder" => "Line 3", "wrapper_id" => "add__line3_line[{$address->id}]", ".ui-w-full" => true]);

		// unitnr, floor, building and farm
		if($options["enable_unit"]){
			$html->xform_input(function($html) use (&$address) {
				$html->div_(".form-row .row");
					$html->div_(".col-sm-2")->xitext("add_unitnr[{$address->id}]", $address->add_unitnr, false, ["@placeholder" => "Unit nr.", "limit" => "alpha_numeric", ".ui-w-full" => true])->_div();
					$html->div_(".col-sm-2")->xitext("add_floor[{$address->id}]", $address->add_floor, false, ["@placeholder" => "Floor", ".ui-w-full" => true])->_div();
					$html->div_(".col-sm-4")->xitext("add_building[{$address->id}]", $address->add_building, false, ["@placeholder" => "Building", ".ui-w-full" => true])->_div();
					$html->div_(".col-sm-4")->xitext("add_farm[{$address->id}]", $address->add_farm, false, ["@placeholder" => "Farm", ".ui-w-full" => true])->_div();
				$html->_div();
			}, ["label" => "Unit nr. / Floor / Building / Farm", "wrapper_id" => "add__unit_line[{$address->id}]"]);
		}

		// streetnr, street and development
		$html->xform_input(function($html) use (&$address) {
			$html->div_(".form-row .row");
				$html->div_(".col-sm-4")->xitext("add_streetnr[{$address->id}]", $address->add_streetnr, false, ["@placeholder" => "Street nr.", "limit" => "alpha_numeric", ".ui-w-full" => true])->_div();
   				$html->div_(".col-sm-8")->xitext("add_street[{$address->id}]", $address->add_street, false, ["@placeholder" => "Street", ".ui-w-full" => true])->_div();
			$html->_div();
		}, ["label" => "Street nr. / Street / Development", "wrapper_id" => "add__streetnr_line[{$address->id}]"]);


		// attention
		$html->xitext("add_attention[{$address->id}]", $address->add_attention, "Attention", ["@placeholder" => "Attention", "wrapper_id" => "add__attention_line[{$address->id}]", ".ui-w-full" => true]);

		// pobox
		$html->xitext("add_pobox[{$address->id}]", $address->add_pobox, "P.O. Box", ["@placeholder" => "P.O. Box", "wrapper_id" => "add__pobox_line[{$address->id}]", ".ui-w-full" => true]);

		// postnet and privatebag
		$html->xform_input(function($html) use (&$address) {
			$html->div_(".form-row .row");
				$html->div_(".col-sm-6")->xitext("add_postnet[{$address->id}]", $address->add_postnet, false, ["@placeholder" => "Postnet nr.", ".ui-w-full" => true])->_div();
   				$html->div_(".col-sm-6")->xitext("add_privatebag[{$address->id}]", $address->add_privatebag, false, ["@placeholder" => "Private Bag", ".ui-w-full" => true])->_div();
			$html->_div();
		}, ["label" => "Postnet nr. / Private Bag", "wrapper_id" => "add__postnet_line[{$address->id}]"]);

		// cluster box
		$html->xitext("add_clusterbox[{$address->id}]", $address->add_clusterbox, "Cluster Box", ["@placeholder" => "Cluster Box", "wrapper_id" => "add__clusterbox_line[{$address->id}]"]);

		// city, suburb and code
		$html->xform_input(function($html) use (&$address, &$context) {

			$town = \core::dbt("town")->get_fromdb($address->add_ref_town);
			$suburb = \core::dbt("suburb")->get_fromdb($address->add_ref_suburb);

			$html->div_(".form-row .row");
				$html->div_(".col-sm-5")
					->xitext("add__town[{$address->id}]", ($town ? $town->name : false), false, ["@placeholder" => "City", "@disabled" => true, ".ui-w-full" => true])
					->xihidden("add_ref_town[{$address->id}]", $address->add_ref_town)
					->_div();

				$html->div_(".col-sm-5")
					->xitext("add__suburb[{$address->id}]", ($suburb ? $suburb->name : false), false, ["@placeholder" => "Suburb", "@disabled" => true, ".ui-w-full" => true])
					->xihidden("add_ref_suburb[{$address->id}]", $address->add_ref_suburb)
					->_div();

				$html->div_(".col-sm-2")->xitext("add_code[{$address->id}]", $address->add_code, false, ["@placeholder" => "Code", "@disabled" => $context["@disabled"], ".ui-w-full" => true])->_div();
			$html->_div();
		}, ["label" => "City / Suburb / Code", "wrapper_id" => "add__town_line[{$address->id}]"]);

		// province and country
		$html->xform_input(function($html) use (&$address) {

			$province = \core::dbt("province")->get_fromdb($address->add_ref_province);
			$country = \core::dbt("country")->get_fromdb($address->add_ref_country);

			$html->div_(".form-row .row");
				$html->div_(".col-sm-5")
					->xitext("add__province[{$address->id}]", ($province ? $province->name : false), false, ["@placeholder" => "Province", "@disabled" => true, ".ui-w-full" => true])
					->xihidden("add_ref_province[{$address->id}]", $address->add_ref_province)
					->_div();

				$html->div_(".col-sm-5")
					->xitext("add__country[{$address->id}]", ($country ? $country->name : false), false, [
						"@placeholder" => "Country",
						"@disabled" => true,
						".ui-w-full" => true
					])
					->xihidden("add_ref_country[{$address->id}]", $address->add_ref_country)
					->_div();

				$html->div_(".col-sm-2")
					->xbutton(false, \core::$panel.".popup('?c=address/vfind_custom/{$address->id}&add_type=' + $('#add_type\\\\[{$address->id}\\\\]').val());", ["icon" => "pushpin", ".w-100" => true])
					->_div();

			$html->_div();
		}, ["label" => "Province / Country", "wrapper_id" => "add__province_line[{$address->id}]"]);

		// gps co-ordinates
//		$html->xform_input(function($html) use (&$address) {
//			$html->div_(".form-row");
//				$html->div_(".col-sm-5")->xitext("add_gps_lat[{$address->id}]", $address->add_gps_lat, false, ["@placeholder" => "GPS Latitude", ".ui-w-full" => true])->_div();
//				$html->div_(".col-sm-5")->xitext("add_gps_lng[{$address->id}]", $address->add_gps_lng, false, ["@placeholder" => "GPS Longitude", ".ui-w-full" => true])->_div();
//				$html->div_(".col-sm-2")->xbutton("find", \core::$app->get_request()->get_panel().".popup('?c=address/vfind_map/{$address->id}');", ["icon" => "globe"])->_div();
//			$html->_div();
//		}, ["label" => "GPS Latitude / Longitude", "wrapper_id" => "add__gps_line[{$address->id}]"]);

		// wrapper: end
		if ($options["wrapper_id"]) {
			$html->_div();
		}

		// done
		return $html->get_clean();
	}

	//--------------------------------------------------------------------------------
	public function __get_standard($options = []) {
		// public static function iaddress($address, $label = false, $options = []) {

		// options
  		$options = array_merge([
			"address" => false,

			"prepend" => false,
			"wrapper_id" => false,
			"add_type_arr" => [],
			"enable_unit" => false,
			"input_arr" => [
				"show_line1_line" => null,
				"show_line2_line" => null,
				"show_line3_line" => null,
				"show_unit_line" => null,
				"show_streetnr_line" => null,
				"show_attention_line" => null,
				"show_pobox_line" => null,
				"show_postnet_line" => null,
				"show_clusterbox_line" => null,
				"show_city_line" => null,
				"show_province_line" => null,
				"show_gps_line" => null,
				"@disabled" => true,
			],
  		], $options);

		$address = $options["address"];

		// params
		$address = \core::dbt("address")->splat($address);

		// context
		$context_arr = [
			1 => [ // residential
				"show_line1_line" => "hide",
				"show_line2_line" => "hide",
				"show_line3_line" => "hide",
				"show_unit_line" => "show",
				"show_streetnr_line" => "show",
				"show_attention_line" => "hide",
				"show_pobox_line" => "hide",
				"show_postnet_line" => "hide",
				"show_clusterbox_line" => "hide",
				"show_city_line" => "show",
				"show_province_line" => "show",
				"show_gps_line" => "hide",
				"@disabled" => true,
			],
			2 => [ // pobox
				"show_line1_line" => "hide",
				"show_line2_line" => "hide",
				"show_line3_line" => "hide",
				"show_unit_line" => "hide",
				"show_streetnr_line" => "hide",
				"show_attention_line" => "show",
				"show_pobox_line" => "show",
				"show_postnet_line" => "hide",
				"show_clusterbox_line" => "hide",
				"show_city_line" => "show",
				"show_province_line" => "show",
				"show_gps_line" => "hide",
				"@disabled" => true,
			],
			3 => [ // privatebag
				"show_line1_line" => "hide",
				"show_line2_line" => "hide",
				"show_line3_line" => "hide",
				"show_unit_line" => "hide",
				"show_streetnr_line" => "hide",
				"show_attention_line" => "show",
				"show_pobox_line" => "hide",
				"show_postnet_line" => "show",
				"show_clusterbox_line" => "hide",
				"show_city_line" => "show",
				"show_province_line" => "show",
				"show_gps_line" => "hide",
				"@disabled" => true,
			],
			4 => [ // copy
				"show_line1_line" => "hide",
				"show_line2_line" => "hide",
				"show_line3_line" => "hide",
				"show_unit_line" => "hide",
				"show_streetnr_line" => "hide",
				"show_attention_line" => "hide",
				"show_pobox_line" => "hide",
				"show_postnet_line" => "hide",
				"show_clusterbox_line" => "hide",
				"show_city_line" => "hide",
				"show_province_line" => "hide",
				"show_gps_line" => "hide",
				"@disabled" => true,
			],
			5 => [ // international
				"show_line1_line" => "show",
				"show_line2_line" => "show",
				"show_line3_line" => "show",
				"show_unit_line" => "hide",
				"show_streetnr_line" => "hide",
				"show_attention_line" => "hide",
				"show_pobox_line" => "hide",
				"show_postnet_line" => "hide",
				"show_clusterbox_line" => "hide",
				"show_city_line" => "show",
				"show_province_line" => "show",
				"show_gps_line" => "hide",
				"@disabled" => false,
			],
			6 => [ // cluster box
				"show_line1_line" => "hide",
				"show_line2_line" => "hide",
				"show_line3_line" => "hide",
				"show_unit_line" => "hide",
				"show_streetnr_line" => "hide",
				"show_attention_line" => "show",
				"show_pobox_line" => "hide",
				"show_postnet_line" => "hide",
				"show_clusterbox_line" => "show",
				"show_city_line" => "show",
				"show_province_line" => "show",
				"show_gps_line" => "hide",
				"@disabled" => true,
			],
		];

		foreach ($context_arr as $index => $data_arr){
			foreach ($data_arr as $field => $option){
				$data_arr[$field] = is_null($options["input_arr"][$field]) ? $option : $options["input_arr"][$field];
			}

			$context_arr[$index] = $data_arr;
		}

		$context = $context_arr[$address->add_type];

		// script
		$JS = "
			core.workspace.add_type__onchange = function(id, skip_default) {
				var add_type = $('#add_type\\\\[' + id + '\\\\]').val();
				switch (add_type) {
				";

					// build script changes based on type
					foreach ($context_arr as $context_index => $context_item) {
						$JS .= "
							case '{$context_index}' :
								$('#add__line1_line\\\\[' + id + '\\\\]').{$context_arr[$context_index]["show_line1_line"]}();
								$('#add__line2_line\\\\[' + id + '\\\\]').{$context_arr[$context_index]["show_line2_line"]}();
								$('#add__line3_line\\\\[' + id + '\\\\]').{$context_arr[$context_index]["show_line3_line"]}();
								$('#add__unit_line\\\\[' + id + '\\\\]').{$context_arr[$context_index]["show_unit_line"]}();
								$('#add__streetnr_line\\\\[' + id + '\\\\]').{$context_arr[$context_index]["show_streetnr_line"]}();
								$('#add__attention_line\\\\[' + id + '\\\\]').{$context_arr[$context_index]["show_attention_line"]}();
								$('#add__pobox_line\\\\[' + id + '\\\\]').{$context_arr[$context_index]["show_pobox_line"]}();
								$('#add__postnet_line\\\\[' + id + '\\\\]').{$context_arr[$context_index]["show_postnet_line"]}();
								$('#add__clusterbox_line\\\\[' + id + '\\\\]').{$context_arr[$context_index]["show_clusterbox_line"]}();
								$('#add__town_line\\\\[' + id + '\\\\]').{$context_arr[$context_index]["show_city_line"]}();
								$('#add__province_line\\\\[' + id + '\\\\]').{$context_arr[$context_index]["show_province_line"]}();
								$('#add__gps_line\\\\[' + id + '\\\\]').{$context_arr[$context_index]["show_gps_line"]}();
								break;
							";
					}

				$JS .= "
				}

				if (add_type == 5) {
					$('#add_code\\\\[' + id + '\\\\]').attr('disabled', false);
					$('#add_code\\\\[' + id + '\\\\]').attr('readonly', false);
				}
				else {
					$('#add_code\\\\[' + id + '\\\\]').attr('disabled', true);
					$('#add_code\\\\[' + id + '\\\\]').attr('readonly', true);
				}

				if (add_type != $('#add_type\\\\[' + id + '\\\\]').attr('defaultvalue')) {
					$('#add_line1\\\\[' + id + '\\\\]').val('');
					$('#add_line2\\\\[' + id + '\\\\]').val('');
					$('#add_line3\\\\[' + id + '\\\\]').val('');
					$('#add_unitnr\\\\[' + id + '\\\\]').val('');
					$('#add_floor\\\\[' + id + '\\\\]').val('');
					$('#add_building\\\\[' + id + '\\\\]').val('');
					$('#add_farm\\\\[' + id + '\\\\]').val('');
					$('#add_streetnr\\\\[' + id + '\\\\]').val('');
					$('#add_street\\\\[' + id + '\\\\]').val('');
					$('#add_development\\\\[' + id + '\\\\]').val('');
					$('#add_attention\\\\[' + id + '\\\\]').val('');
					$('#add_pobox\\\\[' + id + '\\\\]').val('');
					$('#add_postnet\\\\[' + id + '\\\\]').val('');
					$('#add_privatebag\\\\[' + id + '\\\\]').val('');
					$('#add_clusterbox\\\\[' + id + '\\\\]').val('');
					$('#add__town\\\\[' + id + '\\\\]').val('');
					$('#add__suburb\\\\[' + id + '\\\\]').val('');
					$('#add_code\\\\[' + id + '\\\\]').val('');
					$('#add__province\\\\[' + id + '\\\\]').val('');
					$('#add__country\\\\[' + id + '\\\\]').val('');
					$('#add_gps_lat\\\\[' + id + '\\\\]').val('');
					$('#add_gps_lng\\\\[' + id + '\\\\]').val('');

					$('#add_ref_suburb\\\\[' + id + '\\\\]').val('null');
					$('#add_ref_town\\\\[' + id + '\\\\]').val('null');
					$('#add_ref_province\\\\[' + id + '\\\\]').val('null');
					$('#add_ref_country\\\\[' + id + '\\\\]').val('null');
				}
				else if(!skip_default) {
					$('#add_unitnr\\\\[' + id + '\\\\]').val($('#add_unitnr\\\\[' + id + '\\\\]').prop('defaultValue'));
					$('#add_line1\\\\[' + id + '\\\\]').val($('#add_line1\\\\[' + id + '\\\\]').prop('defaultValue'));
					$('#add_line2\\\\[' + id + '\\\\]').val($('#add_line2\\\\[' + id + '\\\\]').prop('defaultValue'));
					$('#add_line3\\\\[' + id + '\\\\]').val($('#add_line3\\\\[' + id + '\\\\]').prop('defaultValue'));
					$('#add_unitnr\\\\[' + id + '\\\\]').val($('#add_unitnr\\\\[' + id + '\\\\]').prop('defaultValue'));
					$('#add_floor\\\\[' + id + '\\\\]').val($('#add_floor\\\\[' + id + '\\\\]').prop('defaultValue'));
					$('#add_building\\\\[' + id + '\\\\]').val($('#add_building\\\\[' + id + '\\\\]').prop('defaultValue'));
					$('#add_farm\\\\[' + id + '\\\\]').val($('#add_farm\\\\[' + id + '\\\\]').prop('defaultValue'));
					$('#add_streetnr\\\\[' + id + '\\\\]').val($('#add_streetnr\\\\[' + id + '\\\\]').prop('defaultValue'));
					$('#add_street\\\\[' + id + '\\\\]').val($('#add_street\\\\[' + id + '\\\\]').prop('defaultValue'));
					$('#add_development\\\\[' + id + '\\\\]').val($('#add_development\\\\[' + id + '\\\\]').prop('defaultValue'));
					$('#add_attention\\\\[' + id + '\\\\]').val($('#add_attention\\\\[' + id + '\\\\]').prop('defaultValue'));
					$('#add_pobox\\\\[' + id + '\\\\]').val($('#add_pobox\\\\[' + id + '\\\\]').prop('defaultValue'));
					$('#add_postnet\\\\[' + id + '\\\\]').val($('#add_postnet\\\\[' + id + '\\\\]').prop('defaultValue'));
					$('#add_privatebag\\\\[' + id + '\\\\]').val($('#add_privatebag\\\\[' + id + '\\\\]').prop('defaultValue'));
					$('#add_clusterbox\\\\[' + id + '\\\\]').val($('#add_clusterbox\\\\[' + id + '\\\\]').prop('defaultValue'));
					$('#add__town\\\\[' + id + '\\\\]').val($('#add__town\\\\[' + id + '\\\\]').prop('defaultValue'));
					$('#add__suburb\\\\[' + id + '\\\\]').val($('#add__suburb\\\\[' + id + '\\\\]').prop('defaultValue'));
					$('#add_code\\\\[' + id + '\\\\]').val($('#add_code\\\\[' + id + '\\\\]').prop('defaultValue'));
					$('#add__province\\\\[' + id + '\\\\]').val($('#add__province\\\\[' + id + '\\\\]').prop('defaultValue'));
					$('#add__country\\\\[' + id + '\\\\]').val($('#add__country\\\\[' + id + '\\\\]').prop('defaultValue'));
					$('#add_gps_lat\\\\[' + id + '\\\\]').val($('#add_gps_lat\\\\[' + id + '\\\\]').prop('defaultValue'));
					$('#add_gps_lng\\\\[' + id + '\\\\]').val($('#add_gps_lng\\\\[' + id + '\\\\]').prop('defaultValue'));

					$('#add_ref_suburb\\\\[' + id + '\\\\]').val($('#add_ref_suburb\\\\[' + id + '\\\\]').attr('defaultvalue'));
					$('#add_ref_town\\\\[' + id + '\\\\]').val($('#add_ref_town\\\\[' + id + '\\\\]').attr('defaultvalue'));
					$('#add_ref_province\\\\[' + id + '\\\\]').val($('#add_ref_province\\\\[' + id + '\\\\]').attr('defaultvalue'));
					$('#add_ref_country\\\\[' + id + '\\\\]').val($('#add_ref_country\\\\[' + id + '\\\\]').attr('defaultvalue'));
				}
			}

			core.workspace.add_type__onchange({$address->id});
			";
		\LiquidedgeApp\Octoapp\app\app\js\js::add_script($JS);

		// html
		$html = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();

		// prepend
		if ($options["prepend"]) {
			$options["prepend"]($html);
		}

		// wrapper: start
		if ($options["wrapper_id"]) {
			$html->div_("#{$options["wrapper_id"]}");
		}

		// type
		if(!$options["add_type_arr"]) $options["add_type_arr"] = $address->db->add_type;
		$html->div_([".d-none" => sizeof($options["add_type_arr"]) <= 1]);
			$html->xiselect("add_type[{$address->id}]", $options["add_type_arr"], $address->add_type, "Type", [
				"savedefault" => true,
				"exclude" => "0".($address->add_nomination == "1" ? ",4" : false),
				"!change" => "core.workspace.add_type__onchange({$address->id});",
			]);
		$html->_div();

		// lines
		$html->xitext("add_line1[{$address->id}]", $address->add_line1, "Line 1", ["@placeholder" => "Line 1", "wrapper_id" => "add__line1_line[{$address->id}]", ".ui-w-full" => true]);
		$html->xitext("add_line2[{$address->id}]", $address->add_line2, "Line 2", ["@placeholder" => "Line 2", "wrapper_id" => "add__line2_line[{$address->id}]", ".ui-w-full" => true]);
		$html->xitext("add_line3[{$address->id}]", $address->add_line3, "Line 3", ["@placeholder" => "Line 3", "wrapper_id" => "add__line3_line[{$address->id}]", ".ui-w-full" => true]);

		// unitnr, floor, building and farm
		if($options["enable_unit"]){
			$html->xform_input(function($html) use (&$address) {
				$html->div_(".form-row .row");
					$html->div_(".col-sm-2")->xitext("add_unitnr[{$address->id}]", $address->add_unitnr, false, ["@placeholder" => "Unit nr.", "limit" => "alpha_numeric", ".ui-w-full" => true])->_div();
					$html->div_(".col-sm-2")->xitext("add_floor[{$address->id}]", $address->add_floor, false, ["@placeholder" => "Floor", ".ui-w-full" => true])->_div();
					$html->div_(".col-sm-4")->xitext("add_building[{$address->id}]", $address->add_building, false, ["@placeholder" => "Building", ".ui-w-full" => true])->_div();
					$html->div_(".col-sm-4")->xitext("add_farm[{$address->id}]", $address->add_farm, false, ["@placeholder" => "Farm", ".ui-w-full" => true])->_div();
				$html->_div();
			}, ["label" => "Unit nr. / Floor / Building / Farm", "wrapper_id" => "add__unit_line[{$address->id}]"]);
		}

		// streetnr, street and development
		$html->xform_input(function($html) use (&$address) {
			$html->div_(".form-row .row");
				$html->div_(".col-sm-4")->xitext("add_streetnr[{$address->id}]", $address->add_streetnr, false, ["@placeholder" => "Street nr.", "limit" => "alpha_numeric", ".ui-w-full" => true])->_div();
   				$html->div_(".col-sm-8")->xitext("add_street[{$address->id}]", $address->add_street, false, ["@placeholder" => "Street", ".ui-w-full" => true])->_div();
			$html->_div();
		}, ["label" => "Street nr. / Street / Development", "wrapper_id" => "add__streetnr_line[{$address->id}]"]);


		// attention
		$html->xitext("add_attention[{$address->id}]", $address->add_attention, "Attention", ["@placeholder" => "Attention", "wrapper_id" => "add__attention_line[{$address->id}]", ".ui-w-full" => true]);

		// pobox
		$html->xitext("add_pobox[{$address->id}]", $address->add_pobox, "P.O. Box", ["@placeholder" => "P.O. Box", "wrapper_id" => "add__pobox_line[{$address->id}]", ".ui-w-full" => true]);

		// postnet and privatebag
		$html->xform_input(function($html) use (&$address) {
			$html->div_(".form-row .row");
				$html->div_(".col-sm-6")->xitext("add_postnet[{$address->id}]", $address->add_postnet, false, ["@placeholder" => "Postnet nr.", ".ui-w-full" => true])->_div();
   				$html->div_(".col-sm-6")->xitext("add_privatebag[{$address->id}]", $address->add_privatebag, false, ["@placeholder" => "Private Bag", ".ui-w-full" => true])->_div();
			$html->_div();
		}, ["label" => "Postnet nr. / Private Bag", "wrapper_id" => "add__postnet_line[{$address->id}]"]);

		// cluster box
		$html->xitext("add_clusterbox[{$address->id}]", $address->add_clusterbox, "Cluster Box", ["@placeholder" => "Cluster Box", "wrapper_id" => "add__clusterbox_line[{$address->id}]"]);

		// city, suburb and code
		$html->xform_input(function($html) use (&$address, &$context) {

			$town = \core::dbt("town")->get_fromdb($address->add_ref_town);
			$suburb = \core::dbt("suburb")->get_fromdb($address->add_ref_suburb);

			$html->div_(".form-row .row");
				$html->div_(".col-sm-5")
					->xitext("add__town[{$address->id}]", ($town ? $town->name : false), false, ["@placeholder" => "City", "@disabled" => true, ".ui-w-full" => true])
					->xihidden("add_ref_town[{$address->id}]", $address->add_ref_town)
					->_div();

				$html->div_(".col-sm-5")
					->xitext("add__suburb[{$address->id}]", ($suburb ? $suburb->name : false), false, ["@placeholder" => "Suburb", "@disabled" => true, ".ui-w-full" => true])
					->xihidden("add_ref_suburb[{$address->id}]", $address->add_ref_suburb)
					->_div();

				$html->div_(".col-sm-2")->xitext("add_code[{$address->id}]", $address->add_code, false, ["@placeholder" => "Code", "@disabled" => $context["@disabled"], ".ui-w-full" => true])->_div();
			$html->_div();
		}, ["label" => "City / Suburb / Code", "wrapper_id" => "add__town_line[{$address->id}]"]);

		// province and country
		$html->xform_input(function($html) use (&$address) {

			$province = \core::dbt("province")->get_fromdb($address->add_ref_province);
			$country = \core::dbt("country")->get_fromdb($address->add_ref_country);

			$html->div_(".form-row .row");
				$html->div_(".col-sm-5")
					->xitext("add__province[{$address->id}]", ($province ? $province->name : false), false, ["@placeholder" => "Province", "@disabled" => true, ".ui-w-full" => true])
					->xihidden("add_ref_province[{$address->id}]", $address->add_ref_province)
					->_div();

				$html->div_(".col-sm-5")
					->xitext("add__country[{$address->id}]", ($country ? $country->name : false), false, [
						"@placeholder" => "Country",
						"@disabled" => true,
						".ui-w-full" => true
					])
					->xihidden("add_ref_country[{$address->id}]", $address->add_ref_country)
					->_div();

				$html->div_(".col-sm-2")
					->xbutton(false, \core::$panel.".popup('?c=address/vfind_custom/{$address->id}&add_type=' + $('#add_type\\\\[{$address->id}\\\\]').val());", ["icon" => "pushpin", ".w-100" => true])
					->_div();

			$html->_div();
		}, ["label" => "Province / Country", "wrapper_id" => "add__province_line[{$address->id}]"]);

		// gps co-ordinates
//		$html->xform_input(function($html) use (&$address) {
//			$html->div_(".form-row");
//				$html->div_(".col-sm-5")->xitext("add_gps_lat[{$address->id}]", $address->add_gps_lat, false, ["@placeholder" => "GPS Latitude", ".ui-w-full" => true])->_div();
//				$html->div_(".col-sm-5")->xitext("add_gps_lng[{$address->id}]", $address->add_gps_lng, false, ["@placeholder" => "GPS Longitude", ".ui-w-full" => true])->_div();
//				$html->div_(".col-sm-2")->xbutton("find", \core::$app->get_request()->get_panel().".popup('?c=address/vfind_map/{$address->id}');", ["icon" => "globe"])->_div();
//			$html->_div();
//		}, ["label" => "GPS Latitude / Longitude", "wrapper_id" => "add__gps_line[{$address->id}]"]);

		// wrapper: end
		if ($options["wrapper_id"]) {
			$html->_div();
		}

		// done
		return $html->get_clean();
	}
	//--------------------------------------------------------------------------------
}