<?php 
	function lang($phrase)
	{
		static $lang = array(
			'message' => 'ahlan',
			'admin' => 'modeer'
		);
		
		return $lang[$phrase];
	}
?>