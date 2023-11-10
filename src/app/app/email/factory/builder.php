<?php

namespace LiquidedgeApp\Octoapp\app\app\email\factory;

/**
 * Class email
 * @package app
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */

class builder extends \LiquidedgeApp\Octoapp\app\app\intf\standard {


	/**
	 * @var null | mixed
	 */
    protected $heading = "";
    protected $enable_heading = true;

    protected string $subject;
    protected string $content;
    protected $to;
    protected $from;

    /**
     * @var \com\email
     */
    protected $comm;

    /**
     * @var \LiquidedgeApp\Octoapp\app\app\email\factory\template
     */
    protected $template;

    /**
     * @var bool
     */
    protected $force = false;

    /**
     * @var int
     */
    protected int $priority = 1;


    public $company = false;

    //--------------------------------------------------------------------------------
    protected function __construct(array $options = []) {

        $options = array_merge([
            "subject" => false,
            "enable_heading" => true,

            "template" => "standard",
            "force" => \core::auth_environment("DEV"),
        ], $options);

        $this->force = $options["force"];
        $this->enable_heading = $options["enable_heading"];

        //set communication module
        $this->comm = $comm = new \com\email();

        //set subject
        if($options["subject"]) $this->set_subject($options["subject"]);

        //set template
        $this->set_template($options["template"]);

        //set from
        $this->set_from(\core::$app->get_instance()->get_email_from(), \core::$app->get_instance()->get_email_from_name());

        //set force
        $this->set_force($options["force"]);

        $this->company = \core::$app->get_instance()->get_company();
    }
    //--------------------------------------------------------------------------------
    /**
     * @param $date
     */
    public function set_delay($date) {
        $this->comm->set_delay($date);
    }
    //--------------------------------------------------------------------------------
	/**
	 * @param string $heading
	 */
	public function set_heading(string $heading): void {
		$this->heading = $heading;
	}
    //--------------------------------------------------------------------------------

	/**
	 * @param $filepath
	 * @param array $options
	 * @throws \Exception
	 */
    public function add_attachment($filepath, $options = []) {
        $this->comm->add_attachment($filepath, $options);
    }
    //--------------------------------------------------------------------------------
	/**
	 * @param $mixed
	 * @param false $name
	 */
    public function add_bcc($mixed, $name = false) {
		$this->add_person($mixed, $name, "bcc");
    }
    //--------------------------------------------------------------------------------

	/**
	 * @param $mixed
	 * @param false $name
	 */
    public function add_cc($mixed, $name = false) {
    	$this->add_person($mixed, $name, "cc");
    }
    //--------------------------------------------------------------------------------

	/**
	 * @param $mixed
	 * @param false $name
	 */
    public function set_from($mixed, $name = false) {

    	if(!is_array($mixed) && $name !== false) $mixed = [$mixed , $name];

    	$this->comm->set_from($mixed);
    }
    //--------------------------------------------------------------------------------

	/**
	 * @param $mixed
	 * @param false $name
	 */
    public function set_to($mixed, $name = false) {
    	$this->add_person($mixed, $name);
    }
    //--------------------------------------------------------------------------------
	public function bcc_admin($options = []) {

    	$options = array_merge([
    	    "bcc_arr" => \core::$app->get_instance()->get_option("email.admin.bcc"),
    	], $options);

    	if($options["bcc_arr"]){

			$cc_arr = \LiquidedgeApp\Octoapp\app\app\arr\arr::splat($options["bcc_arr"]);
			foreach ($cc_arr as $email)
				$this->add_person("Admin", $email, "bcc");
		}
	}
    //--------------------------------------------------------------------------------
	/**
	 * @param $mixed
	 * @param false $name
	 * @param string $type
	 */
	public function add_person($mixed, $name = false, $type = "to") {
		try{
			if($mixed instanceof \com\db\row){
				if(!$name) $name = $mixed->format_name(":firstname");
				$this->comm->add_person_custom($mixed, $mixed->per_email, $name, $type);
			}else if(is_array($mixed)){
				$this->comm->add_recipient($mixed, $type);
			}else if(is_string($mixed)){
			    if(!$name) $name = "User";
			    $mixed = [$mixed , $name];
				$this->comm->add_recipient($mixed, $type);
            }

			if($name && $type == "to" && $this->enable_heading)
				$this->set_heading("Dear {$name}");

    	}catch(\Exception $ex){
    		\LiquidedgeApp\Octoapp\app\app\error\error::create($ex);
    	}
	}
    //--------------------------------------------------------------------------------
	protected function add_config_file_item($name, $mixed, $cid) {

		//first see if there is a db entry
		$config = \core::dbt("config")->find([
			".cfg_name" => $name,
			"create" => true,
		]);

		if($config && $config->source == "database"){
			$file_item = \core::dbt("file_item")->splat($config->cfg_data);
			$manager = \com\file\manager\file_item::make(["file_item" => $file_item,]);
			$this->comm->add_attachment_file($manager, ["cid" => $cid]);
		}else{
			//save to db settings if it does not exist and path is not empty
			if($mixed instanceof \com\db\row){
				$file_item = $mixed;

				//new entry
				$config->cfg_data = $file_item->fil_id;
				$config->save();

				$manager = \com\file\manager\file_item::make(["file_item" => $file_item,]);
				$this->comm->add_attachment_file($manager, ["cid" => $cid]);

			}else if(is_string($mixed) && file_exists($mixed)){
				$app_file = \com\file\manager\file_item::make();
				$file_item = $app_file->save_from_file($mixed);

				//new entry
				$config->cfg_data = $file_item->fil_id;
				$config->save();
			}
		}

	}
    //--------------------------------------------------------------------------------

