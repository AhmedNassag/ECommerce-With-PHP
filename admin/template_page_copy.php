<?php
ob_start(); //output buffering start
session_start();
$page_title = '';

if (isset($_SESSION['username']))
{
	include('init.php');
	if(isset($_GET['do']))
	{
		$do = $_GET['do'];
	}
	else
	{
		$do = 'manage';
	}





	//start manage page
	if($do == 'manage')
	{
		echo "manage page";
	}





	elseif ($do == 'add')
	{
		# code...
	}





	elseif ($do == 'insert')
	{
		# code...
	}





	elseif ($do == 'edit')
	{
		# code...
	}





	elseif ($do == 'update')
	{
		# code...
	}





	elseif ($do == 'delete')
	{
		# code...
	}

	include($tpl .'footer.php');
}
else
{
	header('Location:index.php');
	exit();
}

ob_end_flush(); //release output

?>