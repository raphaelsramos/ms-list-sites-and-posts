<?php

	/***
	 *	2017-12-19
	 */

	class LSAP_Options {
	
		private $options;
		
		public function __construct() {
			add_action( 'admin_menu', array( $this, 'admin_menu' ) );
			add_action( 'admin_init', array( $this, 'register_settings' ) );

			if( isset( $_GET[ 'page' ] ) && $_GET[ 'page' ] == 'lsap-options' ){
				add_action( 'admin_print_scripts', array( $this, 'admin_scripts' ) );
				add_action( 'admin_print_styles', array( $this, 'admin_styles' ) );
			}
		}
		
		function admin_menu(){
			//cria link e exibe a função admin_page
			add_options_page( 'List Site & Posts', 'List Site & Posts', 'manage_options', 'lsap-options', array( $this, 'admin_page' ) );
		}
		
		function admin_scripts(){
			wp_enqueue_script( 'media-upload' );
			wp_enqueue_script( 'thickbox' );
			
			wp_register_script( 'lsap-options', plugins_url( '/js/lsap-options.js' , __FILE__ ), array( 'jquery', 'media-upload', 'thickbox' ), '1.0', true );
			wp_enqueue_script( 'lsap-options' );
		}
		
		function admin_styles(){
			wp_enqueue_style( 'thickbox' );
		}

		/**
		 * Options page callback
		 */
		public function admin_page(){
			// Set class property
			$this->options = get_option( 'lsap-options' );
			
			#var_dump( get_option( 'lsap-options' ) );
?>
			<div class="wrap">
				<?php screen_icon(); ?>
				<h2>List Site and Posts</h2>           
				<form method="post" action="options.php">
				<?php
					// This prints out all hidden setting fields
					settings_fields( 'lsap-group-fields' );   
					do_settings_sections( 'lsap-options' );
					submit_button(); 
				?>
				</form>
			</div>
<?php
		}
		
		function register_settings(){
		
			// cria grupo de campos
			register_setting(
				'lsap-group-fields', // Option group
				'lsap-options', // Option name
				array( $this, 'sanitize' ) // Sanitize
			);

			// cria seção de campos
			add_settings_section(
				'lsap-fields', // ID
				__( 'Settings', 'lsap' ), // Title
				array( $this, 'render_section' ), // Callback
				'lsap-options' // Page
			);  

			add_settings_field(
				'lsap-active', // ID
				__( 'Active on this site?', 'lsap' ), // Title 
				array( $this, 'field_active_cb' ), // Callback
				'lsap-options', // Page
				'lsap-fields' // Section           
			);  

			add_settings_field(
				'lsap-lang', // ID
				__( 'Language of this site?', 'lsap' ), // Title 
				array( $this, 'field_lang_cb' ), // Callback
				'lsap-options', // Page
				'lsap-fields' // Section           
			);  			

			add_settings_field(
				'lsap-flag', // ID
				__( 'Country Flag', 'lsap' ), // Title 
				array( $this, 'field_flag_cb' ), // Callback
				'lsap-options', // Page
				'lsap-fields' // Section           
			); 
		}
		
		/** 
		 * Print the Section text
		 */
		public function render_section(){
			echo '';
		}
		
		/** 
		 * Get the settings option array and print one of its values
		 */
		public function field_active_cb(){
			echo '<input type="checkbox" id="lsap-active" name="lsap-options[lsap-active]" value="1"' . checked( 1, $this->options['lsap-active'], false ) . ' />';
		}
		
		
		/** 
		 * Get the settings option array and print one of its values
		 */
		public function field_lang_cb(){
			echo '<input type="text" id="lsap-lang" name="lsap-options[lsap-lang]" value="'. esc_attr( $this->options['lsap-lang']) .'" />';
		}
		
		/** 
		 * Get the settings option array and print one of its values
		 */
		public function field_flag_cb(){
			printf(
				'<input type="text" id="lsap-flag" name="lsap-options[lsap-flag]" value="%s" style="display: none" />',
				isset( $this->options['lsap-flag'] ) ? esc_attr( $this->options['lsap-flag']) : ''
			);
			echo '<input id="lsap-upload-button" type="button" value="Select Image" />';
			echo '<br /><div id="lsap-flag-preview"></div>';
		}
		
		/**
		 * Sanitize each setting field as needed
		 *
		 * @param array $input Contains all settings fields as array keys
		 */
		public function sanitize( $input ){
			return $input;
		}

	}
