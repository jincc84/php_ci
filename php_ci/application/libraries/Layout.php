<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Layout {
	var $obj;
	var $layout;

	function Layout($layout = "layout/layout_main") {
		$this->obj =& get_instance();
		$this->layout = $layout;
	}

	function set_layout($layout) {
		$this->layout = $layout;
	}

	function view($view, $data=null, $return=false)	{
		$loaded_data = array();
		$loaded_data['content_in_layout'] = $this->obj->load->view($view, $data, true);
// 		$loaded_data['menu_in_layout'] = $this->obj->load->get_var('menu');
// 		$loaded_data['login_in_layout'] = $this->obj->load->get_var('login');
// 		$loaded_data['auth_in_layout'] = $this->obj->load->get_var('auth_list');

		if ($return) {
			$output = $this->obj->load->view($this->layout, $loaded_data, true);

			return $output;
		} else {
			$this->obj->load->view($this->layout, $loaded_data, false);
		}
	}
}