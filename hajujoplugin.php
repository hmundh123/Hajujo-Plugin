<?php
	
/*
	Plugin Name: Hajujo Plugin
	Description: A widget to display set number of posts from the custom post type.
	Plugin URI: http://www.hajujo.ca
	Author: Harinder Mundh, Junaid Siddiqui, Joseph Adamu
	Author URI: http://www.hajujo.ca
	License: HJJ
	Version: 1.0
*/


/**
 * Function for Custom Post Type - Daily Food Specials .
 */

function food_specials_custompost() {
	$args = array(
		'label' => 'Daily Food Specials',
		'public' => true,
		'show_ui' => true,
		'capability_type' => 'post',
		'rewrite' => array('slug' => 'food_specials'),
		'query_var' => true,
		'supports' => array(
			'title', 'editor', 'excerpt', 'comments', 'thumbnail', 'author',)
		);
	register_post_type('food_specials', $args);
}

add_action('init', 'food_specials_custompost');


// Create the Widget

class HajujoWidget extends WP_Widget {
	
	// Initialize the Widget
	public function __construct() {
		$widget_ops = array(
			'classname' => 'widget_archive', 
			'description' => __( 'A display of daily posts from a custom post type.', 'hajujo') 
	);
		// Adds a class to the widget and provides a description on the Widget page to describe what the widget does.
		parent::__construct('hajujo_custom_post', __('Daily Food Specials', 'hajujo'), $widget_ops);
	}

	public function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['numberOfSpecialsPosts'] = strip_tags($new_instance['numberOfSpecialsPosts']);
		return $instance;
	}	
	
	public function form($instance) {
		if( $instance) {
			$title = esc_attr($instance['title']);
			$numberOfSpecialsPosts = esc_attr($instance['numberOfSpecialsPosts']);
		} 
		
		else {
			$title = '';
			$numberOfSpecialsPosts = '';
	}
	?>
		<p>
		<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'hajujo_widget'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		</p>
		<p>
		<label for="<?php echo $this->get_field_id('numberOfSpecialsPosts'); ?>"><?php _e('Number of Specials Posts:', 'hajujo_widget'); ?></label>
		<select id="<?php echo $this->get_field_id('numberOfSpecialsPosts'); ?>"  name="<?php echo $this->get_field_name('numberOfSpecialsPosts'); ?>">
			<?php for($x=1;$x<=7;$x++): ?>
			<option <?php echo $x == $numberOfSpecialsPosts ? 'selected="selected"' : '';?> value="<?php echo $x;?>"><?php echo $x; ?></option>
			<?php endfor;?>
		</select>
		</p>
	<?php
	
	}
	
	public function widget($args, $instance) {
		
		extract( $args );
		$title = apply_filters('widget_title', $instance['title']);
		$numberOfSpecialsPosts = $instance['numberOfSpecialsPosts'];
		
		echo $args['before_widget'];
	
		if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}
	
		$this->getSpecialsPosts($numberOfSpecialsPosts);
	
		echo $after_widget;
	}
	
	public function getSpecialsPosts($numberOfSpecialsPosts) { 
	
		global $post;
		add_image_size( 'hajujo_widget_size', 80, 40, false );
		$specials = new WP_Query();
		$specials->query('post_type=food_specials & posts_per_page=' . $numberOfSpecialsPosts );
		
		if($specials->found_posts > 0) {
				echo '<ul class="hajujo_widget">';
			while ($specials->have_posts()) {
				$specials->the_post();
				$image = (has_post_thumbnail($post->ID)) ? get_the_post_thumbnail($post->ID, 'hajujo_widget_size') : '<div class="noThumb"></div>';
				$dailyspecial = '<li>' . $image;
				$dailyspecial .= '<a href="' . get_permalink() . '">';
				$dailyspecial .= get_the_title() . '</a>';
				echo $dailyspecial;
			}
			
			echo '</ul>';
		
			wp_reset_postdata();
		}
		
		else{
		
		echo '<p style="padding:10px;">No Specials This Week</p>';
	}
}
	
}

// Tells WordPress that this widget has been created and that it should display in the list of available widgets.

add_action( 'widgets_init', function(){
     register_widget( 'HajujoWidget' );
});
