<?php
class Test_model extends CO_Model {
	function __construct() {
		parent::__construct();
	}

	function get_continent_list() {
		return $this->world->select("Continent")->group_by("Continent")->get("country")->result();
	}

	function get_country_list($continent, $country) {
		return $this->world->where("continent", $continent)->like("Name", $country)->get("country")->result();
	}
}

?>