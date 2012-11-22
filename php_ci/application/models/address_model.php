<?php
class Address_model extends CO_Model {
	function __construct() {
		parent::__construct();
	}

	/**
	 * 파라메터에 맞는 리스트 반환
	 * @param string $type
	 * @param int $address_id
	 */
	function get_market_delivery_location($type, $address_id = false) {

		switch($type) {
			case "sido":
				$query = "
						select address_sido_id, sido
						from address_sido
				";
				break;
			case "gugun":
				$query = "
						select address_gugun_id, gugun
						from address_gugun
						where address_sido_id = {$address_id}
				";
				break;
			case "dong":
				$query = "
						select address_dong_id, dong
						from address_dong
						where address_gugun_id = '{$address_id}'
						and is_active = 'Y'
				";

				$query = "
						select d.address_dong_id, d.dong, mdl.delivery_location_id
						from address_dong d left join market_delivery_location mdl
							on d.address_dong_id = mdl.address_dong_id
						where address_gugun_id = '{$address_id}'
						and is_active = 'Y'
						";
				break;
		}

		return $this->test->query($query)->result();
	}
}