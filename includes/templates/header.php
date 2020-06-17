<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title><?php getTitle(); ?></title>
	<link rel="stylesheet" href="<?php echo $css; ?>bootstrap.min.css">
	<link rel="stylesheet" href="<?php echo $css; ?>font-awesome.min.css">
	<link rel="stylesheet" href="<?php echo $css; ?>frontend.css">
</head>
<body>
	<div class="upper-bar">
		<div class="container">

			<?php
				if (isset($_SESSION['user']))
				{
			?>

			<img class="my-image img-thumbnail img-circle" src="img.png">
			<div class="btn-group my-info">
				<span class="btn btn-default dropdown-toggle" data-toggle="dropdown">
					<?php echo $session_user ?>
					<span class="caret"></span>
				</span>
				<ul class="dropdown-menu">
					<li><a href="profile.php">My Profile</a></li>
					<li><a href="newad.php">New Item</a></li>
					<li><a href="profile.php#my-ads">My Item</a></li>
					<li><a href="logout.php">Logout</a></li>
				</ul>
			</div>

			<?php
				}
				else
				{
			?>

			<a href="login.php">
				<span class="pull-right">Login/SignUp</span>
			</a>

			<?php
				}
			?>

		</div>
	</div>