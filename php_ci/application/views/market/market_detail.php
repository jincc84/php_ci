<script type="text/javascript" src="http://openapi.map.naver.com/openapi/naverMap.naver?ver=2.0&key=<?php echo $map_key;?>"></script>
<script type="text/javascript" src="/static/js/map.js" charset="utf-8"></script>
<script type="text/javascript">
$().ready(function() {
	$(".code:last").each(function() {
		$(this).prev().children("button").text("닫기");
		$(this).show();
	});

	$("button").click(function() {
		var button = $(this);
		$(this).parent().next().toggle(function() {
			button.text($(this).is(":visible") ? "닫기" : "열기");
		});
	});

	/*
		배달 가능 지역 관련 스크립트 시작
	*/
	$("select[name=sido]").change(function() {
		var address_sido_id = $(this).val();

		$.ajax({
			url:"/admin/address/search_gugun/" + address_sido_id,
			dataType:"json",
			success:function(data) {
				data = eval(data);
				var select_gugun = $("select[name=gugun]");
				select_gugun.empty();
				$(".dong ul").empty();
				select_gugun.append("<option value='0'>선택</option>");
				for(var i=0; i<data.length; i++) {
					select_gugun.append("<option>" + data[i].gugun + "</option>");
					select_gugun.children(":last").val(data[i].address_gugun_id);
				}
			}
		});
	});

	$("select[name=gugun]").change(function() {
		var address_gugun_id = $(this).val();

		$.ajax({
			url:"/admin/address/search_dong/" + address_gugun_id,
			dataType:"json",
			success:function(data) {
				data = eval(data);

				var dong_list = $(".dong ul");
				dong_list.empty();
				for(var i=0; i<data.length; i++) {
					dong_list.append("<li></li>");
					dong_list.children(":last").append('<input type="checkbox" name="dong" value="' + data[i].address_dong_id + '" /> ' + "[" + data[i].address_dong_id + "]" +  data[i].dong);
					if(data[i].delivery_location_id != null) {
						dong_list.children(":last").children("input").attr("checked", "checked");
					}
				}

				$(".dong input[name=dong]").click(function() {
					$(".dong ul").attr("selected_dong", "");
					$("input[name=dong]:checked").each(function() {
						$(".dong ul").attr("selected_dong", dong_list.attr("selected_dong") + $(this).val() + ",");
					});
				});
			}
		});
	});

	$(".btn_update_delivery_location").click(function() {
		var selected_dong = $(".dong ul").attr("selected_dong");
		if(selected_dong.length > 0) {
			selected_dong = selected_dong.substr(0, selected_dong.length -1);
		}

 		$.ajax({
 	 		type:"post",
			url: "/admin/market/update_delivery_location/" + $("#market_id").val(),
			dataType:"json",
			data:{
				address_gugun_id:$("select[name=gugun]").val(),
				dong:selected_dong
			},
			success:function(result) {
				result = eval(result);
				if(result.result_code) {
					$(".delivery_location_area").empty();
					$(".delivery_location_area").text("배달 가능 지역 : ");
					for(var i=0; i<result.delivery_location_list.length; i++) {
						var delivery_location = result.delivery_location_list[i];
						$(".delivery_location_area").append("<span></span>\t");
						$(".delivery_location_area").children(":last")
							.addClass("delivery_location")
							.attr("address_dong_id", delivery_location.address_dong_id)
							.html(delivery_location.sido + " " + delivery_location.gugun + " " + delivery_location.dong + " <span>X</span>");

						$(".delivery_location_area").children(":last").children("span").click(function() {
							delete_delivery_location($(this));
						});
					}

					alert("완료");
				} else {
					alert("배달 지역 추가 실패");
				}
			}
		});
	});

	function delete_delivery_location(_this) {
		var address_dong_id = _this.parent().attr("address_dong_id");
		$.ajax({
			type:"post",
			url:"/admin/market/delete_delivery_location/" + $("#market_id").val() + "/",
			data:{
				address_dong_id:address_dong_id
			},
			dataType:"json",
			success:function(result) {
				if(eval(result)) {
					$(".delivery_location[address_dong_id=" + address_dong_id + "]").remove();
					$("input[name=dong]:checked").each(function() {
						if($(this).val() == address_dong_id) {
							$(this).attr("checked", false);
						}
					});

					alert("삭제 성공");
				} else {
					alert("삭제 실패");
				}
			}
		});
	}
	$(".delivery_location span").click(function() {
		delete_delivery_location($(this));
	});
	/*
		배달 가능 지역 관련 스크립트 끝
	*/

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

	/*
		이미지 관련 시작
	*/
	$(".btn_insert_image").click(function() {
		var area = $(this).attr("market_image_area");
		var image_count = $(".market_image_" + area + "_list li").length;

		$("#insert_image_area input[name=market_image_area]").val(area);
		$("#insert_image_area").show();
	});

	$(".market_image_list img").mouseover(function(event) {
			//$("#view_img_detail").css("top", event.pageY + 15);
			//$("#view_img_detail").css("left", event.pageX + 25);

			$("#view_img_detail").css("top", $(this).offset().top + $(this).height());
			$("#view_img_detail").css("left", $(this).offset().left + $(this).width());

			$("#view_img_detail img").attr("src", $(this).attr("src"));

			$("#view_img_detail").show();
		}).mouseout(function() {
			$("#view_img_detail").hide();
		});

	$(".btn_set_order_image").click(function() {
		var market_id = $("#market_id").val();
		var market_image_area = $(this).attr("market_image_area");

		$.ajax({
//			type: "POST",
			url: "/admin/market/update_market_image_order/"  + market_id + "/" + market_image_area,
			dataType:"json",
			success: function(data) {
				data = eval(data);
				if(eval(data.result_code)) {
					$(".market_image_" + market_image_area).each(function() {
						$(this).children("span.order").text($(this).children("span.pre_order").text());
					});

					alert("순서 적용 완료!");
				} else {
					alert("순서 적용 실패!");
				}
			}
		});
	});

	function click_image_order(type, _this) {
		var market_image_area = _this.parent().parent().attr("market_image_area");
		var click = _this.parent();
		var pre_order = click.attr("pre_order");
		if((type == "down" && pre_order >= $(".market_image_" + market_image_area).length) ||
				(type == "up" && pre_order <= 1)) {
			return;
		}

		$.ajax({
			url: "/admin/market/market_image_pre_order/" + type + "/" + click.attr("market_image_id"),
			dataType: "json",
			success: function(data) {
				if(eval(data.result_code)) {
					var calculate_value = (type == "down" ? 1 : -1);
					click.attr("pre_order", parseInt(pre_order) + calculate_value);
					click.children("span.pre_order").text(click.attr("pre_order"));
					click.remove();

					$(".market_image_" + market_image_area).each(function() {
						if($(this).attr("pre_order") == parseInt(pre_order) + calculate_value) {
							$(this).attr("pre_order", parseInt($(this).attr("pre_order")) + (calculate_value * -1));
							$(this).children("span.pre_order").text(parseInt($(this).attr("pre_order")));

							var target;
							if(calculate_value == 1) {
								$(this).after("<li>" + click.html() + "</li>");
								target = $(this).next();
							} else {
								$(this).before("<li>" + click.html() + "</li>");
								target = $(this).prev();
							}

							target.addClass("market_image_" + market_image_area).attr("pre_order", click.attr("pre_order")).attr("market_image_id", click.attr("market_image_id"));
							target.children("span.down_image_order").click(function() {
								click_image_order("down", $(this));
							});
							target.children("span.up_image_order").click(function() {
								click_image_order("up", $(this));
							});
						}
					});

					set_market_image_list_background(market_image_area);
				} else {
					alert("에러 발생");
				}
			}
		});
	}

	function set_market_image_list_background(market_image_area) {
		var market_image_max_count_list = eval(<?php echo json_encode($market_image_max_count_list);?>);
		var visible_count = market_image_max_count_list[market_image_area];

		$(".market_image_" + market_image_area + "_list li").each(function(index) {
			if(index < visible_count) {
				$(this).css("background-color", "#cccccc");
			} else {
				$(this).css("background-color", "transparent");
			}
		});
	}

	$(".down_image_order").click(function() {
		click_image_order("down", $(this));
	});

	$(".up_image_order").click(function() {
		click_image_order("up", $(this));
	});

	set_market_image_list_background("main");
	set_market_image_list_background("list");
	set_market_image_list_background("detail");
	/*
		이미지 관련 끝
	*/
});
</script>
<style type="text/css">
#insert_image_area {display:none; border:1px solid black; width:400px; height:100px;}
#view_img_detail {display:none; position:absolute;}

