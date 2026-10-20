<?php
namespace rocket_media_library_mime_type;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // 직접 접근 차단
}

class PluginOptions {

	/**
	 * @var string 플러그인 옵션 키값
	 */
	public static $option_key = 'rocket_media_library_mime_type';

	/**
	 * 체크박스처럼 켜고 끄는 항목 ( 값이 "on" 이면 켜짐 )
	 */
	private static $switch_options = array(
		'change_filename_kr_to_en',
		'change_filename_add_upload_date',
		'change_filename_nfc',
		'restrict_risky_to_admin',
	);

	public static function upgrade_plugin_options( $defaults = null ) {
		$options = get_option( self::$option_key );

		if ( false === $options ) {
			$options = $defaults;
		} else {
			$options = shortcode_atts( $defaults, $options );
		}
		return update_option( self::$option_key, $options );
	}

	public static function get_option( $name, $default ) {
		$options = get_option( self::$option_key );
		if ( is_array( $options ) && array_key_exists( $name, $options ) ) {
			return $options[ $name ];
		}
		return $default;
	}

	public static function get_current_all_options() {
		$saved = get_option( self::$option_key );

		return shortcode_atts( RocketMediaLibraryMimeType::defaults(), is_array( $saved ) ? $saved : array() );
	}

	/**
	 * 예전 버전(2.x)에서 저장한 설정을 3.0.0 방식으로 한 번만 고친다
	 *
	 *  - 더 이상 쓰지 않는 네이버 API 키 항목을 지운다
	 *  - "업로드 일시 붙이기"는 예전에는 한글 파일명을 변환할 때만 적용되었다.
	 *    3.0.0 부터는 모든 파일에 적용되는 별개의 옵션이므로, 한글 변환을 쓰지 않던 사이트의 모든 파일명이
	 *    갑자기 바뀌지 않도록 이 경우에는 꺼 둔다.
	 */
	public static function upgrade_stored_options() {

		if ( '3.0.0' === get_option( self::$option_key . '_db' ) ) {
			return;
		}

		$options = get_option( self::$option_key );

		if ( is_array( $options ) ) {

			unset( $options['naver_client_id'], $options['naver_client_secret'], $options['change_filename_use_naver_api'] );

			if ( ! isset( $options['change_filename_kr_to_en'] ) || 'on' !== $options['change_filename_kr_to_en'] ) {
				$options['change_filename_add_upload_date'] = '';
			}

			update_option( self::$option_key, $options );
		}

		update_option( self::$option_key . '_db', '3.0.0' );
	}

	/**
	 * 설정 화면에서 보낸 값을 검증해서 저장할 옵션 배열로 만든다
	 *
	 * - 정해진 항목만 받는다 (그 외의 값은 무시)
	 * - 체크하지 않은 항목은 폼에서 전달되지 않으므로 "꺼짐"으로 저장한다
	 *
	 * @param array $post 사용자가 보낸 폼값 (wp_unslash 처리된 값)
	 * @return array
	 */
	public static function sanitize( $post ) {

		$post     = is_array( $post ) ? $post : array();
		$defaults = RocketMediaLibraryMimeType::defaults();
		$clean    = array();

		foreach ( $defaults as $name => $default ) {

			$value = isset( $post[ $name ] ) ? $post[ $name ] : '';

			if ( in_array( $name, self::$switch_options, true ) ) {

				$clean[ $name ] = ( 'on' === $value ) ? 'on' : '';

			} else {

				// rocket_upload_mime_type_xxx : 허용 ("yes") 또는 허용 안 함
				$clean[ $name ] = ( 'yes' === $value ) ? 'yes' : '';
			}
		}

		return $clean;
	}

	/**
	 * 설정 화면에서 보낸 값을 저장
	 *
	 * @param array $post
	 * @return bool
	 */
	public static function save_from_post( $post ) {
		return update_option( self::$option_key, self::sanitize( $post ) );
	}

	public static function update_option( $name, $value ) {
		$options = get_option( self::$option_key );
		if ( ! is_array( $options ) ) {
			$options = array();
		}
		$options[ $name ] = $value;

		return update_option( self::$option_key, $options );
	}
} // END CLASS
