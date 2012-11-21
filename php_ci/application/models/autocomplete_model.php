<?php
class Autocomplete_model extends CO_Model {
	function __construct() {
		parent::__construct();
	}

	function get_autocomplete_list($keyword) {
// 		$query = "SELECT address_id, CONCAT(sido, ' ', search_address) AS address, 1 AS part
// 						FROM address
// 						WHERE gugun LIKE '{$keyword}%'
// 						UNION
// 						SELECT address_id, CONCAT(sido, ' ', search_address) AS address, 2 AS part
// 						FROM address
// 						WHERE dong LIKE '{$keyword}%'
// 						ORDER BY part DESC";

		$query = "
			select s.sido, g.gugun, d.dong, CONCAT(s.sido, ' ', g.gugun, ' ', d.dong) as address, d.address_dong_id as address_id, d.part
			from address_sido s, address_gugun g, (
				select b.address_dong_id, a.address_sido_id, b.address_gugun_id, b.dong, 1 as part
				from address_gugun a, address_dong b
				where a.address_gugun_id = b.address_gugun_id
					and b.dong like '{$keyword}%'
					and b.is_used = 'y'
				union
				select b.address_dong_id, a.address_sido_id, b.address_gugun_id, b.dong, 2 as part
				from address_gugun a, address_dong b
				where a.address_gugun_id = b.address_gugun_id
					and a.gugun like '{$keyword}%'
					and b.is_used = 'y') d
			where s.address_sido_id = g.address_sido_id
				and s.address_sido_id = d.address_sido_id
				and g.address_gugun_id = d.address_gugun_id
			limit 0,10
		";

		return $this->test->query($query)->result();
	}
}

?>