<?php
$do = '';

if(isset($_GET['do']))
{
	$do = $_GET['do'];
}
else
{
	$do = 'manage';
}

//if the page is main page
if($do == 'manage')
{
	echo "welcome you are in manage category page";
	echo "<a href=page.php?do=insert>Add New Category +</a>";
}
elseif ($do =='add')
{
	echo "welcome you are in add category page";
}
elseif ($do =='insert')
{
	echo "welcome you are in insert category page";
}
else
{
	echo "there is no page with this name";
}
?>