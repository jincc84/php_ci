<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH.'core/CO_Controller.php';

class Autocomplete extends CO_Controller {

	public function index()
	{
		$this->layout->set_layout('layout/layout_main');

		$data = array();

		$this->layout->view('prototype/autocomplete', $data);
	}
}