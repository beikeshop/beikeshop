/**
 * @license Copyright (c) 2003-2015, CKSource - Frederico Knabben. All rights reserved.
 * Licensed under the terms of the MIT license. See LICENSE.md for more information.
 */

/* global SF */

'use strict';

( function() {
	// All .tree-a elements in DOM.
	var trees = SF.getByClass( 'tree-a' );

	for ( var i = trees.length; i--; ) {
		var tree = trees[ i ];

		SF.attachListener( tree, 'click', function( evt ) {
			var target = evt.target || evt.srcElement;

			// Collapse or expand item groups.
			if ( target.nodeName === 'H2' && !SF.classList.contains( target, 'tree-a-no-sub' ) ) {
				SF.classList.toggle( target, 'tree-a-active' );
			}
		} );
	}
} )();