<?php 
	add_action('admin_menu', 'puzzle_game_settings_menu');
	
	function puzzle_game_settings_menu() {
		add_menu_page('Puzzle Game Settings', 'Puzzle Game Settings', 'manage_options', 'puzzle-game-settings', 'puzzle_game_settings_page', plugins_url('puzzle-game-widgets/admin/img/puzzle-settings-icon.png'));
	}
	
	function puzzle_game_settings_page(){
		
	    //must check that the user has the required capability 
	    if (!current_user_can('manage_options'))
	    {
	      wp_die( __('You do not have sufficient permissions to access this page.') );
	    }
	
	    // variables for the field and option names 
	    $hidden_field_name 	= 'pgs_submit_hidden';
	    $background_color 	= 'pgs_background_color';
		$border_color   	= 'pgs_border_color';
	
	    // See if the user has posted us some information
	    // If they did, this hidden field will be set to 'Y'
	    if( isset($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == 'Y' ) {
	        	
	        // Save the posted value in the database
	        update_option( $background_color, $_POST[ $background_color ] );
	        update_option( $border_color, $_POST[ $border_color ] );
	        // Put an settings updated message on the screen
	
	?>
	<div class="updated"><p><strong><?php _e('Settings saved.', 'menu-test' ); ?></strong></p></div>
	<?php
	    }
	    // Now display the settings editing screen
	    echo '<div class="wrap">';
	    // header
	    echo "<h2>" . __( 'Puzzle Game Settings', 'puzzle-game-settings' ) . "</h2>";
	    // settings form
	    ?>
	<form name="puzzle-game-settings" method="post" action="">
	<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
	
	<p><?php _e("Background Color:" ); ?> 
	<input type="text" name="<?php echo $background_color; ?>" 
		value="<?php $bg_color = get_option( $background_color ); echo $bg_color ? $bg_color : '#FFFFFF'; ?>" 
		size="20">
	</p>
	<p><?php _e("Border Color:" ); ?> 
	<input type="text" name="<?php echo $border_color; ?>" 
		value="<?php $bd_color = get_option( $border_color ); echo $bd_color ? $bd_color : '#333333'; ?>" 
		size="20">
	</p>
	<hr />
	
	<p class="submit">
	<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
	</p>
	
	</form>
	</div>
	<?php
	}
?>