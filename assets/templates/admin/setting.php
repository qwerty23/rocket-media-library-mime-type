<?php
/**
 * 설정 화면 (설정 > 업로드 파일 타입)
 *
 * 사용 가능한 변수 : $options, $rocket_plugin_mime_types, $post_max_size, $version, $updated,
 *                   $nonce_action, $nonce_name, $form_action
 * 화면에 출력하는 값은 모두 이스케이프한다.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// 이 파일은 함수 안에서 불러와 쓰므로 아래 변수는 전역 변수가 아니다
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound

// 툴팁(물음표 아이콘) 안에서 허용하는 HTML
$allowed_tip_html = array(
	'a'    => array(
		'href'   => array(),
		'target' => array(),
		'rel'    => array(),
	),
	'b'    => array(),
	'br'   => array(),
	'span' => array( 'class' => array() ),
);

// 주의 문구가 붙은 형식의 확장자 (예: svg, exe ...)
$risky_extensions = array();
foreach ( $rocket_plugin_mime_types as $rf_type ) {
	if ( ! empty( $rf_type['warning'] ) ) {
		$risky_extensions[] = str_replace( '|', '/', $rf_type['extension'] );
	}
}

/**
 * 물음표 아이콘 : 마우스를 올리면 설명이 나온다
 */
$rf_tip = function ( $html ) use ( $allowed_tip_html ) {
	echo '<i class="fa fa-question-circle fa-c2x tooltips" title="' . esc_attr( wp_kses( $html, $allowed_tip_html ) ) . '"></i>';
};

/**
 * 스위치(켜기/끄기) 하나를 그린다
 */
