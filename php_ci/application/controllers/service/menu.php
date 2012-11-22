<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH.'core/CO_Controller.php';

class Menu extends CO_Controller {

	public function index() {
	}

	public function lists($market_id) {
	}

	public function get_option() {
		$params = $this->get_params();
		$this->load->model("menu_option_model");

		$menu_option_group_list = $this->menu_option_model->get_menu_option_group_list($params->menu_id);
		if(count($menu_option_group_list) > 0) {
			foreach($menu_option_group_list as &$menu_option_group) {
				$menu_option_group->menu_option_list = $this->menu_option_model->get_menu_option_list($menu_option_group->menu_option_group_id);
			}
		}

		echo json_encode($menu_option_group_list);
	}
}