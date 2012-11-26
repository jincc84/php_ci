<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH.'core/CO_Controller.php';

class Option extends CO_Controller {

	public function index()
	{
		$this->layout->set_layout('layout/layout_main');

		$this->load->model("menu_option_model");

		$menu_id = 5;
		$menu_option_group_list = $this->menu_option_model->get_menu_option_group_list($menu_id);
		if(count($menu_option_group_list) > 0) {
			foreach($menu_option_group_list as &$menu_option_group) {
				$menu_option_group->menu_option_list = $this->menu_option_model->get_menu_option_list($menu_option_group->menu_option_group_id);
			}
		}
		$data = array(
				"menu_option_group_list" => $menu_option_group_list
		);

		$this->layout->view('prototype/option', $data);
	}
}