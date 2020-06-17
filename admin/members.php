<?php
ob_start(); //output buffering start
session_start();
$page_title = 'Members';

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
		$query = '';
		if(isset($_GET['page']) && $_GET['page'] == 'pending')
		{
			$query = 'AND regStatus = 0';
		}
		$stmt = $con->prepare("SELECT * FROM users WHERE groupId != 1 " .$query. " ORDER BY userId DESC");
		$stmt->execute();
		$rows = $stmt->fetchAll();
		if(!empty($rows))
		{
?>

			<!--manage page-->
			<h1 class="text-center"> Manage Member </h1>
			<div class="container">
				<div class="table-responsive">
					<table class="main-table manage-members table table-bordered text-center">
						<thead>
							<tr>
								<td>Id</td>
								<td>Avatar</td>
								<td>Username</td>
								<td>Email</td>
								<td>FullName</td>
								<td>Registered Date</td>
								<td>Control</td>
							</tr>
						</thead>

				<?php 
					foreach ($rows as $row)
					{
				?>

						<tbody>
							<tr>
								<td><?php echo $row['userId'];?></td>
								<td>
									<?php
										if(empty($row['avatar']))
										{
											echo "<img src='uploads/avatars/default avatar' alt =''>";
										}
										else
										{
											echo "<img src='uploads/avatars/".$row['avatar']."' alt =''>";
										}
									?>
									
								</td>
								<td><?php echo $row['username'];?></td>
								<td><?php echo $row['email'];?></td>
								<td><?php echo $row['fullName'];?></td>
								<td><?php echo $row['date'];?></td>
								<td>
									<a href="members.php?do=edit&userId=<?php echo $row['userId'];?>" class="btn btn-success"><i class="fa fa-edit"></i>
									Edit </a>
									<a href="members.php?do=delete&userId=<?php echo $row['userId'];?>" class="btn btn-danger confirm"><i class="fa fa-close"></i> Delete </a>

									<?php
										if($row['regStatus'] == 0)
										{
											echo "<a href='members.php?do=activate&userId=".$row['userId']."' class='btn btn-info'><i class='fa fa-check'></i> Activate </a>";
										}
									?>
								</td>
							</tr>
						</tbody>

				<?php
					}
				?>

					</table>
				</div>
				<a href="members.php?do=add" class="btn btn-primary"><i class="fa fa-plus"></i> Add New Member </a>
			</div>
		
		<?php
		}
		else
		{
			echo '<div class="container">';
				echo '<div class="nice-message">The is no record to show</div>';
				echo '<a href="members.php?do=add" class="btn btn-primary"><i class="fa fa-plus"></i> Add New Member </a>';
			echo '</div>';
		}
	}





	elseif ($do == 'add')
	{
	?>

		<!--add page-->
		<h1 class="text-center"> Add New Member </h1>
		<div class="container">
			<form class="form-horizontal" action="?do=insert" method="POST" enctype="multipart/form-data">

				<div class="form-group form-group-lg">
					<label class="col-sm-2 control-label">Username</label>
					<div class="col-sm-10 col-md-8">
						<input type="text" name="username" class="form-control" autocomplete="off" required="required" placeholder="Enter Username">
					</div>
				</div>

				<div class="form-group form-group-lg">
					<label class="col-sm-2 control-label">Password</label>
					<div class="col-sm-10 col-md-8">
						<input type="password" name="password" class="password form-control" autocomplete="new-password" required="required" placeholder="Enter Password">
						<i class="show-pass fa fa-eye fa-2x"></i>
					</div>
				</div>

				<div class="form-group form-group-lg">
					<label class="col-sm-2 control-label">Email</label>
					<div class="col-sm-10 col-md-8">
						<input type="email" name="email" class="form-control" required="required" placeholder="Enter Email">
					</div>
				</div>

				<div class="form-group form-group-lg">
					<label class="col-sm-2 control-label">FullName</label>
					<div class="col-sm-10 col-md-8">
						<input type="text" name="fullName" class="form-control" required="required" placeholder="Enter FullName">
					</div>
				</div>

				<div class="form-group form-group-lg">
					<label class="col-sm-2 control-label">User Avatar</label>
					<div class="col-sm-10 col-md-8">
						<input type="file" name="avatar" class="form-control" required="required">
					</div>
				</div>

				<div class="form-group form-group-lg">
					<div class="col-sm-offset-2 col-sm-10">
						<input type="submit" value="Add Member" class="btn btn-primary btn-lg">
					</div>
				</div>
			</form>
		</div>

	<?php
	}





	elseif ($do == 'insert')
	{
		//insert page
		if ($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			echo "<h1 class='text-center'> Update Member </h1>";
			echo "<div class = 'container'>";

			$username = $_POST['username'];
			$password = $_POST['password'];
			$password = sha1($password);
			$email    = $_POST['email'];
			$fullName = $_POST['fullName'];

			//upload avatar
			$avatarName = $_FILES['avatar']['name'];
			$avatarSize = $_FILES['avatar']['size'];
			$avatarTemp = $_FILES['avatar']['tmp_name'];
			$avatarType = $_FILES['avatar']['type'];

			$allowedExtension = array('jpeg','jpg','png','gif');
			$avatarExtension  = strtolower(end(explode('.',$avatarName)));

			//validate the form
			$formErrors = array();
			if (empty($username))
			{
				$formErrors[] = 'username can not be empty';
			}
			if (strlen($username) < 4 || strlen($username) > 20)
			{
				$formErrors[] = 'username should be more than 4 characters and less than 20 characters';
			}
			if (empty($password))
			{
				$formErrors[] = 'password can not be empty';
			}
			if (empty($email))
			{
				$formErrors[] = 'email can not be empty';
			}
			if (empty($fullName))
			{
				$formErrors[] = 'fullName can not be empty';
			}
			if(empty($avatarName))
			{
				$formErrors[] = 'avatar is required';
			}
			if(!empty($avatarName) && !in_array($avatarExtension,$allowedExtension))
			{
				$formErrors[] = 'this extension is not allowed';
			}
			if($avatarSize > 4194304)
			{
				$formErrors[] = 'avatar can not be larger than 4MB';
			}

			//loop into errors array and print it
			foreach ($formErrors as $error)
			{
				echo '<div class="alert alert-danger">' . $error . '</div>';
			}

			//if there is no errors
			if (empty($formErrors))
			{
				$avatar = rand(0,1000000). "_" .$avatarName;
				move_uploaded_file($avatarTemp,"uploads/avatars/".$avatar);

				//check if user exist in database
				$check = checkItem("username","users",$username);
				if ($check == 1)
				{
					$msg = "<div class='alert alert-danger'> Sorry this username is already exist </div>";
					redirectHome($msg,'back');
				}
				else
				{
					//insert the data into the database
					$stmt = $con->prepare("INSERT INTO users(username,password,email,fullName,regStatus,date,avatar)
										   VALUES (?,?,?,?,1,now(),?)");
					$stmt->execute(array($username,$password,$email,$fullName,$avatar));
	 
					$msg = "<div class='alert alert-success'> data iserted successfully </div>";
					redirectHome($msg,'back');
				}
			}
		}
		else
		{
			echo "<div class='container'>";
			$msg = "<div class='alert alert-danger'>sorry you can't browse this page directory</div>";
			redirectHome($msg);
			echo "</div>";
		}

		echo "</div>";
	}





	elseif ($do == 'edit')
	{
		if (isset($_GET['userId']) && is_numeric($_GET['userId']))
		{
			$userId = intval($_GET['userId']);
		}
		else
		{
			$userId = 0;
		}
		$stmt = $con->prepare("SELECT * FROM users WHERE userId = ? LIMIT 1");
		$stmt->execute(array($userId));
		$row = $stmt->fetch();
		$count = $stmt->rowCount();

		if ($count > 0)
		{
	?>

			<!--edit page-->
			<h1 class="text-center"> Edit Member </h1>
			<div class="container">
				<form class="form-horizontal" action="?do=update" method="POST">

					<input type="hidden" name="userId" value="<?php echo $userId; ?>">

					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Username</label>
						<div class="col-sm-10 col-md-8">
							<input type="text" name="username" value="<?php echo $row['username']?>" class="form-control" autocomplete="off" required="required">
						</div>
					</div>

					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Password</label>
						<div class="col-sm-10 col-md-8">
							<input type="hidden" name="old-password" value="<?php echo $row['password']; ?>">
							<input type="password" name="new-password" class="form-control" autocomplete="new-password" placeholder="Leave this field empty if you don't change it">
						</div>
					</div>

					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Email</label>
						<div class="col-sm-10 col-md-8">
							<input type="email" name="email" value="<?php echo $row['email']?>" class="form-control" required="required">
						</div>
					</div>

					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">FullName</label>
						<div class="col-sm-10 col-md-8">
							<input type="text" name="fullName" value="<?php echo $row['fullName']?>" class="form-control" required="required">
						</div>
					</div>

					<div class="form-group form-group-lg">
						<div class="col-sm-offset-2 col-sm-10">
							<input type="submit" value="Save" class="btn btn-primary btn-lg">
						</div>
					</div>
				</form>
			</div>

	<?php
		}
		else
		{
			echo "<div class='container'>";
			$msg = "<div class='alert alert-danger'>sorry you can't browse this page directory</div>";
			redirectHome($msg);
			echo "</div>";
		}
	}





	elseif ($do == 'update')
	{
		//update page
		echo "<h1 class='text-center'> Update Member </h1>";
		echo "<div class = 'container'>";

		if ($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$userId   = $_POST['userId'];
			$username = $_POST['username'];
			$email    = $_POST['email'];
			$fullName = $_POST['fullName'];

			//password trick
			$password = '';
			if(empty($_POST['new-password']))
			{
				$password = $_POST['old-password'];
			}
			else
			{
				$password = sha1($_POST['new-password']);
			}

			//validate the form
			$formErrors = array();
			if (empty($username))
			{
				$formErrors[] = 'username can not be empty';
			}
			if (strlen($username) < 4 || strlen($username) > 20)
			{
				$formErrors[] = 'username should be more than 4 characters and less than 20 characters';
			}
			if (empty($email))
			{
				$formErrors[] = 'email can not be empty';
			}
			if (empty($fullName))
			{
				$formErrors[] = 'fullName can not be empty';
			}

			//loop into errors array and print it
			foreach ($formErrors as $error)
			{
				echo '<div class="alert alert-danger">' . $error . '</div>';
			}

			//if there is no errors
			if (empty($formErrors))
			{
				$stmt  = $con->prepare(" SELECT * FROM users
										WHERE username = ?
										AND userId 	   = ?
									 ");
				$stmt  = execute(array($username,$userId));
				$count = $stmt->rowCount();
				if($count == 1)
				{
					echo "<div class='alert alert-danger'>sorry this username is exist</div>";
					redirectHome($msg,'back');
				}
				else
				{
					//update the data in the database with this info
					$stmt = $con->prepare(" UPDATE users
											SET username = ?, email = ?, fullName = ?, password = ?
											WHERE userId != ?"
										 );
					$stmt->execute(array($username,$email,$fullName,$password,$userId));

					$msg = "<div class='alert alert-success'> data updated successfully </div>";
					redirectHome($msg,'back');
				}
			}
		}
		else
		{
			$msg = "<div class='alert alert-danger'>sorry you can't browse this page directory</div>";
			redirectHome($msg);
		}

		echo "</div>";
	}





	elseif ($do == 'delete')
	{
		//delete page
		echo "<h1 class='text-center'> Delete Member </h1>";
		echo "<div class = 'container'>";

		if (isset($_GET['userId']) && is_numeric($_GET['userId']))
		{
			$userId = intval($_GET['userId']);
		}
		else
		{
			$userId = 0;
		}

		$stmt = $con->prepare("SELECT * FROM users WHERE userId = ? LIMIT 1");
		$stmt->execute(array($userId));
		$count = $stmt->rowCount();

		if ($count > 0)
		{
			$stmt = $con->prepare("DELETE FROM users WHERE userId = ?");
			$stmt->execute(array($userId));
			$msg = "<div class='alert alert-success'>date deleted successfully</div>";
			redirectHome($msg,'back');
		}
		else
		{
			$msg = "<div class='alert alert-danger'>this id is not exist</div>";
			redirectHome($msg);
		}

		echo "</div>";
	}





	elseif ($do == 'activate')
	{
		//activate page
		echo "<h1 class='text-center'> Activate Member </h1>";
		echo "<div class = 'container'>";

		if (isset($_GET['userId']) && is_numeric($_GET['userId']))
		{
			$userId = intval($_GET['userId']);
		}
		else
		{
			$userId = 0;
		}

		$check = checkItem('userId','users',$userId);

		if ($check > 0)
		{
			$stmt = $con->prepare("UPDATE users SET regStatus = 1 WHERE userId = ?");
			$stmt->execute(array($userId));
			$msg = "<div class='alert alert-success'>user activated successfully</div>";
			redirectHome($msg,'back');
		}
		else
		{
			$msg = "<div class='alert alert-danger'>this id is not exist</div>";
			redirectHome($msg);
		}

		echo "</div>";
	}



	include($tpl .'footer.php');
}
else
{
	header('Location:index.php');
	exit();
}

ob_end_flush(); //release the output

?>