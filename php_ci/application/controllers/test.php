<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH.'core/CO_Controller.php';

class Test extends CO_Controller {

	public function index()
	{
		$this->layout->set_layout('layout/layout_main');

		$country = $this->input->get_post("country");
		$continent = $this->input->get_post("continent");

		$this->load->model('test_model');
		$continent_list = $this->test_model->get_continent_list();
		$list = $this->test_model->get_country_list($continent, $country);

		$data = array(
				"continent_list" => $continent_list,
				"country" => $country,
				"continent" => (!$continent ? "Asia" : $continent),
				"list" => $list
		);

		$this->layout->view('test', $data);
	}
}