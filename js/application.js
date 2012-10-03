!function($) {
	$(function() {
		// Test links
		$('a[href="#"]').click(function(e) {
			e.preventDefault();
		});
	});

	// Equal heights
	$.fn.equalHeight = function() {
		var maxHeight = Math.max.apply(null, this.map(function() {
			return $(this).height();
		}).get());
		this.height(maxHeight);
	};

	$('.carousel').carousel();
}(jQuery);
