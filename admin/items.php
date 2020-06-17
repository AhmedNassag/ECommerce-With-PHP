<?php
ob_start(); //output buffering start
session_start();
$page_title = 'Items';

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
		$stmt = $con->prepare(" SELECT items.*,categories.name AS cat_name,users.username
								FROM items
								INNER JOIN categories ON categories.id = items.cat_id
								INNER JOIN users ON users.userId = items.member_id
								ORDER BY item_id DESC
							 ");
		$stmt->execute();
		$items = $stmt->fetchAll();
		if(!empty($items))
		{
?>

			<!--manage page-->
			<h1 class="text-center"> Manage Items </h1>
			<div class="container">
				<div class="table-responsive">
					<table class="main-table table table-bordered text-center">
						<thead>
							<tr>
								<td>Item Id</td>
								<td>Name</td>
								<td>Description</td>
								<td>Price</td>
								<td>Adding Date</td>
								<td>Category</td>
								<td>Username</td>
								<td>Control</td>
							</tr>
						</thead>

				<?php 
					foreach ($items as $item)
					{
				?>

						<tbody>
							<tr>
								<td><?php echo $item['item_id'];?></td>
								<td><?php echo $item['name'];?></td>
								<td><?php echo $item['description'];?></td>
								<td><?php echo $item['price'];?></td>
								<td><?php echo $item['add_date'];?></td>
								<td><?php echo $item['cat_name'];?></td>
								<td><?php echo $item['username'];?></td>
								<td>
									<a href="items.php?do=edit&item_id=<?php echo $item['item_id'];?>" class="btn btn-success"><i class="fa fa-edit"></i>
									Edit </a>
									<a href="items.php?do=delete&item_id=<?php echo $item['item_id'];?>" class="btn btn-danger confirm"><i class="fa fa-close"></i> Delete </a>

									<?php
										if($item['approve'] == 0)
										{
											echo "<a href='items.php?do=approve&item_id=".$item['item_id']."' class='btn btn-info'><i class='fa fa-check'></i> Approve </a>";
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
				<a href="items.php?do=add" class="btn btn-primary"><i class="fa fa-plus"></i> Add New Item </a>
			</div>
		
		<?php
		}
		else
		{
			echo '<div class="container">';
				echo '<div class="nice-message">The is no record to show</div>';
				echo '<a href="items.php?do=add" class="btn btn-primary"><i class="fa fa-plus"></i> Add New Item </a>';
			echo '</div>';
		}
	}





	elseif ($do == 'add')
	{
		?>

		<!--add page-->
		<h1 class="text-center"> Add New Item </h1>
		<div class="container">
			<form class="form-horizontal" action="?do=insert" method="POST">

				<div class="form-group form-group-lg">
					<label class="col-sm-2 control-label">Name</label>
					<div class="col-sm-10 col-md-8">
						<input type="text" name="name" class="form-control" required="required" placeholder="Name Of The Item">
					</div>
				</div>

				<div class="form-group form-group-lg">
					<label class="col-sm-2 control-label">Description</label>
					<div class="col-sm-10 col-md-8">
						<input type="text" name="description" class="form-control" required="required" placeholder="Description Of The Item">
					</div>
				</div>

				<div class="form-group form-group-lg">
					<label class="col-sm-2 control-label">Price</label>
					<div class="col-sm-10 col-md-8">
						<input type="text" name="price" class="form-control" required="required" placeholder="Price Of The Item">
					</div>
				</div>

				<div class="form-group form-group-lg">
					<label class="col-sm-2 control-label">Country</label>
					<div class="col-sm-10 col-md-8">
						<input type="text" name="country" class="form-control" required="required" placeholder="Country Of The Item">
					</div>
				</div>

				<div class="form-group form-group-lg">
					<label class="col-sm-2 control-label">Status</label>
					<div class="col-sm-10 col-md-8">
						<select class="form-control" name="status">
							<option value="0">...</option>
							<option value="1">New</option>
							<option value="2">Like New</option>
							<option value="3">Used</option>
							<option value="4">Old</option>
						</select>
					</div>
				</div>

				<div class="form-group form-group-lg">
					<label class="col-sm-2 control-label">Member</label>
					<div class="col-sm-10 col-md-8">
						<select class="form-control" name="member">
							<option value="0">...</option>

							<?php
								$users = getAll("*","users","","","userId");
								foreach ($users as $user)
								{
									echo "<option value='".$user['userId']."'>".$user['username']."</option>";
								}
							?>
						</select>
					</div>
				</div>

				<div class="form-group form-group-lg">
					<label class="col-sm-2 control-label">Category</label>
					<div class="col-sm-10 col-md-8">
						<select class="form-control" name="category">
							<option value="0">...</option>

							<?php
								$categories = getAll("*","categories","WHERE parent = 0","","id");
								foreach ($categories as $category)
								{
									echo "<option value='".$category['id']."'>".$category['name']."</option>";
									$childCats = getAll("*","categories","WHERE parent = {$category['id']}","","id");
									foreach ($childCats as $child)
									{
										echo "<option value='".$child['id']."'>--- ".$child['name']."</option>";
									}
								}
							?>
						</select>
					</div>
				</div>

				<div class="form-group form-group-lg">
					<label class="col-sm-2 control-label">Tags</label>
					<div class="col-sm-10 col-md-8">
						<input type="text" name="tags" class="form-control" placeholder="Separate Tags With Comma (,)">
					</div>
				</div>

				<div class="form-group form-group-lg">
					<div class="col-sm-offset-2 col-sm-10">
						<input type="submit" value="Add Item" class="btn btn-primary btn-lg">
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
			echo "<h1 class='text-center'> Update Item </h1>";
			echo "<div class = 'container'>";

			$name    	 = $_POST['name'];
			$description = $_POST['description'];
			$price   	 = $_POST['price'];
			$country 	 = $_POST['country'];
			$status  	 = $_POST['status'];
			$member  	 = $_POST['member'];
			$category  	 = $_POST['category'];
			$tags  	 	 = $_POST['tags'];

			//validate the form
			$formErrors = array();
			if (empty($name))
			{
				$formErrors[] = 'name can not be empty';
			}
			if (empty($description))
			{
				$formErrors[] = 'description can not be empty';
			}
			if (empty($price))
			{
				$formErrors[] = 'price can not be empty';
			}
			if (empty($country))
			{
				$formErrors[] = 'country can not be empty';
			}
			if ($status == 0)
			{
				$formErrors[] = 'you must choose status';
			}
			if ($member == 0)
			{
				$formErrors[] = 'you must choose member';
			}
			if ($category == 0)
			{
				$formErrors[] = 'you must choose category';
			}

			//loop into errors array and print it
			foreach ($formErrors as $error)
			{
				echo '<div class="alert alert-danger">' . $error . '</div>';
			}

			//if there is no errors
			if (empty($formErrors))
			{

				//insert the data into the database
				$stmt = $con->prepare("INSERT INTO items
									   (name,description,price,country_made,status,add_date,member_id,cat_id,tags)
									   VALUES (?,?,?,?,?,now(),?,?)"
									 );
				$stmt->execute(array($name,$description,$price,$country,$status,$member,$category,$tags));
				$msg = "<div class='alert alert-success'> data iserted successfully </div>";
				redirectHome($msg,'back');
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
		if (isset($_GET['item_id']) && is_numeric($_GET['item_id']))
		{
			$item_id = intval($_GET['item_id']);
		}
		else
		{
			$item_id = 0;
		}
		$stmt  = $con->prepare("SELECT * FROM items WHERE item_id = ?");
		$stmt->execute(array($item_id));
		$item  = $stmt->fetch();
		$count = $stmt->rowCount();

		if ($count > 0)
		{
	?>

			<!--add page-->
		<h1 class="text-center"> Edit Item </h1>
		<div class="container">
			<form class="form-horizontal" action="?do=update" method="POST">

				<input type="hidden" name="item_id" value="<?php echo $item['item_id'];?>">
				<div class="form-group form-group-lg">
					<label class="col-sm-2 control-label">Name</label>
					<div class="col-sm-10 col-md-8">
						<input type="text" name="name" class="form-control" required="required" placeholder="Name Of The Item" value="<?php echo $item['name'];?>">
					</div>
				</div>

				<div class="form-group form-group-lg">
					<label class="col-sm-2 control-label">Description</label>
					<div class="col-sm-10 col-md-8">
						<input type="text" name="description" class="form-control" required="required" placeholder="Description Of The Item" value="<?php echo $item['description'];?>">
					</div>
				</div>

				<div class="form-group form-group-lg">
					<label class="col-sm-2 control-label">Price</label>
					<div class="col-sm-10 col-md-8">
						<input type="text" name="price" class="form-control" required="required" placeholder="Price Of The Item" value="<?php echo $item['price'];?>">
					</div>
				</div>

				<div class="form-group form-group-lg">
					<label class="col-sm-2 control-label">Country</label>
					<div class="col-sm-10 col-md-8">
						<input type="text" name="country" class="form-control" required="required" placeholder="Country Of The Item" value="<?php echo $item['country_made'];?>">
					</div>
				</div>

				<div class="form-group form-group-lg">
					<label class="col-sm-2 control-label">Status</label>
					<div class="col-sm-10 col-md-8">
						<select class="form-control" name="status">
							<option value="1" <?php if($item['status']==1){echo'selected';}?>>New</option>
							<option value="2" <?php if($item['status']==2){echo'selected';}?>>Like New</option>
							<option value="3" <?php if($item['status']==3){echo'selected';}?>>Used</option>
							<option value="4" <?php if($item['status']==4){echo'selected';}?>>Old</option>
						</select>
					</div>
				</div>

				<div class="form-group form-group-lg">
					<label class="col-sm-2 control-label">Member</label>
					<div class="col-sm-10 col-md-8">
						<select class="form-control" name="member">

							<?php
								$stmt = $con->prepare("SELECT * FROM users");
								$stmt->execute();
								$users = $stmt->fetchAll();
								foreach ($users as $user)
								{
									echo "<option value='".$user['userId']."'";
									if($item['member_id']==$user['userId']){echo'selected';}
									echo">".$user['username']."</option>";
								}
							?>
						</select>
					</div>
				</div>

				<div class="form-group form-group-lg">
					<label class="col-sm-2 control-label">Category</label>
					<div class="col-sm-10 col-md-8">
						<select class="form-control" name="category">

							<?php
								$stmt = $con->prepare("SELECT * FROM categories");
								$stmt->execute();
								$categories = $stmt->fetchAll();
								foreach ($categories as $category)
								{
									echo "<option value='".$category['id']."'";
									if($item['cat_id']==$category['id']){echo'selected';}
									echo ">".$category['name']."</option>";
								}
							?>
						</select>
					</div>
				</div>

				<div class="form-group form-group-lg">
					<label class="col-sm-2 control-label">Tags</label>
					<div class="col-sm-10 col-md-8">
						<input type="text" name="tags" class="form-control" placeholder="Separate Tags With Comma (,)" value="<?php echo $item['tags'];?>">
					</div>
				</div>

				<div class="form-group form-group-lg">
					<div class="col-sm-offset-2 col-sm-10">
						<input type="submit" value="Save Item" class="btn btn-primary btn-lg">
					</div>
				</div>
			</form>

			<?php
				$stmt = $con->prepare(" SELECT comments.*,users.username AS user
									FROM comments
									INNER JOIN users
									ON users.userId = comments.user_id
									WHERE item_id = ?
								 ");
				$stmt->execute(array($item_id));
				$rows = $stmt->fetchAll();
				if (!empty($rows))
				{
			?>

					<!--manage page-->
					<h1 class="text-center"> Manage [<?php echo $item['name'];?>] Comments </h1>
					<div class="table-responsive">
						<table class="main-table table table-bordered text-center">
							<thead>
								<tr>
									<td>Comment</td>
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
									<td><?php echo $row['comment'];?></td>
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

			<?php
				}
			?>
			
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
		echo "<h1 class='text-center'> Update Item </h1>";
		echo "<div class = 'container'>";

		if ($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$item_id   	 = $_POST['item_id'];
			$name 		 = $_POST['name'];
			$description = $_POST['description'];
			$price 		 = $_POST['price'];
			$country 	 = $_POST['country'];
			$status 	 = $_POST['status'];
			$category 	 = $_POST['category'];
			$member 	 = $_POST['member'];
			$tags 	 	 = $_POST['tags'];

			//validate the form
			$formErrors = array();
			if (empty($name))
			{
				$formErrors[] = 'name can not be empty';
			}
			if (empty($description))
			{
				$formErrors[] = 'description can not be empty';
			}
			if (empty($price))
			{
				$formErrors[] = 'price can not be empty';
			}
			if (empty($country))
			{
				$formErrors[] = 'country can not be empty';
			}
			if ($status == 0)
			{
				$formErrors[] = 'you must choose status';
			}
			if ($category == 0)
			{
				$formErrors[] = 'you must choose category';
			}
			if ($member == 0)
			{
				$formErrors[] = 'you must choose member';
			}

			//loop into errors array and print it
			foreach ($formErrors as $error)
			{
				echo '<div class="alert alert-danger">' . $error . '</div>';
			}

			//if there is no errors
			if (empty($formErrors))
			{
				//update the data in the database with this info
				$stmt = $con->prepare(" UPDATE items
										SET name 		 = ?,
											description  = ?,
											price 		 = ?,
											country_made = ?,
											status 		 = ?,
											cat_id 		 = ?,
											member_id 	 = ?,
											tags 		 = ?
										WHERE item_id 	 = ?
									 ");
				$stmt->execute(array(
										$name,
										$description,
										$price,
										$country,
										$status,
										$category,
										$member,
										$tags,
										$item_id
									)
							  );

				$msg = "<div class='alert alert-success'> data updated successfully </div>";
				redirectHome($msg,'back');
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
		echo "<h1 class='text-center'> Delete Item </h1>";
		echo "<div class = 'container'>";

		if (isset($_GET['item_id']) && is_numeric($_GET['item_id']))
		{
			$item_id = intval($_GET['item_id']);
		}
		else
		{
			$item_id = 0;
		}

		$check = checkItem('item_id','items',$item_id);

		if ($check > 0)
		{
			$stmt = $con->prepare("DELETE FROM items WHERE item_id = ?");
			$stmt->execute(array($item_id));
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
		echo "<h1 class='text-center'> Approve Item </h1>";
		echo "<div class = 'container'>";

		if (isset($_GET['item_id']) && is_numeric($_GET['item_id']))
		{
			$item_id = intval($_GET['item_id']);
		}
		else
		{
			$item_id = 0;
		}

		$check = checkItem('item_id','items',$item_id);

		if ($check > 0)
		{
			$stmt = $con->prepare("UPDATE items SET approve = 1 WHERE item_id = ?");
			$stmt->execute(array($item_id));
			$msg = "<div class='alert alert-success'>item approved successfully</div>";
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

ob_end_flush(); //release output

?>