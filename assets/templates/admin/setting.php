<style>
	.fa-question-circle{color:#1E8CBE;}
	.fa-c2x{font-size:1.1em;}
	.rocket-wrap{margin:10px 20px 0px 2px;}
	
	/* foundation */
	ul li ul, ul li ol{margin-left:0px;}
	ul{margin-left:0px;}
	.row{max-width:none;}
	table{width:100%;}
	.custom-label{font-size:1.5rem;}
	body{background:inherit;}
	.switch-on {
		position: absolute;
		left: -55px;
		top: 10px;
		color: white;
		font-weight: bold;
		font-size: 9px; 
	}

	.switch-off {
		position: absolute;
		left: -25px;
		top: 10px;
		color: white;
		font-weight: bold;
		font-size: 9px; 
	}
	input[type="text"],input[type="password"],.switch{margin:inherit;}
</style>
<script>
	jQuery(document).ready(function($){
	
		$(document).foundation();
		
		$('.tooltips').powerTip({
			placement: 'ne',
			mouseOnToPopup: true,
			smartPlacement: true
		});
		
		translate_option();
		
		$("#change_filename_kr_to_en").on("click", function(){
			translate_option();
		});
		
		function translate_option(){
			if($("#change_filename_kr_to_en").is(":checked")){
				$(".file-translate-options").fadeIn();
			}else{
				$(".file-translate-options").fadeOut();
			}
		}
		
		naver_translate_option();
		
		$("#change_filename_use_naver_api").on("click", function(){
			naver_translate_option();
		});
		
		function naver_translate_option(){
			if($("#change_filename_kr_to_en").is(":checked") && $("#change_filename_use_naver_api").is(":checked")){
				$(".naver-translate-options").fadeIn();
			}else{
				$(".naver-translate-options").fadeOut();
			}
		}
		 
	});

</script>

<div class="row">
	<div class="rocket-wrap">
		<h1><i class="fa fa-rocket fa-2x"></i>업로드 파일 타입 설정</h1>
		
		<?php
		if(!empty($_POST['action']) && $_POST['action']=="update"):
		?>
		<div data-alert class="alert-box success radius">
			저장 완료! 설정이 변경되었습니다.
			<a href="#" class="close">&times;</a>
		</div>
		<?php
		endif;
		?>
		
		<div class="row">
			<div class="large-12 columns">
				<div class="panel callout radius">
					<h5>참고 : <span class="radius secondary label custom-label"><?php echo site_url();?></span> 사이트의 업로드 관련 설정값</h5>
					Max Upload Size <i class="fa fa-question-circle fa-c2x tooltips" title="<b>한번에 업로드 가능한 파일의 최대 사이즈</b>입니다.<br><span class='round info label'>php.ini</span>파일에서 <span class='round info label'>upload_max_filesize</span>항목으로 값을 조정할 수 있습니다.<br>설정을 변경했다면 서버를 재시작해야 적용되며<br>아래의 <span class='round info label'>post_max_size</span> 값과 같게 설정하면 됩니다.<br><br>호스팅 서비스를 사용하는 경우 관리자 메뉴에서 설정할수 있는지 확인해보시고<br>없다면 서버 관리자에 문의해 보세요."></i> : <?php echo size_format( wp_max_upload_size() );?>
					<p>PHP Post Max Size <i class="fa fa-question-circle fa-c2x tooltips" title="<b>한번에 전송 가능한 폼값의 최대 사이즈</b>입니다.<br>마찬가지로 <span class='round info label'>php.ini</span>파일에서 <span class='round info label'>post_max_size</span>항목으로 값을 조정할 수 있습니다.<br>설정을 변경했다면 서버를 재시작해야 적용되며<br>위의 <span class='round info label'>upload_max_size</span> 값과 같게 설정하면 됩니다."></i> : <?php echo $post_max_size;?></p>
				</div>
			</div>
		</div>
		
		<form action="#" method="post" id="rocketfont_form" role="form">
			
			<button type="submit" class="button-primary dnp-plugin-submit">저장</button>
			<p></p>
			
			<table class="">
				<tbody>
					<tr>
						<th width="80%">1. 파일 업로드시 한글 파일명을 자동으로 영문으로 변환 <i class="fa fa-question-circle fa-c2x tooltips" title="예) 사과.jpg -> d554078dc6b975a00e.jpg<br>영문 파일은 영향 없음<br><br>파일명이 한글이면 몇가지 오류가 발생할 수 있습니다.<br>1. 업로드 후 파일명이 깨진다거나<br>2.경로명은 맞는데 이미지가 보이지 않는다거나<br>3.예를들어 PDF 파일을 사용함으로 설정했는데도 업로드가 안된다거나<br><br>위와같은 현상이 있거나 방지하고 싶다면 이 옵션을 사용하면 됩니다."></i></th>
						<td>
							<div class="switch round">
								<input id="change_filename_kr_to_en" name="change_filename_kr_to_en" type="checkbox" <?php checked( $options['change_filename_kr_to_en'], "on" ); ?>>
								<label for="change_filename_kr_to_en">
									<span class="switch-on">Yes</span>
									<span class="switch-off">No</span>
								</label>
							</div>
						</td>
					</tr>
					<tr class="file-translate-options">
						<th width="80%">2. 변환된 파일명에 업로드 일시를 붙임<i class="fa fa-question-circle fa-c2x tooltips" title="예) 사과.jpg -> d554078dc6b975a00e__2020-07-23-19-07-03.jpg<br>이 옵션을 사용하면 한글에서 영문으로 변환된 파일명 뒤에 자동으로 업로드한 시각 및 영문으로 변환한 후 업로드합니다.<br>워드프레스는 업로드한 폴더를 년월로 나눠서 관리하는데,<br>한달에 업로드하는 파일의 양이 많아 파일명만 보고도 알 언제 업로드한 파일인지 알고 싶을경우 사용하면 됩니다."></i></th>
						<td>
							<div class="switch round">
								<input id="change_filename_add_upload_date" name="change_filename_add_upload_date" type="checkbox" <?php checked( $options['change_filename_add_upload_date'], "on" ); ?>>
								<label for="change_filename_add_upload_date">
									<span class="switch-on">Yes</span>
									<span class="switch-off">No</span>
								</label>
							</div>
						</td>
					</tr>
					
					<tr class="file-translate-options">
						<th width="80%">3. 한글 파일을 영문으로 변환시 네이버 파파고 API를 이용해 자동으로 번역해서 변환<i class="fa fa-question-circle fa-c2x tooltips" title="예) 사과.jpg -> Apple.jpg<br>2번 옵션을 사용할경우 Apple__2020-07-23-19-07-03.jpg<br><br>이 옵션을 사용하면 네이버 파파고의 번역 API를 이용해 한글 파일명을 영문으로 번역합니다. <br>아래의 네이버의 Client ID 와 Client Secret 입력이 필요합니다."></i></th>
						<td>
							<div class="switch round">
								<input id="change_filename_use_naver_api" name="change_filename_use_naver_api" type="checkbox" <?php checked( $options['change_filename_use_naver_api'], "on" ); ?>>
								<label for="change_filename_use_naver_api">
									<span class="switch-on">Yes</span>
									<span class="switch-off">No</span>
								</label>
							</div>
						</td>
					</tr>
					<tr class="file-translate-options naver-translate-options">
						<th width="80%">3-1. 네이버 파파고API - Client ID <i class="fa fa-question-circle fa-c2x tooltips" title="<a href='https://developers.naver.com/products/nmt/' target='_blank'>이곳</a>에서 신청하면 됩니다. <br>키를 발급받은 후 반드시 기계번역 항목을 사용함으로 체크해야 사용할 수 있습니다."></i></th>
						<td>
							<input name="naver_client_id" type="text" value="<?php echo $options['naver_client_id'];?>" placeholder="네이버 API Client ID">
						</td>
					</tr>
					<tr class="file-translate-options naver-translate-options">
						<th width="80%">3-2. 네이버 파파고API - Client Secret <i class="fa fa-question-circle fa-c2x tooltips" title="<a href='https://developers.naver.com/products/nmt/' target='_blank'>이곳</a>에서 신청하면 됩니다. <br>위의 Client ID 발급받는곳과 동일한 페이지 입니다."></i></th>
						<td>
							<input name="naver_client_secret" type="password" value="<?php echo $options['naver_client_secret'];?>" placeholder="네이버 API Client Secret">
						</td>
					</tr>
					
				</tbody>
			</table>
						
			<hr />
			<p></p>
			<table class="">
				<thead>
					<tr>
						<th>파일 확장자</th>
						<th>MIME Type</th>
						<th>설명 (<i class="fa fa-wordpress"></i>:워드프레스 기본 허용 파일)</th>
						<th>업로드 허용 여부</th>
					</tr>
				</thead>
				<tbody>
					<?php
					foreach($rocket_plugin_mime_types as $extension => $mime_type):
					?>
					<tr>
						<td>
							<?php
							$extension_arr = explode("|", $mime_type["extension"]);
							foreach($extension_arr as $extension_name):
								?>
								<span class="radius secondary label custom-label"><?php echo $extension_name?></span>
								<?php
							endforeach;
							?>
						</td>
						<td><?php echo $mime_type["mime_type"]?></td>
						<td><?php echo $mime_type["description"]?></td>
						<td>
							<div class="switch round">
								<input id="switch_<?php echo $extension?>" name="rocket_upload_mime_type_<?php echo $extension;?>" type="checkbox" value="yes" <?php checked( $options['rocket_upload_mime_type_'.$extension], "yes" ); ?>>
								<label for="switch_<?php echo $extension?>">
									<span class="switch-on">Yes</span>
									<span class="switch-off">No</span>
								</label> 
							</div>
						</td>
					</tr>
					<?php
					endforeach;
					?>
				</tbody>
			</table>
			
			<input type="hidden" name="action" value="update">
			<p></p>
			<button type="submit" class="button-primary dnp-plugin-submit">저장</button>
			
		</form>
		<p></p>
		<div class="row">
			<div class="large-12 columns">
				<div class="panel callout radius">
					<h2><i class="fa fa-rocket"></i> Rocket Media Library Mime Type - 플러그인 정보</h2>
					<h3>플러그인 버전: <?php echo $version;?></h3>
					<h4>제작: <a href="http://in-web.co.kr" target="_blank">Qwerty23</a></h4>
					<ol>
						<li>현재 총 <?php echo sizeof($rocket_plugin_mime_types) ?>개의 선택할 수 있는 파일 타입이 있습니다.</li>
						<li>이 외에도 파일 확장자는 많지만, 주로 많이 사용하는 파일 위주로 넣었습니다.</li>
						<li>추가하고 싶은 파일 확장자가 있다면 <a href="http://in-web.co.kr/wordpress/plug-in/wordpress-%ec%97%85%eb%a1%9c%eb%93%9c-%ed%99%95%ec%9e%a5%ec%9e%90-%ec%b6%94%ea%b0%80-rocket-media-library-mime-type/" target="_blank">이곳</a> 에서 요청하시면 검토 후 업데이트 하겠습니다.</li>
					</ol>
					기타 플러그인에 관한 문의 및 건의사항은 <a href="http://in-web.co.kr/wordpress/plug-in/wordpress-%ec%97%85%eb%a1%9c%eb%93%9c-%ed%99%95%ec%9e%a5%ec%9e%90-%ec%b6%94%ea%b0%80-rocket-media-library-mime-type/" target="_blank"><i class="fa fa-rocket"></i>댓글</a> 달아주세요.
				</div>
			</div>
		</div>
		
	</div>
</div>