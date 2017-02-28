(function ($) {
	'use strict';

	var win = $(window);
	var o = {
		header: '#header',
		btnMobileNav: '#btn-toggle-header-mobile',
		nav: '#nav',
		navEl: '#nav li',
		navElFirst: '#nav > ul > li > span',
		actions: '.page-single #contents .actions, .single #contents .actions, body.entry_relationship.page-index #contents .actions',
		context: '#context',
		contextTabs: '#context .tabs li',
		contextDrawers: '#context > .actions a.button.drawer',
		contextActions: '#context > .actions a',
		contextActionsButt: '#context > .actions button',
		actionButtons: '.page-single #contents .actions .button-container, .single #contents .actions .button-container, body.entry_relationship.page-index #contents .actions .button-container',
		contents: '#contents',
		contentsForm: '#contents > form',
		tabGroup: '.tab-group',
		secTabGroup: '.secondary.column .tab-group',
		priTabGroup: '.primary.column .tab-group',
		columns: '.two.columns',
		secColumn: '.secondary.column',
		priColumn: '.primary.column',
		multiTabsEl: '.field-multilingual ul.tabs li',
		multiLabel: '.field-multilingual > .container > label',
		editorEl: '.editor-toolbar a',
		tableEl: 'table td',
		dashboard: '#dashboard',
		dashboardDrawerSelects: '#drawer-dashboard select',
		dashboardDrawerSelectsArrows: '#drawer-dashboard .select2-container .select2-selection--single .select2-selection__arrow',
		selectArrows: '.select2-container .select2-selection--single .select2-selection__arrow'
	};
	var s = {
		burger: '<svg version="1" xmlns="http://www.w3.org/2000/svg" width="24" height="15" viewBox="0 0 24 15" class="line-height-0 valign-top width-full height-full block"><path fill-rule="evenodd" clip-rule="evenodd" fill="currentColor" d="M0 0v3h24V0H0zm0 9h24V6H0v3zm0 6h24v-3H0v3z"/></svg>',
		arrow: '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="18px" height="10px" viewBox="0 0 18 10"><path fill="currentColor" d="M5,10c-0.3,0-0.5-0.1-0.7-0.3l-4-4c-0.4-0.4-0.4-1,0-1.4l4-4c0.4-0.4,1-0.4,1.4,0s0.4,1,0,1.4L2.4,5l3.3,3.3c0.4,0.4,0.4,1,0,1.4C5.5,9.9,5.3,10,5,10z"/><path fill="currentColor" d="M17,6H2C1.4,6,1,5.6,1,5s0.4-1,1-1h15c0.6,0,1,0.4,1,1S17.6,6,17,6z"/></svg>',
		chevron: '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="9.2px" height="5.6px" viewBox="0 0 9.2 5.6"><path fill="currentColor" d="M4.6,5.6c-0.3,0-0.5-0.1-0.7-0.3L0.3,1.7c-0.4-0.4-0.4-1,0-1.4s1-0.4,1.4,0l2.9,2.9l2.8-2.8c0.4-0.4,1-0.4,1.4,0s0.4,1,0,1.4L5.3,5.3C5.2,5.5,4.9,5.6,4.6,5.6z"/></svg>',
		view: '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="33.7px" height="19.3px" viewBox="0 0 33.7 19.3"><path fill="currentColor" d="M16.8,19.3c-9.1,0-16.3-8.7-16.6-9c-0.3-0.4-0.3-0.9,0-1.3c0.3-0.4,7.5-9,16.6-9s16.3,8.7,16.6,9c0.3,0.4,0.3,0.9,0,1.3C33.2,10.7,26,19.3,16.8,19.3z M2.3,9.7c1.8,1.9,7.7,7.7,14.5,7.7c6.8,0,12.7-5.7,14.5-7.7C29.6,7.7,23.7,2,16.8,2C10,2,4.1,7.7,2.3,9.7z"/><path fill="currentColor" d="M16.8,15.3c-3.1,0-5.6-2.5-5.6-5.6c0-3.1,2.5-5.6,5.6-5.6s5.6,2.5,5.6,5.6C22.5,12.8,20,15.3,16.8,15.3zM16.8,6c-2,0-3.6,1.6-3.6,3.6s1.6,3.6,3.6,3.6s3.6-1.6,3.6-3.6S18.9,6,16.8,6z"/></svg>',
		hide: '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="33.7px" height="26px" viewBox="0 0 33.7 26"><path fill="currentColor" d="M16.8,22.7c-1.1,0-2.2-0.1-3.5-0.4c-0.5-0.1-0.9-0.7-0.7-1.2s0.7-0.9,1.2-0.7c1,0.2,2,0.4,3,0.4c6.8,0,12.7-5.7,14.5-7.7c-0.9-0.9-2.6-2.7-4.9-4.3c-0.5-0.3-0.6-0.9-0.3-1.4c0.3-0.5,0.9-0.6,1.4-0.3c3.6,2.4,5.8,5.2,5.9,5.3c0.3,0.4,0.3,0.9,0,1.3C33.2,14,26,22.7,16.8,22.7z"/><path fill="currentColor" d="M9.9,21c-0.1,0-0.3,0-0.4-0.1c-5.4-2.6-9.1-7-9.3-7.2c-0.3-0.4-0.3-0.9,0-1.3c0.3-0.4,7.5-9,16.6-9c2.4,0,4.9,0.6,7.4,1.8C24.8,5.4,25,6,24.7,6.5C24.5,7,23.9,7.2,23.4,7c-2.3-1.1-4.5-1.6-6.5-1.6C10,5.4,4.1,11.1,2.3,13c1.2,1.3,4.2,4.2,8,6.1c0.5,0.2,0.7,0.8,0.5,1.3C10.7,20.8,10.3,21,9.9,21z"/><path fill="currentColor" d="M13.6,17.3c-0.3,0-0.5-0.1-0.7-0.3c-1.1-1.1-1.7-2.5-1.7-4c0-3.1,2.5-5.6,5.6-5.6c1.5,0,2.9,0.6,4,1.7c0.4,0.4,0.4,1,0,1.4c-0.4,0.4-1,0.4-1.4,0c-0.7-0.7-1.6-1.1-2.6-1.1c-2,0-3.6,1.6-3.6,3.6c0,1,0.4,1.9,1.1,2.6c0.4,0.4,0.4,1,0,1.4C14.1,17.2,13.8,17.3,13.6,17.3z"/><path fill="currentColor" d="M16.8,18.7c-0.6,0-1-0.4-1-1s0.4-1,1-1c0.1,0,0.1,0,0.2,0l0.2,0c0.6,0,1,0.3,1.1,0.9c0.1,0.5-0.3,1-0.9,1.1l-0.1,0C17.1,18.7,17,18.7,16.8,18.7z"/><path fill="currentColor" d="M21.4,14.5C21.4,14.5,21.4,14.5,21.4,14.5c-0.6-0.1-1.1-0.5-1-1.1l0-0.2c0-0.1,0-0.1,0-0.2c0-0.6,0.4-1,1-1s1,0.4,1,1c0,0.1,0,0.3,0,0.4l0,0.1C22.4,14.1,22,14.5,21.4,14.5z"/><path fill="currentColor" d="M17.3,18.6c-0.5,0-0.9-0.4-1-0.9c-0.1-0.5,0.3-1,0.9-1.1c1.7-0.2,3.1-1.5,3.3-3.3c0.1-0.5,0.5-0.9,1.1-0.9c0.5,0.1,1,0.5,0.9,1.1C22.2,16.2,20.1,18.4,17.3,18.6C17.4,18.6,17.3,18.6,17.3,18.6z"/><path fill="currentColor" d="M23.8,7.1c-0.3,0-0.5-0.1-0.7-0.3c-0.4-0.4-0.4-1,0-1.4l5.1-5.1c0.4-0.4,1-0.4,1.4,0s0.4,1,0,1.4l-5.1,5.1C24.3,7,24.1,7.1,23.8,7.1z"/><path fill="currentColor" d="M4.9,26c-0.3,0-0.5-0.1-0.7-0.3c-0.4-0.4-0.4-1,0-1.4l5-5c0.4-0.4,1-0.4,1.4,0s0.4,1,0,1.4l-5,5C5.4,25.9,5.2,26,4.9,26z"/><path fill="currentColor" d="M20.2,10.8c-0.3,0-0.5-0.1-0.7-0.3c-0.4-0.4-0.4-1,0-1.4l3.7-3.7c0.4-0.4,1-0.4,1.4,0s0.4,1,0,1.4l-3.7,3.7C20.7,10.7,20.4,10.8,20.2,10.8z"/><path fill="currentColor" d="M13.6,17.3c-0.3,0-0.5-0.1-0.7-0.3c-0.4-0.4-0.4-1,0-1.4l6.6-6.6c0.4-0.4,1-0.4,1.4,0s0.4,1,0,1.4L14.3,17C14.1,17.2,13.8,17.3,13.6,17.3z"/><path fill="currentColor" d="M9.9,21c-0.3,0-0.5-0.1-0.7-0.3c-0.4-0.4-0.4-1,0-1.4l3.7-3.7c0.4-0.4,1-0.4,1.4,0s0.4,1,0,1.4l-3.7,3.7C10.4,20.9,10.2,21,9.9,21z"/></svg>',
		add: '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="27px" height="26px" viewBox="0 0 27 26"><path fill="currentColor" d="M26,24c-0.6,0-1-0.4-1-1V11.5L22.6,11c0,0,0,0,0,0C18.9,11,16,8.5,16,4.9V1c0-0.6,0.4-1,1-1s1,0.4,1,1v3.9c0,2.5,2,4.5,4.6,4.5L26,9.6c0.5,0,1,0.5,1,1V23C27,23.6,26.6,24,26,24z"/><path fill="currentColor" d="M24,26H8c-1.7,0-3-1.3-3-3c0-0.6,0.4-1,1-1s1,0.4,1,1c0,0.6,0.4,1,1,1h16c0.6,0,1-0.4,1-1V10.1c-0.6-1.3-6.8-7.5-8.1-8.1H8C7.4,2,7,2.4,7,3c0,0.6-0.4,1-1,1S5,3.6,5,3c0-1.7,1.3-3,3-3h9c1.7,0,10,8.3,10,10v13C27,24.7,25.7,26,24,26z M25,10.2L25,10.2L25,10.2z M16.8,2L16.8,2L16.8,2z"/><path fill="currentColor" d="M11,14H1c-0.6,0-1-0.4-1-1s0.4-1,1-1h10c0.6,0,1,0.4,1,1S11.6,14,11,14z"/><path fill="currentColor" d="M6,19c-0.6,0-1-0.4-1-1V8c0-0.6,0.4-1,1-1s1,0.4,1,1v10C7,18.6,6.6,19,6,19z"/></svg>',
		edit: '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="26px" height="26px" viewBox="0 0 26 26"><path fill="currentColor" d="M13.2,26c-2.2,0-2.8-1.9-3.1-3c-0.1-0.4-0.2-0.7-0.4-1c0-0.1-0.1-0.2-0.1-0.2c-0.1,0-0.1,0-0.2,0.1c-0.3,0.1-0.6,0.3-1,0.5c-1,0.6-2.8,1.6-4.4,0c-1.6-1.6-0.7-3.3-0.2-4.4c0.2-0.4,0.4-0.7,0.4-1c0-0.1,0-0.2,0.1-0.2c-0.1,0-0.1-0.1-0.2-0.1c-0.3-0.1-0.6-0.2-1-0.3C2,16,0,15.5,0,13.3c0-2.2,1.9-2.8,3-3.2c0.4-0.1,0.7-0.2,1-0.4c0.1,0,0.2-0.1,0.2-0.1c0-0.1,0-0.1-0.1-0.2C4,9,3.8,8.7,3.6,8.4c-0.6-1-1.6-2.8,0-4.4C5.2,2.4,6.9,3.3,8,3.8C8.4,4,8.7,4.2,9,4.3c0.1,0,0.2,0,0.2,0.1c0-0.1,0.1-0.1,0.1-0.2c0.1-0.3,0.2-0.6,0.3-1C10,2,10.5,0,12.7,0c2.2,0,2.8,1.9,3.2,3c0.1,0.4,0.2,0.7,0.4,1c0.1,0.1,0.1,0.2,0.1,0.2c0.1,0,0.1,0,0.2-0.1C17,4,17.3,3.9,17.6,3.7c1-0.6,2.8-1.6,4.4,0c1.6,1.6,0.7,3.3,0.2,4.4c-0.2,0.4-0.4,0.7-0.4,1c0,0.1,0,0.2-0.1,0.2c0.1,0,0.1,0.1,0.2,0.1c0.3,0.1,0.6,0.2,1,0.3C24,10,26,10.5,26,12.7c0,2.3-1.9,2.8-3,3.2c-0.4,0.1-0.7,0.2-1,0.4c-0.1,0-0.2,0.1-0.2,0.1c0,0.1,0,0.1,0.1,0.2c0.1,0.3,0.3,0.6,0.5,1c0.6,1,1.6,2.8,0,4.4c-1.6,1.6-3.3,0.7-4.4,0.2c-0.4-0.2-0.7-0.4-1-0.4c-0.1,0-0.2,0-0.2-0.1c0,0.1-0.1,0.1-0.1,0.2c-0.1,0.3-0.2,0.6-0.3,1C16,24,15.5,26,13.2,26C13.2,26,13.2,26,13.2,26z M9.8,19.8c0.2,0,0.3,0,0.5,0.1c0.6,0.2,0.9,0.7,1.2,1.2c0.2,0.4,0.4,0.9,0.5,1.4c0.4,1.3,0.6,1.6,1.3,1.6c0.6,0,0.8-0.3,1.2-1.6c0.1-0.5,0.3-1,0.5-1.4c0.3-0.5,0.6-1,1-1.2c0.5-0.2,1-0.1,1.7,0c0.5,0.1,0.9,0.4,1.3,0.6c1.2,0.7,1.6,0.7,2,0.2c0.4-0.5,0.4-0.8-0.3-2c-0.2-0.4-0.5-0.9-0.6-1.3c-0.2-0.6-0.3-1.1-0.1-1.6c0.2-0.5,0.6-0.8,1.2-1.1c0,0,0,0,0,0c0.4-0.2,0.9-0.4,1.4-0.5c1.3-0.4,1.6-0.6,1.6-1.3s-0.3-0.8-1.6-1.2c-0.5-0.1-1-0.3-1.4-0.5c-0.5-0.3-1-0.6-1.2-1c-0.2-0.6-0.1-1.1,0-1.7c0.1-0.5,0.4-0.9,0.6-1.3c0.7-1.2,0.7-1.6,0.2-2c-0.5-0.4-0.8-0.4-2,0.3c-0.4,0.2-0.9,0.5-1.3,0.6c-0.6,0.2-1.1,0.3-1.6,0.1c-0.6-0.2-0.9-0.7-1.1-1.2C14.3,4.5,14.2,4,14,3.6C13.6,2.2,13.4,2,12.8,2c-0.6,0-0.8,0.3-1.2,1.6c-0.1,0.5-0.3,1-0.5,1.4c-0.3,0.5-0.6,1-1,1.2C9.5,6.5,9,6.4,8.4,6.2C8,6.1,7.5,5.8,7.1,5.6C5.8,5,5.5,4.9,5.1,5.4c-0.4,0.5-0.4,0.8,0.3,2C5.6,7.8,5.9,8.2,6,8.7c0.2,0.6,0.3,1.1,0.2,1.5c0,0,0,0.1,0,0.1c-0.2,0.4-0.6,0.8-1.2,1.1c-0.4,0.2-0.9,0.4-1.4,0.5C2.2,12.4,2,12.6,2,13.2c0,0.6,0.3,0.8,1.6,1.2c0.5,0.1,1,0.3,1.4,0.5c0.5,0.3,1,0.6,1.2,1c0.2,0.6,0.1,1.1,0,1.7c-0.1,0.5-0.4,0.9-0.6,1.3c-0.7,1.2-0.7,1.6-0.2,2c0.5,0.4,0.8,0.4,2-0.3c0.4-0.2,0.9-0.5,1.3-0.6C9.1,19.8,9.5,19.8,9.8,19.8z"/><path fill="currentColor" d="M13,19.2c-3.4,0-6.2-2.8-6.2-6.2S9.6,6.8,13,6.8s6.2,2.8,6.2,6.2S16.4,19.2,13,19.2z M13,8.8c-2.3,0-4.2,1.9-4.2,4.2s1.9,4.2,4.2,4.2s4.2-1.9,4.2-4.2S15.3,8.8,13,8.8z"/></svg>',
		save: '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="28.7px" height="19.3px" viewBox="0 0 28.7 19.3"><path fill="currentColor" d="M21.2,19.3H6.4C2.8,19.3,0,16.5,0,13c0-2.9,2-5.4,4.7-6.1C5.5,2.9,9,0,13.2,0c2.3,0,4.4,0.9,6.1,2.5c0.4,0.4,0.4,1,0,1.4c-0.4,0.4-1,0.4-1.4,0C16.6,2.7,15,2,13.2,2C9.8,2,7,4.5,6.6,7.9c0,0.5-0.4,0.8-0.9,0.9C3.6,9.1,2,10.9,2,13c0,2.4,1.9,4.3,4.4,4.3h14.8c3.1,0,5.5-2.4,5.5-5.4c0-1.9-1.1-3.7-2.8-4.7c-0.5-0.3-0.6-0.9-0.4-1.4c0.3-0.5,0.9-0.6,1.4-0.4c2.3,1.3,3.8,3.8,3.8,6.4C28.7,16,25.4,19.3,21.2,19.3z"/><path fill="currentColor" d="M13.9,13.2L13.9,13.2c-0.3,0-0.5-0.1-0.7-0.3L9.5,9.3c-0.4-0.4-0.4-1,0-1.4s1-0.4,1.4,0l2.9,2.9l9.3-9.3c0.4-0.4,1-0.4,1.4,0s0.4,1,0,1.4l-10,10C14.4,13.1,14.1,13.2,13.9,13.2z"/></svg>',
		clone: '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="28px" height="31px" viewBox="0 0 28 31"><path fill="currentColor" d="M21,29c-0.6,0-1-0.4-1-1V16.5L17.6,16c0,0,0,0,0,0c-3.7,0-6.6-2.5-6.6-6.1V6c0-0.6,0.4-1,1-1s1,0.4,1,1v3.9c0,2.5,2,4.5,4.6,4.5l3.4,0.1c0.5,0,1,0.5,1,1V28C22,28.6,21.6,29,21,29z"/><path fill="currentColor" d="M27,24c-0.6,0-1-0.4-1-1V11.5L23.6,11c0,0,0,0,0,0C19.9,11,17,8.5,17,4.9V1c0-0.6,0.4-1,1-1s1,0.4,1,1v3.9c0,2.5,2,4.5,4.6,4.5L27,9.6c0.5,0,1,0.5,1,1V23C28,23.6,27.6,24,27,24z"/><path fill="currentColor" d="M16,25H6c-0.6,0-1-0.4-1-1s0.4-1,1-1h10c0.6,0,1,0.4,1,1S16.6,25,16,25z"/><path fill="currentColor" d="M16,20H6c-0.6,0-1-0.4-1-1s0.4-1,1-1h10c0.6,0,1,0.4,1,1S16.6,20,16,20z"/><path fill="currentColor" d="M9,15H6c-0.6,0-1-0.4-1-1s0.4-1,1-1h3c0.6,0,1,0.4,1,1S9.6,15,9,15z"/><path fill="currentColor" d="M19,31H3c-1.7,0-3-1.3-3-3V8c0-1.7,1.3-3,3-3h9c1.7,0,10,8.3,10,10v13C22,29.7,20.7,31,19,31z M3,7C2.4,7,2,7.4,2,8v20c0,0.6,0.4,1,1,1h16c0.6,0,1-0.4,1-1V15.1c-0.6-1.3-6.8-7.5-8.1-8.1H3z M20,15.2L20,15.2L20,15.2z M11.8,7L11.8,7L11.8,7z"/><path fill="currentColor" d="M25,26c-0.6,0-1-0.4-1-1s0.4-1,1-1c0.6,0,1-0.4,1-1V10.1c-0.6-1.3-6.8-7.5-8.1-8.1H9C8.4,2,8,2.4,8,3c0,0.6-0.4,1-1,1S6,3.6,6,3c0-1.7,1.3-3,3-3h9c1.7,0,10,8.3,10,10v13C28,24.7,26.7,26,25,26z M26,10.2L26,10.2L26,10.2z M17.8,2L17.8,2L17.8,2z"/></svg>',
		delete: '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="26.2px" height="26px" viewBox="0 0 26.2 26"><path fill="currentColor" d="M25.2,24c-0.6,0-1-0.4-1-1V11.5L21.8,11c0,0,0,0,0,0c-3.7,0-6.6-2.5-6.6-6.1V1c0-0.6,0.4-1,1-1s1,0.4,1,1v3.9c0,2.5,2,4.5,4.6,4.5l3.4,0.1c0.5,0,1,0.5,1,1V23C26.2,23.6,25.8,24,25.2,24z"/><path fill="currentColor" d="M23.2,26h-16c-1.7,0-3-1.3-3-3c0-0.6,0.4-1,1-1s1,0.4,1,1c0,0.6,0.4,1,1,1h16c0.6,0,1-0.4,1-1V10.1c-0.6-1.3-6.8-7.5-8.1-8.1H7.2c-0.6,0-1,0.4-1,1c0,0.6-0.4,1-1,1s-1-0.4-1-1c0-1.7,1.3-3,3-3h9c1.7,0,10,8.3,10,10v13C26.2,24.7,24.9,26,23.2,26z M24.3,10.2L24.3,10.2L24.3,10.2z M16.1,2L16.1,2L16.1,2z"/><path fill="currentColor" d="M1,18.2c-0.3,0-0.5-0.1-0.7-0.3c-0.4-0.4-0.4-1,0-1.4l8.5-8.5c0.4-0.4,1-0.4,1.4,0s0.4,1,0,1.4l-8.5,8.5C1.5,18.1,1.3,18.2,1,18.2z"/><path fill="currentColor" d="M9.5,18.2c-0.3,0-0.5-0.1-0.7-0.3L0.3,9.5c-0.4-0.4-0.4-1,0-1.4s1-0.4,1.4,0l8.5,8.5c0.4,0.4,0.4,1,0,1.4C10,18.1,9.7,18.2,9.5,18.2z"/></svg>',
		site: '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="22px" height="26px" viewBox="0 0 22 26"><path fill="currentColor" d="M21,24c-0.6,0-1-0.4-1-1V11.5L17.6,11c0,0,0,0,0,0C13.9,11,11,8.5,11,4.9V1c0-0.6,0.4-1,1-1s1,0.4,1,1v3.9c0,2.5,2,4.5,4.6,4.5L21,9.6c0.5,0,1,0.5,1,1V23C22,23.6,21.6,24,21,24z"/><path fill="currentColor" d="M16,20H6c-0.6,0-1-0.4-1-1s0.4-1,1-1h10c0.6,0,1,0.4,1,1S16.6,20,16,20z"/><path fill="currentColor" d="M16,15H6c-0.6,0-1-0.4-1-1s0.4-1,1-1h10c0.6,0,1,0.4,1,1S16.6,15,16,15z"/><path fill="currentColor" d="M9,10H6c-0.6,0-1-0.4-1-1s0.4-1,1-1h3c0.6,0,1,0.4,1,1S9.6,10,9,10z"/><path fill="currentColor" d="M19,26H3c-1.7,0-3-1.3-3-3V3c0-1.7,1.3-3,3-3h9c1.7,0,10,8.3,10,10v13C22,24.7,20.7,26,19,26z M3,2C2.4,2,2,2.4,2,3v20c0,0.6,0.4,1,1,1h16c0.6,0,1-0.4,1-1V10.1c-0.6-1.3-6.8-7.5-8.1-8.1H3z M20,10.2L20,10.2L20,10.2z M11.8,2L11.8,2L11.8,2z"/></svg>',                           
		random: '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="23.3px" height="23.3px" viewBox="0 0 23.3 23.3"><path fill="currentColor" d="M20.3,23.3H10.1c-1.7,0-3-1.3-3-3V10.1c0-1.7,1.3-3,3-3h10.2c1.7,0,3,1.3,3,3v10.2C23.3,22,22,23.3,20.3,23.3zM10.1,9.1c-0.6,0-1,0.4-1,1v10.2c0,0.6,0.4,1,1,1h10.2c0.6,0,1-0.4,1-1V10.1c0-0.6-0.4-1-1-1H10.1z"/><circle fill="currentColor" cx="11.5" cy="11.8" r="1.2"/><path fill="currentColor" d="M11.5,13.2c-0.8,0-1.4-0.6-1.4-1.4s0.6-1.4,1.4-1.4s1.4,0.6,1.4,1.4S12.2,13.2,11.5,13.2z M11.5,10.9c-0.5,0-0.9,0.4-0.9,0.9s0.4,0.9,0.9,0.9s0.9-0.4,0.9-0.9S12,10.9,11.5,10.9z"/><circle fill="currentColor" cx="18.6" cy="18.9" r="1.2"/><path fill="currentColor" d="M18.6,20.3c-0.8,0-1.4-0.6-1.4-1.4c0-0.8,0.6-1.4,1.4-1.4s1.4,0.6,1.4,1.4C20,19.7,19.3,20.3,18.6,20.3zM18.6,18c-0.5,0-0.9,0.4-0.9,0.9c0,0.5,0.4,0.9,0.9,0.9s0.9-0.4,0.9-0.9C19.5,18.4,19.1,18,18.6,18z"/><circle fill="currentColor" cx="11.5" cy="18.9" r="1.2"/><path fill="currentColor" d="M11.5,20.3c-0.8,0-1.4-0.6-1.4-1.4c0-0.8,0.6-1.4,1.4-1.4s1.4,0.6,1.4,1.4C12.9,19.7,12.2,20.3,11.5,20.3zM11.5,18c-0.5,0-0.9,0.4-0.9,0.9c0,0.5,0.4,0.9,0.9,0.9s0.9-0.4,0.9-0.9C12.4,18.4,12,18,11.5,18z"/><circle fill="currentColor" cx="15" cy="15.3" r="1.2"/><path fill="currentColor" d="M15,16.7c-0.8,0-1.4-0.6-1.4-1.4s0.6-1.4,1.4-1.4s1.4,0.6,1.4,1.4S15.8,16.7,15,16.7z M15,14.4c-0.5,0-0.9,0.4-0.9,0.9s0.4,0.9,0.9,0.9s0.9-0.4,0.9-0.9S15.5,14.4,15,14.4z"/><circle fill="currentColor" cx="18.6" cy="11.8" r="1.2"/><path fill="currentColor" d="M18.6,13.2c-0.8,0-1.4-0.6-1.4-1.4s0.6-1.4,1.4-1.4S20,11,20,11.8S19.3,13.2,18.6,13.2z M18.6,10.9c-0.5,0-0.9,0.4-0.9,0.9s0.4,0.9,0.9,0.9s0.9-0.4,0.9-0.9S19.1,10.9,18.6,10.9z"/><path fill="currentColor" d="M4.3,16.2H2.8C1.2,16.2,0,15,0,13.4V2.8C0,1.2,1.2,0,2.8,0h10.7c1.5,0,2.8,1.2,2.8,2.8v2.7c0,0.6-0.4,1-1,1s-1-0.4-1-1V2.8c0-0.4-0.3-0.8-0.8-0.8H2.8C2.3,2,2,2.3,2,2.8v10.7c0,0.4,0.3,0.8,0.8,0.8h1.6c0.6,0,1,0.4,1,1S4.9,16.2,4.3,16.2z"/><circle fill="currentColor" cx="4.3" cy="4.7" r="1.2"/><path fill="currentColor" d="M4.3,6.1c-0.8,0-1.4-0.6-1.4-1.4c0-0.8,0.6-1.4,1.4-1.4c0.8,0,1.4,0.6,1.4,1.4C5.8,5.4,5.1,6.1,4.3,6.1zM4.3,3.7c-0.5,0-0.9,0.4-0.9,0.9c0,0.5,0.4,0.9,0.9,0.9c0.5,0,0.9-0.4,0.9-0.9C5.3,4.2,4.8,3.7,4.3,3.7z"/><circle fill="currentColor" cx="11.3" cy="4.7" r="1.2"/><path fill="currentColor" d="M11.3,6.1c-0.8,0-1.4-0.6-1.4-1.4c0-0.8,0.6-1.4,1.4-1.4c0.8,0,1.4,0.6,1.4,1.4C12.8,5.4,12.1,6.1,11.3,6.1zM11.3,3.7c-0.5,0-0.9,0.4-0.9,0.9c0,0.5,0.4,0.9,0.9,0.9c0.5,0,0.9-0.4,0.9-0.9C12.3,4.2,11.8,3.7,11.3,3.7z"/><circle fill="currentColor" cx="4.3" cy="11.7" r="1.2"/><path fill="currentColor" d="M4.3,13.1c-0.8,0-1.4-0.6-1.4-1.4c0-0.8,0.6-1.4,1.4-1.4c0.8,0,1.4,0.6,1.4,1.4C5.8,12.4,5.1,13.1,4.3,13.1zM4.3,10.7c-0.5,0-0.9,0.4-0.9,0.9c0,0.5,0.4,0.9,0.9,0.9c0.5,0,0.9-0.4,0.9-0.9C5.3,11.2,4.8,10.7,4.3,10.7z"/></svg>',
		associations: '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="30px" height="24px" viewBox="0 0 30 24"><path fill="currentColor" d="M26,24c-1.1,0-2.1-0.4-2.8-1.2c-1.6-1.6-1.6-4.1,0-5.7c1.5-1.5,4.1-1.5,5.7,0c1.6,1.6,1.6,4.1,0,5.7C28.1,23.5,27.1,24,26,24z M26,18c-0.5,0-1,0.2-1.4,0.6c-0.8,0.8-0.8,2,0,2.8c0.8,0.8,2.1,0.8,2.8,0c0.8-0.8,0.8-2,0-2.8C27,18.2,26.5,18,26,18z"/><path fill="currentColor" d="M4,8C2.9,8,1.9,7.5,1.2,6.8c-1.6-1.6-1.6-4.1,0-5.7c1.5-1.5,4.1-1.5,5.7,0c1.6,1.6,1.6,4.1,0,5.7C6.1,7.5,5.1,8,4,8z M4,2C3.5,2,3,2.2,2.6,2.5c-0.8,0.8-0.8,2,0,2.8c0.8,0.8,2.1,0.8,2.8,0c0.8-0.8,0.8-2,0-2.8C5,2.2,4.5,2,4,2z"/><path fill="currentColor" d="M26,8c-1.1,0-2.1-0.4-2.8-1.2c-1.6-1.6-1.6-4.1,0-5.7c1.5-1.5,4.1-1.5,5.7,0c1.6,1.6,1.6,4.1,0,5.7C28.1,7.5,27.1,8,26,8z M26,2c-0.5,0-1,0.2-1.4,0.6c-0.8,0.8-0.8,2,0,2.8c0.8,0.8,2.1,0.8,2.8,0c0.8-0.8,0.8-2,0-2.8C27,2.2,26.5,2,26,2z"/><path fill="currentColor" d="M22.5,20.7c-7,0-8-4.9-8.9-8.8C12.8,8.1,12.1,5,7,5C6.4,5,6,4.5,6,4s0.4-1,1-1h16c0.6,0,1,0.4,1,1s-0.4,1-1,1H12.8c1.8,1.7,2.3,4.3,2.8,6.6c0.9,4,1.5,7.2,6.9,7.2c0.6,0,1,0.4,1,1S23,20.7,22.5,20.7z"/></svg>',
		filter: '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="26px" height="26px" viewBox="0 0 26 26"><path fill="currentColor" d="M25,5H1C0.4,5,0,4.5,0,4s0.4-1,1-1h24c0.6,0,1,0.4,1,1S25.6,5,25,5z"/><ellipse transform="matrix(0.7071 -0.7071 0.7071 0.7071 -0.7512 6.1102)" fill="#fff" cx="7" cy="4" rx="3" ry="3"/><path fill="currentColor" d="M7,8C5.9,8,4.9,7.5,4.2,6.8c-1.6-1.6-1.6-4.1,0-5.7c1.5-1.5,4.1-1.5,5.7,0c1.6,1.6,1.6,4.1,0,5.7C9.1,7.5,8.1,8,7,8z M7,2C6.5,2,6,2.2,5.6,2.5c-0.8,0.8-0.8,2,0,2.8c0.8,0.8,2.1,0.8,2.8,0c0.8-0.8,0.8-2,0-2.8C8,2.2,7.5,2,7,2z"/><path fill="currentColor" d="M25,14H1c-0.6,0-1-0.4-1-1s0.4-1,1-1h24c0.6,0,1,0.4,1,1S25.6,14,25,14z"/><ellipse transform="matrix(0.7071 -0.7071 0.7071 0.7071 -3.3076 17.9386)" fill="#fff" cx="20" cy="13" rx="3" ry="3"/><path fill="currentColor" d="M20,17c-1.1,0-2.1-0.4-2.8-1.2c-1.6-1.6-1.6-4.1,0-5.7c1.5-1.5,4.1-1.5,5.7,0c1.6,1.6,1.6,4.1,0,5.7C22.1,16.5,21.1,17,20,17z M20,11c-0.5,0-1,0.2-1.4,0.6c-0.8,0.8-0.8,2,0,2.8c0.8,0.8,2.1,0.8,2.8,0c0.8-0.8,0.8-2,0-2.8C21,11.2,20.5,11,20,11z"/><path fill="currentColor" d="M25,23H1c-0.6,0-1-0.4-1-1s0.4-1,1-1h24c0.6,0,1,0.4,1,1S25.6,23,25,23z"/><ellipse transform="matrix(0.7071 -0.7071 0.7071 0.7071 -12.3076 14.2107)" fill="#fff" cx="11" cy="22" rx="3" ry="3"/><path fill="currentColor" d="M11,26c-1.1,0-2.1-0.4-2.8-1.2c-1.6-1.6-1.6-4.1,0-5.7c1.5-1.5,4.1-1.5,5.7,0c1.6,1.6,1.6,4.1,0,5.7C13.1,25.5,12.1,26,11,26z M11,20c-0.5,0-1,0.2-1.4,0.6c-0.8,0.8-0.8,2,0,2.8c0.8,0.8,2.1,0.8,2.8,0c0.8-0.8,0.8-2,0-2.8C12,20.2,11.5,20,11,20z"/></svg>',
		locked: '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="22px" height="26.4px" viewBox="0 0 22 26.4"><path fill="currentColor" d="M11.1,21.7c-1.7,0-3-1.3-3-3s1.3-3,3-3s3,1.3,3,3S12.8,21.7,11.1,21.7z M11.1,17.7c-0.6,0-1,0.4-1,1s0.4,1,1,1s1-0.4,1-1S11.7,17.7,11.1,17.7z"/><path fill="currentColor" d="M19,26.4H3c-1.7,0-3-1.3-3-3v-9c0-1.7,1.3-3,3-3h16c1.7,0,3,1.3,3,3v9C22,25.1,20.7,26.4,19,26.4z M3,13.4c-0.6,0-1,0.4-1,1v9c0,0.6,0.4,1,1,1h16c0.6,0,1-0.4,1-1v-9c0-0.6-0.4-1-1-1H3z"/><path fill="currentColor" d="M18,11.9c-0.6,0-1-0.4-1-1V8.3C17,4.8,14.4,2,11,2c-0.1,0-0.2,0-0.2,0C7.5,2.1,5,4.7,5,8.1v2.8c0,0.6-0.4,1-1,1s-1-0.4-1-1V8.1C3,3.6,6.5,0,11,0c0.1,0,0.2,0,0.3,0C15.6,0.2,19,3.8,19,8.3v2.6C19,11.5,18.6,11.9,18,11.9z"/></svg>',
		unlocked: '<svg version="1.1" xmlns="http://www.w3.org/2000/svg"  width="22px" height="26.4px" viewBox="0 0 22 26.4"><path fill="currentColor" d="M11.1,21.7c-1.7,0-3-1.3-3-3s1.3-3,3-3s3,1.3,3,3S12.8,21.7,11.1,21.7z M11.1,17.7c-0.6,0-1,0.4-1,1s0.4,1,1,1s1-0.4,1-1S11.7,17.7,11.1,17.7z"/><path fill="currentColor" d="M19,26.4H3c-1.7,0-3-1.3-3-3v-9c0-1.7,1.3-3,3-3h16c1.7,0,3,1.3,3,3v9C22,25.1,20.7,26.4,19,26.4z M3,13.4c-0.6,0-1,0.4-1,1v9c0,0.6,0.4,1,1,1h16c0.6,0,1-0.4,1-1v-9c0-0.6-0.4-1-1-1H3z"/><path fill="currentColor" d="M18,11.9c-0.6,0-1-0.4-1-1V8.3C17,4.8,14.4,2,11,2c-0.1,0-0.2,0-0.2,0c-2,0.1-3.8,1.1-4.9,2.8C5.6,5.3,5,5.4,4.5,5.1C4.1,4.8,3.9,4.2,4.2,3.8C5.6,1.4,8.2,0,11,0c0.1,0,0.2,0,0.3,0C15.6,0.2,19,3.8,19,8.3v2.6C19,11.5,18.6,11.9,18,11.9z"/></svg>',
		logout: '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="25px" height="25.5px" viewBox="0 0 25 25.5"><path fill="currentColor" d="M12.5,25.5C5.6,25.5,0,19.9,0,13C0,8.9,2,5,5.4,2.7c0.5-0.3,1.1-0.2,1.4,0.2C7.1,3.4,7,4,6.5,4.3C3.7,6.3,2,9.5,2,13c0,5.8,4.7,10.5,10.5,10.5S23,18.8,23,13c0-3.4-1.7-6.7-4.5-8.6C18,4,17.9,3.4,18.3,2.9c0.3-0.5,0.9-0.6,1.4-0.2C23,5,25,8.9,25,13C25,19.9,19.4,25.5,12.5,25.5z"/><path fill="currentColor" d="M12.5,14c-0.6,0-1-0.4-1-1V1c0-0.6,0.4-1,1-1s1,0.4,1,1v12C13.5,13.6,13.1,14,12.5,14z"/></svg>',
		notification: '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="24.2px" height="26px" viewBox="0 0 24.2 26"><path fill="currentColor" d="M7.2,26c-3.3,0-5.9-1-6.6-2.7c-0.7-1.9-0.1-3,0.4-3.9c0.4-0.7,0.7-1.2,0.4-2.3C-0.4,10.2-1.1,6.5,3.5,2.7C3.9,2.4,3.9,2.3,4,1.9C4,1.5,4.2,0.6,5.4,0.2c1.3-0.5,1.9,0.1,2.3,0.4C7.9,0.8,8,0.9,8.5,0.9c5.9-0.1,7.8,3.2,10.9,9.6v0c0.5,1,1,1.2,1.8,1.5c0.9,0.4,2.1,0.8,2.8,2.7c0.3,0.8,0.2,1.7-0.3,2.7c-1.1,2.3-4.4,5.4-9.4,7.2C11.9,25.6,9.4,26,7.2,26z M6,2.1c0,0,0,0.1,0,0.1C5.9,2.7,5.8,3.5,4.8,4.3C1.4,7,1.4,9.2,3.3,16.6c0.5,1.9-0.1,3-0.6,3.8c-0.4,0.7-0.7,1.2-0.3,2.2c0.5,1.3,5.4,2.3,11.2,0.2c4.4-1.6,7.4-4.3,8.2-6.2c0.2-0.5,0.3-0.9,0.2-1.2c-0.4-1.1-0.9-1.2-1.6-1.5c-0.9-0.3-2-0.8-2.9-2.5c0,0,0,0,0,0c-3.3-6.9-4.7-8.6-9-8.5c-1.3,0-1.9-0.5-2.2-0.8c0,0-0.1-0.1-0.1-0.1c0,0-0.1,0-0.2,0C6,2.1,6,2.1,6,2.1z"/><path fill="currentColor" d="M2.4,20.1c-0.2,0-0.4-0.1-0.6-0.2c-0.4-0.3-0.5-1-0.2-1.4c1.6-2.1,4.5-4.1,8-5.5c4.5-1.8,9.1-2.2,11.6-0.9c0.5,0.2,0.7,0.8,0.5,1.3c-0.2,0.5-0.8,0.7-1.3,0.5c-1.7-0.8-5.4-0.8-10,1c-3.1,1.2-5.8,3-7.2,4.8C3,20,2.7,20.1,2.4,20.1z"/><path fill="currentColor" d="M11.3,20.7c-1.3,0-2.6-0.8-3.2-2c-0.2-0.5,0-1.1,0.5-1.3c0.5-0.2,1.1,0,1.3,0.5c0.3,0.7,1.2,1.1,2,0.7c0.4-0.2,0.6-0.4,0.8-0.8c0.1-0.3,0.1-0.7,0-1.1c-0.2-0.5,0-1.1,0.5-1.3c0.5-0.2,1.1,0,1.3,0.5c0.4,0.8,0.4,1.8,0.1,2.6c-0.3,0.9-1,1.5-1.9,1.9C12.3,20.6,11.8,20.7,11.3,20.7z"/></svg>',
		list: '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="22px" height="12px" viewBox="0 0 22 12"><path fill="currentColor" d="M21,12H1c-0.6,0-1-0.4-1-1s0.4-1,1-1h20c0.6,0,1,0.4,1,1S21.6,12,21,12z"/><path fill="currentColor" d="M21,7H1C0.4,7,0,6.6,0,6s0.4-1,1-1h20c0.6,0,1,0.4,1,1S21.6,7,21,7z"/><path fill="currentColor" d="M21,2H1C0.4,2,0,1.6,0,1s0.4-1,1-1h20c0.6,0,1,0.4,1,1S21.6,2,21,2z"/></svg>',
		close: '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="19.9px" height="19.9px" viewBox="0 0 19.9 19.9"><path fill="currentColor" d="M1,19.9c-0.3,0-0.5-0.1-0.7-0.3c-0.4-0.4-0.4-1,0-1.4L18.2,0.3c0.4-0.4,1-0.4,1.4,0s0.4,1,0,1.4L1.7,19.6C1.5,19.8,1.3,19.9,1,19.9z"/><path fill="currentColor" d="M18.9,19.9c-0.3,0-0.5-0.1-0.7-0.3L0.3,1.7c-0.4-0.4-0.4-1,0-1.4s1-0.4,1.4,0l17.9,17.9c0.4,0.4,0.4,1,0,1.4C19.4,19.8,19.2,19.9,18.9,19.9z"/></svg>',
		help: '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="26px" height="26px" viewBox="0 0 26 26"><circle fill="currentColor" cx="13" cy="20.1" r="1.3"/><path fill="currentColor" d="M14,17.1h-2v-3.2c0-0.6,0.4-1,1-1c1.7,0,3.1-1.3,3.1-3s-1.4-3-3.1-3c-1.7,0-3.1,1.3-3.1,3.3h-2c0-3,2.3-5.3,5.1-5.3c2.9,0,5.1,2.2,5.1,5c0,2.5-1.7,4.5-4.1,4.9V17.1z"/><path fill="currentColor" d="M13,26C5.8,26,0,20.2,0,13S5.8,0,13,0s13,5.8,13,13S20.2,26,13,26z M13,2C6.9,2,2,6.9,2,13s4.9,11,11,11s11-4.9,11-11S19.1,2,13,2z"/></svg>'
	}

	var ready = function () {

		/* ////////////////////////////////////////////////////////////////////////////
		//
		// Header and Context
		//
		/////////////////////////////////////////////////////////////////////////// */

		/*
		// Header Nav
		_____________________________________________ */

		/* Init - Setup mobile Nav Toggler */

		$(o.header).append('<a href="" id="btn-toggle-header-mobile">'+s.burger+'</a>');

		$(o.header).on('click', o.btnMobileNav, function(){
			$(o.header).toggleClass('opened');

			return false;
		});

		/* Init - Show Subnav if parent active and highlight Subnav page if the case */

		$(o.navEl + '.active').has('ul').each(function(){
			var t = $(this);
			t.addClass('opened');
			$('ul', t).show();

			$('ul > li a', t).each(function(){
				var t = $(this);
				if(window.location.href.indexOf(t.attr('href')) !== -1) t.parent().addClass('active');
			});
		});

		/* Toggle Subnav on parent click */

		$(o.navEl).has('ul').on('click', '> span', function(){
			var t = $(this);
			t.parent().toggleClass('opened');
			t.siblings('ul').slideToggle(250);
		});

		/*
		// Context Actions
		_____________________________________________ */

		/* Init - Remove Lightbox Actiuons if it is only Close */

		$('body.entry_relationship.page-index #contents .actions').remove();

		/* Init - Add space to the Context if there are action buttons */

		if($(o.actions).length){
			$(o.context).addClass('spaced-right');
			$(o.contents).addClass('spaced-bottom');
		}

		/* Init - Wrap Inputs and Buttons */

		$('#context > .actions fieldset').wrapInner('<div></div>');

		$('#context > .actions > button').each(function(){
			var t = $(this);
			t.wrap('<li class="button-container close"></li>');
		});

		$('input, button', o.actions).each(function(){
			var t = $(this);
			t.wrap('<div class="button-container" data-icon="'+t.attr('name')+'"></div>');
		});

		/* Init - Remove Version */
		
		$('.actions #version').remove();

		/*
		// Context Tabs
		_____________________________________________ */

		/* Init - Determine how to center content */

		if(!$(o.tabGroup).length && !$(o.secColumn).length) $(o.contents).addClass('centered-content');

		/* Init - TEMP repartition of the divided tabs in the Primary Column */

		if($(o.secTabGroup).length > 1){
			$(o.secTabGroup).parents(o.columns).attr('class', '');

			$(o.secTabGroup).each(function(){
				var t = $(this);
				var classes = t.attr('class').split(' ');

				if($(o.priTabGroup+classes[1]).length) $(o.priTabGroup+classes[1]).append(t.html());
				else $(o.priColumn).append(t);
			});

			$(o.secTabGroup).remove();
		}

		/* Init - Add a title to each Tab */

		$(o.tabGroup).each(function(){
			var t = $(this);
			var title = $(o.contextTabs).eq(t.index()).html();
			t.prepend('<h2>'+title+'</h2>');
		});

		/* Init - Nano Scroll */

		/* Scroll to right TabGroup on TabNav click */

		$(o.contextTabs).on('click', function(){
			var t = $(this);

			$('html, body').stop().animate({scrollTop: ($(o.tabGroup).eq(t.index()).offset().top - 159)}, 750);

			return false;
		});

		/*
		// Context Association Drawer
		_____________________________________________ */

		/* Init - Always Close Drawer */

		if($(o.contextDrawers).hasClass('selected')) $(o.contextDrawers).trigger('click');

		/* Check Drawer state on DrawerButton click */

		$(o.contextDrawers).on('click', function(){
			changeStateDrawer($(this));
		});

		/* ////////////////////////////////////////////////////////////////////////////
		//
		// Contents
		//
		/////////////////////////////////////////////////////////////////////////// */

		/*
		// Dashboard
		_____________________________________________ */

		/* Init - Columns */

		if($('.secondary.column', o.dashboard).length){
			$(o.contentsForm).removeClass('two columns');
			$(o.dashboard).addClass('two columns');
		} 

		/*
		// Table
		_____________________________________________ */

		$(o.tableEl).each(function(){
			var t = $(this);
			var table = t.parents('table');
			if($('thead th', table).eq(t.index()).find('span').length > 0) var text = $('thead th', table).eq(t.index()).find('span').text() + ' : ';
			else var text = $('thead th', table).eq(t.index()).text() + ' : ';
			$(this).attr('data-title', text)
		});

		/*
		// Markdown
		_____________________________________________ */

		/* Trigger fullscreen state on Body */

		$(o.editorEl).on('click', function(){
			var t = $(this).parents('.editor-toolbar');

			setTimeout(function(){
				if(t.hasClass('fullscreen')) $('body').addClass('editor-fullscreen');
				else $('body').removeClass('editor-fullscreen');
			}, 100);
		});

		/*
		// Multilingual Fields
		_____________________________________________ */

		/* Init - Cut all strings to only 2 characters */

		$(o.multiTabsEl).each(function(){
			var t = $(this);
			t.html(t.html().substring(0,2));
		});

		/* Space the Label depending on the Tabs width */

		$(o.multiLabel).each(function(){
			var t = $(this);
			t.css({'padding-right': (t.next('.tabs').width() + 15)});
		});

		/*
		// Select
		_____________________________________________ */

		$('select').each(function(){
			var t = $(this);
			if(t.attr('multiple') === undefined) t.select2();
		});

		$('#context .actions li select[name="panel-type"]').on('change', function(){
			setTimeout(function(){
				$(o.dashboardDrawerSelects).select2();
				$(o.dashboardDrawerSelectsArrows).html(s.chevron);
			}, 250);
		});
		$('#dashboard .panel a.panel-edit').on('click', function(){
			setTimeout(function(){
				$(o.dashboardDrawerSelects).select2();
				$(o.dashboardDrawerSelectsArrows).html(s.chevron);
			}, 250);
		});

		$(o.context).on('click', '.drawer-filtering .constructor', function(){
			setTimeout(function(){
				$('.drawer-filtering .instance:last-child select').select2();
				$('.drawer-filtering .instance:last-child .select2-container .select2-selection--single .select2-selection__arrow').html(s.chevron);
			}, 250);
		});

		$('body').on('click', 'fieldset.apply button', function(){
			var t = $(this).parents('.apply');

			setTimeout(function(){
				var f = t.parents('.frame').find('ol .instance:last-child');

				if(t.parents('.frame').hasClass('collapsible')) $('.frame-header h4', f).append(s.chevron);

				$('select', f).select2();
				$('.select2-container .select2-selection--single .select2-selection__arrow', f).html(s.chevron);
			}, 250);
		});

		/*
		// SVGs
		_____________________________________________ */

		/* Init - Add SVGs */

		$('#breadcrumbs nav p a').prepend(s.arrow);
		$(o.contextActions).each(function(){
			var t = $(this);
			var title = t.attr('title');
			var url = t.attr('href');

			// By URL
			if(url.indexOf('/edit/') !== -1) t.prepend(s.edit);
			else if(url.indexOf('/new/') !== -1) t.prepend(s.add);
			else if(url.indexOf('/random_entries_generator/') !== -1) t.prepend(s.random);
			else if(url.indexOf('-associations') !== -1) t.prepend(s.associations);
			else if(url.indexOf('#drawer-filtering') !== -1) t.prepend(s.filter);
			else if(url.indexOf('#drawer-documenter') !== -1) t.prepend(s.help);
			else if(url.indexOf('?list') !== -1) t.prepend(s.list);
			// By Class
			else if(t.hasClass('link-preview')) t.prepend(s.view);
			// By Title
			else if(title !== undefined) {
				if(title.indexOf('View') !== -1) t.prepend(s.view);
			}
		});
		$(o.contextActionsButt).prepend(s.close);
		$(o.actionButtons).each(function(){
			var t = $(this);
			var title = t.attr('data-icon');

			if(title !== undefined){
				if(title.indexOf('save-duplicate') !== -1) t.prepend(s.clone);
				else if(title.indexOf('save') !== -1) t.prepend(s.save);
				else if(title.indexOf('delete') !== -1) t.prepend(s.delete);
				else if(title.indexOf('clone') !== -1) t.prepend(s.clone);
			}
		});
		$(o.navElFirst).append(s.chevron);
		if($(o.actionButtons).length > 1) $(o.actions).append(s.chevron);
		$(o.selectArrows).html(s.chevron);
		$('.frame.collapsible .frame-header h4').append(s.chevron);

		/* ////////////////////////////////////////////////////////////////////////////
		//
		// Init
		//
		/////////////////////////////////////////////////////////////////////////// */

		/*
		// Website
		_____________________________________________ */

		onScroll();

		win.on('scroll', function(){
			onScroll();
		}).on('resize', function(){
			onScroll();
		});

		/* ////////////////////////////////////////////////////////////////////////////
		//
		// Functions
		//
		/////////////////////////////////////////////////////////////////////////// */

		/* Header Drawers - Function */

		function changeStateDrawer(t){
			var t = t;
			var d = $(t.attr('href'));

			if(t.hasClass('selected')) d.addClass('opened');
			else d.removeClass('opened');
		}

		/* Window Scroll - Function */

		function onScroll(){
			var pageEnd = $(document).height() - win.height();
			var curScroll = win.scrollTop();

			if(curScroll == 0){
				$(o.contextTabs).removeClass('selected');
				if(win.width() < 1280) $(o.contextTabs + ':first-child').addClass('selected');
				else $(o.contextTabs + ':nth-child(2)').addClass('selected');
			} else if(curScroll == pageEnd){
				$(o.contextTabs).removeClass('selected');
				$(o.contextTabs + ':last-child').addClass('selected');
			} else {
				$(o.tabGroup).each(function(){
					var t = $(this);

					if(curScroll + (win.height() / 2) > t.offset().top) {
						$(o.contextTabs).removeClass('selected');
						$(o.contextTabs).eq(t.index()).addClass('selected');
					}
				});
			}
		}

		/* Browser Detector */

		var browserDetector = (function () {
			var getUserAgent = function (userAgent) {
				if (!userAgent) {
					return window.navigator.userAgent;
				}
				return userAgent;
			};
			
			var testUserAgent = function (regexp, userAgent) {
				userAgent = getUserAgent(userAgent);
				return regexp.test(userAgent);
			};
			
			var detector = {
			
				isTablet: function (userAgent) {
					return detector.isMobile(userAgent) &&
						!detector.isPhone(userAgent);
				},
				
				/* @deprecated */
				isTablette: function (userAgent) {
					return this.isTablet(userAgent);
				},
				
				isIos: function (userAgent) {
					return detector.isIphone(userAgent) ||
						detector.isIpad(userAgent);
				},
				
				isIphone: function (userAgent) {
					return !detector.isIpad(userAgent) &&
						(testUserAgent(/iPhone/i, userAgent) || testUserAgent(/iPod/i, userAgent));
				},
				
				isIpad: function (userAgent) {
					return testUserAgent(/iPad/i, userAgent);
				},
				
				isAndroid: function (userAgent) {
					return testUserAgent(/Android/i, userAgent);
				},
				
				isAndroidPhone: function (userAgent) {
					return detector.isAndroid(userAgent) &&
						testUserAgent(/mobile/i, userAgent);
				},
				
				isPhone: function (userAgent) {
					return !detector.isIpad(userAgent) && (
						detector.isOtherPhone(userAgent) ||
						detector.isAndroidPhone(userAgent) ||
						detector.isIphone(userAgent));
				},
				
				isOtherPhone: function (userAgent) {
					return testUserAgent(/phone/i, userAgent);
				},
				
				isOtherMobile: function (userAgent) {
					return testUserAgent(/mobile/i, userAgent) ||
						detector.isOtherPhone(userAgent);
				},
				
				isMobile: function (userAgent) {
					return detector.isIos(userAgent) ||
						detector.isAndroid(userAgent) ||
						detector.isOtherMobile(userAgent);
				},
				
				isMsie: function (userAgent) {
					return testUserAgent(/msie/mi, userAgent) ||
						testUserAgent(/trident/mi, userAgent);
				}
			};
			
			return detector;
		})();
		
		$.iphone = browserDetector.isIphone();
		$.ipad = browserDetector.isIpad();
		$.ios = browserDetector.isIos();
		$.mobile = browserDetector.isMobile();
		$.android = browserDetector.isAndroid();
		$.phone = browserDetector.isPhone();
		$.tablet = browserDetector.isTablet();
		$.touch = $.ios || $.android;

		if($.touch) $('html').addClass('touch');
	};
	
	$(ready);
	
})(jQuery);
