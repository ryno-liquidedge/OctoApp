<?php

namespace LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap;

/**
 * @package app\ui\set\bootstrap
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class map extends \com\ui\intf\map {
	//--------------------------------------------------------------------------------
	// fields
	//--------------------------------------------------------------------------------
	protected $id = false;
	protected $html = false;
	protected $location_arr = false;
	protected $cluster = false;
	protected $labels = false;
	protected $zoom = false;
	protected $center = false;

	protected $base_stream_url = false;
	protected $icon_arr = [];
	protected $options = [];

	protected $post_js_arr = [];
	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	protected function __construct($options = []) {
		// options
		$options = array_merge([
			"id" => false,
			"cluster" => false,
			"labels" => "alpha",
			"zoom" => 5,
		], $options);

		// init
		$this->id = $options["id"];
		$this->name = "Map";
		$this->cluster = $options["cluster"];
		$this->labels = $options["labels"];
		$this->zoom = $options["zoom"];
		$this->set_center(-29.445236, 24.911619);
		$this->options = $options;
	}

	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
    /**
     * @param bool $zoom
     */
    public function set_zoom($zoom) {
        $this->zoom = $zoom;
    }

	//--------------------------------------------------------------------------------
	/**
	 * @param $lat
	 * @param $lng
	 */
    public function set_center($lat, $lng) {
        $this->center = ["lat" => floatval($lat), "lng" => floatval($lng)];
    }

	//--------------------------------------------------------------------------------
    /**
     * @param bool $base_stream_url
     */
    public function set_base_stream_url($base_stream_url) {
        $this->base_stream_url = $base_stream_url;
    }

	//--------------------------------------------------------------------------------
    /**
     * @param bool $base_stream_url
     */
    public function add_custom_icon($key, $stream) {
        $this->icon_arr[$key] = [
            "icon" => $stream,
        ];
    }

	//--------------------------------------------------------------------------------
	public function place_marker_click($options = []) {

    	$options = array_merge([
			"!click" => "function(position, map){}"
    	], $options);

    	$js_options = \LiquidedgeApp\Octoapp\app\app\js\js::create_options($options);

		$this->post_js_arr[] = "
			google.maps.event.addListener(map, 'click', function(e) {
				
				let position = {
					lat:e.latLng.lat(), 
					lng:e.latLng.lng()
				};
				
				$this->id.clearMarkers();
				$this->id.addMarker(e.latLng, $js_options);
				$this->id.panTo(position);
				
				let fn = eval({$options['!click']});
				fn.apply(this, [position, $this->id]);
			});
		";

	}
	//--------------------------------------------------------------------------------
	public function on_center_change($fn, $options = []) {

    	$options["fn"] = "!$fn";

    	$js_options = \LiquidedgeApp\Octoapp\app\app\js\js::create_options($options);

		$this->post_js_arr[] = "
			google.maps.event.addListener(map, 'dragend', () => {
				{$options["fn"]}.apply(this, [map, $js_options]);
			});
		";

	}
	//--------------------------------------------------------------------------------
	public function on_loaded($fn, $options = []) {

    	$options["fn"] = "!$fn";

    	$js_options = \LiquidedgeApp\Octoapp\app\app\js\js::create_options($options);

		$this->post_js_arr[] = "
			google.maps.event.addListenerOnce(map, 'idle', function(){
				{$options["fn"]}.apply(this, [map, $js_options]);
			});
		";

	}
	//--------------------------------------------------------------------------------
	/**
	 * Builds and returns the HTML for the map.
	 * @param array $options - <p>Any @attriute, #style or !event options available on an html tag used on the map wrapper.</p>
	 * @return bool|string
	 */
	public function build($options = []) {
		// options
		$options = array_merge([
			"@id" => $this->id,
			"#width" => "100%",
			"#height" => "500px",
			"#border" => "1px solid #E5E5E5",
			"enable_directions" => false,
		], $options, $this->options);

		// id
		if (isset($options["id"]) && !$options["@id"]) $options["@id"] = $options["id"]; // cater for either

		// required
		if (!$options["@id"]) {
			return \com\error::create("ID required for map element");
		}

		// init
        $this->id = $options["@id"];

		// html buffer
		$this->html = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();

		// locations
		$location_arr = $this->location_arr;
		$this->location_arr = $location_arr ? json_encode(array_values($location_arr), JSON_NUMERIC_CHECK) : "{}";

		// html
		$this->html->div(false, $options);

		if($options["enable_directions"]){
			$marker = reset($location_arr);
			if($marker){
				$query = http_build_query([
					"api" => 1,
					"destination" => "{$marker['lat']},{$marker['lng']}"
				]);
				$this->html->div_([".d-flex mt-2" => true]);
					$this->html->xbutton("Get Directions", "window.open('https://www.google.com/maps/dir/?$query', '_blank')", [".w-100 btn-secondary" => true]);
				$this->html->_div();
			}
		}

		// js
		$this->build_js();

		// run
        \com\js::add_script("
            $(function(){
                (core.workspace.google_maps_loaded ? initMap() : loadJS())
            })
        ");

		// done
		return $this->html->get_clean();
	}
	//--------------------------------------------------------------------------------
	public function build_js() {

	    $this->html->script(["*" => "
	    	
	    	var $this->id;
	    	
	        function initMap() {
                // init
                var element_id = '{$this->id}';
                var cluster = '$this->cluster';
                var labels = '$this->labels';
                var location_arr = $this->location_arr;
                var zoom = $this->zoom;
                var center = ".json_encode($this->center)."; // center on South Africa if no coords supplied
                const icons = ".json_encode($this->icon_arr).";
                
                //--------------------------------------------------------
                // create map
                var map = $this->id = new google.maps.Map(document.getElementById(element_id), {
                    zoom: zoom,
                    center: center,
                    styles: {$this->build_styles()}
                });
	
                //--------------------------------------------------------
				//prototype methods
                google.maps.Map.prototype.markers = new Array();
                google.maps.Map.prototype.currentInfoWindow = null;
                //--------------------------------------------------------
				google.maps.Map.prototype.addMarker = function(position, options) {
					// options
					var options = $.extend({
						key: Object.values(position).join('-'),
						title: null,
						label: null,
						position: position,
						map: map,
					}, (options == undefined ? {} : options));
					
					var marker = new google.maps.Marker(options);  
					if (options.complete) options.complete.apply(this, [marker, options]);
					this.markers.push(marker);
					
					return marker;
					
				};
                //--------------------------------------------------------
				google.maps.Map.prototype.getMarkers = function() {
					return this.markers
				};
                //--------------------------------------------------------
				google.maps.Map.prototype.getOrCreateMarker = function(lat, lng, options) {
				
					var position = {lat:lat, lng:lng};
				
					// options
					var options = $.extend({
						key: Object.values(position).join('-'),
						title: null,
						label: null,
						icon: null,
						position: position,
						map: map,
						auto_pan: true,
						url: false,
						load_window: true,
						zoom: false,
					}, (options == undefined ? {} : options));
				
					let key = map.buildKey(lat, lng);
					let marker = map.getMarkerFromKey(key);
					if(!marker) marker = map.addMarker({lat:lat, lng:lng}, options);
					else{
						if(options.icon) map.setMarkerIcon(key, options.icon);
					}
					
					if(!options.icon) options.icon = 'standard';
					if(options.icon) map.setMarkerIcon(key, options.icon);
					if(options.zoom) {$this->id}.setZoom(options.zoom);
					if(options.auto_pan) {$this->id}.panTo(options.position);
					
					if(options.url && options.load_window){
						{$this->id}.closeMarkerWindowEvent(marker);
						{$this->id}.addMarkerWindowEvent(marker, options.url);
					}
					
					return marker;
				};
                //--------------------------------------------------------
				google.maps.Map.prototype.panToPosition = function(position, zoomHeight) {
				
					if(!zoomHeight) zoomHeight = zoom;
				
					map.setZoom(parseInt(zoomHeight));
					map.panTo(position);
				};
                //--------------------------------------------------------
				google.maps.Map.prototype.buildKey = function(lat, lng) {
					return lat+'-'+lng;
				};
                //--------------------------------------------------------
				google.maps.Map.prototype.getPositionObj = function(lat, lng) {
					return {lat:lat, lng:lng};
				};
                //--------------------------------------------------------
				google.maps.Map.prototype.clearMarkers = function() {
					for(var i=0; i<this.markers.length; i++){
						this.markers[i].setMap(null);
					}
					this.markers = new Array();
				};
                //--------------------------------------------------------
				google.maps.Map.prototype.loadMarker = function(key, lat, lng, windowUrl, options) {
					// options
					var options = $.extend({
						url: windowUrl,
					}, (options == undefined ? {} : options));
				
					return {$this->id}.getOrCreateMarker(lat, lng, options);
				};
				//--------------------------------------------------------
				google.maps.Map.prototype.closeMarkerWindowEvent = function(marker) {
				
					if({$this->id}.currentInfoWindow != null){
						let position = {$this->id}.currentInfoWindow.getPosition();
						if(position){
							let lat = {$this->id}.currentInfoWindow.getPosition().lat();
							let lng = {$this->id}.currentInfoWindow.getPosition().lng();
							let key = {$this->id}.buildKey(lat, lng);
							let currentWindowMarker = {$this->id}.getMarkerFromKey(key);
							{$this->id}.setMarkerIcon(key, 'standard');
							
							if(currentWindowMarker.key != marker.key){
								{$this->id}.currentInfoWindow.close();
							}
						}
					}
				};
                //--------------------------------------------------------
				google.maps.Map.prototype.addMarkerWindowEvent = function(marker, url) {
					
					if(marker.is_loaded) return;
					
					if(url){
						{$this->id}.currentInfoWindow = new google.maps.InfoWindow({content: ''});
						//debugger;
						google.maps.event.addListener(marker, 'click', function (target, elem) {
							core.ajax.request_function(url, function(response){
								{$this->id}.currentInfoWindow.setContent(response.html);
								{$this->id}.currentInfoWindow.open({$this->id}, marker);
								
								{$this->id}.panTo(marker.position);
								{$this->id}.setZoom(9);
								
								if(response.on_complete){
									eval(response.on_complete);
								}
							}, {method: 'GET',})
						});
						marker.is_loaded = true;
					}
				
				};
				//--------------------------------------------------------
				google.maps.Map.prototype.setMarkerIcon = function(key, iconName) {
					let marker = this.getMarkerFromKey(key);
					if(marker){
						marker.setIcon(icons[iconName].icon);
					}
				};
				//--------------------------------------------------------
				google.maps.Map.prototype.getMarkerFromKey = function(key) {
				
					let m;
					$.each(this.markers, function( index, marker ) {
						if(key == marker.key) return m = marker;
					});
					
					return m;
				
				};
                //--------------------------------------------------------
                // add markers if locations provided
                if (location_arr.length > 0) {
                    labels = (labels == 'alpha' ? 'ABCDEFGHIJKLMNOPQRSTUVWXYZ' : '');
                    $.each(location_arr, function( index, value ) {
                    	$this->id.addMarker({lat:parseFloat(value.lat), lng:parseFloat(value.lng)}, value);
					});
                }
                //--------------------------------------------------------
                ".implode(" ", $this->post_js_arr)."
                //--------------------------------------------------------
                //--------------------------------------------------------
            }

            function loadJS() {
                // set loaded flag
                core.workspace.google_maps_loaded = 1;

                // load scripts
                // google maps api
                var script = document.createElement('script');
                script.type = 'text/javascript';
                script.src = 'https://maps.googleapis.com/maps/api/js?callback=initMap&key=".\core::$app->get_instance()->get_google_maps_api_key()."';
                document.body.appendChild(script);

                // google marker clusterer
                var script = document.createElement('script');
                script.type = 'text/javascript';
                script.src = 'https://cdn.rawgit.com/googlemaps/js-marker-clusterer/gh-pages/src/markerclusterer.js';
                document.body.appendChild(script);
            }
	    "]);

	}
	//--------------------------------------------------------------------------------
	public function add_location($options = []) {
		// options
		$options = array_merge([
			"lat" => false,
			"lng" => false,
			"title" => null,
			"label" => null,
			"icon" => "standard",
			"is_center" => false,
			"zoom" => 9,
		], $options);

		// required
        if (!$options["lat"] || !$options["lng"]) {
            return false;
		}

		// add location
		$this->location_arr["{$options["lat"]}-{$options["lng"]}"] = $options;

        if($options["is_center"]) {
        	$this->zoom = $options["zoom"];
        	$this->center = ["lat" => floatval($options["lat"]), "lng" => floatval($options["lng"])];
		}

	}
	//--------------------------------------------------------------------------------
	public function add_location_fromarray($arr, $options = []) {
		// options
		$options = array_merge([
		], $options);

		// add each link
		foreach ($arr as $arr_item) {
			$this->add_location($arr_item);
		}
	}
	//--------------------------------------------------------------------------------
	private function build_styles() {

	    $style_arr = [];
	    $style_arr[] = json_encode([
            "featureType" => "landscape",
            "elementType" => "all",
            "stylers" => [
                  ["hue" => "#FFA800"],
                  ["gamma" => 1],
            ],
        ]);

	    $style_arr[] = json_encode([
            "featureType" => "landscape.natural.terrain",
            "elementType" => "geometry.fill",
            "stylers" => [
                  ["color" => "#d2ef9d"],
                  ["lightness" => 15],
                  ["saturation" => -20],
            ],
        ]);

	    $style_arr[] = json_encode([
            "featureType" => "poi",
            "elementType" => "all",
            "stylers" => [
                  ["hue" => "#679714"],
                  ["saturation" => 33.4],
                  ["lightness" => -25.4],
                  ["gamma" => 1],
            ],
        ]);

	    $style_arr[] = json_encode([
            "featureType" => "road.highway",
            "elementType" => "all",
            "stylers" => [
                  ["weight" => "1.30"],
                  ["hue" => "#0300ff"],
                  ["saturation" => -75],
                  ["lightness" => 2],
                  ["gamma" => 1.5],
            ],
        ]);

	    $style_arr[] = json_encode([
            "featureType" => "road.arterial",
            "elementType" => "all",
            "stylers" => [
                  ["hue" => "#FBFF00"],
                  ["gamma" => 1],
            ],
        ]);

	    $style_arr[] = json_encode([
            "featureType" => "road.local",
            "elementType" => "all",
            "stylers" => [
                  ["hue" => "#00FFFD"],
                  ["lightness" => 30],
                  ["gamma" => 1],
            ],
        ]);

	    $style_arr[] = json_encode([
            "featureType" => "transit.station.airport",
            "elementType" => "labels.text.fill",
            "stylers" => [
                  ["color" => "#67a0c7"],
            ],
        ]);

	    $style_arr[] = json_encode([
            "featureType" => "transit.station.airport",
            "elementType" => "labels.icon",
            "stylers" => [
                  ["color" => "#67a0c7"],
            ],
        ]);


	    $style_arr[] = json_encode([
            "featureType" => "water",
            "elementType" => "all",
            "stylers" => [
                  ["hue" => "#00BFFF"],
                  ["saturation" => 6],
                  ["lightness" => 8],
                  ["gamma" => 1],
            ],
        ]);

	    $style_arr[] = json_encode([
            "featureType" => "water",
            "elementType" => "geometry.fill",
            "stylers" => [
                  ["color" => "#94dfed"],
            ],
        ]);


	    return "[".implode(",", $style_arr)."]";
	}
	//--------------------------------------------------------------------------------
	public function get($options = []) {
		return $this->build($options);
	}
	//--------------------------------------------------------------------------------
}