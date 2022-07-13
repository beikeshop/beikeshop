/*
 * CKFinder - Sample Plugins
 * ==========================
 * http://cksource.com/ckfinder
 * Copyright (C) 2007-2015, CKSource - Frederico Knabben. All rights reserved.
 *
 * This file and its contents are subject to the MIT License.
 * Please read the LICENSE.md file before using, installing, copying,
 * modifying or distribute this file or part of its contents.
 */

CKFinder.define( [ 'underscore', 'backbone', 'marionette', 'doT' ], function( _, Backbone, Marionette, doT ) {
	'use strict';

	/**
	 * This plugin illustrates how to show, style and add information to the Status Bar.
	 */
	return {
		init: function( finder ) {
			// A basic model that stores the message which will be displayed in the status bar.
			var messageModel = new Backbone.Model( { message: '' } );

			// A view that will be displayed inside the status bar.
			var statusBarView = new Marionette.ItemView( {
				tagName: 'p',
				template: doT.template( '{{= it.message }}' ),
				model: messageModel,
				modelEvents: {
					// This will call the render method when any model attribute will change.
					'change': 'render'
				}
			} );

			// Wait for the 'page:create:Main' event to attach the status bar
			finder.on( 'page:create:Main', function() {
				// Create a status bar named 'MyStatusBar' for the 'Main' page which contains the files pane.
				finder.request( 'statusBar:create', {
					name: 'MyStatusBar',
					page: 'Main',
					label: 'My Status Bar'
				} );

				// Add a region inside the 'MyStatusBar' status bar. By default the status bar is empty.
				finder.request( 'statusBar:addRegion', {
					id: 'my-status-bar-region',
					name: 'MyStatusBar'
				} );

				//  Pass a view instance to the status bar. This will add a view to the regions layout manager.
				finder.request( 'statusBar:showView', {
					region: 'my-status-bar-region',
					name: 'MyStatusBar',
					view: statusBarView
				} );

				// Listen to the 'files:selected' event which is triggered when file selection changes.
				finder.on( 'files:selected', function( evt ) {
					var selectedFiles = evt.data.files;

					if ( !selectedFiles.length ) {
						// There are no selected files so display information about folder contents.
						// Get current folder.
						var folder = evt.finder.request( 'folder:getActive' );
						// Get all files in the current folder.
						var filesCount = evt.finder.request( 'files:getCurrent' ).length;
						// Display information about the current folder and the number of files.
						messageModel.set( 'message', 'Folder "' + finder.util.escapeHtml( folder.get( 'name' ) ) + '" contains ' + filesCount + ' file(s)' );
					} else if ( selectedFiles.length === 1 ) {
						// There is only one file selected so get the first file and show its name.
						messageModel.set( 'message', 'Selected: ' + finder.util.escapeHtml( selectedFiles.at( 0 ).get( 'name' ) ) );
					} else {
						// There are many files selected so display the number of selected files.
						messageModel.set( 'message', 'Selected ' + selectedFiles.length + ' files' );
					}
				} );

				finder.on( 'folder:getFiles:after', function( evt ) {
					// Get all files in the current folder.
					var filesCount = evt.finder.request( 'files:getCurrent' ).length;

					// Display information about the current folder and the number of files.
					messageModel.set( 'message', 'Folder "' + finder.util.escapeHtml( evt.data.folder.get( 'name' ) ) + '" contains ' + filesCount + ' file(s)' );
				} );
			} );

			// Set some nicer styles for the status bar content.
			this.addCss( '#my-status-bar-region {padding: 0 1em;font-size:0.8em;font-weight:normal}' );
		}
	};
} );
