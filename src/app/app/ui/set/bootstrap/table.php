<?php

namespace LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap;

/**
 * @package app\ui\set\bootstrap
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class table extends \com\ui\set\bootstrap\table {
	//--------------------------------------------------------------------------------
	// properties
	//--------------------------------------------------------------------------------
	// general
	public $id = false;						// unique id identifying this component
	public $table_options = [];				// unique id identifying this component
	public $td_options = [];				// unique id identifying this component
	public $th_options = [];				// unique id identifying this component
	public $cache_id = false;				// unique id used for saving current options between page loads
	public $wrapper_id = false;				// wrapper used to update contents of list into
	public $request_arr = [];				// current request options for sorting, filtering and paging
	// i - unique component id
	// o - item offset
	// sf - sort field
	// so - sort order
	// df - date from filter
	// dt - date to filter
	// q - quickfind filter
	// r - reset flag

	// current requests
	public $offset = 0;						// current item number offset
	public $sortfield = 0;					// current sort field, 0 would be the first add_field specified
	public $sortorder = 0;					// current sort order, 0 is asc and 1 desc
	public $datefrom = 0;					// current date from filter
	public $dateto = 0;						// current date to filter
	public $quickfind = "";					// current quickfind filter
	public $quickfind_mark_text = true;     // highlight text
	public $filter_arr = [];				// current advanced filters
	public $custom_filter_arr = [];		    // current custom filters

	// default requests
	protected $default_sortfield = 0;		// default sort field, set by options in constructor
	protected $default_sortorder = 0;		// default sort order, set by options in constructor
	protected $default_offset = 0;			// default item offset, set by options in constructor
	protected $default_datefrom = 0;		// default date from, set by options in constructor
	protected $default_dateto = 0;			// default date to, set by options in constructor
	protected $default_quickfind = "";		// default quickfind, set by options in constructor

	// sql
	public $sql_select = "";				// SELECT part of sql query
	public $sql_from = "";					// FROM part of sql query
	public $sql_where = "";					// WHERE part of sql query
	public $sql_orderby = "";				// ORDER BY part of sql query
	public $sql_groupby = "";				// GROUP BY part of sql query
	public $sql_having = "";				// HAVING part of sql query
	public $sql_union = "";					// UNION an extra sql query

	// custom fields
	public $sql_field_arr = [];				// array to store \app\sql\intf\field items for sql lists
	public $item_field_arr = [];			// array to store \app\sql\intf\field items for item lists

	// event
	public $on_display_legend = false;		// function called before legend is displayed

	public $enable_prepare = false;
	public $enable_sorting = true;
	public $enable_nav = true;
	public $enable_item_count = false;
	public $enable_emptyview = true;
	public $enable_headers = true;
	public $enable_numbers = false;
	public $enable_legend = true;
	public $enable_config = true;

	protected $custom_table_headers = false;
	public $append_form = false; 			// additional forms to serialize and send with updates
	public $html = false; 					// \com\html to integrate navigation with
	public $filter_function = false;
	public $is_filtered = 0;

	public $message_empty = "There are no items that match the given criteria";

	public $on_items_complete = false;

	public $addnew_url = false;
	public $addnew_onclick = false;
	public $addnew_label = "add new";

	public $append_url = false;
	public $append_url_custom = false;
	public $key = false;
	public $width = "100%";
	public $nav_append = false;
	public $nav_append_end = false;
	public $nav_append_custom = false;
	public $header = false;
	public $margin = false;
	public $page_size = 20;
	/**
	 * @var \com\db\connection
	 */
	public $connection = false;
	public $item_arr = [];
	public $save_items = false;

	public $item_total = 0;
	public $item_total_override = 0;
	public $item_count = 0;
	public $item_label = "items";
	public $sql = false;
	public $sql_prepare = false;
	public $limit = false;

	public $similar_field = false;
	public $similar_value = false;
	public $similar_diff = false;

	public $request = "";
	public $is_init = false;
	public $url = false;
	public $field_arr = [];
	public $uid_arr = [];
	public $legend_arr = [];
	public $legend_arr2 = [];
	public $action_arr = [];
	public $action_group_arr = [];
	/**
	 * Collection of filters that should be applied to each row of the result items.
	 * @var callable[]
	 */
	protected $item_filter_arr = [];
	public $page_total = 0;
	public $page_current = 0;

	public $controlbreak_name = false;
	public $controlbreak_field = false;
	public $controlbreak_order = "ASC";
	public $controlbreak_order_field = false;
	public $controlbreak_headerrepeat = false;
	public $controlbreak_trim = false;
	public $controlbreak_lookup = false;
	public $controlbreak_current = false;
	public $controlbreak_current_text = false;

	public $quickfind_description = false;
	public $quickfind_field = false;
	public $quickfind_exact_field_arr = false;
	public $quickfind_from = false;
	public $quickfind_where = false;
	public $quickfind_focus = true;
	public $quickfind_fuzzy = false;
	public $quickfind_placeholder = "Find ..";

	public $checkbox_field = false;
	public $checkbox_visibility_callback = false;
	public $serialize_checkboxes = false;
	public $form_options = [];

	public $datefilter_field = false;

	public $navigation_type = "page";
	public $navigation_field = false;

	protected $total_arr = [];

	// ordering
	protected $ordering_event_js = false;
	public $table_wrapper_options = [".table-responsive" => true];


	//advanced filters
	protected $has_advanced_filters = false;

	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	protected function __construct($options = []) {
		// options
		$options = array_merge([
			"id" => 0,

			"sortfield" => 0,
			"sortorder" => 0,
			"offset" => 0,
			"datefrom" => 0,
			"dateto" => 0,
			"quickfind" => "",
			"append_cache_id" => false,
			"disable_session_cache" => false,
			"form_options" => [],
			"/table" => [],
			"/td" => [],
			"/th" => [],
		], $options);

		// generate cache id
		if (!$options["disable_session_cache"])	{
			$this->cache_id = "comlist_".md5(\core::$app->get_request()->get_resource().\core::$app->get_request()->get_context().\core::$app->get_request()->get_action().\core::$app->get_request()->get_id().$options["append_cache_id"]);
		}

		// id
		$id = $options["id"];
		if ($id) $this->id = $id;

		// init options
		$this->default_sortfield = $options["sortfield"];
		$this->default_sortorder = $options["sortorder"];
		$this->default_offset = $options["offset"];
		$this->default_datefrom = $options["datefrom"];
		$this->default_dateto = $options["dateto"];
		$this->default_quickfind = $options["quickfind"];
		$this->init_requests();

		// id from options
		if (!$this->id) {
			if ($this->request_arr["i"]) {
				$this->id = $this->request_arr["i"];
			}
			else $this->id = \com\session::$current->session_uid;
		}

		// form
		if ($options["form_options"]) {
			$this->form_options = $options["form_options"];
		}

		$this->table_options = $options["/table"];
		$this->td_options = $options["/td"];
		$this->th_options = $options["/th"];

		// init
		$this->wrapper_id = "panel_".\core::$panel;
	}
	//--------------------------------------------------------------------------------
	public function build($options = []) {
		ob_start();
		$this->display();
		return ob_get_clean();
	}
	//--------------------------------------------------------------------------------
    public function parse_request(){
	    // params
		$requests = \core::$app->get_request()->get("comlist", \com\data::TYPE_STRING);

        $requests = json_decode($requests);

		return $requests;
    }
	//--------------------------------------------------------------------------------
    public function is_reset(){
	    // params
		$requests = $this->parse_request();

		return $requests && $requests->r == 1;
    }
	//--------------------------------------------------------------------------------
    public function build_custom_table_headers($mixed){
	    if(is_array($mixed)) $this->custom_table_headers = implode("", $mixed);
	    if(is_string($mixed)) $this->custom_table_headers = $mixed;
	    if(is_callable($mixed)) $this->custom_table_headers = $mixed();
    }
	//--------------------------------------------------------------------------------
	protected function init_requests() {
		// params
		$requests = \core::$app->get_request()->get("comlist", \com\data::TYPE_STRING);

		// get defaults from cache
		if ($this->cache_id) {
			if ($requests !== false) $requests = \com\session::$current->{$this->cache_id} = $this->get_requests_json(json_decode($requests, true));
			if ($requests === false) $requests = \com\session::$current->{$this->cache_id};
			if ($requests === false) $requests = \com\session::$current->{$this->cache_id} = $this->get_requests_json();

			// reset list
			$requests = json_decode($requests, true);
			if ($requests["r"]) {
				\com\session::$current->{$this->cache_id} = $this->get_requests_json();
				$requests = json_decode(\com\session::$current->{$this->cache_id}, true);
			}
		}
		else {
			if ($requests === false) $requests = $this->get_requests();
			else $requests = json_decode($requests, true);

			if ($requests["r"]) $requests = $this->get_requests();
		}

		// save current requests
		$this->offset = $requests["o"];
		$this->datefrom = $requests["df"];
		$this->dateto = $requests["dt"];
		$this->quickfind = $requests["q"];
		$this->sortfield = $requests["sf"];
		$this->sortorder = $requests["so"];
		$this->filter_arr = $requests["f"];

		if(!$this->datefrom){
			$value = $this->request_advanced_filter("{$this->id}_datefrom", ["property" => "datefrom", "type" => \com\data::TYPE_DATE]);
			if($value && !isnull($value)) $this->datefrom = $value;
		}
		if(!$this->dateto){
			$value = $this->request_advanced_filter("{$this->id}_dateto", ["property" => "dateto", "type" => \com\data::TYPE_DATE]);
			if($value && !isnull($value)) $this->dateto = $value;
		}
		if(!$this->quickfind){
			$value = $this->request_advanced_filter("{$this->id}_quickfind", ["property" => "quickfind"]);
			if($value) $this->quickfind = $value;
		}

		if ($this->filter_arr) $this->is_filtered = true;
		$this->request_arr = $requests;
	}
	//--------------------------------------------------------------------------------
	protected function get_requests($request_arr = []) {
		// requests
		$request_arr = array_merge([
			"i" => "$this->id", 					// unique id
			"sf" => "$this->default_sortfield", 	// sort field
			"so" => "$this->default_sortorder", 	// sort order
			"o" => "$this->default_offset", 		// offset
			"df" => "$this->default_datefrom", 		// date from
			"dt" => "$this->default_dateto", 		// date to
			"q" => "$this->default_quickfind", 		// quickfind
			"r" => 0, 								// reset
			"f" => $this->filter_arr, 				// advanced filters
		], $request_arr);

		// return requests
		return $request_arr;
	}
	//--------------------------------------------------------------------------------
	public function request_advanced_filter($id, $options = []) {

		$options = array_merge([
		    "default" => false,
		    "property" => false,
		    "type" => \com\data::TYPE_STRING,
		    "suffix" => "_adv",
		], $options);

		if($options["suffix"]) $id = "{$id}{$options["suffix"]}";
		$cache_id = "{$this->cache_id}_{$id}";
		$value = \core::$app->get_request()->get($id, $options["type"], $options);

		if(isnull($value)) $value = $options["default"];

		if(!$value) $value = \com\session::$current->{$cache_id};

		if ($this->is_reset()) {
			$value = \com\session::$current->{$cache_id} = $options["default"];
			if($options["property"]) $this->{$options["property"]} = $value;
			return $value;
		}

		if($options["property"]) $this->{$options["property"]} = $value;
		\com\session::$current->{$cache_id} = $value;

		return $value;

	}
	//--------------------------------------------------------------------------------
	public function build_advanced_filter($options = []) {

		$options = array_merge([
		    "enable_quickfind" => $this->quickfind_field,
		    "enable_date" => $this->datefilter_field,
		    "label_col" => 4,
		], $options);


		$this->has_advanced_filters = true;

		$this->nav_append_end = function($comtable, $toolbar) use($options){

			$modal = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->modal();
			$modal->set_title("Advanced Search");
			$modal->set_body(function(&$buffer) use($comtable, $options){
				$buffer->div_([".advanced-filter-wrapper" => true, "@data-source" => $comtable->id]);

					if($options["enable_quickfind"]) {
						$value = $this->request_advanced_filter("{$comtable->id}_quickfind", ["property" => "quickfind"]);
						$buffer->xitext("{$comtable->id}_quickfind_adv", $value, "Search", [
						    "/form_input" => [".mb-2" => false],
							"label_col" => $options["label_col"],
							"focus" => (bool)$value,
							"@placeholder" => $this->quickfind_placeholder,
							"/wrapper" => [".d-flex align-items-center" => true]
						]);
					}

					if($options["enable_date"]) {
						$buffer->div_([".row align-items-center mb-2" => true]);
							$buffer->div_([".col-md-{$options["label_col"]}" => true]);
						    	$buffer->xform_label("Filter by Date");
						    $buffer->_div();
						    $buffer->div_([".col" => true]);
						    	$datefrom = $this->request_advanced_filter("{$comtable->id}_datefrom", ["property" => "datefrom", "type" => \com\data::TYPE_DATE]);
						    	$buffer->xidate("{$comtable->id}_datefrom_adv", $datefrom, false, [
						    		"@placeholder" => "Date from",
						    		".mb-2" => true,
									"!change" => "
										let date = $('#{$comtable->id}_datefrom_adv').val();
										setTimeout(function(){
											$('#{$comtable->id}_dateto_adv').datetimepicker('minDate', date);
											$('#{$comtable->id}_dateto_adv').datetimepicker('date', new Date(date));
											$('#{$comtable->id}_dateto_adv').val('');
										}, 100);
									",
								]);

						    	$dateto = $this->request_advanced_filter("{$comtable->id}_dateto", ["property" => "dateto", "type" => \com\data::TYPE_DATE]);
						    	$buffer->xidate("{$comtable->id}_dateto_adv", $dateto, false, ["@placeholder" => "Date To",]);
						    $buffer->_div();
						$buffer->_div();
					}

					array_walk($this->custom_filter_arr, function($item, $id) use(&$buffer, $options){
						$value = $this->request_advanced_filter($id);
						$buffer->xiselect("{$id}_adv", $item["value_option_arr"], $value, $item["label"], ["label_col" => $options["label_col"]]);
					});

				$buffer->_div();
			});
			$modal->set_footer(function(&$buffer) use($comtable){
				$buffer->div_([".row" => true]);
				    $buffer->div_([".col-12" => true]);
				    	$buffer->xbutton("Reset", "$('#{$comtable->id}').comlist('reset');", ["@data-bs-dismiss" => "modal", ".btn-danger me-2" => true, "icon" => "fa-ban", "@title" => "Reset Filters"]);
				    	$buffer->xbutton("Apply Filters", \core::$app->get_request()->get_panel().".refresh(null, {data:$('#{$this->id} .advanced-filter-wrapper *').serialize()})", ["@data-bs-dismiss" => "modal"]);
				    $buffer->_div();
				$buffer->_div();
			});
			$toolbar->add(\LiquidedgeApp\Octoapp\app\app\ui\ui::make()->button("Advanced Filter", $modal, ["icon" => "fa-search"]));

		};
	}
	//--------------------------------------------------------------------------------
	protected function get_requests_json($requests = []) {
		if (!is_array($requests)) $requests = [];
		return json_encode($this->get_requests($requests));
	}
	//--------------------------------------------------------------------------------
	public function get_filter($name) {
		if (isset($this->filter_arr[$name])) return $this->filter_arr[$name];
		else return false;
	}
	//--------------------------------------------------------------------------------
	public function add_custom_filter($id, $value_option_arr, $label = false, $options = []) {

	    $options = array_merge([
	        "!change" => \core::$app->get_request()->get_panel().".refresh(null, {data:$('#{$this->id}.ui-table div[role=toolbar] *').serialize()})",
	        "exclude_arr" => [],
	        "default" => null,
	    ], $options);

	    foreach ($options["exclude_arr"] as $exclude){
	    	if(isset($value_option_arr[$exclude]))
	    		unset($value_option_arr[$exclude]);
		}

        $session_id = "{$this->cache_id}_filter_{$id}";
	    $value = \core::$app->get_request()->get($id, \com\data::TYPE_STRING, ["trusted" => true, "default" => \core::$app->get_session()->get($session_id, null)]);

	    if($this->is_reset()) $value = null;
	    if($value === "" || $value === null) $value = $options["default"];

        $this->custom_filter_arr[$id] = [
            "id" => "",
            "value_option_arr" => $value_option_arr,
            "label" => $label,
            "value" => \core::$app->get_session()->{$session_id} = $value,
            "/" => $options
        ];

        return $value;
	}
	//--------------------------------------------------------------------------------
	public function init_custom_nav() {
	    if(!$this->nav_append_custom && !$this->has_advanced_filters){
            $this->nav_append_custom = function($list, &$toolbar){
                foreach ($this->custom_filter_arr as $id => $item){
                    $uri[$id] = $item["value"];
					$buffer = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();
					$buffer->div_([".d-block d-md-flex align-items-center" => true]);
						$buffer->label(["*" => $item["label"], "@for" => $id, ".me-2 my-0" => true]);

						$item["/"][".mb-2"] = false;
						$item["/"]["/wrapper"][".mb-2"] = false;
						$item["/"]["/wrapper"]["#min-width"] = "200px";
						$buffer->xiselect($id, $item["value_option_arr"], $item["value"], false, $item["/"]);

					$buffer->_div();

                    $toolbar->add($buffer->build());
                }
            };
        }
	}
	//--------------------------------------------------------------------------------
	public function get_custom_filter($id, $default = null) {
	    return isset($this->custom_filter_arr[$id]) && !isempty($this->custom_filter_arr[$id]) ? $this->custom_filter_arr[$id]["value"] : $default;
	}
	//--------------------------------------------------------------------------------
	protected function init_append_url() {

	    $uri = [];
	    foreach ($this->custom_filter_arr as $id => $item){
	        $uri[$id] = $item["value"] == null ? "null" : $item["value"];
        }

		$this->append_url_custom = "&".http_build_query($uri);
	}
	//--------------------------------------------------------------------------------
	public function add_item_filter($fn_filter) {
		$this->page_size = 0;
		$this->item_filter_arr[] = $fn_filter;
	}
	//--------------------------------------------------------------------------------
	protected function get_legend_class($name) {
		// mappings
		$map_arr = [
			"success" => "success",
			"danger" => "danger",
			"info" => "info",
			"light" => "light",
			"warning" => "warning",
			"primary" => "primary",
			"secondary" => "secondary",

			"green" => "success",
			"red" => "danger",
			"blue" => "info",
			"grey" => "light",
			"orange" => "warning",
			"brown" => "secondary",
			"purple" => "primary",
		];

		// limit to preset colors
		if (!isset($map_arr[$name])) {
			return false;
		}

		// done
		return $map_arr[$name];
	}
	//--------------------------------------------------------------------------------
	public function add_legend2($color, $description, $fn_filter) {
		// add to legend array
		$legend_class = $this->get_legend_class($color);
		$this->legend_arr2[$legend_class] = [
			"description" => $description,
			"fn_filter" => $fn_filter,
		];
	}
	//--------------------------------------------------------------------------------
	public function add_legend($color, $description, $sql) {
		// add to legend array
		$legend_class = $this->get_legend_class($color);
		$this->legend_arr[$legend_class] = [
			"description" => $description,
			"sql" => $sql,
		];
	}
	//--------------------------------------------------------------------------------
	public function add_ordering($url) {
		// make sure that you have set a list->key
		$this->ordering_event_js = $url;
	}
	//--------------------------------------------------------------------------------
	public function get_item_fields() {
		return $this->item_field_arr;
	}
	//--------------------------------------------------------------------------------
	public function add_custom_field($field_type, $table, $field, $type, $context = false, $options = []) {
		// options
		$options = array_merge([
			"alias" => false,
			"sql_field" => "{$table}_{$field}",
			"context" => $context,
		], $options);

		// limit
		$field_type_arr = [
			"sql",
			"item",
		];
		if (!in_array($field_type, $field_type_arr)) {
			return;
		}

		// init
		$field_item = \LiquidedgeApp\Octoapp\app\app\ui\sql\factory\field::make()->get($options["sql_field"], ["context" => $context]);

		if ($options["alias"]) {
			$field = "{$options["alias"]}_{$field}";
		}

		// uid
		$uid = base64_encode($field_item->get_description().".".$field_item->get_class());
		$options["uid"] = $uid;

		// field options
		$field_options = array_merge($field_item->get_options(), $options);

		// add to list
		$this->add_field($field_item->get_description(), $field, $field_options);

		// prepare item
		$item = [
			"type" => $type,
			"field" => $field_item->get_class(),
			"name" => $field_item->get_description(),
			"alias" => $options["alias"],
			"context" => $context,
			"uid" => $uid,
		];

		// add to field array
		switch($field_type) {
			case "sql" :
				// add to field arr
				$this->sql_field_arr[$uid] = $item;
				break;

			case "item" :
				$this->item_field_arr[$uid] = $item;
				break;
		}
	}
	//--------------------------------------------------------------------------------
	public function add_item_field($table, $field, $type, $context = false, $options = []) {
		$this->add_custom_field("item", $table, $field, $type, $context, $options);
	}
	//--------------------------------------------------------------------------------
	public function add_sql_field($table, $field, $type, $context = false, $options = []) {
		$this->add_custom_field("sql", $table, $field, $type, $context, $options);
	}
	//--------------------------------------------------------------------------------
	public function has_custom_fields() {
		// sql
		if ($this->sql_field_arr) {
			return true;
		}

		// items
		if ($this->item_field_arr) {
			return true;
		}

		 // done
		return false;
	}
	//--------------------------------------------------------------------------------
	public function get_list_fields() {
		// init
		$list_field_arr = [];

		// sql
		if ($this->sql_field_arr) {
			$list_field_arr = $this->sql_field_arr;
		}

		// items
		if ($this->item_field_arr) {
			$list_field_arr = $this->item_field_arr;
		}

		// filter
		foreach ($list_field_arr as $key => $field_item) {
			$field = \LiquidedgeApp\Octoapp\app\app\ui\sql\factory\field::make()->get($field_item["field"], ["context" => $field_item["context"]]);
			// remove if not visible field
			if (!$field->is_display_field()) {
				unset($list_field_arr[$key]);
			}
		}

		// done
		return $list_field_arr;
	}
	//--------------------------------------------------------------------------------
	/**
	 * @param \com\db\sql\select $sql
	 */
	public function build_sql($sql) {
		// get list config
		$config_class = \com\core\config\uitable::make(["cfg_name" => $this->cache_id]);
		$data_arr = json_decode($config_class->get_config()->cfg_data ?? [], true);

		// run through sql fields to attach to and generate $sql
		foreach ($this->sql_field_arr as $field_item) {
			// get field from class
			$field = \LiquidedgeApp\Octoapp\app\app\ui\sql\factory\field::make()->get($field_item["field"], ["context" => $field_item["context"], "sql" => $sql]);

			// look for this field in data if it is display field (a non-display field would inherently be needed as it is added to $this->sql_field_arr but not visible)
			if (!$field->is_required() && $field->is_display_field()) {
				// customized
				if ($data_arr) {
					// if field doesn't exist in data, continue
					if (!isset($data_arr[$field_item["uid"]])) {
						continue;
					}

					// if field exists, but is not added or hidden, continue
					$config_data_item = $data_arr[$field_item["uid"]];
					if (!$config_data_item["show"] || !$config_data_item["added"]) {
						continue;
					}
				}
				elseif ($field->get_type() != "default") {
					continue;
				}
			}

			// add to sql
			$field->build();
		}
	}
	//--------------------------------------------------------------------------------
	/**
	 * @param \com\db\sql\select $sql
	 */
	public function set_sql($sql) {
		if (\com\release::is_active("!CRMDEV-19769 .core")) {
			$this->build_sql($sql);
		}

		$part_arr = $sql->get_parts();

		$this->sql_select = $part_arr["select"];
		$this->sql_from = $part_arr["from"];
		$this->sql_where = $part_arr["where"];
		$this->sql_groupby = $part_arr["groupby"];
		$this->sql_having = $part_arr["having"];
		$this->sql_union = $part_arr["union"];
		$this->sql_orderby = $part_arr["orderby"];
	}
	//--------------------------------------------------------------------------------
	public function set_items($item_arr) {
		$this->item_arr = $item_arr;
	}
	//--------------------------------------------------------------------------------
	/**
	 * @param \com\ui\intf\uploader $uploader
	 */
	public function add_uploader($uploader) {
		// uploader navigation
		$uploader->button_id = "{$this->id}uploadbtn";
		$uploader->nav_options = ["#display" => "inline"];
		$uploader->nav_content = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->button("upload", "", ["@id" => "{$this->id}uploadbtn"]);

		// uploader progress
		$uploader->delay_progress_display = true;
		$this->on_display_legend = [$uploader, "display_progress"];

		// add uploader to navigation
		ob_start();
		$uploader->display();
		$this->nav_append = ob_get_clean();
	}
	//--------------------------------------------------------------------------------
    public function add_field_switch($header, $field, $action = false, $options = []) {

	    $options = array_merge([
	        "#width" => "200px",
	        "#text-align" => "center",
	        "nosort" => true,
	        "fn_filter" => false,
	        "key" => $this->key,
	        "panel" => \core::$app->get_request()->get_panel(),
	    ], $options);


	    $options["function"] = function($content, $item_index, $field_index, $list) use($field, $action, $options){
            $id = "switch_{$item_index}_{$field_index}";
            $db_id = $list->item_arr[$item_index]["{$options["key"]}"];

            parse_str($action, $action_arr);
            foreach ($action_arr as $key => $value){
            	$value = trim($value);
            	if(strpos($value, "%") !== false){
            		$url_key = \LiquidedgeApp\Octoapp\app\app\str\str::extract_between_two_chars($value, "%");
            		$value = $list->item_arr[$item_index][$url_key];
            		$action = str_replace("%{$url_key}%", $value, $action);
				}
			}

            if($options["fn_filter"] && is_callable($options["fn_filter"])){
                $result = $options["fn_filter"]($content, $item_index, $field_index, $list);
                if($result == true) return;
            }

            return \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->iswitch($id, $list->item_arr[$item_index][$field], $list->item_arr[$item_index][$field], false, [
                "!click" => \LiquidedgeApp\Octoapp\app\app\js\js::ajax("{$action}", [
                	"*data" => "!{ id:'{$db_id}', field:'{$field}', el:'#{$id}' }",
                	"*success" => "function(response){
                		if(!response.code){
                			{$options["panel"]}.refresh();
                		}else{
                			core.ajax.process_response(response);
                		}
                	}",
				]),
                "/wrapper" => [".d-inline-block" => true, ".mb-2" => false,],
            ]);
        };

        $this->add_field($header, $field, $options);
    }
	//--------------------------------------------------------------------------------
    public function get_dtb_table_name($field) {
        // check if we have the prefix
        $table_prefix = substr($field, 0, 3);
        if (!\com\db\lib\config::has_prefix($table_prefix)) {
            return \com\error::create("Table prefix not found: {$table_prefix}");
        }

        // get table field lookup
        return \com\db\lib\config::get_table($table_prefix);
    }
	//--------------------------------------------------------------------------------
	public function add_field($header, $field, $options = []) {
		if (\com\release::is_active("!CRMDEV-19769 .core")) {
			// options
			$options = array_merge([
				"header" => $header,
				"field" => $field,

				"!click" => false,
				"#width" => false,
				"#text-align" => false,
				"#vertical-align" => false,
				"tooltip" => false,
				"headtooltip" => false,
				"nullvalue" => false,
				"falsevalue" => false,
				"lookup" => false,
				"set" => false,
				"maxlength" => false,
				"function" => false, // function($content, $item_index, $field_index, $this_list)
				"function_refresh_data" => false,
				"sortfield" => $field,
				"input" => false,
				"nosort" => false,
				"template" => false,
				"nl2br" => false,
				"id" => false,
				"shrink" => false,
				"format" => false,
				"total" => false,
				"total_no_currency" => false,
				"decimals" => 2,
				"hide_for" => false,
				"hide_currency_symbol" => false,
				"replace_tag" => [],
				"add_tags" => [],
				"add_notes" => [],
				"fn_value" => false, // function($item)
				"uid" => false,

				// custom fields (sql / item)
				"sql_field" => false,
				"added" => true,
				"context" => false,
			], $options);
		}
		else {
			// options
			$options = array_merge([
				"header" => $header,
				"field" => $field,

				"!click" => false,
				"#width" => false,
				"#text-align" => false,
				"#vertical-align" => false,
				"tooltip" => false,
				"headtooltip" => false,
				"nullvalue" => false,
				"falsevalue" => false,
				"lookup" => false,
				"set" => false,
				"maxlength" => false,
				"function" => false, // function($content, $item_index, $field_index, $this_list)
				"function_refresh_data" => false,
				"sortfield" => $field,
				"input" => false,
				"nosort" => false,
				"template" => false,
				"nl2br" => false,
				"id" => false,
				"shrink" => false,
				"format" => false,
				"total" => false,
				"total_no_currency" => false,
				"decimals" => 2,
				"hide_for" => false,
				"hide_currency_symbol" => false,
				"replace_tag" => [],
				"add_tags" => [],
				"add_notes" => [],
				"fn_value" => false, // function($item)
				"uid" => false,
			], $options);
		}

		// show key only for admins
		if ($options["format"] == DB_KEY) {
			if (!\com\user::auth_for("DEV ADMIN") && !\com\session::$current->core_userloginas_id) return;
		}

		// identifier
		if (!$options["uid"]) {
			$options["uid"] = base64_encode($options["header"].".".$options["field"]);
		}

		// only record if enabled for config
		if (empty($options[".ui-table-cell-noconfig"]) || !$options[".ui-table-cell-noconfig"]) {
			// base order
			static $field_order = 0;

			// store layout details
			if (\com\release::is_active("!CRMDEV-19769 .core")) {
				$this->uid_arr[$options["uid"]] = [
					"uid" => $options["uid"],
					"name" => strip_tags($options["header"]),
					"order" => $field_order++,
				];
			}
			else {
				$this->uid_arr[$options["uid"]] = [
					"uid" => $options["uid"],
					"header" => strip_tags($options["header"]),
					"order" => $field_order++,
				];
			}
		}

		// auto lookup
		if ($options["lookup"] === true) {
			// check if we have the prefix
			$table_prefix = substr($field, 0, 3);
			if (!\com\db\lib\config::has_prefix($table_prefix)) {
				return \com\error::create("Table prefix not found: {$table_prefix}");
			}

			// get table field lookup
			$table = \com\db\lib\config::get_table($table_prefix);
			$options["lookup"] = \core::dbt($table)->{$field};
		}

		// onclick
		if ($options["!click"]) {
			$options["id"] = true;
			$options["__click"] = \com\str::variables($options["!click"]);
		}

		// tooltip
		if ($options["tooltip"]) {
			$options["id"] = true;
			$options["__tooltip"] = \com\str::variables($options["tooltip"]);
		}

		// template
		if ($options["template"]) {
			$options["__template"] = \com\str::variables($field);
		}

		// total
		if ($options["total"]) {
			$this->total_arr[$options["field"]] = 0;
		}

		// add field
		$this->field_arr[] = $options;
	}
	//--------------------------------------------------------------------------------
	public function set_tag($field, $color, $fn_filter, $options = []) {
		// find field
		$index = \LiquidedgeApp\Octoapp\app\app\arr\arr::find($field, $this->field_arr, "field");
		if ($index === false) throw new \com\error\exception\generic("Field not found", 1);

		// done
		$this->field_arr[$index]["replace_tag"][] = ["color" => $color, "fn_filter" => $fn_filter, "options" => $options];
	}
	//--------------------------------------------------------------------------------
	public function add_tag($label, $field, $color, $fn_filter, $options = []) {
		// find field
		$index = \LiquidedgeApp\Octoapp\app\app\arr\arr::find($field, $this->field_arr, "field");
		if ($index === false) throw new \com\error\exception\generic("Field not found", 1);

		// done
		$this->field_arr[$index]["add_tags"][] = ["label" => $label, "color" => $color, "fn_filter" => $fn_filter, "options" => $options];
	}
	//--------------------------------------------------------------------------------
	public function add_note($note, $field, $fn_filter, $options = []) {
		// find field
		$index = \LiquidedgeApp\Octoapp\app\app\arr\arr::find($field, $this->field_arr, "field");
		if ($index === false) throw new \com\error\exception\generic("Field not found", 1);

		// done
		$this->field_arr[$index]["add_notes"][] = ["note" => $note, "fn_filter" => $fn_filter, "options" => $options];
	}
	//--------------------------------------------------------------------------------
	protected function build_controller($controller = false, $action = false, $key = false) {
		// controller
		$c = "?c=".($controller ? $controller : \core::$app->get_request()->get_resource());

		// action
		$c .= "/".($action ? $action : \core::$app->get_request()->get_action());

		// key
		$c .= "/".($key ? $key : \core::$app->get_request()->get_id());

		// remove trailing /
		$c = rtrim($c, "/");

		// return controller
		return $c;
	}
	//--------------------------------------------------------------------------------
	protected function build_url($url = false, $action = false) {
		// build url with components
		if (!$url) {
			if (!$this->key) \com\error::create("No key specified");
			$url = $this->build_controller(false, $action, "%$this->key%");
		}
		elseif (!preg_match("/^(index\\.php)|\\?/i", $url)) {
			if (!$this->key) \com\error::create("No key specified");
			$url = $this->build_controller(false, $url, "%$this->key%");
		}

		// return
		return $url;
	}
	//--------------------------------------------------------------------------------
	public function add_action_edit($url = false, $options = []) {
	    //options
        $options = array_merge([
            "label" => "manage"
        ], $options);

		// build url
		$url = $this->build_url($url, "vedit");

		// add action
		$this->add_action($options["label"], \core::$panel.".requestUpdate('$url');", "pencil", $options);
	}
	//--------------------------------------------------------------------------------
	public function add_action_manage($url = false, $options = []) {
	    //options
        $options = array_merge([
            "label" => "manage"
        ], $options);

		// build url
		$url = $this->build_url($url, "vmanage");

		// add action
		$this->add_action($options["label"], \core::$panel.".requestUpdate('$url');", "pencil", $options);
	}
	//--------------------------------------------------------------------------------
	public function add_action_delete($url = false, $options = []) {
	    //options
        $options = array_merge([
            "label" => "delete"
        ], $options);

		// build url
		$url = $this->build_url($url, "xdelete");

		// add action
		$this->add_action($options["label"], \core::$panel.".requestRefresh('$url', { confirm: 'Are you sure you want to delete this item?' });", "remove", $options);
	}
	//--------------------------------------------------------------------------------
	public function add_action_vdelete($url = false, $options = []) {
		// build url
		$url = $this->build_url($url, "vdelete");

		// add action
		$this->add_action("delete", \core::$panel.".requestUpdate('$url');", "remove", $options);
	}
	//--------------------------------------------------------------------------------
		public function add_action_view($url = false, $options = []) {
		// build url
		$url = $this->build_url($url, "vview");

		// add action
		$this->add_action("view", \core::$panel.".requestUpdate('$url');", "zoom-in", $options);
	}
	//--------------------------------------------------------------------------------
	public function add_action_download($url = false, $options = []) {
		// build url
		$url = $this->build_url($url, "xdownload");

		// add action
		$this->add_action("download", "document.location = '$url';", "download", $options);
	}
	//--------------------------------------------------------------------------------
	/**
	 * @param type $label
	 * @param \com\ui\intf\dropdown $dropdown
	 */
	public function add_action_group($label, $dropdown) {
		// done
		$this->action_group_arr[] = [
			"label" => $label,
			"dropdown" => $dropdown,
		];
	}
	//--------------------------------------------------------------------------------
	public function add_action($name, $onclick = false, $icon = false, $options = []) {
		// options
		$options = array_merge([
			"!click" => $onclick,
			"name" => ucfirst($name),
			"icon" => $icon,
			"filter" => false,
			"escape" => false,
			"confirm" => false,
		], $options);

		// onclick
		$options["__click"] = \com\str::variables($options["!click"]);

		// name
		$options["__name"] = \com\str::variables($options["name"]);

		// confirm
		if ($options["confirm"]) {
			$message = ($options["confirm"] === true ? "Are you sure you want to continue?" : \com\str::escape_singlequote($options["confirm"]));
			$options["!click"] = "core.browser.confirm('{$message}', function() { {$options["!click"]} });";
		}

		// add to actions
		$this->action_arr[] = $options;
	}
	//--------------------------------------------------------------------------------
	protected function init_items() {
		// check if list uses database
		if ($this->sql_select) $this->init_dbitems();
		else $this->init_manualitems();

		// override offset is more than total
		if ($this->offset > $this->item_total) $this->offset = 0;

		// event: on_items_complete
		if ($this->on_items_complete) call_user_func_array($this->on_items_complete, [$this]);

		// current page item count
		$this->item_count = count($this->item_arr);
	}
	//--------------------------------------------------------------------------------
	protected function init_manualitems() {
		// check if we have items
		if (!$this->item_arr) return;

		// quickfind
		if ($this->quickfind_field && $this->quickfind) {
			$quickfind = \com\str::strip_extra_whitespace($this->quickfind);
			$quickfind_arr = explode(" ", $quickfind);
			$quickfind_arr = array_map(function($item) { return "(?=.*".preg_quote($item, "/").")"; }, $quickfind_arr);
			$regx_quickfind = implode("", $quickfind_arr);
			foreach ($this->item_arr as $item_index => $item_item) {
				if (!preg_match("/{$regx_quickfind}/i", $item_item[$this->quickfind_field])) unset($this->item_arr[$item_index]);
			}
		}

		// total item count
		if ($this->item_total_override) $this->item_total = $this->item_total_override;
		else $this->item_total = count($this->item_arr);

		// sorting
		if ($this->enable_sorting) {
			$order = ($this->sortorder ? "desc" : "asc");
			\LiquidedgeApp\Octoapp\app\app\arr\arr::sort($this->item_arr, $this->field_arr[$this->sortfield]["sortfield"], ["order" => $order]);
		}

		// paging
		$offset = false;
		$limit = false;
		if ($this->page_size && $this->enable_nav) {
			switch ($this->navigation_type) {
				case "page" : // page next, previous, jumpto navigation
					if (!$this->limit && $this->offset >= 0) {
						$offset = (is_numeric($this->offset) ? $this->offset : 0);
						$limit = $this->page_size;
						if (count($this->item_arr) < $offset) $offset = 0;
					}
					break;
			}

			if ($this->limit) $limit = $this->limit;

			if (!$this->item_total_override) {
				$this->item_arr = array_slice($this->item_arr, $offset, $limit, true);
			}
		}
	}
	//--------------------------------------------------------------------------------
	private function parse_quickfind_arr(){

		$quickfind = $this->quickfind;

		if($this->quickfind && $this->quickfind_fuzzy){
			$quickfind = explode(",", $this->quickfind);

			foreach ($quickfind as $index => $value){
				$quickfind[$index] = trim($value);
			}
		}

		return $quickfind;
	}
	//--------------------------------------------------------------------------------
	protected function init_dbitems($options = []) {
		// options
		$options = array_merge([
			"buffer" => false,
		], $options);

		// connection
		if (!$this->connection) $this->connection = \com\db::connect_primary();

		// init sql
		$SQL_select = [$this->sql_select];
		$SQL_from = [$this->sql_from];
		$SQL_where = [];
		$SQL_union_where = [];
		$SQL_orderby = [];
		$SQL_having = [];
		$SQL_groupby = [];
		$SQL_union = [];

		// control break
		if ($this->controlbreak_field) {
			$controlbreak_order_field = ($this->controlbreak_order_field ?: $this->controlbreak_field);
			$SQL_orderby[] = "{$controlbreak_order_field} {$this->controlbreak_order}";
		}

		// defaults
		if ($this->sql_where) $SQL_where[] = $this->sql_where;
		if ($this->sql_orderby) $SQL_orderby[] = $this->sql_orderby;
		if ($this->sql_having) $SQL_having[] = $this->sql_having;
		if ($this->sql_groupby) $SQL_groupby[] = $this->sql_groupby;
		if ($this->sql_union) $SQL_union[] = $this->sql_union;

		if (\com\release::is_active("!CRMDEV-19769 .core")) {
			// ordering flag
			if ($this->enable_sorting) {
				$ordertype = ($this->sortorder ? "DESC" : "ASC");
				$orderfield = false;
				if (isset($this->field_arr[$this->sortfield]) && $this->field_arr[$this->sortfield]["added"] === true) {
					$orderfield = $this->field_arr[$this->sortfield]["sortfield"];
				}
				elseif (isset($this->field_arr[0]["sortfield"]) && $this->field_arr[0]["added"] === true) {
					$orderfield = $this->field_arr[0]["sortfield"];
				}

				// sort
				if ($orderfield && \LiquidedgeApp\Octoapp\app\app\arr\arr::find_regex("/^{$orderfield} /i", $SQL_orderby) === false) {
					$SQL_orderby[] = "{$orderfield} {$ordertype}";
				}
			}
		}
		else {
			// ordering flag
			if ($this->enable_sorting) {
				$ordertype = ($this->sortorder ? "DESC" : "ASC");
				if (isset($this->field_arr[$this->sortfield])) $orderfield = $this->field_arr[$this->sortfield]["sortfield"];
				else $orderfield = $this->field_arr[0]["sortfield"];
				if (\LiquidedgeApp\Octoapp\app\app\arr\arr::find_regex("/^{$orderfield} /i", $SQL_orderby) === false) $SQL_orderby[] = "{$orderfield} {$ordertype}";
			}
		}

		// legend
		if ($this->legend_arr) {
			$this_select = "CASE";
			foreach ($this->legend_arr as $legend_index => $legend_item) {
				if ($legend_item["sql"] == "ELSE") $this_select .= " ELSE '$legend_index'";
				else $this_select .= " WHEN $legend_item[sql] THEN '$legend_index'";
			}
			$this_select .= " END AS __legend";
			$SQL_select[] = $this_select;
		}

		// action filters
		foreach ($this->action_arr as $action_index => $action_item) {
			if ($action_item["filter"] && !is_callable($action_item["filter"])) $SQL_select[] = "CASE WHEN {$action_item["filter"]} THEN 1 ELSE 0 END AS __actionfilter$action_index";
		}

		// quickfind
		if ($this->quickfind) {
			// quickfind: field
			if ($this->quickfind_field) {

				$quickfind = $this->parse_quickfind_arr();

				if ($SQL_union) $SQL_union_where[] = \com\db::getsql_find($quickfind, $this->quickfind_field);
				else $SQL_where[] = \com\db::getsql_find($quickfind, $this->quickfind_field);
			}
			elseif ($this->quickfind_where) {
				// quickfind: custom where
				$SQL_quickfind = dbvalue("%{$this->quickfind}%");
				if ($SQL_union) $SQL_union_where[] = strtr("({$this->quickfind_where})", ["%term%" => "{$SQL_quickfind}"]);
				else $SQL_where[] = strtr("({$this->quickfind_where})", ["%term%" => "{$SQL_quickfind}"]);
			}
			elseif ($this->quickfind_exact_field_arr) {
				$inner_where_arr = [];
				$SQL_quickfind = dbvalue($this->quickfind);
				foreach ($this->quickfind_exact_field_arr as $quickfind_exact_field_item) {
					$inner_where_arr[] = "{$quickfind_exact_field_item} = {$SQL_quickfind}";
				}
				$SQL_where[] = "(".implode(" OR ", $inner_where_arr).")";
			}

			// quickfind: from
			if ($this->quickfind_from) $SQL_from[] = $this->quickfind_from;
		}

		// date filter
		if ($this->datefilter_field) {
			$this_where = [];
			if ($this->datefrom) $this_where[] = "$this->datefilter_field >= '$this->datefrom 00:00:00'";
			if ($this->dateto) $this_where[] = "$this->datefilter_field <= '$this->dateto 23:59:59'";
			if ($this_where) $SQL_where[] = implode(" AND ", $this_where);
		}

		// navigation
		$offset = false;
		$limit = false;
		if ($this->page_size && $this->enable_nav) {
			switch ($this->navigation_type) {
				case "page" : // page next, previous, jumpto navigation
					if (!$this->limit && $this->enable_nav && $this->offset >= 0) {
						$offset = (is_numeric($this->offset) ? $this->offset : 0);
						$limit = $this->page_size;
					}
					break;

				case "alpha" : // jumpto AB, CD etc. navigation
					// default offset
					if (!$this->offset) $this->offset = "a";

					switch($this->offset) {
						// numeric
						case "#" :
							// range
							$char_range = "[0-9]";

							// sql
							$SQL_where[] = "($this->navigation_field LIKE '{$char_range}%')";
							break;

						// alphabetical
						default :
							// get next character
							$next_letter = chr(ord($this->offset) + 1);

							// range
							$char_range = "[{$this->offset}-{$next_letter}]";

							// sql
							$SQL_where[] = "($this->navigation_field LIKE '{$char_range}%')";
							break;
					}
					break;
			}
		}

		// build sql, run query and get total count
		$SQL_calc_rows = (\core::$app->get_instance()->get_db_type() == "mysql" ? " SQL_CALC_FOUND_ROWS " : false);
		$this->sql = "SELECT{$SQL_calc_rows} ".implode(",", $SQL_select)." FROM ".implode($SQL_from);
		if ($SQL_where) {
			if ($this->enable_prepare) {
				$this->sql .= " WHERE %sql_prepare%";
			}
			else {
				$this->sql .= " WHERE ".implode(" AND ", $SQL_where);
			}
		}
		if ($SQL_groupby) $this->sql .= " GROUP BY ".implode(",", $SQL_groupby);
		if ($SQL_having) $this->sql .= " HAVING ".implode(",", $SQL_having);
		if ($SQL_union)	$this->sql = "SELECT * FROM ({$this->sql} UNION ".implode(" UNION ", $SQL_union).") AS zz";
		if ($SQL_union_where) $this->sql .= " WHERE ".implode(" AND ", $SQL_union_where);
		if ($SQL_orderby) $this->sql .= " ORDER BY ".implode(",", $SQL_orderby);

		// limit
		if ($this->limit) {
			$limit = $this->limit;
			$this->sql = $this->connection->getsql_top($this->sql, $limit);
		}

		if ($options["buffer"]) {
			$this->connection = \core::db("pri.buffer");
			$this->connection->selectbuffer($this->sql);
		}
		else {
			// fetch results
			if ($this->enable_prepare) {
				$this->sql_prepare = "SELECT{$SQL_calc_rows} {$this->key} FROM ".implode($SQL_from);
				if ($SQL_where) $this->sql_prepare .= " WHERE ".implode(" AND ", $SQL_where);
				if ($SQL_orderby) $this->sql_prepare .= " ORDER BY ".implode(",", $SQL_orderby);

				$item_id_arr = $this->connection->selectsinglelist($this->sql_prepare, [
					"offset" => $offset,
					"count" => $limit,
				]);
				$this->item_total = $this->connection->get_row_count();

				if ($item_id_arr) {
					$this->sql = strtr($this->sql, ["%sql_prepare%" => $this->connection->getsql_in($item_id_arr, $this->key)]);
					$this->item_arr = $this->connection->select($this->sql);
				}
			}
			else {
				$this->item_arr = $this->connection->select($this->sql, $offset, $limit);
				$this->item_total = $this->connection->get_row_count();
			}

			// filter items
			if ($this->item_filter_arr) {
				foreach ($this->item_arr as $item_index => $item_item) {
					foreach ($this->item_filter_arr as $item_filter_item) {
						if ($item_filter_item($item_item) !== false) continue;

						unset($this->item_arr[$item_index]);
						$this->item_total--;
						break;
					}
				}
			}
		}

		/*
			// generate and run query
			if ($this->huge_result) {
				$this->sql = "$select$from$where$having$groupby"; // assemble sql query
				if ($this->navigation_type == "page") $this->sql = $this->connection->getsql_limit($this->sql, $orderby, $offset, $limit);
				$this->items = $this->connection->select($this->sql); // retrieve items from database
				$this->total_count = $this->connection->selectsingle("SELECT COUNT(*)$from$where$having$groupby"); // retrieve total item count
			}
		*/
	}
	//--------------------------------------------------------------------------------
	protected function init_pages() {
		// check if pages enabled
		if (!$this->page_size || !$this->enable_nav) return;

		$this->page_total = \com\num::getpage($this->page_size, $this->item_total);
		if (($this->item_total % $this->page_size) == 0 && $this->item_total > 0) $this->page_total -= 1;
		if ($this->navigation_type == "page") {
			$this->page_current = \com\num::getpage($this->page_size, $this->offset);
		}
	}
	//--------------------------------------------------------------------------------
	protected function init_url() {
		$this->url = $_SERVER["QUERY_STRING"];
		if (!preg_match("/^(\\?|index\\.php)/i", $this->url)) $this->url = "?$this->url";

		// add panel to url
		if (strpos($this->url, "&p=") === false) $this->url .= '&p='.\core::$panel;
	}
	//--------------------------------------------------------------------------------
	public function init() {
		// init
		if (!$this->is_init) {
			// make sure we use page navigation when filtering in quickind
			if ($this->quickfind) $this->navigation_type = "page";

			$this->init_url();
			$this->init_items();
			$this->init_pages();
			$this->init_custom_nav();
			$this->is_init = true;

			// filter for similar field values
			if ($this->similar_field && $this->similar_value && $this->similar_diff) {
				foreach ($this->item_arr as $item_index => $item) {
					$diff = levenshtein($item[$this->similar_field], $this->similar_value);
					if ($diff > $this->similar_diff) {
						unset($this->item_arr[$item_index]);
						$this->item_total--;
					}
					else $this->item_arr[$item_index]["__matchrating"] = $diff;
				}
				if ($this->item_arr) {
					$this->add_field("Match rating", "__matchrating", ["#text-align" => "center"]);
					\LiquidedgeApp\Octoapp\app\app\arr\arr::sort($this->item_arr, "__matchrating");
				}
			}
		}

		/*


		if ($this->auto_columns && $this->items) {
			foreach ($this->items[0] as $fieldname => $column_value) $this->add_column($fieldname, $fieldname);
		}
		*/

	}
	//--------------------------------------------------------------------------------
	protected function display_nav() {
		$this->display_nav2($this);
	}
	//--------------------------------------------------------------------------------
	protected function display_legend() {
		return;
	}
	//--------------------------------------------------------------------------------
	protected function display_headers() {

		if ($this->custom_table_headers){
		    $this->table_head_open();
		    $options["*"] = $this->custom_table_headers;
			$this->table_head_th($options);
			return;
        }

		// check for header flag
		if (!$this->enable_headers) return;

		//--------------------
		// open table head
		$this->table_head_open();

		//--------------------
		// action header
		if ($this->action_arr) {
			$this->table_head_th(["*" => "&nbsp;", ".ui-table-cell-actions" => true]);
		}

		//--------------------
		// checkbox header
		if ($this->checkbox_field) {
			$HTML_checkbox = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->icheckbox("{$this->id}_chkall", 1, false, false, ["!click" => "$('#{$this->id}').comlist('togglecheck');", "@noserialize" => ($this->serialize_checkboxes ? false : "noserialize")]);
			$this->table_head_th(["*" => $HTML_checkbox, ".ui-table-cell-chk" => true]);
		}

		//--------------------
		// number header
		if ($this->enable_numbers) {
			$this->table_head_th(["*" => "#", ".ui-table-cell-rownr" => true]);
		}

		if (\com\release::is_active("!CRMDEV-19769 .core")) {

		}
		else {
			if (\core::$app->get_instance()->get_option("com.core.config.enable_uitable") && $this->enable_config) {
				//--------------------
				// arrange columns
				$config_class = \com\core\config\uitable::make(["cfg_name" => $this->cache_id]);
				$this->field_arr = $config_class->prepare_fields($this->field_arr);
			}
		}

		//--------------------
		// field headers
		foreach ($this->field_arr as $field_index => $field_item) {
			//--------------------
			// save items
			if ($this->save_items) {
				$this->items_save[-1][$field_item["field"]] = [
					"value" => iconv(\core::$app->get_instance()->get_charset(), "WINDOWS-1252//TRANSLIT//IGNORE", strip_tags($field_item["header"])),
					"uid" => $field_item["uid"]
				];
				continue;
			}

			//--------------------
			// options and content
			$options = [];
			$content = $field_item["header"];

			//--------------------
			// styles
			if ($field_item["#width"]) $options["#width"] = $field_item["#width"];

			//--------------------
			// format styles
			$this->table_td_format($field_item, $options);

			//--------------------
			// alignment
			if ($field_item["#text-align"]) $options["#text-align"] = $field_item["#text-align"];

			//--------------------
			// filter
			if ($field_item["input"]) {
				$content = $field_item["input"];
				$this->enable_sorting = false;
			}

			//--------------------
			// sorting
			if (!$this->enable_sorting || $field_item["nosort"]) {
				$options["#cursor"] = "default";
			}
			else {
				$new_ordertype = 0;
				if ($this->sortfield == $field_index) {
					$content .= (!$this->sortorder ? " &uarr;" : " &darr;");
					$new_ordertype = (int)!$this->sortorder;
				}

				$options["@title"] = "Order by ".strtolower($field_item["header"])." ".(!$new_ordertype ? "ascending" : "descending");
				$options["!click"] = "$('#{$this->id}').comlist('sort', { field: $field_index, order: $new_ordertype });";
				$options["#cursor"] = "pointer";
			}

			//--------------------
			// tooltip
			if ($field_item["headtooltip"]) {
				$options["@title"] = $field_item["headtooltip"];
				$options["@id"] = \com\session::$current->session_uid;
				\LiquidedgeApp\Octoapp\app\app\ui\ui::make()->tooltip();
			}

			//--------------------
			// shrink
			if ($field_item["shrink"]) {
				$options[".ui-table-cell-fit"] = true;
			}

			//--------------------
			// responsive
			if ($field_item["hide_for"]) {
				$options[".hidden-{$field_item["hide_for"]}"] = true;
			}

			//--------------------
			// classes
			$class_arr = (preg_filter("/^\\./", "$1", array_keys($field_item)));
			foreach ($class_arr as $class_item) {
				$options[".{$class_item}"] = true;
			}

			//--------------------
			// identifier
			if ($field_item["uid"]) {
				$options["@data-uid"] = $field_item["uid"];
			}

			//--------------------
			// view
			$options["*"] = $content;
			$this->table_head_th($options);
		}

		//--------------------
		// ordering header
		if ($this->ordering_event_js) {
			$this->table_head_th(["*" => "&nbsp;", ".ui-table-cell-fit" => true, ".ui-table-cell-ordering" => true]);
		}

		//--------------------
		// table head close
		$this->table_head_close();
	}
	//--------------------------------------------------------------------------------
	protected function display_controlbreak($controlbreak) {
		//--------------------
		// skip when current step is the same
		if (!$this->controlbreak_lookup && (isnull($controlbreak) || $controlbreak === "0" || $controlbreak === 0)) $controlbreak = "None";
		if ($this->controlbreak_current !== false && $this->controlbreak_current == $controlbreak) return false;

		//--------------------
		// set current step
		$this->controlbreak_current_text = $this->controlbreak_current = $controlbreak;

		//--------------------
		// trim
		if ($this->controlbreak_trim) $this->controlbreak_current_text = substr($this->controlbreak_current, $this->controlbreak_trim);

		//--------------------
		// lookup
		if ($this->controlbreak_lookup) $this->controlbreak_current_text = $this->controlbreak_lookup[$this->controlbreak_current_text];

		//--------------------
		// render control break row
		echo \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->tag()->tr_();

		$count = count($this->field_arr);
		if ($this->action_arr) $count++;
		if ($this->checkbox_field) $count++;
		if ($this->enable_numbers) $count++;
		echo \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->tag()->td(".ui-table-row-head *{$this->controlbreak_current_text}", ["@colspan" => $count]);

		echo \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->tag()->_tr();

		//--------------------
		// headers
		if ($this->controlbreak_headerrepeat) $this->display_headers();

		return true;
	}
	//--------------------------------------------------------------------------------
	protected function display_items() {
		//--------------------
		// open table
		$this->table_open(!$this->item_arr);

		//--------------------
		// no items
		if (!$this->item_arr) {
			if ($this->enable_headers) $this->display_headers();

			if ($this->message_empty) {
				echo \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->tag()->tr_();
				echo \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->tag()->td_(".ui-table-legend-light ^{$this->message_empty}", [
					"@colspan" => "100%",
					"html" => $this->message_empty_is_html,
				]);
				echo \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->tag()->_td();
				echo \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->tag()->_tr();
			}

			$this->table_close();
			return;
		}

		//--------------------
		// control break
		if ($this->controlbreak_field && !$this->controlbreak_headerrepeat) {
			$this->display_headers();
			$this->table_body_open();
		}
		if ($this->controlbreak_field) {
			$first_item = \LiquidedgeApp\Octoapp\app\app\arr\arr::get_first_value($this->item_arr);
			$this->display_controlbreak($first_item[$this->controlbreak_field]);
			if ($this->save_items) {
				$this->items_save[-1][$this->controlbreak_field] = [
					"value" => ($this->controlbreak_name ? $this->controlbreak_name : $this->controlbreak_field),
					"uid" => false,
				];
			}
		}
		else {
			// non control break headers
			$this->display_headers();
			$this->table_body_open();
		}

		//--------------------
		// items
		//$this->table_body_open();

		$first_row = true;
		$item_nr = 0;
		$item_pagenr = ($this->navigation_type == "alpha" || $this->offset == -1 ? 0 : $this->offset); // @todo: why -1?
		foreach ($this->item_arr as $item_index => $item) {
			//--------------------
			// control break
			if ($this->controlbreak_field) {
				$first_row = $this->display_controlbreak($item[$this->controlbreak_field]);
				if ($this->save_items) {
					$this->items_save[$item_index][$this->controlbreak_field] = [
						"value" => iconv(\core::$app->get_instance()->get_charset(), "WINDOWS-1252//TRANSLIT//IGNORE", strip_tags($this->controlbreak_current_text)),
						"uid" => false,
					];
				}
			}

			//--------------------
			// init
			$item_nr++;
			$item_pagenr++;

			//--------------------
			// legend
			$legend_class = false;
			if ($this->legend_arr) {
				if (!isnull($item["__legend"]) && $item["__legend"]) {
					$legend_class = $this->get_legend_class($item["__legend"]);
					$legend_class = ".ui-table-legend-{$legend_class}";
				}
			}
			if ($this->legend_arr2) {
				foreach ($this->legend_arr2 as $legend_index => $legend_item) {
					if (!$legend_item["fn_filter"]($item)) continue;
					$legend_class = ".ui-table-legend-{$legend_index}";
					break;
				}
			}

			//--------------------
			// start row
			$row_options = [];
			if ($legend_class) {
				$row_options[$legend_class] = true;
			}
			// id
			if ($this->key && isset($item[$this->key])) {
				$row_options["@row-id"] = $item[$this->key];
			}
			$this->table_row_open($row_options);

			//--------------------
			// actions
			if ($this->action_arr) {
				// init
				$content = false;
				$options = [];

				// build action icons specified with the add_action function
				$last_index = \LiquidedgeApp\Octoapp\app\app\ui\arr::get_last_index($this->action_arr);
				foreach ($this->action_arr as $action_index => $action_item) {
					// determine if action is visible
					$filter_check = true;
					if ($action_item["filter"]) {
						$filter_check = (is_callable($action_item["filter"]) ? $action_item["filter"]($item) : $item["__actionfilter{$action_index}"]);
					}

					// show action
					if ($filter_check) {
						$variable_replace_options = [];
						if ($action_item["escape"]) $variable_replace_options["strtr"] = [$action_item["escape"] => "\\".$action_item["escape"]];
						$action_item["!click"] = \com\str::variable_replace($action_item["!click"], $action_item["__click"], $item, $variable_replace_options);
						$action_item["name"] = \com\str::variable_replace($action_item["name"], $action_item["__name"], $item, $variable_replace_options);
						$content .= \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->iconbutton($action_item["name"], $action_item["!click"], $action_item["icon"], [".me-2" => $last_index != $action_index]);
					}
					else $content .= \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->iconbutton(false, false, false, ["#opacity" => "0"]);
				}

				// render the actions
				$options["*"] = $content;
				$options[".ui-table-cell-actions"] = true;
				$this->table_row_td($options);
			}

			//--------------------
			// checkbox
			if ($this->checkbox_field) {
				// init
				$options = [];

				// show checkboxes
				$checkboxes_visible = true;

				// custom callback per item
				if (is_callable($this->checkbox_visibility_callback)) {
					$checkboxes_visible = call_user_func($this->checkbox_visibility_callback, $item);
				}

				// build checkbox
				if ($checkboxes_visible) {
					$HTML_checkbox = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->icheckbox("{$this->id}_chk[" . ($item_nr - 1) . "]", $item[$this->checkbox_field], false, false, ["@noserialize" => ($this->serialize_checkboxes ? false : "noserialize")]);
					$options = [
						".ui-table-cell-chk" => true,
						"*" => $HTML_checkbox,
					];
				}

				// apply to row
				$this->table_row_td($options);
			}

			//--------------------
			// number
			if ($this->enable_numbers) {
				$options = [
					".ui-table-cell-rownr" => true,
					"^" => $item_pagenr,
				];
				$this->table_row_td($options);
			}

			//--------------------
			// fields
			foreach ($this->field_arr as $field_index => $field_item) {
				//--------------------
				// options
				$options = [];
				$content = false;

				//--------------------
				// template
				if ($field_item["template"]) {
					$content = \com\str::variable_replace($field_item["field"], $field_item["__template"], $item);
				}
				else {
					//--------------------
					// value
					$content = $item[$field_item["field"]];
				}

				//--------------------
				// total
				if ($field_item["total"]) {
					$this->total_arr[$field_item["field"]] += $item[$field_item["field"]];
				}

				//--------------------
				// unique id
				if ($field_item["id"]) {
					$options["@id"] = ($field_item["id"] === true ? \com\session::$current->session_uid : $field_item["id"]);
					$this->item_arr[$item_index]["__id"] = $options["@id"];
				}

				//--------------------
				// styles
				if ($field_item["#width"]) $options["#width"] = $field_item["#width"];

				//--------------------
				// field handler
				if ($field_item["function"]) {
					$this->__current_options = &$options;
					$this->item_arr[$item_index][$field_item["field"]] = $content = call_user_func($field_item["function"], $content, $item_index, $field_index, $this);
					// function($content, $item_index, $field_index, $list) {}
					if ($field_item["function_refresh_data"]) {
						$item = $this->item_arr[$item_index];
					}
				}

				//--------------------
				// function: fn_value
				if ($field_item["fn_value"]) {
					$this->item_arr[$item_index][$field_item["field"]] = $content = $field_item["fn_value"]($item);
				}

				//--------------------
				// format styles
				$this->table_td_format($field_item, $options);

				//--------------------
				// format value
				switch ($field_item["format"]) {
					case DB_SECONDS :
						$content = (is_numeric($content) ? \com\num::hms($content) : $content);
						break;

					case DB_MINUTES :
						$content = (is_numeric($content) ? \com\num::hm($content) : $content);
						break;

					case DB_MILISECONDS :
						$content = (is_numeric($content) ? \com\num::hmsm($content) : $content);
						break;

					case DB_DURATION :
						$content = (is_numeric($content) ? \com\num::duration($content) : $content);
						break;

					case DB_BYTES :
						$content = (is_numeric($content) ? \com\num::kbmbgb($content) : $content);
						break;

					case DB_INT :
						$content = (is_numeric($content) ? number_format($content, 0, ".", " ") : $content);
						break;

					case DB_DECIMAL :
						if (is_numeric($content)) {
							if ($field_item["decimals"] !== false) {
								$content = number_format($content, $field_item["decimals"], ".", "");
							}
							else {
								$content = $content * 1;
							}
						}
						break;

					case DB_PERCENTAGE :
						$content *= 1;
						$content = (is_numeric($content) ? round($content, 1)."%" : $content);
						break;

					case DB_DATE :
						$content = \com\data::format_html($content, DB_DATE);
						break;

					case DB_DATETIME :
						$content = \com\data::format_html($content, DB_DATETIME);
						break;

					case DB_YEARMONTH :
						$content = \com\data::format_html($content, DB_YEARMONTH);
						break;

					case DB_CURRENCY :
						$content = (is_numeric($content) ? \com\num::currency($content, ["decimals" => $field_item["decimals"], "trim" => 2, "include_symbol" => !$field_item["hide_currency_symbol"]]) : $content);
						break;

					case DB_BOOL :
						if (isnull($content)) {
							$content = "";
						}
						else {
							if ($this->save_items) $content = ($content ? "Yes" : "No");
							else {
								$content = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->icon(($content ? ":ok" : ":notok"));
							}
						}
						break;

					case DB_KEY :
						if (\com\user::auth_for("DEV")) {
							$key_field = $field_item["field"];
							if (preg_match("/^.+\\./i", $this->key)) {
								//$key_field = substr($key_field, 1);
							}
							$key_config = \com\db::get_field_detail($key_field);
							if ($key_config && !isnull($content)) {
								$content = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->link("#", $content, [
									"!click" => \core::$app->get_request()->get_panel().".popup('?c=xnova.reference/vmanage&table={$key_config["table"]}&id={$content}')",
								]);
							}
						}
						break;
				}

				//--------------------
				// lookup
				if ($field_item["lookup"]) {
					if ($content) {
						if ($field_item["lookup"] instanceof \com\intf\factory) {
							$lookup_class = $field_item["lookup"]->get($content);
							if ($lookup_class) $content = $lookup_class->get_name();
						}
						elseif (isset($field_item["lookup"][$content])) {
							$content = $field_item["lookup"][$content];
						}
					}
					else {
						$content = $field_item["falsevalue"];
					}
				}

				//--------------------
				// set
				if ($field_item["set"]) $content = implode(", ", \com\misc::get_bit_values($content, $field_item["set"]));

				//--------------------
				// false value replacement
				if ($field_item["falsevalue"] && (!$content || isnull($content) || $content === "None")) $content = $field_item["falsevalue"];

				//--------------------
				// null value replacement
				if (isnull($content)) $content = ($field_item["nullvalue"] ? $field_item["nullvalue"] : "");

				//--------------------
				// new line to br
				if ($field_item["nl2br"]) $content = nl2br($content);


				//--------------------
				// max length
				if ($field_item["maxlength"]) {
					if (strlen($content) > $field_item["maxlength"]) $options["@title"] = htmlentities($content);
					$content = \com\str::wrap_string($content, $field_item["maxlength"]);
				}

				//--------------------
				// alignment
				if ($field_item["#text-align"]) $options["#text-align"] = $field_item["#text-align"];

				//--------------------
				// row class
				if (isset($item["__class"])) $options[".{$item["__class"]}"] = true;

				//--------------------
				// onclick
				if ($field_item["!click"]) {
					$options["@onclick"] = \com\str::variable_replace($field_item["!click"], $field_item["__click"], $item);
					$options["#cursor"] = "pointer";
				}

				//--------------------
				// tooltip
				if ($field_item["tooltip"]) {
					$text = \com\str::variable_replace($field_item["tooltip"], $field_item["__tooltip"], $item);
					if ($text) {
						$options["@title"] = $text;
						\LiquidedgeApp\Octoapp\app\app\ui\ui::make()->tooltip();
					}
				}

				//--------------------
				// shrink
				if ($field_item["shrink"]) $content = strtr($content, [" " => "&nbsp;"]);

				//--------------------
				// vertical align
				if ($field_item["#vertical-align"]) $options["#vertical-align"] = $field_item["#vertical-align"];

				//--------------------
				// responsive
				if ($field_item["hide_for"]) {
					$options[".hidden-{$field_item["hide_for"]}"] = true;
				}

				//--------------------
				// replace tag
				if ($field_item["replace_tag"]) {
					foreach ($field_item["replace_tag"] as $tag_item) {
						$result = $tag_item["fn_filter"]($item);
						if (!$result) continue;
						$tag_text = ($result === true ? $content : $result);
						$content = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->badge($tag_text, $tag_item["color"], array_merge(["#position" => "relative", "#bottom" => "2px"], $tag_item["options"]));
					}
				}

				//--------------------
				// add tags
				if ($field_item["add_tags"]) {
					foreach ($field_item["add_tags"] as $tag_item) {
						$result = $tag_item["fn_filter"]($item);
						if (!$result) continue;
						$tag_text = ($result === true ? $tag_item["label"] : $result);
						$content .= \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->badge($tag_text, $tag_item["color"], array_merge(["#margin-left" => "5px", "#position" => "relative", "#bottom" => "2px"], $tag_item["options"]));
					}
				}

				//--------------------
				// add notes
				if ($field_item["add_notes"]) {
					foreach ($field_item["add_notes"] as $note_item) {
						$result = $note_item["fn_filter"]($item);
						if (!$result) continue;
						$note_text = ($result === true ? $note_item["note"] : $result);
						$content .= \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->table_note($note_text, $note_item["options"]);
					}
				}

				//--------------------
				// view
				if ($this->save_items) {
					$this->items_save[$item_index][$field_item["field"]] = [
						"value" => iconv(\core::$app->get_instance()->get_charset(), "WINDOWS-1252//TRANSLIT//IGNORE", strip_tags($content)),
						"uid" => $field_item["uid"],
					];
				}
				else {
					$options["*"] = $content;
					$this->table_row_td($options);
				}
			}

			//--------------------
			// ordering
			if ($this->ordering_event_js) {
				// render the actions
				if (!isset($item["__orderfilter"]) || $item["__orderfilter"]) {
					$options["*"] = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->icon("fa-sort", [".me-2" => false]);
					$options[".ui-table-cell-order pointer"] = true;
					$options["#width"] = "20px";
				}
				else $options["*"] = "";
				$this->table_row_td($options);
			}

			//--------------------
			// table: end
			$this->table_row_close();
		}

		// total
		if ($this->total_arr && $this->item_arr) {
			// display total row
			$this->table_row_open([".ui-table-row-total" => true]);
			if ($this->action_arr) $this->table_row_td();
			if ($this->enable_numbers) $this->table_row_td();
			if ($this->checkbox_field) $this->table_row_td();
			foreach ($this->field_arr as $field_item) {
				unset($field_item["!click"]);

				if (isset($this->total_arr[$field_item["field"]])) $content = $this->total_arr[$field_item["field"]];
				else $content = "";
				$content = (is_numeric($content) && !$field_item["total_no_currency"] ? \com\num::currency($content) : $content);

				$total_options = [
					"^" => $content,
				];
				$this->table_td_format($field_item, $total_options);

				$this->table_row_td(array_merge($field_item, $total_options));
			}
			$this->table_row_close();
		}

		// close table
		$this->table_body_close();
		$this->table_close();
	}
	//--------------------------------------------------------------------------------
	public function display_filter() {
		// check if filter enabled
		if (!$this->filter_function) return;

		// view
		$this->htms->end_column();
		$HTML_state = (!$this->is_filtered ? " list-filter-hide" : false);
		echo \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->tag()->div_("#{$this->id}_filter .list-filter{$HTML_state}");
		$this->htms->header_width = false;
		$this->htms->header(3, "Advanced filter");
		$this->htms->button("apply filter", "$this->id.filter_apply();");
		$this->htms->button("clear filter", "$this->id.filter_toggle();");
		call_user_func_array($this->filter_function, [$this]);
		$this->htms->end_column();
		echo \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->tag()->_div();
	}
	//--------------------------------------------------------------------------------
	public function display() {
		if (\com\release::is_active("!CRMDEV-19769 .core")) {
			if (\core::$app->get_instance()->get_option("com.core.config.enable_uitable") && $this->enable_config) {
				// arrange columns
				$config_class = \com\core\config\uitable::make(["cfg_name" => $this->cache_id]);
				$this->field_arr = $config_class->prepare_fields($this->field_arr, ["has_custom_fields" => $this->has_custom_fields()]);
			}
		}

		// init
		$this->init();
		$this->init_append_url();

		// dont show list when there are nothing to display
		if (!$this->enable_emptyview && !$this->item_arr) return;

		// open wrapper
		$this->wrapper_open($this->id, $this->margin);

		// header
		if ($this->header) echo $this->header;

		// nav
		$this->display_nav();

		// filter
		$this->display_filter();

		// event: on_display_legend
		if ($this->on_display_legend) call_user_func_array($this->on_display_legend, [$this]);

		// legend
		$this->display_legend();

		// items
		if ($this->checkbox_field) {
			echo \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->form("{$this->id}_form", false, $this->form_options);
		}
		$this->display_items();
		if ($this->checkbox_field) echo \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->tag()->_form();

		// close wrapper
		$this->wrapper_close();

		// additional forms
		$JS_form = ($this->append_form ? ", form: ".json_encode($this->append_form) : false);

		// script
		$JS_url = \com\str::escape_singlequote($this->url);
		$JS_panel = "#panel_".\core::$panel;
		$JS_params = $this->get_requests_json($this->request_arr);
		$JS_csrf = \core::$app->get_response()->get_csrf();
		$JS_uid_arr = json_encode($this->uid_arr);

		if (\com\release::is_active("!CRMDEV-19769 .core")) {
			$JS_field_arr = json_encode(array_values($this->get_list_fields()));
			$config_class = \com\core\config\uitable::make(["cfg_name" => $this->cache_id]);
			$JS_config_data = json_encode($config_class->get_config()->cfg_data ?? false);

			\com\js::add_script("$('#{$this->id}').comlist({
				url: '{$JS_url}{$this->append_url}{$this->append_url_custom}',
				panel: '{$JS_panel}',
				navigation: '{$this->navigation_type}',
				csrf: '{$JS_csrf}',
				params: {$JS_params},
				uid_arr: {$JS_uid_arr},
				field_arr: {$JS_field_arr},
				config_data: {$JS_config_data}
				{$JS_form}
			});");
		}
		else {
			\com\js::add_script("$('#{$this->id}').comlist({
				url: '{$JS_url}{$this->append_url}{$this->append_url_custom}',
				panel: '{$JS_panel}',
				navigation: '{$this->navigation_type}',
				csrf: '{$JS_csrf}',
				params: {$JS_params},
				uid_arr: {$JS_uid_arr}
				{$JS_form}
			});");
		}

		// ordering
		if ($this->ordering_event_js) {
			$JS_event = \com\str::escape_singlequote($this->ordering_event_js);
			\com\js::add_script("
				$('#{$this->id} table tbody').sortable({
					axis: 'y',
					containment: 'parent',
					distance: 10,
					tolerance: 'pointer',
					handle: '.ui-table-cell-order',
					revert: 50,
					scroll: true,
					stop: function(event, ui) {
						var js = '{$JS_event}';
						var id = $(ui.item).attr('row-id');
						var prev_id = $(ui.item).prev().attr('row-id')

						if (!id) {
							$(this).sortable('cancel');
							return;
						}

						js = js.replace('%id%', id);
						js = js.replace('%prev_id%', prev_id);
						eval(js);
					}
				});
			");
		}

		if(!$this->item_arr){
		    \com\js::add_script("
		        $('#{$this->id} .ui-table-cell-actions').hide();
		    ");
        }

		if($this->quickfind && $this->quickfind_mark_text && !$this->quickfind_fuzzy){
			\LiquidedgeApp\Octoapp\app\app\js\js::add_script($this->get_highlight_script($this->quickfind));
        }

	}
	//--------------------------------------------------------------------------------
    public function get_highlight_script($search_text, $options = []) {

	    $options = array_merge([
	        "target" => "#{$this->id} .ui-table tbody"
	    ], $options);

	    $js_options = array_merge([
	        "*q" => $search_text,
	        "*delay" => false,
	        "*easing" => false,
	        "*className" => "ui-highlight",
	        "*element" => "span",
	        "*caseSensitive" => false,
	        "*diacriticSensitive" => false,
	        "*wordsOnly" => false,
	        "*fullText" => false,
	        "*minLength" => false,
        ], $options);

	    $js_options = \LiquidedgeApp\Octoapp\app\app\js\js::create_options($js_options);

        return "
            $('{$options["target"]}').highlighter({$js_options});
        ";
    }
	//--------------------------------------------------------------------------------
	public function stream_buffer($options = []) {
		// options
		$options = array_merge([
			"filename" => "list-export.csv",
			"include_legends" => false,
		], $options);

		// file
		$file = new \com\file("php://output/{$options["filename"]}", ["timestamp" => true]);
		$file->add_filter("null", "-");
		$file->encoding = \com\data::ENCODE_WIN;

		// headers
		$header_arr = [];

		// legends
		if ($options["include_legends"]) {
			foreach ($this->legend_arr as $legend_index => $legend_item) {
				$this->field_arr[] = ["header" => $legend_item["description"], "field" => "__legend_{$legend_index}", "uid" => false];
			}
		}

		if (\core::$app->get_instance()->get_option("com.core.config.enable_uitable") && $this->enable_config) {
			//--------------------
			// arrange columns
			$config_class = \com\core\config\uitable::make(["cfg_name" => $this->cache_id]);
			if (\com\release::is_active("!CRMDEV-19769 .core")) {
				$this->field_arr = $config_class->prepare_fields($this->field_arr, ["has_custom_fields" => $this->has_custom_fields()]);
			}
			else {
				$this->field_arr = $config_class->prepare_fields($this->field_arr);
			}
		}

		// add headers
		foreach ($this->field_arr as $field_item) {
			$header_arr[] = $field_item["header"];
		}

		$file->write_csv($header_arr);

		// items
		$this->init_dbitems(["buffer" => true]);
		while ($row = $this->connection->selectrow()) {
			// init
			$item = [];

			// build and format each column for writing
			foreach ($this->field_arr as $field_index => $field_item) {
				// legends
				if ($options["include_legends"] && substr($field_item["field"], 0 , 9) == "__legend_") {
					// init
					$value = (bool)(isset($this->legend_arr[$row["__legend"]])) && $row["__legend"] == substr($field_item["field"], 9);

					// add
					$item[] = \com\db::$list_bool[$value];
					continue;
				}

				// init
				$value = $row[$field_item["field"]];

				// field handler
				if ($field_item["function"]) {
					$this->item_arr[0] = $row;
					$value = strip_tags($field_item["function"]($value, 0, $field_index, $this));
				}

				// function: fn_value
				if ($field_item["fn_value"]) {
					$value = $field_item["fn_value"]($row);
				}

				// formatting
				switch ($field_item["format"]) {
					case DB_DATE : $value = \com\date::strtodate($value); break;
					case DB_DATETIME : $value = \com\date::strtodatetime($value); break;
					case DB_YEARMONTH : $value = \com\date::strtodate($value, "Y-m"); break;
					case DB_BOOL : $value = ($value ? "Yes" : "No"); break;
				}

				// lookup
				if ($value && $field_item["lookup"]) {
					if ($field_item["lookup"] instanceof \com\intf\factory) {
						$lookup_class = $field_item["lookup"]->get($value);
						if ($lookup_class) $value = $lookup_class->get_name();
					}
					elseif (isset($field_item["lookup"][$value])) {
						$value = $field_item["lookup"][$value];
					}
				}

				// false value replacement
				if ($field_item["falsevalue"] && (!$value || isnull($content))) {
					$value = $field_item["falsevalue"];
				}

				// null value replacement
				if (isnull($content)) {
					$value = ($field_item["nullvalue"] ? $field_item["nullvalue"] : "");
				}

				// add item
				$item[] = $value;
			}

			// write the line
			$file->write_csv($item);

			// flush output buffer
			ob_flush();
		}
	}
	//--------------------------------------------------------------------------------
	public function stream($filename = false) {
		// init
		$options = [];
		$options["write"] = true;
		$options["timestamp"] = true;
		if (!$filename) {
			$filename = "export.csv";
		}
		$this->enable_nav = false;

		// generate items
		$this->save_items = true;
		ob_start();
		$this->display();
		ob_end_clean();
		$this->save_items = false;

		if (\core::$app->get_instance()->get_option("com.core.config.enable_uitable") && $this->enable_config) {
			// ensure array exists
			if (empty($this->items_save)) {
				$this->items_save = [];
			}

			//--------------------
			// arrange columns
			$config_class = \com\core\config\uitable::make(["cfg_name" => $this->cache_id]);
			foreach ($this->items_save as $index => $item) {
				if (\com\release::is_active("!CRMDEV-19769 .core")) {
					$this->items_save[$index] = $config_class->prepare_fields($item, ["has_custom_fields" => $this->has_custom_fields()]);
				}
				else {
					$this->items_save[$index] = $config_class->prepare_fields($item);
				}
			}
		}

		// file
		$file = new \com\file("php://output/$filename", $options);
		$header_arr = [];
		if ($this->controlbreak_field) $header_arr[$this->controlbreak_field] = $this->controlbreak_field;
		foreach ($this->field_arr as $field_item) $header_arr[$field_item["field"]] = $field_item["header"];

		// stream items
		foreach ($this->items_save as $item) {
			$write_item = [];
			foreach ($header_arr as $header_index => $header_item) {
				$write_item[] = $item[$header_index]["value"];
			}
			$file->write_csv($write_item);
		}

		// end
		$file->end();
	}
	//--------------------------------------------------------------------------------
	// @todo engine functions
	//--------------------------------------------------------------------------------
	protected function table_open($is_empty = false) {
		echo \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->tag()->div_($this->table_wrapper_options);

		$this->table_options[".table table-bordered table-hover"] = true;
		$this->table_options[".mb-0"] = $is_empty;

		echo \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->tag()->table_($this->table_options);
	}
	//--------------------------------------------------------------------------------
	protected function table_head_open() {
		echo \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->tag()->thead_(".thead-dark");
		echo \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->tag()->tr_();
	}
	//--------------------------------------------------------------------------------
	protected function table_head_close() {
		echo \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->tag()->_tr();
		echo \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->tag()->_thead();
	}
	//--------------------------------------------------------------------------------
	protected function wrapper_open($id, $margin) {

		$this->table_options["@id"] = $id;
		$this->table_options[".ui-table"] = true;
		$this->table_options["#margin"] = $margin;

		echo \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->tag()->div_($this->table_options);
	}
	//--------------------------------------------------------------------------------
	protected function wrapper_close() {
		echo \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->tag()->_div();
	}
	//--------------------------------------------------------------------------------
	protected function table_close() {
		echo \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->tag()->_table();
		echo \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->tag()->_div();
	}
	//--------------------------------------------------------------------------------
	protected function table_head_th($options = []) {
		$options = array_merge($this->th_options, $options);
		echo \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->tag()->th(false, $options);
	}
	//--------------------------------------------------------------------------------
	protected function table_body_open() {
		echo \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->tag()->tbody_();
	}
	//--------------------------------------------------------------------------------
	protected function table_body_close() {
		echo \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->tag()->_tbody();
	}
	//--------------------------------------------------------------------------------
	protected function table_row_open($options = []) {
		echo \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->tag()->tr_(false, $options);
	}
	//--------------------------------------------------------------------------------
	protected function table_row_close() {
		echo \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->tag()->_tr();
	}
	//--------------------------------------------------------------------------------
	protected function table_row_td($options = []) {
		$options = array_merge($this->td_options, $options);
		echo \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->tag()->td(false, $options);
	}
	//--------------------------------------------------------------------------------
	/**
	 * @param \com\ui\intf\table $comtable
	 */
	protected function display_nav2($comtable) {
		// toolbar
		$toolbar = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->toolbar();
		//$toolbar->set_button_max(false);

		// navigation
		if ($comtable->enable_nav) {
			// item count
			if ($this->enable_item_count) {
				$toolbar->add_text("<strong>{$comtable->item_total}</strong> {$comtable->item_label}");
			}

			// existing html toolbar
			if ($comtable->html && $comtable->html->toolbar) {
				$clone_toolbar = clone $comtable->html->toolbar;
				$toolbar->add_toolbar($clone_toolbar);
				$comtable->html->toolbar = false;
			}

			// grouped actions
			foreach ($this->action_group_arr as $action_group_item) {
				$toolbar->add_button($action_group_item["label"], $action_group_item["dropdown"], ["icon_right" => "fas-caret-down"]);
			}

			// custom navigation
			if ($comtable->nav_append) {
				if (is_callable($comtable->nav_append)) call_user_func_array($comtable->nav_append, [$comtable, $toolbar]);
				else $toolbar->add($comtable->nav_append, ["html" => true]);
			}

			// add new button
			if ($comtable->addnew_url) {
				$toolbar->add_button($comtable->addnew_label, \core::$panel.".requestUpdate('{$comtable->addnew_url}');", ["icon" => "plus"]);
			}

			// add new button with onlick
			if ($comtable->addnew_onclick) {
				$toolbar->add_button($comtable->addnew_label, $comtable->addnew_onclick, ["icon" => "plus"]);
			}

			// quickfind filter
			if ($comtable->quickfind_field || $comtable->quickfind_where || $comtable->quickfind_exact_field_arr) {
				// reset button
				$toolbar->add_button("reset", "$('#{$comtable->id}').comlist('reset');", [".d-none d-md-block" => $this->has_advanced_filters,]);

				// find textbox
				$toolbar->add(\LiquidedgeApp\Octoapp\app\app\ui\ui::make()->itext("{$comtable->id}_quickfind", $comtable->quickfind, false, [
				    "/form_input" => [".mb-2" => false],
					"focus" => (bool)$comtable->quickfind,
					"width" => "large",
					".d-none d-md-block" => $this->has_advanced_filters,
					"@placeholder" => $this->quickfind_placeholder,
					"!enter" => "$('#{$comtable->id}').comlist('quickfind');",
					"/wrapper" => [".d-flex align-items-center" => true]
				]));
			}

			// date filter
			if ($comtable->datefilter_field && !$this->has_advanced_filters) {
//				$toolbar->add(\app\ui::make()->idate("{$comtable->id}_datefrom", $comtable->datefrom, false, [
//					"@placeholder" => "Date from",
//					"!enter" => "$('#{$comtable->id}').comlist('datefind');",
//				]));
//				$toolbar->add(\app\ui::make()->idate("{$comtable->id}_dateto", $comtable->dateto, false, [
//					"@placeholder" => "Date to",
//					"!enter" => "$('#{$comtable->id}').comlist('datefind');",
//				]));


				$buffer = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();
				$buffer->div_([".d-block d-md-flex align-items-center" => true]);
					$buffer->div_([".me-2" => true]);
						$buffer->xidate("{$comtable->id}_datefrom", $comtable->datefrom, false, [
							"@placeholder" => "Date from",
							"!enter" => "$('#{$comtable->id}').comlist('datefind');",
							"#width" => "115px",
							"min_date" => false,
							"!change" => "
								{$comtable->id}_dateto.setNewStartDate($('#{$comtable->id}_datefrom').val());
								{$comtable->id}_dateto.setDate('');
								$('#{$comtable->id}_dateto').val('');
							",
						]);
					$buffer->_div();
					$buffer->div_([".me-2" => true]);
						$buffer->xidate("{$comtable->id}_dateto", $comtable->dateto, false, [
							"@placeholder" => "Date to",
							"#width" => "115px",
							"!enter" => "$('#{$comtable->id}').comlist('datefind');",
							"min_date" => false,
						]);
					$buffer->_div();
					$buffer->xbutton("Apply", "$('#{$comtable->id}').comlist('datefind');");

					if($this->datefrom || $this->dateto)
						$buffer->xbutton("Clear", "
							$('#{$comtable->id}_datefrom').val('');
							$('#{$comtable->id}_dateto').val('');
							$('#{$comtable->id}').comlist('datefind');
						", [".ms-1" => true]);
				$buffer->_div();
				$toolbar->add($buffer->build());

			}

			// filter
			if ($comtable->filter_function) {
				$toolbar->add_button("filter", "{$comtable->id}.filter_toggle();");
			}

			// navigation
			if ($comtable->page_size) {
				switch ($comtable->navigation_type) {
					case "page" :
						// do not display page navigation if we have only one page
						if ($comtable->page_total < 2) break;

						// page counter
						$toolbar->add_divider();
						if ($comtable->offset == -1) $toolbar->add("<strong>All</strong> pages");
						else $toolbar->add_text("Page <strong>{$comtable->page_current}</strong> of <strong>{$comtable->page_total}</strong>");

						// display page numbers when we have less then 11 pages
						$toolbar->add_divider();
						if ($comtable->page_total < 7) {
							$toolbar->add_button_group();
							for ($i = 0; $i < $comtable->page_total; $i++) {
								$label = $i + 1;
								$offset = $i * $comtable->page_size;
								$toolbar->add_button($label, "$('#{$comtable->id}').comlist('page', { offset: {$offset} });", ["selected" => ($comtable->page_current == $label)]);
							}
							$toolbar->close_button_group();
						}
						else {
							// first page
							$toolbar->add_button_group();
							$toolbar->add_button("", "$('#{$comtable->id}').comlist('page');", [
								"@disabled" => ($comtable->page_current == 1),
								"icon" => "fas-fast-backward",
								"tooltip" => "First page",
							]);

							// previous page
							$previous_page_offset = (($comtable->page_current - 2) * $comtable->page_size);
							$toolbar->add_button("", "$('#{$comtable->id}').comlist('page', { offset: {$previous_page_offset} });", [
								"@disabled" => ($comtable->page_current == 1),
								"icon" => "fas-step-backward",
								"tooltip" => "Previous page",
							]);
							$toolbar->close_button_group();

							// page selection
							if ($comtable->page_total <= 30) {
								$page_arr = [];
								for ($i = 0; $i < $comtable->page_total; $i++) $page_arr[$i * $comtable->page_size] = $i + 1;
								$toolbar->add(\LiquidedgeApp\Octoapp\app\app\ui\ui::make()->iselect("{$comtable->id}_pageselect", $page_arr, (($comtable->page_current - 1) * $comtable->page_size), false, [
									"#min-width" => "70px",
									"width" => "small",
									"!change" => "$('#{$comtable->id}').comlist('page', { offset: $(this).val() });",
									"/wrapper" => [".d-flex align-items-center" => true, ".mb-2" => false],
								]));
							}
							else {
								$toolbar->add(\LiquidedgeApp\Octoapp\app\app\ui\ui::make()->itext("{$comtable->id}_pageselect", $comtable->page_current, false, [
									"limit" => "numeric" ,
									".text-center" => true,
									"width" => "tiny",
									"/wrapper" => [".d-flex align-items-center" => true, ".mb-2" => false],
									"!enter" => "var value = parseInt($(this).val()); if (value > 0 && value <= {$comtable->page_total}) $('#{$comtable->id}').comlist('page', { offset: (value - 1) * {$comtable->page_size} });"
								]));
							}

							// next page
							$toolbar->add_button_group();
							$next_page_offset = ($comtable->page_current * $comtable->page_size);
							$toolbar->add_button("", "$('#{$comtable->id}').comlist('page', { offset: {$next_page_offset} });", [
								"@disabled" => ($comtable->page_current == $comtable->page_total),
								"icon" => "fas-step-forward",
								"tooltip" => "Next page",
							]);

							// last page
							$last_page_offset = ($comtable->page_total - 1) * $comtable->page_size;
							$toolbar->add_button("", "$('#{$comtable->id}').comlist('page', { offset: {$last_page_offset} });", [
								"@disabled" => ($comtable->page_current == $comtable->page_total),
								"icon" => "fas-fast-forward",
								"tooltip" => "Last page",
							]);
							$toolbar->close_button_group();
						}
						break;

					case "alpha" :
						// group buttons
						$toolbar->add_button_group();

						// add ab, cd alphabetical options
						foreach (range('A', 'Z', 2) as $letter) {
							$next_letter = chr(ord($letter) + 1);
							$toolbar->add_button("{$letter}{$next_letter}", "$('#{$comtable->id}').comlist('page', { offset: '{$letter}' });", ["selected" => ($letter == $comtable->offset)]);
						}

						// add special characters option
						$toolbar->add_button("#", "$('#{$comtable->id}').comlist('page', { offset: '#' });", ["selected" => ($comtable->offset == "#")]);

						$toolbar->close_button_group();
						break;
				}
			}

			// custom navigation at end
			if ($comtable->nav_append_custom) {
				if (is_callable($comtable->nav_append_custom)) call_user_func_array($comtable->nav_append_custom, [$comtable, &$toolbar]);
				else $toolbar->add($comtable->nav_append_custom, ["html" => true]);
			}

			// custom navigation at end
			if ($comtable->nav_append_end) {
				if (is_callable($comtable->nav_append_end)) call_user_func_array($comtable->nav_append_end, [$comtable, &$toolbar]);
				else $toolbar->add($comtable->nav_append_end, ["html" => true]);
			}

		}

		// settings
		if (\com\release::is_active("!CRMDEV-19769 .core")) {
			if (\core::$app->get_instance()->get_option("com.core.config.enable_uitable") && $this->enable_config) {
				$toolbar->add_button(false, \core::$panel.".popup('?c=config/vmanage&cfg_name={$this->cache_id}&context=uitable&list_id={$this->id}&has_custom_fields={$this->has_custom_fields()}');", ["icon" => "fa-cog", "tooltip" => "Configure"]);
			}
		}
		else {
			if (\core::$app->get_instance()->get_option("com.core.config.enable_uitable") && $this->enable_config && count($this->field_arr) > 1) {
				$toolbar->add_button(false, \core::$panel.".popup('?c=config/vmanage&cfg_name={$this->cache_id}&context=uitable&list_id={$this->id}');", ["icon" => "fa-cog", "tooltip" => "Configure"]);
			}
		}

		// legend
		if ($comtable->legend_arr && $comtable->enable_legend) {
			$html = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();
			$html->div_(".form-control-plaintext .ui-table-legend-wrapper .d-flex .p-0");
			foreach ($comtable->legend_arr as $legend_index => $legend_item) {
				$html->div(".ui-table-legend-indicator .ui-table-legend-{$legend_index}");
				$html->div(".ui-table-legend-label .ui-table-legend-{$legend_index} ^{$legend_item["description"]}");
			}
			$html->_div();
			$toolbar->add($html, [".ms-auto" => true]);
		}
		elseif ($comtable->legend_arr2 && $comtable->enable_legend) {
			$html = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();
			$html->div_(".form-control-plaintext .ui-table-legend-wrapper .d-flex .p-0");
			foreach ($comtable->legend_arr2 as $legend_index => $legend_item) {
				$html->div(".ui-table-legend-indicator .ui-table-legend-{$legend_index}");
				$html->div(".ui-table-legend-label .ui-table-legend-{$legend_index} ^{$legend_item["description"]}");
			}
			$html->_div();
			$toolbar->add($html, [".ms-auto" => true]);
		}

		echo $toolbar->build([".mb-2" => true]);
	}
	//--------------------------------------------------------------------------------
	/**
	 * @param $table
	 * @param $index
	 * @param array $options
	 * @return \com\db\row|\com\db\table
	 */
	public function get_dbt($table, $index, $options = []) {

		if(isset($this->item_arr[$index]["dbt_{$table}"])) return $this->item_arr[$index]["dbt_{$table}"];

		return $this->item_arr[$index]["dbt_{$table}"] = \core::dbt($table)->get_fromarray($this->item_arr[$index]);

	}
	//--------------------------------------------------------------------------------
	protected function table_td_format(&$field_item, &$options) {
		switch ($field_item["format"]) {
			case DB_SECONDS :
			case DB_BYTES :
			case DB_MINUTES :
			case DB_MILISECONDS :
			case DB_DURATION :
			case DB_INT :
			case DB_CURRENCY :
			case DB_KEY :
				$options[".ui-table-cell-numeric"] = true;
				break;

			case DB_PERCENTAGE :
			case DB_DECIMAL :
				$options[".ui-table-cell-decimal"] = true;
				break;

			case DB_DATE :
			case DB_DATETIME :
			case DB_YEARMONTH :
				$options[".ui-table-cell-date"] = true;
				break;

			case DB_BOOL :
				$options[".ui-table-cell-bool"] = true;
				break;
		}
	}
}