=== Rocket Media Library Mime Type ===
Contributors: Qwerty23
Donate link: http://in-web.co.kr
Plugin URI: http://in-web.co.kr
Tags: Wordpress Korean Media Library Upload File Type, 업로드 파일 타입
Requires at least: 3.5.1
Tested up to: 4.9.4
Stable tag: 2.0.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

wordpress 에서 업로드를 허용할 파일 타입을 설정할 수 있는 플러그인 입니다. 

== Description ==

크게 3가지의 기능이 있습니다.

1. wordpress 의 미디어 라이브러리(자료실)에서 업로드를 허용할 파일을 설정할 수 있습니다. 예를들어 hwp 확장자의 파일이 업로드가 안될경우 사용하시면 됩니다.
2. 미디어 라이브러리에서  특정 확장자의 파일 목록을 볼 수 있습니다. 예를들어 hwp 파일만 목록에 표시할 수 있습니다.
3. 한글 파일명을 자동으로 영문으로 전환 후 업로드 할 수 있습니다.
4. 3의 확장으로 네이버 번역 API 를 이용해 한글 파일명을 자동으로 영문으로 전환할 수 있습니다.(하루에 10000 글자 제한이 있습니다.) 예를들어 파일명이 "오렌지.jpg" 라면  "orange.jpg" 로 자동 번역되어 업로드 됩니다.
의미없는 영문자보다는 그래도 어느정도 알아볼 수 있게 파일명을 바꾸고 싶다면 이 옵션을 사용하시면 됩니다.

설정은 간단합니다. 업로드를 허용하고 싶은 파일 타입을 선택한 후 저장하면 끝입니다.

= 선택 가능한 파일 확장자 종류 =

* hwp
* bmp
* doc
* ico
* xla, xls, xlt, xlw
* pot, pps, ppt
* ppam
* pptm
* pptx
* psd
* mp4
* webm
* ogv
* csv
* ai,eps,ps
* txt
* xlsx
* svg
* json

만약 업로드하고 싶은 파일이 이 플러그인에 없다면 [댓글](http://in-web.co.kr/wordpress/plug-in/wordpress-%ec%97%85%eb%a1%9c%eb%93%9c-%ed%99%95%ec%9e%a5%ec%9e%90-%ec%b6%94%ea%b0%80-rocket-media-library-mime-type/) 을 통해 저에게 알려주세요. 검토 후 추가하겠습니다.


== Installation ==

1. 플러그인 검색에서 upload file type 혹은 rocket media library 로 검색하셔서 설치하시면 됩니다.
2. 혹은 직접 다운로드를 받아서 압축을 풀고 wp-contents/plugins 폴더안에 업로드하시면 됩니다.

== Frequently Asked Questions ==

문의사항 및 요청, 버그 리포트 등은 [댓글](http://in-web.co.kr/wordpress/plug-in/wordpress-%ec%97%85%eb%a1%9c%eb%93%9c-%ed%99%95%ec%9e%a5%ec%9e%90-%ec%b6%94%ea%b0%80-rocket-media-library-mime-type/) 에 댓글이나 메일을 보내주세요.

== Screenshots ==
1. 업로드 파일 타입 설정 화면입니다. 아직 hwp 파일은 선택하지 않은 상태입니다.
2. hwp 파일 타입은 1의 화면에서 선택하지 않았기 때문에 업로드 되지 않습니다.
3. hwp 파일은 업로드 허용으로 선택한 화면입니다.
4. 3의 화면에서 허용으로 선택했기 때문에 hwp 파일도 업로드 됩니다.
5. 한글 파일명을 영문으로 자동 변환 기능을 활성화 한 후 "미디어 라이브러리.jpg"라는 이름의 한글 파일을 업로드해서 임의의 영문으로 자동 변환한 모습입니다.
6. 네이버 번역 API 를 이용해서"미디어 라이브러리.jpg"라는 이름의 한글 파일을 영문 "media-library.jpg"로 자동 변환한 모습입니다.

== Changelog ==

= 2.0.0 =
* 파일 타입 추가 : svg, json

= 2.0.0 =
* 파일 타입 추가 : txt, xlsx
* 한글 파일명 파일 영문으로 자동 변환 기능 추가
* 네이버 번역 API를 사용한 한글 파일명 파일 영문으로 자동 변환 기능 추가

= 1.1.0 =
* 미디어 > 파일 확장자 필터 (지정한 확장자 파일 목록 보기) 기능 추가

= 1.0.1 =
* 파일 타입 추가 : ai,eps,ps

= 1.0.0 =
* 파일 타입 추가 : webm, ogv, csv
* 아이콘이 깨져서 나오는 현상 수정

= 0.0.1 =

== Upgrade Notice ==

= 0.0.1 =
