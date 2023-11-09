<?php

namespace LiquidedgeApp\Octoapp\app\app\db\tra;

/**
 * Trait table
 * @package app\db\tra
 */

trait table {

    /**
     * @var \LiquidedgeApp\Octoapp\app\app\db\sql\select
     */
    private $sql_builder;

    //--------------------------------------------------------------------------------

    /**
     * @param $obj
     * @param array $options
     * @return false|string
     */
    public function get_property_table($obj, $options = []) {

        if(!property_exists($this, "property_table"))
            return false;

        return $this->property_table;
    }
    //--------------------------------------------------------------------------------
    public function load_properties($obj, $options = []) {

        $options = array_merge([
            "force" => false,
        ], $options);


        if(property_exists($obj, "property_arr") && $obj->property_arr && !$options["force"]){
            return $obj->property_arr;
        }else{
            $obj->property_arr = [];
            $property_table = $this->get_property_table($obj);

            if($property_table){
                $property_dbt = \core::dbt($property_table);
                $field = $property_dbt->get_prefix()."_ref_{$this->name}";

                $sql = \LiquidedgeApp\Octoapp\app\app\db\sql\select::make();

                $sql->select("{$property_table}.*");
                $sql->from($property_table);
                $sql->and_where("{$field} = {$obj->{$this->key}}");
                $sql->extract_options($options);

                $obj->property_arr = $property_dbt->get_fromsql($sql->build(), ["multiple" => true]);
            }
        }
    }
    //--------------------------------------------------------------------------------
    public function get_property($obj, $key, $options = []) {

        $options = array_merge([
            "force" => false,
        ], $options);

        $property_arr = $this->get_property_arr($obj, $key, $options);

        return reset($property_arr);

    }
    //--------------------------------------------------------------------------------
    public function has_property($obj, $key, $options = []) {

        $options = array_merge([
            "force" => true,
        ], $options);

        return (bool) $this->get_property($obj, $key, $options);

    }
    //--------------------------------------------------------------------------------
    public function save_property($obj, $key, $value = false, $options = []) {

        $options = array_merge([
            "force" => true,
            "audit" => true,
            "trace" => true,
        ], $options);

        if(isempty($value)) return;

        $property_table = $this->get_property_table($obj);
        $property = $this->get_property($obj, $key, $options);
        $solid = \LiquidedgeApp\Octoapp\app\app\solid\solid::get_instance($key);

        if(!$solid) return \LiquidedgeApp\Octoapp\app\app\error\error::create("Solid class for '$key' was not found");

        if(!$property && $property_table) {
            $property = $property_dbt = \core::dbt($property_table)->get_fromdefault();
            $property->{$property_dbt->get_prefix()."_ref_{$this->name}"} = $obj->id;
            $property->{$property_dbt->get_prefix()."_key"} = $key;
        }

        $property->{$property->db->get_prefix()."_value"} = $solid->parse($value);
        $field_value_arr = \LiquidedgeApp\Octoapp\app\app\arr\arr::extract_signature_items(".", $options);
        foreach ($field_value_arr as $field => $field_value){
            $property->{$field} = $field_value;
        }
        $property->audit = $options["audit"];
        $property->trace = $options["trace"];
        $property->save();

    }
    //--------------------------------------------------------------------------------
	/**
	 * @param $obj
	 * @return \app\solid\property_set\intf\standard|mixed
	 */
	public function get_solid_class($obj) {
		return \LiquidedgeApp\Octoapp\app\app\solid\solid::get_instance($obj->{$obj->get_prefix()."_key"});
	}
    //--------------------------------------------------------------------------------
    public function get_prop($obj, $key, $options = []) {

        $solid = \LiquidedgeApp\Octoapp\app\app\solid\solid::get_instance($key);

        $options = array_merge([
            "force" => false,
            "default" => $solid->get_default(),
            "format" => false,
        ], $options);

        if($obj->is_empty($this->key)) return $options["default"];

        if($options["force"]){
        	$property_table = $this->get_property_table($obj);
			$property_dbt = \core::dbt($property_table);
			$field = $property_dbt->get_prefix()."_ref_{$this->name}";

			$sql = \LiquidedgeApp\Octoapp\app\app\db\sql\select::make();
			$sql->select("{$property_table}.*");
			$sql->from($property_table);
			$sql->and_where("{$field} = {$obj->{$this->key}}");
			$sql->and_where("{$property_dbt->get_prefix()}_key = ".dbvalue($key));
			$sql->extract_options($options);

			$property = $property_dbt->get_fromsql($sql->build());
			if($property){
				if(!property_exists($obj, "property_arr")) $obj->property_arr = [];
				$obj->property_arr[$property->id] = $property;
			}

		}else{
			$property = $this->get_property($obj, $key, $options);
		}


        if($property){
        	if($options["format"]) return $solid->format($property->{$property->db->get_prefix()."_value"});
            return $solid->parse($property->{$property->db->get_prefix()."_value"});
        }

        return $options["default"];
    }
    //--------------------------------------------------------------------------------
    public function delete_prop($obj, $key, $options = []) {
    	$options = array_merge([
    	    "force" => true,
    	], $options);

    	$property = $this->get_property($obj, $key, $options);

    	if($property){

    		$property->messages = false;
    		$property->delete();

    		if(isset($obj->property_arr[$property->id]))
    			unset($obj->property_arr[$property->id]);
		}
	}
    //--------------------------------------------------------------------------------

