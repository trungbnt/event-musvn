<?php
class Event_Widget extends WP_Widget{
	function __construct() {
		parent::__construct(
		'event_widget', // Base ID
		__('Event Widget', 'event_widget_domain'), // Name
		array('description' => __( 'Displays your latest listings. Outputs the post thumbnail, title and date per listing'))
		);
	}

	public function widget($args, $instance) {
		extract( $args );
		$title = apply_filters('widget_title', $instance['title']);
		$numberOfListings = $instance['numberOfListings'];
		echo $before_widget;
		if ( $title ) {
			echo $before_title . $title . $after_title;
		}
		$this->getEventListings($numberOfListings);
		echo $after_widget;
	}

public function getEventListings($numberOfListings) { //html
	global $post;
	add_image_size( 'event_widget_size', 300, 250, false );
	$listings = new WP_Query();
	$listings->query('post_type=event');
	if($listings->found_posts > 0) {
		echo '<ul class="event_widget"><div class="owl-carousel owl-theme">';
			while ($listings->have_posts()) {
				$listings->the_post();
				$image = (has_post_thumbnail($post->ID)) ? get_the_post_thumbnail($post->ID, 'event_widget_size') : '<div class="noThumb"></div>';
				$listItem .= '<div class="item">';
				$listItem .=  the_field('date');
				$listItem .=  $image;
				$listItem .= '<a href="' . get_permalink() . '">';
				$listItem .= get_the_title() . '</a>';
				$listItem .= '</div>';
				echo $listItem;
			}
		echo '</div></ul>';
		wp_reset_postdata();
	}else{
		echo '<p style="padding:25px;">No listing found</p>';
	}
}

public function update($new_instance, $old_instance) {
	$instance = $old_instance;
	$instance['title'] = strip_tags($new_instance['title']);
	$instance['numberOfListings'] = strip_tags($new_instance['numberOfListings']);
	return $instance;
} 

function form($instance) {
	if( $instance) {
		$title = esc_attr($instance['title']);
		$numberOfListings = esc_attr($instance['numberOfListings']);
	} else {
		$title = '';
		$numberOfListings = '';
	}
	?>
	<p>
		<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'event_widget'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('numberOfListings'); ?>"><?php _e('Number of Listings:', 'event_widget'); ?></label>
		<select id="<?php echo $this->get_field_id('numberOfListings'); ?>"  name="<?php echo $this->get_field_name('numberOfListings'); ?>">
			<?php for($x=1;$x<=10;$x++): ?>
				<option <?php echo $x == $numberOfListings ? 'selected="selected"' : '';?> value="<?php echo $x;?>"><?php echo $x; ?></option>
			<?php endfor;?>
		</select>
	</p>
	<?php
}

} //end class Event_Widget
register_widget('Event_Widget');