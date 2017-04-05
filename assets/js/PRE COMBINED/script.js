
	$(document).ready(function () {
		// Cached DOM references
		var $goarchive = $('#goarchive'),
			$gopost = $('#gopost'),
			$goworkarchive = $('#goworkarchive'),
			$goworkpost = $('#goworkpost'),
			$archive = $('#archive'),
			$post = $('#post'),
			$work = $('#workpost'),
			$workarchive = $('#workarchive');


		function goarchive() {
			  NProgress.start();
				$workarchive.fadeOut(300),
				$post.fadeOut(300, function () {
				$archive.scrollTop(0);
				$archive.fadeIn(300);
			  NProgress.done();
				$goarchive.show(0);
				$gopost.hide(0);
				$goworkarchive.show(0);
				$goworkpost.hide(0);
			});
		};

		function gopost() {
			  NProgress.start();
				$archive.fadeOut(300, function () {
				$post.scrollTop(0);
				$post.fadeIn(300);
				$gopost.hide(0);
			  NProgress.done();
				$goarchive.show(0);
				$goworkpost.hide(0);
				$goworkarchive.show(0);
			});
		};


		function goworkarchive() {
			  NProgress.start();
			    $archive.fadeOut(300),
				$post.fadeOut(300, function () {
				$workarchive.scrollTop(0);
				$workarchive.fadeIn(300);
			  NProgress.done();
				$goarchive.show(0);
				$gopost.hide(0);
				$goworkarchive.show(0);
				$goworkpost.hide(0);
			});
		};

		function goworkpost() {
			  NProgress.start();
				$archive.fadeOut(300),
				$post.fadeOut(300),
				$workarchive.fadeOut(300, function () {
				$post.scrollTop(0);
				$post.fadeIn(300);
			  NProgress.done();
				$gopost.hide(0);
				$goarchive.show(0);
				$goworkarchive.show(0);
				$goworkpost.hide(0);
			});
		};



		function loadpost() {

			var perma = $(this).attr('rel'),
				postid = $(this).attr('id'),
				postitle = $(this).attr('title');

			$(this).parent().parent().addClass('loader');

			$post.load(perma + ' #post', function () {
			  	    NProgress.start();				
					$workarchive.fadeOut(300),
					$archive.fadeOut(300, function () {
					$gopost.hide(0);				
					$goarchive.show(0);
					$post.fadeIn(300, function () {
						$('#' + postid).parent().parent().removeClass('loader');
						window.location.hash = '/' + postitle;
						if (typeof twttr != 'undefined') {
							twttr.widgets.load()
						}
					$('html, body').animate({'scrollTop': 0});
			  		NProgress.done();
					});
				});
			});
		}

		function loadwork() {

			var workperma = $(this).attr('rel'),
				workid = $(this).attr('id'),
				workcat = ('portfolio'),
				worktitle = $(this).attr('title');

			$(this).parent().parent().addClass('loader');

			$post.load(workperma + ' #post', function () {
			  	    NProgress.start();				
					$workarchive.fadeOut(300),
					$archive.fadeOut(300, function () {
					$goworkpost.hide(0);				
					$goworkarchive.show(0);
					$post.fadeIn(300, function () {
						$('#' + workid).parent().parent().removeClass('loader');
						window.location.hash = '/' + workcat + '/' + worktitle + '/';
						if (typeof twttr != 'undefined') {
							twttr.widgets.load()
						}
					$('html, body').animate({'scrollTop': 0});
		    		NProgress.done();
					});
				});
			});
		}



		$goarchive.on('click',$goarchive,goarchive);

		$gopost.on('click',$gopost,gopost);

		$goworkarchive.on('click',$goworkarchive,goworkarchive);

		$goworkpost.on('click',$goworkpost,goworkpost);

		$archive.find('a').on('click',$archive.find('a'),loadpost);
		
		$workarchive.find('a').on('click',$workarchive.find('a'),loadwork);


		/* arrow key navigation */

		$(document).keydown(function(ev) {
			if(ev.which === 39) {
				if ( $post.is(':visible') ) {
					goarchive();
				}
				return false;
			}

			if(ev.which === 37) {
				if ( $archive.is(':visible') ) {
					gopost();
				}
				return false;
			}
		});


	});
	
    /* ============================================================ */
    /* Scroll To Top */
    /* ============================================================ */

    $('.js-jump-top').on('click', function(e) {
        e.preventDefault();

        $('html, body').animate({'scrollTop': 0});
    });

    /* ============================================================ */
    /* Top Bar */
    /* ============================================================ */
	$(document).ready(function () {
			    
		$("#topbar .open .right").click(function() {
			$("#topbar .open").slideUp(400, function() {
				$("#topbar .closed").fadeIn(600);
			});
		});
		
		$("#topbar .closed .right").click(function() {
			$("#topbar .closed").fadeOut(400, function() {
				$("#topbar .open").slideDown(600);
			});
		});
		
	});