<?php 

add_action('wp_print_scripts', 'pgw_puzzle_game_scripts_method');
function pgw_puzzle_game_scripts_method(){

	if(is_admin()){
	}else{
		wp_enqueue_script('puzzle_game_js', plugins_url('/js/puzzle-game.js', __FILE__ ), array('jquery'), '1.0' ) ;
		wp_enqueue_style('puzzle_game_css', plugins_url('/css/puzzle-game.css', __FILE__ ) ) ;
	}
}
?>