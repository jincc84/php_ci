<?php
class Menu_option_model extends CO_Model {
	function __construct() {
		parent::__construct();
	}

	function insert_menu_option_group($params) {
		$data = array(
				"menu_id" => $params->menu_id,
				"menu_option_group_name" => $params->menu_option_group_name,
				"is_essential" => ($params->is_essential) ? 'y' : 'n',
				"max_select" => (!$params->max_select ? 0 : $params->max_select)
		);

		$this->test->insert("menu_option_group", $data);
		return $this->test->insert_id();
	}

	function get_menu_option_group_list($menu_id) {
		$where = array(
				"menu_id" => $menu_id,
				"is_delete" => "n"
		);
		return $this->test->get_where("menu_option_group", $where)->result();
	}

	function delete_menu_option_group($menu_option_group_id) {
		$data= array(
				"is_delete" => "y",
				"delete_datetime" => date("Y-m-d H:i:s")
		);
		return $this->test->where("menu_option_group_id", $menu_option_group_id)->update("menu_option_group", $data);
	}

	function delete_menu_option_group_by_menu($menu_id) {
		$data= array(
				"is_delete" => "y",
				"delete_datetime" => date("Y-m-d H:i:s")
		);
		return $this->test->where("menu_id", $menu_id)->update("menu_option_group", $data);
	}

	function insert_menu_option($params) {
		$data = array(
				"menu_option_group_id" => $params->menu_option_group_id,
				"menu_option_name" => $params->menu_option_name,
				"add_price" => $params->add_price
		);

		$this->test->insert("menu_option", $data);
		return $this->test->insert_id();
	}

	function delete_menu_option($menu_option_id) {
		$data= array(
				"is_delete" => "y",
				"delete_datetime" => date("Y-m-d H:i:s")
		);
		return $this->test->where("menu_option_id", $menu_option_id)->update("menu_option", $data);
	}

	function delete_menu_option_by_menu($menu_id) {
		$query = "
				update menu_option mo, menu_option_group mog
				set mo.is_delete = 'y', mo.delete_datetime = '" . date("Y-m-d H:i:s") . "'
				where mo.menu_option_group_id = mog.menu_option_group_id
					and mog.menu_id = ?
				";

		return $this->test->query($query, array($menu_id));
	}

// 	function get_menu_option_list($where) {
// 		$query = "
// 				select *
// 				from menu_option_group mog, menu_option mo
// 				where mog.menu_option_group_id = mo.menu_option_group_id
// 					and mo.is_delete = 'n'
// 				";

// 		$where = array();
// 		foreach($where as $key => $val) {
// 			$query .= "
// 			and $key = ?
// 			";

// 			$where.push($val);
// 		}

// 		return $this->test->query($query, $where)->result();
// 	}

	function get_menu_option_list($menu_option_group_id) {
		$query = "
				select *
				from menu_option_group mog, menu_option mo
				where mog.menu_option_group_id = mo.menu_option_group_id
					and mo.is_delete = 'n'
					and mo.menu_option_group_id = ?
				";

		$where = array($menu_option_group_id);

		return $this->test->query($query, $where)->result();
	}
}

?>