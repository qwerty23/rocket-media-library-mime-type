<?php
/**
 * 플러그인을 삭제할 때 저장된 설정을 지운다
 */
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

$rocket_mlmt_key = 'rocket_media_library_mime_type';

if ( is_multisite() ) {
	foreach ( get_sites( array( 'fields' => 'ids', 'number' => 0 ) ) as $rocket_mlmt_site_id ) {
		delete_blog_option( $rocket_mlmt_site_id, $rocket_mlmt_key );
	}
}

delete_option( $rocket_mlmt_key );