    /**
     * @param $obj
     * @param bool $key
     * @param array $options
     * @return array
     */
    public function get_property_arr($obj, $key = false, $options = []) {

        $options = array_merge([
            "force" => false,
        ], $options);

        $property_table = $this->get_property_table($obj);
        if(!$property_table) \LiquidedgeApp\Octoapp\app\app\error\error::create("{$this->name} does not have a property table configured.");

        $this->load_properties($obj, $options);

        if($key){
            $property_dbt = \core::dbt($property_table);
            $options[".{$property_dbt->get_prefix()}_key"] = $key;
        }else return $obj->property_arr;

        if($property_table) {
            $property_dbt = \core::dbt($property_table);
            return array_filter($obj->property_arr, function($property)use($key, $property_dbt){
                return $property->{$property_dbt->get_prefix()."_key"} == $key;
            });
        }

        return [];

    }
    //--------------------------------------------------------------------------------
    public function format_name($obj, $format = false, $options = []) {

        return html_entity_decode($this->get_name($obj));
    }
    //--------------------------------------------------------------------------------
    public function get_name($obj, $options = []) {

        return str_replace("''", "'", $obj->name);
    }
    //--------------------------------------------------------------------------------
    public function sanitize_field_arr(&$obj, $options = []) {

        foreach ($obj->db->field_arr as $name => $data){
    		if(in_array($data[2], [DB_TEXT, DB_STRING, DB_HTML]) && !$obj->is_empty($name)){
    			$obj->{$name} = $this->sanitize_value($obj->{$name});
			}
		}
    }
    //--------------------------------------------------------------------------------
    public function sanitize_value($str, $options = []) {

        return  str_replace("’", "'", $str);

    }
    //--------------------------------------------------------------------------------
    public function encode_field_arr(&$obj, $options = []) {

        foreach ($this->field_arr as $name => $data){
    		if(in_array($data[2], [DB_TEXT, DB_STRING, DB_HTML]) && !$obj->is_empty($name)){
    			$obj->{$name} = \LiquidedgeApp\Octoapp\app\app\data\data::dbvalue(htmlentities($obj->{$name}), false);
			}
		}
    }
    //--------------------------------------------------------------------------------
    public function decode_field_arr(&$obj, $options = []) {

        foreach ($obj->db->field_arr as $name => $data){
    		if(in_array($data[2], [DB_TEXT, DB_STRING, DB_HTML]) && !$obj->is_empty($name)){
    			$obj->{$name} = str_replace("''", "'", html_entity_decode($obj->{$name}));
			}
		}
    }
	//--------------------------------------------------------------------------------
	public function is_unique($obj) {
		// params
		$obj = $this->splat($obj);

		// sql
		$sql = \LiquidedgeApp\Octoapp\app\app\db\sql\select::make();
		$sql->select($this->key);
		$sql->from($this->name);
		$sql->and_where("{$this->display} = ".dbvalue($obj->{$this->display}));

		// existing product
		if (!$obj->is_empty($this->key)) $sql->and_where("{$this->key} <> '$obj->id'");

		// check for unique username
		return !(bool)\core::db()->selectsingle($sql->build());
	}
    //--------------------------------------------------------------------------------
    public function decode_obj(&$obj, $options = []) {

		if (isset($options["multiple"])) {
			foreach ($obj as $d) $this->decode_field_arr($d);
		} else if ($obj) {
			$this->decode_field_arr($obj);
		}

    }
    //--------------------------------------------------------------------------------
    public function encode_obj(&$obj, $options = []) {
        $this->encode_field_arr($obj, $options);
    }
    //--------------------------------------------------------------------------------
    public function enum_arr($obj, $field, $unset_index = true, $options = []) {

    	$arr = $this->{$field};

    	if(is_array($unset_index)){
    		foreach ($unset_index as $key) unset($arr[$key]);
		}else if($unset_index === true){
    		\LiquidedgeApp\Octoapp\app\app\arr\arr::unset_first_index($arr);
		}

    	return $arr;
    }
    //--------------------------------------------------------------------------------
    public function get_last_inserted_id() {

        $sql = \LiquidedgeApp\Octoapp\app\app\db\sql\select::make();
        $sql->select("MAX($this->key)");
        $sql->from("$this->name");

        return intval(\core::db()->selectsingle($sql->build()));
    }
    //--------------------------------------------------------------------------------
    public function get_next_id() {

        $sql = \LiquidedgeApp\Octoapp\app\app\db\sql\select::make();
        $sql->select("AUTO_INCREMENT");
        $sql->from("information_schema.TABLES");
        $sql->and_where("TABLE_SCHEMA = '".\core::$app->get_instance()->get_db_name()."'");
        $sql->and_where("TABLE_NAME = '$this->name'");

        return intval(\core::db()->selectsingle($sql->build()));
    }
    //--------------------------------------------------------------------------------
    public function get_slug($obj, $options = []) {

    	if(!property_exists($this, "slug")) return false;

        return $obj->{$this->slug};
    }
    //--------------------------------------------------------------------------------
    public function get_slug_name($obj, $options = []) {

    	if(!property_exists($this, "slug")) return false;

        return $obj->{$this->slug};
    }
    //--------------------------------------------------------------------------------
    public function get_seo_name($obj, $options = []) {
        return $this->get_slug_name($obj);
    }

