=== Rocket Media Library Mime Type ===
Contributors: qwerty-23
Tags: upload, file type, mime type, hwp, korean
Requires at least: 5.0
Tested up to: 7.1
Requires PHP: 7.0
Stable tag: 3.0.2
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Choose which file types (hwp, svg, csv ...) can be uploaded to the media library, and convert Korean file names to Latin letters.

== Description ==

A small plugin that collects useful upload-related features. It has four main features.

1. **Choose the file types that may be uploaded** to the media library. For example, use it when hwp (Hangul Word Processor) files are rejected by WordPress.
2. Filter the media library list by file extension. Example: show only hwp files.
3. **Automatically convert Korean file names to Latin letters (romanization)** on upload. Example: `사과.jpg` becomes `sagwa.jpg`, and `한글 파일.jpg` becomes `hangeul-pail.jpg`. A separate option appends the **upload date and time** to the file name (for example `photo__2026-01-31-09-30-00.jpg`).
4. **Restrict risky file types (svg, exe, macro-enabled Office files) to administrators.**

= 한국어 안내 =

보안 문제 수정 및 서비스가 종료된 파파고 API 옵션은 삭제했습니다. 대신 외부 API 없이 한글을 영어로 변경하는 옵션으로 대체했습니다. 기존 버전과 크게 달라지지는 않았지만 오류나 버그를 발견했다면 알려주시면 감사하겠습니다.

워드프레스 업로드와 관련된 기능을 모은 플러그인입니다. 설정은 **설정 > 업로드 파일 타입**에서 합니다.

* 업로드를 허용할 파일 타입 선택 (hwp, svg, csv 등)
* 한글 파일명을 로마자로 자동 변환 (예: 사과.jpg > sagwa.jpg). 외부 서비스나 API 키가 필요 없습니다.
* 파일명 뒤에 업로드 일시 붙이기, 맥(macOS)에서 올린 한글 파일명 고치기
* 위험한 형식(svg, exe, 매크로 파일)은 관리자만 업로드

= Korean file name conversion =

Korean letters are converted to Latin letters using the letter table of the Revised Romanization of Korean. This is not a translation: `사과` becomes `sagwa`, not `Apple`. The conversion is calculated on your server, **no external service is used**, and no API key or fee is needed. Pronunciation-dependent rules (such as liaison) are not applied; each letter is converted as it is. Example: `신라` becomes `sinra`.

Files uploaded from macOS often have Korean letters split into consonants and vowels (Unicode NFD), which look broken on servers and Windows. With the option "Fix Korean file names uploaded from macOS" turned on, the letters are combined back into normal Korean when the file is uploaded.

= Selectable file extensions =

hwp, bmp, doc, ico, xla/xls/xlt/xlw, xlsx, pot/pps/ppt, pptx, ppam, pptm, psd, mp4, webm, ogv, csv, ai/eps/ps, txt, svg, json, exe, and the file types WordPress allows by default (jpg, png, gif, mp3, mov, avi, wmv, mid, pdf, zip, gz).

**Security notice**: Allow svg, exe and files that may contain macros (ppam, pptm) with care. After uploading the file you need, turn the option off again. With the option "Only administrators can upload risky file types" (on by default), users who are not administrators cannot upload these types even if they are enabled.

== External services ==

This plugin does not connect to any external service and does not send any data outside your site. The settings screen uses only the CSS and JavaScript files included in the plugin.

== Installation ==

The installation is the same as for any WordPress plugin.

1. Upload the plugin folder (`rocket-media-library-mime-type`) to `/wp-content/plugins/`, or install it from the plugin search in the admin screen.
2. Activate the plugin.
3. Go to **Settings > Upload File Types**, choose the file types you want to allow, and save.

== Frequently Asked Questions ==

= My hwp file is not uploaded =

Go to **Settings > Upload File Types**, turn hwp on, save, and upload the file again from **Media**. Since 3.0.0, hwp files that were rejected because WordPress detected a different type from the file content are also allowed.

= Does it also apply to files uploaded in the block editor? =

Yes. Since 3.0.0 the same settings apply to files uploaded in the block editor.

= Questions and suggestions =

