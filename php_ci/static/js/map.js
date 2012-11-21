var map = function() {
	var map_info = {
		is_init : false,
		is_show : false
	};

	function _set_map(location_name, coord) {
		var oSeoulCityPoint = new nhn.api.map.LatLng(coord.y, coord.x);
		var defaultLevel = 11;
		var oMap = new nhn.api.map.Map(document.getElementById('map'), {
						point : oSeoulCityPoint,
						zoom : defaultLevel,
						enableWheelZoom : true,
						enableDragPan : true,
						enableDblClickZoom : false,
						mapMode : 0,
						activateTrafficMap : false,
						activateBicycleMap : false,
						minMaxLevel : [ 1, 14 ],
						size : new nhn.api.map.Size(600, 360)		});
		var oSlider = new nhn.api.map.ZoomControl();
		oMap.addControl(oSlider);
		oSlider.setPosition({
			top : 10,
			left : 10
		});

		var oMapTypeBtn = new nhn.api.map.MapTypeBtn();
		oMap.addControl(oMapTypeBtn);
		oMapTypeBtn.setPosition({
			bottom : 10,
			right : 80
		});

		var oSize = new nhn.api.map.Size(28, 37);
		var oOffset = new nhn.api.map.Size(14, 37);
		var oIcon = new nhn.api.map.Icon('http://static.naver.com/maps2/icons/pin_spot2.png', oSize, oOffset);

		var oInfoWnd = new nhn.api.map.InfoWindow();
		oInfoWnd.setVisible(false);
		oMap.addOverlay(oInfoWnd);

		oInfoWnd.setPosition({
			top : 20,
			left :20
		});

		var oLabel = new nhn.api.map.MarkerLabel(); // - 마커 라벨 선언.
		var oMarker = new nhn.api.map.Marker(oIcon, {title : location_name});
		oMarker.setPoint(oSeoulCityPoint);
		oMap.addOverlay(oMarker);
		oMap.addOverlay(oLabel);
		oLabel.setVisible(true, oMarker);

		oInfoWnd.attach('changeVisible', function(oCustomEvent) {
			if (oCustomEvent.visible) {
				oLabel.setVisible(false);
			}
		});
	}

	return {
		init: function(location_name, coord, error_code) {
			if(!error_code) {
				_set_map(location_name, coord);
				map_info.is_init = true;
				map_info.is_show = true;
			} else {
				$("#map").hide();
				switch(error_code) {
					case "010":
						alert("요청 제한을 초과하였습니다. 일반적으로는 100,000회 이상의 요청에 대하여 이 에러 메세지가 발생되나, 요청 제한이 다르게 설정된 경우에는 이에 준하여 발생됩니다.");
						break;
					case "011":
						alert("잘못된 요청입니다. 쿼리(query=)필드 자체가 없는 경우 발생하는 에러 메세지 입니다.");
						break;
					case "020":
						alert("등록되지 않은 키입니다.");
						break;
					case "200":
						alert("Reserved");
						break;
					default:
						alert("error");
						break;
				}
			}
		},
		is_set_map: function() {
			return map_info.is_init;
		},
		is_show_map: function() {
			return map_info.is_show;
		},
		toggle_map_view: function() {
			if(map_info.is_show) {
				$("#map").hide();
			} else {
				$("#map").show();
			}

			map_info.is_show = !map_info.is_show;
		}
	};
}();
