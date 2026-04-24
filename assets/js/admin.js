/**
 * Enhanced – Admin settings page JS.
 */
jQuery( function ( $ ) {
	'use strict';

	/* ── Colour picker ───────────────────────────────────────── */
	$( '.en-color-picker' ).wpColorPicker();

	/* ── Generic single-image fields (hero, banners, etc.) ───── */
	$( document ).on( 'click', '.en-upload-btn', function ( e ) {
		e.preventDefault();
		var $wrap  = $( this ).closest( '.en-img-field' );
		var frame  = wp.media( { title: 'Select Image', multiple: false, library: { type: 'image' } } );
		frame.on( 'select', function () {
			var att = frame.state().get( 'selection' ).first().toJSON();
			$wrap.find( '.en-img-input' ).val( att.id );
			$wrap.find( '.en-img-preview' ).html( '<img src="' + att.url + '" alt="">' ).removeClass( 'en-img-preview--empty' );
			$wrap.find( '.en-remove-btn' ).show();
		} );
		frame.open();
	} );

	$( document ).on( 'click', '.en-remove-btn', function ( e ) {
		e.preventDefault();
		var $wrap = $( this ).closest( '.en-img-field' );
		$wrap.find( '.en-img-input' ).val( '' );
		$wrap.find( '.en-img-preview' ).empty().addClass( 'en-img-preview--empty' );
		$( this ).hide();
	} );

	/* ── Right-panel: build thumb tile HTML ──────────────────── */
	function makeTile( id, url ) {
		return '<div class="en-rp-thumb" data-id="' + id + '">' +
			'<img src="' + url + '" alt="">' +
			'<button type="button" class="en-rp-thumb-del" title="Remove">&#215;</button>' +
			'</div>';
	}

	function syncIds( $panel ) {
		var ids = [];
		$panel.find( '.en-rp-thumb' ).each( function () {
			ids.push( $( this ).data( 'id' ) );
		} );
		$panel.find( '.en-rp-ids-input' ).val( ids.join( ',' ) );
	}

	function showEmpty( $thumbs ) {
		$thumbs.html(
			'<div class="en-rp-empty">' +
			'<svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#ccc" stroke-width="1.2">' +
			'<rect x="3" y="3" width="18" height="18" rx="2"/>' +
			'<circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>' +
			'<p>No images yet. Click &ldquo;+ Upload Images&rdquo; to add 1&ndash;3 slides.</p>' +
			'</div>'
		);
	}

	/* ── "+ Upload Images" — opens multi-select media picker ─── */
	$( document ).on( 'click', '.en-rp-upload-btn', function ( e ) {
		e.preventDefault();
		var panel   = $( this ).data( 'panel' );
		var $panel  = $( this ).closest( '.en-rp-panel' );
		var $thumbs = $panel.find( '.en-rp-thumbs' );

		var frame = wp.media( {
			title:    'Select 1–3 images',
			button:   { text: 'Use selected images' },
			multiple: 'add',
			library:  { type: 'image' },
		} );

		frame.on( 'select', function () {
			var atts = frame.state().get( 'selection' ).toJSON();
			$thumbs.empty();
			atts.forEach( function ( att ) {
				$thumbs.append( makeTile( att.id, att.url ) );
			} );
			syncIds( $panel );
		} );

		frame.open();
	} );

	/* ── Remove individual thumb ─────────────────────────────── */
	$( document ).on( 'click', '.en-rp-thumb-del', function () {
		var $panel  = $( this ).closest( '.en-rp-panel' );
		var $thumbs = $panel.find( '.en-rp-thumbs' );
		$( this ).closest( '.en-rp-thumb' ).remove();
		syncIds( $panel );
		if ( ! $thumbs.find( '.en-rp-thumb' ).length ) {
			showEmpty( $thumbs );
		}
	} );

	/* ── Opacity range live value ────────────────────────────── */
	$( document ).on( 'input', '.en-rp-range', function () {
		$( this ).closest( '.en-rp-shared__field' ).find( '.en-rp-val' ).text( $( this ).val() );
	} );
} );
