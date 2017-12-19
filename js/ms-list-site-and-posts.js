/***
 *	MU - List Site And Posts
 *==============================*/
( function( $ ){
	$( function(){

		var $count = $( '.lsap-field' ).length;
		
		function $templ( $c ){
			var $sites = sites2options()
				, $t = [
					'<div id="lsap-field-'+ $c +'" class="lsap-field cf">'
						, '<div class="col col-40">'
							, '<label for="lsap-site-'+ $c +'">Site: </label>'
							, '<select name="lsap-data['+ $c +'][site]" id="lsap-site-'+ $c +'" class="lsap-select lsap-site">'
								, $sites
							, '</select>'
						, '</div>'
						, '<div class="col col-40">'
							, '<label for="lsap-posts-'+ $c +'">Post: </label>'
							, '<select name="lsap-data['+ $c +'][post]" id="lsap-posts-'+ $c +'" class="lsap-select lsap-posts disabled">'
								, '<option value="">Selecione...</option>'
							, '</select>'
						, '</div>'
						, '<div class="col col-20">'
							, '<a href="#" class="button lsap-remove">&times;</a>'
						, '</div>'
					, '</div>'
				];
			return $t.join( "\n" );
		}
		
		function sites2options(){
			if( $lsap_sites != undefined && $lsap_sites.length ){
				$lista = [ '<option value="">Selecione</option>' ];
				$.each( $lsap_sites, function(){
					$lista.push( '<option value="'+ this.id +'">'+ this.href +'</option>' );
				} );
				return $lista.join( "\n" );
			}
			return '';
		}
		
		function posts2options( $posts ){
			if( $posts.length ){
				$lista = [];
				$.each( $posts, function(){
					$lista.push( '<option value="'+ this.id +'">'+ this.title +'</option>' );
				} );
				return $lista.join( "\n" );
			}
			return '';
		}
	
		$( '#lsap-add' ).on( 'click', function( e ){
			e.preventDefault();
			console.log( 'add clicado' );
			
			var $t = $templ( $count++ );
			
			$( '#lsap_fields' ).append( $t );
		} );
		
		$( '#lsap-clear' ).on( 'click', function( e ){
			e.preventDefault();
			console.log( 'clear clicado' );
			
			$count = 0;
			
			$( '#lsap_fields' ).empty();
		} );

		$( document ).delegate( '.lsap-remove', 'click', function( e ){
			e.preventDefault();
			var $self = $( this )
				, $target = $self.parents( '.lsap-field' );
			$target.slideUp( function(){
				$( this ).remove();
			} );
		} ).delegate( '.lsap-site', 'change', function(){
			var $self = $( this )
				, $site = $self.val()
				, $target = $self.parent().parent().find( '.lsap-posts' );
				;
			if( $site != '' ){
				var $site_obj = $lsap_sites.filter( function( $s ){
						return $s.id == $site;
					} );
				var $posts = $site_obj[ 0 ].posts;
				
				console.log( { 'site': $site_obj, 'posts': $posts } );
				$target.removeClass( 'disabled' )
					.empty().append( posts2options( $posts ) );
			} else {
				$target.empty().append( '<option value="">Selecione</option>' ).addClass( 'disabled' );
			}
		} );

	} );
})( jQuery );