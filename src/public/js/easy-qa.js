(function( window, $ ) {
	'use strict';

	$(document).ready(function() {
		initSearchForms();
		initIsotope();
		initRatings();
		initSharethis();
	});

	function initSearchForms() {
		var $searchForms = $('.easy-qa-form');

		if ($searchForms.length) {
			var searchForms = [];
			$searchForms.each(function() {
				searchForms.push(new EasyQA.SearchForm({el: $(this)}));
			});
			window.easyQaRecaptchaOnloadCallback = function() {
				$.each(searchForms, function(i, searchForm) {
					searchForm.initRecaptcha();
				});
			}
		}
	}

	function initIsotope() {
		var $easyQaGrid = $('#easy-qa-grid');
		if ($easyQaGrid.length) {
			$easyQaGrid.isotope({
				itemSelector: '.item',
				layoutMode: 'masonry',
				masonry: {
					//gutter: 5
				}
			});
		}
	}

	function initRatings() {
		var ratingOptions = {
			starCaptions: {
				1: 'Poor',
				2: 'Adequate',
				3: 'Satifactory',
				4: 'Very Good',
				5: 'Excellent'
			},
			step: 1.0,
			showClear: false,
			size: 'xs',
			glyphicon: false
		};

		var $ratings = $('.easy-qa-ratings input.rating-stars');

		var $newRatings = $ratings.filter(function() {
			return $(this).is('[value=""]');
		});

		var $prefilledRatings = $ratings.filter(function() {
			return $.trim($(this).val()) !== '';
		});

		if ($newRatings.length) {
			$newRatings.each(function() {
				var postId = $(this).closest('form').find('input[name="easy_qa_rating[post_ID]"]').val();
				new EasyQA.Ratings({
					el: $(this),
					postId: postId,
					ratingOptions: ratingOptions
				});
			});
		}

		if ($prefilledRatings.length) {
			$prefilledRatings.each(function() {
				var $rating = $(this);
				var val = $rating.val();
				$rating.val('');
				$rating.rating($.extend(ratingOptions, {disabled:true}));
				$rating.rating('update', val);
			})
		}

	}

	function initSharethis() {
		var $sharethis = $('.easy-qa-sharethis');
		if ($sharethis.length && window.easy_qa_php_vars.easy_qa_sharethis_publisher_key && window.easy_qa_php_vars.easy_qa_sharethis_providers_code) {
			window.switchTo5x = true;
			stLight.options({publisher: window.easy_qa_php_vars.easy_qa_sharethis_publisher_key, doNotHash: false, doNotCopy: false, hashAddressBar: true});
		}
	}

})( window, jQuery );
