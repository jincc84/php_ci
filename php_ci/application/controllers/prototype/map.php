<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH.'core/CO_Controller.php';

class Map extends CO_Controller {
	public function index() {
		$this->layout->set_layout('layout/layout_main');

		$ip = $_SERVER["REMOTE_ADDR"];
// 		$ip = "221.138.236.254";
// 		$ip = "175.213.138.68";

		$market_address = "서울특별시 송파구 신천동 7-20";
		$market_address_name = "티켓몬스터";
		$user_address = $this->get_ip_targeting_address($ip);
// 		$user_address = "대전 서구 도마1동 26-14";

		$maket_location = $this->get_address_location($market_address);
		$user_location = $this->get_address_location($user_address);

		print_r($maket_location["coord"]);
		echo "market_location:" . $market_address  . "<br />";
		print_r($user_location["coord"]);
		echo "user_location:" . $user_address;
		echo "<br /><br />";

		$distance = $this->get_distance($user_location["coord"], $maket_location["coord"]);

		$data = array(
				"map_key" => NHN_MAP_API_KEY,
				"location_name" => $market_address_name,
				"error_code" => $maket_location["error_code"],
				"coord" => json_encode($maket_location["coord"])
		);
		$this->layout->view('prototype/map', $data);
	}
}