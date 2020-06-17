<?php
ob_start(); //output buffering start
session_start();
$page_title = 'Comments';

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
		$stmt = $con->prepare(" SELECT comments.*,items.name AS item,users.username AS user
								FROM comments
								INNER JOIN items
								ON items.item_id = comments.item_id
								INNER JOIN users
								ON users.userId = comments.user_id
								ORDER BY comment_id DESC
							 ");
		$stmt->execute();
		$rows = $stmt->fetchAll();
		if(!empty($rows))
		{
?>

			<!--manage page-->
			<h1 class="text-center"> Manage Comments </h1>
			<div class="container">
				<div class="table-responsive">
					<table class="main-table table table-bordered text-center">
						<thead>
							<tr>
								<td>Id</td>
								<td>Comment</td>
								<td>Item Name</td>
								<td>User Name</td>
								<td>Added Date</td>
								<td>Control</td>
							</tr>
						</thead>

				<?php 
					foreach ($rows as $row)
					{
				?>

						<tbody>
							<tr>
								<td><?php echo $row['comment_id'];?></td>
								<td><?php echo $row['comment'];?></td>
								<td><?php echo $row['item'];?></td>
								<td><?php echo $row['user'];?></td>
								<td><?php echo $row['comment_date'];?></td>
								<td>
									<a href="comments.php?do=edit&comment_id=<?php echo $row['comment_id'];?>" class="btn btn-success"><i class="fa fa-edit"></i>
									Edit </a>
									<a href="comments.php?do=delete&comment_id=<?php echo $row['comment_id'];?>" class="btn btn-danger confirm"><i class="fa fa-close"></i> Delete </a>

									<?php
										if($row['status'] == 0)
										{
											echo "<a href='comments.php?do=approve&comment_id=".$row['comment_id']."' class='btn btn-info'><i class='fa fa-check'></i> Approve </a>";
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
			</div>
		
		<?php
		}
		else
		{
			echo '<div class="container">';
				echo '<div class="nice-message">The is no record to show</div>';
			echo '</div>';
		}
	}





	elseif ($do == 'edit')
	{
		if (isset($_GET['comment_id']) && is_numeric($_GET['comment_id']))
		{
			$comment_id = intval($_GET['comment_id']);
		}
		else
		{
			$comment_id = 0;
		}
		$stmt = $con->prepare("SELECT * FROM comments WHERE comment_id = ?");
		$stmt->execute(array($comment_id));
		$row = $stmt->fetch();
		$count = $stmt->rowCount();

		if ($count > 0)
		{
	?>

			<!--edit page-->
			<h1 class="text-center"> Edit Comment </h1>
			<div class="container">
				<form class="form-horizontal" action="?do=update" method="POST">

					<input type="hidden" name="comment_id" value="<?php echo $comment_id;?>">

					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Comment</label>
						<div class="col-sm-10 col-md-8">
							<textarea name="comment" class="form-control"><?php echo $row['comment']?></textarea>
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
		echo "<h1 class='text-center'> Update Comment </h1>";
		echo "<div class = 'container'>";

		if ($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$comment_id = $_POST['comment_id'];
			$comment 	= $_POST['comment'];
			//update the data in the database with this info
			$stmt = $con->prepare(" UPDATE comments
									SET comment = ?
									WHERE comment_id = ?
								 ");
				$stmt->execute(array($comment,$comment_id));

				$msg = "<div class='alert alert-success'> data updated successfully </div>";
				redirectHome($msg,'back');
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
		echo "<h1 class='text-center'> Delete Comment </h1>";
		echo "<div class = 'container'>";

		if (isset($_GET['comment_id']) && is_numeric($_GET['comment_id']))
		{
			$comment_id = intval($_GET['comment_id']);
		}
		else
		{
			$comment_id = 0;
		}

		$check = checkItem('comment_id','comments',$comment_id);

		if ($check > 0)
		{
			$stmt = $con->prepare("DELETE FROM comments WHERE comment_id = ?");
			$stmt->execute(array($comment_id));
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





	elseif ($do == 'approve')
	{
		//activate page
		echo "<h1 class='text-center'> Approve Member </h1>";
		echo "<div class = 'container'>";

		if (isset($_GET['comment_id']) && is_numeric($_GET['comment_id']))
		{
			$comment_id = intval($_GET['comment_id']);
		}
		else
		{
			$comment_id = 0;
		}

		$check = checkItem('comment_id','comments',$comment_id);

		if ($check > 0)
		{
			$stmt = $con->prepare("UPDATE comments SET status = 1 WHERE comment_id = ?");
			$stmt->execute(array($comment_id));
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