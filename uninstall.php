<?php
/**
 * 플러그인을 삭제할 때 저장된 설정을 지운다
 */
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

/**
 * 함수 안에서 실행해서 전역 변수를 만들지 않는다
 */
function rocket_media_library_mime_type_uninstall() {

	$option_key = 'rocket_media_library_mime_type';

	if ( is_multisite() ) {
		foreach ( get_sites( array( 'fields' => 'ids', 'number' => 0 ) ) as $site_id ) {
			delete_blog_option( $site_id, $option_key );
			delete_blog_option( $site_id, $option_key . '_db' );
		}
	}

	delete_option( $option_key );
	delete_option( $option_key . '_db' );
}

rocket_media_library_mime_type_uninstall();
