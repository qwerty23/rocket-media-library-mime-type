<?php
/**
 * Plugin Name:       Rocket Media Library Mime Type
 * Plugin URI:        http://in-web.co.kr/wordpress/plug-in/wordpress-%ec%97%85%eb%a1%9c%eb%93%9c-%ed%99%95%ec%9e%a5%ec%9e%90-%ec%b6%94%ea%b0%80-rocket-media-library-mime-type/
 * Description:       한국 유저를 위한 업로드 파일타입 추가 플러그인. hwp 확장자 및 미디어에 업로드하길 원하는 파일 타입을 추가할 수 있습니다. (Choose which file types can be uploaded to the media library.)
 * Version:           3.0.1
 * Requires at least: 5.0
 * Requires PHP:      7.0
 * Author:            Qwerty23
 * Author URI:        http://in-web.co.kr/
 * License:           GPLv2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       rocket-media-library-mime-type
 */

namespace rocket_media_library_mime_type;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // 직접 접근 차단
}

define( 'rocket_media_library_mime_type\VERSION', '3.0.1' );
define( 'rocket_media_library_mime_type\PLUGIN_FILE', __FILE__ );
define( 'rocket_media_library_mime_type\PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'rocket_media_library_mime_type\PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'rocket_media_library_mime_type\PLUGIN_PREFIX', 'rocket-media-library-mime-type' );
define( 'rocket_media_library_mime_type\PLUGIN_MENU_SLUG', 'rocket-media-library-mime-type-setting' );

require_once PLUGIN_DIR . 'features/feature.php';
require_once PLUGIN_DIR . 'features/template.php';
require_once PLUGIN_DIR . 'features/options.php';
require_once PLUGIN_DIR . 'features/core/mime-type.php'; // 활성화할 때도 기본값에 모든 파일 형식이 들어 있도록 미리 불러온다
require_once PLUGIN_DIR . 'features/class-loader.php';
require_once PLUGIN_DIR . 'features/plugin-set.php';

class RocketMediaLibraryMimeType extends Plugin_Set {

	private static $__instance;

	public static function init() {
		if ( ! self::$__instance ) {
			PluginOptions::upgrade_stored_options();
			self::$__instance = new RocketMediaLibraryMimeType();
			parent::initialize();
		}
		return self::$__instance;
	}

	public function __construct() {
		parent::__construct();
	}

	/**
	 * 플러그인 옵션 기본값 정의
	 *
	 * @static
	 * @return array 플러그인 옵션 기본값
	 */
	public static function defaults() {

		$defaults_value = array(
			'change_filename_kr_to_en'        => '',
			'change_filename_add_upload_date' => '',
			'change_filename_nfc'             => 'on',
			'restrict_risky_to_admin'         => 'on',
		);

		if ( class_exists( 'rocket_media_library_mime_type\MimeType' ) ) {

			foreach ( MimeType::get_mime_types() as $target => $target_info ) {
				$defaults_value[ 'rocket_upload_mime_type_' . $target ] = $target_info['default_value'];
			}
		}

		return $defaults_value;
	}

	/**
	 * Plugin activation hook
	 *
	 * 플러그인 활성화시 실행
	 *
	 * @static
	 */
	public static function activate_plugin() {
	}

	/**
	 * Plugin deactivation hook
	 *
	 * 플러그인 비활성화시 실행
	 *
	 * @static
	 */
	public static function deactivate_plugin() {
	}

} // End Class

add_action( 'plugins_loaded', array( 'rocket_media_library_mime_type\RocketMediaLibraryMimeType', 'init' ) );
register_activation_hook( __FILE__, array( 'rocket_media_library_mime_type\RocketMediaLibraryMimeType', '_activate_plugin' ) );
register_deactivation_hook( __FILE__, array( 'rocket_media_library_mime_type\RocketMediaLibraryMimeType', 'deactivate_plugin' ) );
