<?php
/**
 * The template for displaying the header
 *
 * Displays all of the head element and everything up until the "site-content" div.
 *
 * @package WordPress
 * @subpackage Business
 * @since Business 1.0
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
        
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
        
	<link rel="profile" href="http://gmpg.org/xfn/11">
        
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<!--[if lt IE 9]>
	<script src="<?php echo esc_url( get_template_directory_uri() ); ?>/js/html5.js"></script>
	<![endif]-->
        <link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/style.css"  media="screen, print">
	 
            <?php wp_head(); ?>
</head>

<body <?php body_class();?>> 

<div id="page" class="hfeed site">
	 
	 
	 <div class="header-wrapper">
                 
                <?php get_sidebar("header"); ?>
             
                <div class="mainMenuWrap">
                    
                                
                
				<div class="wrapper">

					<span class="mobileNav"></span>

					<h1 class="logo">
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><span class="hidden"><?php bloginfo( 'name' ); ?></span></a>
					</h1>
                                        <h4 class="description">
                                        <?php bloginfo('description'); ?> 
                                        </h4>
					<?php  wp_nav_menu( array('menu' => 'Primary Navigation', 'menu_class' => "main-menu")); ?>

	            </div>
 
            </div><!-- .sidebar -->
        </div>
	<div id="content" class="site-content">
