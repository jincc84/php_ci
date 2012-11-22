<script type="text/javascript" src="http://openapi.map.naver.com/openapi/naverMap.naver?ver=2.0&key=<?php echo $map_key;?>"></script>
<script type="text/javascript" src="/static/js/map.js" charset="utf-8"></script>
<script type="text/javascript" src="/static/js/order_temp.js" charset="utf-8"></script>
<script type="text/javascript">
$().ready(function() {
	/*
		지도 보기 관련 시작
	*/
	$("#btn_view_map").click(function() {
		if(map.is_set_map()) {
			map.toggle_map_view();
		} else {
			map.init("<?php echo $market_info->market_name;?>", eval(<?php echo $market_info->map_coord;?>), "<?php echo $market_info->map_error_code;?>");
		}

		$(this).val(map.is_show_map() ? "지도 숨기기" : "지도 보기");
	});
	/*
		지도 보기 관련 끝
	*/

	$(".menu_list_area li h4 span").each(function(idx) {
		$(this).click(function() {
			$(".menu_category_area").children("ul").hide();
			$(".menu_category_area:eq(" + idx + ")").children("ul").show();
		});
	});

	$(".menu_image").mouseover(function(event) {
		//$("#view_img_detail").css("top", event.pageY + 15);
		//$("#view_img_detail").css("left", event.pageX + 25);

		$("#view_img_detail").css("top", $(this).offset().top + $(this).height());
		$("#view_img_detail").css("left", $(this).offset().left + $(this).width());

		$("#view_img_detail img").attr("src", $(this).attr("src"));

		$("#view_img_detail").show();
	}).mouseout(function() {
		$("#view_img_detail").hide();
	});

	$(".menu_category_area:gt(0)").children("ul").hide();

	$(".menu").mouseover(function() {
		$(this).css("background-color", "#cccccc");
	}).mouseout(function() {
		$(this).css("background-color", "transparent");
	});
/*
	$(".menu").click(function() {
		$.get("/service/menu/get_option", {
			menu_id: $(this).attr("menu_id")
		}, function(result) {
			data = JSON.parse(result);

			alert(data.length);
		});
	});
*/
	Order.init();
});
</script>
<style type="text/css">
#view_img_detail {display:none; position:absolute;}
.menu_list_area li h4, .menu {cursor:pointer;}

#order_area {position:absolute; right:500px; top:150px; min_width:300px;}
#orderlist>li>span.btn {cursor:pointer;}
#option_area {display:none; position:absolute; top:150px; left:550px; width:300px; min_height:200px; border:1px solid gray; background-color:white; margin:0;}
#option_area h2 {font-size:12pt; border-bottom:1px dotted gray; margin:0; padding:5px; cursor:pointer;}
#option_area h3 {font-size:9pt; padding-left:5px;}
span.add, span.sub {font-size:12pt;}

#delivery_tip_area {display:none;}
</style>
<div id="body">
	<input type="hidden" id="market_id" value="<?php echo $market_info->market_id;?>" />
	<div id="view_img_detail"><img width="256" height="192" /></div>
	<h1>market_detail</h1>
	<h3>[<?php echo $market_info->market_id . " / " . $market_info->market_name . " / " . $market_info->market_simple_info;?>]</h3>
	<ul>
		<li>
			주소 : <?php echo "[{$market_info->postcd}] {$market_info->market_address1} {$market_info->market_address2}";?>
			<input type="button" id="btn_view_map" value="지도 보기" />
		</li>
		<div id="map"></div>
		<li>
			배달 가능 지역
			<ul>
<?php	foreach($delivery_location_list as $delivery_location): ?>
				<li>
					<?php echo $delivery_location->sido . " " . $delivery_location->gugun . " " . $delivery_location->dong;?>
				</li>
<?php	endforeach;?>
			</ul>
		</li>
		<li class="menu_list_area">
			메뉴 리스트
			<ul>
				<li class="menu_category_area">
					<h4>
						<span>전체 메뉴</span>
					</h4>
	<?php
			if(isset($menu_list)):
				foreach($menu_list as $menu):
	?>
					<ul class="menu_area">
						<li class="menu" menu_id="<?php echo $menu->menu_id;?>" menu_name="<?php echo $menu->menu_name;?>" price="<?php echo $menu->price;?>" fee="<?php echo $menu->fee;?>">
							<?php echo $menu->menu_id . " / " . $menu->menu_name . " / " . number_format($menu->price)  . "원";?>
	<?php		if(isset($menu->menu_image_path)): ?>
							<img src="<?php echo IMAGE_HOST . str_replace("/test_upload", "", $menu->menu_image_path);?>" width="25" height="19" class="menu_image" />
	<?php		endif; ?>
						</li>
					</ul>
	<?php
				endforeach;
			endif;
	?>
				</li>
<?php	foreach($menu_category_list as $menu_category): ?>
				<li class="menu_category_area">
					<h4>
						<span><?php echo $menu_category->menu_category_id . " / " . $menu_category->menu_category_name . " / " . $menu_category->menu_category_type;?></span>
					</h4>
	<?php
				if(isset($menu_category->menu_list)):
					foreach($menu_category->menu_list as $menu):
	?>
					<ul class="menu_area">
						<li class="menu" menu_id="<?php echo $menu->menu_id;?>" menu_name="<?php echo $menu->menu_name;?>" price="<?php echo $menu->price;?>" fee="<?php echo $menu->fee;?>">
							<?php echo $menu->menu_id . " / " . $menu->menu_name . " / " . number_format($menu->price)  . "원";?>
	<?php		if(isset($menu->menu_image_path)): ?>
							<img src="<?php echo IMAGE_HOST . str_replace("/test_upload", "", $menu->menu_image_path);?>" width="25" height="19" class="menu_image" />
	<?php		endif; ?>
						</li>
					</ul>
	<?php
					endforeach;
				endif;
	?>
				</li>
<?php	endforeach;?>
			</ul>
		</li>
	</ul>

	<code id="order_area">
		<ul id="orderlist"></ul>
		<ul id="delivery_tip_area">
			<li>배달팁 : <span><?php echo number_format($market_info->delivery_tip);?></span>원</li>
		</ul>
		<p>
			total price: <span id="total_price">0</span>원
		</p>
	</code>
	<div id="option_area">
		<h2 id="menu_name" onclick="Order.close();"></h2>
		<p id="optionlist_area"></p>
		<p>
			주문금액 : <span id="order_price">0</span>원 <input type="button"
				value="add order" onclick="Order.set_option();" />
		</p>
	</div>
</div>
