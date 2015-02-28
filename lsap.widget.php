<?php

	/** 
	 * The Class.
	 */
	class LSAP_Widget {
	
		/**
		 * Hook into the appropriate actions when the class is constructed.
		 */
		public function __construct() {
			add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
			add_action( 'save_post', array( $this, 'save' ) );
			
			add_action( 'admin_enqueue_scripts', array( $this, 'load_admin_styles' ) );
		}
		
		static function init() {
            // do not generate any output here
		}
		
		static function load_admin_styles(){
			
			wp_enqueue_script(
				'list-site-and-posts'
				, plugins_url( '/js/list-site-and-posts.js' , __FILE__ )
				, array( 'jquery' )
			);
			
			wp_enqueue_style( 'list-site-and-posts', plugins_url( '/css/list-sites-and-posts.css' , __FILE__ ) );
		}

		/**
		 * Adds the meta box container.
		 */
		public function add_meta_box( $post_type ) {
			$post_types = array( 'post', 'page' );     //limit meta box to certain post types

			#if ( in_array( $post_type, $post_types )) {
				add_meta_box(
					'list_sites_and_posts'
					, 'List Site & Posts'
					, array( $this, 'render_meta_box_content' )
					, $post_type
					, 'advanced'
					, 'default'
				);
			#}
		}
		
		
		/**
		 * Render Meta Box content.
		 *
		 * @param WP_Post $post The post object.
		 */
		public function render_meta_box_content( $post ) {
		
			// Add an nonce field so we can check for it later.
			// wp_nonce_field( 'myplugin_inner_custom_box', 'myplugin_inner_custom_box_nonce' );
			wp_nonce_field( 'list_site_and_posts', 'lsap_nonce' );

			// Use get_post_meta to retrieve an existing value from the database.
			$value = get_post_meta( $post->ID, 'lsap-data', true );
			
			

			// Display the form, using the current value.
			echo '<div id="lsap_fields">';
			
			$lista = $this->get_list( $post->post_type );
			
			if( $value ){
				$relateds = json_decode( $value );
				
				#var_dump( $relateds );
				
				$c = 0;
				foreach( $relateds as $related ){
				
					#var_dump( $related );
					echo "<hr />";
				
					$site = get_blog_details( $related->site );
					switch_to_blog( $site->blog_id );
					$site_post = get_post( $related->post );
				
					echo '<div id="lsap-field-'. $c .'" class="lsap-field cf">'
						, '<div class="col col-40">'
							, '<label for="lsap-site-'. $c .'">Site: </label>'
							, '<select name="lsap-data['. $c .'][site]" id="lsap-site-'. $c .'" class="lsap-select lsap-site">'
								, '<option value="'. $site->blog_id .'">'. $site->domain . $site->path .'</option>'
							, '</select>'
						, '</div>'
						, '<div class="col col-40">'
							, '<label for="lsap-posts-'. $c .'">Post: </label>'
							, '<select name="lsap-data['. $c .'][post]" id="lsap-posts-'. $c .'" class="lsap-select lsap-posts">'
								, '<option value="'. $site_post->ID .'">'. $site_post->post_title .'</option>'
							, '</select>'
						, '</div>'
						, '<div class="col col-20">'
							, '<a href="#" class="button lsap-remove">&times;</a>'
						, '</div>'
					, '</div>';
					
					restore_current_blog();
					
				}
				#delete_post_meta( $post->ID, 'lsap-data' );
			}
			
			echo '</div>';
					
			echo '<a href="#" id="lsap-add" class="button button-primary cf">Add Relation</a><br />';
			echo '<a href="#" id="lsap-clear" class="button cf">Remove ALL Relations</a>';
			
			echo '<script type="text/javascript">';
			echo 'var $lsap_sites = '. json_encode( $this->get_list( $post->post_type ) ) .';';
			echo '</script>';
			#var_dump( $this->get_list( $post->post_type ) );
		}
		
		private function get_list( $post_type ){
			// $blog_list = get_blog_list( 0, 'all' );
			$blog_list = wp_get_sites();
			$list = array();
			$current = get_current_blog_id();
			foreach( $blog_list as $blog ){
				$id = $blog[ 'blog_id' ];
				if( $id != $current ){
					switch_to_blog( $id );
					$options = get_option( 'lsap-options' );
					if( $options[ 'lsap-active' ] ){
						$posts = array();
						$q = new WP_Query( array( 'post_type' => $post_type, 'posts_per_page' => -1 ) );
						if( $q->have_posts() ){
							while( $q->have_posts() ){
								$q->the_post();
								$posts[] = array( 'id' => get_the_ID(), 'title' => get_the_title() );
							}
							wp_reset_postdata();
						}
						
						$list[] = array(
							'id' => $id
							, 'href' => $blog[ 'domain' ] . $blog[ 'path' ]
							, 'posts' => $posts
						);
					}
					restore_current_blog();
				}
			}
			return $list;
		}

		/**
		 * Save the meta when the post is saved.
		 *
		 * @param int $post_id The ID of the post being saved.
		 */
		public function save( $post_id ) {
		
			/*
			 * We need to verify this came from the our screen and with proper authorization,
			 * because save_post can be triggered at other times.
			 */

			// Check if our nonce is set.
			// if ( ! isset( $_POST['myplugin_inner_custom_box_nonce'] ) )
			if( ! isset( $_POST['lsap_nonce'] ) )
				return $post_id;

			// $nonce = $_POST['myplugin_inner_custom_box_nonce'];
			$nonce = $_POST['lsap_nonce'];

			// Verify that the nonce is valid.
			// if ( ! wp_verify_nonce( $nonce, 'myplugin_inner_custom_box' ) )
			if( !wp_verify_nonce( $nonce, 'list_site_and_posts' ) )
				return $post_id;

			// If this is an autosave, our form has not been submitted,
					//     so we don't want to do anything.
			if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
				return $post_id;

			// Check the user's permissions.
			if( 'page' == $_POST['post_type'] ) {
				if( !current_user_can( 'edit_page', $post_id ) )
					return $post_id;
			} else {
				if( !current_user_can( 'edit_post', $post_id ) )
					return $post_id;
			}

			/* OK, its safe for us to save the data now. */

			// Sanitize the user input.
			$data = json_encode( $_POST[ 'lsap-data' ] );
			
			#var_dump( $data );

			// Update the meta field.
			update_post_meta( $post_id, 'lsap-data', $data );
		}
		
	}