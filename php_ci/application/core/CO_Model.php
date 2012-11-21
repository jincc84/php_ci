<?php
class CO_Model extends CI_Model {
	function __construct() {
		parent::__construct();

		$this->world = $this->load->database('world', TRUE);
		$this->test = $this->load->database('test', TRUE);
	}

	protected function generate_query($query, $param, $search_condition, $order_condition, $search_prepend = "", $order_prepend = "", $offset = -1, $limit = 0) {
		$binds = array();

		$where_clause = "";
		$order_by_clause = "";
		$limit_clause = "";

		$has_limit_clause = ($offset >= 0 && $limit > 0);

		if (!is_array($param)) $param = array($param);

		$segments = explode("?", $query);

		for ($i = 0, $n = count($segments); $i < $n; $i++) {

			$chunks = preg_split('/(\[:[_a-z]+:\])/', $segments[$i], null, PREG_SPLIT_DELIM_CAPTURE);

			for ($j = 0, $m = count($chunks); $j < $m; $j++) {
				if ($chunks[$j] == "[:where:]") {
					if (!empty($search_condition)) {
						foreach ($search_condition as $key => $val) {
							array_push($binds, sprintf($this->where_clauses[$key][1], $val));
						}
					}
				} else if ($has_limit_clause && $chunks[$j] == "[:limit:]") {
					$limit_clause = " LIMIT ?, ? ";
					array_push($binds, $offset);
					array_push($binds, $limit);
				}
			}

			if ($i < count($param))	array_push($binds, $param[$i]);
		}

		foreach ($search_condition as $key => $val) {
			if ($where_clause != "") $where_clause .= " AND";

			$where_clause .= " ".$this->where_clauses[$key][0]." ";
		}

		if (!empty($order_condition)) {
			foreach ($order_condition as $key => $val) {
				if ($order_by_clause != "") $order_by_clause .= ",";

				if (array_key_exists($key, $this->order_by_clauses)) {
					$order_by_clause .= " ".$this->order_by_clauses[$key]." ".$val." ";
				} else {
					$order_by_clause .= " ".$key." ".$val." ";
				}
			}
		}

		$rtn = str_replace("[:where:]", empty($where_clause) ? "" : $search_prepend." ".$where_clause, $query);
		$rtn = str_replace("[:orderby:]", empty($order_by_clause) ? "" : $order_prepend." ".$order_by_clause, $rtn);
		$rtn = str_replace("[:limit:]", $limit_clause, $rtn);

		return array($rtn, $binds);
	}
}
?>