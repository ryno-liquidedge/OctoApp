<?php

if ( !isset( $_SERVER['SERVER_ADDR'] ) ) $_SERVER['SERVER_ADDR'] = 'localhost';
if ( !isset( $_SERVER['SCRIPT_FILENAME'] ) ) $_SERVER['SCRIPT_FILENAME'] = 'cron.php';

// setup
include_once("setup.php");

\app\cron\helper\trigger::make()->run();
