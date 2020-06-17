<?php
	ob_start();
	session_start();
	$page_title = 'Home';
	include('init.php');
?>


<div class="container">
	<div class="row">

	<?php
		$allItems = getAll('*','items','WHERE approve = 1','','item_id');
		foreach ($allItems as $item)
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
	?>

	</div>
</div>

<?php
	include($tpl.'footer.php');
	ob_end_flush();
?>