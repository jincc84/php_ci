<?php
class Bootstrap extends CI_Controller {
	public function grid_system() {
		$this->load->view('bootstrap/grid_system');
	}

	public function layouts() {
		$this->load->view("bootstrap/layouts");
	}

	public function base_css() {
		$this->load->view("bootstrap/base_css");
	}
}