.btn_order_change, .delivery_location span {cursor:pointer;}
.code {display:none;}
</style>
<div id="body">
	<input type="hidden" id="market_id" value="<?php echo $market_info->market_id;?>" />
	<div id="view_img_detail"><img width="256" height="192" /></div>
	<h1>market_detail</h1>
	<h3>
		[<?php echo $market_info->market_id . " / " . $market_info->market_name . " / " . $market_info->market_simple_info;?>]
		<ul>
			<li>
				주소 : <?php echo "[{$market_info->postcd}] {$market_info->market_address1} {$market_info->market_address2}";?>
				<input type="button" id="btn_view_map" value="지도 보기" />
			</li>
		</ul>
	</h3>
	<div id="map"></div>

	<h4>
		배달 가능 지역 선택
		<button class="btn_toggle_area">열기</button>
	</h4>
	<code class="code">
		<ul>
			<li class="delivery_location_area">
				배달 가능 지역 :
<?php
			foreach($delivery_location_list as $delivery_location):
?>
			<span class="delivery_location" address_dong_id="<?php echo $delivery_location->address_dong_id;?>">
				<?php echo $delivery_location->sido . " " . $delivery_location->gugun . " " . $delivery_location->dong;?>
				<span>X</span>
			</span>
<?php	endforeach;?>
			</li>
			<li>
				시도 :
				<select name="sido">
					<option value="0">선택</option>
