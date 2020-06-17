<?php
	ob_start();
	session_start();
	$page_title = 'Show Items';
	include('init.php');
	if (isset($_GET['item_id']) && is_numeric($_GET['item_id']))
	{
		$item_id = intval($_GET['item_id']);
	}
	else
	{
		$item_id = 0;
	}
	$stmt  = $con->prepare("SELECT items.*,categories.name AS cat_name,users.username
							FROM items
							INNER JOIN categories ON categories.id = items.cat_id
							INNER JOIN users ON users.userId = items.member_id
							WHERE item_id = ? AND approve = 1
						  ");
	$stmt->execute(array($item_id));
	$count = $stmt->rowCount();
	$item  = $stmt->fetch();

	if($count > 0)
	{
?>


<h1 class="text-center"><?php echo $item['name']?></h1>
<div class="container">
	<div class="row">
		<div class="col-md-3">
			<img class="img-responsive img-thumbnail center-block" src="img.png" alt="">
		</div>
		<div class="col-md-9 item-info">
			<h2><?php echo $item['name']?></h2>
			<p><?php echo $item['description']?></p>
			<ul class="list-unstyled">
				<li>
					<i class="fa fa-calendar fa-fw"></i>
					<span>Added Date</span> : <?php echo $item['add_date']?>
				</li>
				<li>
					<i class="fa fa-money fa-fw"></i>
					<span>Price</span> : $<?php echo $item['price']?>
				</li>
				<li>
					<i class="fa fa-building fa-fw"></i>
					<span>Made In</span> : <?php echo $item['country_made']?>
				</li>
				<li>
					<i class="fa fa-tags fa-fw"></i>
					<span>Category</span> : <a href="categories.php?pageId=<?php echo $item['cat_id']?>"><?php echo $item['cat_name']?></a>
				</li>
				<li>
					<i class="fa fa-user fa-fw"></i>
					<span>Added By</span> : <a href="3"><?php echo $item['username']?></a>
				</li>
				<li class="tags-items">
					<i class="fa fa-user fa-fw"></i>
					<span>Tags</span> : 

					<?php
						$tags = explode(",",$item['tags']);
						foreach ($tags as $tag)
						{
							$tag = str_replace(' ','',$tag);
							$tag = strtolower($tag);
							if(!empty($tag))
							{
								echo "<a href='tags.php?name={$tag}'>" .$tag. "</a>";
							}
						}
					?>

				</li>
			</ul>
		</div>
	</div>
	<hr class="custom-hr">

	<?php
		if(isset($_SESSION['user']))
		{
	?>

	<div class="row">
		<div class="col-md-offset-3">
			<div class="add-comment">
				<h3>Add Your Comment</h3>
				<form action="<?php echo $_SERVER['PHP_SELF'] .'?item_id='.$item['item_id']?>" method='POST'>
					<textarea name="comment" required="required"></textarea>
					<input class="btn btn-primary" type="submit" value="Add Comment">
				</form>

				<?php
					if($_SERVER['REQUEST_METHOD'] == 'POST')
					{
						$comment = filter_var($_POST['comment'],FILTER_SANITIZE_STRING);
						$item_id  = $item['item_id'];
						$userid  = $_SESSION['id'];
						if(!empty($comment))
						{
							$stmt = $con->prepare("INSERT INTO comments
												   (comment,status,comment_date,item_id,user_id)
												   VALUES(?,0,NOW(),?,?)
												  ");
							$stmt->execute(array($comment,$item_id,$userid));
							if($stmt)
							{
								echo '<div class="alert alert-success">Comment Added</div>';
							}
						}
					}
				?>
			</div>
		</div>
	</div>

	<?php
		}
		else
		{
			echo '<a href="login.php">Login</a> Or <a href="login.php">Register</a> To Add Comment';
		}
	?>

	<hr class="custom-hr">

	<?php
		$stmt = $con->prepare(" SELECT comments.*,users.username AS user
								FROM comments
								INNER JOIN users
								ON users.userId = comments.user_id
								WHERE item_id = ? AND status = 1
								ORDER BY comment_id DESC
							 ");
		$stmt->execute(array($item['item_id']));
		$comments = $stmt->fetchAll();
	
		foreach ($comments as $comment)
		{
	?>
			<div class="comment-box">
				<div class="row">
					<div class="col-sm-2">
						<img class="img-responsive img-thumbnail img-circle center-block" src="img.png">
						<?php echo $comment['user']; ?>
					</div>
					<div class="col-sm-10">
						<p class="lead">
							<?php echo $comment['comment']; ?>
						</p>
					</div>
				</div>
			</div>
			<hr class="custom-hr">

	<?php	
		}
	?>

</div>


<?php
	}
	else
	{
		echo '<div class="alert-danger">there is no such id or this item is waitting approval</div>';
	}
	include($tpl.'footer.php');
	ob_end_flush();
?>