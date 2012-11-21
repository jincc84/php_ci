<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH.'core/CO_Controller.php';

class Address extends CO_Controller {

	public function index() {
	}

	/**
	 * 시도 아이디에 맞는 구군 리스트 반환
	 * @param unknown $address_sido_id
	 */
	public function search_gugun($address_sido_id) {
		$address_sido_id = urldecode($address_sido_id);
		if(!$address_sido_id) {
			echo json_encode(array());
			return;
		}

		$this->load->model('address_model');
		// 배달 가능 지역 선택 시도 데이터
		$market_delivery_location_gugun_list = $this->address_model->get_market_delivery_location("gugun", $address_sido_id);

		echo json_encode($market_delivery_location_gugun_list);
	}

	/**
	 * 구군 아이디에 맞는 동 리스트 반환
	 * @param unknown $address_gugun_id
	 */
	public function search_dong($address_gugun_id) {
		$address_gugun_id = urldecode($address_gugun_id);
		if(!$address_gugun_id) {
			echo json_encode(array());
			return;
		}

		$this->load->model('address_model');
		// 배달 가능 지역 선택 시도 데이터
		$market_delivery_location_dong_list = $this->address_model->get_market_delivery_location("dong", $address_gugun_id);

		echo json_encode($market_delivery_location_dong_list);
	}
}