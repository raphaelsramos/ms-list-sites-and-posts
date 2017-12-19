<?php

/*
Plugin Name: MS List Site & Posts
Plugin URI: https://github.com/raphaelsramos/ms-list-sites-and-posts/
Description: Trying associate posts between Multisites? When activated, this plugins show a list of sites in the network. When the site is selected, it shows the list of posts (of same post type) for association.
Author: Raphael Ramos
Author URI: http://www.raphaelramos.com.br/
Text Domain: ms-lsap
Domain Path: /lang/
Version: 1.0.1
Date: 2017-12-19
*/

	define( 'LSAP_ROOT', dirname( __FILE__ ) .'/' );

	require_once 'metabox.php';
	require_once 'options.php';
	require_once 'helpers.php';

	/***
	 * Calls the class on the post edit screen.
	 */
	function LSAP_add_metabox(){
		new LSAP_Metabox();
	}

	new LSAP_Options();

	if( is_admin() ){
		add_action( 'load-post.php', 'LSAP_add_metabox' );
		add_action( 'load-post-new.php', 'LSAP_add_metabox' );
	}
