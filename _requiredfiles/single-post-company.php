<?php
/**
 * Template Name: Company Single Page
 * Template Post Type: post
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package HWCOE_UFL_CHILD
 */
get_header(); ?>

<div id="main" class="container main-content">
<div class="row">
	<div class="col-md-4">
		<?php 
			$image    = get_field( 'company_logo' );
			$alt      = $image['alt'];
			$img_src  = $image['sizes']['medium'];
		?>
		<p><img src="<?php echo $img_src; ?>" alt="<?php echo $alt; ?>"></p>
	</div>
	<div class="col-md-8 company-name">
		<h1><?php the_title();?></h1>
		<?php if ( get_field( 'hiring_weblink') ): ?>
			<p><a href="<?php esc_url( the_field( 'hiring_weblink' ) ); ?>" target="_blank"><?php esc_attr( the_field( 'hiring_weblink' ) ); ?></a></p>
		<?php endif ?>
	</div>
	<div class="col-md-12">
	  <?php 
			if(get_field('company_description')){ //if the field is not empty
				echo '<h3>Description</h3><p>' . get_field('company_description') . '</p>'; //display it
			} 
		
			if(get_field('company_accepting')){ 
				include( 'inc/modules/ufl-company-accepting.php' );
			}
		?>
	</div>
</div>
</div>

<?php get_footer(); ?>
