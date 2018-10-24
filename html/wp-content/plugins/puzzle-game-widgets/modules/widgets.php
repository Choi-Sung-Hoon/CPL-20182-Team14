<?php 
/**
 * Adds Foo_Widget widget.
 */
class Puzzle_Game_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'puzzle_game_widgets', // Base ID
			__('Puzzle Game Widget', 'text_domain'), // Name
			array( 'description' => __( 'Free Puzzle Games.', 'text_domain' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] );
		$puzzle_type =  $instance['puzzle_type'] ;
		$puzzle_size =  $instance['puzzle_size'] ;
		
		$PuzzleTypes = pgw_get_puzzle_types();
		unset($PuzzleTypes['rand']);
		$puzzle_type = isset($puzzle_type) ? $puzzle_type : 'rand';
		$puzzle_type = array_key_exists($puzzle_type, $PuzzleTypes) ? $puzzle_type : array_rand($PuzzleTypes);
		
		$PuzzleSizes = pgw_get_puzzle_sizes();
		$puzzle_size = array_key_exists($puzzle_size, $PuzzleSizes) ? $puzzle_size : 'medium';
		
		echo $args['before_widget'];
		if ( ! empty( $title ) )
			echo $args['before_title'] . $title . $args['after_title'];
		
		echo do_shortcode('[puzzle-game type="'.$puzzle_type.'" size="'.$puzzle_size.'" ]');
		/*
		if( $puzzle_type ){
			echo do_shortcode('[puzzle-game id="'.$puzzle_type.'"]');
		}else{
			echo do_shortcode('[puzzle-game]');
		}*/
		
		echo $args['after_widget'];
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __( 'Can you solve this Puzzle?', 'text_domain' );
		}
		$puzzle_type = isset( $instance['puzzle_type'] ) ? $instance['puzzle_type'] : 'rand';
		$puzzle_size = isset( $instance['puzzle_size'] ) ? $instance['puzzle_size'] : 'medium';
		
		// Get Puzzle Types
		$PuzzleTypes = pgw_get_puzzle_types();
		// Get Puzzle Sizes
		$PuzzleSizes = pgw_get_puzzle_sizes();
				
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('puzzle_type'); ?>"><?php _e( 'Puzzle Type:' ); ?></label> 
			<select id="<?php echo $this->get_field_id('puzzle_type'); ?>" name="<?php echo $this->get_field_name('puzzle_type'); ?>">
		<?php
			foreach ( $PuzzleTypes as $PT_Key => $PT_Val ) {
				echo '<option value="' . $PT_Key . '"'
					. selected( $puzzle_type, $PT_Key, false )
					. '>'. $PT_Val . '</option>';
			}
		?>
			</select>			
		</p>
        <p>
			<label for="<?php echo $this->get_field_id('puzzle_size'); ?>"><?php _e( 'Puzzle Size:' ); ?></label> 
			<select id="<?php echo $this->get_field_id('puzzle_size'); ?>" name="<?php echo $this->get_field_name('puzzle_size'); ?>">
		<?php
			foreach ( $PuzzleSizes as $PS_Key => $PS_Val ) {
				echo '<option value="' . $PS_Key . '"'
					. selected( $puzzle_size, $PS_Key, false )
					. '>'. $PS_Val . '</option>';
			}
		?>
			</select>			
		</p>
		<?php 
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['puzzle_type'] = $new_instance['puzzle_type'];
		$instance['puzzle_size'] = $new_instance['puzzle_size'];		

		return $instance;
	}

} // class Foo_Widget

// register Foo_Widget widget
function register_Puzzle_Game_Widget() {
    register_widget( 'Puzzle_Game_Widget' );
}
add_action( 'widgets_init', 'register_Puzzle_Game_Widget' );
?>