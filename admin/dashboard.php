<?php 
	ob_start(); //output buffering start
	session_start();
	if(isset($_SESSION['username']))
	{
		$page_title = 'Dashboard';
		include('init.php');
?>
		
		<div class="home-stats">
			<div class="container text-center">
				<h1>Dashboard</h1>
				<div class="row">
					<div class="col-md-3">
						<a href="members.php">
							<div class="stat st-members">
								<i class="fa fa-users"></i>
								<div class="info">
									Total Members
									<span>

										<?php echo countItems('userId','users');?>
										
									
									</span>
								</div>
							</div>
						</a>
					</div>
					<div class="col-md-3">
						<a href="members.php?do=manage&page=pending">
							<div class="stat st-pending">
								<i class="fa fa-user-plus"></i>
								<div class="info">
									Pinding Members
									<span>

										<?php echo checkItem('regStatus','users',0);?>

									</span>
								</div>
							</div>
						</a>
					</div>
					<div class="col-md-3">
						<a href="items.php">
							<div class="stat st-items">
								<i class="fa fa-tag"></i>
								<div class="info">
									Total Items
									<span>
									
										<?php echo countItems('item_id','items');?>
									
									</span>
								</div>
							</div>
						</a>
					</div>
					<div class="col-md-3">
						<div class="stat st-comments">
							<i class="fa fa-comments"></i>
							<div class="info">
								Total Comments
								<span>
									
									<?php echo countItems('comment_id','comments');?>

								</span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="latest">
			<div class="container">
				<div class="row">
					<div class="col-sm-6">
						<div class="panel panel-default">

							<?php 
								$numUsers = 3; //number of the latest user array
								$latestUsers = getLatest('*','users','userId',$numUsers);
								$numItems = 3; //number of the latest user array
								$latestItems = getLatest('*','items','item_id',$numItems);
								$numComments = 3; //number of the latest comment array
							?>

							<div class="panel-heading">
								<i class="fa fa-users"></i>
								Latest <?php echo $numUsers;?> Registered Users
								<span class="toggle-info pull-right">
									<i class="fa fa-minus fa-lg"></i>
								</span>
							</div>
							<div class="panel-body">
								<ul class="list-unstyled latest-users">
									
									<?php
										if(!empty($latestUsers))
										{
											foreach ($latestUsers as $latestUser)
											{
												echo '<li>';
													echo $latestUser['username'];
													echo '<a href="members.php?do=edit&userId='.$latestUser['userId'].'">';
														echo '<span class="btn btn-success pull-right">';
															echo'<i class="fa fa-edit"></i> Edit ';
															if($latestUser['regStatus'] == 0)
															{
																echo "<a href='members.php?do=activate&userId=".$latestUser['userId']."' class='btn btn-info pull-right'><i class='fa fa-check'></i> Activate </a>";
															}
														echo '</span>';
													echo '</a>';
												echo '</li>';
											}
										}
										else
										{
											echo "there's no record to show";
										}
									?>

								</ul>
								
							</div>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="panel panel-default">
							<div class="panel-heading">
								<i class="fa fa-tag"></i>
								Latest <?php echo $numItems;?> Items
								<span class="toggle-info pull-right">
									<i class="fa fa-minus fa-lg"></i>
								</span>
							</div>
							<div class="panel-body">
								<ul class="list-unstyled latest-users">
									
									<?php
										if(!empty($latestItems))
										{
											foreach ($latestItems as $latestItem)
											{
												echo '<li>';
													echo $latestItem['name'];
													echo '<a href="items.php?do=edit&item_id='.$latestItem['item_id'].'">';
														echo '<span class="btn btn-success pull-right">';
															echo'<i class="fa fa-edit"></i> Edit ';
															if($latestItem['approve'] == 0)
															{
																echo "<a href='items.php?do=approve&item_id=".$latestItem['item_id']."' class='btn btn-info pull-right'><i class='fa fa-check'></i> Approve </a>";
															}
														echo '</span>';
													echo '</a>';
												echo '</li>';
											}
										}
										else
										{
											echo "there's no record to show";
										}
									?>

								</ul>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-6">
						<div class="panel panel-default">
							<div class="panel-heading">
								<i class="fa fa-comments-o"></i>
								Latest <?php echo $numComments;?> Comments
								<span class="toggle-info pull-right">
									<i class="fa fa-minus fa-lg"></i>
								</span>
							</div>
							<div class="panel-body">
								
								<?php
									$stmt = $con->prepare(" SELECT comments.*,
																   users.username AS user
															FROM comments
															INNER JOIN users
															ON users.userId=comments.user_id
															ORDER BY comment_id DESC
															LIMIT $numComments
								 						 ");
									$stmt->execute(array());
									$comments = $stmt->fetchAll();
									if(!empty($comments))
									{
										foreach ($comments as $comment)
										{
											echo "<div class='comment-box'>";
												echo '<span class="member-n">
													      <a href="members.php?do=edit&userId='.$comment['user_id'].'">'.$comment['user'].'</a>
													  </span>';
												echo "<p class='member-c'>".$comment['comment']."</p>";
											echo "</div>";
										}
									}
									else
									{
										echo "there's no record to show";	
									}

								?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

<?php
		include($tpl.'footer.php');
	}
	else
	{
		header('Location: index.php');
		exit();
	}

	ob_end_flush(); //release the output

?>