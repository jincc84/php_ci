<?php
class Order_model extends CO_Model {
	function __construct() {
		parent::__construct();
	}

	/*
	 * 주문 준비 단계 처리(트랜잭션)
	 * */
	function order_standby($market_info, $order_user_id, $order, $now_datetime) {
		$this->test->trans_start();

		$this->load->model("menu_model");
		$this->load->model("menu_option_model");
		$result = $this->insert($market_info, $order_user_id, $now_datetime);
		if($result) {
			$order_id = $this->test->insert_id();
			$order_price = 0;
			foreach($order["order_list"] as $menu) {
				$menu_info = $this->menu_model->get_menu_info($menu["menu_id"]);
				$result = $this->insert_order_menu($order_id, $menu_info, $menu["count"]);
				$option_price = 0;
				if($result) {
					$order_menu_id = $this->test->insert_id();
					foreach($menu["menu_option_list"] as $option) {
						$option_info = $this->menu_option_model->get_menu_option_info($option["menu_option_id"]);
						$result = $this->insert_order_menu_option($order_menu_id, $option_info);
						if(!$result) {
							break;
						}

						$option_price += isset($option_info->discount_add_price) ? $option_info->discount_add_price : $option_info->add_price;
					}
				} else {
					break;
				}

				$menu_price = isset($menu_info->_price) ? $menu_info->discount_price : $menu_info->price;
				$order_price += ($menu_price + $option_price) * $menu["count"];
			}
		}

		if($result &&
				$this->update_order_price($order_id, $order_price, $market_info->delivery_tip) &&
				$this->insert_order_state_history($order_id, "STANDBY", null, $now_datetime)) {
			$this->test->trans_complete();
		} else {
			$this->test->trans_rollback();
		}

		return $result ? $order_id : $result;
	}

	/*
	 * 주문 내역 추가
	 * */
	private function insert($market_info, $order_user_id, $now_datetime) {
		$data = array(
				"market_id" => $market_info->market_id,
				"delivery_tip" => $market_info->delivery_tip,
				"order_user_id" => $order_user_id,
				"create_datetime" => $now_datetime,
				"latest_update_order_state_datetime" => $now_datetime,
		);
		$result_insert = $this->test->insert("tb_order", $data);
		return $result_insert;
	}

	/*
	 * 해당 주문 내역의 메뉴 리스트 추가
	 * */
	private function insert_order_menu($order_id, $menu, $count) {
		$data = array(
				"order_id" => $order_id,
				"menu_id" => $menu->menu_id,
				"menu_name" => $menu->menu_name,
				"price" => $menu->price,
				"discount_price" => (isset($menu->discount_price) ? $menu->discount_price : null),
				"count" => $count,
// 				"total_price" => (isset($menu->discount_price) ? $menu->discount_price : $menu->price) * $count,
				"fee" => $menu->fee
		);

		$result_insert = $this->test->insert("tb_order_menu", $data);
		return $result_insert;
	}

	/*
	 * 해당 주문 내역 > 메뉴 내역의 옵션 리스트 추가
	 * */
	private function insert_order_menu_option($order_menu_id, $option) {
		$data = array(
				"order_menu_id" => $order_menu_id,
				"menu_option_id" => $option->menu_option_id,
				"menu_option_name" => $option->menu_option_name,
				"add_price" => $option->add_price,
				"discount_add_price" => (isset($option->discount_add_price) ? $option->discount_add_price : null)
		);

		$result_insert = $this->test->insert("tb_order_menu_option", $data);
		return $result_insert;
	}

	private function update_order_price($order_id, $order_price = false, $delivery_tip = false, $total_price = false) {
		$data = array();
		if($order_price) $data["order_price"] = $order_price;
		if($delivery_tip) $data["delivery_tip"] = $delivery_tip;
		if($total_price) $data["total_price"] = $total_price;

		if(count($data) < 1) {
			return false;
		}

		return $this->test->where("order_id", $order_id)->update("tb_order", $data);
	}

	/*
	 * 주문 내역 상태값 변환에 따른 히스토리 추가
	 * */
	private function insert_order_state_history($order_id, $order_state, $order_desc = null, $datetime = false) {
		if(!$datetime) {
			$datetime = date("Y-m-d H:i:s");
		}

		$data = array(
				"order_id" => $order_id,
				"order_state" => $order_state,
				"order_state_desc" => $order_desc,
				"create_datetime" => $datetime
		);

		$result_insert = $this->test->insert("tb_order_state_history", $data);
		return $result_insert;
	}

	function get_order_info($order_id) {
		return $this->test->get_where("tb_order", array("order_id"=>$order_id))->row();
	}

	function get_order_menu_list($order_id) {
		return $this->test->get_where("tb_order_menu", array("order_id"=>$order_id))->result();
	}

	function get_order_menu_option_list($order_menu_id) {
		return $this->test->get_where("tb_order_menu_option", array("order_menu_id"=>$order_menu_id))->result();
	}
}