<?php	foreach($market_delivery_location_sido_list as $market_delivery_location_sido):
?>
					<option value="<?php echo $market_delivery_location_sido->address_sido_id;?>"><?php echo $market_delivery_location_sido->sido;?></option>
<?php	endforeach;?>
					</select>
				구군 :
				<select name="gugun">
					<option value="0">선택</option>
				</select>
			</li>
			<div class="dong">
				<ul></ul>
			</div>
			<input class="btn_update_delivery_location" type="submit" value="배달 지역 변경" />
		</ul>
	</code>

	<h4>
		이미지 추가
		<button class="btn_toggle_area">열기</button>
	</h4>
	<code class="code">
		<ul>
			<h4>
				메인 이미지
				<input class="btn_insert_image" type="button" value="등록" market_image_area="main" />
				<input class="btn_set_order_image" type="button" value="순서 적용" market_image_area="main" />
			</h4>
			<ul class="market_image_list market_image_main_list" market_image_area="main">
<?php	foreach($market_image_main_list as $market_image):?>
				<li class="market_image_main" pre_order="<?php echo $market_image->image_pre_order;?>" market_image_id="<?php echo $market_image->market_image_id;?>">
					<span class="order"><?php echo (!$market_image->image_order) ? "-" : $market_image->image_order;?></span> /
					<span class="pre_order"><?php echo $market_image->image_pre_order;?></span> <span class="down_image_order btn_order_change">↓</span> <span class="up_image_order btn_order_change">↑</span> /
					<img src="http://image.test.com<?php echo str_replace("/test_upload", "", $market_image->file_path) . $market_image->file_name;?>" width="25" height="19" />
				</li>
<?php	endforeach;?>
			</ul>
		</ul>

		<ul>
			<h4>
				리스트 이미지
				<input class="btn_insert_image" type="button" value="등록" market_image_area="list" />
				<input class="btn_set_order_image" type="button" value="순서 적용" market_image_area="list" />
			</h4>
			<ul class="market_image_list market_image_list_list" market_image_area="list">
<?php	foreach($market_image_list_list as $market_image):?>
				<li class="market_image_list" pre_order="<?php echo $market_image->image_pre_order;?>" market_image_id="<?php echo $market_image->market_image_id;?>">
					<span class="order"><?php echo (!$market_image->image_order) ? "-" : $market_image->image_order;?></span> /
					<span class="pre_order"><?php echo $market_image->image_pre_order;?></span> <span class="down_image_order btn_order_change">↓</span> <span class="up_image_order btn_order_change">↑</span> /
					<img src="http://image.test.com<?php echo str_replace("/test_upload", "", $market_image->file_path) . $market_image->file_name;?>" width="25" height="19" />
				</li>
<?php	endforeach;?>
			</ul>
		</ul>

		<ul>
			<h4>
				상세 이미지
				<input class="btn_insert_image" type="button" value="등록" market_image_area="detail" />
			</h4>
			<ul class="market_image_list market_image_detail_list" market_image_area="detail">
<?php	foreach($market_image_detail_list as $market_image):?>
				<li class="market_image_detail" pre_order="<?php echo $market_image->image_pre_order;?>" market_image_id="<?php echo $market_image->market_image_id;?>">
					<span class="order"><?php echo (!$market_image->image_order) ? "-" : $market_image->image_order;?></span> /
					<span class="pre_order"><?php echo $market_image->image_pre_order;?></span> <span class="down_image_order btn_order_change">↓</span> <span class="up_image_order btn_order_change">↑</span> /
					<img src="http://image.test.com<?php echo str_replace("/test_upload", "", $market_image->file_path) . $market_image->file_name;?>" width="25" height="19" />
				</li>
<?php	endforeach;?>
			</ul>
		</ul>
	</code>

	<div id="insert_image_area">
		<?php echo form_open_multipart('admin/market/upload_image');?>
			<input type="hidden" name="market_id" value="<?php echo $market_info->market_id;?>" />
			<input type="hidden" name="market_image_area" />
			<input type="file" name="userfile" size="20" /><br />
			<input type="submit" value="upload" />
		</form>
	</div>

	<h4>
		메뉴 관련 정보 <button onclick='document.location.href="/admin/menu/lists/<?php echo $market_info->market_id;?>";'>Go</button>
	</h4>
</div>
