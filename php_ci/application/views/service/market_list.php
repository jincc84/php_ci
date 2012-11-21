<script type="text/javascript">
$().ready(function() {
	$(".btn_detail").click(function() {
		var market_id = $(this).parent().parent().children(":first").text();
		document.location.href = "/service/market/detail/" + market_id;
	});
});
</script>
<style type="text/css">
</style>
<div id="body">
	<input type="hidden" id="cur_page" value="<?php echo $cur_page;?>" />
	<h1>market_list</h1>
	<code>
	<input type="hidden" name="market_id" />
		<table>
			<tr>
				<th>매장 아이디</th>
				<th>매장명</th>
				<th>매장 간단 정보</th>
				<th>포토 리뷰</th>
				<th>-</th>
			</tr>
<?php
	foreach($market_list as $row) {
?>
			<tr>
				<td class="market_id"><?php echo $row->market_id;?></td>
				<td><?php echo $row->market_name;?></td>
				<td><?php echo $row->market_simple_info;?></td>
				<td><?php echo $row->content;?></td>
				<td>
					<input type="button" value="선택" class="btn_detail" />
				</td>
			</tr>
<?php
	}
?>
		</table>
		<?php echo $pagination;?>
	</code>
</div>

