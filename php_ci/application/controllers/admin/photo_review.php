<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH.'core/CO_Controller.php';

class Photo_review extends CO_Controller {

	public function index() {
	}

	/**
	 * 포토 리뷰 보기
	 * @param unknown $market_id
	 */
	public function pre($market_id) {
		$this->load->helper(array("url"));
		$params = $this->get_params("get");

		$this->load->model('market_model');
		$this->load->model('photo_review_model');
		$market_info = $this->market_model->get_market_info($market_id);
		$photo_review_info = $this->photo_review_model->get_photo_review_info($market_id);

		$this->set_attribute('market_info', $market_info);
		$this->set_attribute('photo_review_info', $photo_review_info);
		$this->set_attribute("cur_page", $params->cur_page);
		$this->set_view('service/photo_review');
	}

	/**
	 * 포토 리뷰 추가
	 */
	public function insert() {
		$this->load->helper(array("form", "url"));
		$params = $this->get_params("post");

		$market_id = $params->market_id;
		$content = $params->ir1;

		$this->load->model('photo_review_model');
		$this->photo_review_model->insert_photo_review($market_id, $content);

// 		$this->pre($params->market_id);
		redirect("/admin/photo_review/pre/" . $params->market_id . "?cur_page=" . $params->cur_page, "refresh");
	}

	/**
	 * 포토 리뷰 수정
	 */
	public function update() {
		$this->load->helper(array("url"));
		$params = $this->get_params("post");

		$market_id = $params->market_id;
		$photo_review_id = $params->photo_review_id;
		$content = $params->ir1;

		$this->load->model("photo_review_model");
		$this->photo_review_model->update_photo_review($photo_review_id, $content);

		redirect("/admin/photo_review/pre/" . $params->market_id . "?cur_page=" . $params->cur_page, "refresh");
	}

	/**
	 * 포토 리뷰 삭제
	 */
	public function delete() {
		$this->load->helper(array("url"));
		$params = $this->get_params("post");
		$photo_review_id = $params->photo_review_id;

		$this->load->model("photo_review_model");
		$this->photo_review_model->delete_photo_review($photo_review_id);

		redirect("/admin/market/lists/" . $params->cur_page, "refresh");
	}
}
?>