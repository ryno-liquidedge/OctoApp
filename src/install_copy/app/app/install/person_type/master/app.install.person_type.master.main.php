<?php
namespace app\install\person_type\master;
/*
 * Class 
 * @filename app 
 * @encoding UTF-8
 * @author Liquid Edge Solutions  * 
 * @copyright Copyright Liquid Edge Solutions. All rights reserved. * 
 * @programmer Ryno van Zyl * 
 * @date 28 Jun 2018 * 
 */

/**
 * Description of app
 *
 * @author Ryno
 */
abstract class main {
	private $dbobj = false;
	//--------------------------------------------------------------------------
	public function getdb() {
		$pty_code = $this->get_pty_code();
		$pty_class = $this->get_pty_class();

		if(!$pty_code) return false;
		
		if($this->dbobj) return $this->dbobj;

		$this->dbobj = \core::dbt("person_type")->get_fromdb("pty_code = '$pty_code'");
		if(!$this->dbobj) $this->dbobj = \core::dbt("person_type")->get_fromdb("pty_class LIKE '%$pty_class%'");

		return $this->dbobj;
	}
	//--------------------------------------------------------------------------
	public function getobj() {
		$pty_name = $this->get_pty_name();
		$pty_code = $this->get_pty_code();
		$pty_is_individual = $this->get_pty_is_individual();
		$pty_class = $this->get_pty_class();
		
		if(!isnull($pty_code)) return false;

		$obj = \core::dbt("person_type")->get_fromdefault();
		$obj->pty_name = $pty_name;
		$obj->pty_code = strtoupper($pty_code);
		if($obj->is_empty("pty_code")) $obj->pty_code = strtoupper($pty_class);
		$obj->pty_is_individual = $pty_is_individual;
		$obj->pty_class = $pty_class;
		
		return $obj;
	}
	//--------------------------------------------------------------------------
    public function find($options = []) {

	    $options = array_merge([
	        "create" => false
	    ], $options);

	    $pty_name = $this->get_pty_name();
		$pty_code = $this->get_pty_code();
		$pty_is_individual = $this->get_pty_is_individual();
		$pty_class = $this->get_pty_class();

	    $obj = \core::dbt("person_type")->find([
	        ".pty_code" => $pty_code,
        ]);

	    if(!$obj){
	        $obj = \core::dbt("person_type")->find([
                ".pty_class" => $pty_class,
                "create" => $options["create"]
            ]);
        }

        if($obj->is_empty("pty_code")) $obj->pty_code = strtoupper($obj->pty_class);
        if($obj->is_empty("pty_name")) $obj->pty_name = $pty_name;
        $obj->pty_is_individual = $pty_is_individual;

        return $obj;
    }
	//--------------------------------------------------------------------------
	public function update() {
		$person_type_db = $this->getdb();
        $person_type_obj = $this->getobj();
        foreach ($person_type_db->db->field_arr as $name => $data){
            if($name == $person_type_db->db->key) continue;
            $person_type_db->{$name} = $person_type_obj->{$name};
            $person_type_db->update();
        }
	}
	//--------------------------------------------------------------------------
	public function is_in_db() {
		return (bool)$this->getdb();
	}
	//--------------------------------------------------------------------------------
	public static function make($pty_name = false, $pty_code = false, $pty_class = "", $pty_is_individual = 0) {
	    $called_class = get_called_class();
		return new $called_class($pty_name, $pty_code, $pty_class, $pty_is_individual);
	}
	//--------------------------------------------------------------------------
	abstract function get_pty_name();
	abstract function get_pty_code();
	abstract function get_pty_class();
	abstract function get_pty_is_individual();
	//--------------------------------------------------------------------------
}
