/*
 Copyright (c) 2007-2019, CKSource - Frederico Knabben. All rights reserved.
 For licensing, see LICENSE.html or https://ckeditor.com/sales/license/ckfinder
 */
'use strict';

CKFinder.define( {
	config: function( config ) {
		// Override swatch so it always use 'a' swatch.
		config.swatch = 'a';

		// Override dialog & loader overlay swatch.
		config.dialogOverlaySwatch = true;
		config.loaderOverlaySwatch = true;

		config.thumbnailClasses = {
			180: 'xs',
			250: 'sm',
			400: 'md',
			450: 'lg',
			1000: 'xl'
		};

		config.customPreviewImageIcon = 'image-preview.png';

		config.fileIconsPath = 'skins/neko/file-icons/';
		config.fileIconsSizes = '256,128,64,48,32,22,16';
		config.compactViewIconSize = 48;

		// Use New Moono theme.
		/* istanbul ignore else: does not occurs in tests */
		if ( !config.themeCSS ) {
			config.themeCSS = 'skins/neko/ckfinder.css';
		}

		// Use Moono icons.
		/* istanbul ignore else: does not occurs in tests */
		if ( !config.iconsCSS ) {
			config.iconsCSS = 'skins/neko/icons.css';
		}

		return config;
	},

	init: function() {
		CKFinder.require( [ 'jquery' ], function( jQuery ) {
			// Enforce black iconset.
			jQuery( 'body' ).addClass( 'ui-alt-icon' );
		} );
	}
} );
