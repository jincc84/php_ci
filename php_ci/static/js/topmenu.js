var category = {
		list: new Array({
			id:10000,
			name:"admin",
			sub_category:new Array(
					{
						id:10100,
						name:"market_lists",
						url:"/admin/market/lists"
					}, {
						id:10200,
						name:"market_insert",
						url:"/admin/market/insert"
					}
			)
		}, {
			id:20000,
			name:"service",
			sub_category:new Array(
					{
						id:10100,
						name:"market",
						url:"/service/market/lists"
					}
			)
		}, {
			id:99999,
			name:"prototype",
			sub_category:new Array(
					{
						id:100,
						name:"home",
						url:"/"
					}, {
						id:200,
						name:"autocomplete",
						url:"/prototype/autocomplete"
					}, {
						id:250,
						name:"zipcode",
						url:"/prototype/zipcode"
					}, {
						id:300,
						name:"order",
						url:"/prototype/order"
					}, {
						id:400,
						name:"move_image",
						url:"/prototype/move_image"
					}, {
						id:500,
						name:"map",
						url:"/prototype/map"
					}, {
						id:600,
						name:"smart_editor",
						url:"/prototype/smart_editor"
					}, {
						id:700,
						name:"option",
						url:"/prototype/option"
					}
			)
		}),
		info:{
			has_sub_category:false,
			selected_first_category_id:0
		}
};

$().ready(function() {
	set_first_depth_menu();

	$(".first_depth>li").each(function() {
		$(this).click(function() {
			if(category.info.selected_first_category_id != $(this).attr("categoryId")) {
				if($(this).attr("url").length > 0) {
					document.location.href = $(this).attr("url");
				} else {
					set_second_depth_menu($(this).attr("categoryId"));
				}

				category.info.selected_first_category_id = $(this).attr("categoryId");
			}
		});
	});

	/**
	 * 첫번째 메뉴탭 생성
	 */
	function set_first_depth_menu() {
		for(var i=0; i<category.list.length; i++) {
			var cat = category.list[i];
			var li = "<li categoryId='" + cat.id + "' url='"+(cat.url ? cat.url : "")+"'>" + cat.name + "</li>";
			$(".first_depth").append(li);
		}
	}

	/**
	 * 두번째 메뉴탭 생성
	 * @param categoryId 첫번째 depth 메뉴의 category id
	 */
	function set_second_depth_menu(categoryId) {
		$(".second_depth").empty();

		category.info.has_sub_category = false;

		for(var i=0; i<category.list.length; i++) {
			var cat = category.list[i];
			if(cat.id == categoryId) {
				var sub_category = cat.sub_category;

				if(typeof(sub_category) == "undefined") {
					break;
				}

				for(var j=0; j<sub_category.length; j++) {
					var sub_cat = sub_category[j];

					var li = "<li categoryId='" + sub_cat.id + "' url='"+(sub_cat.url ? sub_cat.url : "")+"'>" + sub_cat.name + "</li>";
					$(".second_depth").append(li);
				}

				$(".second_depth>li").each(function() {
					$(this).click(function() {
						if($(this).attr("url").length > 0) {
							document.location.href = $(this).attr("url");
						} else {
							alert($(this).attr("categoryId"));
						}
					});
				});

				$(".second_depth").show(300);
				category.info.has_sub_category = true;
				break;
			}
		}

		if(category.info.has_sub_category == false) {
			$(".second_depth").hide(300);
			setTimeout(function() {
				$(".second_depth").empty();
			}, 300);
		}
	}
});