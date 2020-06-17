<?php 
	function lang($phrase)
	{
		static $lang = array(
			'message' => 'welcome',
			'admin' => 'adminstrator'
		);
		
		return $lang[$phrase];
	}
?>