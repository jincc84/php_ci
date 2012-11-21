<script type="text/javascript">
$().ready(function() {
	$(".btn_delete").click(function() {
		if(confirm("정말 삭제?")) {
			var market_id = $(this).parent().parent().children(":first").text();
			$("input[name=market_id]").val(market_id);
			$("#form_delete").submit();
		}
	});

	$(".btn_detail").click(function() {
		var market_id = $(this).parent().parent().children(":first").text();
		document.location.href = "/market/market/detail/" + market_id;
	});

	$(".btn_photo_review").click(function() {
		var market_id = $(this).parent().parent().children(":first").text();
		document.location.href = "/market/photo_review/pre/" + market_id + "?cur_page=" + $("#cur_page").val();
	});
});
</script>
<style type="text/css">
</style>
<div id="body">
	<input type="hidden" id="cur_page" value="<?php echo $cur_page;?>" />
	<h1>market_list</h1>
	<code>
<?php echo validation_errors(); ?>
<?php echo form_open("market/market/delete", array("id"=>"form_delete")); ?>
	<input type="hidden" name="market_id" />
		<table>
			<tr>
				<th>매장 아이디</th>
				<th>매장명</th>
				<th>매장 간단 정보</th>
				<th>포토 리뷰 작성 여부</th>
				<th>-</th>
			</tr>
<?php
	foreach($market_list as $row) {
?>
			<tr>
				<td class="market_id"><?php echo $row->market_id;?></td>
				<td><?php echo $row->market_name;?></td>
				<td><?php echo $row->market_simple_info;?></td>
				<td><?php echo isset($row->photo_review_id) ? "O" : "X";?></td>
				<td>
					<input type="button" value="포토 리뷰" class="btn_photo_review" />
					<input type="button" value="상세" class="btn_detail" />
					<input type="button" value="매장 삭제" class="btn_delete" />
				</td>
			</tr>
<?php
	}
?>
		</table>
		</form>
		<?php echo $pagination;?>
	</code>
</div>

