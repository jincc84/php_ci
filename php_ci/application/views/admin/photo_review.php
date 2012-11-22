<script type="text/javascript" src="/static/smart_editor/js/HuskyEZCreator.js" charset="utf-8"></script>
<script type="text/javascript" src="/static/js/smart_editor.js"></script>
<script type="text/javascript">
$().ready(function() {
	smart_editor.init();
});
</script>
<style type="text/css">
</style>
<div id="body">
	<h1>포토리뷰 작성</h1>
	<h3>
		[<?php echo $market_info->market_id . " / " . $market_info->market_name . " / " . $market_info->market_simple_info;?>]
		<ul>
			<li>
				주소 : <?php echo "[{$market_info->postcd}] {$market_info->market_address1} {$market_info->market_address2}";?>
			</li>
		</ul>
	</h3>
	<code>
		<form action="/admin/photo_review/<?php echo (isset($photo_review_info->photo_review_id) ? 'update' : 'insert');?>" method="post">
			<input type="hidden" name="cur_page" value="<?php echo $cur_page;?>" />
			<input type="hidden" name="market_id" value="<?php echo $market_info->market_id;?>" />
			<input type="hidden" name="photo_review_id" value="<?php echo (isset($photo_review_info->photo_review_id) ? $photo_review_info->photo_review_id : '');?>" />
			<textarea name="ir1" id="ir1" rows="10" cols="100" style="width:100%; height:412px; min-width:610px; display:none;"><?php echo isset($photo_review_info->content) ? $photo_review_info->content : '';?></textarea>
			<p><!--
				<input type="button" onclick="smart_editor.paste();" value="본문에 내용 넣기" />
				<input type="button" onclick="smart_editor.show();" value="본문 내용 가져오기" />
				<input type="button" onclick="smart_editor.submit(this);" value="서버로 내용 전송" />
				<input type="button" onclick="smart_editor.default_font();" value="기본 폰트 지정하기 (궁서_24)" />
				 -->

				 <input type="button" onclick="smart_editor.submit(this);" value="저장" />
<?php
	if(isset($photo_review_info->photo_review_id)) {
?>
				<input type="button" onclick="smart_editor.del(this);" value="삭제" />
<?php
	}
?>
			</p>
		</form>
		<form method="post" action="/admin/photo_review/delete" name="delete_content_form">
			<input type="hidden" name="photo_review_id" value="<?php echo (isset($photo_review_info->photo_review_id) ? $photo_review_info->photo_review_id : '');?>" />
			<input type="hidden" name="cur_page" value="<?php echo $cur_page;?>" />
		</form>
	</code>
</div>

