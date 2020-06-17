<?php 
	include('init.php');
?>


<div class="container">
	<h1 class="text-center">Show Category Items</h1>
	<div class="row">

	<?php
		if (isset($_GET['pageId']) && is_numeric($_GET['pageId']))
		{
			$category = intval($_GET['pageId']);
			$items = getAll("*","items","where cat_id = {$category}","AND approve = 1","item_id");
			foreach ($items as $item)
			{
				echo '<div class="col-sm-6 col-md-3">';
					echo '<div class="thumbnail item-box">';
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
		}
		else
		{
			echo "You Must Add Page Id";
		}
	?>

	</div>
</div>

<?php
	include($tpl.'footer.php');
?>