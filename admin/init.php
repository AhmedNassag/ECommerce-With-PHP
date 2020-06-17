<?php 
	include('connect.php');
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

	if(!isset($noNavbar))
	{
		include($tpl.'navbar.php');
	}
	
?>