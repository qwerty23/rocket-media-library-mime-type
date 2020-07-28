=== Rocket Media Library Mime Type ===
Contributors: Qwerty23
Donate link: http://in-web.co.kr
Plugin URI: http://in-web.co.kr
Tags: Wordpress Korean Media Library Upload File Type, 업로드 파일 타입, Rocket Upload File Type, Rocket Media
Requires at least: 3.5.1
Tested up to: 5.4.2
Stable tag: 2.0.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

wordpress 에서 업로드를 허용할 파일 타입을 설정할 수 있으며
업로드시 한글 파일을 영문으로 자동 번역해주는 등 업로드와 관련된 몇가지 유용한 기능을 넣었습니다.

== Description ==

크게 3가지의 기능이 있습니다.

1. wordpress 의 미디어 라이브러리(자료실)에서 업로드를 허용할 파일을 설정할 수 있습니다. 예를들어 hwp 확장자의 파일이 업로드가 안될경우 사용하시면 됩니다.
2. 미디어 라이브러리에서  특정 확장자의 파일 목록을 볼 수 있습니다. 예를들어 hwp 파일만 목록에 표시할 수 있습니다.
3. 한글 파일명을 자동으로 영문으로 전환 후 업로드 할 수 있습니다.
4. 3의 확장으로 **네이버 파파고 번역 API 를 이용해 한글 파일명을 자동으로 영문으로 전환**할 수 있습니다. ( 예: `사과.jpg` -> `Apple.jpg` )

4번의 네이버 파파고 API를 이용할 경우 기본 무료이지만 [하루에 10000 글자 제한](https://developers.naver.com/docs/papago/papago-nmt-overview.md#papago-%EB%B2%88%EC%97%AD-%EA%B0%9C%EC%9A%94) 이 있습니다. 
충분히 사용할수 있는 글자수이지만 무제한은 아니라는점 염두에 두시면 되겠습니다.

한글 파일명을 의미없는 영문자보다는 그래도 어느정도 알아볼 수 있게 바꾸고 싶을때 이 옵션을 사용하시면 됩니다. 
이 경우 [**네이버 파파고 API**](https://developers.naver.com/products/nmt/) 에서 `Client ID`와 `Client Secret`을 발급받아서 저장해야 합니다.

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

= 사용 =
1. `관리자 메뉴` > `설정`> `업로드 파일 타입` 에서 업로드를 허용할 확장자를 선택하고 저장
2. `관리자 메뉴` > `미디어` 에서 허용한 확장자의 파일이 업로드 되는지 확인
3. 허용한 확장자의 파일이 미디어 라이브러리 상단의 필터 (모든 미디어 아이템) 에 추가됐는지 확인
4. 한글 파일명 자동변환을 사용하려면 **파일 업로드시 한글 파일명을 자동으로 영문으로 변환** 옵션을 활성화하고 저장 > 미디어에서 테스트
5. 한글 파일명 자동 번역 변환을 사용하려면 [**네이버 파파고 API**](https://developers.naver.com/products/nmt/) 에서 `Client ID`와 `Client Secret`를 발급받아서 입력 저장후 미디어에서 테스트

= 참고 =

아래는 워드프레스에서 한글 폰트를 보다 쉽게 설정/사용할 수 있는 플러그인입니다.
- <a href="https://wordpress.org/plugins/rocket-font" target="_blank">로켓폰트 플러그인 워드프레스 사이트 링크</a>
- <a href="https://github.com/qwerty23/rocket-font" target="_blank">로켓폰트 깃허브 사이트 링크</a>

== Installation ==

일반적인 워드프레스 플러그인 설치와 같습니다.

 1. **워드프레스 관리자 화면의 플러그인 검색**에서 **업로드 파일** 로 검색해서 설치, 활성화하면 됩니다.
 2. 혹은 직접 파일을 다운로드를 받아서 압축을 풀고 `wp-contents/plugins` 폴더안에 업로드 후 활성화하면 됩니다.
 3. `관리자 메뉴` > `설정 `> `업로드 파일 타입` 에서 업로드를 허용할 파일 타입을 선택해 주세요.
 
== Frequently Asked Questions ==

기타 플러그인을 사용중에 버그를 발견했다던지 문의 및 업로드 파일 타입, 기능 추가등의 건의사항이 있다면
1. [블로그 플러그인 소개글](https://in-web.co.kr/wordpress/plug-in/wordpress-%ec%97%85%eb%a1%9c%eb%93%9c-%ed%99%95%ec%9e%a5%ec%9e%90-%ec%b6%94%ea%b0%80-rocket-media-library-mime-type/) (회원가입 불필요) 
2. [깃허브 이슈 게시판](https://github.com/qwerty23/rocket-media-library-mime-type/issues) (깃허브 계정 필요) 
을 통해 저에게 알려주세요. 검토 후 추가하겠습니다.


== Screenshots ==
1. 업로드 파일 타입 설정 화면입니다. 아직 hwp 파일은 선택하지 않은 상태입니다.
2. hwp 파일 타입은 1의 화면에서 선택하지 않았기 때문에 업로드 되지 않습니다.
3. hwp 파일은 업로드 허용으로 선택한 화면입니다.
4. 3의 화면에서 허용으로 선택했기 때문에 hwp 파일도 업로드 됩니다.
5. 한글 파일명을 영문으로 자동 변환 기능을 활성화 한 후 "미디어 라이브러리.jpg"라는 이름의 한글 파일을 업로드해서 임의의 영문으로 자동 변환한 모습입니다.
6. 네이버 번역 API 를 이용해서"미디어 라이브러리.jpg"라는 이름의 한글 파일을 영문 "media-library.jpg"로 자동 변환한 모습입니다.

== Changelog ==

= 2.0.1 =
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
