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
		$this->load->model('market_model');
		$this->load->model('menu_model');
		$this->load->model('address_model');

		$market_info = $this->market_model->get_market_info($market_id);
		$market_address = $market_info->market_address1 . " " . $market_info->market_address2;

		$market_location = $this->get_address_location($market_address);
		$market_info->map_coord = json_encode($market_location["coord"]);
		$market_info->map_error_code = $market_location["error_code"];

		// 기선택된 배달 가능 지역
		$delivery_location_list = $this->market_model->get_delivery_location_list($market_id);

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
}