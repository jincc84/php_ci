<script type="text/javascript" src="/static/js/option.js"></script>
<script type="text/javascript">
$().ready(function() {
	option.init();
});
</script>
<style type="text/css">
#option_list p {padding:0; margin:5px;}
</style>
<div id="body">
	<h1>option</h1>
	<code>
		<table id="option_list">
		<tr>
			<th>옵션명(예:사이즈)</th>
			<th>항목(예:곱배기) / 추가금액</th>
			<th>선택 필수여부</th>
			<th>항목 선택</th>
			<th>-</th>
		</tr>
		<tr option_group_id="">
			<td><input type="text" name="option_group_name" origin="" /></td>
			<td>
				 <p option_id=""><input type="text" name="option_name" origin="" /> / <input type="text" name="add_price" origin="" />원 <button class="btn_remove_option">삭제</button></p>
			</td>
			<td>
				<select name="is_essential" origin="">
					<option value="Y">필수</option>
					<option value="N">선택</option>
				</select>
			</td>
			<td>
				<select name="max_count" origin="">
					<option value="1">1</option>
					<option value="2">2</option>
					<option value="3">3</option>
					<option value="4">4</option>
					<option value="5">5</option>
				</select>
			</td>
			<td>
				<button class="btn_add_option">옵션추가</button>
				<button class="btn_remove_option_group">옵션그룹삭제</button>
			</td>
		</tr>
		</table>
		<button id="btn_add_option_group">옵션그룹추가</button>
		<p>
			<button id="btn_apply_option">적용</button>
		</p>
	</code>

	<!-- 옵션그룹 추가용 html -->
	<table id="add_option_group_row" style="display:none">
		<tr>
			<td><input type="text" name="option_group_name" /></td>
			<td>
				<p><input type="text" name="option_name" /> / <input type="text" name="add_price" />원 <button class="btn_remove_option">삭제</button></p>
			</td>
			<td>
				<select name="is_essential">
					<option value="Y">필수</option>
					<option value="N">선택</option>
				</select>
			</td>
			<td>
				<select name="max_count">
					<option value="1">1</option>
					<option value="2">2</option>
					<option value="3">3</option>
					<option value="4">4</option>
					<option value="5">5</option>
				</select>
			</td>
			<td>
				<button class="btn_add_option">옵션추가</button>
				<button class="btn_remove_option_group">옵션그룹삭제</button>
			</td>
		</tr>
	</table>
</div>
