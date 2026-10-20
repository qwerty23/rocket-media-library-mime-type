<?php
namespace rocket_media_library_mime_type;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // 직접 접근 차단
}

/**
 * 파일 업로드와 관련된 처리
 *
 *  - 업로드를 허용할 파일 확장자 설정
 *  - 미디어 라이브러리 필터에 확장자 추가
 *  - 한글 파일명을 로마자로 변환 (외부 서비스를 사용하지 않는다)
 *
 * 블록 에디터(REST API)로 올리는 파일에도 적용되도록 관리자 화면에서만이 아니라 항상 로드된다.
 */
class Upload extends Feature {

	public function __construct() {
		$this->add_filter( 'upload_mimes', 'custom_add_upload_mimes' );
		$this->add_filter( 'post_mime_types', 'media_filter_allow_mime_types' );
		$this->add_filter( 'sanitize_file_name', 'rocket_sanitize_chars' );
		$this->add_filter( 'wp_check_filetype_and_ext', 'fix_filetype_and_ext', 10, 5 );
	}

	/**
	 * 이 파일 형식을 지금 업로드할 수 있는지
	 *
	 * 설정에서 허용으로 켜 두었더라도, "위험한 형식은 관리자만" 옵션이 켜져 있으면
	 * 주의 문구가 붙은 형식(svg, exe, 매크로 파일 등)은 관리자가 아닌 사용자에게 허용하지 않는다.
	 *
	 * @param string $key     MimeType 목록의 키
	 * @param array  $info    MimeType 목록의 항목
	 * @param array  $options 플러그인 옵션
	 * @return bool
	 */
	private static function is_type_enabled( $key, $info, $options ) {

		if ( 'yes' !== $options[ 'rocket_upload_mime_type_' . $key ] ) {
			return false;
		}

		if ( ! empty( $info['warning'] ) && 'on' === $options['restrict_risky_to_admin'] && ! current_user_can( 'manage_options' ) ) {
			return false;
		}

		return true;
	}

	/**
	 * 워드프레스에서 파일을 업로드 할때, 워드프레스에서 허용하는 파일 확장자를 불러오는데 바로 그 전에 실행되는 펑션
	 *
	 * @param array $wp_mime_types 워드프레스에서 허용하는 파일 확장자 목록
	 * @return array 워드프레스에서 기본 허용 확장자 + 플러그인에서 설정한 확장자 목록
	 */
	public function custom_add_upload_mimes( $wp_mime_types = array() ) {

		$options    = self::get_current_all_options();
		$mime_types = MimeType::get_mime_types();

		foreach ( $mime_types as $mime_type_key => $mime_type_info ) {

			if ( self::is_type_enabled( $mime_type_key, $mime_type_info, $options ) ) {

				if ( ! empty( $wp_mime_types[ $mime_type_info['extension'] ] ) ) {
					continue;
				}
				$wp_mime_types[ $mime_type_info['extension'] ] = $mime_type_info['mime_type'];

			} elseif ( isset( $wp_mime_types[ $mime_type_info['extension'] ] ) ) {

				unset( $wp_mime_types[ $mime_type_info['extension'] ] );
			}
		}

		return $wp_mime_types;
	}

	/**
	 * 워드프레스는 파일 내용으로 알아낸 형식이 확장자와 다르면 업로드를 막는다.
	 * 예) 한글(hwp) 파일은 내용상 application/CDFV2 로 판별되어 hwp 를 허용해도 막힌다.
	 *
	 * 이 플러그인에서 허용으로 켠 확장자이고, 파일 내용으로 알아낸 형식이 그 확장자에서 흔히 나오는
	 * 형식(MimeType 의 real_mimes)일 때만 업로드를 허용한다. 그 외의 경우는 워드프레스 판단을 따른다.
	 *
	 * @param array       $data      ext, type, proper_filename
	 * @param string      $file      파일 경로
	 * @param string      $filename  파일 이름
	 * @param array|null  $mimes     허용 형식
	 * @param string|bool $real_mime 파일 내용으로 알아낸 형식
	 * @return array
	 */
	public function fix_filetype_and_ext( $data, $file = '', $filename = '', $mimes = null, $real_mime = false ) {

		if ( ! is_array( $data ) || ( ! empty( $data['ext'] ) && ! empty( $data['type'] ) ) || ! is_string( $filename ) || ! is_string( $real_mime ) || '' === $real_mime ) {
			return $data;
		}

		$extension = strtolower( pathinfo( $filename, PATHINFO_EXTENSION ) );

		if ( '' === $extension ) {
			return $data;
		}

		$options = self::get_current_all_options();

		foreach ( MimeType::get_mime_types() as $key => $info ) {

			if ( empty( $info['real_mimes'] ) || ! self::is_type_enabled( $key, $info, $options ) ) {
				continue;
			}

			if ( in_array( $extension, explode( '|', $info['extension'] ), true ) && in_array( $real_mime, $info['real_mimes'], true ) ) {
				$data['ext']  = $extension;
				$data['type'] = $info['mime_type'];
				break;
			}
		}

		return $data;
	}

