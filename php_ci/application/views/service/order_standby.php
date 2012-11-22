<script type="text/javascript">
$().ready(function() {
	$("input[name=search_address]").keypress(function(event) {
		if(event.which == 13) {
			$(".btn_search_address").trigger("click");
			return false;
		}
	});

	$(".btn_search_address").click(function() {
		var query = $("input[name=search_address]").val();

		$.ajax({
			type: "POST",
			url: "/common/utility/zipcode/"  + query,
			dataType:"json",
			success: function(data) {
				if(data == null) {
					alert("검색된 주소가 없습니다.");
				} else if(eval(data.error.is_error)) {
					alert("주소 검색 중 에러 발생");
				} else {
					$("#search_address_area").empty();
					var zipcode_list = eval(data.zipcode_list);

					$("#search_address_area").append("<ul></ul>");
					for(var i=0; i<zipcode_list.length; i++) {
						var zipcode = eval(zipcode_list[i]);

						$("#search_address_area ul").append("<li></li>");
						$("#search_address_area ul li:last").attr("postcd", zipcode.postcd).html("[" + zipcode.postcd + "] <span>" + zipcode.address + "</span>");
					}

					$("#search_address_area").show();

					$("#search_address_area ul li span").click(function() {
						var postcd = $(this).parent().attr("postcd");
						var address1 = $(this).text();

						$("input[name=postcd]").val(postcd);
						$("input[name=address1]").val(address1);

						$("#search_address_area").hide();
					});
				}
			}
		});
	});
});
</script>
<style type="text/css">
code {position:relative;}
#search_address_area {display:none; position:absolute; top:10px; left:500px; height:200px; border:1px solid black; margin:0 10px; overflow-y:scroll;}
#search_address_area ul {margin:0 10px 0 0; padding-left:20px;}
#search_address_area ul li {padding:0; margin:0;}
#search_address_area ul li span {cursor:pointer;}

.address {width:200px;}
.readonly {color:gray;}
</style>
<div id="body">
	<h1>order_standby</h1>
	<h3>주문 내역 확인</h3>
	<code>
		<ul>
<?php foreach($order_info->order_menu_list as $menu):?>
			<li>
				<?php echo $menu->count?>X<?php echo $menu->menu_name;?>		<?php echo number_format($menu->menu_option_price * $menu->count);?>원
<?php 	if(isset($menu->order_menu_option_name)):?>
				<ul>
					<li><?php echo $menu->order_menu_option_name;?></li>
				</ul>
<?php 	endif;?>
			</li>
<?php endforeach;?>
<?php if(isset($order_info->delivery_tip)):?>
			<li>배달팁 : <?php echo number_format($order_info->delivery_tip);?>원</li>
<?php endif;?>
			<li>
				총 : <?php echo number_format($order_info->order_price + $order_info->delivery_tip);?>원
			</li>
		</ul>
	</code>

	<?php echo validation_errors(); ?>
	<?php echo form_open("service/order/complete", array("id"=>"form")); ?>
	<h3>배달 주소 선택</h3>
	<code>
		<ul>
			<li>이름 : <input type="text" name="name" /></li>
			<li>
				연락처 :
				<select name="phone_no1">
					<option value="010">010</option>
					<option value="011">011</option>
					<option value="016">016</option>
					<option value="017">017</option>
					<option value="018">018</option>
					<option value="019">019</option>
				</select> -
				<input type="text" name="phone_no2" />
				<input type="text" name="phone_no3" />
			</li>
			<li>
				주소 검색 : <input name="search_address" /> <input type="button" value="주소검색" class="btn_search_address" />
				<ul>
					<li>우편번호 : <input name="postcd" class="readonly" readonly="readonly" /></li>
					<li>주소1 : <input name="address1" class="address readonly" readonly="readonly" /></li>
					<li>주소2(상세) : <input name="address2" class="address" /></li>
				</ul>
			</li>
			<li>이름 : <input type="text" name="name" /></li>
			<li>주문 요청사항 : <textarea name="order_desc"></textarea></li>
		</ul>
		<div id="search_address_area"></div>
	</code>
	</form>
</div>

