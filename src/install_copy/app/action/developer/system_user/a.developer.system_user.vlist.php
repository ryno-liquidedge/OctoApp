<?php

namespace action\developer\system_user;

/**
 * Class vtable
 * @package action\system\setup\system_user
 * @author Ryno Van Zyl
 */

class vlist implements \com\router\int\action {
	/**
	 * @var \com\ui\set\bootstrap\table
	 */
    private $table;
    private $per_is_active = false;
    //--------------------------------------------------------------------------------
	use \com\router\tra\action;
    //--------------------------------------------------------------------------------
    public function auth() { 
        return \core::$app->get_token()->check("dev");
    }
    //--------------------------------------------------------------------------------
    public function run () {

    	$this->role = $this->request->get('role', \com\data::TYPE_STRING);
        $this->context = new \com\context();

        $this->context->filter_type("active", "sql_where", "per_is_active = 1");
        $this->context->filter_type("active", "heading", "Active Users");

        $this->context->filter_type("inactive", "sql_where", "per_is_active = 0");
        $this->context->filter_type("inactive", "heading", "Inactive Users");

        $this->table = \com\ui::make()->table();
        $this->table->key = "per_id";

        $this->table->nav_append_end = function($table, $toolbar){
			$toolbar->add(\com\ui::make()->iselect("role", [null => "-- Not Selected --"] + \core::dbt("acl_role")->get_list(), $this->role, false, ["!change" => "{$this->request->get_panel()}.refresh(null, {data: {role:$('#role').val()} })"]));
		};

        $this->table->set_sql($this->get_sql());
        $this->table->quickfind_field = \com\db::getsql_concat(["per_email", "per_findstring"]);
        $this->table->nav_append = function($table, $toolbar){
            $toolbar->add_button("Add new User", "{$this->request->get_panel()}.popup( '?c=developer.system_user/vadd', {width:'modal-lg'});");
        };

        // fields
        $this->table->add_field("First Name", "per_firstname");
        $this->table->add_field("Last Name", "per_lastname");
        $this->table->add_field("E-mail", "per_email", ["function" => function($content, $item_index, $field_index, $table) {
            $dropdown_email = \com\ui::make()->dropdown();
            $dropdown_email->add_link("Copy Email", "core.workspace.copy_text('$content')", ["icon" => "fa-clipboard"]);
            $dropdown_email->add_link("Send Email", "window.location.href = 'mailto:$content';", ["icon" => "fa-envelope"]);
            return \com\ui::make()->link($dropdown_email, "$content");
        }]);
        $this->table->add_field("Roles", "id", ["function" => function($content, $item_index, $field_index, $table){
			$person = \core::dbt("person")->get_fromdb($content);
			$person->add_cache();
            return implode("<br>", $person->get_role_list());
        }]);

        $this->table->add_action('Edit', "{$this->request->get_panel()}.popup('?c=developer.system_user/vedit/%per_id%', {width:'modal-lg'});", 'fa-pencil-alt');
        $this->table->add_action('Login AS', "document.location='?c=developer.system_user.functions/xlogin_as/%per_id%'", 'fa-refresh');

        $html = \com\ui::make()->html();
        $html->space(20);
        $html->display($this->table);

    }
    //--------------------------------------------------------------------------------
    public function get_sql() {
        $sql = \com\db\sql\select::make();

        $sql->select("per_id AS id");
        $sql->select("person.*");

        $sql->from("person");
        $sql->from("LEFT JOIN person_role ON (pel_ref_person = per_id)");

        $sql->groupby("per_id");

        if($this->context->sql_where) $sql->and_where($this->context->sql_where);

        if($this->role){
        	$sql->and_where("pel_ref_acl_role = $this->role");
		}

        return $sql;
    }
    //--------------------------------------------------------------------------------
}

