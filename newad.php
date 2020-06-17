<?php
	session_start();
	$page_title = 'Create New Item';
	include('init.php');
	if(isset($_SESSION['user']))
	{
		if($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$form_errors = array();
			$name 		 = filter_var($_POST['name'],FILTER_SANITIZE_STRING);
			$description = filter_var($_POST['description'],FILTER_SANITIZE_STRING);
			$price 		 = filter_var($_POST['price'],FILTER_SANITIZE_NUMBER_INT);
			$country 	 = filter_var($_POST['country'],FILTER_SANITIZE_STRING);
			$status 	 = filter_var($_POST['status'],FILTER_SANITIZE_NUMBER_INT);
			$category 	 = filter_var($_POST['category'],FILTER_SANITIZE_NUMBER_INT);
			$tags 		 = filter_var($_POST['tags'],FILTER_SANITIZE_STRING);

			if(strlen($name) < 4 )
			{
				$form_errors[] = 'item title must be at least 4 character';
			}
			if(strlen($description) < 10 )
			{
				$form_errors[] = 'item description must be at least 10 character';
			}
			if(strlen($country) < 2 )
			{
				$form_errors[] = 'item country must be at least 2 character';
			}
			if(empty($price))
			{
				$form_errors[] = 'item price must be not empty';
			}
			if(empty($status))
			{
				$form_errors[] = 'item status must be not empty';
			}
			if(empty($category))
			{
				$form_errors[] = 'item category must be not empty';
			}

			//if there is no errors
			if (empty($form_errors))
			{

				//insert the data into the database
				$stmt = $con->prepare("INSERT INTO items
									   (name,description,price,country_made,status,add_date,cat_id,member_id,tags)
									   VALUES (?,?,?,?,?,now(),?,?,?)"
									 );
				$stmt->execute(array($name,$description,$price,$country,$status,$category,$_SESSION['id'],$tags));
				
				if($stmt)
				{
					$successMsg = "Item Added Successfully";
				}
			}
		}
?>


		<h1 class="text-center"> <?php echo $page_title;?> </h1>
		<div class="create-ad block">
			<div class="container">
				<div class="panel panel-primary">
					<div class="panel-heading"> <?php echo $page_title;?> </div>
					<div class="panel-body">
						<div class="row">
							<div class="col-md-8">
								<form class="form-horizontal main-form" action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">

									<div class="form-group form-group-lg">
										<label class="col-sm-3 control-label">Name</label>
										<div class="col-sm-10 col-md-9">
											<input type="text" name="name" class="form-control live" placeholder="Name Of The Item" data-class=".live-title" pattern=".{4,}" title="this field require at least 4 character" required="required">
										</div>
									</div>
									
									<div class="form-group form-group-lg">
										<label class="col-sm-3 control-label">Description</label>
										<div class="col-sm-10 col-md-9">
											<input type="text" name="description" class="form-control live" placeholder="Description Of The Item"
											data-class=".live-desc" pattern=".{10,}" title="this field require at least 10 character" required="required">
										</div>
									</div>
									
									<div class="form-group form-group-lg">
										<label class="col-sm-3 control-label">Price</label>
										<div class="col-sm-10 col-md-9">
											<input type="text" name="price" class="form-control live" placeholder="Price Of The Item" data-class=".live-price" required="required">
										</div>
									</div>
									
									<div class="form-group form-group-lg">
										<label class="col-sm-3 control-label">Country</label>
										<div class="col-sm-10 col-md-9">
											<input type="text" name="country" class="form-control" placeholder="Country Of The Item" required="required">
										</div>
									</div>
									
									<div class="form-group form-group-lg">
										<label class="col-sm-3 control-label">Status</label>
										<div class="col-sm-10 col-md-9">
											<select class="form-control" name="status" required="required">
												<option value="">...</option>
												<option value="1">New</option>
												<option value="2">Like New</option>
												<option value="3">Used</option>
												<option value="4">Old</option>
											</select>
										</div>
									</div>
									
									<div class="form-group form-group-lg">
										<label class="col-sm-3 control-label">Category</label>
										<div class="col-sm-10 col-md-9">
											<select class="form-control" name="category" required="required">
												<option value="">...</option>

												<?php
													$categories = getAll('*','categories','','','id');
													foreach ($categories as $category)
													{
														echo "<option value='".$category['id']."'>".$category['name']."</option>";
													}
												?>
											</select>
										</div>
									</div>

									<div class="form-group form-group-lg">
										<label class="col-sm-3 control-label">Tags</label>
										<div class="col-sm-10 col-md-9">
											<input type="text" name="tags" class="form-control" placeholder="Separate Tags With Comma (,)">
										</div>
									</div>

									<div class="form-group form-group-lg">
										<div class="col-sm-offset-3 col-sm-9">
											<input type="submit" value="Add Item" class="btn btn-primary btn-lg">
										</div>
									</div>

								</form>
							</div>
							<div class="col-md-4">
								<div class="thumbnail item-box live-preview">
									<span class="price">
										$ <span class="live-price">0</span>
									</span>
									<img class="img-responsive" src="img.png">
									<div class="caption">
										<h3 class="live-title">Title</h3>
										<p class="live-desc">Description</p>
									</div>
								</div>
							</div>
						</div>

						<?php
							if(!empty($form_errors))
							{
								foreach ($form_errors as $error)
								{
									echo '<div class="alert alert-danger">' .$error. '</div>';
								}
							}
							if(isset($successMsg))
							{
								echo '<div class="alert alert-success">' .$successMsg. '</div>';
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
?>