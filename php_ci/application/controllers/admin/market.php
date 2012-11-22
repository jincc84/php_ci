<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH.'core/CO_Controller.php';

class Market extends CO_Controller {

	public function index() {
		$this->lists();
	}

	/**
	 * 매장 리스트
	 * @param number $cur_page 현재 페이지
	 */
	public function lists($cur_page = 1) {
		$this->load->helper(array("form", "url"));
		$this->load_library("form_validation");

		$count_per_page = 10;

		$this->load->model('market_model');
		$market_count = $this->market_model->get_market_list_count();
		$market_list = $this->market_model->get_market_list($cur_page, $count_per_page);
		$pagination = $this->set_pagination("/admin/market/lists", $market_count, $cur_page, $count_per_page);

		$this->set_attribute("cur_page", $cur_page);
		$this->set_attribute('market_list', $market_list);
		$this->set_view('service/market_list');
	}

	/**
	 * 매장 상세
	 * @param unknown $market_id
	 */
	public function detail($market_id) {
		$this->load->helper(array("form", "url"));
		$this->load_library("form_validation");

		$this->load->model('market_model');
		$this->load->model('address_model');
		$market_info = $this->market_model->get_market_info($market_id);
		$market_address = $market_info->market_address1 . " " . $market_info->market_address2;

		$market_location = $this->get_address_location($market_address);
		$market_info->map_coord = json_encode($market_location["coord"]);
		$market_info->map_error_code = $market_location["error_code"];

		// 배달 가능 지역 선택 시도 데이터
		$market_delivery_location_sido_list = $this->address_model->get_market_delivery_location("sido");
		// 기선택된 배달 가능 지역
		$delivery_location_list = $this->market_model->get_delivery_location_list($market_id);

		// 각 영역별 등록 이미지 정보
		$market_image_main_list = $this->market_model->get_market_image_list($market_id, "main", true);
		$market_image_list_list = $this->market_model->get_market_image_list($market_id, "list", true);
		$market_image_detail_list = $this->market_model->get_market_image_list($market_id, "detail", true);
		// 각 영역당 최대 이미지 등록 가능 개수
		$market_image_max_count_list = array(
				"main" => 2,
				"list" => 2,
				"detail" => 5
		);

		$this->set_attribute("map_key", NHN_MAP_API_KEY);
		$this->set_attribute("market_info", $market_info);
		$this->set_attribute("market_image_main_list", $market_image_main_list);
		$this->set_attribute("market_image_list_list", $market_image_list_list);
		$this->set_attribute("market_image_detail_list", $market_image_detail_list);
		$this->set_attribute("delivery_location_list", $delivery_location_list);
		$this->set_attribute("market_delivery_location_sido_list", $market_delivery_location_sido_list);

		$this->set_attribute("market_image_max_count_list", $market_image_max_count_list);

		$this->set_view('service/market_detail');
	}

	/**
	 * 매장 추가
	 */
	public function insert() {
		$this->load->helper(array("form", "url"));
		$this->load_library("form_validation");

		$this->form_validation->set_rules("market_name", "market_name", "required");
		$this->form_validation->set_rules("market_simple_info", "market_simple_info", "");
		$this->form_validation->set_rules("postcd", "postcd", "required|numeric");
		$this->form_validation->set_rules("market_address1", "market_address1", "required");
		$this->form_validation->set_rules("market_address2", "market_address2", "required");
		$this->form_validation->set_rules("default_fee", "default_fee", "required|is_natural");
		$form_result = $this->form_validation->run();

		$params = $this->get_params("post");

		if($form_result) {
			// 주소의 번지 영역 제거
			$market_address1 = explode(" ", $params->market_address1);
			$new_address1 = array();
			foreach($market_address1 as &$tmp) {
				$reg = "/^([0-9]*)~?([0-9]*)/";
				$tmp = preg_replace($reg, "", $tmp);
				$new_address1[] = $tmp;
			}
			$params->market_address1 = implode(" ", $new_address1);

			$this->load->model('market_model');
			$market_id = $this->market_model->insert_market($params);
		}

		if($form_result) {
			redirect("/admin/market/lists", "refresh");
		} else {
			$this->set_attribute("params", $params);
			$this->set_view("service/market_insert");
		}
	}

	/**
	 * 매장 정보 수정
	 */
	public function update() {
		$this->layout->set_layout('layout/layout_main');
		$data = array();
		$this->layout->view('service/market', $data);
	}

	/**
	 * 매장 삭제
	 */
	public function delete() {
		$this->load->helper(array("form", "url"));
		$this->load_library("form_validation");
		$market_id = $this->input->get_post("market_id");

		$this->load->model("market_model");
		$market_id = $this->market_model->delete_market($market_id);

		redirect("/admin/market/lists", "refresh");
	}

