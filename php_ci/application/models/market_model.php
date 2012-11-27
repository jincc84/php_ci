<?php
class Market_model extends CO_Model {
	function __construct() {
		parent::__construct();
	}

	/**
	 * 매장 수 반환
	 */
	function get_market_list_count() {
		return $this->test->where("is_delete", "n")->from("tb_market")->count_all_results();
	}

	/**
	 * 매장 리스트
	 * @param int $page
	 * @param int $count
	 */
	function get_market_list($page = 1, $count = 10, $market_image_area = false) {
		$page = !$page ? 1 : $page;
		$count = !$count ? 10 : $count;

		$where = array();
		switch($market_image_area) {
			case "main":
				$query = "SELECT a.*,
								(select concat(file_path, file_name) from tb_market_image where market_id = a.market_id and market_image_area = 'main' and image_order = 1 and is_delete = 'n') as img_src1,
								(select concat(file_path, file_name) from tb_market_image where market_id = a.market_id and market_image_area = 'main' and image_order = 2 and is_delete = 'n') as img_src2,
								pr.photo_review_id
						FROM tb_market a left join tb_photo_review pr
						on a.market_id = pr.market_id
						where a.is_delete = 'n'
						LIMIT ?,?
				";
				break;
			default:
				$query = "SELECT a.*, pr.photo_review_id
						FROM tb_market a left join tb_photo_review pr
						on a.market_id = pr.market_id
						where a.is_delete = 'n'
						LIMIT ?,?
				";
				break;
		}

		$where = array(($page - 1) * $count, $count);

		return $this->test->query($query, $where)->result();
	}

	/**
	 * 매장 정보
	 * @param unknown $market_id
	 */
	function get_market_info($market_id, $market_image_area = false) {
		switch($market_image_area) {
			case "main":
				$query = "
						select a.*,
								(select concat(file_path, file_name) from tb_market_image where market_id = a.market_id and market_image_area = 'main' and image_order = 1 and is_delete = 'n') as img_src1,
								(select concat(file_path, file_name) from tb_market_image where market_id = a.market_id and market_image_area = 'main' and image_order = 2 and is_delete = 'n') as img_src2
				from tb_market
				where a.market_id = ?
					and a.is_delete = 'n'
				";
				break;
			default:
				$query = "
						select *
						from tb_market
						where market_id = ?
						and is_delete = 'n'
				";
				break;
		}

		$where = array($market_id);

		return $this->test->query($query, $where)->row();
	}

	/**
	 * 마켓 추가
	 * @param unknown $params
	 */
	function insert_market($params) {
		$data = array(
				"market_name" => $params->market_name,
				"market_simple_info" => $params->market_simple_info,
				"market_address1" => $params->market_address1,
				"market_address2" => $params->market_address2,
				"postcd" => $params->postcd,
				"fee" => $params->fee,
				"average_delivery_time" => $params->average_delivery_time,
				"create_datetime" => date("Y-m-d H:i:s")
		);

		$this->test->insert("tb_market", $data);
		return $this->test->insert_id();
	}

	/**
	 * 마켓 삭제
	 * @param unknown $market_id
	 */
	function delete_market($market_id) {
		$data= array(
				"is_delete" => "y",
				"delete_datetime" => date("Y-m-d H:i:s")
		);

		$this->test->where("market_id", $market_id)->update("tb_market", $data);
	}

	/**
	 * 해당 마켓의 영역에서의 차기 순서 번호 반환
	 * @param unknown $market_id
	 * @param unknown $market_image_area
	 */
	function get_market_image_pre_order($market_id, $market_image_area) {
		$query = "
				select ifnull(max(image_pre_order),0) + 1 as image_pre_order
				from tb_market_image
				where market_id = ?
					and market_image_area = ?
					and is_delete = 'n'
			";

		return $this->test->query($query, array($market_id, $market_image_area))->row();
	}

	/**
	 * 마켓 이미지 추가
	 * @param unknown $params
	 * @param unknown $upload_data
	 */
	function insert_market_image($market_id, $market_image_area, $image_pre_order, $upload_data) {
		$data = array(
				"market_id" => $market_id,
				"market_image_area" => $market_image_area,
				"image_pre_order" => $image_pre_order,
				"file_path" => str_replace("C:", "", $upload_data["file_path"]),
				"file_name" => $upload_data["file_name"],
				"file_size" => $upload_data["file_size"],
				"image_width" => $upload_data["image_width"],
				"image_height" => $upload_data["image_height"],
				"create_datetime" => date("Y-m-d H:i:s")
		);

		$this->test->insert("tb_market_image", $data);
		return $this->test->insert_id();
	}

	/**
	 * 매장 이미지 리스트
	 * @param unknown $market_id
	 * @param unknown $market_image_area
	 * @param string $is_admin
	 */
	function get_market_image_list($market_id, $market_image_area, $is_admin = false) {
		$where = array(
				"market_id" => $market_id,
				"market_image_area" => $market_image_area,
				"is_delete" => "n"
		);

		if($is_admin) {
			$order_by = "image_pre_order asc";
		} else {
			$order_by = "image_order asc";
		}

		return $this->test->order_by($order_by)->get_where("tb_market_image", $where)->result();
	}

