<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH.'core/CO_Controller.php';

class Market extends CO_Controller {

	public function index() {
	}

	public function lists($cur_page = 1) {
		//$this->load->helper(array("form", "url"));
		//$this->load_library("form_validation");

		$count_per_page = 10;

		$this->load->model('market_model');
		$market_count = $this->market_model->get_market_list_count();
		$market_list = $this->market_model->get_market_list($cur_page, $count_per_page, "main");
		$pagination = $this->set_pagination("/service/market/lists", $market_count, $cur_page, $count_per_page);

		$this->set_attribute("cur_page", $cur_page);
		$this->set_attribute('market_list', $market_list);
		$this->set_view('service/market_list');
	}

	public function detail($market_id) {
		$this->load_helper(array("form"));

		$this->load->model('market_model');
		$this->load->model('menu_model');
		$this->load->model('address_model');

		$market_info = $this->market_model->get_market_info($market_id);
		/*
		 * 영업 가능 시간인지 체크
		 * */
		$today = explode("-", date("Y-m-d-D-H-i"));
		$market_time_info = $this->market_model->get_market_time_info($market_id, $today[3]);
		// array 를 return 받으므로 내용이 있는지에 대한 체크를 count 로 함
		if(count($market_time_info)) {
			$now_time = $today[4] . ":" . $today[5];
			$market_info->is_order_time = ($market_time_info->open_time <= $now_time && $market_time_info->close_time >= $now_time) ? "Y" : "N";

			// 휴일 체크
			if($market_info->is_order_time == "Y" && $market_info->is_holiday == "Y") {
				$market_info->is_order_time = $this->get_is_order_time_by_holiday_type($market_info->holiday_type);
			}

			// 브레이크 타임 체크
			if($market_info->is_order_time == "Y" && isset($market_info->break_start_time) && isset($market_info->break_end_time)) {
				$market_info->is_order_time = ($market_time_info->break_start_time <= $now_time && $market_time_info->break_end_time >= $now_time) ? "N" : "Y";
			}
		} else {
			$market_info->is_order_time = "N";
		}
		$market_info->market_time_info = $market_time_info;

		/*
		 * 지도 관련
		 * */
		$market_address = $market_info->market_address1 . " " . $market_info->market_address2;
		$market_location = $this->get_address_location($market_address);
		$market_info->map_coord = json_encode($market_location["coord"]);
		$market_info->map_error_code = $market_location["error_code"];

		/*
		 * 기선택된 배달 가능 지역
		 * */
		$delivery_location_list = $this->market_model->get_delivery_location_list($market_id);

		/*
		 * 메뉴 관련
		 * */
		$menu_category_list = $this->menu_model->get_menu_category_list($market_id);
		$menu_list = $this->menu_model->get_menu_list($market_id);
		if(count($menu_category_list) > 0) {
			foreach($menu_list as $menu) {
				foreach($menu_category_list as &$menu_category) {
					if($menu->menu_category_id == $menu_category->menu_category_id) {
						$menu_category->menu_list[$menu->menu_id] = $menu;
						break;
					}
				}
			}
		}

		$this->set_attribute("map_key", NHN_MAP_API_KEY);
		$this->set_attribute("market_info", $market_info);
		$this->set_attribute("menu_category_list", $menu_category_list);
		$this->set_attribute("delivery_location_list", $delivery_location_list);
		$this->set_attribute("menu_list", $this->menu_model->get_menu_list($market_id, "menu_id_unique"));

		$this->set_attribute("market_info", $market_info);
		$this->set_view("service/market_detail");
	}

	/**
	 * 몇째 주인지 확인
	 */
	private function get_week_nth() {
		$today = explode("-", date("Y-m-d"));
		$time = mktime(0, 0, 0, $today[1], 1, $today[0]);
		$add_day = date("w", $time);

		// 		$today_temp = date("d", mktime(0, 0, 0, $today[1], $today[2], $today[0]));
		// 		$week = ceil(($today_temp + $add_day) / 7);
		$week = ceil(($today[2] + $add_day) / 7);

		return $week;
	}

	/**
	 * 휴일 타입별 영업 가능한지 확인
	 * @param unknown $holiday_type
	 * @return string
	 */
	private function get_is_order_time_by_holiday_type($holiday_type) {
		$week_nth = $this->get_week_nth();
		switch($holiday_type) {
			case 'ALL':
				$is_order_time = "N";
				break;
			case 'ODD':
				$is_order_time = ($week_nth % 2 == 1) ? "N" : "Y";
				break;
			case 'EVEN':
				$is_order_time = ($week_nth % 2 == 0) ? "N" : "Y";
				break;
			case 'FIRST_THIRD':
				$is_order_time = ($week_nth < 5 && $week_nth % 2 == 1) ? "N" : "Y";
				break;
			case 'SECOND_FOURTH':
				$is_order_time = ($week_nth < 5 && $week_nth % 2 == 0) ? "N" : "Y";
				break;
			case 'FIRST':
				$is_order_time = ($week_nth == 1) ? "N" : "Y";
				break;
			case 'SECOND':
				$is_order_time = ($week_nth == 2) ? "N" : "Y";
				break;
			case 'THIRD':
				$is_order_time = ($week_nth == 3) ? "N" : "Y";
				break;
			case 'FOURTH':
				$is_order_time = ($week_nth == 4) ? "N" : "Y";
				break;
			default:
				$is_order_time = "Y";
				break;
		}

		return $is_order_time;
	}
}