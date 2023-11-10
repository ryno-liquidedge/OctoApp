<?php

namespace action\developer\property_config;

/**
 * Class vlist
 * @package action\system\setup\property_config
 * @author Ryno Van Zyl
 */

class vlist implements \com\router\int\action {

	private $sms = false;

	//--------------------------------------------------------------------------------
	use \com\router\tra\action;

	//--------------------------------------------------------------------------------
	public function auth() { 
		return \core::$app->get_token()->check("dev");
	}

	//--------------------------------------------------------------------------------
	public function run() {

	    if(!file_exists(\core::$folders->get_app_db()."/db.property_config.php")){
	        echo \app\ui::make()->message("Please install property table before you continue.");
	        return "stream";
        }

	    $this->prc_table = $this->request->get('prc_table', \com\data::TYPE_STRING, ["trusted" => true]);

		$list = \com\ui::make()->table();

		$list->key = "prc_id";
		$list->sql_select = "*";
		$list->sql_from = "property_config";
		$list->set_sql($this->get_sql());
		$list->quickfind_field = \core::db()->getsql_concat(["prc_display", "prc_code", "prc_key"]);
		if($this->prc_table) $list->append_url= "&prc_table={$this->prc_table}";
		$list->nav_append_end = function($table, $toolbar){
		    $toolbar->add("Filter Table", [".align-self-center" => true]);
		    $toolbar->add(\com\ui::make()->iselect("prc_table", [null => "-- Not Selected --"] + \db_property_config::get_table_list(["sql_where" => \core::db()->getsql_in(["settings"], "prc_table", false, true)]), $this->prc_table, false, [
		        "!change" => "{$this->request->get_panel()}.refresh(null, {data: $('.ui-table .btn-toolbar *').serialize() })",
		        ".min-w-200px" => true,
                "/wrapper" => [".mb-2" => false],
            ]));
        };

		// fields
		$list->add_field("Name", "prc_display");
		$list->add_field("Code", "prc_code");
		$list->add_field("File", "id", ["function" => function($content, $item_index, $field_index, $list){
		    $property_config = \core::dbt("property_config")->get_fromarray($list->item_arr[$item_index]);
		    $solid = $property_config->get_solid();
		    $text = "app.solid.property_set.solid_classes.{$solid->get_table_name()}.{$solid->get_class()}.php";
		    return  \app\ui::make()->link("javascript:core.util.copy_text('{$text}', {br2nl: true,})", $solid->get_class());
        }]);

		$fn_toggle_field = function($title, $field)use(&$list){
            $list->add_field($title, "id", ["#text-align" => "center", "function" => function($content, $item_index, $field_index, $list) use($field){
                $property_config = \core::dbt("property_config")->get_fromarray($list->item_arr[$item_index]);
                return \app\ui::make()->iswitch("{$field}_{$property_config->id}", false, (bool)$property_config->{$field}, false, [
                    "/wrapper" => [".d-inline-block" => true, ".mb-2" => false,],
                    "!click" => \app\js::ajax("?c=developer.property_config.functions/xtoggle_field/{$property_config->prc_id}&field={$field}")
                ]);
            }]);
        };

		$fn_toggle_field("Is Enabled", "prc_is_enabled");
		$fn_toggle_field("Is Editable", "prc_is_editable");
		$fn_toggle_field("Allow External Override", "prc_allow_external_override");
		$fn_toggle_field("Allow Logging", "prc_allow_logging");


		$html = \com\ui::make()->html();
		$html->header(2, "Property Config");
		$html->display($list);

    }
	//--------------------------------------------------------------------------------
    public function get_sql() {
        $sql = \app\db\sql\select::make();
        $sql->select("prc_id AS id");
        $sql->select("property_config.*");

        $sql->from("property_config");

        $sql->and_where(\core::db()->getsql_in(["settings"], "prc_table", false, true));

        if($this->prc_table) $sql->and_where(\core::db()->getsql_in([$this->prc_table], "prc_table"));

        return $sql;
    }
	//--------------------------------------------------------------------------------
}
