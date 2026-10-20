/**
 * Rocket Media Library Mime Type : 설정 화면
 * 예전 버전의 화면 동작(Foundation 초기화, 물음표 툴팁)과, 저장하지 않은 변경 사항 알림
 */
jQuery( document ).ready( function ( $ ) {
	'use strict';

	var dirty = false;
	var submitting = false;

	if ( $.fn.foundation ) {
		$( document ).foundation();
	}

	if ( $.fn.powerTip ) {
		$( '.tooltips' ).powerTip( {
			placement: 'ne',
			mouseOnToPopup: true,
			smartPlacement: true
		} );
	}

	var $form = $( '#rocket-mlmt-form' );

	$form.on( 'change', function () {
		dirty = true;
		$( '.rocket-mlmt-dirty' ).prop( 'hidden', false );
	} );

	$form.on( 'submit', function () {
		submitting = true;
	} );

	$( window ).on( 'beforeunload', function ( event ) {
		if ( dirty && ! submitting ) {
			event.preventDefault();
			return '';
		}
	} );
} );