    public function init_logo() {

//        $logo_file = \core::$folders->get_root_files()."/img/logo-email.png";

        $file_item_logo = \db_settings::get_company_logo_light();

        if($file_item_logo){

			$this->add_config_file_item(CONFIG_EMAIL_FILE_ITEM_LOGO, $file_item_logo, "logo");
        	$this->template->add_argument("logo", \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->image("cid:logo", [
        		"@style" => "display: block; height: auto; max-height:70px; border: 0; width: 250px; max-width: 100%;",
        		"@width" => "250",
			]));

		}

    }
    //--------------------------------------------------------------------------------

    /**
     * @param mixed $template
     */
    public function set_template($template) {

    	$color = \core::$app->get_instance()->get_option("bs.color.primary");
    	if(!$color) $color = "#61213B";

        $this->template = \LiquidedgeApp\Octoapp\app\app\email\factory\template::make(["template" => $template]);
        $this->template->set_style("
        	a{
                color: {$color};
            }

            .comment{
                color: {$color};
            }
        ");

    }
    //--------------------------------------------------------------------------------

    /**
     * @param $subject
     * @param array $options
     */
    public function set_subject($subject, $options = []) {
        $options = array_merge([
            "prepend" => $this->company,
            "separator" => " - ",
        ], $options);

        if($options["prepend"]){
            $this->subject = implode($options["separator"], [
                $options["prepend"],
                $subject,
            ]);
        }else{
            $this->subject = $this->subject = implode($options["separator"], \LiquidedgeApp\Octoapp\app\app\arr\arr::splat($subject));
        }

    }
    //--------------------------------------------------------------------------------

    /**
     * @param mixed $content
     */
    public function set_content($content) {

    	if(is_callable($content)) $content = $content($this);

        $this->content = $content;
    }
    //--------------------------------------------------------------------------------
	/**
	 * @param bool $bool
	 */
    public function set_force($bool = true) {
        $this->force = $bool;
    }
    //--------------------------------------------------------------------------------

	/**
	 * @param int $priority
	 */
    public function set_priority(int $priority) {
        $this->priority = $priority;
    }

    //--------------------------------------------------------------------------------
    protected function build_template() {

    	$this->init_logo();

    	$this->template->add_argument("heading", html_entity_decode($this->heading));
        $this->template->add_argument("company_tellnr", \core::$app->get_instance()->get_option("tell.nr.office"));
        $this->template->add_argument("company_email", \core::$app->get_instance()->get_option("email.from"));
        $this->template->add_argument("company_website", \core::$app->get_instance()->get_website());
        $this->template->add_argument("company_copyright", \core::$app->get_instance()->get_copyright());
        $this->template->add_argument("content", $this->content);
        return $this->template->get_html();
    }

    //--------------------------------------------------------------------------------
	/**
	 * @return \com\db\row|\com\db\table|\db_email|false
	 */
    public function send() {

        try {
            $this->comm->set_subject($this->subject);

            $this->comm->set_body($this->build_template());
            $this->comm->set_priority($this->priority);

            return $this->comm->send(["force" => $this->force]);

        } catch (\Exception $e) {
            \LiquidedgeApp\Octoapp\app\app\error\error::create($e);
        }

    }
    //--------------------------------------------------------------------------------
	public function add_some_file_item($obj, $cid = false) {
		$some_file_item = \com\file\manager\some_file_item::make_with($obj);
		$this->comm->add_attachment_file($some_file_item, ["cid" => $cid]);
	}
    //--------------------------------------------------------------------------------
	// static
  	//--------------------------------------------------------------------------------
	/**
	 * @param $to
	 * @param $subject
	 * @param $message
	 * @param array $options
	 * @return \com\db\row|\com\db\table|\db_email|false
	 */
    public static function send_email($to, $subject, $message, $options = []) {

        //send email
        $email = self::make($options);
		$email->set_subject($subject);
		$email->set_content(nl2br($message));

		$to_arr = \LiquidedgeApp\Octoapp\app\app\arr\arr::splat($to);
		$email->set_to($to_arr[0], isset($to_arr[1]) ? $to_arr[1] : false);
		return $email->send();

    }
    //--------------------------------------------------------------------------------
}