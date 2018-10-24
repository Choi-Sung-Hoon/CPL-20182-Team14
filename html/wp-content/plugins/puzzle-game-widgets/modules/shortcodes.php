<?php 
add_shortcode( 'puzzle-game', 'pgw_shortcode_method' );
function pgw_shortcode_method( $atts ) {
	$PuzzleTypes = pgw_get_puzzle_types();
	unset($PuzzleTypes['rand']);
	$puzzle_type = isset($atts['type']) ? $atts['type'] : 'rand';
	$puzzle_type = array_key_exists($puzzle_type, $PuzzleTypes) ? $puzzle_type : array_rand($PuzzleTypes);
	switch($puzzle_type){
		case '3x3': $puzzle_type = 3; break;
		case '4x4': $puzzle_type = 4; break;
		case '5x5': $puzzle_type = 5; break;
	}
	$puzzle_size = isset($atts['size']) ? $atts['size'] : 'medium';
	
	// Geting Colors from DB
	$background_color 	= 'pgs_background_color';
	$border_color   	= 'pgs_border_color';
	
	$bg_color = get_option( $background_color );
	$bd_color = get_option( $border_color );
	
	$puzzle_bg_color = (isset($bg_color) && $bg_color) ? $bg_color : '#FFFFFF';
	$puzzle_bd_color = (isset($bd_color) && $bd_color) ? $bd_color : '#333333';
	
	$puzzle_uniqid = pgw_get_random_string();
	
	$puzzle_game_class = 'puzzle-game-'.$puzzle_type.'-'.$puzzle_size;
	
	$puzzle = array();
	$puzzle_output = '';
	$correct_puzzle = array();
	
	$open_number = $puzzle_type * $puzzle_type;
	
	$puzzle_key = 1;
	for($i = 0; $i < $puzzle_type; $i++){
		$puzzle[$i] = array();
		for($j = 0; $j < $puzzle_type; $j++){
			$puzzle[$i][$j] = $puzzle_key;
			$puzzle_key++;
		}
	}
	
	$open_x = $i-1;
	$open_y = $j-1;
	
	$puzzle[$open_x][$open_y] = 'open';
	//echo '<pre>'; print_r($puzzle);
	$correct_puzzle = $puzzle;
	
	for($l = 0; $l < 1000; $l++){
		$availables = array();
		$availables[] = array($open_x, $open_y + 1);
		$availables[] = array($open_x, $open_y - 1);
		$availables[] = array($open_x + 1, $open_y);
		$availables[] = array($open_x - 1, $open_y);
		
		if(count($availables) > 0) foreach($availables as $a_key => $available){
			if(! isset($puzzle[$available[0]][$available[1]]))
				unset($availables[$a_key]);
		}
		//echo '<pre>'; print_r($availables);
		if(count($availables) > 0){
			$next_key = array_rand($availables);
			$next_item_keys = $availables[$next_key];
			$puzzle[$open_x][$open_y] = $puzzle[$next_item_keys[0]][$next_item_keys[1]];
			$puzzle[$next_item_keys[0]][$next_item_keys[1]] = 'open';
			$open_x = $next_item_keys[0];
			$open_y = $next_item_keys[1];
		}
	}
	
	$puzzle_output .= '		
		<div id="'.$puzzle_uniqid.'" class="puzzle-game-container '.$puzzle_game_class.'">
			<table class="puzzle-game">
	';
					foreach($puzzle as $row){
						$puzzle_output .= '<tr>';
						foreach($row as $cell){
							if($cell == 'open')
								$puzzle_output .= '<td class="open">&nbsp;</td>';
							else
								$puzzle_output .= '<td class="piece-'.$cell.'">&nbsp;</td>';
						}
						$puzzle_output .= '</tr>';
					}
	$puzzle_output .= '
			</table>
			<p align="center">Steps: <span class="puzzle_steps">0</span></p>
		</div>
		<style type="text/css">
		#'.$puzzle_uniqid.'.puzzle-game-container td.open{
			background:'.$puzzle_bg_color.' !important;
		}
		#'.$puzzle_uniqid.'.puzzle-game-container{
			border:1px solid '.$puzzle_bd_color.' !important;
		}
		#'.$puzzle_uniqid.'.puzzle-game-container td{
			background-image:url('.plugins_url( 'img/puzzle-'.$puzzle_type.'x'.$puzzle_type.'-'.$puzzle_size.'.png' , __FILE__ ).');
		}
		</style>
		<script type="text/javascript">
			$puzzle_game_vars["'.$puzzle_uniqid.'"] = '.json_encode($puzzle).';
			$puzzle_game_orgs["'.$puzzle_uniqid.'"] = '.json_encode($correct_puzzle).';
			$puzzle_game_opens["'.$puzzle_uniqid.'"] = "piece-'.$open_number.'";
			$puzzle_game_steps["'.$puzzle_uniqid.'"] = 0;
		</script>
	';
		
	
	return $puzzle_output; 
}

?>