<?php
/**
 * Plugin Name: Most Popular Posts for Jetpack
 * Plugin URI: https://knytl.com
 * Description: Wordpress widget showing most popular posts based on Jetpack stats.
 * Version: 0.1.1
 * Author: Jakub Knytl
 * Author URI: https://knytl.com
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

class jetstat_plugin extends WP_Widget {

	// constructor
	function jetstat_plugin() {
	}

	// widget form creation
	function form($instance) {	
				// Check values
		if( $instance) {
			 $pocet = esc_attr($instance['pocet']);
		} else {
			 $pocet = '8';
		}
		?>
		<p>
		<label for="<?php echo $this->get_field_id('pocet'); ?>"><?php _e('Počet článků', 'jetstat_plugin'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('pocet'); ?>" name="<?php echo $this->get_field_name('pocet'); ?>" type="number" value="<?php echo $pocet; ?>" />
		</p>

<?php
	}

	// widget update
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		  // Fields
		  $instance['pocet'] = strip_tags($new_instance['pocet']);
		 return $instance;
	}

	// widget display
	function widget($args, $instance) {
		extract( $args );
		if(function_exists('stats_get_csv')){
	        $popular = stats_get_csv( 'postviews', array( 'days' => 8, 'limit' => $instance['pocet'] + 1 ) );
	        echo '<h4 class="widget-title"><i class="fa fa-line-chart" style="color:orangered;"></i> Nejpopulárnější články týdne:</h4>';
	        echo '<ul class="toplist">';	
	  		foreach ( $popular as $p ) {
				if ($p['post_id']  && get_post( $p['post_id'] ) && 'post' === get_post_type( $p['post_id'] ))
	             printf('<li><a href="%s">%s</a></li>', $p['post_permalink'], $p['post_title']);
	      	  }
	  		echo '</ul>';
	}
	}
}

// register widget
add_action('widgets_init', create_function('', 'return register_widget("jetstat_plugin");'));
