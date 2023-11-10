<?php

namespace LiquidedgeApp\Octoapp\app\app\db\sql;

/**
 * Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class select extends \com\db\sql\select {
	protected $with_arr = [];
	protected $offset = false;
	//--------------------------------------------------------------------------------
	public function select($sql, $options = []) {

		if(!is_string($sql) && is_callable($sql)) $sql = $sql();

		return parent::select($sql, $options);
	}

	//--------------------------------------------------------------------------------
	public function select_sql_concat($concat_arr, $alias, $options = []) {
		$this->select(\core::db()->getsql_concat($concat_arr)." AS $alias");
	}
	//--------------------------------------------------------------------------------
	public function from($sql) {

		if(!is_string($sql) && is_callable($sql)) $sql = $sql();

		return parent::from($sql);
	}

	//--------------------------------------------------------------------------------
	public function where($operator, $sql) {

		if (!$sql) return $this;
		if(!is_string($sql) && is_callable($sql)) $sql = $sql();

		$this->where_arr[$operator][] = "({$sql})";
		return $this;
	}
	//--------------------------------------------------------------------------------
	public function json_and_where($field, $key, $value) {
		$this->and_where("{$field} RLIKE '\"{$key}\":\"[[:<:]]{$value}[[:>:]]\"'");
		return $this;
	}
	//--------------------------------------------------------------------------------
	public function json_or_where($field, $key, $value) {
		$this->or_where("{$field} RLIKE '\"{$key}\":\"[[:<:]]{$value}[[:>:]]\"'");
		return $this;
	}
	//--------------------------------------------------------------------------------
    public static function has_extract_options($options = []) {

        if(array_key_exists("from", $options)) return true;
        if(array_key_exists("sql_where", $options)) return true;
        if(array_key_exists("and_where", $options)) return true;
        if(array_key_exists("where", $options)) return true;
        if(\LiquidedgeApp\Octoapp\app\app\arr\arr::has_signature_items(".", $options)) return true;

		return false;
    }
	//--------------------------------------------------------------------------------
	public function extract_options($options = []) {

	    $fn_extract = function($key, $fn)use($options){
	        if(isset($options[$key]) && $options[$key]){
                $options[$key] = \LiquidedgeApp\Octoapp\app\app\arr\arr::splat($options[$key]);
                foreach ($options[$key] as $from)
                    if($from) $this->{$fn}($from);
            }
        };

	    $fn_extract("from", "from");
	    $fn_extract("sql_where", "and_where");
	    $fn_extract("and_where", "and_where");
	    $fn_extract("where", "and_where");
	    $fn_extract("limit", "limit");
	    $fn_extract("orderby", "orderby");

		// extract the fields from options
		$field_arr = \LiquidedgeApp\Octoapp\app\app\arr\arr::extract_signature_items(".", $options);
		if (!$field_arr) return false;

		// generate sql for find query
		foreach ($field_arr as $field_index => $field_item) {
			// handle null
			if (isnull($field_item)) {
				$this->and_where("{$field_index} IS NULL");
			}
			else {
				$value = dbvalue($field_item);
				$this->and_where("{$field_index} = {$value}");
			}
		}
	}
	//--------------------------------------------------------------------------------
	public function limit($value): select {
		return $this->top($value);
	}
	//--------------------------------------------------------------------------------
    public function offset($offset): select {
        $this->offset = $offset;

        return $this;
    }
    //--------------------------------------------------------------------------------
	public function with($sql) {
		if (!$sql) return $this;
		$this->with_arr[$sql] = $sql;
		return $this;
	}
	//--------------------------------------------------------------------------------
	public function get_parts() {
		// parts
		$part_arr = [
			"with" => false,
			"select" => false,
			"from" => false,
			"where" => false,
			"groupby" => false,
			"having" => false,
			"union" => false,
			"orderby" => false,
			"limit" => false,
			"offset" => false,
		];


		// basic parts
		if ($this->with_arr) {
			$part_arr["with"] = implode(",\n\t", $this->with_arr); // supports multiple successive CTEs
		}

		// basic parts
		if ($this->select_arr) {
			$part_arr["select"] = implode(",\n\t", $this->select_arr);
			if ($this->top) $part_arr["select"] = "TOP {$this->top}\n\t{$part_arr["select"]}";
			if ($this->distinct) $part_arr["select"] = "DISTINCT\n\t{$part_arr["select"]}";
		}
		if ($this->from_arr) {
			$part_arr["from"] = implode("\n\t", $this->from_arr);
			$part_arr["from"] = preg_replace("/^(JOIN |LEFT JOIN )/i", "", $part_arr["from"]);
			$part_arr["from"] = str_replace("LEFT JOIN", "\n\tLEFT JOIN", $part_arr["from"]);

			if ($this->is_mysql()) {
				$part_arr["from"] =  preg_replace("/ ?WITH \\(NOLOCK\\)/i", "", $part_arr["from"]);
			}
		}
		if ($this->groupby_arr) $part_arr["groupby"] = implode(",\n\t", $this->groupby_arr);
		if ($this->orderby_arr) $part_arr["orderby"] = implode(",\n\t", $this->orderby_arr);
		if ($this->limit) $part_arr["limit"] = $this->limit;
		if ($this->offset) $part_arr["offset"] = $this->offset;

		// bind
		$bind_arr = [];
		foreach ($this->bind_arr as $bind_index => $bind_item) {
			if (is_array($bind_item)) {
				$bind_arr[$bind_index] = implode(",", array_map(function($item) {
					return dbvalue($item);
				}, $bind_item));
			}
			else {
				$bind_arr[$bind_index] = dbvalue($bind_item);
			}
		}

		// where
		$and_where_arr = $this->where_arr["AND"];
		if ($this->where_arr["OR"]) {
			$and_where_arr[] = "(".implode(" OR ", $this->where_arr["OR"]).")";
		}
		if ($and_where_arr) {
			$part_arr["where"] = strtr(implode("\n\tAND ", $and_where_arr), $bind_arr);
		}

		// having
		$and_having_arr = $this->having_arr["AND"];
		if ($this->having_arr["OR"]) {
			$and_having_arr[] = "(".implode(" OR ", $this->having_arr["OR"]).")";
		}
		if ($and_having_arr) {
			$part_arr["having"] = strtr(implode("\n\tAND ", $and_having_arr), $bind_arr);
		}

		// union
		if ($this->union_arr) {
			$part_arr["union"] = implode(" UNION ", $this->union_arr);
		}

		// done
		return $part_arr;
	}
	//--------------------------------------------------------------------------------
    public function and_where_person_role_exists($role, $not = false) {
        $this->and_where(function () use($role, $not){
            $sql = \LiquidedgeApp\Octoapp\app\app\db\sql\select::make();
            $sql->select("pel_id");

            $sql->from("person_role");
            $sql->from("LEFT JOIN acl_role ON (pel_ref_acl_role = acl_id)");
            $sql->and_where("pel_ref_person = per_id");
            $sql->and_where(\core::db()->getsql_in(\LiquidedgeApp\Octoapp\app\app\arr\arr::splat($role), "acl_code"));

            return ($not ? "NOT " : "")."EXISTS ({$sql->build()})";
        });
    }
	//--------------------------------------------------------------------------------
	public function build() {
		// init
		$sql = "";
		$part_arr = $this->get_parts();

		// build sql
		if ($part_arr["select"]) $sql .= "SELECT\n\t{$part_arr["select"]}";
		if ($part_arr["from"]) $sql .= "\n\nFROM\n\t{$part_arr["from"]}";
		if ($part_arr["where"]) $sql .= "\n\nWHERE\n\t{$part_arr["where"]}";
		if ($part_arr["groupby"]) $sql .= "\n\nGROUP BY\n\t{$part_arr["groupby"]}";
		if ($part_arr["having"]) $sql .= "\n\nHAVING\n\t{$part_arr["having"]}";
		if ($part_arr["union"]) $sql .= "\n\nUNION\n\t{$part_arr["union"]}";
		if ($part_arr["orderby"]) $sql .= "\n\nORDER BY\n\t{$part_arr["orderby"]}";
		if ($part_arr["limit"]) $sql .= "\n\nLIMIT {$part_arr["limit"]}";
		if ($part_arr["offset"]) $sql .= "\n\nOFFSET {$part_arr["offset"]}";

		// done
		return $sql;
	}
	//--------------------------------------------------------------------------------
	public static function getsql_case($case_arr, $field, $else = false, $options = []) {

		$options = array_merge([
		    "wrap_quotes" => false
		], $options);

		// start
		$sql = "(CASE ";

		// case items
		foreach ($case_arr as $case_index => $case_item) {
			$sql .= "\n\tWHEN {$field} {$case_index} THEN ".\LiquidedgeApp\Octoapp\app\app\data\data::dbvalue($case_item);
		}

		// else
		if ($else) $sql .= "\n\tELSE {$else}";

		// end
		$sql .= "\nEND)";

		// done
		return $sql;
	}
	//--------------------------------------------------------------------------------
	public function and_where_date_between($field, $date1, $date2 = false) {
        $date1 = \LiquidedgeApp\Octoapp\app\app\date\date::strtodate($date1);
        if(!$date2) $date2 = \LiquidedgeApp\Octoapp\app\app\date\date::strtodate();

        $this->and_where("$field BETWEEN '$date1' AND '$date2'");
	}
	//--------------------------------------------------------------------------------
}
