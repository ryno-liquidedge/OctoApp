<?php
namespace app\install\person_type\make;
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
class install extends \app\install\person_type\master\main{
	
	private $pty_name;
	private $pty_code;
	private $pty_class;
	private $pty_is_individual = 0;
	
	private $solid_class_name = false;
	
	//put your code here
	//--------------------------------------------------------------------------
	public function __construct($pty_name, $pty_code, $pty_class, $pty_is_individual = 0) {
		$this->pty_name = ucfirst(strtolower($pty_name));
		$this->pty_code = strtoupper(str_replace(" ", "_", $pty_code));
		$this->pty_is_individual = $pty_is_individual;
		$this->pty_class = $pty_class;

		if(!$this->pty_code){
		    $this->pty_code = strtoupper($this->pty_class);
        }

		$this->solid_class_name = "app.install.person_type.".strtolower($this->pty_code);

	}
	//--------------------------------------------------------------------------
	public function get_pty_code() {
		return $this->pty_code;
	}
	//--------------------------------------------------------------------------
	public function get_pty_is_individual() {
		return $this->pty_is_individual;
	}
	//--------------------------------------------------------------------------
	public function get_pty_class() {
		return $this->pty_class;
	}
	//--------------------------------------------------------------------------
	public function get_pty_name() {
		return $this->pty_name;
	}
	//--------------------------------------------------------------------------
	public static function install_from_solid($return_sql = false, $get_all = false) {
		$sql = [];
		$glob_arr = glob(\core::$folders->get_app_app()."/install/person_type/app*");
		foreach ($glob_arr as $php_class){
			$name = basename($php_class);
			$class_name = str_replace(".php", "", $name);
			$c = "\\".str_replace(".", "\\", $class_name);

			$class_exists = class_exists($c);
			if($class_exists){
				if (is_callable(array($c,"make"))){
					$dbt = call_user_func( [$c,"make"] );
					$obj = $dbt->find(["create" => true]);
					if($obj) $obj->save();
				}
			}
		}

		self::build_constants();

		return $sql;
	}
	//--------------------------------------------------------------------------
	public function install() {
		$person_type = $this->find(["create" => true]);
        if($person_type) $person_type->save();
		
		if(!$this->solid_class_exists()){
			$this->build();
		}
	}
	//--------------------------------------------------------------------------------
	public function solid_class_exists(){
		@$class_exists = class_exists("\\".str_replace(".", "\\", $this->solid_class_name));
		return $class_exists;
	}
	//--------------------------------------------------------------------------------
	public function build(){
		
		if(!$this->solid_class_exists()){
			$dir = \core::$folders->get_app_app()."/install/person_type";
			$filename = "$this->solid_class_name.php";
			
			\com\os::mkdir($dir);
			
			file_put_contents("$dir/$filename", $this->get_template_code());
		}
	}
	//--------------------------------------------------------------------------------
	public static function build_constants($constant_arr = []){

	    if($constant_arr){
            $dir = \core::$folders->get_app_app()."/install/person_type/incl";
            $filename = "app.person_type.incl.constants.php";

            \com\os::mkdir($dir);

            $constant_str = implode("\n", $constant_arr);

            $content = <<<EOD
<?php
/**
 * @package app\install\person_type\incl
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
$constant_str


EOD;

            file_put_contents("$dir/$filename", $content);
        }

	}

	//--------------------------------------------------------------------------
	private function get_template_code(){
		
		$class_name = strtolower($this->pty_code);
		$date = \com\date::strtodate();
		
		$content = <<<EOD
<?php
namespace app\install\person_type;
/**
 * @package app\install\person_type
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class $class_name extends \app\install\person_type\master\main{
	//--------------------------------------------------------------------------------
	public function get_pty_code() {
		return "{$this->pty_code}";
	}
	//--------------------------------------------------------------------------------
	public function get_pty_is_individual() {
		return {$this->pty_is_individual};
	}
	//--------------------------------------------------------------------------------
	public function get_pty_class() {
		return "{$this->pty_class}";
	}
	//--------------------------------------------------------------------------------
	public function get_pty_name() {
		return "{$this->pty_name}";
	}
	//--------------------------------------------------------------------------------
}

EOD;
		
		return $content;
	}
	//--------------------------------------------------------------------------
	// static
	//--------------------------------------------------------------------------
	public static function make_db_enties($options = []) {
		$options_arr = array_merge([
			"return_sql" => false,
		], $options);
		return \app\install\person_type\make\install::install_from_solid($options_arr["return_sql"]);
	}
	//--------------------------------------------------------------------------
	public static function install_from_db() {
		$person_type_arr = \core::$db->person_type->get_fromdb("1=1", ["multiple" => true]);

		$constant_arr = [];

		foreach ($person_type_arr as $person_type) {
			$install = new \app\install\person_type\make\install($person_type->pty_name, $person_type->pty_code, $person_type->pty_class, $person_type->pty_is_individual);
			$install->install();

			$constant_arr[$install->get_pty_code()] = "define(\"PERSON_TYPE_{$install->get_pty_code()}\", \"{$install->get_pty_code()}\");";
		}

		self::build_constants($constant_arr);
	}
	//--------------------------------------------------------------------------
}
