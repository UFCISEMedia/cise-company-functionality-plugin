<?php
/*
Plugin Name: HWCOE Company Functionality
Description: Use this shortcode to display companies under the "current" category<strong>[COMPANY_DISPLAY type="post" posts_per_page="50" order="ASC" orderby="title" category_name="current"]</strong>
Version: 1.1
Author: Allison Logan
Author URI: http://allisoncandreva.com/
*/


Class CompanyDisplay {

	public $plugin_dir;
	public $plugin_url;

	function  __construct(){
		global $wpdb;
		$prefix = $wpdb->prefix;
		$this->plugin_dir = plugin_dir_path(__FILE__);
		$this->plugin_url = plugin_dir_url(__FILE__);
		add_shortcode( 'COMPANY_DISPLAY', array($this, 'company_display_shortcode' ) );
		add_action( 'wp_enqueue_scripts', array($this,'compfun_enqueue_scripts_styles' ));
	}
	
	function compfun_enqueue_scripts_styles(){
		wp_enqueue_script('compfun_fancybox_js', $this->plugin_url.'js/jquery.fancybox.min.js', array('jquery'), '1.0.0', true);
		wp_enqueue_style('compfun_fancybox_css', $this->plugin_url.'css/jquery.fancybox.min.css');
		wp_enqueue_script('compfunc', $this->plugin_url.'js/compfunc.js', array('jquery'), '1.0.0', true);
		wp_enqueue_style('compfunc', $this->plugin_url.'css/compfunc.css');
	}
	
	public function company_display_shortcode($atts) {

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

		$testimonials = '<div class="company-tab">'; //col-md-12

		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) : $query->the_post();
				$post_id = get_the_ID();

				$featimageURL = wp_get_attachment_url( get_post_thumbnail_id($post_id) );


				$feat_image       = ( !empty($featimageURL) ) ?  '<img src="'.$featimageURL.'" class="img-responsive companypostimg">':'';	

				$imageArray  = get_field( 'company_logo' );
				$imageAlt    = esc_attr($imageArray['alt']);
				$theimage       = esc_url($imageArray['sizes']['medium']);
				
				$values = get_field('company_accepting');
				$field = get_field_object('company_accepting');
				$choices = $field['choices'];
				$eachchoice = 1;
				$characterbreak = 1;
				$posttags = get_the_tags();
			
				$testimonials .= '<div class="companybox mix';
				
				//Adds checked items as classes
				foreach($values as $value) :
				$testimonials .= ' ' .str_replace (' ', '', $value['value']);
				endforeach;
			
				$testimonials .= '">';									
				$testimonials .= $this->wpse69204_excerpt(); 
				$testimonials .= 
					'<div class="fancyboxcont" id="post_'.$post_id.'">';
				$testimonials .= 
						'<div class="col-md-12 popupcont">';
				$testimonials .= 
							'<div class="popupcont-img"><img src="'.$theimage.'" alt="'.$imageAlt. '"/></div>
							<div class="popupcont-text"><h1>' .get_the_title(). '</h1>
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
							<p>' .get_field('company_description'). '</p>
							<p><a href="' .get_field('hiring_weblink'). '" target="_blank">' .get_field('hiring_weblink'). '</a></p>';

				$testimonials .= '</div></div></div></div>';
			endwhile;
			wp_reset_postdata();
		} else { ?>
			<p style="text-align:center;">There are no companies registered. Please come back.</p>
		<?php }
		$testimonials .= '</div>';
		return $testimonials;
	} //end company_display_shortcode function
	
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

$CompanyDisplay = new CompanyDisplay();