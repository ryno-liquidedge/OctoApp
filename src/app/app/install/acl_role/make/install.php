<?php
namespace LiquidedgeApp\Octoapp\app\app\install\acl_role\make;
/**
 * @package LiquidedgeApp\Octoapp\app\app\install\acl_role\make
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class install extends \LiquidedgeApp\Octoapp\app\app\install\acl_role\master\main{
	
	private $acl_name;
	private $acl_code;
	private $acl_level;
	private $acl_is_locked = 0;
	
	private $solid_class_name = false;
	
	//put your code here
	//--------------------------------------------------------------------------
	public function __construct($acl_name, $acl_code, $acl_level, $acl_is_locked = 0) {
		$this->acl_name = ucfirst(strtolower($acl_name));
		$this->acl_code = strtoupper(str_replace(" ", "_", $acl_code));
		$this->acl_is_locked = $acl_is_locked;
		$this->acl_level = $acl_level;
		
		$this->solid_class_name = "app.install.acl_role.".strtolower($this->acl_code);
	}

	//--------------------------------------------------------------------------
    /**
     * @return bool|string
     */
    public function get_solid_class_name() {
        return $this->solid_class_name;
    }
	//--------------------------------------------------------------------------
	public function get_acl_code() {
		return $this->acl_code;
	}
	//--------------------------------------------------------------------------
	public function get_acl_is_locked() {
		return $this->acl_is_locked;
	}
	//--------------------------------------------------------------------------
	public function get_acl_level() {
		return $this->acl_level;
	}
	//--------------------------------------------------------------------------
	public function get_acl_name() {
		return $this->acl_name;
	}
	//--------------------------------------------------------------------------
	public static function install_from_solid($return_sql = false, $get_all = false) {
		$sql = [];
		$glob_arr = glob(\core::$folders->get_app_app()."/install/acl_role/app*");
		foreach ($glob_arr as $php_class){
			$name = basename($php_class);
			$class_name = str_replace(".php", "", $name);
			$c = "\\".str_replace(".", "\\", $class_name);

			$class_exists = class_exists($c);
			if($class_exists){
				if (is_callable(array($c,"make"))){
					$app_acl_role = call_user_func( [$c,"make"] );
					$obj = $app_acl_role->find();
					$obj->save();
				}
			}
		}
		return $sql;
	}
	//--------------------------------------------------------------------------
	public function install() {
		$acl_role = $this->find(["create" => true]);
        $acl_role->save();

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
			$dir = \core::$folders->get_app_app()."/install/acl_role";
			$filename = "$this->solid_class_name.php";
			
			\com\os::mkdir($dir);
			file_put_contents("$dir/$filename", $this->get_template_code());
		}
	}
	//--------------------------------------------------------------------------------
	public static function build_constants(){


	    $sql = \LiquidedgeApp\Octoapp\app\app\db\sql\select::make();
	    $sql->select("acl_id");
	    $sql->select("acl_code");
	    $sql->from("acl_role");
	    $constant_arr = \core::db()->selectlist($sql->build(), "acl_id", "acl_code");

	    $user_roles_arr = [];
	    array_walk($constant_arr, function (&$item, $key) use(&$user_roles_arr){
	    	$user_roles_arr[] = "ACL_CODE_$item";
            $item = "define(\"ACL_CODE_$item\", \"$item\");";
        });

	    if($constant_arr){
            $dir = \core::$folders->get_app_app()."/install/acl_role/incl";
            $filename = "app.acl_role.incl.constants.php";

            \com\os::mkdir($dir);

            $constant_str = implode("\n", $constant_arr);

            $constant_str .= "\ndefine(\"ACL_AUTH_USERS\", [".implode(", ", $user_roles_arr)."]);";

            $content = <<<EOD
<?php
/**
 * @package app\install\acl_role\incl
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
		
		$class_name = strtolower($this->acl_code);
		$date = \LiquidedgeApp\Octoapp\app\app\date\date::strtodate();
		
		$content = <<<EOD
<?php
namespace LiquidedgeApp\Octoapp\app\app\install\acl_role;

/**
 * @package app\install\acl_role
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class $class_name extends \app\install\acl_role\master\main{
	//--------------------------------------------------------------------------------
	public function get_acl_code() {
		return "{$this->acl_code}";
	}
	//--------------------------------------------------------------------------------
	public function get_acl_is_locked() {
		return {$this->acl_is_locked};
	}
	//--------------------------------------------------------------------------------
	public function get_acl_level() {
		return {$this->acl_level};
	}
	//--------------------------------------------------------------------------------
	public function get_acl_name() {
		return "{$this->acl_name}";
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
		return \LiquidedgeApp\Octoapp\app\app\install\acl_role\make\install::install_from_solid($options_arr["return_sql"]);
	}
	//--------------------------------------------------------------------------
	public static function install_from_db() {
		$acl_role_arr = \core::$db->acl_role->get_fromdb("1=1", ["multiple" => true]);

		foreach ($acl_role_arr as $acl_role) {
			$install = new \LiquidedgeApp\Octoapp\app\app\install\acl_role\make\install($acl_role->acl_name, $acl_role->acl_code, $acl_role->acl_level, $acl_role->acl_is_locked);
			$install->install();
		}

		self::build_constants();
	}
	//--------------------------------------------------------------------------
    public function install_class($acl_role) {
        $install = new \LiquidedgeApp\Octoapp\app\app\install\acl_role\make\install($acl_role->acl_name, $acl_role->acl_code, $acl_role->acl_level, $acl_role->acl_is_locked);
        $install->install();

        self::build_constants();
    }
	//--------------------------------------------------------------------------
}