    //--------------------------------------------------------------------------------
    public function get_fromslug($options = []) {

        if(!property_exists($this, "slug")) return false;

        $slug = \LiquidedgeApp\Octoapp\app\app\http\http::get_slug();

        return \core::dbt($this->name)->find([
            ".{$this->slug}" => $slug
        ]);

    }
    //--------------------------------------------------------------------------------
    public function get_fromseo($options = []) {
        return $this->get_fromslug($options);
    }
    //--------------------------------------------------------------------------------

    /**
     * Select a single field
     * @param $field
     * @param $where
     * @return bool|string
     */
    public function selectsingle($mixed, $field = false) {

        // params
        if (!$field) $field = $this->display;

        $sql = \LiquidedgeApp\Octoapp\app\app\db\sql\select::make();
        $sql->select($field);
        $sql->from($this->name);

        if (is_numeric($mixed)) {
            $sql->and_where("$this->key = '$mixed'");
        } else {
            $sql->and_where($mixed);
        }

        $value = \core::db($this->database)->selectsingle($sql->build());

        // with new sql driver 4.3.0+ -- hex2bin is done automatically
        if (!\core::$instance->get_option("com.db.disable_hex2bin")) {
            // conversions based on field
            switch ($this->field_arr[$field][2]) {
                case DB_BINARY :
                    if (!isnull($value)) $value = hex2bin($value);
                    break;
            }
        }

        // done
        return $value;
    }
    //--------------------------------------------------------------------------
    public function delete_entity_property($obj, $property_reference_field, $property_key_field, $key, $options = []) {

        $reference_table = \com\db\lib\config::get_table(substr($property_reference_field, 0, 3));
        if($reference_table){
            $property = \core::dbt($reference_table)->find([
                ".$property_key_field" => str_replace("_", ".", $key),
                ".$property_reference_field" => $obj->id,
            ]);

            if($property) $property->delete();
        }
    }
    //--------------------------------------------------------------------------
    /**
     * @param $obj
     * @param $property_table
     * @param $property_reference_field
     * @param array $options
     * @return \com\db\row|\com\db\row[]|\com\db\table|\com\db\table[]
     */
    public function get_entity_property_arr($obj, $property_table, $property_reference_field, $options = []) {
		$options_arr = array_merge([
			"sql_where" => false,
			"exclude_brp_key_arr" => [],
			"include_brp_key_arr" => [],
		], $options);

		$property_table = \core::dbt($property_table);

		if(property_exists($obj, "property_arr")) return $obj->property_arr;

		$sql = \LiquidedgeApp\Octoapp\app\app\db\sql\select::make();
		$sql->where("AND", "$property_reference_field = $obj->id");
		if($options_arr["sql_where"])$sql->where("AND", $options_arr["sql_where"]);

		$property_arr = \core::dbt($property_table->name)->get_fromdb("{$sql->get_parts()["where"]}", ["multiple" => true]);

        return $obj->property_arr = $property_arr;
    }
    //--------------------------------------------------------------------------------
	/**
	 * @param $obj
	 * @param array $field_arr
	 * @param array $options
	 * @return \com\db\row|\com\db\row[]|\com\db\table|\com\db\table[]|false
	 * @throws \Exception
	 */
    public function decrypt_fields(&$obj, $field_arr = [], $options = []) {

    	if(!$field_arr) return $obj;

    	$fn_decrypt = function(&$obj) use (&$field_arr){
    		if($obj){
				foreach ($field_arr as $field){
					if(!$obj->is_empty($field)) $obj->{$field} = \com\str::decrypt_r($obj->{$field});
				}
			}
		};

		if(isset($options["multiple"])){
			foreach ($obj as $key => $o) $fn_decrypt($o);
		}else{
			$fn_decrypt($obj);
		}

		return $obj;

    }
    //--------------------------------------------------------------------------------
	/**
	 * @param $obj
	 * @param array $field_arr
	 * @param array $options
	 * @return mixed
	 * @throws \Exception
	 */
    public function encrypt_fields(&$obj, $field_arr = [], $options = []) {

    	if(!$field_arr) return $obj;

    	$fn_decrypt = function(&$obj) use (&$field_arr){
    		foreach ($field_arr as $field){
				if(!$obj->is_empty($field)) $obj->{$field} = \com\str::encrypt_r($obj->{$field});
			}
		};

		$fn_decrypt($obj);

		return $obj;

    }
    //--------------------------------------------------------------------------------
    public function splat_identifier($obj, $options = []) {

    	if(!$obj->db->string) return false;

		return $obj->{$obj->db->string};
    }
    //--------------------------------------------------------------------------------

