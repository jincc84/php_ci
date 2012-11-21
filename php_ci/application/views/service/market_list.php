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
				<th>매장 이미지1</th>
				<th>매장 이미지2</th>
				<th>-</th>
			</tr>
<?php	foreach($market_list as $row): ?>
			<tr>
				<td class="market_id"><?php echo $row->market_id;?></td>
				<td><?php echo $row->market_name;?></td>
				<td><?php echo $row->market_simple_info;?></td>
				<td>
<?php 	if(isset($row->img_src1)):?>
					<img src="<?php echo IMAGE_HOST . str_replace("/test_upload", "", $row->img_src1);?>" width="400" height="300" />
<?php 	endif;?>
				</td>
				<td>
<?php 	if(isset($row->img_src2)):?>
					<img src="<?php echo IMAGE_HOST . str_replace("/test_upload", "", $row->img_src2);?>" width="400" height="300" />
<?php 	endif;?>
				</td>
				<td>
					<input type="button" value="선택" class="btn_detail" />
				</td>
			</tr>
<?php	endforeach; ?>
		</table>
		<?php echo $pagination;?>
	</code>
</div>

