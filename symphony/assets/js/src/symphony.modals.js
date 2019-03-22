/**
 * @package assets
 */
(function ($) {

	'use strict';

	var sels = {
		ctn: '.js-modal',
		trigger: '.js-modal-trigger',
		content: '.js-modal-content'
	};

	var onClick = function (event) {
		var t = $(this);
		var ctn = t.find(sels.ctn);
		ctn.find(sels.content).addClass('is-open');
		return event.stopPropagation();
	};

	var onWindowClick = function () {
		$(sels.content).removeClass('is-open');
	};

	var init = function () {
		$(sels.trigger).on('click', onClick);
		$(window).on('click', onWindowClick);
	};

	$(init);

})(jQuery);
