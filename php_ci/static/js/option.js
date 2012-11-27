var option = function() {
	var insert_html = {
			add_menu_option_group:"",
			add_menu_option:""
	};

	var available_option_info = {
			insert:{
				option_group:new Array(),
				option:new Array()
			},
			update:{
				option_group:new Array(),
				option:new Array()
			},
			remove:{
				option_group_id:new Array(),
				option_id:new Array()
			}
	};

	function init_available_option_info() {
		available_option_info.insert.option_group = new Array();
		available_option_info.insert.option = new Array();
		available_option_info.update.option_group = new Array();
		available_option_info.update.option = new Array();
		// 삭제된 id array list 는 계속 갖고 있어야 한다.
//		available_option_info.remove.option_group_id = new Array();
//		available_option_info.remove.option_id = new Array();
	}

	// 옵션 그룹 추가 버튼 세팅
	function _set_btn_add_menu_option_group() {
		$("#btn_add_menu_option_group").click(function() {
			$("#menu_option_list").append(insert_html.add_menu_option_group);
			_set_btn_add_menu_option($(".btn_add_menu_option:last"));
			_set_btn_remove_menu_option_group($(".btn_remove_menu_option_group:last"));
			_set_btn_remove_menu_option($(".btn_remove_menu_option:last"));
		});
	}

	// 옵션 추가 버튼 세팅
	function _set_btn_add_menu_option(target) {
		if(typeof(target) == "undefined") {
			target = $(".btn_add_menu_option");
		}

		target.click(function() {
			var option_td = $(this).parent().parent().children().eq(1);
			option_td.append(insert_html.add_menu_option);
			_set_btn_remove_menu_option(option_td.children("p").children(".btn_remove_menu_option:last"));
		});
	}

	// 옵션 그룹 삭제 버튼 세팅
	function _set_btn_remove_menu_option_group(target) {
		if(typeof(target) == "undefined") {
			target = $(".btn_remove_menu_option_group");
		}

		target.click(function() {
			var option_group_id = $(this).parent().parent().attr("menu_option_group_id");
			if(typeof(option_group_id) != "undefined") {
				available_option_info.remove.option_group_id.push(option_group_id);
			}

			$(this).parent().parent().remove();
		});
	}

	// 옵션 삭제 버튼 세팅
	function _set_btn_remove_menu_option(target) {
		if(typeof(target) == "undefined") {
			target = $(".btn_remove_menu_option");
		}

		target.click(function() {
			var option_id = $(this).parent().attr("menu_option_id");
			if(typeof(option_id) != "undefined") {
				available_option_info.remove.option_id.push(option_id);
			}

			$(this).parent().remove();
		});
	}

	/*
	 * validation check
	 * */
	function _check_input_validation() {
		var validation_result = true;
		$("input[name=add_price]").each(function() {
			if(isNaN($(this).val())) {
				validation_result = false;
			} else if(!(/(^[+-]?\d+)$/.test($(this).val()))) {
				validation_result = false;
			}

			if(!validation_result) {
				$(this).focus();
				return false;
			}
		});

		return validation_result;
	}

	// 옵션 적용(temp)
	function _set_btn_apply_menu_option() {
		$("#btn_apply_menu_option").click(function() {
			if(!_check_input_validation()) {
				alert("error!");
				return false;
			}

			init_available_option_info();
			$("#menu_option_list tr").each(function(idx) {
				if(idx == 0) return true;

				if(isNaN($(this).attr("menu_option_group_id"))) { // insert option group
					var option_list = new Array();
					$(this).children("td").eq(1).children("p").each(function() {
						var option_info = {
								menu_option_name:$(this).children("input[name=menu_option_name]").val(),
								add_price:$(this).children("input[name=add_price]").val()
						};

						option_list.push(option_info);
					});

					var insert_info = {
							menu_option_group_name:$(this).children("td").eq(0).children("input").val(),
							is_essential:$(this).children("td").eq(2).children("select").val(),
							max_select:$(this).children("td").eq(3).children("select").val(),
							option_list:option_list // insert option
					};

					available_option_info.insert.option_group.push(insert_info);
				} else { // update
					var menu_option_group_id = $(this).attr("menu_option_group_id");
					var origin_menu_option_group_info = {
							"menu_option_group_name": $(this).children("td").eq(0).children("input[name=menu_option_group_name]").attr("origin"),
							"is_essential": $(this).children("td").eq(2).children("select[name=is_essential]").attr("origin"),
							"max_select": $(this).children("td").eq(3).children("select[name=max_select]").attr("origin")
					};
					var menu_option_group_info = {
							"menu_option_group_name": $(this).children("td").eq(0).children("input[name=menu_option_group_name]").val(),
							"is_essential": $(this).children("td").eq(2).children("select[name=is_essential]").val(),
							"max_select": $(this).children("td").eq(3).children("select[name=max_select]").val()
					};

					if(origin_menu_option_group_info.menu_option_group_name != menu_option_group_info.menu_option_group_name ||
							origin_menu_option_group_info.is_essential != menu_option_group_info.is_essential ||
							origin_menu_option_group_info.max_select != menu_option_group_info.max_select) { // update option_group
						menu_option_group_info.menu_option_group_id = menu_option_group_id;
						available_option_info.update.option_group.push(menu_option_group_info);
					}

					$(this).children("td").eq(1).children("p").each(function() {
						var menu_option_id = $(this).attr("menu_option_id");
						if(isNaN(menu_option_id)) { // insert option
							var menu_option_info = {
									"menu_option_group_id": menu_option_group_id,
									"menu_option_name": $(this).children("input[name=menu_option_name]").val(),
									"add_price": $(this).children("input[name=add_price]").val()
							};

							available_option_info.insert.option.push(menu_option_info);
						} else { // update option
							var origin_menu_option_info = {
									"menu_option_name": $(this).children("input[name=menu_option_name]").attr("origin"),
									"add_price": $(this).children("input[name=add_price]").attr("origin")
							};
							var menu_option_info = {
									"menu_option_name": $(this).children("input[name=menu_option_name]").val(),
									"add_price": $(this).children("input[name=add_price]").val()
							};

							if(origin_menu_option_info.menu_option_name != menu_option_info.menu_option_name ||
									origin_menu_option_info.add_price != menu_option_info.add_price) {
								menu_option_info.menu_option_id = menu_option_id;
								available_option_info.update.option.push(menu_option_info);
							}
						}
					});
				}
			});

			$.get("/admin/menu/modify_option", {
				menu_id:$("#menu_id").val(),
				available_option_info: JSON.stringify(available_option_info)
			}, function(result) {
				alert(eval(result));
			});
		});
	}

	function _set_html() {
		insert_html.add_menu_option_group = $("#add_menu_option_group_row tbody").html();
		insert_html.add_menu_option = '<p><input type="text" name="menu_option_name" /> / <input type="text" name="add_price" />원 <button class="btn_remove_menu_option">삭제</button></p>';
		$("#add_menu_option_group_row").remove();
	}

	return {
		init: function() {
			_set_html();
			_set_btn_add_menu_option_group();
			_set_btn_add_menu_option();
			_set_btn_remove_menu_option_group();
			_set_btn_remove_menu_option();
			_set_btn_apply_menu_option(); // temp
		}
	};
}();
