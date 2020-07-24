<?php
/*
 *	Plugin Name: 	Rocket Media Library Mime Type
 *  Plugin URI:  	http://rocketpress.kr/wp-plugin/5760/
 *	Description:    한국 유저를 위한 업로드 파일타입 추가 플러그인. hwp 확장자 및 미디어에 업로드하길 원하는 파일 타입을 추가할 수 있습니다.  
 *	Author:         <a href="http://in-web.co.kr/">Qwerty23</a> & <a href="http://rocketpress.kr/">RocketPress</a>
 *	Version: 		2.0.1
 *	Text Domain: 	RocketMLMT
 *	Domain Path: 	languages/
 */

namespace rocket_media_library_mime_type;

define( 'rocket_media_library_mime_type\VERSION', '2.0.1' );
define( 'rocket_media_library_mime_type\PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'rocket_media_library_mime_type\PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'rocket_media_library_mime_type\PLUGIN_PREFIX'	, 'RocketMLMT');
define( 'rocket_media_library_mime_type\PLUGIN_MENU_SLUG'	, 'rocket-media-library-mime-type-setting');

require_once( 'features/feature.php' );
require_once( 'features/template.php' );
require_once( 'features/options.php' );
require_once( 'features/class-loader.php' );
require_once( 'features/plugin-set.php' );

class RocketMediaLibraryMimeType extends Plugin_Set {

	private static $__instance;

	public static function init() {
		if ( !self::$__instance ) {
			load_plugin_textdomain( PLUGIN_PREFIX, FALSE, PLUGIN_DIR . 'languages' );
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
			"change_filename_kr_to_en"			=> "",
			"change_filename_use_naver_api"		=> "",
			"naver_client_id"					=> "",
			"naver_client_secret"				=> "",
			"change_filename_add_upload_date"	=> "on",
		);
		
		if( class_exists('rocket_media_library_mime_type\MimeType' ) ){
				
			$targets = MimeType::get_mime_types();
			$create_value_names = array("rocket_upload_mime_type_");
			
			$create_values = array();
			foreach($create_value_names as $value_name):
				
				foreach($targets as $target => $target_info):
					
					$create_values[$value_name . $target] = $target_info["default_value"];
					
				endforeach;
				
			endforeach;
			
			$defaults_value = array_merge($defaults_value, $create_values);
		}
		
		
		return $defaults_value;
	}

	/**
	 * Plugin activation hook
	 *
	 * 플러그인 활성화시 실행
	 *
	 * @static
	 * @hook register_activation_hook
	 */
	public static function activate_plugin() {
		
	}
	
	/**
	 * Plugin deactivation hook
	 *
	 * 플러그인 비활성화시 실행
	 *
	 * @static
	 * @hook register_deactivation_hook
	 */
	public static function deactivate_plugin() {
		$dismissed_pointers = explode( ',', get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true ) );

		if( in_array( 'rocket_media_upload_file_settings_pointer', $dismissed_pointers ) ) {
			unset($dismissed_pointers[array_search("rocket_media_upload_file_settings_pointer",$dismissed_pointers)]);
			update_user_meta(get_current_user_id(),'dismissed_wp_pointers',$dismissed_pointers);
		}
	}

} // End Class

add_action( 'plugins_loaded', array( 'rocket_media_library_mime_type\RocketMediaLibraryMimeType', 'init' ) );
register_activation_hook( __FILE__, array( 'rocket_media_library_mime_type\RocketMediaLibraryMimeType', '_activate_plugin' ) );
register_deactivation_hook( __FILE__, array( 'rocket_media_library_mime_type\RocketMediaLibraryMimeType', 'deactivate_plugin' ) );