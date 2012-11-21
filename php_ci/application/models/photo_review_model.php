<?php
class Photo_review_model extends CO_Model {
	function __construct() {
		parent::__construct();
	}

	/**
	 * 포토 리뷰 추가
	 * @param unknown $market_id
	 * @param unknown $content
	 */
	function insert_photo_review($market_id, $content) {
		$data = array(
				"market_id" => $market_id,
				"content" => $content
		);

		return $this->test->insert("photo_review", $data);
	}

	/**
	 * 포토 리뷰 수정
	 * @param unknown $photo_review_id
	 * @param unknown $content
	 */
	function update_photo_review($photo_review_id, $content) {
		$data = array(
				"content" => $content,
				"latest_update_datetime" => date("Y-m-d H:i:s")
		);
		return $this->test->where("photo_review_id", $photo_review_id)->update("photo_review", $data);
	}

	/**
	 * 포토 리뷰 삭제
	 * @param unknown $photo_review_id
	 */
	function delete_photo_review($photo_review_id) {
		return $this->test->delete("photo_review", array("photo_review_id" => $photo_review_id));
	}

	/**
	 * 포토 리뷰 정보 반환
	 * @param unknown $market_id
	 */
	function get_photo_review_info($market_id) {
		return $this->test->get_where("photo_review", array("market_id" => $market_id))->row();
	}
}

?>