<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH.'core/CO_Controller.php';

class Menu_option extends CO_Controller {

	public function index() {
	}

	/**
	 * 옵션 그룹 추가
	 */
	public function insert_group() {
		$this->load->helper(array("form", "url"));
		$this->load_library("form_validation");

		$this->load->model('menu_option_model');

		$this->form_validation->set_rules("menu_option_group_name", "menu_option_group_name", "required");
		$this->form_validation->set_rules("max_select", "max_select", "required|numeric");
		$form_result = $this->form_validation->run();

		$params = $this->get_params("post");

		if($form_result) {
			$menu_option_group_id = $this->menu_option_model->insert_menu_option_group($params);
			var_dump($menu_option_group_id);
		}

		redirect("/market/menu/detail/" . $params->market_id. "/" . $params->menu_id, "refresh");
	}

	public function delete_group($menu_option_group_id) {
		$this->load->model('menu_option_model');

		echo $this->menu_option_model->delete_menu_option_group($menu_option_group_id);
	}

	public function insert() {
		$this->load->helper(array("form", "url"));
		$this->load_library("form_validation");

		$this->load->model('menu_option_model');

		$this->form_validation->set_rules("menu_option_name", "menu_option_name", "required");
		$this->form_validation->set_rules("add_price", "add_price", "required|numeric");
		$form_result = $this->form_validation->run();

		$params = $this->get_params("post");

		if($form_result) {
			$menu_option_id = $this->menu_option_model->insert_menu_option($params);
			var_dump($menu_option_id);
		}

		redirect("/market/menu/detail/" . $params->market_id. "/" . $params->menu_id, "refresh");
	}

	public function delete() {
		$this->load->helper(array("form", "url"));
		$this->load_library("form_validation");

		$this->load->model('menu_option_model');

		$params = $this->get_params("post");

		$menu_opton_id = $this->menu_option_model->delete_menu_option($params->menu_option_id);

		redirect("/market/menu/detail/" . $params->market_id. "/" . $params->menu_id, "refresh");
	}
}