	/**
	 * 이미지 업로드
	 */
	public function upload_image() {
		$this->load->helper(array("form", "url"));

		$config["upload_path"] = UPLOAD_PATH . "/market/";
		$config["allowed_types"] = "gif|jpg|png";
// 		$config["max_size"]	= "4096";
// 		$config["max_width"]  = "2048";
// 		$config["max_height"]  = "1536";
		$config["max_size"]	= "1000";
		$config["max_width"]  = "1024";
		$config["max_height"]  = "768";

		$this->load->library("upload", $config);
		$params = $this->get_params("post");

		if (!$this->upload->do_upload()) {
			$error = $this->upload->display_errors();
			$this->set_attribute("error", $error);
		} else {
			$market_id = $params->market_id;
			$market_image_area = $params->market_image_area;

			$upload_data = $this->upload->data();
			$this->load->model("market_model");
			$market_image_pre_order = $this->market_model->get_market_image_pre_order($market_id, $market_image_area);
			$market_image_id = $this->market_model->insert_market_image($market_id, $market_image_area, $market_image_pre_order->image_pre_order, $upload_data);
		}

// 		$this->detail($params->market_id);
 		redirect("/admin/market/detail/" . $params->market_id, "refresh");
	}

	/**
	 * 기지정된 예비 순서를 서비스 순서로 일괄 변경
	 * @param unknown $market_id
	 * @param unknown $market_image_area
	 */
	public function update_market_image_order($market_id, $market_image_area) {
		$this->load->model("market_model");
		$is_success = $this->market_model->update_market_image_order($market_id, $market_image_area);

		$result = array(
			"result_code" => $is_success
		);

		echo json_encode($result);
	}

	/**
	 * 운영툴에서의 예비 순서 변경(변경 row + 변경 row 로 인해 영향 받는 row = 총 2개 row)
	 * @param unknown $type
	 * @param unknown $market_image_id
	 */
	public function market_image_pre_order($type, $market_image_id) {
		$this->load->model("market_model");
		$where = array(
				"market_image_id" => $market_image_id
		);
		$market_image_info = $this->market_model->get_market_image_info($where);
		switch($type) {
			case "down":
				$add_order = 1;
				$where_relation = array(
					"market_id" => $market_image_info->market_id,
					"market_image_area" => $market_image_info->market_image_area,
					"image_pre_order" => intval($market_image_info->image_pre_order) + $add_order,
				);
				$relation_market_image_info = $this->market_model->get_market_image_info($where_relation);
				$result_update = $this->market_model->update_market_image_pre_order($market_image_info, $relation_market_image_info, $add_order);
				break;
			case "up":
				$add_order = -1;
				$where_relation = array(
						"market_id" => $market_image_info->market_id,
						"market_image_area" => $market_image_info->market_image_area,
						"image_pre_order" => intval($market_image_info->image_pre_order) + $add_order,
				);
				$relation_market_image_info = $this->market_model->get_market_image_info($where_relation);
				$result_update = $this->market_model->update_market_image_pre_order($market_image_info, $relation_market_image_info, $add_order);
				break;
		}

		$result = array(
				"result_code" => $result_update
		);

		echo json_encode($result);
	}

	/**
	 * 배달 지역 변경
	 * @param unknown $market_id
	 */
	public function update_delivery_location($market_id) {
		$params = $this->get_params("post");
		$dong = $params->dong;
		if(strlen($dong) == 0) {
			echo false;
		}

		$dong_list = explode(",", $dong);

		$this->load->model("market_model");
		$result = array();
		$result["result_code"] = $this->market_model->update_delivery_location($market_id, $dong_list, $params->address_gugun_id);

		if($result["result_code"]) {
			$result["delivery_location_list"] = $this->market_model->get_delivery_location_list($market_id);
		}

		echo json_encode($result);
	}

	/**
	 * 배달 지역 삭제
	 * @param unknown $market_id
	 */
	public function delete_delivery_location($market_id) {
		$params = $this->get_params("post");
		$this->load->model("market_model");
		$result = $this->market_model->delete_delivery_location($market_id, $params->address_dong_id);

		echo $result;
	}

	/**
	 * 메뉴 카테고리 지정
	 */
	public function update_menu_category_relation() {
		$params = $this->get_params("post");
		$menu_category_id_list = explode(",", $params->menu_category_id);

		$this->load->model("market_model");
		$result = $this->market_model->update_menu_category_relation($params->menu_id, strlen($params->menu_category_id) > 0 ? $menu_category_id_list : false);

		echo json_encode($result);
	}
}