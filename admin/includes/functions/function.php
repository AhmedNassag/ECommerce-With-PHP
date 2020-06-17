<?php

//function that get all records from database
function getAll($field,$table,$where = NULL,$and = NULL,$orderField,$ordering = 'DESC')
{
	global $con;

	$stmt = $con->prepare("SELECT $field FROM $table $where $and ORDER BY $orderField $ordering");
	$stmt->execute();
	$all = $stmt->fetchAll();
	return $all;
}



//function which give title to the page
function getTitle()
{
	global $page_title;
	
	if (isset($page_title))
	{
		echo $page_title;
	}
	else
	{
		echo "Default";
	}
}



//function that redirect to index page if there is an error
function redirectHome($msg,$url = null,$seconds=3)
{
	if($url === null)
	{
		$url = 'index.php';
		$link = 'Home Page';
	}
	else
	{
		if(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== '')
		{
			$url = $_SERVER['HTTP_REFERER'];
			$link = 'Previous Page';
		}
		else
		{
			$url = 'index.php';
			$link = 'Home Page';
		}
	}
	echo $msg;
	echo '<div class="alert alert-info">You Will Be Directed To '.$link.' After '.$seconds.' Seconds</div>';
	header("refresh:$seconds;url=$url");
	exit();
}



//function that check if this item exist in database
function checkItem($selected,$table,$value)
{
	global $con;

	$stmt = $con->prepare("SELECT $selected FROM $table WHERE $selected = ?");
	$stmt->execute(array($value));
	$count = $stmt->rowCount();
	return $count;
}



//count items function
function countItems($item,$table)
{
	global $con;

	$stmt = $con->prepare("SELECT COUNT($item) FROM $table");
	$stmt-> execute();
	return $stmt->fetchColumn();
}



//function that get latest data inserted in database
function getLatest($selected,$table,$order,$limit=5)
{
	global $con;

	 $stmt = $con->prepare("SELECT $selected FROM $table ORDER BY $order DESC LIMIT $limit");
	 $stmt->execute();
	 $rows = $stmt->fetchAll();
	 return $rows;
}

?>