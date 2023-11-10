<?php

namespace action\developer\database;

/**
 * @package action\developer\database
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class vlist implements \com\router\int\action {

    /**
     * @var \app\ui\set\bootstrap\table
     */
    protected $table;
    //--------------------------------------------------------------------------------
    use \com\router\tra\action;
    //--------------------------------------------------------------------------------
    // functions
    //--------------------------------------------------------------------------------
    public function auth() {
        return \core::$app->get_token()->check("dev");
    }
    //--------------------------------------------------------------------------------
    public function run() {

    	$this->filter = $this->request->get('filter', \com\data::TYPE_STRING, ["default" => "has_update"]);
        $this->existing_tables_list = \com\db::get_tables();

		// html
		$html = \com\ui::make()->html();
		$html->header(2, "SQL Builder");

		// list
		$this->table = \com\ui::make()->table();

		$this->table->key = "name";
		$this->table->nav_append_end = $this->custom_nav();
		$this->table->enable_numbers = false;
		$this->table->quickfind_field = "name";
		$this->table->nav_append_end = function($table, $toolbar){
			$toolbar->add_button("Generate Config", "{$this->request->get_panel()}.requestRefresh('?c=developer.database.functions/xgenerate_config')");
			$toolbar->add_button("Download DB Creation SQL", "document.location='?c=developer.database.functions/xsql'");
			$toolbar->add(\com\ui::make()->iselect("filter", ["all" => "Show All", "has_update" => "Has Update"], $this->filter, false, ["!change" => "{$this->request->get_panel()}.refresh(null, {data: {filter:$('#filter').val()} })", "/wrapper" => [".mb-2" => false, ".min-w-200px" => true]]));
		};
		$this->table->item_arr = $this->get_item_arr();

		$this->table->add_field("Table", "name", ["!click" => "$('#%name%').click()"]);
		$this->table->add_field("Options", "__key", ["function" => function($content, $item_index, $field_index, $list) {

		    $name = $list->item_arr[$item_index]["name"];
		    $has_update = $list->item_arr[$item_index]["has_update"];

		    $toolbar = \com\ui::make()->toolbar();
		    if(!$has_update){
		        $toolbar->add_button("Create SQL", "{$this->request->get_panel()}.popup('?c=developer.database/vcreate_sql&name=$name');", ["icon" => "fa-plus-square"]);
            }
		    if($has_update && !in_array($name, $this->existing_tables_list)){
                $toolbar->add_button("Create SQL", "{$this->request->get_panel()}.popup('?c=developer.database/vcreate_sql&name=$name');", ["icon" => "fa-plus-square"]);
            }else if($has_update){
                $toolbar->add_button("Alter SQL", "{$this->request->get_panel()}.popup('?c=developer.database/valter_sql&name=$name');", [".btn-info" => true, "icon" => "fa-edit"]);
            }
		    $toolbar->add_button_group();
		    return $toolbar->build();

        }]);
		$this->table->add_field("", "id", ["function" => function($content, $item_index, $field_index, $list) {
			$has_update = $list->item_arr[$item_index]["has_update"];
			return $has_update ? \com\ui::make()->badge("Has Update", "green", [".font-12" => true]) : "-";
        }]);

		// display
		$html->display($this->table);
    }
    //--------------------------------------------------------------------------------
	private function get_item_arr(){

		$item_arr = [];

		// get the reference information
        $table_arr = [];
        foreach (glob(\core::$folders->get_app()."/db/*") as $table_class){
            $table = str_replace(["db.", ".php"], "", basename($table_class));
            $table_arr[$table] = $table;
        }

		foreach ($table_arr as $table => $references_table_arr) {

			if(!file_exists(\core::$folders->get_app()."/db/db.{$table}.php")){
				continue;
			}

            $sql = \com\db::getsql_alter_table($table);
			$sql = trim(str_replace(["-- $table", "--"], "", $sql));

			if($this->filter == "has_update" && $sql == "") continue;

			$item_arr[$table] = [
				"__key" => $table,
				"key" => $table,
				"name" => $table,
				"id" => $table,
				"has_update" => $sql != "",
			];
		}

		return $item_arr;
	}
    //--------------------------------------------------------------------------------
	public function custom_nav() {

		$toolbar = \com\ui::make()->toolbar();
		$toolbar->add_button("", "document.location='?c=install/xsql'", ["@title" => "Download DB Create SQL", "icon" => "fa-download", "@class" => "btn btn-success"]);
		$toolbar->add_button("", "{$this->request->get_panel()}.requestRefresh('?c=developer.database.functions/xgenerate_config')", ["@title" => "Rebuild Config File", "icon" => "fa-refresh", "@class" => "btn btn-info"]);
		$toolbar->add_button("refresh", "{$this->request->get_panel()}.refresh()", ["icon" => "fa-refresh", "@class" => "btn btn-success"]);
//		$toolbar->add(\com\ui::make()->iselect("filter", ["all" => "Show All", "has_update" => "Has Update"], $this->filter, false, ["!change" => "{$this->request->get_panel()}.refresh(null, {data:$('.ui-table *').serialize(), csrf:'{$this->request->get_csrf()}'})"]));
		return $toolbar->build();
	}
  	//--------------------------------------------------------------------------------
  	public function request($id, $default = false){

        $filter_arr = array_merge([
            $id => $default
        ], $this->session->get("{$this->table->cache_id}-filters", []));

        $value = \core::$app->get_request()->get($id, \com\data::TYPE_STRING, ["default" => $filter_arr[$id], "trusted" => true]);

        if($value !== $default){
            $filter_arr[$id] = $value;
            $this->session->{"{$this->table->cache_id}-filters"} = $filter_arr;
        }

        return $value;
    }
    //--------------------------------------------------------------------------------
}
