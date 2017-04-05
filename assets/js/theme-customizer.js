( function( $ ){
	wp.customize( 'blogname', function( value ) {
		value.bind( function( to ) {
			$( '.site-title' ).html( to );
		} );
	} );
	wp.customize( 'blogdescription', function( value ) {
		value.bind( function( to ) {
			$( '.site-description' ).html( to );
		} );
	} );
	wp.customize( 'glidingghost_credits', function( value ) {
		value.bind( function( to ) {
			$( '.footer-copyright' ).html( to );
		} );
	} );
	wp.customize( 'glidingghost_color_bkg', function( value ) {
		value.bind( function( to ) {
			$('body').css('background-color', to );
		} );
	} );
	
	wp.customize( 'glidingghost_color_highlight', function( value ) {
		value.bind( function( to ) {
			$('.site-title a, .button-square, .newer-posts, .older-posts, #submit, #cancel-comment-reply-link').css('background-color', to );
			$('.post-content a:hover, #commentform a:hover').css('border-bottom', to );
			$('a:link, a:visited,.post-stub a').css('color', to );
		} );
	} );
	wp.customize( 'glidingghost_color_highlighttext', function( value ) {
		value.bind( function( to ) {
			$('.pagination a,.site-title a, #submit, #cancel-comment-reply-link').css('color', to );
		} );
	} );
	wp.customize( 'glidingghost_color_highlighthover', function( value ) {
		value.bind( function( to ) {
			$('#topbar a:hover, .site-title a:hover, .button-square:hover').css('background', to );
		} );
	} );
	wp.customize( 'glidingghost_color_highlighthovertext', function( value ) {
		value.bind( function( to ) {
			$('.pagination a:hover,.site-title a:hover, #submit:hover, #cancel-comment-reply-link:hover').css('color', to );
		} );
	} );
	wp.customize( 'glidingghost_color_fonts', function( value ) {
		value.bind( function( to ) {
			$('body').css('color', to );
			$('.post-header,.post-date:after, #topbar .open').css('border-bottom', to );
			$('.post-navigation').css('border-top', to );
		} );
	} );
	wp.customize( 'glidingghost_color_byline', function( value ) {
		value.bind( function( to ) {
			$('.post-date').css('color', to );
		} );
	} );
	wp.customize( 'glidingghost_color_tocbody', function( value ) {
		value.bind( function( to ) {
			$('body.tofc,.tofc .homelink').css('background-color', to );
		} );
	} );
	wp.customize( 'glidingghost_color_tocfontcolor', function( value ) {
		value.bind( function( to ) {
			$('body.tofc, .tofc .homelink').css('color', to );
			$('body.tofc .post-header').css('border-bottom', to );
		} );
	} );
	wp.customize( 'glidingghost_color_toclink', function( value ) {
		value.bind( function( to ) {
			$('.tofc .post-stub a').css('color', to );
			$('.tofc .post-stub').css('border-bottom', to );
		} );
	} );
	wp.customize( 'glidingghost_color_toclinktexthover', function( value ) {
		value.bind( function( to ) {
			$('.tofc .post-stub a:hover').css('color', to );
		} );
	} );
	wp.customize( 'glidingghost_color_toclinkbghover', function( value ) {
		value.bind( function( to ) {
			$('.tofc .post-stub a:hover').css('background-color', to );
		} );
	} );
	wp.customize( 'glidingghost_color_listlinktext', function( value ) {
		value.bind( function( to ) {
			$('.post-stub a').css('color', to );
			$('.post-stub').css('border-bottom', to );
		} );
	} );
	wp.customize( 'glidingghost_color_listlinktexthover', function( value ) {
		value.bind( function( to ) {
			$('.post-stub a:hover').css('color', to );
		} );
	} );
	wp.customize( 'glidingghost_color_listlinkbghover', function( value ) {
		value.bind( function( to ) {
			$('.post-stub a:hover').css('background-color', to );
		} );
	} );

} )( jQuery );