If you find a bug or want another file type added, leave a comment on the [plugin page on my blog](https://in-web.co.kr/wordpress/plug-in/wordpress-%ec%97%85%eb%a1%9c%eb%93%9c-%ed%99%95%ec%9e%a5%ec%9e%90-%ec%b6%94%ea%b0%80-rocket-media-library-mime-type/) or open a [GitHub issue](https://github.com/qwerty23/rocket-media-library-mime-type/issues).

== Screenshots ==

1. The upload file type settings screen. The hwp file type is not selected yet.
2. The hwp file is not uploaded because its type was not selected in screen 1.
3. The screen with the hwp file type allowed.
4. The hwp file is uploaded because it was allowed in screen 3.
5. A Korean file name converted to a Latin name after turning on the romanization option.
6. The same with the option that appends the upload date and time.

== Changelog ==

= 3.0.2 =
* security : Re-confirmed that saving the settings is protected against Cross-Site Request Forgery (CSRF): it requires a logged-in user with the `manage_options` capability and a valid nonce.
* fix : Fixed the position of the Yes / No labels on the switches of the settings screen.
* change : The readme now lists the WordPress.org user name `qwerty-23` as contributor.

= 3.0.1 =
* fix : File type settings were reset when the plugin was deactivated and activated again.
* fix : Types that WordPress allows by default (doc, xlsx, pptx, csv, bmp, ico, mp4, txt and others) are on by default on a fresh install.
* fix : File name conversion applies only to uploads, and no longer leaves `%xx` in URLs for letters that cannot be romanized.
* fix : The plugin no longer allows a file type that another plugin or form has deliberately excluded for a specific upload.
* fix : Removed the use of `seems_utf8()`, which is deprecated since WordPress 6.9.
* change : The readme is now written in English (with a short Korean summary), as required by WordPress.org.
* change : `uninstall.php` no longer creates global variables.

= 3.0.0 =
* security : **Fixed an issue that allowed users who were not logged in to change the settings.** Saving settings now checks capability and nonce. Please update.
* security : All output of the settings screen is escaped, and each saved value is validated.
* fix : File type settings were reset when the plugin was deactivated and activated again.
* fix : File types that WordPress allows by default (doc, xlsx, pptx, csv, bmp, ico, mp4, txt and others) were blocked on a fresh install. The defaults now match WordPress.
* fix : File name conversion is applied only when a file is uploaded (other file name handling is not affected). Also fixed broken file URLs caused by letters that cannot be romanized.
* security : Added warnings for svg, exe and macro-enabled files, and an option (on by default) to let only administrators upload risky file types.
* change : "Append upload date and time" is now a separate option that applies to all files, regardless of Korean conversion (off by default). When updating, it is turned off for sites that did not use the Korean conversion, so existing file names do not suddenly change.
* new : Added an option that combines Korean file names split into consonants and vowels (uploaded from macOS) into normal Korean.
* change : A save bar is fixed at the bottom of the screen and warns about unsaved changes.
* fix : Switches that were turned off were not saved as off.
* fix : Settings were not applied to files uploaded from the block editor (REST API).
* fix : Removed code that changed the PHP time zone of the site. The upload date and time use the site time zone.
* fix : Wrong default for xlsx.
* change : The settings screen keeps the same look as before. Libraries previously loaded from external CDNs (Foundation, Font Awesome and others) are now **bundled with the plugin**, so the settings screen loads nothing from external sites.
* change : **Korean file name conversion now uses romanization, and the Naver Papago translation option was removed.** The free Papago API ended in February 2024. Romanization needs no external service, API key or fee. Example: 사과.jpg to sagwa.jpg. Previously saved API keys are deleted automatically on update.
* change : Korean detection is limited to Hangul syllables and jamo (Hanja and others were treated as Korean before).
* change : Requires PHP 7.0 or later and WordPress 5.0 or later.
* add : Settings are cleaned up when the plugin is deleted.

= 2.1.0 =
* fix : Changed the translation API (Naver to Naver Papago).
* fix : For Korean files, spaces and dashes are removed after translating to English.

= 2.0.1 =
* Added file types: svg, json

= 2.0.0 =
* Added file types: txt, xlsx
* Added automatic conversion of Korean file names to English.
* Added Korean file name conversion using the Naver translation API.

= 1.1.0 =
* Added a file extension filter to Media (show only files with a chosen extension).

= 1.0.1 =
* Added file types: ai, eps, ps

= 1.0.0 =
* Added file types: webm, ogv, csv
* Fixed broken icons.

= 0.0.1 =
* First version

== Upgrade Notice ==

= 3.0.2 =
Security update. Settings changes require a logged-in administrator and a valid nonce (CSRF fix). Please update.

= 3.0.1 =
Minor maintenance update. If you are updating from 2.x, see the 3.0.0 notes: users who were not logged in could change the settings in older versions.

= 3.0.0 =
Security update. Before this version, users who were not logged in could change the settings. The Naver Papago option is removed; Korean file names are romanized. Please update and review your settings.
