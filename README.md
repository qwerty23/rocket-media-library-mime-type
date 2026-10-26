# Wordpress Plugin - 업로드 파일 타입 추가

<p align="center">
<a href="https://github.com/qwerty23/rocket-media-library-mime-type/releases"><img src="https://img.shields.io/github/v/release/qwerty23/rocket-media-library-mime-type" alt="release"></a>
<img src="https://img.shields.io/badge/PHP-7.0%2B-777bb4" alt="php">
<img src="https://img.shields.io/badge/WordPress-5.0%2B-21759b" alt="wordpress">
<img src="https://img.shields.io/badge/license-GPL--2.0--or--later-blue" alt="license">
</p>

Wordpress 에서 설정만으로 업로드 가능한 파일 종류를 추가할 수 있는 플러그인 입니다.
또한 Wordpress에서 파일 업로드와 관련된 몇가지 유용한 기능을 넣었습니다.

> 보안 문제 수정 및 서비스가 종료된 파파고 API 옵션은 삭제했습니다. 대신 외부 API 없이 한글을 영어로 변경하는 옵션으로 대체했습니다. 기존 버전과 크게 달라지지는 않았지만 오류나 버그를 발견했다면 알려주시면 감사하겠습니다.


## 특징

크게 4가지의 기능이 있습니다.

 1. Wordpress 에서 **업로드를 허용할 파일을 설정**할 수 있습니다. 예를들어 hwp 확장자의 파일이 업로드가 안될경우 이 플러그인으로 활성화 시킬수 있습니다.
 2. 미디어 라이브러리에서 특정 확장자의 파일 목록을 볼 수 있습니다. 예를들어 hwp 파일들만 목록에 표시할 수 있습니다.
 3. 업로드시 **한글 파일명을 영어(로마자)로 자동 전환** 할 수 있습니다. ( 예: `사과.jpg` -> `sagwa.jpg` ) 영문 파일명은 그대로 업로드됩니다. 외부 서비스나 API 키 없이 서버에서 계산만 하므로 비용이 들지 않습니다. 파일명 뒤에 **업로드 일시를 붙이는 옵션**( 예: `photo__2026-01-31-09-30-00.jpg` )은 따로 쓸 수 있고, 맥(macOS)에서 올린 자음, 모음이 분리된 한글 파일명을 정상적인 한글로 합쳐 주는 옵션도 있습니다.
 4. **위험한 형식(svg, exe, 매크로 파일)은 관리자만 업로드**하도록 제한할 수 있습니다. ( 기본 켜짐 )

> 3.0.0 에서 네이버 파파고 번역 옵션은 없어졌습니다. 파파고 무료 API 가 2024년 2월에 종료되었기 때문입니다.


## 현재 선택 가능한 파일 종류

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
* exe

svg, exe, 매크로가 포함될 수 있는 파일(ppam, pptm)은 신중하게 허용하세요. 필요한 파일을 올린 뒤에는 옵션을 다시 끄는 것을 권장합니다.


## 설치

일반적인 워드프레스 플러그인 설치와 같습니다.

 1. [Releases](https://github.com/qwerty23/rocket-media-library-mime-type/releases) 에서 압축 파일을 받아 `wp-contents/plugins` 폴더안에 올리거나, 관리자 화면에서 플러그인 업로드로 설치한 뒤 활성화합니다.
 2. `관리자 메뉴` > `설정 `> `업로드 파일 타입` 에서 업로드를 허용할 파일 타입을 선택해 주세요.

필요한 환경 : WordPress 5.0 이상, PHP 7.0 이상


## 사용
1. `관리자 메뉴` > `설정`> `업로드 파일 타입` 에서 업로드를 허용할 확장자를 선택하고 저장
2. `관리자 메뉴` > `미디어` 에서 허용한 확장자의 파일이 업로드 되는지 확인
3. 허용한 확장자의 파일이 미디어 라이브러리 상단의 필터 (모든 미디어 아이템) 에 추가됐는지 확인
4. 한글 파일명 자동변환을 사용하려면 **파일 업로드시 한글 파일명을 영어로 변환** 옵션을 활성화하고 저장 > 미디어에서 테스트


## 외부 서비스

이 플러그인은 외부 서비스에 연결하지 않으며 어떤 데이터도 외부로 보내지 않습니다. 설정 화면에서 쓰는 라이브러리(Foundation, Font Awesome 등)는 플러그인에 포함되어 있습니다. ( `assets/vendor/LICENSES.txt` )


## 문의 혹은 건의사항

기타 플러그인에 관한 문의 및 건의사항은 [플러그인 소개글](http://in-web.co.kr/wordpress/plug-in/wordpress-%EC%97%85%EB%A1%9C%EB%93%9C-%ED%99%95%EC%9E%A5%EC%9E%90-%EC%B6%94%EA%B0%80-rocket-media-library-mime-type/)에 댓글을 남기거나 GitHub Issues 를 이용해 주세요.


## 참고

아래는 워드프레스에서 한글 폰트를 쉽게 사용할 수 있는 플러그인입니다.
- <a href="https://github.com/qwerty23/rocket-font" target="_blank">로켓폰트 깃허브 사이트 링크</a>


## LICENSE
[GPL-2.0 license](https://opensource.org/licenses/GPL-2.0)
