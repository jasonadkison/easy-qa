(function($) {
	'use strict';

	window.EasyQA = $.extend((window.EasyQA || {}), {
		Ratings: function Ratings(options) {
			this.options = $.extend({
				el: null,
				postId: null,
				localStorageKey: 'wp_easy_qa_ratings',
				ratingOptions: {}
			}, options);

			this.init();
		}
	});

	EasyQA.Ratings.prototype.init = function() {
		try {
			this.options.postId = parseInt(this.options.postId);
		} catch(e) {
		}

		if (isNaN(this.options.postId) || $.type(this.options.postId) !== 'number') {
			this.options.el.replaceWith('<div class="alert alert-danger"><strong>Error: </strong> Invalid ratings config. Missing or invalid postId setting.</div>');;
			return false;
		}

		this.options.el.rating(this.options.ratingOptions);

		this.updateUiFromStorage();

		this.bind();
	};

	EasyQA.Ratings.prototype.bind = function() {
		var self = this;
		this.options.el.off('rating.change').on('rating.change', function(e, value, caption) {
			if (self.isDuplicateRating()) {
				console.log('Duplicate rating for ' + self.options.postId + '.');
				return false;
			}

			self.rate(value);
		});
	};

	EasyQA.Ratings.prototype.rate = function(value) {
		this.setLocalRating(value);
		this.setRemoteRating(value);
	};

	EasyQA.Ratings.prototype.isDuplicateRating = function() {
		var localRating = this.getLocalRating();

		return (localRating > 0);
	};

	EasyQA.Ratings.prototype.getAllLocalRatings = function() {
		var ratings = $.parseJSON(localStorage.getItem(this.options.localStorageKey));

		if (!$.isPlainObject(ratings)) {
			ratings = {};
			localStorage.setItem(this.options.localStorageKey, JSON.stringify(ratings));
		}

		return ratings;
	};

	EasyQA.Ratings.prototype.getLocalRating = function() {
		var ratings = this.getAllLocalRatings();

		if ($.isEmptyObject(ratings) || isNaN(parseInt(ratings[this.options.postId]))) {
			this.setLocalRating(0);
			return 0;
		}

		return ratings[this.options.postId];
		
	};

	EasyQA.Ratings.prototype.setLocalRating = function(value) {
		var ratings = this.getAllLocalRatings();
		ratings[this.options.postId] = value;
		localStorage.setItem(this.options.localStorageKey, JSON.stringify(ratings));
		this.updateUiValue(value);
	};

	EasyQA.Ratings.prototype.setRemoteRating = function(value) {
		var self = this;
		return $.post(window.easy_qa_php_vars.easy_qa_ajaxurl, this.options.el.closest('form').serialize())
			.then(function(response) {
				try {
					response = $.parseJSON(response);
				} catch(e) {
					response = {};
				}

				if ($.isEmptyObject(response)) {
					console.error('The server responded with an error when posting a rating.');
					return false;
				}

				if (response.hasOwnProperty('status') && response.hasOwnProperty('message')) {
					console.log(response.status, response.message);
					self.options.el.rating('refresh', {disabled: true});
				}

			}, function() {
				console.error('Could not post rating due to server error.');
			});;
	};

	EasyQA.Ratings.prototype.updateUiValue = function(value) {
		this.options.el.val(value);
		this.options.el.rating('update', value);
	};

	EasyQA.Ratings.prototype.updateUiFromStorage = function() {
		var rating = this.getLocalRating();

		if (rating > 0) {
			this.updateUiValue(rating);
			this.options.el.rating('refresh', {disabled: true});
		}
	};

})(jQuery);