<script type="text/javascript">
$().ready(function() {
	$("input[name=market_search_address]").keypress(function(event) {
		if(event.which == 13) {
			$(".btn_search_address").trigger("click");
			return false;
		}
	});

	$(".btn_search_address").click(function() {
		var query = $("input[name=market_search_address]").val();

		$.ajax({
			type: "POST",
			url: "/admin/market/zipcode/"  + query,
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
						$("input[name=market_address1]").val(address1);

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
	<h1>market_insert</h1>
	<?php echo validation_errors(); ?>
	<?php echo form_open("admin/market/insert", array("id"=>"form_insert")); ?>
	<code>
		<ul>
			<li>매장 이름 : <input name="market_name" value="<?php echo $params->market_name;?>" /></li>
			<li>매장 정보 : <input name="market_simple_info" value="<?php echo $params->market_simple_info;?>" /></li>
			<li>
				매장 주소 검색 : <input name="market_search_address" value="<?php echo $params->market_search_address;?>" /> <input type="button" value="주소검색" class="btn_search_address" />
				<ul>
					<li>우편번호 : <input name="postcd" class="readonly" readonly="readonly" value="<?php echo $params->postcd;?>" /></li>
					<li>매장 주소1 : <input name="market_address1" class="address readonly" readonly="readonly" value="<?php echo $params->market_address1;?>" /></li>
					<li>매장 주소2(상세) : <input name="market_address2" class="address" value="<?php echo $params->market_address2;?>" /></li>
				</ul>
			</li>
			<li>기본 수수료 : <input name="default_fee" value="<?php echo $params->default_fee;?>" /> %</li>
		</ul>
		<input type="submit" value="매장 추가" />
		<div id="search_address_area"></div>
	</code>
	<input type="hidden" name="postcd" />
	</form>
</div>

