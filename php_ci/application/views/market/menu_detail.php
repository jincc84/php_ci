<script type="text/javascript">
$().ready(function() {
	$(".btn_add_menu_option").click(function() {
		$("#add_menu_option_area h4 span").text($(this).parent().children("span").text());
		$("#insert_menu_option_form input[name=menu_option_group_id]").val($(this).parent().parent().attr("menu_option_group_id"));

		$("#add_menu_option_group_area").hide();
		$("#add_menu_option_area").show();
	});

	$(".btn_delete_menu_option_group").click(function() {
		var area = $(this).parent().parent();
		$.ajax({
			url:"/market/menu_option/delete_group/" + area.attr("menu_option_group_id"),
			dataType:"json",
			success:function(result) {
				if(eval(result)) {
					area.remove();
				} else {
					alert("옵션 그룹 삭제 실패");
				}
			}
		});
	});

	$("#btn_cancel_add_menu_option").click(function() {
		$("#add_menu_option_area").hide();
		$("#insert_menu_option_form input[type=text]").val("");
		$("#add_menu_option_group_area").show();
	});

	$(".btn_delete_menu_option").click(function() {
		$("#delete_menu_option_form input[name=menu_option_id]").val($(this).attr("menu_option_id"));
		$("#delete_menu_option_form").submit();
	});
});
</script>
<style type="text/css">
#add_menu_option_area {display:none;}
</style>
<div id="body">
	<h1>menu_detail</h1>
	<h3>[<?php echo $market_info->market_id . " / " . $market_info->market_name . " / " . $market_info->market_simple_info;?>]</h3>
	<h3>[<?php echo $menu_info->menu_id . " / " . $menu_info->menu_name . " / " . $menu_info->price;?>]</h3>
	<h4>메뉴 옵션 그룹</h4>
	<code>
		<ul>
			<li>
				옵션 그룹 아이디 / 옵션 그룹명 / 필수 여부 / 최대 선택 수
				<ul>
					<li>옵션 아이디 / 옵션명 / 추가 가격</li>
				</ul>
			</li>
<?php
	foreach($menu_option_group_list as $menu_option_group) {
?>
			<li class="menu_option_group_area" menu_option_group_id="<?php echo $menu_option_group->menu_option_group_id;?>">
				<h4>
					<span><?php echo $menu_option_group->menu_option_group_id . " / " . $menu_option_group->menu_option_group_name . " / " . $menu_option_group->is_essential . " / " . $menu_option_group->max_select;?></span>
					<input type="button" value="옵션 추가" class="btn_add_menu_option" />
					<input type="button" value="옵션 그룹 삭제" class="btn_delete_menu_option_group" />
				</h4>
<?php
		if(isset($menu_option_group->menu_option_list)) {
			foreach($menu_option_group->menu_option_list as $menu_option) {
?>
				<ul>
					<li>
						<?php echo $menu_option->menu_option_id . " / " . $menu_option->menu_option_name . " / " . number_format($menu_option->add_price);?>
						<input type="button" class="btn_delete_menu_option" menu_option_id="<?php echo $menu_option->menu_option_id;?>" value="옵션 삭제" />
					</li>
				</ul>
<?php
			}
		}
?>
			</li>
<?php
	}
?>
		</ul>
	</code>
	<div id="add_menu_option_group_area">
		<h4>옵션 그룹 추가</h4>
		<code>
<?php echo validation_errors(); ?>
<?php echo form_open("market/menu_option/insert_group", array("id"=>"menu_option_group_form")); ?>
			<ul>
				<li>옵션 그룹명 : <input type="text" name="menu_option_group_name" /></li>
				<li>필수 여부 : <input type="checkbox" name="is_essential" /></li>
				<li>최대 선택 개수 : <input type="text" name="max_select" /></li>
			</ul>
			<input type="submit" value="추가" />
			<input type="hidden" name="market_id" value="<?php echo $market_info->market_id;?>" />
			<input type="hidden" name="menu_id" value="<?php echo $menu_info->menu_id;?>" />
		</form>
		</code>
	</div>
	<div id="add_menu_option_area">
		<h4><span></span> 옵션 추가</h4>
		<code>
<?php echo validation_errors(); ?>
<?php echo form_open("market/menu_option/insert", array("id"=>"insert_menu_option_form")); ?>
			<ul>
				<li>옵션명 : <input type="text" name="menu_option_name" /></li>
				<li>추가 가격 : <input type="text" name="add_price" /></li>
			</ul>
			<input type="submit" value="추가" />
			<input type="button" value="취소" id="btn_cancel_add_menu_option" />
			<input type="hidden" name="market_id" value="<?php echo $market_info->market_id;?>" />
			<input type="hidden" name="menu_id" value="<?php echo $menu_info->menu_id;?>" />
			<input type="hidden" name="menu_option_group_id" />
		</form>
		</code>
	</div>
</div>
<?php echo form_open("market/menu_option/delete", array("id"=>"delete_menu_option_form")); ?>
	<input type="hidden" name="market_id" value="<?php echo $market_info->market_id;?>" />
	<input type="hidden" name="menu_id" value="<?php echo $menu_info->menu_id;?>" />
	<input type="hidden" name="menu_option_id" />
</form>