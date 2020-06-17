<?php
	ob_start();
	session_start();
	$page_title = 'Profile';
	include('init.php');
	if(isset($_SESSION['user']))
	{
		$getUser = $con->prepare(" SELECT * FROM users WHERE username = ? ");
		$getUser->execute(array($session_user));
		$info 	 = $getUser->fetch();
?>


		<h1 class="text-center">My Profile</h1>
		<div class="information block">
			<div class="container">
				<div class="panel panel-primary">
					<div class="panel-heading">My Information</div>
					<div class="panel-body">
						<ul class="list-unstyled">
							<li>
								<i class="fa fa-unlock-alt fa-fw"></i>
								<span>Login Name</span>: <?php echo $info['username'] ?>
							</li>
							<li>
								<i class="fa fa-envelope-o fa-fw"></i>
								<span>Email</span>: <?php echo $info['email'] ?>
							</li>
							<li>
								<i class="fa fa-user fa-fw"></i>
								<span>Full Name</span>: <?php echo $info['fullName'] ?>
							</li>
							<li>
								<i class="fa fa-calendar fa-fw"></i>
								<span>Register Date</span>: <?php echo $info['date'] ?>
							</li>
							<li>
								<i class="fa fa-tags fa-fw"></i>
								<span>Favourite Category</span>:
							</li>
						</ul>
						<a href="#" class="btn btn-default">Edit My Information</a>
					</div>
				</div>
			</div>
		</div>

		<div id="my-ads" class="my-ads block">
			<div class="container">
				<div class="panel panel-primary">
					<div class="panel-heading">My Items</div>
					<div class="panel-body">
						
						<?php
							$items = getAll("*","items","WHERE member_id = {$info['userId']}","","item_id");
							if(!empty($items))
							{
								echo '<div class="row">';
								foreach ($items as $item)
								{
									echo '<div class="col-sm-6 col-md-3">';
										echo '<div class="thumbnail item-box">';
											if($item['approve'] == 0)
											{
												echo '<span class="approve-status">Waiting Approval</span>';
											}
											echo '<span class="price">' .$item['price']. '</span>';
											echo '<img class="img-responsive" src="img.png" alt="">';
											echo '<div class="caption">';
												echo '<h3><a href="items.php?item_id='.$item['item_id'].'">' .$item['name']. '</a></h3>';
												echo '<p>' .$item['description']. '</p>';
												echo '<div class="date">' .$item['add_date']. '</div>';
											echo '</div>';
										echo '</div>';
									echo '</div>';
								}
								echo '</div>';
							}
							else
							{
								echo "there's no ads to show, create <a href='newad.php'>New Ad</a>";
							}
						?>

					</div>
				</div>
			</div>
		</div>

		<div class="my-comments block">
			<div class="container">
				<div class="panel panel-primary">
					<div class="panel-heading">My Comments</div>
					<div class="panel-body">
					
					<?php
						$comments = getAll("comment","comments","WHERE user_id = {$info['userId']}","","comment_id");
						if(!empty($comments))
						{
							foreach ($comments as $comment)
							{
								echo '<p>' .$comment['comment']. '</p>';
							}
						}
						else
						{
							echo "there is no commente to show";
						}
					?>

					</div>
				</div>
			</div>
		</div>


<?php
	}
	else
	{
		header('Location: login.php');
		exit();
	}
	include($tpl.'footer.php');
	ob_end_flush();
?>