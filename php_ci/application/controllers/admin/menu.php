<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH.'core/CO_Controller.php';

class Menu extends CO_Controller {

	public function index() {
	}

	public function lists($market_id) {
		$this->load->helper(array("form", "url"));

		$this->load->model('market_model');
		$this->load->model('menu_model');

		$market_info = $this->market_model->get_market_info($market_id);
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

		$this->set_attribute("market_info", $market_info);
		$this->set_attribute("menu_category_list", $menu_category_list);
		$this->set_attribute("menu_list", $this->menu_model->get_menu_list($market_id, "menu_id_unique"));

		$this->set_view('admin/menu_list');
	}

	/**
	 * 메뉴 상세
	 * @param unknown $menu_id
	 */
	public function detail($market_id, $menu_id) {
		$this->load->helper(array("form", "url"));
		$this->load_library("form_validation");

		$this->load->model('market_model');
		$this->load->model('menu_model');
		$this->load->model('menu_option_model');

		$market_info = $this->market_model->get_market_info($market_id);
		$menu_info = $this->menu_model->get_menu_info($menu_id);

		$menu_option_group_list = $this->menu_option_model->get_menu_option_group_list($menu_info->menu_id);
		if(count($menu_option_group_list) > 0) {
			foreach($menu_option_group_list as &$menu_option_group) {
				$menu_option_group->menu_option_list = $this->menu_option_model->get_menu_option_list($menu_option_group->menu_option_group_id);
			}
		}

		$this->set_attribute("market_info", $market_info);
		$this->set_attribute("menu_info", $menu_info);
		$this->set_attribute("menu_option_group_list", $menu_option_group_list);

		$this->set_view('admin/menu_detail');
	}

	/**
	 * 메뉴 카테고리 추가
	 */
	public function insert_category() {
		$this->load->helper(array("form", "url"));
		$this->load_library("form_validation");

		$this->load->model('menu_model');

		$this->form_validation->set_rules("menu_category_name", "menu_category_name", "required");
		$form_result = $this->form_validation->run();

		$params = $this->get_params("post");

		if($form_result) {
			$menu_category_id = $this->menu_model->insert_menu_category($params);
		}

		redirect("/admin/menu/lists/" . $params->market_id, "refresh");
	}

	/**
	 * 메뉴 카테고리 삭제
	 * @param unknown $menu_category_id
	 */
	public function delete_category() {
		$this->load->helper(array("url"));
		$this->load->model('menu_model');

		$params = $this->get_params("post");
		var_dump($params->market_id);
		var_dump($params->menu_category_id);

		$result = $this->menu_model->delete_menu_category($params->menu_category_id);

		$this->lists($params->market_id);
// 		redirect("/admin/menu/lists/" . $params->market_id, "refresh");
	}

	/**
	 * 메뉴 추가
	 */
	public function insert() {
		$this->load->helper(array("form", "url"));
		$this->load_library("form_validation");

		$this->load->model('menu_model');

		$this->form_validation->set_rules("menu_name", "menu_name", "required");
		$this->form_validation->set_rules("price", "price", "required|numeric");
		$this->form_validation->set_rules("fee", "fee", "required|numeric");
		$form_result = $this->form_validation->run();

		$params = $this->get_params("post");

		if($form_result) {
			$menu_id = $this->menu_model->insert_menu($params);
			if($menu_id && $params->menu_image_id) {
				$data = array(
						"menu_id" => $menu_id
				);
				$result = $this->menu_model->update_menu_image($params->menu_image_id, $data);
			}
		}

		redirect("/admin/menu/lists/" . $params->market_id, "refresh");
	}

	/**
	 * 메뉴 수정
	 */
	public function update() {
		$this->load->helper(array("form", "url"));
		$this->load_library("form_validation");

		$this->load->model('menu_model');

		$this->form_validation->set_rules("menu_name", "menu_name", "required");
		$this->form_validation->set_rules("price", "price", "required|numeric");
		$this->form_validation->set_rules("fee", "fee", "required|is_natural");
		$form_result = $this->form_validation->run();

		$params = $this->get_params("post");

		if($form_result) {
			$menu_option_id = $this->menu_model->update_menu($params);
			redirect("/admin/menu/lists/" . $params->market_id, "refresh");
		} else {
			$this->lists($params->market_id);
		}
	}

	/**
	 * 메뉴 삭제
	 */
	public function delete() {
		$this->load->helper(array("form", "url"));
		$this->load_library("form_validation");

		$this->load->model('menu_model');
		$this->load->model('menu_option_model');

		$params = $this->get_params("post");

		$result = $this->menu_model->delete_menu($params->menu_id);
// 		$result = $this->menu_model->delete_menu($params->menu_id) &&
// 						$this->menu_option_model->delete_menu_option_group_by_menu($params->menu_id) &&
// 						$this->menu_option_model->delete_menu_option_by_menu($params->menu_id);

		redirect("/admin/menu/lists/" . $params->market_id, "refresh");
	}

	/**
	 * 이미지 업로드
	 */
	public function upload_image() {
		$this->load->helper(array("form", "url"));

		$config["upload_path"] = UPLOAD_PATH . "/menu/";
		$config["allowed_types"] = "gif|jpg|png";
		// 		$config["max_size"]	= "4096";
		// 		$config["max_width"]  = "2048";
		// 		$config["max_height"]  = "1536";
		$config["max_size"]	= "1000";
		$config["max_width"]  = "1024";
		$config["max_height"]  = "768";

		$this->load->library("upload", $config);
		$params = $this->get_params("post");

		$result = array();
		if (!$this->upload->do_upload()) {
			$error = $this->upload->display_errors();
			$result["result_code"] = false;
			$result["error"] = $error;
		} else {
			$menu_id = $params->menu_id;

			$upload_data = $this->upload->data();
			$this->load->model("menu_model");
			$menu_image_id = $this->menu_model->insert_menu_image($menu_id, $upload_data);
			$upload_data["menu_image_id"] = $menu_image_id;

			$result["result_code"] = true;
			$result["data"] = $upload_data;
		}

		echo json_encode($result);
	}

	/**
	 * 옵션 그룹 + 옵션 내용 추가/수정/삭제 처리
	 */
	public function modify_option() {
		$params = $this->get_params();

		$available_option_info = json_decode($params->available_option_info, true);
		$menu_id = $params->menu_id;

		$this->load->model("menu_option_model");
		$result = $this->menu_option_model->modify_option($menu_id, $available_option_info);

		echo $result;
	}
}