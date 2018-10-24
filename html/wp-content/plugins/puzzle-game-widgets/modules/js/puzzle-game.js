var $puzzle_game_vars = [];
var $puzzle_game_orgs = [];
var $puzzle_game_opens = [];
var $puzzle_game_steps = [];

jQuery(document).ready( function($){
	
function CheckPuzzle(id){
	var $return = true;
	var $puzzle = $puzzle_game_vars[id];
	var $correct_puzzle = $puzzle_game_orgs[id];
	
	$.each($puzzle, function(i, $puzzle_row) {
		$.each($puzzle_row, function(j, item) {
			if($correct_puzzle[i][j] != item)
				$return = false;
		});
	});
	$('#' + id).find('.puzzle_steps').text($puzzle_game_steps[id]);
	return $return;
}

	$('.puzzle-game').find('td').click(function(){
		var $item = $(this);
		var $puzzle_container = $item.parents('.puzzle-game-container');
		var $puzzle_game_id = $puzzle_container.attr('id');
		
		var $puzzle = $puzzle_game_vars[$puzzle_game_id];
		var $open_img = $puzzle_game_opens[$puzzle_game_id];		
		
		var $open = $puzzle_container.find('.open');
		var $open_xy = [$open.closest('tr').index(), $open.index()];
		var $item_xy = [$item.closest('tr').index(), $item.index()];
		
		if(($open_xy[0] == $item_xy[0] && ($open_xy[1] == $item_xy[1]+1 || $open_xy[1] == $item_xy[1]-1)) || ($open_xy[1] == $item_xy[1] && ($open_xy[0] == $item_xy[0]+1 || $open_xy[0] == $item_xy[0]-1))){
			$open.attr('class', $item.attr('class'));
			$puzzle[$open_xy[0]][$open_xy[1]] = $puzzle[$item_xy[0]][$item_xy[1]];
			$item.attr('class', 'open');
			$puzzle[$item_xy[0]][$item_xy[1]] = 'open';
			$puzzle_game_steps[$puzzle_game_id]++;
			if(CheckPuzzle($puzzle_game_id)){
				$puzzle_container.find('.open').attr('class', $open_img);
				alert('Well Done!!!');
			}
		}
		
	});

	
})