	/**
	 * 운영툴에서 지정한 매장 이미지 순서를 서비스에 적용하도록 업데이트
	 * @param unknown $market_id
	 * @param unknown $market_image_area
	 */
	function update_market_image_order($market_id, $market_image_area) {
		$query = "
				update tb_market_image
				set image_order = image_pre_order
				where market_id = ?
					and market_image_area = ?
				";

		return $this->test->query($query, array($market_id, $market_image_area));
	}

	/**
	 * 매장 이미지 정보
	 * @param unknown $where
	 */
	function get_market_image_info($where) {
		return $this->test->get_where("tb_market_image", $where)->row();
	}

	/**
	 * 매장 이미지 순서 지정(운영툴)
	 * @param unknown $market_image_id
	 * @param unknown $image_pre_order
	 */
	function update_market_image_pre_order($market_image_info, $relation_market_image_info, $add_order) {
		$this->test->trans_start();

		$result = $this->set_market_image_pre_order($market_image_info->market_image_id,  intval($market_image_info->image_pre_order) + $add_order) &&
						$this->set_market_image_pre_order($relation_market_image_info->market_image_id,  intval($relation_market_image_info->image_pre_order) - $add_order);

		$this->test->trans_complete();
		return $result;
	}

	private function set_market_image_pre_order($market_image_id, $image_pre_order) {
		return $this->test->update("tb_market_image", array("image_pre_order"=>$image_pre_order), array("market_image_id"=>$market_image_id));
	}

	/**
	 * 배달 가능 지역 삭제
	 * @param unknown $market_id
	 * @param unknown $address_dong_id
	 */
	function delete_delivery_location($market_id, $address_dong_id) {
		$where = array(
				"market_id" => $market_id,
				"address_dong_id" => $address_dong_id
		);

		return $this->test->delete("tb_market_delivery_location", $where);
	}

	/**
	 * 배달 가능 지역 업데이트(구군 단위 삭제 + 추가)
	 * @param unknown $market_id
	 * @param unknown $dong_list
	 * @param unknown $address_gugun_id
	 * @return boolean
	 */
	function update_delivery_location($market_id, $dong_list, $address_gugun_id) {
		$this->test->trans_start();

		$result = $this->delete_delivery_location_gugun($address_gugun_id) &&
												$this->insert_delivery_location($market_id, $dong_list);

		$this->test->trans_complete();
		return $result;
	}

	/**
	 * 배달 가능 지역 삭제
	 * @param unknown $address_gugun_id
	 */
	private function delete_delivery_location_gugun($address_gugun_id) {
		$query = "
				delete mdl
				from tb_market_delivery_location mdl, tb_address_dong d, tb_address_gugun g
				where mdl.address_dong_id = d.address_dong_id
					and d.address_gugun_id = g.address_gugun_id
					and g.address_gugun_id = ?
				";

		return $this->test->query($query, array($address_gugun_id));
	}

	/**
	 * 배달 가능 지역 추가
	 * @param unknown $market_id
	 * @param unknown $dong_list
	 */
	private function insert_delivery_location($market_id, $dong_list) {
		$query = "
				insert into tb_market_delivery_location (market_id, address_dong_id) values
				";

		foreach($dong_list as $address_dong_id) {
			$query .= "($market_id, $address_dong_id),";
		}

		$query = substr($query, 0, strlen($query) - 1);
		return $this->test->query($query);
	}

	/**
	 * 배달 가능 지역 목록 반환
	 * @param unknown $market_id
	 */
	function get_delivery_location_list($market_id) {
		$query = "
				select mdl.address_dong_id, s.sido, g.gugun, d.dong
				from tb_market_delivery_location mdl, tb_address_dong d, tb_address_gugun g, tb_address_sido s
				where mdl.address_dong_id = d.address_dong_id
					and d.address_gugun_id = g.address_gugun_id
					and g.address_sido_id = s.address_sido_id
					and mdl.market_id = ?
				";

		return $this->test->query($query, array($market_id))->result();
	}

	/**
	 * 메뉴 카테고리 삭제
	 * @param unknown $menu_id
	 */
	private function delete_menu_category_relation($menu_id) {
		return $this->test->delete("tb_menu_category_relation", array("menu_id" => $menu_id));
	}

	/**
	 * 메뉴 카테고리 추가
	 * @param unknown $menu_id
	 * @param unknown $menu_category_id_list
	 */
	private function insert_menu_category_relation($menu_id, $menu_category_id_list) {
		$query = "
				insert into tb_menu_category_relation (menu_id, menu_category_id) values
				";

		$where = array();
		foreach($menu_category_id_list as $menu_category_id) {
			$query .= "(?, ?),";

			array_push($where, $menu_id);
			array_push($where, $menu_category_id);
		}

		$query = substr($query, 0, strlen($query) - 1);
		return $this->test->query($query, $where);
	}

	/**
	 * 메뉴 카테고리 업데이트(메뉴 단위 삭제 + 추가)
	 * @param unknown $menu_id
	 * @param unknown $menu_category_id_list
	 * @return boolean
	 */
	function update_menu_category_relation($menu_id, $menu_category_id_list) {
		$this->test->trans_start();

		$result = $this->delete_menu_category_relation($menu_id) &&
						($menu_category_id_list ? $this->insert_menu_category_relation($menu_id, $menu_category_id_list) : true);

		$this->test->trans_complete();
		return $result;
	}

	function get_market_time_info($market_id, $day) {
		$where = array(
				"market_id" => $market_id,
				"week_day" => strtoupper($day)
		);
		return $this->test->get_where("tb_market_time", $where)->row();
	}
}

?>