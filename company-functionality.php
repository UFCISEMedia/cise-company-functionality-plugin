<?php
/*
Plugin Name: Company Functionality
Description: This is a customized version of the Custom Post Popup https://gnanavel.wordpress.com/. Use this shortcode to display <strong>[CUSTOM_POST_POPUP type="post" posts_per_page="50" order="ASC" orderby="title" category_name="current"]</strong>
Version: 1.0
Author: Allison Logan
Author URI: http://allisoncandreva.com/
*/


Class CustomPostPopup {

	public $plugin_dir;
	public $plugin_url;

	function  __construct(){
		global $wpdb;
		$prefix = $wpdb->prefix;
		$this->plugin_dir = plugin_dir_path(__FILE__);
		$this->plugin_url = plugin_dir_url(__FILE__);
		add_shortcode( 'CUSTOM_POST_POPUP', array($this, 'custom_post_popup_shortcode' ) );
		add_action( 'wp_enqueue_scripts', array($this,'wpsp_enqueue_scripts_styles' ));
	}
	
	function wpsp_enqueue_scripts_styles(){
		wp_enqueue_script('wpct_fancybox_js', $this->plugin_url.'js/jquery.fancybox.min.js', array('jquery'), '1.0.0', true);
		wp_enqueue_style('wpct_fancybox_css', $this->plugin_url.'css/jquery.fancybox.min.css');
		wp_enqueue_script('wpct_frontend_js', $this->plugin_url.'js/wpspfrontend.js', array('jquery'), '1.0.0', true);
		wp_enqueue_style('wpsp_frontend_css', $this->plugin_url.'css/frontend.css');
	}
	
	public function custom_post_popup_shortcode($atts) {

		extract( shortcode_atts( array(
			'posts_per_page' => '50',
			'order' => 'ASC',
			'orderby' => 'title',
			'type'=>'type',	
			'category_name' => 'current',
		), $atts ) );
		
		$args = array(
			'posts_per_page' => (int) $atts['posts_per_page'],
			'post_type' =>$atts['type'],
			'order' => $atts['order'],
			'orderby' => $atts['orderby'],
			'category_name' => $atts['category_name'],
			'no_found_rows' => true,
		);
		
		$dispCount  = (int) $posts_per_page;
		if($dispCount==50){
			$colmd = four;
		}else if($dispCount=="4"){
			$colmd = four; 
		}else{
			$colmd = four;
		}
		$query = new WP_Query( $args  );

		$testimonials = '<div class="speaker-tab">'; //col-md-12

		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) : $query->the_post();
				$post_id = get_the_ID();

				$featimageURL = wp_get_attachment_url( get_post_thumbnail_id($post_id) );


				$feat_image       = ( !empty($featimageURL) ) ?  '<img src="'.$featimageURL.'" class="img-responsive testimonialimg">':'';	

				$imageArray  = get_field( 'company_logo' );
				$imageAlt    = esc_attr($imageArray['alt']);
				$theimage       = esc_url($imageArray['sizes']['medium']);
				
				//$thechoices = file_get_contents( get_stylesheet_directory_uri() . '/inc/modules/ufl-company-accepting.php', FILE_USE_INCLUDE_PATH );
				$values = get_field('company_accepting');
				$field = get_field_object('company_accepting');
				$choices = $field['choices'];
				$eachchoice = 1;
				$characterbreak = 1;
				$posttags = get_the_tags();
			
				$testimonials .= '<div class="speakerbox mix ';
				//Adds the post tags as classes
					if ($posttags) {
					  foreach($posttags as $tag) {
				$testimonials .= $tag->name. ' ';
					  }
					}
			
				$testimonials .= '">';									
				$testimonials .= $this->wpse69204_excerpt(); 
				$testimonials .= 
					'<div class="fancyboxcont" id="post_'.$post_id.'">';
				$testimonials .= 
						'<div class="col-md-12 popupmailtxtcont">';
				$testimonials .= 
							'<div class="popupmailtxtcont-img"><img src="'.$theimage.'" alt="'.$imageAlt. '"/></div>
							<div class="popupmailtxtcont-text"><h1>' .get_the_title(). '</h1>
							<p class="co-func-fancy-op">';
				foreach ($choices as $choice_value => $choice_label) {
				$testimonials .= '<span class="fancy-choice-' .$eachchoice++.'">';
						$found = false;
						foreach ($values as $value) {
							if ($value['value'] == $choice_value) {
							$found = true;
				$testimonials .= '<span style="color:#008000">&#10003;</span> ';
						  }
						} // end foreach $values
						if (!$found) {
				$testimonials .= '<span style="color:#FF0000">&#10005;</span> ';
						}
				$testimonials .= $choice_label . ' <span class="co-line-fancy-' .$characterbreak++.'">| </span></span>';				
					 } // end foreach $choices		
				;
				$testimonials .= '</p><hr class="co-func-line">
							<p>' .get_field('company_description'). '</p>';

				$testimonials .= '</div></div></div></div>';
			endwhile;
			wp_reset_postdata();
		} else { ?>
			<p>There are no company registered. Please come back.</p>
		<?php }
		$testimonials .= '</div>';
		return $testimonials;
	} //end custom_post_popup_shortcode function
	
	public function wpse69204_excerpt( $post_id = null )
	{
		global $post;
		$current_post = $post_id ? get_post( $post_id ) : $post;
		$imageArray  = get_field( 'company_logo' );
		$imageAlt    = esc_attr($imageArray['alt']);
		$theimage       = esc_url($imageArray['sizes']['medium']);
		$values = get_field('company_accepting');
		$field = get_field_object('company_accepting');
		$choices = $field['choices'];
		$eachchoice = 1;
		$characterbreak = 1;
		$excerpt .= '<a class="various" href="#post_'.$post->ID.'" title=""><img src="'.$theimage.'" alt="'.$imageAlt. '"/><h1>'. get_the_title() . '</h1><p>';
		foreach ($choices as $choice_value => $choice_label) {
				$excerpt .= '<span class="choice-' .$eachchoice++.'">';
						$found = false;
						foreach ($values as $value) {
							if ($value['value'] == $choice_value) {
							$found = true;
				$excerpt .= '<span class="co-choices" style="color:#008000">&#10003;</span> ';
						  }
						} // end foreach $values
						if (!$found) {
				$excerpt .= '<span class="co-choices" style="color:#FF0000">&#10005;</span> ';
						}
				$excerpt .= $choice_label . ' <span class="co-line-' .$characterbreak++.'">| </span></span>';	
					 } // end foreach $choices		
				;
		$excerpt .= '</p></a>';
		return $excerpt;
	}
}

$CustomPostPopup = new CustomPostPopup();