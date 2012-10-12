$ = jQuery.noConflict()

!($) ->
	$('a[href="#"]').click (e) ->
		e.preventDefault();