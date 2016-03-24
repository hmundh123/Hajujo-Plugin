<?php
	
/*
	Plugin Name: Hajujo Plugin
	Description: A plugin to add a custom post type, add a widget to display set number of posts from the custom post type (Daily Food Specials), 		and to add a shortcode - [hajujoshortcode].
	Plugin URI: http://www.hajujo.ca
	Author: Harinder Mundh, Junaid Siddiqui, Joseph Adamu
	Author URI: http://www.hajujo.ca
	License: HJJ
	Version: 1.0
*/


// Enqueue the plugin style sheet. Strictly for the plugin, when activated.

function plugin_stylesheet() {
	wp_register_style( 'plugin-style', plugins_url( '/hajujoplugin/style.css'));
	wp_enqueue_style ( 'plugin-style' );	
}

add_action( 'wp_enqueue_scripts', 'plugin_stylesheet' );


// Function for Custom Post Type - Daily Food Specials .

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

// Creates a function for a shortcode (hajujoshortcode) that will display Hajujo's custom post type posts - Daily Food Specials posts and will also provide a link to the posts if you click any of them.

add_shortcode('hajujoshortcode', 'hajujoshortcode');

function hajujoshortcode(){
	$args = array(
		'post_type' => 'food_specials',
	);
	
	$shortcodeposts = '';
	$query = new WP_Query($args);
	
	if($query->have_posts() ) {
		$shortcodeposts .= '<ul>';
		while($query->have_posts() ) {
			$query->the_post();
			$shortcodeposts .= '<li>' . $image;
			$shortcodeposts .= '<a href="' . get_permalink() . '">';
			$shortcodeposts .= get_the_title() . '</a>';	
		}
		$shortcodeposts .= '</ul>';
	}
	wp_reset_postdata();
	return $shortcodeposts;
}


// Create the Widget

class HajujoWidget extends WP_Widget {
	
	// Initialize the Widget
	public function __construct() {
		$widget_ops = array(
			'classname' => 'hajujo_widget_specials_posts', 
			'description' => __( 'A display of daily food specials that can be added from custom post type - Daily Food Specials.', 'hajujo') 
	);
		// Adds a class to the widget and provides a description on the Widget admin page that describes what the Hajujo Daily Food Specials widget does.
		parent::__construct('hajujo_custom_post', __('Daily Food Specials', 'hajujo'), $widget_ops);
	}

	// Saves and inputs the user-generated widget content options - the title of the widget and the amount of custom post types that will be displayed on the widget.

	public function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['numberOfSpecialsPosts'] = strip_tags($new_instance['numberOfSpecialsPosts']);
		return $instance;
	}	
	
	// This function creates the actual form that our client will see when they access their Wordpress Admin - Widgets section. Our option is to enter a title and the amount of posts the user would like to display as output on their sidebar widget (Option is 1 to 7).
	
	public function form($instance) {
		if($instance) {
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
	
	// Function that determines what will appear on the website - widget output. 
	
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
	
	// Function to query and pull our custom post type posts (Daily Food Specials) that will show on the sidebar widget. The amount of posts dipslayed will be determined by the client. Option is between 1-7 (1 for everyday's special).
	
	public function getSpecialsPosts($numberOfSpecialsPosts) { 
	
		global $post;
		add_image_size( 'hajujo_widget_size', 140, 100, false );
		$specials = new WP_Query();
		$specials->query('post_type=food_specials & posts_per_page=' . $numberOfSpecialsPosts );
		
		// If/Else statement to determine what to display if there are posts, and if there are no posts, it will show "No Specials This Week". If there is no thumbnail associated with a post, it will show a 100*60 blank grey box.
		
		if($specials->found_posts > 0) {
				echo '<ul class="hajujo_widget">';
			while ($specials->have_posts()) {
				$specials->the_post();
				$image = (has_post_thumbnail($post->ID)) ? get_the_post_thumbnail($post->ID, 'hajujo_widget_size') : '<div class="noThumbnailAvail"></div>';
				$dailyspecial = '<li>' . $image;
				$dailyspecial .= '<a href="' . get_permalink() . '">';
				$dailyspecial .= get_the_title() . '</a>';
				echo $dailyspecial;
			}
			
			echo '</ul>';
		
			wp_reset_postdata();
		}
		
		else{
		
		// If there are no Daily Food Specials posts (custom post type), "No Specials This Week" will display. 
		
		echo '<p style="padding:10px;">No Specials This Week</p>';
	}
}
	
}

// Tells WordPress that our Hajujo - Daily Food Specials widget has been created and that it should display in the list of available widgets in the Wordpress admin page.

add_action( 'widgets_init', function(){
     register_widget( 'HajujoWidget' );
});
