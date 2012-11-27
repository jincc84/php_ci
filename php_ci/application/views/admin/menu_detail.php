<script type="text/javascript" src="/static/js/option.js"></script>
<script type="text/javascript">
$().ready(function() {
	option.init();
});
</script>
<style type="text/css">
#menu_option_list p {padding:0; margin:5px;}
#add_menu_option_group_row {display:none;}
</style>
<div id="body">
	<h1>menu_detail</h1>
	<h3>[<?php echo $market_info->market_id . " / " . $market_info->market_name . " / " . $market_info->market_simple_info;?>]</h3>
	<h3>[<?php echo $menu_info->menu_id . " / " . $menu_info->menu_name . " / " . $menu_info->price;?>]</h3>
	<h4>메뉴 옵션 그룹</h4>
	<code>
		<table id="menu_option_list">
		<tr>
			<th>옵션명(예:사이즈)</th>
			<th>항목(예:곱배기) / 추가금액</th>
			<th>선택 필수여부</th>
			<th>항목 선택</th>
			<th>-</th>
		</tr>
<?php foreach($menu_option_group_list as $menu_option_group):?>
		<tr menu_option_group_id="<?php echo $menu_option_group->menu_option_group_id;?>">
			<td><input type="text" name="menu_option_group_name" origin="<?php echo $menu_option_group->menu_option_group_name;?>" value="<?php echo $menu_option_group->menu_option_group_name;?>" /></td>
			<td>
<?php 	foreach($menu_option_group->menu_option_list as $menu_option):?>
				 <p menu_option_id="<?php echo $menu_option->menu_option_id;?>"><input type="text" name="menu_option_name" origin="<?php echo $menu_option->menu_option_name;?>" value="<?php echo $menu_option->menu_option_name;?>" /> / <input type="text" name="add_price" origin="<?php echo $menu_option->add_price;?>" value="<?php echo $menu_option->add_price;?>" />원 <button class="btn_remove_menu_option">삭제</button></p>
<?php 	endforeach;?>
			</td>
			<td>
				<select name="is_essential" origin="<?php echo $menu_option_group->is_essential;?>">
					<option value="Y" <?php echo $menu_option_group->is_essential == "Y" ? "selected=selected" : "";?>>필수</option>
					<option value="N" <?php echo $menu_option_group->is_essential == "N" ? "selected=selected" : "";?>>선택</option>
				</select>
			</td>
			<td>
				<select name="max_select" origin="<?php echo $menu_option_group->max_select;?>">
					<option value="1" <?php echo $menu_option_group->max_select == 1 ? "selected=selected" : "";?>>1</option>
					<option value="2" <?php echo $menu_option_group->max_select == 2 ? "selected=selected" : "";?>>2</option>
					<option value="3" <?php echo $menu_option_group->max_select == 3 ? "selected=selected" : "";?>>3</option>
					<option value="4" <?php echo $menu_option_group->max_select == 4 ? "selected=selected" : "";?>>4</option>
					<option value="5" <?php echo $menu_option_group->max_select == 5 ? "selected=selected" : "";?>>5</option>
				</select>
			</td>
			<td>
				<button class="btn_add_menu_option">옵션추가</button>
				<button class="btn_remove_menu_option_group">옵션그룹삭제</button>
			</td>
		</tr>
<?php endforeach;?>
		</table>
		<button id="btn_add_menu_option_group">옵션그룹추가</button>
		<p>
			<button id="btn_apply_menu_option">적용</button>
		</p>
	</code>

	<!-- 옵션그룹 추가용 html -->
	<table id="add_menu_option_group_row">
		<tr>
			<td><input type="text" name="menu_option_group_name" /></td>
			<td>
				<p><input type="text" name="menu_option_name" /> / <input type="text" name="add_price" />원 <button class="btn_remove_menu_option">삭제</button></p>
			</td>
			<td>
				<select name="is_essential">
					<option value="Y">필수</option>
					<option value="N">선택</option>
				</select>
			</td>
			<td>
				<select name="max_select">
					<option value="1">1</option>
					<option value="2">2</option>
					<option value="3">3</option>
					<option value="4">4</option>
					<option value="5">5</option>
				</select>
			</td>
			<td>
				<button class="btn_add_menu_option">옵션추가</button>
				<button class="btn_remove_menu_option_group">옵션그룹삭제</button>
			</td>
		</tr>
	</table>
</div>
<input type="hidden" id="menu_id" name="menu_id" value="<?php echo $menu_info->menu_id;?>" />