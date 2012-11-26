var option = function() {
	var insert_html = {
			add_option_group:"",
			add_option:""
	};

	var o_info = {
			insert:new Array(),
			update:{
				option_group:new Array(),
				option:new Array()
			},
			remove:{
				option_group_id:new Array(),
				option_id:new Array()
			}
	};

	function init_o_info() {
		o_info.insert = new Array();
		o_info.update.option_group = new Array();
		o_info.update.option = new Array();
		o_info.remove.option_group_id = new Array();
		o_info.remove.option_id = new Array();
	}

	// 옵션 그룹 추가 버튼 세팅
	function _set_btn_add_option_group() {
		$("#btn_add_option_group").click(function() {
			$("#option_list").append(insert_html.add_option_group);
			_set_btn_add_option($(".btn_add_option:last"));
			_set_btn_remove_option_group($(".btn_remove_option_group:last"));
			_set_btn_remove_option($(".btn_remove_option:last"));
		});
	}

	// 옵션 추가 버튼 세팅
	function _set_btn_add_option(target) {
		if(typeof(target) == "undefined") {
			target = $(".btn_add_option");
		}

		target.click(function() {
			var option_td = $(this).parent().parent().children().eq(1);
			option_td.append(insert_html.add_option);
			_set_btn_remove_option(option_td.children("p").children(".btn_remove_option:last"));
		});
	}

	// 옵션 그룹 삭제 버튼 세팅
	function _set_btn_remove_option_group(target) {
		if(typeof(target) == "undefined") {
			target = $(".btn_remove_option_group");
		}

		target.click(function() {
			var option_group_id = $(this).parent().parent().attr("option_group_id");

			if(typeof(option_group_id) != "undefined") {
				o_info.remove.option_group_id.push();
			}

			$(this).parent().parent().remove();
		});
	}

	// 옵션 삭제 버튼 세팅
	function _set_btn_remove_option(target) {
		if(typeof(target) == "undefined") {
			target = $(".btn_remove_option");
		}

		target.click(function() {
			var option_id = $(this).parent().attr("option_id");

			if(typeof(option_id) != "undefined") {
				o_info.remove.option_id.push();
			}

			$(this).parent().remove();
		});
	}

	// 옵션 적용(temp)
	function _set_btn_apply_option() {
		$("#btn_apply_option").click(function() {
			$("#option_list tr").each(function(idx) {
				if(idx == 0) return;

				if(isNaN($(this).children("td:first").attr("option_group_id"))) { // insert option group
					var option_list = new Array();
					$(this).children("td").eq(1).children("p").each(function() {
						var option_info = {
								option_name:$(this).children("input[name=option_name]").val(),
								add_price:$(this).children("input[name=add_price]").val()
						};

						option_list.push(option_info);
					});

					var insert_info = {
							option_group_name:$(this).children("td:first").children("input").val(),
							is_essential:$(this).children("td").eq(2).children("select").val(),
							max_count:$(this).children("td").eq(3).children("select").val(),
							option_list:option_list
					};

					o_info.insert.push(insert_info);
				} else { // update option group

				}
			});
		});
	}

	function _set_html() {
		insert_html.add_option_group = $("#add_option_group_row tbody").html();
		insert_html.add_option = '<p><input type="text" name="option_name" /> / <input type="text" name="add_price" />원 <button class="btn_remove_option">삭제</button></p>';
		$("#add_option_group_row").remove();
	}

	return {
		init: function() {
			_set_html();
			_set_btn_add_option_group();
			_set_btn_add_option();
			_set_btn_remove_option_group();
			_set_btn_remove_option();
			_set_btn_apply_option(); // temp
		}
	};
}();
