<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH.'core/CO_Controller.php';

class Utility extends CO_Controller {

	/**
	 * 주소 검색
	 * @param string $query
	 * @return NULL
	 */
	public function zipcode($query = false) {
		$url = "http://biz.epost.go.kr/KpostPortal/openapi";
		if(!$query) {
			return null;
		}

		$query = urldecode($query);
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

		$data = array(
				"error" => $error,
				"zipcode_list" => isset($result["itemlist"]["item"]) ? $result["itemlist"]["item"] : array()
		);

		echo json_encode($data);
	}
}