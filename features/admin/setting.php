<?php

namespace rocket_media_library_mime_type;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // 직접 접근 차단
}

/**
 * 관리자 설정 화면 : 설정 > 업로드 파일 타입
 */
class Setting extends Feature {

	const NONCE_ACTION = 'rocket_mlmt_save';
	const NONCE_NAME   = 'rocket_mlmt_nonce';

	private $page_hook = '';

	public function __construct() {
		$this->add_action( 'admin_menu', 'add_menu' );
		$this->add_action( 'admin_init', 'handle_save' );
		$this->add_action( 'admin_enqueue_scripts', 'enqueue' );
	}

	/**
	 * 관리 메뉴 추가
	 */
	public function add_menu() {
		$this->page_hook = add_options_page(
			'Rocket Media Library Mime Type',
			__( '업로드 파일 타입', PLUGIN_PREFIX ),
			'manage_options',
			PLUGIN_MENU_SLUG,
			array( $this, 'setting_page' )
		);
	}

	/**
	 * 설정 저장
	 *
	 * 로그인하지 않은 사용자, 권한이 없는 사용자, nonce 가 맞지 않는 요청은 저장하지 않는다.
	 */
	public function handle_save() {

		if ( ! isset( $_POST['rocket_mlmt_action'], $_GET['page'] ) ) {
			return;
		}

		if ( 'update' !== $_POST['rocket_mlmt_action'] || PLUGIN_MENU_SLUG !== sanitize_text_field( wp_unslash( $_GET['page'] ) ) ) {
			return;
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( '이 설정을 변경할 권한이 없습니다.', PLUGIN_PREFIX ), '', array( 'response' => 403 ) );
		}

		check_admin_referer( self::NONCE_ACTION, self::NONCE_NAME );

		PluginOptions::save_from_post( wp_unslash( $_POST ) );

		wp_safe_redirect(
			add_query_arg(
				array(
					'page'    => PLUGIN_MENU_SLUG,
					'updated' => '1',
				),
				admin_url( 'options-general.php' )
			)
		);
		exit;
	}

	/**
	 * 관리 페이지 랜더링
	 */
	public function setting_page() {

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( '이 페이지에 접근할 권한이 없습니다.', PLUGIN_PREFIX ), '', array( 'response' => 403 ) );
		}

		$template = self::get_template();
		$template->set( 'post_max_size', size_format( $this->format_size( ini_get( 'post_max_size' ) ) ) );
		$template->set( 'version', VERSION );
		$template->set( 'options', self::get_current_all_options() );
		$template->set( 'rocket_plugin_mime_types', MimeType::get_mime_types() );
		$template->set( 'updated', isset( $_GET['updated'] ) && '1' === $_GET['updated'] );
		$template->set( 'nonce_action', self::NONCE_ACTION );
		$template->set( 'nonce_name', self::NONCE_NAME );
		$template->set( 'form_action', admin_url( 'options-general.php?page=' . PLUGIN_MENU_SLUG ) );

		// 템플릿 안에서 모든 값을 이스케이프해서 출력한다
		echo $template->apply( 'admin/setting.php' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * 파일 용량 계산 : 단순 정보 표시용
	 *
	 * @param string $filesize 예) 8M
	 * @return int
	 */
	private function format_size( $filesize ) {

		$unit = strtoupper( substr( (string) $filesize, -1 ) );
		$ret  = (float) $filesize;

		switch ( $unit ) {
			case 'P':
				$ret *= 1024;
				// no break
			case 'T':
				$ret *= 1024;
				// no break
			case 'G':
				$ret *= 1024;
				// no break
			case 'M':
				$ret *= 1024;
				// no break
			case 'K':
				$ret *= 1024;
		}

		return (int) $ret;
	}

	/**
	 * 설정 화면에서만 CSS, JS 를 불러온다
	 *
	 * @param string $hook 현재 관리자 화면
	 */
	public function enqueue( $hook ) {

		if ( $hook !== $this->page_hook ) {
			return;
		}

		// 플러그인에 포함된 라이브러리만 사용한다 (외부 사이트에서 불러오지 않음) : 자세한 내용은 assets/vendor/LICENSES.txt
		wp_enqueue_style( 'rocket-mlmt-fontawesome', PLUGIN_URL . 'assets/vendor/fontawesome/css/font-awesome.min.css', array(), '4.5.0' );
		wp_enqueue_style( 'rocket-mlmt-libs', PLUGIN_URL . 'assets/vendor/rocket-libs/rocket-libs.min.css', array( 'rocket-mlmt-fontawesome' ), '5.5.1' );
		wp_enqueue_style( 'rocket-mlmt-admin', PLUGIN_URL . 'assets/css/admin-setting.css', array( 'rocket-mlmt-libs' ), VERSION );

		wp_enqueue_script( 'rocket-mlmt-libs', PLUGIN_URL . 'assets/vendor/rocket-libs/rocket-libs.min.js', array( 'jquery' ), '5.5.1', true );
		wp_enqueue_script( 'rocket-mlmt-admin', PLUGIN_URL . 'assets/js/setting.js', array( 'jquery', 'rocket-mlmt-libs' ), VERSION, true );
	}
}
add_action( 'rocket_media_library_mime_type_init', array( 'rocket_media_library_mime_type\Setting', 'init' ), 1, 1 );
