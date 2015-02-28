<?php

	/***
	 * List Connected Sites
	 * 
	 * Updated @ 2015-02-28
	 *==================================*/
	function get_lsap_related_sites(){
		
		$list = array();
		
		$blog_list = wp_get_sites();
		$list = array();
		$current = get_current_blog_id();
		
		foreach( $blog_list as $blog ){
			$id = $blog[ 'blog_id' ];
			if( $id != $current ){
				switch_to_blog( $id );
				$options = get_option( 'lsap-options' );
				if( $options[ 'lsap-active' ] ){
					$list[] = array(
						'id' => $id
						, 'title' => get_bloginfo('name')
						, 'link' => $blog[ 'domain' ] . $blog[ 'path' ]
						, 'flag' => $options[ 'lsap-flag' ]
					);
				}
				restore_current_blog();
			}
		}
		
		return $list;
	}
	
	function get_lsap_related_sites_links(){
		
		$lista = get_lsap_related_sites();
		$list = array();
		
		foreach( $lista as $site ){
			$list[] = '<a href="'. $site[ 'link' ] .'" title="'. $site[ 'title' ] .'" class="lsap-item"><img src="'. $site[ 'flag' ] .'" alt="'. $site[ 'title' ] .'" /></a>';
		}
		
		return $list;
	}
	
	function the_lsap_related_sites_links(){
		echo implode( "\n", get_lsap_related_sites_links() );
	}


	/***
	 * List Connected Posts
	 *==================================*/
	function get_lsap_related_posts( $id = false ){
		if( !$id ) $id = get_the_ID();
		
		$list = array();
		
		$value = get_post_meta( $id, 'lsap-data', true );
		if( $value !== false ){
			$relateds = json_decode( $value );
			
			foreach( $relateds as $related ){
			
				$site = get_blog_details( $related->site );
				switch_to_blog( $site->blog_id );
				$options = get_option( 'lsap-options' );
				
				if( $options[ 'lsap-active' ] ){
					$site_post = get_post( $related->post );
					
					$list[] = array(
						'title' => $site_post->post_title
						, 'link' => get_permalink( $site_post->ID )
						, 'flag' => $options[ 'lsap-flag' ]
					);
				}
				restore_current_blog();

			}
			
		}
		return $list;
	}
	
	function get_lsap_related_posts_links( $id = false, $attrs = array() ){
		if( !$id ) $id = get_the_ID();
		
		#$options = array_merge( array(
		#		
		#), $attrs );
		
		$lista = get_lsap_related_posts( $id );
		$list = array();
		
		foreach( $lista as $item ){
			$list[] = '<a href="'. $item[ 'link' ] .'" title="'. $item[ 'title' ] .'" class="lsap-item"><img src="'. $item[ 'flag' ] .'" alt="'. $item[ 'title' ] .'" /></a>';
		}
		
		return $list;
	}
	
	function the_lsap_related_posts_links( $id = false, $attrs = array() ){
		echo implode( "\n", get_lsap_related_posts_links( $id, $attrs ) );
	}