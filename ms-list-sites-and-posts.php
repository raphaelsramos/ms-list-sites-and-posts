<?php

/*
Plugin Name: MS List Site & Posts
Plugin URI: http://www.raphaelramos.com.br/wp/plugins/ms-list-site-and-posts/
Description: Trying associate posts between Multisites? When activated, this plugins show a list of sites in the network. When the site is selected, it shows the list of posts (of same post type) for association.
Author: Raphael Ramos
Author URI: http://www.raphaelramos.com.br/
Text Domain: ms-sites-p2p
Domain Path: /lang/
Version: 0.21
Date: 2015-02-28
*/

	define( 'LSAP_ROOT', dirname( __FILE__ ) .'/' );

	include( 'lsap.widget.php' );
	include( 'lsap.options.php' );
	include( 'lsap.helpers.php' );

	/***
	 * Calls the class on the post edit screen.
	 */
	function call_LSAP_Widget(){
		new LSAP_Widget();
	}

	new LSAP_Options();

	if( is_admin() ){
		add_action( 'load-post.php', 'call_LSAP_Widget' );
		add_action( 'load-post-new.php', 'call_LSAP_Widget' );
	}

	
	
	
	