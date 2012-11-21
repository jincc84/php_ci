<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH.'core/CO_Controller.php';

class Autocomplete_address extends CO_Controller {
	public function index()
	{
		$q = $this->input->get_post("q");
		$limit = 15;

		$result = "";
		if(strlen(trim($q)) > 0) {
			$this->load->model('autocomplete_model');
			$list = $this->autocomplete_model->get_autocomplete_list($q);

			foreach($list as $row) {
				$result .= json_encode($row)."\n";
			}
		}

		$this->output->set_output($result);
	}
}