	/**
	 * @param $obj
	 * @param $table
	 * @param array $options
	 * @return false|\com\db\row
	 */
    public function get_reference_obj($obj, $table, $options = []) {
		$options = array_merge([
    	    "field" => false,
    	], $options);

    	if($options["field"]){
			if($obj->{$table}) return $obj->{$table}->{$options["field"]};
			else return false;
		}

        return $obj->{$table};
    }
    //--------------------------------------------------------------------------------
	/**
	 * @param $obj
	 * @param $field
	 * @param $where
	 * @param array $options
	 * @return array|false|mixed
	 */
	public function get_fromdb_json($field, $where, $options = []) {

		$options = array_merge([
		    "orderby" => "{$this->key} DESC"
		], $options);

		$where_arr = \LiquidedgeApp\Octoapp\app\app\arr\arr::splat($where);

		$sql = \LiquidedgeApp\Octoapp\app\app\db\sql\select::make();
		$sql->select("{$this->name}.*");
		$sql->from($this->name);
		foreach ($where_arr as $key => $value){
			$sql->json_and_where($field, $key, $value);
		}

		$sql->extract_options($options);

		if($options["orderby"]) $sql->orderby($options["orderby"]);

		return \core::dbt($this->name)->get_fromsql($sql->build(), $options);
	}
	//--------------------------------------------------------------------------------
	public function build_slug($obj, $options = []) {

	    $options = array_merge([
	        "append" => []
	    ], $options);

		$name_arr = [];
		$name_arr[] = $obj->format_name();

		if($obj->is_empty($this->key)) $name_arr[] = $obj->get_next_id();
		else $name_arr[] = $obj->id;

		$append_arr = \LiquidedgeApp\Octoapp\app\app\arr\arr::splat($options["append"]);
		foreach ($append_arr as $append) $name_arr[] = $append;

		return \LiquidedgeApp\Octoapp\app\app\str\str::str_to_seo(implode("-", $name_arr));
	}
	//--------------------------------------------------------------------------
    public function select_list($options = []) {

        $options = array_merge([
            "field1" => $this->key,
            "field2" => $this->display,
            "orderby" => "field2 DESC",
        ], $options);

        $sql = \LiquidedgeApp\Octoapp\app\app\db\sql\select::make();
        $sql->select("{$options["field1"]} AS field1");
        $sql->select("{$options["field2"]} AS field2");

        $sql->from($this->name);

        $sql->extract_options($options);

        return \core::db()->selectlist($sql->build(), "field1", "field2");
    }
    //--------------------------------------------------------------------------------
    public function get_next_order($options = []) {

	    if(!isset($this->field_arr["{$this->key}_order"]))
	        return 0;

        $sql = \LiquidedgeApp\Octoapp\app\app\db\sql\select::make();
        $sql->select("MAX({$this->key}_order)");
        $sql->from($this->name);
        $sql->extract_options($options);

        return \core::db()->selectsingle($sql->build());

    }
    //--------------------------------------------------------------------------
}
