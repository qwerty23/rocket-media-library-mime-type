<?php

namespace rocket_media_library_mime_type;

class Setting extends Feature {
	
	//무료지만 하루에 10000글자 제한
	//2020-7-24 수정 : "파파고"로 API 가 변경됨에 따라 URL변경
	private $naver_api_url = "https://openapi.naver.com/v1/papago/n2mt";

	public function __construct() {
		$this->add_action( 'admin_menu', 'rocket_media_library_mime_type_menu' );
		
		if(isset($_GET['page']) && in_array($_GET['page'],array(PLUGIN_MENU_SLUG))):
			//업데이트 된 값이 있으면 반영
			self::detect_option_change();
			$this->add_action( 'admin_enqueue_scripts', 'enqueue' );

		endif;
		
		//파일 업로드시 확장자를 체크하는 filter
		$this->add_filter('upload_mimes', 'custom_add_upload_mimes');
		$this->add_filter('post_mime_types', 'media_filter_allow_mime_types' );
		$this->add_filter('sanitize_file_name', 'rocket_sanitize_chars');
	}
	
	//관리 메뉴 추가
	public function rocket_media_library_mime_type_menu(){
		
		add_options_page('Rocket Media Library Mime Type', '업로드  파일 타입', 'manage_options', PLUGIN_MENU_SLUG, array( &$this, 'setting_page' ));
		
		//wp-pointer 처리-----start
		$enqueue_pointer_script_style = false;
		$dismissed_pointers_values = get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true );
		
		if(is_array($dismissed_pointers_values)){
			
			$dismissed_pointers = $dismissed_pointers_values;
			
		}else{
			
			$dismissed_pointers = explode( ',', $dismissed_pointers_values );
			
		}
		
		if( !in_array( 'rocket_media_upload_file_settings_pointer', $dismissed_pointers ) ) {
			$enqueue_pointer_script_style = true;
			$this->add_action("admin_print_footer_scripts","show_post_pointer");
		}
		
