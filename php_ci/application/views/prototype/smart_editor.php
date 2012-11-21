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
	<h1>smart_editor</h1>
	<code>
		<form action="sample.php" method="post">
			<textarea name="ir1" id="ir1" rows="10" cols="100" style="width:100%; height:412px; min-width:610px; display:none;"></textarea>
			<p>
				<input type="button" onclick="smart_editor.paste();" value="본문에 내용 넣기" />
				<input type="button" onclick="smart_editor.show();" value="본문 내용 가져오기" />
				<input type="button" onclick="smart_editor.submit(this);" value="서버로 내용 전송" />
				<input type="button" onclick="smart_editor.default_font();" value="기본 폰트 지정하기 (궁서_24)" />
			</p>
		</form>
	</code>
</div>

