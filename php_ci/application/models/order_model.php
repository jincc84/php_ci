<?php
class Order_model extends CO_Model {
	function __construct() {
		parent::__construct();
	}

	/*
	 * 주문 준비 단계 처리(트랜잭션 처리)
	 * */
	function order_standby($market_info, $order_user_id, $order, $now_datetime) {
		$this->test->trans_begin();

		$this->load->model("menu_model");
		$this->load->model("menu_option_model");

		if(!$this->insert($market_info, $order_user_id, $now_datetime)) {
			$this->test->trans_rollback();
			return false;
		}

		$order_id = $this->test->insert_id();
		$this->insert_order_datetime($order_id, "STANDBY", $now_datetime);
		$order_price = 0;
		foreach($order["order_list"] as $menu) {
			$menu_info = $this->menu_model->get_menu_info($menu["menu_id"]);
			if(!$this->insert_order_menu($order_id, $menu_info, $menu["count"])) {
				$this->test->trans_rollback();
				return false;
			}

			$option_price = 0;
			$order_menu_id = $this->test->insert_id();
			foreach($menu["menu_option_list"] as $option) {
				$option_info = $this->menu_option_model->get_menu_option_info($option["menu_option_id"]);
				if(!$this->insert_order_menu_option($order_menu_id, $option_info)) {
					$this->test->trans_rollback();
					return false;
				}

				$option_price += isset($option_info->discount_add_price) ? $option_info->discount_add_price : $option_info->add_price;
			}

			$menu_price = isset($menu_info->_price) ? $menu_info->discount_price : $menu_info->price;
			$order_price += ($menu_price + $option_price) * $menu["count"];
		}

		if(!$this->update_order_price($order_id, $order_price, $market_info->delivery_tip)) {
			$this->test->trans_rollback();
			return false;
		} else {
			$this->test->trans_commit();
		}

		return $order_id;
	}

	/*
	 * 주문 내역 추가
	 * */
	private function insert($market_info, $order_user_id, $now_datetime) {
		$data = array(
				"market_id" => $market_info->market_id,
				"delivery_tip" => $market_info->delivery_tip,
				"order_user_id" => $order_user_id,
				"fee" => $market_info->fee,
				"create_datetime" => $now_datetime
		);
		return $this->test->insert("tb_order", $data);
	}

	/*
	 * 주문 내역 상태 변경 날짜 기록
	 * */
	private function insert_order_datetime($order_id, $order_state, $now_datetime) {
		if(!$now_datetime) {
			$now_datetime = date("Y-m-d H:i:s");
		}
		$data = array(
				"order_id" => $order_id,
				"order_state" => $order_state,
				"create_datetime" => $now_datetime
		);
		return $this->test->insert("tb_order_datetime", $data);
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
		);

		return $this->test->insert("tb_order_menu", $data);
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