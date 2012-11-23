var Order = function() {
	// menu 정보
//	var menus = [
//	             {menu_id:1, name:"menu1", price:5000,
//	            	 options: [
//	            	 	{menu_option_group_id:1, name:"패티선택", is_essential:true, max_select:1,
//	            	 		menu_option_list:[
//	            	 		             {menu_option_id:1, menu_option_name:"호주산 청정우", add_price:0},
//	            	 		             {menu_option_id:2, menu_option_name:"한우", add_price:2000},
//	            	 		             {menu_option_id:3, menu_option_name:"닭가슴살", add_price:-1000}]},
//	            	    {menu_option_group_id:2, name:"빵선택", is_essential:false, max_select:1,
//	            	 		menu_option_list:[
//	            	 		             {menu_option_id:4, menu_option_name:"번", add_price:0},
//	            	 		             {menu_option_id:5, menu_option_name:"호밀샌드위치", add_price:500}]},
//	            	 	{menu_option_group_id:3, name:"토핑추가", is_essential:false, max_select:2,
//	            	    	menu_option_list:[
//	            	    	             {menu_option_id:6,menu_option_name:"계란후라이", add_price:500},
//	            	    	             {menu_option_id:7, menu_option_name:"베이컨2줄", add_price:1000},
//	            	    	             {menu_option_id:8, menu_option_name:"치즈", add_price:500},
//	            	    	             {menu_option_id:9, menu_option_name:"야채", add_price:900},
//	            	    	             {menu_option_id:10, menu_option_name:"닭가슴살", add_price:2000}]},
//	            	    {menu_option_group_id:4, name:"소스추가", is_essential:false, max_select:1,
//	            	    	menu_option_list:[
//	            	    	             {menu_option_id:11, menu_option_name:"케찹", add_price:0},
//	            	    	             {menu_option_id:12, menu_option_name:"바베큐소스", add_price:0}]}
//	            	 ]},
//	             {menu_id:2, name:"menu2", price:3000},
//	             {menu_id:3, name:"menu3", price:2000},
//	             {menu_id:4, name:"menu4", price:7500},
//	];

	var menus;

	// 주문 정보
	var orders = {
			max_order_count:10,
			temp_order_id:0,
			order_list:[]
	};

	var market = {
			is_available_order:true,
			is_order_time:true
	};

	function _set_menu(_menus) {
		menus = new Array($(".menu").length);
		$(".menu").each(function(idx) {
			var menu_info = {
					menu_id:$(this).attr("menu_id"),
					menu_name:$(this).attr("menu_name"),
					price:$(this).attr("price"),
					fee:$(this).attr("fee")
			};

			menus[idx] = menu_info;
//			menus.push(menu_info);
		});
//		menus = _menus;

		// 메뉴 리스트 클릭 시 주문 혹은 상세 옵션 입력하도록 click 이벤트 추가
		$("button.btn_add_order").click(function() {
			if(!market.is_available_order) {
				alert("매장에서 주문 접수를 일시 받지 않습니다.");
				return;
			} else if(!market.is_order_time) {
				alert("주문 접수 시간이 아닙니다.");
				return;
			}

			var menu = $(this).parent();
			if(orders.order_list.length < orders.max_order_count) {
				$.get("/service/menu/get_option", {
					menu_id: menu.attr("menu_id")
				}, function(result) {
					data = JSON.parse(result);
					if(data.length > 0) {
						for(var i=0; i<menus.length; i++) {
							if(menus[i].menu_id == menu.attr("menu_id")) {
								menus[i].options = data;
								break;
							}
						}
						_set_options(menu);
					} else {
						_set_order(_get_menu_info(menu.attr("menu_id")));
					}
				});
			} else {
				alert("주문은 최대 " + orders.max_order_count + "개까지 가능합니다.");
			}
		});
	}

	function _init_standby(_orders) {
		alert(_orders);
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
			option.is_essential = (option.is_essential == "y");

			// option 타이틀 세팅
			var option_info = {
					sub_title:(option.is_essential ? "필수" : "선택"),
					input_type:(option.is_essential ? "radio" : "checkbox"),
			};

			if(option.max_select > 1) {
				option_info.sub_title += ", 최대" + option.max_select + "개까지";
			}
			var h3 = "<h3>" + option.menu_option_group_name + "(" + option_info.sub_title + ")</h3>";
			$("#optionlist_area").append(h3);

			// option 세팅
			$("#optionlist_area").append("<ul></ul>");
			$("#optionlist_area").children("ul:last").attr("max_select", option.max_select).attr("is_essential", option.is_essential);
			for(var j=0; j<option.menu_option_list.length; j++) {
				var detail_option = option.menu_option_list[j];
				var li = "<li>" +
					"<input type='" + option_info.input_type + "' />" +
					detail_option.menu_option_name + (detail_option.add_price != 0 ? "(" + commify(detail_option.add_price) + ")" : "") +
					"</li>";

				$("#optionlist_area ul:last").append(li);
				$("#optionlist_area ul:last li:last input")
					.attr("name", "option_group_" + option.menu_option_group_id)
					.attr("menu_option_group_id", option.menu_option_group_id)
					.attr("menu_option_id", detail_option.menu_option_id)
					.attr("add_price", detail_option.add_price)
					.attr("menu_option_name", detail_option.menu_option_name)
					.click(function() {
						// option 선택했을 경우 해당 옵션 그룹의  최대 선택 가능 개수 체크 로직
						var input_name = $(this).attr("name");
						var max_select = $(this).parent().parent().attr("max_select");
						var checked_count = 0;
						$("input[name='" + input_name + "']:checked").each(function() {
							checked_count++;
						});

						if(checked_count > max_select) {
							alert("이 옵션은 최대 " + max_select + "개까지 선택 가능합니다.");
							$(this).attr("checked", false);
						} else {
							var price = eval(menu.attr("price"));
							var option_price = 0;
							$("#optionlist_area input:checked").each(function() {
								option_price += eval($(this).attr("add_price"));
							});

							$("#order_price").text(commify(price + option_price));
						}
					});
			}
		}

		$("#order_price").text(commify(menu.attr("price")));
		$("#menu_name").text(menu.attr("menu_name"));
		$("#option_area").show();
	}

	/**
	 * 주문 추가
	 * @param menu 메뉴리스트의 메뉴 json
	 * @param select_options [optional]Array 선택한 options 정보(menu_id, menu_option_group_id, menu_option_id, option_name)
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
				menu_name:menu.menu_name,
				price : eval(menu.price),
				count:1,
				fee:menu.fee,
				menu_option_list:[]
		};

		var option_names = "";
		if(typeof(select_options) != "undefined") {
			for(var i=0; i<select_options.length; i++) {
				var select_option = select_options[i];
				option_names += select_option.menu_option_name + ",";
				order_info.price += eval(select_option.add_price);

				order_info.menu_option_list.push(select_option);
			}
		}

		if(option_names.length > 0) {
			option_names = option_names.substring(0, option_names.length - 1);
		}

		$("#orderlist").children("li:last").children("span.menu_name").text(menu.menu_name); // 메뉴명
		$("#orderlist").children("li:last").children("span.options").text(option_names); // 옵션 나열
		$("#orderlist").children("li:last").children("span.price").text(commify(order_info.price)); // 가격
		$("#orderlist").children("li:last")
			.attr("order_id", orders.temp_order_id++)
			.attr("menu_id", menu.menu_id)
			.attr("name", menu.menu_name)
			.attr("price", commify(order_info.price))
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
		var delivery_tip = parseInt($("#delivery_tip_area span").text().replace(/,/g, ''));
		var total_price = delivery_tip;
		$("#orderlist>li").each(function() {
			total_price += $(this).attr("count") * $(this).attr("price").replace(/,/g, '');
		});

		if(total_price == delivery_tip) {
			total_price = 0;
			$("#delivery_tip_area").hide();
		} else {
			$("#delivery_tip_area").show();
		}

		$("#total_price").text(commify(total_price));
	}

	/**
	 * 기존에 세팅되어 있던 옵션 리스트 제거
	 */
	function _empty_option_data() {
		$("#optionlist_area").empty();
		$("#menu_name").text("");
	}

	/**
	 * 천 단위 구분자 찍기
	 */
	function commify(n) {
		var reg = /(^[+-]?\d+)(\d{3})/;   // 정규식
		n += '';                          // 숫자를 문자열로 변환

		while (reg.test(n)) {
			  n = n.replace(reg, '$1' + ',' + '$2');
		}

		return n;
	}

	function _set_market() {
		market.is_available_order = $("#is_available_order").val() == "Y";
		market.is_order_time = $("#is_order_time").val() == "Y";

		if(!(market.is_available_order && market.is_order_time)) {
			$("button.btn_add_order").attr("disabled", "disabled");
			$("#btn_order").attr("disabled", "disabled");
		}
	}

	return {
		init: function(menus) {
			_set_menu(menus);
			_set_market();
		},
		init_standby: function(orders) {
			_init_standby(orders);
			_set_market();
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
//					var text = "menu_id:" + menu_id + ", menu_option_group_id:" + $(this).attr("menu_option_group_id") + ", menu_option_id:" + $(this).attr("menu_option_id") + ", option_name:" + $(this).attr("option_name") + ", add_price:" + $(this).attr("add_price");
//					alert(text);
					var select_option = {
							menu_id:menu_id,
							menu_option_group_id:$(this).attr("menu_option_group_id"),
							menu_option_id:$(this).attr("menu_option_id"),
							menu_option_name:$(this).attr("menu_option_name"),
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
		get_order: function() {
			return orders;
		},
		close: function() {
			$("#option_area").hide();
		}
	};
}();
