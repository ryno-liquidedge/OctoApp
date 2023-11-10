<?php

namespace action\developer\cron_task;

/**
 * Action Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class vhome implements \com\router\int\action {
	//--------------------------------------------------------------------------------
	use \com\router\tra\action;
	//--------------------------------------------------------------------------------
	public function auth() {
		return $this->token->check("dev");
	}
	//--------------------------------------------------------------------------------
	public function run() {
		// tab
		$tab = \com\ui::make()->tab();
		$tab->add_tab("Active", "?c=developer.cron_task/vlist&context=active");
		$tab->add_tab("Inactive", "?c=developer.cron_task/vlist&context=inactive");

		// html
		$html = \com\ui::make()->html();
		$html->header(0, "Crons");
		$html->display($tab);
	}
	//--------------------------------------------------------------------------------
}