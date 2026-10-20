<?php
namespace rocket_media_library_mime_type;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // 직접 접근 차단
}

/**
 * features 폴더 안에 구현할 파일은 반드시 이 클래스를 상속받아야 함
 */
class Feature {

	/**
	 * 이 클래스를 상속받은 자식 클래스들은
	 * add_action( 'rocket_media_library_mime_type_init', array( '클래스명', 'init' ), 1, 1 ); 으로 호출한다
	 */
	public static function init() {
		if ( function_exists( 'get_called_class' ) ) {
			$current_class = get_called_class();
			return new $current_class();
		}
		return null;
	}

	public function get_template() {
		return new Template();
	}

	public function get_option( $name, $default = null ) {
		return PluginOptions::get_option( $name, $default );
	}

	public function get_current_all_options() {
		return PluginOptions::get_current_all_options();
	}

	public function set_option( $name, $value ) {
		return PluginOptions::update_option( $name, $value );
	}

	public function marshal( $method_name ) {
		return array( &$this, $method_name );
	}

	/**
	 * 클래스에서 add_action 명령어를 사용할 때 클래스명을 일일히 쓸 필요없이 $this 를 넣으면 됨
	 *
	 * @param string $action
	 * @param string $method_name
	 * @param int    $priority
	 * @param int    $accepted_args
	 */
	public function add_action( $action, $method_name, $priority = 10, $accepted_args = 1 ) {
		add_action( $action, $this->marshal( $method_name ), $priority, $accepted_args );
	}

	public function add_filter( $filter, $method_name, $priority = 10, $accepted_args = 1 ) {
		add_filter( $filter, $this->marshal( $method_name ), $priority, $accepted_args );
	}
} // End Class