	/**
	 * 워드프레스의 미디어 라이브러리에는 파일 목록을 확장자 별로 볼 수 있는 필터가 있는데,
	 * 그 필터 목록에 이 플러그인으로 추가한 확장자를 추가하는 펑션
	 *
	 * @param array $post_mime_types 미디어 라이브러리의 필터 파일 확장자 목록
	 * @return array 미디어 라이브러리의 필터 파일 확장자 목록  + 플러그인에서 추가한 확장자 명
	 */
	public function media_filter_allow_mime_types( $post_mime_types = array() ) {

		foreach ( $this->custom_add_upload_mimes() as $extension => $mime_type ) {

			$label = implode( ' , ', explode( '|', $extension ) );

			$post_mime_types[ $mime_type ] = array(
				$label,
				/* translators: %s: file extensions */
				sprintf( __( 'Manage %s', PLUGIN_PREFIX ), $label ),
				_n_noop( $label . ' <span class="count">(%s)</span>', $label . ' <span class="count">(%s)</span>' ),
			);
		}

		return $post_mime_types;
	}

	/**
	 * 파일 업로드시 실행되는  워드프레스 필터중 하나 sanitize_file_name 를 사용
	 * 1. 파일명이 한글인지 확인 후
	 * 2. 한글이면 로마자로 바꾼 파일명을 반환 (ex:사과->sagwa)
	 *
	 * @param string $filename 업로드한 파일의 파일 이름
	 * @return string 영문 파일명
	 */
	public function rocket_sanitize_chars( $filename ) {

		$options  = self::get_current_all_options();
		$to_roman = ( 'on' === $options['change_filename_kr_to_en'] );
		$add_date = ( 'on' === $options['change_filename_add_upload_date'] );
		$nfc      = ( 'on' === $options['change_filename_nfc'] );

		if ( ! $to_roman && ! $add_date && ! $nfc ) {
			return $filename;
		}

		if ( empty( $filename ) || ! seems_utf8( $filename ) ) {
			return $filename;
		}

		// 자음, 모음이 분리된 한글(맥에서 올린 파일 등)을 먼저 합친다. 로마자 변환 전에도 항상 필요하다
		if ( $to_roman || $nfc ) {
			$filename = Romanizer::compose( $filename );
		}

		$parts     = explode( '.', $filename );
		$extension = array_pop( $parts );

		if ( empty( $parts ) ) {
			$fname     = $extension;
			$extension = null;
		} else {
			$fname = implode( '.', $parts );
		}

		// 한글을 로마자로 바꾼 뒤 파일명에 쓸 수 있는 글자만 남긴다
		if ( $to_roman && $this->is_korean( $fname ) ) {

			/* sanitize_title_with_dashes
			 *  1. 공백 => - 으로 변환
			 *  2. 영 대문자는 소문자로 변환
			 *  3. 영숫자, _ - 를 제외한 문자는 모두 제거
			 */
			$fname = sanitize_title_with_dashes( Romanizer::romanize( $fname ) );

			// 남은 글자가 없으면(한자만 있는 이름 등) 임의의 영숫자로 변환
			if ( '' === $fname ) {
				$fname = strtolower( wp_generate_password( 18, false, false ) );
			}
		}

		// 파일 업로드 일시 추가 (한글 변환과 관계없이 모든 파일, 이미 붙어 있으면 다시 붙이지 않는다)
		if ( $add_date && ! preg_match( '/__\d{4}-\d{2}-\d{2}-\d{2}-\d{2}-\d{2}$/', $fname ) ) {
			$fname .= '__' . current_time( 'Y-m-d-H-i-s' );
		}

		return $fname . ( $extension ? '.' . $extension : '' );
	}

	/**
	 * 문자열에 한글(완성형, 자모)이 포함되어 있는지 확인
	 *
	 * @param string $check_string 한글이 포함되어 있는지 검사할 문자열
	 * @return bool
	 */
	private function is_korean( $check_string ) {
		return 1 === preg_match( '/[\x{1100}-\x{11FF}\x{3130}-\x{318F}\x{AC00}-\x{D7A3}]/u', $check_string );
	}
}
add_action( 'rocket_media_library_mime_type_init', array( 'rocket_media_library_mime_type\Upload', 'init' ), 1, 1 );
