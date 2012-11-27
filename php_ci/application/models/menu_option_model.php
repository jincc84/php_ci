<?php
class Menu_option_model extends CO_Model {
	function __construct() {
		parent::__construct();
	}

	function get_menu_option_group_list($menu_id) {
		$where = array(
				"menu_id" => $menu_id,
				"is_delete" => "n"
		);
		return $this->test->get_where("tb_menu_option_group", $where)->result();
	}

// 	function delete_menu_option_group_by_menu($menu_id) {
// 		$data= array(
// 				"is_delete" => "y",
// 				"delete_datetime" => date("Y-m-d H:i:s")
// 		);
// 		return $this->test->where("menu_id", $menu_id)->update("tb_menu_option_group", $data);
// 	}

// 	function delete_menu_option_by_menu($menu_id) {
// 		$query = "
// 				update tb_menu_option mo, tb_menu_option_group mog
// 				set mo.is_delete = 'y', mo.delete_datetime = '" . date("Y-m-d H:i:s") . "'
// 				where mo.menu_option_group_id = mog.menu_option_group_id
// 					and mog.menu_id = ?
// 				";

// 		return $this->test->query($query, array($menu_id));
// 	}

	function get_menu_option_list($menu_option_group_id) {
		$query = "
				select *
				from tb_menu_option_group mog, tb_menu_option mo
				where mog.menu_option_group_id = mo.menu_option_group_id
					and mo.is_delete = 'n'
					and mo.menu_option_group_id = ?
				";

		$where = array($menu_option_group_id);

		return $this->test->query($query, $where)->result();
	}

	function get_menu_option_info($menu_option_id) {
		return $this->test->get_where("tb_menu_option", array("menu_option_id"=>$menu_option_id))->row();
	}

	/**
	 * 옵션 그룹 + 옵션 추가/수정/삭제 (동시 처리)
	 * @param unknown $menu_id
	 * @param unknown $data
	 * @return unknown
	 */
	function modify_option($menu_id, $data) {
		// 		$this->test->trans_start();
		$this->test->trans_begin();

		// insert option group
		foreach($data["insert"]["option_group"] as $option_group) {
			$menu_option_group_id = $this->insert_menu_option_group($menu_id, $option_group);
			foreach($row["option_list"] as $option) {
				$this->insert_menu_option($menu_option_group_id, $option);
			}
		}

		// insert option
		foreach($data["insert"]["option"] as $option) {
			$this->insert_menu_option($option["menu_option_group_id"], $option);
		}

		// update option group
		foreach($data["update"]["option_group"] as $menu_option_group) {
			$this->update_menu_option_group($menu_option_group);
		}

		// update option
		foreach($data["update"]["option"] as $menu_option) {
			$this->update_menu_option($menu_option);
		}

		// delete option group
		foreach($data["remove"]["option_group_id"] as $menu_option_group_id) {
			$this->delete_menu_option_group($menu_option_group_id);
		}

		// delete option
		foreach($data["remove"]["option_id"] as $menu_option_id) {
			$this->delete_menu_option($menu_option_id);
		}

		// 		$this->test->trans_complete();
		$result_model = $this->test->trans_status();
		if($result_model) {
			$this->test->trans_commit();
		} else {
			$this->test->trans_rollback();
		}

		return $result_model;
	}

	function insert_menu_option_group($menu_id, $row) {
		$data = array(
				"menu_id" => $menu_id,
				"menu_option_group_name" => $row["menu_option_group_name"],
				"is_essential" => $row["is_essential"],
				"max_select" => $row["max_select"],
				"create_datetime" => date("Y-m-d H:i:s")
		);

		$this->test->insert("tb_menu_option_group", $data);
		return $this->test->insert_id();
	}

	function insert_menu_option($menu_option_group_id, $row) {
		$data = array(
				"menu_option_group_id" => $menu_option_group_id,
				"menu_option_name" => $row["menu_option_name"],
				"add_price" => $row["add_price"],
				"create_datetime" => date("Y-m-d H:i:s")
		);

		$this->test->insert("tb_menu_option", $data);
		return $this->test->insert_id();
	}

	function update_menu_option_group($row) {
		$data = array(
				"menu_option_group_name" => $row["menu_option_group_name"],
				"is_essential" => $row["is_essential"],
				"max_select" => $row["max_select"],
				"latest_update_datetime" => date("Y-m-d H:i:s")
		);

		return $this->test->where("menu_option_group_id", $row["menu_option_group_id"])->update("tb_menu_option_group", $data);
	}

	function update_menu_option($row) {
		$data = array(
				"menu_option_name" => $row["menu_option_name"],
				"add_price" => $row["add_price"],
				"latest_update_datetime" => date("Y-m-d H:i:s")
		);

		return $this->test->where("menu_option_id", $row["menu_option_id"])->update("tb_menu_option", $data);
	}

	function delete_menu_option_group($menu_option_group_id) {
		$data= array(
				"is_delete" => "y",
				"delete_datetime" => date("Y-m-d H:i:s")
		);
		return $this->test->where("menu_option_group_id", $menu_option_group_id)->update("tb_menu_option_group", $data);
	}

	function delete_menu_option($menu_option_id) {
		$data= array(
				"is_delete" => "y",
				"delete_datetime" => date("Y-m-d H:i:s")
		);
		return $this->test->where("menu_option_id", $menu_option_id)->update("tb_menu_option", $data);
	}
}

?>