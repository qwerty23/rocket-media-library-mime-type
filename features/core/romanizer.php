<?php
namespace rocket_media_library_mime_type;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // 직접 접근 차단
}

/**
 * 한글을 로마자로 바꾼다 ( 국립국어원 로마자 표기법의 글자 대응표 사용 )
 *
 * 예) 사과 -> sagwa, 한글 -> hangeul
 *
 * 한글 완성형(가 ~ 힣)은 초성 19개, 중성 21개, 종성 28개가 규칙적으로 배열되어 있어서
 * 외부 서비스나 라이브러리 없이 계산만으로 글자를 나눌 수 있다.
 *
 *   번호 = 글자 코드 - 0xAC00
 *   초성 = 번호 / 588,  중성 = (번호 % 588) / 28,  종성 = 번호 % 28
 *
 * 발음에 따라 달라지는 규칙(연음, 비음화 등)은 적용하지 않고 글자마다 그대로 바꾼다. 예) 신라 -> sinra
 * 파일 이름용이라 이 정도로 충분하다. 한글이 아닌 글자는 그대로 둔다.
 */
class Romanizer {

	private static $initials = array( 'g', 'kk', 'n', 'd', 'tt', 'r', 'm', 'b', 'pp', 's', 'ss', '', 'j', 'jj', 'ch', 'k', 't', 'p', 'h' );

	private static $medials = array( 'a', 'ae', 'ya', 'yae', 'eo', 'e', 'yeo', 'ye', 'o', 'wa', 'wae', 'oe', 'yo', 'u', 'wo', 'we', 'wi', 'yu', 'eu', 'ui', 'i' );

	private static $finals = array( '', 'k', 'k', 'k', 'n', 'n', 'n', 't', 'l', 'k', 'm', 'l', 'l', 'l', 'p', 'l', 'm', 'p', 'p', 't', 't', 'ng', 't', 't', 'k', 't', 'p', 't' );

	/**
	 * 자음과 모음이 분리된 한글(맥에서 올린 파일명 등, 유니코드 NFD)을 글자 하나로 합친다
	 * 예) ㅎㅏㄴㄱㅡㄹ(분리) -> 한글
	 *
	 * 한글 글자 조합 규칙만 계산하므로 다른 글자는 건드리지 않고, intl 확장도 필요 없다.
	 *
	 * @param string $text UTF-8 문자열
	 * @return string
	 */
	public static function compose( $text ) {

		$text = (string) $text;

		// 자음(초성) + 모음(중성) [+ 받침(종성)]
		$result = preg_replace_callback(
			'/([\x{1100}-\x{1112}])([\x{1161}-\x{1175}])([\x{11A8}-\x{11C2}])?/u',
			function ( $m ) {
				$code = 0xAC00 + ( ( self::code( $m[1] ) - 0x1100 ) * 21 + ( self::code( $m[2] ) - 0x1161 ) ) * 28;
				if ( isset( $m[3] ) && '' !== $m[3] ) {
					$code += self::code( $m[3] ) - 0x11A7;
				}
				return self::char( $code );
			},
			$text
		);

		if ( null === $result ) {
			return $text;
		}

		// 받침이 없는 글자 뒤에 받침이 따로 떨어져 있는 경우
		$joined = preg_replace_callback(
			'/([\x{AC00}-\x{D7A3}])([\x{11A8}-\x{11C2}])/u',
			function ( $m ) {
				$code = self::code( $m[1] );
				if ( 0 !== ( $code - 0xAC00 ) % 28 ) {
					return $m[0];
				}
				return self::char( $code + self::code( $m[2] ) - 0x11A7 );
			},
			$result
		);

		return ( null === $joined ) ? $result : $joined;
	}

	/**
	 * 3바이트 UTF-8 글자의 유니코드 번호
	 */
	private static function code( $char ) {
		return ( ( ord( $char[0] ) & 0x0F ) << 12 ) | ( ( ord( $char[1] ) & 0x3F ) << 6 ) | ( ord( $char[2] ) & 0x3F );
	}

	/**
	 * 유니코드 번호(0x0800 ~ 0xFFFF)를 3바이트 UTF-8 글자로
	 */
	private static function char( $code ) {
		return chr( 0xE0 | ( $code >> 12 ) ) . chr( 0x80 | ( ( $code >> 6 ) & 0x3F ) ) . chr( 0x80 | ( $code & 0x3F ) );
	}

	/**
	 * @param string $text UTF-8 문자열
	 * @return string 한글을 로마자로 바꾼 문자열
	 */
	public static function romanize( $text ) {

		$chars = preg_split( '//u', (string) $text, -1, PREG_SPLIT_NO_EMPTY );

		if ( ! is_array( $chars ) ) {
			return '';
		}

		$result = '';

		foreach ( $chars as $char ) {

			// 한글 완성형은 UTF-8 로 3바이트
			if ( 3 === strlen( $char ) ) {

				$code = ( ( ord( $char[0] ) & 0x0F ) << 12 ) | ( ( ord( $char[1] ) & 0x3F ) << 6 ) | ( ord( $char[2] ) & 0x3F );

				if ( $code >= 0xAC00 && $code <= 0xD7A3 ) {

					$index   = $code - 0xAC00;
					$result .= self::$initials[ intdiv( $index, 588 ) ]
						. self::$medials[ intdiv( $index % 588, 28 ) ]
						. self::$finals[ $index % 28 ];
					continue;
				}
			}

			$result .= $char;
		}

		return $result;
	}
}
