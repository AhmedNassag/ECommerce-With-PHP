<?php
	ob_start();
	session_start();
	$page_title = 'Login'; 
	if(isset($_SESSION['user']))
	{
		header('Location: index.php');
	}
	include('init.php');

	//check if user comming from post request
	if($_SERVER['REQUEST_METHOD']=='POST')
	{
		if(isset($_POST['login']))
		{
			$username = $_POST['username'];
			$password = $_POST['password'];
			$password = sha1($password);

			//check if the user exist in database
			$stmt = $con->prepare(" SELECT userId,username,password
								    FROM users
								    WHERE username = ? 
								    AND password = ? 
								 ");
			$stmt->execute(array($username, $password));
			$get   = $stmt->fetch();
			$count = $stmt->rowCount();

			//if count > 0 this mean the database contain record about this username
			if ($count > 0)
			{
				$_SESSION['user'] = $username;
				$_SESSION['id']   = $get['userId'];
				header('Location: index.php');
				exit();
			}
		}
		else
		{
			$form_errors = array();
			$username    = $_POST['username'];
			$email       = $_POST['email'];
			$password    = $_POST['password'];
			$password2   = $_POST['password2'];
			if(isset($username))
			{
				$filter_user = filter_var($username,FILTER_SANITIZE_STRING);
				if(strlen($filter_user) < 4)
				{
					$form_errors[] = 'please, username must be larger than 4 character';
				}
			}
			if(isset($password) && isset($password2))
			{
				if(empty($password))
				{
					$form_errors[] = 'sorry, password can not be empty';
				}
				$pass1 = sha1($password);
				$pass2 = sha1($password2);
				if($pass1 !== $pass2)
				{
					$form_errors[] = 'sorry, password not match';
				}
			}
			if(isset($email))
			{
				$filter_email = filter_var($email,FILTER_SANITIZE_EMAIL);
				if(filter_var($filter_email,FILTER_VALIDATE_EMAIL) != true)
				{
					$form_errors[] = 'sorry, this is not valid email';
				}
			}

			//if there is no errors
			if (empty($formErrors))
			{

				//check if user exist in database
				$check = checkItem("username","users",$username);
				if ($check == 1)
				{
					$form_errors[] = 'sorry, this user is exist';
				}
				else
				{
					//insert the data into the database
					$stmt = $con->prepare("INSERT INTO users(username,password,email,regStatus,date)
										   VALUES (?,?,?,0,now())");
					$stmt->execute(array($username,sha1($password),$email));
	 
					$successMsg = "congratulation, you are now registered user";
					redirectHome($msg,'back');
				}
			}
		}
	}
?>


<div class="container login-page">
	<h1 class="text-center">
		<span class="selected" data-class="login">Login</span> | <span data-class="signup">SignUp</span>
	</h1>
	<!--start login form-->
	<form class="login" action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">
		<div class="input-container">
			<input class="form-control" type="text" name="username" autocomplete="off" placeholder="Enter Your Username" required="required">
		</div>
		<div class="input-container">
			<input class="form-control" type="password" name="password" autocomplete="new-password" placeholder="Enter Your Password" required="required">
		</div>
		<input class="btn btn-primary btn-block" name="login" type="submit" value="Login">
	</form>
	<!--end login form-->

	<!--start signup form-->
	<form class="signup" action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">
		<div class="input-container">
			<input class="form-control" type="text" name="username" autocomplete="off" placeholder="Enter Your Username" required="required" pattern=".{4,}" title="username must be larger than 4 character">
		</div>
		<div class="input-container">
			<input class="form-control" type="email" name="email" autocomplete="off" placeholder="Enter Valid Email" required="required">
		</div>
		<div class="input-container">
			<input class="form-control" type="password" name="password" autocomplete="new-password" placeholder="Enter Your Password" required="required" minlength="4">
		</div>
		<div class="input-container">
			<input class="form-control" type="password" name="password2" autocomplete="new-password" placeholder="Re-Enter Your Password Again" required="required" minlength="4">
		</div>
		<input class="btn btn-success btn-block" name="signup" type="submit" value="SignUp">
	</form>
	<!--end signup form-->

	<div class="the-errors text-center">
		
		<?php
			if(!empty($form_errors))
			{
				foreach ($form_errors as $error)
				{
					echo $error."<br>";
				}
			}
			if(isset($successMsg))
			{
				echo '<div class="msg success">' .$successMsg. '</div>';
			}
		?>
	</div>
</div>


<?php
	include($tpl.'footer.php');
	ob_end_flush();
?>