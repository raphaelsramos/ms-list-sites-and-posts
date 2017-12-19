<?php

	/***
	 *	2017-12-19
	 */

	function get_lsap_related_sites(){
		
		$list = array();
		
		$blog_list = get_sites();
		$list = array();
		$current = get_current_blog_id();
		
		foreach( $blog_list as $blog ){
			$id = $blog->blog_id;
			if( $id != $current ){
				switch_to_blog( $id );
				$options = get_option( 'lsap-options' );
				if( $options[ 'lsap-active' ] ){
					$list[] = array(
						'id' => $id,
						'title' => get_bloginfo( 'name' ),
						'link' => $blog->path,
						'flag' => $options[ 'lsap-flag' ],
						'lang' => $options[ 'lsap-lang' ]
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
			$list[] = '<a href="'. $site[ 'link' ] .'" title="'. $site[ 'title' ] .'" class="lsap-item" data-lang="'. $site[ 'lang' ] .'"><img src="'. $site[ 'flag' ] .'" alt="'. $site[ 'title' ] .'" /></a>';
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

		if( $value = get_post_meta( $id, 'lsap-data', true ) ){
			
			$relateds = json_decode( $value );
			
			if( count( $relateds ) ){
				foreach( $relateds as $related ){
				
					$site = get_blog_details( $related->site );
					switch_to_blog( $site->blog_id );
					$options = get_option( 'lsap-options' );
					
					if( $options[ 'lsap-active' ] ){
						$site_post = get_post( $related->post );
						
						$list[] = array(
							'id' => $site_post->ID,
							'title' => $site_post->post_title,
							'link' => get_permalink( $site_post->ID ),
							'flag' => $options[ 'lsap-flag' ],
							'lang' => $options[ 'lsap-lang' ]
						);
						
					}
					restore_current_blog();
				}
			}
		}

		return $list;
	}
	
	function get_lsap_related_posts_links( $id = false, $attrs = array() ){
		if( !$id ) $id = get_the_ID();
		
		$lista = get_lsap_related_posts( $id );
		if( count( $lista ) ){
			$list = array();
		
			foreach( $lista as $item ){
				$list[] = sprintf( '<a href="%s" title="%s" class="lsap-item" data-lang="%s"><img src="%s" alt="%s" /></a>',
							$item[ 'link' ],
							$item[ 'title' ],
							$item[ 'lang' ],
							$item[ 'flag' ],
							$item[ 'title' ]
						);
			}
		}
		else {
			$list = get_lsap_related_sites_links();
		}
		
		return $list;
	}
	
	function the_lsap_related_posts_links( $id = false, $attrs = array() ){
		echo implode( "\n", get_lsap_related_posts_links( $id, $attrs ) );
	}
