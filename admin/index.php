<?php 
	session_start();
	$noNavbar   = '';
	$page_title = 'Login';
	if(isset($_SESSION['username']))
	{
		header('Location: dashboard.php');
	}
	include('init.php');

	//check if user comming from post request
	if($_SERVER['REQUEST_METHOD']=='POST')
	{
		$username = $_POST['username'];
		$password = $_POST['password'];
		$password = sha1($password);

		//check if the user exist in database
		$stmt = $con->prepare("SELECT userId,username,password
							   FROM users
							   WHERE username = ? 
							   AND password = ? 
							   AND groupId = 1
							   LIMIT 1"
							 );
		$stmt->execute(array($username, $password));
		$row   = $stmt->fetch();
		$count = $stmt->rowCount();

		//if count > 0 this mean the database contain record about this username
		if ($count > 0)
		{
			$_SESSION['username'] = $username;
			$_SESSION['id'] = $row['userId'];
			header('Location: dashboard.php');
			exit();
		}
	}
?>

	<form class="login" action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">
		<h3 class="text-center">Admin Login</h3>
		<input class="form-control input-lg" type="text" name="username" placeholder="Username" autocomplete="off">
		<input class="form-control input-lg" type="password" name="password" placeholder="Password" autocomplete="new-password">
		<input class="btn btn-primary btn-block btn-lg" type="submit" value="Login">
	</form>

<?php 
	include($tpl.'footer.php');
?>