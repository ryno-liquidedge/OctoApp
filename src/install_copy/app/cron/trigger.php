<?php

if ( !isset( $_SERVER['SERVER_ADDR'] ) ) $_SERVER['SERVER_ADDR'] = 'localhost';
if ( !isset( $_SERVER['SCRIPT_FILENAME'] ) ) $_SERVER['SCRIPT_FILENAME'] = 'cron.php';

// setup
include_once("setup.php");

$class_name = isset($_REQUEST["run"]) ? $_REQUEST["run"] : false;
if($class_name){
	\app\cron\helper\trigger::make()->handle_run($class_name, ["force" => true]);
}