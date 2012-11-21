var move_image = function() {
	var image_info = {
			list:[
                  {idx:0, url:"/static/img/moohandojeon.jpg", desc:"무한~도전!!"},
                  {idx:1, url:"/static/img/Chrysanthemum.jpg", desc:"빨간 꽃"},
                  {idx:2, url:"/static/img/Desert.jpg", desc:"사막이네!"},
                  {idx:3, url:"/static/img/Tulips.jpg", desc:"튜울리입"}
			],
			current_image_idx:0
		};

	function _move_image(action) {
		var image_info_size = image_info.list.length;
		switch(action) {
			case "prev":
				image_info.current_image_idx--;

				if(image_info.current_image_idx < 0) {
					image_info.current_image_idx = image_info_size - 1;
				}
				break;
			case "next":
				image_info.current_image_idx++;

				if(image_info.current_image_idx > image_info_size - 1) {
					image_info.current_image_idx = 0;
				}
				break;
		}
	}

	function _set_data() {
		$("#img").attr("src", image_info.list[image_info.current_image_idx].url);
		$("#desc_area").text(image_info.list[image_info.current_image_idx].desc);
	}

	return {
		init: function() {
			_set_data();
		},
		move: function(action) {
			_move_image(action);
			_set_data();
		}
	};
}();