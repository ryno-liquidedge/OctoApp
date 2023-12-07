<?php

namespace action\install;

/**
 * Action Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class vdatabase implements \com\router\int\action {
	//--------------------------------------------------------------------------------
	use \com\router\tra\action;
	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	protected function __construct() {
		\core::$app->set_section(\acc\core\section\api::make());
	}
	//--------------------------------------------------------------------------------
	public function auth() {
		return file_exists(\core::$folders->get_root()."/install.php");
	}
	//--------------------------------------------------------------------------------
	public function run() {


	    \app\coder\installer\base::make()->rebuild_config();

		// html
		?>
		<!DOCTYPE html>
		<html lang="en">
			<head>
				<meta charset="utf-8">
				<meta http-equiv="X-UA-Compatible" content="IE=edge">
				<meta name="viewport" content="width=device-width, initial-scale=1">

				<title>Nova Installation</title>

				<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">

				<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
				<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
			</head>
			<body>
				<div class="jumbotron">
					<div class="container">
						<h1>Nova Installation</h1>
						<p>Please download the database table creation sql script; run it; and then click next.</p>
						<button type="button" class="btn btn-default" onclick="document.location = '?c=install/xsql&nocache=<?php echo time(); ?>';">Download SQL</button>
						<button type="button" class="btn btn-default" onclick="document.location = '?c=install/xdatabase';">Next</button>
					</div>
				</div>
			</body>
		</html>
		<?php

		// done
		return "clean";
	}
	//--------------------------------------------------------------------------------
}