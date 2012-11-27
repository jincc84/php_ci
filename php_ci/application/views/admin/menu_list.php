<script type="text/javascript" src="/static/js/ajaxfileupload.js"></script>
<script type="text/javascript">
$().ready(function() {
	/*
		메뉴 관련 시작
	*/
	function show_area(add_menu_category_area, add_menu_area, update_menu_area) {
		$("#add_menu_category_area").css("display", add_menu_category_area ? "block" : "none");
		$("#add_menu_area").css("display", add_menu_area ? "block" : "none");
		$("#update_menu_area").css("display", update_menu_area ? "block" : "none");

		if(add_menu_area || update_menu_area) {
			$(".uploaded_menu_image_full_path").text("");
        	$(".upload_menu_image_input_area").show();
        }
	}

	$(".btn_add_menu_category").click(function() {
		show_area(true, false, false);
	});

	$(".btn_add_menu").click(function() {
		$("#add_menu_area h4 span").text($(this).parent().children("span").text());

		show_area(false, true, false);
	});

	$(".btn_insert_menu").click(function() {
		$("#insert_menu_form").submit();
	});

	$(".btn_update_menu").click(function() {
		if($("#update_menu_form input[name=delete_menu_image]").attr("checked") == "checked") {
			$("#update_menu_form input[name=menu_image_id]").val(null);
		}
		$("#update_menu_form").submit();
	});

	$(".btn_cancel_add_menu").click(function() {
		show_area(false, false, false);
		$("#update_menu_form input[name=delete_menu_image]").attr("checked", false);
		$("#insert_menu_form input[type=text]").val("");
	});

	$(".btn_delete_menu_category").click(function() {
		if(confirm("정말 삭제?")) {
			$("#delete_menu_category_form input[name=menu_category_id]").val($(this).attr("menu_category_id"));
			$("#delete_menu_category_form").submit();
		}
	});

	$(".btn_delete_menu").click(function() {
		if(confirm("정말 삭제?")) {
			$("#delete_menu_form input[name=menu_id]").val($(this).attr("menu_id"));
			$("#delete_menu_form").submit();
		}
	});

	$(".btn_show_update_menu_area").click(function() {
		$("#update_menu_form input[name=menu_id]").val($(this).parent().attr("menu_id"));
		$("#update_menu_form input[name=menu_name]").val($(this).parent().attr("menu_name"));
		$("#update_menu_form input[name=price]").val($(this).parent().attr("price"));

		if($(this).parent().children("img").attr("src")) {
			$("#update_menu_form .menu_image_full_path").text($(this).parent().children("img").attr("src"));
		} else {
			$("#update_menu_form .menu_image_full_path").text("");
		}

		show_area(false, false, true);
	});

	$(".btn_menu_detail").click(function() {
		document.location.href = "/admin/menu/detail/" + $(this).attr("market_id") + "/" + $(this).attr("menu_id");
	});

	$("input[name=btn_apply_menu_category_relation]").click(function() {
		var has_image = eval($(this).parent().attr("has_image"));
		var invalid_category_name = "";
		var menu_category_ids = new Array();
		$(this).parent().children("input[name=set_menu_category_relation]:checked").each(function() {
			if(has_image && $(this).attr("menu_category_type") != "photo") {
				invalid_category_name = $(this).next().text();
				return false;
			} else if(!has_image && $(this).attr("menu_category_type") != "normal") {
				invalid_category_name = $(this).next().text();
				return false;
			} else {
				menu_category_ids.push($(this).val());
			}
		});

		if(invalid_category_name != "") {
			alert("'" + invalid_category_name + "' 카테고리에 적용할 수 없습니다.");
			return;
		}

		var menu_category_id = "";
		for(var i=0; i<menu_category_ids.length; i++) {
			menu_category_id += menu_category_ids[i];
			if(i + 1 < menu_category_ids.length) {
				menu_category_id += ",";
			}
		}

		var menu_id = $(this).parent().attr("menu_id");
		$.ajax({
			type:"post",
			url: "/admin/market/update_menu_category_relation",
			data: {
				menu_id: menu_id,
				menu_category_id: menu_category_id
			},
			dataType: "json",
			success: function(result) {
				if(eval(result)) {
					alert("성공");
					document.location.reload();
				} else {
					alert("실패");
				}
			}
		});
	});

	$("input[name=btn_upload_menu_image]").each(function() {
		var tag = $(this);
		$(this).click(function() {
			var data = {};
			var is_update = $(this).parent().parent().parent().attr("id") == "upload_menu_image_form_update";
			if(is_update) {
				data.menu_id = $("#update_menu_form input[name=menu_id]").val();
			}

			$.ajaxFileUpload({
	                url:"/admin/menu/upload_image",
	                secureuri:false,
	                fileElementId: is_update ? "userfile_update" : "userfile_insert",
	                dataType: 'json',
	                data:data,
	                success: function (data) {
	                	var result = eval(data);

	                	if(result.result_code) {
	                    	if(is_update) {
	                    		$("#update_menu_form input[name=menu_image_id]").val(result.data.menu_image_id);
	                        } else {
	                        	$("#insert_menu_form input[name=menu_image_id]").val(result.data.menu_image_id);
							}
	                    	$(".uploaded_menu_image_full_path").text(result.data.full_path);
	                    	$(".upload_menu_image_input_area").hide();
	                    } else {
	                    	alert(result.error);
	                    }
	                },
	                error: function (data, status, e) {
	                    alert(e);
	                }
	            }
	        );
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
	/*
		메뉴 관련 끝
	*/
});
</script>
<style type="text/css">
#add_menu_category_area, #add_menu_area, #update_menu_area {display:none;}
#view_img_detail {display:none; position:absolute;}
</style>
<div id="body">
	<input type="hidden" id="market_id" value="<?php echo $market_info->market_id;?>" />
	<div id="view_img_detail"><img width="256" height="192" /></div>
	<h4>
		메뉴
		<input class="btn_add_menu_category" type="button" value="메뉴 카테고리 추가" />
		<input type="button" value="메뉴 추가" class="btn_add_menu" />
	</h4>
	<code class="code">
		<ul>
			<li>
				메뉴 카테고리 아이디 / 카테고리명
				<ul>
					<li>메뉴 아이디 / 메뉴명 / 가격</li>
				</ul>
			</li>

			<li>
				<h4>
					<span>전체 메뉴</span>
				</h4>
<?php
		if(isset($menu_list)) {
			foreach($menu_list as $menu) {
?>
				<ul>
					<li menu_id="<?php echo $menu->menu_id;?>" menu_name="<?php echo $menu->menu_name;?>" price="<?php echo $menu->price;?>" has_image="<?php echo isset($menu->menu_image_path);?>">
<?php		if(isset($menu->menu_image_path)):?>
						<img src="http://image.test.com<?php echo str_replace("/test_upload", "", $menu->menu_image_path);?>" width="25" height="19" class="menu_image" />
<?php		endif;?>
						<?php echo $menu->menu_id . " / " . $menu->menu_name . " / " . number_format($menu->price);?>
						<input type="button" class="btn_delete_menu" menu_id="<?php echo $menu->menu_id;?>" value="메뉴 삭제" />
						<input type="button" class="btn_show_update_menu_area" menu_id="<?php echo $menu->menu_id;?>" value="메뉴 수정" />
						<input type="button" class="btn_menu_detail" market_id="<?php echo $market_info->market_id;?>" menu_id="<?php echo $menu->menu_id;?>" value="상세" />
						메뉴 카테고리 :
<?php
				foreach($menu_category_list as $menu_category) {
						$checked = isset($menu_category->menu_list[$menu->menu_id]) ? "checked=checked" : "";
?>
						<input type="checkbox" name="set_menu_category_relation" value="<?php echo $menu_category->menu_category_id;?>" <?php echo $checked;?> menu_category_type="<?php echo $menu_category->menu_category_type;?>" /> <span><?php echo $menu_category->menu_category_name;?></span>
<?php
				}
?>
						<input type="button" name="btn_apply_menu_category_relation" value="카테고리 적용" />
					</li>
				</ul>
<?php
			}
		}
?>
			</li>
<?php
	foreach($menu_category_list as $menu_category) {
?>
			<li>
				<h4>
					<span><?php echo $menu_category->menu_category_id . " / " . $menu_category->menu_category_name . " / " . $menu_category->menu_category_type;?></span>
					<input type="button" class="btn_delete_menu_category" value="카테고리 삭제" menu_category_id="<?php echo $menu_category->menu_category_id;?>" />
				</h4>
<?php
		if(isset($menu_category->menu_list)) {
			foreach($menu_category->menu_list as $menu) {
?>
				<ul>
					<li menu_id="<?php echo $menu->menu_id;?>" menu_name="<?php echo $menu->menu_name;?>" price="<?php echo $menu->price;?>">
						<?php echo $menu->menu_id . " / " . $menu->menu_name . " / " . number_format($menu->price);?>
						<input type="button" class="btn_delete_menu" menu_id="<?php echo $menu->menu_id;?>" value="메뉴 삭제" />
						<input type="button" class="btn_show_update_menu_area" menu_id="<?php echo $menu->menu_id;?>" value="메뉴 수정" />
						<input type="button" class="btn_menu_detail" market_id="<?php echo $market_info->market_id;?>" menu_id="<?php echo $menu->menu_id;?>" value="상세" />
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

	<div id="add_menu_category_area">
		<h4>메뉴 카테고리 추가</h4>
		<code>
<?php echo validation_errors(); ?>
<?php echo form_open("admin/menu/insert_category", array("id"=>"menu_category_form")); ?>
			<ul>
				<li>메뉴 카테고리명 : <input type="text" name="menu_category_name" /></li>
				<li>카테고리 타입 : <input type="radio" name="menu_category_type" value="normal" checked="checked" /> 일반	<input type="radio" name="menu_category_type" value="photo" /> 사진</li>
			</ul>
			<input type="submit" value="추가" />
			<input type="button" value="취소" class="btn_cancel_add_menu" />
			<input type="hidden" name="market_id" value="<?php echo $market_info->market_id;?>" />
		</form>
		</code>
	</div>

	<div id="add_menu_area">
		<h4><span></span> 메뉴 추가</h4>
		<code>
<?php echo validation_errors(); ?>
			<ul>
				<?php echo form_open("admin/menu/insert", array("id"=>"insert_menu_form")); ?>
					<input type="hidden" name="market_id" value="<?php echo $market_info->market_id;?>" />
					<input type="hidden" name="menu_image_id" />
					<li>메뉴명 : <input type="text" name="menu_name" /></li>
					<li>가격 : <input type="text" name="price" /></li>
				</form>
				<?php echo form_open_multipart(null, array("id"=>"upload_menu_image_form_insert"));?>
					<li>
						<p class="uploaded_menu_image_full_path"></p>
						<p class="upload_menu_image_input_area">
							<input type="hidden" name="menu_id" />
							<input type="file" name="userfile" size="20" id="userfile_insert" /> <input type="button" value="upload" name="btn_upload_menu_image" />
						</p>
					</li>
				</form>
				<input type="submit" value="추가" class="btn_insert_menu" />
			<input type="button" value="취소" class="btn_cancel_add_menu" />
			</ul>
		</code>
	</div>

	<div id="update_menu_area">
		<h4><span></span> 메뉴 수정</h4>
		<code>
<?php echo validation_errors(); ?>
			<ul>
				<?php echo form_open("admin/menu/update", array("id"=>"update_menu_form")); ?>
					<input type="hidden" name="menu_id" />
					<input type="hidden" name="market_id" value="<?php echo $market_info->market_id;?>" />
					<input type="hidden" name="menu_image_id" />
					<li>메뉴명 : <input type="text" name="menu_name" /></li>
					<li>가격 : <input type="text" name="price" /></li>
					<li>
						이미지 파일 경로 : <span class="menu_image_full_path" />
					</li>
					<li>
						이미지 삭제 : <input type="checkbox" name="delete_menu_image" value="y" />
					</li>
				</form>
				<?php echo form_open_multipart(null, array("id"=>"upload_menu_image_form_update"));?>
					<li>
						<p class="uploaded_menu_image_full_path"></p>
						<p class="upload_menu_image_input_area">
							<input type="hidden" name="menu_id" />
							<input type="file" name="userfile" size="20" id="userfile_update" /> <input type="button" value="upload" name="btn_upload_menu_image" />
						</p>
					</li>
				</form>
				<input type="submit" value="수정" class="btn_update_menu" />
				<input type="button" value="취소" class="btn_cancel_add_menu" />
			</ul>
		</code>
	</div>
</div>
<?php echo form_open("admin/menu/delete", array("id"=>"delete_menu_form")); ?>
	<input type="hidden" name="market_id" value="<?php echo $market_info->market_id;?>" />
	<input type="hidden" name="menu_id" />
</form>

<?php echo form_open("admin/menu/delete_category", array("id"=>"delete_menu_category_form")); ?>
	<input type="hidden" name="market_id" value="<?php echo $market_info->market_id;?>" />
	<input type="hidden" name="menu_category_id" />
</form>