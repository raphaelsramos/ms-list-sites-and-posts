/***
 *	MU - List Site And Posts
 *
 *	Updated:	2016-03-18
 *==============================*/
( function( $ ){
	$( function(){
		
		/* ADMIN */
		
		var $flag = $( '#lsap-flag' )
			, $active = $( '#lsap-active' )
			, $tr = $flag.parent().parent()
			;
		if( !$active.is( ':checked' ) ){
			$tr.hide();
		}
		
		$active.on( 'click', function( e ){
			if( $active.is( ':checked' ) ){
				$tr.show();
			} else {
				$tr.hide();
			}
		} );
		
		if( $flag.val() !== '' ){
			$( '#lsap-flag-preview' ).html( '<img src="'+ $flag.val() +'" />' );
		}
		
		$( '#lsap-upload-button' ).click(function() {
			window.send_to_editor = function( html ){
				var $html = $( html ),
					el = $html[ 0 ],
					$el = el.nodeName.toLowerCase() == 'a' ? $( 'img', $html ) : $html,
					imgurl = $el.attr( 'src' );
					;
				// console.log( { 'html': html, '$html': $html, 'el': el } );
				$( '#lsap-flag' ).val( imgurl );
				$( '#lsap-flag-preview' ).html( '<img src="'+ imgurl +'" />' );
				tb_remove();
			}
        
			tb_show('', 'media-upload.php?type=image&TB_iframe=true');
			//tb_show('Define the Site Flag', 'media-upload.php?referer=lsap-option&type=image&TB_iframe=true', false);
			return false;
		});
 
	} );
})( jQuery );