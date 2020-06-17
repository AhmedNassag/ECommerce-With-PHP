<?php

	//Error Reporting
	ini_set('display_errors','on');
	error_reporting(E_ALL);

	include('admin/connect.php');

	$session_user = '';
	if(isset($_SESSION['user']))
	{
		$session_user = $_SESSION['user'];
	}
	
	//Routes
	$tpl  = 'includes/templates/';   //templates directory
	$lang = 'includes/languages/';  //languages directory
	$func = 'includes/functions/'; //functions directory
	$css  = 'layout/css/';	      //css directory
	$js   = 'layout/js/';	   	 //js directory

	//include the important files
	include($func.'function.php');
	include($lang.'english.php');
	include($tpl.'header.php');
	include($tpl.'navbar.php');
	
?>