		if( $enqueue_pointer_script_style ) {
			wp_enqueue_style( 'wp-pointer' );
			wp_enqueue_script( 'wp-pointer' );
		}
		//wp-pointer 처리-----end
	}
	
	/**
	 * 플러그인 최초 활성화시 포인터 활성화
	 */
	function show_post_pointer(){
		
		$wp_pointer_content = __('post pointer message', PLUGIN_PREFIX);
		$wp_pointer_image_content = __('post image pointer message', PLUGIN_PREFIX);
		?>
		<style>
			.wp-pointer-content h3.rocket-icon:before{content: "\f326";}
		</style>
		<script>
		jQuery(document).ready( function($) {
			
			if($("#menu-settings .menu-icon-settings").length > 0){
				
				var options = {"content":"<h3 class='rocket-icon'>"+'Rocket Media Library Mime Type'+"<\/h3>"+'<p>플러그인이 활성화 되었습니다.</p><p>[설정] > [이미지 파일 타입] 메뉴에서 허용할 파일 타입을 설정해 주세요</p>',"position":{"edge":"left","align":"center"}};
				if ( ! options ) return;
				
				options = $.extend( options, {
					close: function() {
						$.post( ajaxurl, {
							pointer: 'rocket_media_upload_file_settings_pointer', 
							action: 'dismiss-wp-pointer'
						});
					}
				});
				$('#menu-settings .menu-icon-settings').pointer( options ).pointer("open");
			}
		});
		</script>
		
		<?php
	}
	
	/**
	 * 관리 페이지 랜더링
	 */
	public function setting_page(){
		
		$template = self::get_template();
		$current_allowed_mime_types = get_allowed_mime_types();
		$options = self::get_current_all_options();
		$template->set('current_allowed_mime_types',$current_allowed_mime_types);
		$template->set('rocket_plugin_mime_types',MimeType::get_mime_types());
		$template->set('post_max_size',size_format($this->format_size(ini_get( 'post_max_size' ))));
		$template->set('version',VERSION);
		$template->set('options',$options);
		
		echo $template->apply("admin/setting.php");

	}
	
	/**
	 * 파일 용량 계산 : 단순 정보 표시용
	 *
	 * @param string 
	 * @return string
	 */
	function format_size($filesize){
		$l   = substr( $filesize, - 1 );
		$ret = substr( $filesize, 0, - 1 );

		switch ( strtoupper( $l ) ) {
			case 'P':
				$ret *= 1024;
			case 'T':
				$ret *= 1024;
			case 'G':
				$ret *= 1024;
			case 'M':
				$ret *= 1024;
			case 'K':
				$ret *= 1024;
		}
		return $ret;
	}
	
	/**
	 * 워드프레스에서 파일을 업로드 할때, 워드프레스에서 허용하는 파일 확장자를 불러오는데 바로 그 전에 실행되는 펑션
	 *
	 * @param array $wp_mime_types 워드프레스에서 허용하는 파일 확장자 목록
	 * @return array 워드프레스에서 기본 허용 확장자 + 플러그인에서 설정한 확장자 목록
	 */
	public function custom_add_upload_mimes($wp_mime_types = array()){
		
		$options = self::get_current_all_options();
		$mime_types = MimeType::get_mime_types();
		
		foreach($mime_types as $mime_type_key => $mime_type_info):

			if($options['rocket_upload_mime_type_' . $mime_type_key]=="yes"){
				
				if(!empty($wp_mime_types[$mime_type_info['extension']])){
					continue;
				}
				$wp_mime_types[$mime_type_info['extension']] = $mime_type_info['mime_type'];
				
			}else{
				
				if(isset($wp_mime_types[$mime_type_info['extension']])){
					
					unset($wp_mime_types[$mime_type_info['extension']]);

				}
				
			}
			
		endforeach;
		
		return $wp_mime_types;
	}
	
	/**
	 * 워드프레스의 미디어 라이브러리에는 파일 목록을 확장자 별로 볼 수 있는 필터가 있는데,
	 * 그 필터 목록에 이 플러그인으로 추가한 확장자를 추가하는 펑션
	 *
	 * @param array $post_mime_types 미디어 라이브러리의 필터 파일 확장자 목록
	 * @return array 미디어 라이브러리의 필터 파일 확장자 목록  + 플러그인에서 추가한 확장자 명
	 */
	function media_filter_allow_mime_types( $post_mime_types = array()) {
		$plugin_allow_mime_types = self::custom_add_upload_mimes();
		foreach($plugin_allow_mime_types as $extension => $mime_type):
			
			$extension_arr = explode("|", $extension);
			$extension_name = implode(" , ", $extension_arr);
			
			$post_mime_types[$mime_type] = array( 
												0 =>  __( $extension_name, 'RocketMLMT' ), 
												1 => __( 'Manage '.$extension_name, 'RocketMLMT' ), 
												2 => _n_noop( $extension_name . ' <span class="count">(%s)</span>', $extension_name .' <span class="count">(%s)</span>', 'RocketMLMT' ));
			
		endforeach;
		return $post_mime_types;
	
	}
	
	/**
	 * 파일 업로드시 실행되는  워드프레스 필터중 하나 sanitize_file_name 를 사용
	 * 1. 파일명이 한글인지 확인 후 
	 * 2. 한글이면 영문으로 번역된 파일명을 반환 (ex:사과->Apple)
	 *
	 * @param $filename 업로드한 파일의 파일 이름
	 * @return string 영문 파일명
	 */
	function rocket_sanitize_chars($filename) {
		
		$options = self::get_current_all_options();
		
		if($options['change_filename_kr_to_en']=="on"):
			
			if ( !empty($filename) && seems_utf8($filename) && $this->is_korean($filename)) {
				
				$parts = explode('.', $filename);
				$extension = array_pop($parts);
				
				if (empty($parts)) {
					$fname = $extension;
					$extension = NULL;
				} else {
					$fname = implode('.', $parts);
				}
				
				//네이버 API 로 자동번역할 경우 in
				if($options['change_filename_use_naver_api'] == "on" && ($options['naver_client_id'] && $options['naver_client_secret'])):
					
					$filename_header = $this->get_translate_word($fname, $options);
				
					if(!$filename_header){
						$filename_header = substr(md5(rand(0,999)),0,4) . substr(md5(microtime()),0,14);
					}
					
					/* sanitize_title_with_dashes 
					*  1. 공백 => - 으로 변환
					*  2. 영 대문자는 소문자로 변환
					*  3. 영숫자, _ - 를 제외한 문자는 모두 제거
					*/
					$fname = sanitize_title_with_dashes($filename_header);
				
				//네이버 자동번역을 사용하지 않을 경우 임의의 랜덤 문자로 변환
				else:
					
					$filename_header = substr(md5(rand(0,999)),0,4) . substr(md5(microtime()),0,14);
					$fname = $filename_header;
					
				endif;
				
				$file_upload_date = "";
				//파일 업로드 일시 추가
				if($options['change_filename_add_upload_date'] == "on"):
					
					date_default_timezone_set(get_option('timezone_string'));
					$file_upload_date = "__" . date("Y-m-d-H-i-s",strtotime("now"));
					
				endif;
				
				$filename = $fname . $file_upload_date . (($extension) ? ".".$extension : '');
			}

		endif;
		
		return $filename;
	}
	
	/**
	 *  문자열에 한글이 포함되어 있는지 확인
	 *
	 * @param $check_string 한글이 포함되어 있는지 검사할 문자열
	 * @return boolean
	 */
	private function is_korean($check_string){
			
		return preg_match("/[\xE0-\xFF][\x80-\xFF][\x80-\xFF]/", $check_string);
		
	}

	/**
	 * 네이버 API 를 사용해 한글을 영문으로 번역 
	 * 하루 만글자 제한 (횟수가 아닌 글자수)
	 *
	 * @param $source_text 번역할 한글 문자열
	 * @param $options 플러그인 옵션
	 * @return string 번역된 영문 문자열
	 */
	private function get_translate_word($source_text, $options){
		
		//언어 체크
		$post_fields = array(
							"source"	=> "ko",
							"target"	=> "en",
							"text"		=> $source_text,
							);

		$curlsession = curl_init ();
		curl_setopt($curlsession, CURLOPT_URL, $this->naver_api_url);
		curl_setopt($curlsession, CURLOPT_POST, 0);
		curl_setopt($curlsession, CURLOPT_HTTPHEADER, array(
			'X-Naver-Client-Id: ' . $options["naver_client_id"],
			'X-Naver-Client-Secret: ' . $options["naver_client_secret"],
		));
		
		curl_setopt($curlsession, CURLOPT_POST, 1);
		curl_setopt($curlsession, CURLOPT_POSTFIELDS, http_build_query($post_fields));
		curl_setopt ($curlsession, CURLOPT_RETURNTRANSFER,  1);

		$buffer = curl_exec ($curlsession);
		
		//2020-7-24 수정 : "파파고"로 API 가 변경됨에 따라 출력값이 변경됨
		$status_code = curl_getinfo($curlsession, CURLINFO_HTTP_CODE);
		curl_close($curlsession);
		
		if ($status_code != 200){
			return false;
		}
		
		$response = json_decode($buffer);
		return $response->message->result->translatedText;
	}
	
	public function enqueue() {
		
		wp_enqueue_script("jquery-ui-sortable");
		
		wp_enqueue_style(
			'fontawesome',
			'//cdn.jsdelivr.net/fontawesome/4.5.0/css/font-awesome.min.css'
		);
		
		wp_enqueue_style(
			'jsdelivr-group',
			'//cdn.jsdelivr.net/g/foundation@5.5.1(css/foundation.min.css+css/normalize.css),jquery.powertip@1.2.0(css/jquery.powertip.min.css)'
		);
		
		wp_enqueue_script(
			'jsdelivr-group',
			'//cdn.jsdelivr.net/g/modernizr@2.8.3(modernizr.min.js),foundation@5.5.1(js/foundation.min.js),jquery.powertip@1.2.0(jquery.powertip.min.js)',
			array( 'jquery' ),
			VERSION,
			true
		);
	}
}
add_action( 'rocket_media_library_mime_type_init' , array( 'rocket_media_library_mime_type\Setting' , 'init'  )  , 1 ,  1 );
?>