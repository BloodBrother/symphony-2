/**
 * @package assets
 */
(function ($) {

	'use strict';
	
	var doc = $(window.document);
	var canAnimate = false;
	
	var header = $();
	var headerBg = $();
	
	var TRIGGER_ZONE_X = ~~($(window).width() / 20);
	var FORCE_TOGGLE_ZONE = 0;
	var currentPos = 0;
	
	var updatePos = function (pos) {
		pos = !!canAnimate ? pos : header.outerWidth() * -1;
		pos = Math.min(pos, 0);
		header.css('transform', 'translateX(' + pos + 'px)');
		
		if (pos === 0 ) {
			window.Symphony.Elements.body.addClass('is-header-opened');
		} else {
			window.Symphony.Elements.body.removeClass('is-header-opened');
		}

		var opacity = (1 * pos) /header.outerWidth() * -1;
		opacity  = Math.min(1 - opacity, 0.4);

		headerBg.css('opacity', opacity);
		currentPos = pos;
	};
	
	var onTouchStart = function (event) {
		var x = event.originalEvent.changedTouches[0].clientX;
		var headerWidth = header.outerWidth();
		canAnimate = x <= TRIGGER_ZONE_X || currentPos !== headerWidth * -1 && x >= (headerWidth - TRIGGER_ZONE_X);
		window.Symphony.Elements.body.addClass('is-touching');
	};

	var onTouchMove = function (event) {
		var x = event.originalEvent.changedTouches[0].clientX;
		var onHeader = $(event.target).is(header) || $(event.target).closest('#header').length;

		if (canAnimate && !onHeader) {
			updatePos((header.outerWidth() * -1) - (x * -1));
		}
	};

	var onTouchEnd = function (event) {
		var x = event.originalEvent.changedTouches[0].clientX;
		var onHeader = $(event.target).is(header) || $(event.target).closest('#header').length;
		var headerWidth = header.outerWidth();
		window.Symphony.Elements.body.removeClass('is-touching');
		if	(!onHeader && currentPos !== headerWidth * -1) {
			updatePos(x >= FORCE_TOGGLE_ZONE && canAnimate ? 0 : headerWidth * -1);
		}
	};
	
	var init = function () {
		var onAndroid = window.navigator.userAgent.indexOf('Android') >= 0;

		header = window.Symphony.Elements.header;
		headerBg = $('#mobile-bg-menu-toggler');

		FORCE_TOGGLE_ZONE = ~~(header.outerWidth() / 2);

		if (!!onAndroid) {
			doc.on('touchstart', onTouchStart);
			doc.on('touchmove', onTouchMove);
			doc.on('touchend', onTouchEnd);
		}

		doc.on('click', '.js-symphony-close-header', function () {
			canAnimate = true;
			updatePos(currentPos === header.outerWidth() * -1 ? 0 : header.outerWidth() * -1);
			canAnimate = false;
		});
	};
	
	$(init);
	
})(jQuery);
