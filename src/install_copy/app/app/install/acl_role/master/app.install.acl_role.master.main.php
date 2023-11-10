<?php
namespace app\install\acl_role\master;
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
		$acl_code = $this->get_acl_code();
		
		if(empty($acl_code)) return false;
		
		if($this->dbobj) return $this->dbobj;
		
		return $this->dbobj = \core::$db->acl_role->get_fromdb("acl_code = '$acl_code'");
	}
	//--------------------------------------------------------------------------
	public function getobj() {
		$acl_name = $this->get_acl_name();
		$acl_code = $this->get_acl_code();
		$acl_is_locked = $this->get_acl_is_locked();
		$acl_level = $this->get_acl_level();
		
		if(empty($acl_code) || empty($acl_code) || empty($acl_code)) return false;
		
		$obj = \core::dbt("acl_role")->find([
		    ".acl_code" => $acl_code,
		    "create" => true
        ]);
		$obj->acl_name = $acl_name;
		$obj->acl_code = $acl_code;
		$obj->acl_is_locked = $acl_is_locked;
		$obj->acl_level = $acl_level;
		
		return $obj;
	}
	//--------------------------------------------------------------------------
    public function find($options = []) {

	    $options = array_merge([
	    ], $options);

        $obj = \core::dbt("acl_role")->find([
		    ".acl_code" => $this->get_acl_code(),
		    "create" => true
        ]);

        $obj->acl_code = $this->get_acl_code();
        $obj->acl_name = $this->get_acl_name();
		$obj->acl_code = $this->get_acl_code();
		$obj->acl_is_locked = $this->get_acl_is_locked();
		$obj->acl_level = $this->get_acl_level();

        return $obj;
    }
	//--------------------------------------------------------------------------
	public function is_in_db() {
		return (bool)$this->getdb();
	}
	//--------------------------------------------------------------------------------
	public static function make($acl_name = false, $acl_code = false, $acl_level = 1000, $acl_is_locked = 0) {
	    $called_class = get_called_class();
		return new $called_class($acl_name, $acl_code, $acl_level, $acl_is_locked);
	}
	//--------------------------------------------------------------------------
	abstract function get_acl_name();
	abstract function get_acl_code();
	abstract function get_acl_is_locked();
	abstract function get_acl_level();
	//--------------------------------------------------------------------------
}
