<nav class="navbar navbar-inverse">
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-nav" aria-expanded="false">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="index.php">Home</a>
		</div>
		<div class="collapse navbar-collapse" id="app-nav">
			<ul class="nav navbar-nav navbar-right">
				<?php
					$categories = getAll("*","categories","WHERE parent = 0","","id","ASC");
					foreach ($categories as $cat)
					{
						echo '<li><a href="categories.php?pageId='.$cat['id'].'">'.$cat['name'].'</a></li>';
					}
				?>
			</ul>
		</div>
	</div>
</nav>