var Order = function() {
	// menu 정보
	var menus = [
	             {menu_id:1, name:"menu1", price:5000,
	            	 options: [
	            	 	{option_group_id:1, name:"패티선택", is_essential:true, max_select:1,
	            	 		option_list:[
	            	 		             {option_id:1, name:"호주산 청정우", add_price:0},
	            	 		             {option_id:2, name:"한우", add_price:2000},
	            	 		             {option_id:3, name:"닭가슴살", add_price:-1000}]},
	            	    {option_group_id:2, name:"빵선택", is_essential:false, max_select:1,
	            	 		option_list:[
	            	 		             {option_id:4, name:"번", add_price:0},
	            	 		             {option_id:5, name:"호밀샌드위치", add_price:500}]},
	            	 	{option_group_id:3, name:"토핑추가", is_essential:false, max_select:2,
	            	    	option_list:[
	            	    	             {option_id:6, name:"계란후라이", add_price:500},
	            	    	             {option_id:7, name:"베이컨2줄", add_price:1000},
	            	    	             {option_id:8, name:"치즈", add_price:500},
	            	    	             {option_id:9, name:"야채", add_price:900},
	            	    	             {option_id:10, name:"닭가슴살", add_price:2000}]},
	            	    {option_group_id:4, name:"소스추가", is_essential:false, max_select:1,
	            	    	option_list:[
	            	    	             {option_id:11, name:"케찹", add_price:0},
	            	    	             {option_id:12, name:"바베큐소스", add_price:0}]}
	            	 ]},
	             {menu_id:2, name:"menu2", price:3000},
	             {menu_id:3, name:"menu3", price:2000},
	             {menu_id:4, name:"menu4", price:7500},
	];

	// 주문 정보
	var orders = {
			max_order_count:10,
			temp_order_id:0,
			order_list:[]
	};

	function _set_menu() {
		// 메뉴 세팅
		for(var i=0; i<menus.length; i++) {
			var menu = menus[i];

			var li = "<li>" + menu.name + "\t" + menu.price + "원</li>";
			var has_options = (typeof(menu.options) != "undefined");
			$("#menulist").append(li);
			$("#menulist").children("li:last").attr("menu_id", menu.menu_id).attr("name", menu.name).attr("price", menu.price).attr("has_options", has_options);

		}

		// 메뉴 리스트 클릭 시 주문 혹은 상세 옵션 입력하도록 click 이벤트 추가
		$("#menulist>li").each(function() {
			$(this).click(function() {
				if(orders.order_list.length < orders.max_order_count) {
					if(eval($(this).attr("has_options"))) {
						_set_options($(this));
					} else {
						_set_order(_get_menu_info($(this).attr("menu_id")));
					}
				} else {
					alert("주문은 최대 " + orders.max_order_count + "개까지 가능합니다.");
				}
			}).css("cursor", "pointer");
		});
	}

	/**
	 * 메뉴 정보 return
	 * @param menu_id menu_id
	 */
	function _get_menu_info(menu_id) {
		var idx = -1;
		for(var i=0; i<menus.length; i++) {
			var menu = menus[i];
			if(menu.menu_id == menu_id) {
				idx = i;
				break;
			}
		}

		return (idx < 0 ? null : menus[idx]);
	}

	/**
	 * 옵션 레이어 세팅
	 * @param menu 메뉴리스트의 메뉴 jquery instance
	 */
	function _set_options(menu) {
		_empty_option_data();

		var menu_id = menu.attr("menu_id");
		var _menu = _get_menu_info(menu_id);
		$("#optionlist_area").attr("menu_id", menu_id);

		for(var i=0; i<_menu.options.length; i++) {
			var option = _menu.options[i];

			// option 타이틀 세팅
			var option_info = {
					sub_title:(option.is_essential ? "필수" : "선택"),
					input_type:(option.is_essential ? "radio" : "checkbox"),
			};

			if(option.max_select > 1) {
				option_info.sub_title += ", 최대" + option.max_select + "개까지";
			}
			var h3 = "<h3>" + option.name + "(" + option_info.sub_title + ")</h3>";
			$("#optionlist_area").append(h3);

			// option 세팅
			$("#optionlist_area").append("<ul></ul>");
			$("#optionlist_area").children("ul:last").attr("max_select", option.max_select).attr("is_essential", option.is_essential);
			for(var j=0; j<option.option_list.length; j++) {
				var detail_option = option.option_list[j];
				var li = "<li>" +
					"<input type='" + option_info.input_type + "' />" +
					detail_option.name + (detail_option.add_price != 0 ? "(" + detail_option.add_price + ")" : "") +
					"</li>";

				$("#optionlist_area ul:last").append(li);
				$("#optionlist_area ul:last li:last input")
					.attr("name", "option_group_" + option.option_group_id)
					.attr("option_group_id", option.option_group_id)
					.attr("option_id", detail_option.option_id)
					.attr("add_price", detail_option.add_price)
					.attr("option_name", detail_option.name)
					.click(_option_check(menu));
			}
		}

		$("#order_price").text(menu.attr("price"));
		$("#menu_name").text(menu.attr("name"));
		$("#option_area").show();
	}

	/**
	 * option 선택했을 경우 최대 선택 가능 개수 체크 로직
	 * @param menu 메뉴리스트의 메뉴 jquery instance
	 */
	function _option_check(menu) {
		$("#optionlist_area input").click(function() {
			var input_name = $(this).attr("name");
			var max_select = $(this).parent().parent().attr("max_select");
			var checked_count = 0;
			$("input[name='" + input_name + "']:checked").each(function() {
				checked_count++;
			});

			// click 이벤트 별 최대 옵션 개수 체크 로직
			if(checked_count > max_select) {
				alert("이 옵션은 최대 " + max_select + "개까지 선택 가능합니다.");
				$(this).attr("checked", false);
			} else {
				var price = eval(menu.attr("price"));
				var option_price = 0;
				$("#optionlist_area input:checked").each(function() {
					option_price += eval($(this).attr("add_price"));
				});

				$("#order_price").text(price + option_price);
			}
		});
	}

	/**
	 * 주문 추가
	 * @param menu 메뉴리스트의 메뉴 json
	 * @param select_options [optional]Array 선택한 options 정보(menu_id, option_group_id, option_id, option_name)
	 */
	function _set_order(menu, select_options) {
		// 메뉴 추가
		var li = "<li><span class='menu_name'></span>\t" +
					"<span class='options'></span>\t" +
					"<span class='price'></span>원\t" +
					"<span class='count'>1</span>개\t" +
					"<span class='btn add'>+</span>\t" +
					"<span class='btn sub'>-</span></li>";

		$("#orderlist").append(li);

		var order_info = {
				order_id:orders.temp_order_id,
				menu_id:menu.menu_id,
				price : menu.price,
				count:1,
				option_list:[]
		};

		var option_names = "";
		if(typeof(select_options) != "undefined") {
			for(var i=0; i<select_options.length; i++) {
				var select_option = select_options[i];
				option_names += select_option.option_name + ",";
				order_info.price += eval(select_option.add_price);

				order_info.option_list.push(select_option);
			}
		}

		if(option_names.length > 0) {
			option_names = option_names.substring(0, option_names.length - 1);
		}

		$("#orderlist").children("li:last").children("span.menu_name").text(menu.name); // 메뉴명
		$("#orderlist").children("li:last").children("span.options").text(option_names); // 옵션 나열
		$("#orderlist").children("li:last").children("span.price").text(order_info.price); // 가격
		$("#orderlist").children("li:last")
			.attr("order_id", orders.temp_order_id++)
			.attr("menu_id", menu.menu_id)
			.attr("name", menu.name)
			.attr("price", order_info.price)
			.attr("count", 1)
			.children("span.btn").click(function() {
				var classname = $(this).attr("class");
				var order_id = $(this).parent().attr("order_id");
				Order.modify_order_count(order_id, classname.indexOf("add") >= 0);
			});

		orders.order_list.push(order_info);
		_calculate_total_price();
	}

	/**
	 * 총 주문 금액 계산
	 */
	function _calculate_total_price() {
		var total_price = 0;
		$("#orderlist>li").each(function() {
			total_price += $(this).attr("count") * $(this).attr("price");
		});

		$("#total_price").text(total_price);
	}

	/**
	 * 기존에 세팅되어 있던 옵션 리스트 제거
	 */
	function _empty_option_data() {
		$("#optionlist_area").empty();
		$("#menu_name").text("");
	}

	return {
		init: function() {
			_set_menu();
		},
		set_option: function() {
			var is_success = true;
			$("#optionlist_area ul").each(function() {
				// 필수 옵션에 대한 처리 + 옵션별 최대 개수
				if(eval($(this).attr("is_essential")) && is_success) {
					var max_select = eval($(this).attr("max_select"));
					var checked_count = 0;
					$(this).children("li").children("input[type=radio]:checked").each(function() {
						checked_count++;
					});

					// 필수 옵션에 대한 처리
					if(checked_count == 0) {
						alert("필수 옵션입니다. 선택해 주세요.");
						is_success = false;
					} else if(max_select < checked_count) { // 옵션별 최대 개수
						alert("이 옵션은 최대 " + max_select + "개까지 선택 가능합니다.");
						is_success = false;
					}
				}
			});

			// 필수 옵션을 모두 체크하였을 경우
			if(is_success) {
				var menu_id = $("#optionlist_area").attr("menu_id");
				var select_options = new Array();
				$("#optionlist_area input:checked").each(function() {
//					var text = "menu_id:" + menu_id + ", option_group_id:" + $(this).attr("option_group_id") + ", option_id:" + $(this).attr("option_id") + ", option_name:" + $(this).attr("option_name") + ", add_price:" + $(this).attr("add_price");
//					alert(text);
					var select_option = {
							menu_id:menu_id,
							option_group_id:$(this).attr("option_group_id"),
							option_id:$(this).attr("option_id"),
							option_name:$(this).attr("option_name"),
							add_price:$(this).attr("add_price"),
					};
					select_options.push(select_option);
				});

				_set_order(_get_menu_info(menu_id), select_options);
				$("#option_area").hide();
			}
		},
		/**
		 * 주문한 메뉴의 수량 조정
		 * @param menu 주문리스트의 메뉴 jquery instance
		 * @param boolean is_add 증가 여부(false 는 감소)
		 */
		modify_order_count: function(order_id, is_add) {
			var menu_tag = $("#orderlist>li[order_id="+order_id+"]");
			var order_idx = 0;
			for(var i=0; i<orders.order_list.length; i++) {
				if(orders.order_list[i].order_id == order_id) {
					order_idx = i;
					break;
				}
			}

			var count = eval(menu_tag.attr("count"));
			count = (is_add ? count + 1 : count - 1);
			orders.order_list[order_idx].count = count;

			if(count <= 0) {
				menu_tag.remove();
				orders.order_list.splice(order_idx, 1);
			} else {
				menu_tag.attr("count", count);
				menu_tag.children("span.count").text(count);
			}

			for(var i=0; i<orders.order_list.length; i++) {
				if(orders.order_list[i].order_id == order_id) {
					orders.order_list[i].count = count;
				}
			}

			_calculate_total_price();
		},
		close: function() {
			$("#option_area").hide();
		}
	};
}();
