<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH.'core/CO_Controller.php';

class Order extends CO_Controller {

	public function index() {
		$this->load->helper(array("url"));

		$this->load->model("market_model");
		$this->load->model("order_model");

		// useruuid 를 가져오는 부분 추가해야 함.
		$temp_user_id = 1;
		$now_datetime = date("Y-m-d H:i:s");

		$params = $this->get_params();
		$market_id = $params->market_id;
		$order = json_decode($params->order, true);

		$market_info = $this->market_model->get_market_info($market_id);
		$order_id = $this->order_model->order_standby($market_info, $temp_user_id, $order, $now_datetime);

		if($order_id) {
			redirect("/service/order/standby?order_id=".$order_id, "refresh");
		} else { // error
// 			redirect("", "refresh");
		}
	}

	/**
	 * 주문 시도
	 * 데이터 추가 후 결제 및 배송 관련 데이터 받는 페이지로 이동
	 */
	public function standby() {
		$this->load->helper(array("form"));

		$this->load->model("market_model");
		$this->load->model("order_model");

		$params = $this->get_params();
		$order_id = $params->order_id;

		$order_info = $this->get_detail_order_info($order_id);
		$market_info = $this->market_model->get_market_info($order_info->market_id);
		$this->set_attribute("market_info", $market_info);
		$this->set_attribute("order_info", $order_info);

		$this->set_view("service/order_standby");
	}

	private function get_detail_order_info($order_id) {
		$order_info = $this->order_model->get_order_info($order_id);
		$order_menu_list = $this->order_model->get_order_menu_list($order_id);
		foreach($order_menu_list as &$order_menu) {
			$order_menu_option_list = $this->order_model->get_order_menu_option_list($order_menu->order_menu_id);
			$order_menu_option_name_list = array();
			$order_menu_option_add_price_sum = 0;
			foreach($order_menu_option_list as $order_menu_option) {
				array_push($order_menu_option_name_list, $order_menu_option->menu_option_name);
				$order_menu_option_add_price_sum += isset($order_menu_option->discount_add_price) ? $order_menu_option->discount_add_price : $order_menu_option->add_price;
			}

			// 옵션명
			if(count($order_menu_option_name_list) > 0) {
				$order_menu->order_menu_option_name = implode(",", $order_menu_option_name_list);
			}
			// 옵션 추가 가격
			$order_menu->menu_option_add_price = $order_menu_option_add_price_sum;
			// 메뉴 가격(단가 + 옵션)
			$order_menu->menu_option_price = $order_menu->price + $order_menu->menu_option_add_price;
		}
		$order_info->order_menu_list = $order_menu_list;

		return $order_info;
	}
}