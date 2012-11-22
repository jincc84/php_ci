<?php
class Menu_model extends CO_Model {
	function __construct() {
		parent::__construct();
	}

	/**
	 * 메뉴 카테고리 추가
	 * @param unknown $params
	 */
	function insert_menu_category($params) {
		$data = array(
				"market_id" => $params->market_id,
				"menu_category_type" => $params->menu_category_type,
				"menu_category_name" => $params->menu_category_name,
				"create_datetime" => date("Y-m-d H:i:s")
		);

		$this->test->insert("menu_category", $data);
		return $this->test->insert_id();
	}

	function delete_menu_category($menu_category_id) {
		$data= array(
				"is_delete" => "y",
				"delete_datetime" => date("Y-m-d H:i:s")
		);
		return $this->test->where("menu_category_id", $menu_category_id)->update("menu_category", $data);
	}

	/**
	 * 메뉴 카테고리 리스트 반환
	 * @param unknown $market_id
	 */
	function get_menu_category_list($market_id) {
		$where = array(
				"market_id" => $market_id,
				"is_delete" => "n"
				);
		return $this->test->get_where("menu_category", $where)->result();
	}

	/**
	 * 메뉴 추가
	 * @param unknown $params
	 */
	function insert_menu($params) {
		$data = array(
				"market_id" => $params->market_id,
				"menu_image_id" => $params->menu_image_id,
				"menu_name" => $params->menu_name,
				"price" => $params->price,
				"fee" => $params->fee,
				"create_datetime" => date("Y-m-d H:i:s")
		);

		$this->test->insert("menu", $data);
		return $this->test->insert_id();
	}

	/**
	 * 메뉴 수정
	 * @param unknown $params
	 */
	function update_menu($params) {
		$data = array(
				"menu_name" => $params->menu_name,
				"price" => $params->price,
				"fee" => $params->fee,
				"menu_image_id" => ($params->menu_image_id == "" ? null : $params->menu_image_id)
		);

		return $this->test->where("menu_id", $params->menu_id)->update("menu", $data);
	}

	/**
	 * 메뉴 삭제
	 * @param unknown $menu_id
	 */
	function delete_menu($menu_id) {
		$data= array(
				"is_delete" => "y",
				"delete_datetime" => date("Y-m-d H:i:s")
		);
		return $this->test->where("menu_id", $menu_id)->update("menu", $data);
	}

	/**
	 * 메뉴 리스트 반환
	 * @param unknown $column
	 * @param unknown $value
	 */
	function get_menu_list($market_id, $type = "default") {
		switch($type) {
			case "menu_id_unique":
				$query = "
					select m.*,
						(select concat(file_path, file_name) from menu_image where menu_image_id = m.menu_image_id) as menu_image_path
					from menu m
					where is_delete = 'n'
					and market_id = ?
				";

				$where = array($market_id);
				break;
			default:
				$query = "
					select m.menu_id, m.menu_name, m.price, m.fee, a.menu_category_id,
						(select concat(file_path, file_name) from menu_image where menu_image_id = m.menu_image_id) as menu_image_path
					from menu m left join (
					select mcr.menu_id, mc.menu_category_id, mc.menu_category_name
					from menu_category_relation mcr, menu_category mc
					where mcr.menu_category_id = mc.menu_category_id
					and mc.market_id = ?
					) a
					on m.menu_id = a.menu_id
					where m.is_delete = 'n'
					and m.market_id = ?
				";

				$where = array($market_id, $market_id);
				break;
		}

		return $this->test->query($query, $where)->result();
	}

	/**
	 * 메뉴 정보 반환
	 * @param unknown $menu_id
	 */
	function get_menu_info($menu_id) {
		return $this->test->get_where("menu", array("menu_id"=>$menu_id))->row();
	}

	function insert_menu_image($menu_id, $upload_data) {
		$data = array(
				"menu_id" => ($menu_id == "" ? null : $menu_id),
				"file_path" => str_replace("C:", "", $upload_data["file_path"]),
				"file_name" => $upload_data["file_name"],
				"file_size" => $upload_data["file_size"],
				"image_width" => $upload_data["image_width"],
				"image_height" => $upload_data["image_height"],
				"create_datetime" => date("Y-m-d H:i:s")
		);

		$this->test->insert("menu_image", $data);
		return $this->test->insert_id();
	}

	function update_menu_image($menu_image_id, $data) {
		return $this->test->where("menu_image_id", $menu_image_id)->update("menu_image", $data);
	}
}

?>