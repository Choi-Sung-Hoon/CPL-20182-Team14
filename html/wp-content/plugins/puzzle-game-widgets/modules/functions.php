<?php 
	function pgw_get_puzzle_types(){
		$PuzzleTypes = array(
			'rand'	=> 'Random Puzzle',
			'3x3' 	=> '3x3 Puzzle',
			'4x4' 	=> '4x4 Puzzle',
			'5x5' 	=> '5x5 Puzzle',
		);
		return $PuzzleTypes;
	}
	
	function pgw_get_puzzle_sizes(){
		$PuzzleSizes = array(
			'small'		=> 'Small',
			'medium'	=> 'Medium',
			'large' 	=> 'Large'
		);
		return $PuzzleSizes;
	}
	
	function pgw_get_random_string_old(){
		return substr( "abcdefghijklmnopqrstuvwxyz", mt_rand(0, 25) , 1) .substr( md5( time() ), 1);
	}
	
	function pgw_get_random_string($length = 10) {
		$characters = 'abcdefghijklmnopqrstuvwxyz';
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, strlen($characters) - 1)];
		}
		return $randomString;
	}
?>