$rf_switch = function ( $id, $name, $value, $checked ) {
	?>
	<div class="switch round">
		<input id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $name ); ?>" type="checkbox" value="<?php echo esc_attr( $value ); ?>" <?php checked( $checked ); ?>>
		<label for="<?php echo esc_attr( $id ); ?>">
			<span class="switch-on">Yes</span>
			<span class="switch-off">No</span>
		</label>
	</div>
	<?php
};
?>
<div class="rocket-mlmt-scope">
<div class="row">
	<div class="rocket-wrap">
		<h1><i class="fa fa-rocket fa-2x"></i><?php esc_html_e( '업로드 파일 타입 설정', 'rocket-media-library-mime-type' ); ?></h1>

		<?php if ( $updated ) : ?>
		<div data-alert class="alert-box success radius">
			<?php esc_html_e( '저장 완료! 설정이 변경되었습니다.', 'rocket-media-library-mime-type' ); ?>
			<a href="#" class="close">&times;</a>
		</div>
		<?php endif; ?>

		<div class="row">
			<div class="large-12 columns">
				<div class="panel callout radius">
					<h5><?php esc_html_e( '참고 :', 'rocket-media-library-mime-type' ); ?> <span class="radius secondary label custom-label"><?php echo esc_html( site_url() ); ?></span> <?php esc_html_e( '사이트의 업로드 관련 설정값', 'rocket-media-library-mime-type' ); ?></h5>
					Max Upload Size <?php $rf_tip( __( '<b>한번에 업로드 가능한 파일의 최대 사이즈</b>입니다.<br>php.ini 파일에서 <span class="round info label">upload_max_filesize</span> 항목으로 값을 조정할 수 있습니다.<br>설정을 변경했다면 서버를 재시작해야 적용되며<br>아래의 <span class="round info label">post_max_size</span> 값과 같게 설정하면 됩니다.<br><br>호스팅 서비스를 사용하는 경우 관리자 메뉴에서 설정할수 있는지 확인해보시고<br>없다면 서버 관리자에 문의해 보세요.', 'rocket-media-library-mime-type' ) ); ?> : <?php echo esc_html( size_format( wp_max_upload_size() ) ); ?>
					<p>PHP Post Max Size <?php $rf_tip( __( '<b>한번에 전송 가능한 폼값의 최대 사이즈</b>입니다.<br>마찬가지로 php.ini 파일에서 <span class="round info label">post_max_size</span> 항목으로 값을 조정할 수 있습니다.<br>설정을 변경했다면 서버를 재시작해야 적용되며<br>위의 <span class="round info label">upload_max_filesize</span> 값과 같게 설정하면 됩니다.', 'rocket-media-library-mime-type' ) ); ?> : <?php echo esc_html( $post_max_size ); ?></p>
				</div>
			</div>
		</div>

		<form action="<?php echo esc_url( $form_action ); ?>" method="post" id="rocket-mlmt-form" role="form">
			<?php wp_nonce_field( $nonce_action, $nonce_name ); ?>
			<input type="hidden" name="rocket_mlmt_action" value="update">

			<button type="submit" class="button-primary dnp-plugin-submit"><?php esc_html_e( '저장', 'rocket-media-library-mime-type' ); ?></button>
			<p></p>

			<table class="">
				<tbody>
					<tr>
						<th width="80%">
							<?php esc_html_e( '1. 파일 업로드시 한글 파일명을 영어로 변환', 'rocket-media-library-mime-type' ); ?>
							<?php $rf_tip( __( '예) 사과.jpg -> sagwa.jpg<br>영문 파일은 영향 없음<br>발음을 따라 로마자로 바꿉니다. 외부 서비스를 사용하지 않습니다.<br><br>파일명이 한글이면 몇가지 오류가 발생할 수 있습니다.<br>1. 업로드 후 파일명이 깨진다거나<br>2. 경로명은 맞는데 이미지가 보이지 않는다거나<br>3. 예를들어 PDF 파일을 사용함으로 설정했는데도 업로드가 안된다거나<br><br>위와같은 현상이 있거나 방지하고 싶다면 이 옵션을 사용하면 됩니다.', 'rocket-media-library-mime-type' ) ); ?>
						</th>
						<td><?php $rf_switch( 'change_filename_kr_to_en', 'change_filename_kr_to_en', 'on', 'on' === $options['change_filename_kr_to_en'] ); ?></td>
					</tr>
					<tr>
						<th width="80%">
							<?php esc_html_e( '2. 파일명 뒤에 업로드 일시를 붙임', 'rocket-media-library-mime-type' ); ?>
							<?php $rf_tip( __( '예) photo.jpg -> photo__2020-07-23-19-07-03.jpg<br>1번을 같이 사용하면 사과.jpg -> sagwa__2020-07-23-19-07-03.jpg<br>모든 파일에 적용되며, 1번과 관계없이 따로 쓸 수 있습니다.<br><br>워드프레스는 업로드한 폴더를 년월로 나눠서 관리하는데,<br>한달에 업로드하는 파일의 양이 많아 파일명만 보고도 언제 업로드한 파일인지 알고 싶을경우 사용하면 됩니다.', 'rocket-media-library-mime-type' ) ); ?>
						</th>
						<td><?php $rf_switch( 'change_filename_add_upload_date', 'change_filename_add_upload_date', 'on', 'on' === $options['change_filename_add_upload_date'] ); ?></td>
					</tr>
					<tr>
						<th width="80%">
							<?php esc_html_e( '3. 맥(macOS)에서 올린 한글 파일명 고치기', 'rocket-media-library-mime-type' ); ?>
							<?php $rf_tip( __( '맥에서 만든 파일은 한글이 자음과 모음으로 분리된 형태로 저장되어 서버나 윈도우에서 "ㅎㅏㄴㄱㅡㄹ"처럼 깨져 보일 수 있습니다.<br>올릴 때 정상적인 한글로 합쳐 줍니다.<br><br>1번을 켠 경우에는 이 옵션과 관계없이 항상 합친 뒤 로마자로 바꿉니다.', 'rocket-media-library-mime-type' ) ); ?>
						</th>
						<td><?php $rf_switch( 'change_filename_nfc', 'change_filename_nfc', 'on', 'on' === $options['change_filename_nfc'] ); ?></td>
					</tr>
					<tr>
						<th width="80%">
							<?php esc_html_e( '4. 위험한 형식은 관리자만 업로드', 'rocket-media-library-mime-type' ); ?>
							<?php
							$rf_tip(
								sprintf(
									/* translators: %s: file extensions such as svg, exe */
									__( '아래 표에서 주의 문구가 붙은 형식(%s)은 관리자만 올릴 수 있게 합니다.<br>편집자, 작성자 등 다른 사용자는 허용으로 켜 두어도 이 형식을 올릴 수 없습니다.', 'rocket-media-library-mime-type' ),
									esc_html( implode( ', ', $risky_extensions ) )
								)
							);
							?>
						</th>
						<td><?php $rf_switch( 'restrict_risky_to_admin', 'restrict_risky_to_admin', 'on', 'on' === $options['restrict_risky_to_admin'] ); ?></td>
					</tr>
				</tbody>
			</table>

			<hr />
			<p></p>
			<table class="rocket-mlmt-types">
				<thead>
					<tr>
						<th><?php esc_html_e( '파일 확장자', 'rocket-media-library-mime-type' ); ?></th>
						<th>MIME Type</th>
						<th><?php esc_html_e( '설명', 'rocket-media-library-mime-type' ); ?> (<i class="fa fa-wordpress"></i>:<?php esc_html_e( '워드프레스 기본 허용 파일', 'rocket-media-library-mime-type' ); ?>)</th>
						<th><?php esc_html_e( '업로드 허용 여부', 'rocket-media-library-mime-type' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ( $rocket_plugin_mime_types as $extension => $mime_type ) : ?>
					<tr>
						<td>
							<?php foreach ( explode( '|', $mime_type['extension'] ) as $extension_name ) : ?>
								<span class="radius secondary label custom-label"><?php echo esc_html( $extension_name ); ?></span>
							<?php endforeach; ?>
						</td>
						<td><?php echo esc_html( $mime_type['mime_type'] ); ?></td>
						<td>
							<?php if ( ! empty( $mime_type['wp_default'] ) ) : ?>
								<i class="fa fa-wordpress"></i>
							<?php endif; ?>
							<?php echo esc_html( $mime_type['description'] ); ?>
							<?php if ( ! empty( $mime_type['warning'] ) ) : ?>
								<br><span class="rocket-mlmt-warning"><?php echo esc_html( $mime_type['warning'] ); ?> <?php esc_html_e( '파일을 업로드한 뒤에는 이 옵션을 다시 끄는 것을 권장합니다.', 'rocket-media-library-mime-type' ); ?></span>
							<?php endif; ?>
						</td>
						<td><?php $rf_switch( 'switch_' . $extension, 'rocket_upload_mime_type_' . $extension, 'yes', 'yes' === $options[ 'rocket_upload_mime_type_' . $extension ] ); ?></td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>

			<p></p>
			<div class="rocket-mlmt-savebar">
				<span class="rocket-mlmt-dirty" hidden><i class="fa fa-exclamation-circle"></i> <?php esc_html_e( '저장되지 않은 변경 사항이 있습니다.', 'rocket-media-library-mime-type' ); ?></span>
				<button type="submit" class="button-primary dnp-plugin-submit"><?php esc_html_e( '저장', 'rocket-media-library-mime-type' ); ?></button>
			</div>
		</form>
		<p></p>
		<div class="row">
			<div class="large-12 columns">
				<div class="panel callout radius">
					<h2><i class="fa fa-rocket"></i> Rocket Media Library Mime Type - <?php esc_html_e( '플러그인 정보', 'rocket-media-library-mime-type' ); ?></h2>
					<h3><?php esc_html_e( '플러그인 버전:', 'rocket-media-library-mime-type' ); ?> <?php echo esc_html( $version ); ?></h3>
					<h4><?php esc_html_e( '제작:', 'rocket-media-library-mime-type' ); ?> <a href="http://in-web.co.kr" target="_blank" rel="noopener noreferrer">Qwerty23</a></h4>
					<ol>
						<li>
							<?php
							/* translators: %d: number of file types */
							echo esc_html( sprintf( __( '현재 총 %d개의 선택할 수 있는 파일 타입이 있습니다.', 'rocket-media-library-mime-type' ), count( $rocket_plugin_mime_types ) ) );
							?>
						</li>
						<li><?php esc_html_e( '이 외에도 파일 확장자는 많지만, 주로 많이 사용하는 파일 위주로 넣었습니다.', 'rocket-media-library-mime-type' ); ?></li>
						<li><?php echo wp_kses( __( '추가하고 싶은 파일 확장자가 있다면 <a href="http://in-web.co.kr/wordpress/plug-in/wordpress-%ec%97%85%eb%a1%9c%eb%93%9c-%ed%99%95%ec%9e%a5%ec%9e%90-%ec%b6%94%ea%b0%80-rocket-media-library-mime-type/" target="_blank" rel="noopener noreferrer">이곳</a> 에서 요청하시면 검토 후 업데이트 하겠습니다.', 'rocket-media-library-mime-type' ), $allowed_tip_html ); ?></li>
					</ol>
					<?php echo wp_kses( __( '기타 플러그인에 관한 문의 및 건의사항은 <a href="http://in-web.co.kr/wordpress/plug-in/wordpress-%ec%97%85%eb%a1%9c%eb%93%9c-%ed%99%95%ec%9e%a5%ec%9e%90-%ec%b6%94%ea%b0%80-rocket-media-library-mime-type/" target="_blank" rel="noopener noreferrer"><i class="fa fa-rocket"></i>댓글</a> 달아주세요.', 'rocket-media-library-mime-type' ), array_merge( $allowed_tip_html, array( 'i' => array( 'class' => array() ) ) ) ); ?>
				</div>
			</div>
		</div>

	</div>
</div>
</div>
