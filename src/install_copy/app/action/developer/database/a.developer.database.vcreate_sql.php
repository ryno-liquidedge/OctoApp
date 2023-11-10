<?php

namespace action\developer\database;

/**
 * @package action\developer\database
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class vcreate_sql implements \com\router\int\action {

    //--------------------------------------------------------------------------------
    use \com\router\tra\action;
    //--------------------------------------------------------------------------------
    // functions
    //--------------------------------------------------------------------------------
    public function auth() {
        return \core::$app->get_token()->check("dev");
    }
    //--------------------------------------------------------------------------------
    public function run() {

		$name = $this->request->get("name", \com\data::TYPE_STRING, ["get" => true]);
		
		// html
		$html = \com\ui::make()->html();
		$html->header(2, "SQL Create");

		$html->button("Copy SQL", "
			$('#sql_raw').selectText();
			setTimeout(function(){ 
				document.execCommand('copy');
				core.message.show_notice('Copy', 'Text copied to clipboard');
			}, 50)
		", [".btn-info" => true]);
		$html->button("Run SQL", "{$this->request->get_panel()}.requestRefresh('?c=developer.database.functions/xrun_create_sql&name=$name', {confirm:'Are you sure you want to continue?'})");
		$html->value(false, nl2br(\core::db()->getsql_create_table($name)), false, ["@id" => "sql_raw"]);
    }
    //--------------------------------------------------------------------------------
}
