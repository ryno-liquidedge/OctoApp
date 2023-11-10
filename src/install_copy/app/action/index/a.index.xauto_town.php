<?php


namespace action\index;

/**
 * @package action\website\index\functions
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class xauto_town implements \com\router\int\action {
    
	//--------------------------------------------------------------------------------
	use \com\router\tra\action;
    //--------------------------------------------------------------------------------
    public function auth() {
    	return true;
    }
    //--------------------------------------------------------------------------------
    public function run () {
        
        // request params
		$term = $this->request->get('term', \com\data::TYPE_STRING, ["get" => true]);
		$term = explode(",", str_replace(", ", ",", $term));

		$tow_name = \core::db()->getsql_concat([
			"tow_name",
			"(CASE WHEN prv_name IS NULL THEN '' ELSE ( CONCAT(', ', prv_name) ) END)",
			"(CASE WHEN con_name IS NULL THEN '' ELSE CONCAT(', ', con_name) END)",
		]);

		$sql = \com\db\sql\select::make();
		$sql->select("town.*");
		$sql->select("province.*");
		$sql->select("country.*");
		$sql->select("$tow_name AS formatted_name");

		$sql->from("town");
		$sql->from("LEFT JOIN province ON (tow_ref_province = prv_id)");
		$sql->from("LEFT JOIN country ON (tow_ref_country = con_id)");

		$sql->and_where("con_code IN ('ZA')");
		$sql->or_where(\com\db::getsql_find($term, $tow_name, ["find_glue" => "AND"]));
		$sql->top(15);

		$town_data_arr = \core::db()->select($sql->build());

		if(!$town_data_arr) return \app\http::json([["id" => 0, "value" => "No results found"]]);

		$autocomplete_arr = [];
		array_walk($town_data_arr, function($data, $key)use(&$autocomplete_arr){
			$autocomplete_arr[] = [
			    "id" => $data["tow_id"],
                "value" => $data["formatted_name"],

                "tow_id" => $data["tow_id"],
                "tow_name" => $data["tow_name"],

                "prv_id" => $data["prv_id"],
                "prv_name" => $data["prv_name"],

                "con_id" => $data["con_id"],
                "con_name" => $data["con_name"],
            ];
		});

		return \app\http::json($autocomplete_arr);
    }
	//--------------------------------------------------------------------------------
}
    
?>