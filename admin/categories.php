<?php
ob_start(); //output buffering start
session_start();
$page_title = 'Categories';

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
		$sort = 'ASC';
		$sort_array = array('ASC','DESC');
		if(isset($_GET['sort']) && in_array($_GET['sort'],$sort_array))
		{
			$sort = $_GET['sort'];
		}
		$stmt = $con->prepare("SELECT * FROM categories WHERE parent = 0 ORDER BY ordering $sort");
		$stmt->execute();
		$categories = $stmt->fetchAll();
	?>

		<!--manage page-->
		<h1 class="text-center"> Manage Categories </h1>
		<div class="container categories">
			<div class="panel panel-default">
				<div class="panel-heading">
					<i class="fa fa-edit"></i> Manage Categories
					<div class="option pull-right">
						<i class="fa fa-sort"></i> Ordering:[
						<a class="<?php if($sort == 'ASC'){ echo'active'; } ?>" href="?sort=ASC">Asc</a> |
						<a class="<?php if($sort == 'DESC'){ echo'active'; } ?>" href="?sort=DESC">Desc</a> ]
						<i class="fa fa-eye"></i> View:[
						<span class="active" data-view="full">Full</span> |
						<span data-view="classic">Classic</span> ]
					</div>
				</div>
				<div class="panel-body">
					
					<?php
						foreach ($categories as $category)
						{
							echo "<div class='cat'>";
								echo "<div class='hidden-buttons'>";
									echo "<a href='categories.php?do=edit&categoryId=".$category['id']."' class='btn btn-primary btn-xs'><i class='fa fa-edit'></i> Edit </a>";
									echo "<a href='categories.php?do=delete&categoryId=".$category['id']."' class='confirm btn btn-danger btn-xs'><i class='fa fa-close'></i> Delete </a>";
								echo "</div>";
								echo "<h3>".$category['name']."</h3>";
								echo "<div class='full-view'>";
									echo "<p>";
											if($category['description']=='')
												{
													echo "this category has no description";
												}
												else
												{
													echo $category['description'];
												}
									echo "</p>";
									if($category['visibility'] == 1)
									{
										echo "<span class='visibility'><i class='fa fa-eye'></i> Visibility Hidden</span>";
									}
									if($category['allow_comments'] == 1)
									{
										echo "<span class='comments'><i class='fa fa-close'></i> Comments Disabled</span>";
									}
									if($category['allow_ads'] == 1)
									{
										echo "<span class='advertises'><i class='fa fa-close'></i> Advertises Disabled</span>";
									}
								echo "</div>";
							$childCats = getAll("*","categories","WHERE parent = {$category['id']}","","id","ASC");
							if(!empty($childCats))
							{
								echo "<h4 class='child-head'>Child Categories</h4>";
								echo "<ul class='list-unstyled child-cats'>";
								foreach($childCats as $childCat)
								{
									echo "<li class='child-link'>
											<a href='categories.php?do=edit&categoryId=".$childCat['id']."'>".$childCat['name']."</a>
											<a href='categories.php?do=delete&categoryId=".$childCat['id']."' class='show-delete confirm'>Delete</a>
										</li>";
								}
								echo "</ul>";
							}
							echo "</div>";
							echo "<hr>";
						}
					?>

				</div>				
			</div>
			<a href="categories.php?do=add" class="add-category btn btn-primary"><i class="fa fa-plus"></i> Add New Category </a>
		</div>

	<?php
	}





	elseif ($do == 'add')
	{
	?>

		<!--add page-->
		<h1 class="text-center"> Add New Category </h1>
		<div class="container">
			<form class="form-horizontal" action="?do=insert" method="POST">

				<div class="form-group form-group-lg">
					<label class="col-sm-2 control-label">Name</label>
					<div class="col-sm-10 col-md-8">
						<input type="text" name="name" class="form-control" autocomplete="off" required="required" placeholder="Name Of The Category">
					</div>
				</div>

				<div class="form-group form-group-lg">
					<label class="col-sm-2 control-label">Description</label>
					<div class="col-sm-10 col-md-8">
						<input type="text" name="description" class="form-control" placeholder="Describe The Category">
					</div>
				</div>

				<div class="form-group form-group-lg">
					<label class="col-sm-2 control-label">Parent</label>
					<div class="col-sm-10 col-md-8">
						<select class="form-control" name="parent">
							<option value="0">None</option>

							<?php
								$Categories = getAll("*","categories","WHERE parent = 0","","id","ASC");
								foreach ($Categories as $category)
								{
									echo "<option value='".$category['id']."'>".$category['name']."</option>";
								}
							?>
						</select>
					</div>
				</div>

				<div class="form-group form-group-lg">
					<label class="col-sm-2 control-label">Ordering</label>
					<div class="col-sm-10 col-md-8">
						<input type="text" name="ordering" class="form-control" placeholder="Number To Arrange The Categories">
					</div>
				</div>

				<div class="form-group form-group-lg">
					<label class="col-sm-2 control-label">Visible</label>
					<div class="col-sm-10 col-md-8">
						<div>
							<input id="vis-yes" type="radio" name="visibility" value="0" checked>
							<label for="vis-yes">Yes</label>
						</div>
						<div>
							<input id="vis-no" type="radio" name="visibility" value="1">
							<label for="vis-no">No</label>
						</div>
					</div>
				</div>

				<div class="form-group form-group-lg">
					<label class="col-sm-2 control-label">Allow Comments</label>
					<div class="col-sm-10 col-md-8">
						<div>
							<input id="com-yes" type="radio" name="allow_comments" value="0" checked>
							<label for="com-yes">Yes</label>
						</div>
						<div>
							<input id="com-no" type="radio" name="allow_comments" value="1">
							<label for="com-no">No</label>
						</div>
					</div>
				</div>

				<div class="form-group form-group-lg">
					<label class="col-sm-2 control-label">Allow Ads</label>
					<div class="col-sm-10 col-md-8">
						<div>
							<input id="ads-yes" type="radio" name="allow_ads" value="0" checked>
							<label for="ads-yes">Yes</label>
						</div>
						<div>
							<input id="ads-no" type="radio" name="allow_ads" value="1">
							<label for="ads-no">No</label>
						</div>
					</div>
				</div>

				<div class="form-group form-group-lg">
					<div class="col-sm-offset-2 col-sm-10">
						<input type="submit" value="Add Category" class="btn btn-primary btn-lg">
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
			echo "<h1 class='text-center'> Insert Category </h1>";
			echo "<div class = 'container'>";

			$name 		 	= $_POST['name'];
			$description 	= $_POST['description'];
			$parent     	= $_POST['parent'];
			$ordering    	= $_POST['ordering'];
			$visibility  	= $_POST['visibility'];
			$allow_comments = $_POST['allow_comments'];
			$allow_ads  	= $_POST['allow_ads'];

			//check if user exist in database
			$check = checkItem("name","categories",$name);
			if ($check == 1)
			{
				$msg = "<div class='alert alert-danger'> Sorry this category is already exist </div>";
				redirectHome($msg,'back');
			}
			else
			{
				//insert the data into the database
				$stmt = $con->prepare("INSERT INTO categories(name,description,parent,ordering,visibility,allow_comments,allow_ads)
										VALUES (?,?,?,?,?,?,?)");
				$stmt->execute(array($name,$description,$parent,$ordering,$visibility,$allow_comments,$allow_ads));
	 
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
		if (isset($_GET['categoryId']) && is_numeric($_GET['categoryId']))
		{
			$categoryId = intval($_GET['categoryId']);
		}
		else
		{
			$categoryId = 0;
		}
		$stmt = $con->prepare("SELECT * FROM categories WHERE id = ?");
		$stmt->execute(array($categoryId));
		$category = $stmt->fetch();
		$count = $stmt->rowCount();

		if ($count > 0)
		{
	?>

			<!--edit page-->
			<h1 class="text-center"> Edit Category </h1>
			<div class="container">
				<form class="form-horizontal" action="?do=update" method="POST">

					<input type="hidden" name="categoryId" value="<?php echo $categoryId;?>">
					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Name</label>
						<div class="col-sm-10 col-md-8">
							<input type="text" name="name" class="form-control" required="required" placeholder="Name Of The Category" value="<?php echo $category['name']; ?>">
						</div>
					</div>

					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Description</label>
						<div class="col-sm-10 col-md-8">
							<input type="text" name="description" class="form-control" placeholder="Describe The Category" value="<?php echo $category['description']; ?>">
						</div>
					</div>

					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Ordering</label>
						<div class="col-sm-10 col-md-8">
							<input type="text" name="ordering" class="form-control" placeholder="Number To Arrange The Categories" value="<?php echo $category['ordering']; ?>">
						</div>
					</div>

					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Parent</label>
						<div class="col-sm-10 col-md-8">
							<select class="form-control" name="parent">
								<option value="0">None</option>

								<?php
									$Cats = getAll("*","categories","WHERE parent = 0","","id","ASC");
									foreach ($Cats as $cat)
									{
										echo "<option value='".$cat['id']."'";
										if($category['parent'] == $cat['id'])
										{
											echo "selected";
										}
										echo ">".$cat['name']."</option>";
									}
								?>
							</select>
						</div>
					</div>

					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Visible</label>
						<div class="col-sm-10 col-md-8">
							<div>
								<input id="vis-yes" type="radio" name="visibility" value="0" <?php if($category['visibility'] == 0){echo "checked";}?> >
								<label for="vis-yes">Yes</label>
							</div>
							<div>
								<input id="vis-no" type="radio" name="visibility" value="1" <?php if($category['visibility'] == 1){echo "checked";}?> >
								<label for="vis-no">No</label>
							</div>
						</div>
					</div>

					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Allow Comments</label>
						<div class="col-sm-10 col-md-8">
							<div>
								<input id="com-yes" type="radio" name="allow_comments" value="0" <?php if($category['allow_comments'] == 0){echo "checked";}?> >
								<label for="com-yes">Yes</label>
							</div>
							<div>
								<input id="com-no" type="radio" name="allow_comments" value="1" <?php if($category['allow_comments'] == 1){echo "checked";}?> >
								<label for="com-no">No</label>
							</div>
						</div>
					</div>

					<div class="form-group form-group-lg">
						<label class="col-sm-2 control-label">Allow Ads</label>
						<div class="col-sm-10 col-md-8">
							<div>
								<input id="ads-yes" type="radio" name="allow_ads" value="0" <?php if($category['allow_ads'] == 0){echo "checked";}?> >
								<label for="ads-yes">Yes</label>
							</div>
							<div>
								<input id="ads-no" type="radio" name="allow_ads" value="1" <?php if($category['allow_ads'] == 1){echo "checked";}?> >
								<label for="ads-no">No</label>
							</div>
						</div>
					</div>

					<div class="form-group form-group-lg">
						<div class="col-sm-offset-2 col-sm-10">
							<input type="submit" value="Save Changes" class="btn btn-primary btn-lg">
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
		echo "<h1 class='text-center'> Update Category </h1>";
		echo "<div class = 'container'>";

		if ($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$categoryId   	= $_POST['categoryId'];
			$name 			= $_POST['name'];
			$description    = $_POST['description'];
			$parent 		= $_POST['parent'];
			$ordering 		= $_POST['ordering'];
			$visibility 	= $_POST['visibility'];
			$allow_comments = $_POST['allow_comments'];
			$allow_ads 		= $_POST['allow_ads'];

			//update the data in the database with this info
			$stmt = $con->prepare(" UPDATE categories
								    SET name 		   = ?,
								    	description    = ?,
								    	parent 		   = ?,
								   	    ordering 	   = ?,
								   	    visibility 	   = ?,
								   	    allow_comments = ?,
								   	    allow_ads 	   = ?
								    WHERE id 		   = ?"
								 );
			$stmt->execute(array
							    ( $name,
								  $description,
								  $parent,
								  $ordering,
								  $visibility,
								  $allow_comments,
								  $allow_ads,
								  $categoryId)
						  );

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
		echo "<h1 class='text-center'> Delete Category </h1>";
		echo "<div class = 'container'>";

		if (isset($_GET['categoryId']) && is_numeric($_GET['categoryId']))
		{
			$categoryId = intval($_GET['categoryId']);
		}
		else
		{
			$categoryId = 0;
		}

		$stmt = $con->prepare("SELECT * FROM categories WHERE id = ? LIMIT 1");
		$stmt->execute(array($categoryId));
		$count = $stmt->rowCount();

		if ($count > 0)
		{
			$stmt = $con->prepare("DELETE FROM categories WHERE id = ?");
			$stmt->execute(array($categoryId));
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

	include($tpl .'footer.php');
}
else
{
	header('Location:index.php');
	exit();
}

ob_end_flush(); //release the output

?>