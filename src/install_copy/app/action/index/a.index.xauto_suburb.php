<?php


namespace action\index;

/**
 * @package action\website\index\functions
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class xauto_suburb implements \com\router\int\action {
    
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

		$sub_name = \core::db()->getsql_concat([
			"sub_name",
			"(CASE WHEN tow_name IS NULL THEN '' ELSE ( CONCAT(', ', tow_name) ) END)",
			"(CASE WHEN prv_name IS NULL THEN '' ELSE ( CONCAT(', ', prv_name) ) END)",
			"(CASE WHEN con_name IS NULL THEN '' ELSE CONCAT(', ', con_name) END)",
		]);

		$sql = \com\db\sql\select::make();
		$sql->select("suburb.*");
		$sql->select("town.*");
		$sql->select("province.*");
		$sql->select("country.*");
		$sql->select("$sub_name AS formatted_name");

		$sql->from("suburb");
		$sql->from("LEFT JOIN town ON (sub_ref_town = tow_id)");
		$sql->from("LEFT JOIN province ON (tow_ref_province = prv_id)");
		$sql->from("LEFT JOIN country ON (tow_ref_country = con_id)");

		$sql->and_where("con_code IN ('ZA')");
		$sql->or_where(\com\db::getsql_find($term, $sub_name, ["find_glue" => "AND"]));
		$sql->top(15);

		$town_data_arr = \core::db()->select($sql->build());

		if(!$town_data_arr) return \app\http::json([["id" => 0, "value" => "No results found"]]);

		$autocomplete_arr = [];
		array_walk($town_data_arr, function($data, $key)use(&$autocomplete_arr){
			$autocomplete_arr[] = [
			    "id" => $data["sub_id"],
                "value" => $data["formatted_name"],

                "sub_id" => $data["sub_id"],
                "sub_name" => $data["sub_name"],
                "sub_residential_code" => $data["sub_residential_code"],

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