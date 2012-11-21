<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH.'core/CO_Controller.php';

class Zipcode extends CO_Controller {
	public function index() {
		$this->layout->set_layout('layout/layout_main');
		$this->load->helper(array("form", "url"));
		$this->load_library("form_validation");

		$this->form_validation->set_rules("dong", "dong", "required");
		$this->form_validation->run();
		$dong = $this->input->get_post("dong");
		list($error, $zipcode_list) = $this->get_zipcode_list($dong);

		$data = array(
				"error" => $error,
				"zipcode_list" => $zipcode_list
		);

		$this->layout->view('prototype/zipcode', $data);
	}

	function get_zipcode_list($query) {
		$url = "http://biz.epost.go.kr/KpostPortal/openapi";
		if(!$query) {
			return null;
		}

		$params = array(
			"regkey" => POST_API_KEY,
			"target" => "post",
			"query" => iconv("utf-8", "euc-kr", $query)
		);

		$result = $this->get_url_to_contents($url, $params);
		$error = array(
			"is_error" => isset($result["error_code"]),
		);

		if($error["is_error"]) {
			$error["error_code"] = $result["error_code"];
			$error["message"] = $result["message"];
		}

		return array(
				$error,
				isset($result["itemlist"]["item"]) ? $result["itemlist"]["item"] : array()
		);
	}
}