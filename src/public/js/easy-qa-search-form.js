(function($) {
	'use strict';

	window.EasyQA = $.extend((window.EasyQA || {}), {
		SearchForm: function SearchForm(options) {
			this.options = $.extend({
				el: null
			}, options);

			this.$navBtns = this.options.el.find('.easy-qa-nav [data-toggle-form]');
			this.$searchForm = this.options.el.find('form[data-form="search"]');
			this.$askForm = this.options.el.find('form[data-form="ask"]');

			this.init();
		}
	});

	EasyQA.SearchForm.prototype.init = function() {
		this.displayActiveForm(false);
		this.bind();
	};

	EasyQA.SearchForm.prototype.bind = function() {
		var self = this;

		this.$navBtns.on('click', function(e) {
			e.preventDefault();

			self.$navBtns.removeClass('active');
			$(this).addClass('active');
			self.displayActiveForm();
		});

		this.$askForm.on('submit', function(e) {
			e.preventDefault();

			if (self.options.el.hasClass('loading')) {
				return false;
			}

			self.clearResponseErrors();

			if (!grecaptcha.getResponse(self.recaptcha)) {
				self.options.el.find('.recaptcha iframe').addClass('has-error');
				self.showAlert('danger', 'Please click the security checkbox and try again.');
				return false;
			}

			self.showLoadingState();

			$.post(window.easy_qa_php_vars.easy_qa_ajaxurl, self.$askForm.serialize())
				.then(function(response) {
					self.handleResponse(response);
				})
				.always(function() {
					self.hideLoadingState();
				});

		});
	};

	EasyQA.SearchForm.prototype.getActiveForm = function() {
		return this.$navBtns.filter(function() {
			return $(this).is('.active');
		}).data('toggle-form');
	};

	EasyQA.SearchForm.prototype.displayActiveForm = function(autofocus) {
		autofocus = $.type(autofocus) === 'undefined' ? true : autofocus;
		var self = this;
		this.options.el.find('form').each(function() {
			if ($(this).is('[data-form="' + self.getActiveForm() + '"]')) {
				if (autofocus) {
					var query = $(this).find('input:visible:first').val();
					$(this).show().find('input:visible:first').val('').focus().val(query);
				}
			} else {
				$(this).hide();
			}
		})
	};

	EasyQA.SearchForm.prototype.handleResponse = function(response) {

		try {
			response = $.parseJSON(response);
		} catch(e) {
			console.error('Invalid JSON response.', response);
			return false;
		}

		if (response.status == 200) {
			return this.questionAdded(response);
		} else if (response.status == 422) {
			if (response.errors.length) {
				this.displayResponseErrors(response.errors);
			}
		} else {
			return console.error('Question could not be processed.');
		}
	};

	EasyQA.SearchForm.prototype.questionAdded = function(response) {
		this.resetAskForm();

		if (response.message) {
			this.showAlert('success', response.message);
		}
	};

	EasyQA.SearchForm.prototype.showAlert = function(type, message) {
		var alert = ['<div class="alert alert-' + type + ' alert-dismissible" role="alert">',
										'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>',
										message,
									'</div>'].join('\n');
		this.options.el.prepend($(alert));
	};

	EasyQA.SearchForm.prototype.resetAskForm = function() {
		this.clearResponseErrors();
		this.$askForm.find('input:visible').val('');
		this.options.el.find('.alert').remove();
		grecaptcha.reset(this.recaptcha);
	};

	EasyQA.SearchForm.prototype.displayResponseErrors = function(errors) {
		this.clearResponseErrors();

		var self = this;

		if ($.isArray(errors) && errors.length) {
			$.each(errors, function(i, v) {
				var field = v.field;
				var error = v.error;
				var $field = self.$askForm.find(':input[name*="[' + field + ']"]');
				var $wrap = $field.closest('div').addClass('has-error');

				$('<p></p>').text(error).addClass('error-text').insertAfter($field);

				$field.off('focus.easy_qa_highlighted').on('focus.easy_qa_highlighted', function(e) {
					$wrap.removeClass('has-error');
					$wrap.find('.error-text').remove();
				});
			});
		}
	};

	EasyQA.SearchForm.prototype.clearResponseErrors = function() {
		this.options.el.find('.error-text').remove();
		this.options.el.find('.has-error').removeClass('has-error');
		this.options.el.find('.alert').remove();
	};

	EasyQA.SearchForm.prototype.showLoadingState = function() {
		this.options.el.addClass('loading');
	};

	EasyQA.SearchForm.prototype.hideLoadingState = function() {
		this.options.el.removeClass('loading');
	};

	EasyQA.SearchForm.prototype.initRecaptcha = function() {
		var self = this;

		this.recaptchaId = 'recaptcha-' + ($('.recaptcha.loaded').length + 1);

		this.$recaptcha = this.options.el.find('.recaptcha:first').attr('id', this.recaptchaId);

		this.recaptcha = function() {
			self.$recaptcha.addClass('loaded');

			return grecaptcha.render(self.recaptchaId, {
				'sitekey' : window.easy_qa_php_vars.easy_qa_recaptcha_sitekey,
				'theme' : 'light'
			});

		}();
	};

})(jQuery)