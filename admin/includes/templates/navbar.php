<nav class="navbar navbar-inverse">
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-nav" aria-expanded="false">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="dashboard.php">Home</a>
		</div>
		<div class="collapse navbar-collapse" id="app-nav">
			<ul class="nav navbar-nav">
				<li><a href="categories.php">Categories</a></li>
				<li><a href="items.php">Items</a></li>
				<li><a href="members.php">Members</a></li>
				<li><a href="comments.php">Comments</a></li>
				<li><a href="#">Statistics</a></li>
				<li><a href="#">Logs</a></li>
			</ul>
			<ul class="nav navbar-nav navbar-right">
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Ahmed<span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="../index.php">Visit Main Site</a></li>
						<li><a href="members.php?do=edit&userId=<?php echo $_SESSION['id'];?>">Edit Profile</a></li>
						<li><a href="#">Setting</a></li>
						<li><a href="logout.php">Logout</a></li>
					</ul>
				</li>
			</ul>
		</div>
	</div>
</nav>