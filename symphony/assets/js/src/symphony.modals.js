/**
 * @package assets
 */
(function ($) {

	'use strict';

	var sels = {
		ctn: '.js-modal',
		trigger: '.js-modal-trigger'
	};

	var onClick = function (event) {
		var t = $(this);
		var ctn = t.closest(sels.ctn);
		$(sels.ctn).removeClass('is-open');
		ctn.addClass('is-open');
		event.preventDefault();
		return event.stopPropagation();
	};

	var onWindowClick = function () {
		$(sels.ctn).removeClass('is-open');
	};

	var init = function () {
		window.Symphony.Elements.body.on('click', sels.trigger, onClick);
		window.Symphony.Elements.window.on('click', onWindowClick);
	};

	$(init);

})(jQuery);
