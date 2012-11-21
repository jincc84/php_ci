<?php

class Admin_auth {
	/**
	 *
	 * 로그인 및 권한 설정
	 */
	function auth() {
		$CI =& get_instance();

		$root_path = $CI->uri->segment(1);

		$CI->load->helper('cookie');

		// 임시 시작
		$auth_info = array();
		$auth_info['admin_user_name'] = "test";
		$auth_info['admin_user_id'] = "test";
		$auth_info['admin_type'] = "manager";
		$auth_info['login'] = TRUE;
		$auth_info['top_menu'] = $root_path;
		$auth_info['sub_menu'] = $CI->uri->segment(2);
		$auth_info['current_page'] = $CI->uri->segment(3);

		$CI->load->vars($auth_info);

		return;
		// 임시 끝


		if ($root_path == 'api' && $CI->uri->segment(2) == 'message' && ($CI->uri->segment(3) == 'send_get' || $CI->uri->segment(3) == 'send_post')) {
			return;
		}

		if ($root_path == 'batch') {
			return;
		}

		// 로그인 상태가 아닐경우, login_form 으로 redirect
		if (!get_cookie('session_id') AND $root_path != 'common') {
			$CI->load->helper('url');
			redirect('http://'.$_SERVER['HTTP_HOST'].'/common/login/form/');
		}

		$auth_info = array();
		if (get_cookie('session_id')) {
			$CI->load->library('encrypt');
			$admin_user_id = $CI->encrypt->decode(get_cookie('session_id'));

			$condition = array();
			$condition['admin_user_id'] = $admin_user_id;
			$condition['is_active'] = 'Y';

			$CI->load->model('admin/admin_model');

			$admin_user_info = $CI->admin_model->select_admin($condition);
			if ($admin_user_info == null) {
				delete_cookie('session_id');

				$CI->load->helper('url');
				redirect('http://'.$_SERVER['HTTP_HOST'].'/common/login/form/');
			} else {
				//$auth_list = $CI->admin_model->select_admin_has_auth_list($admin_id);

				//foreach ($auth_list as $auth) {
				//	if ($auth->auth_id == $menu && $auth->has_auth == 'N') {
				//		$CI->load->helper('url');
				//		redirect('http://'.$_SERVER['HTTP_HOST'].'/common/access_denied/');
				//	}
				//}

				// 로그인 및 권한 설정
				$auth_info['admin_user_name'] = $admin_user_info->admin_user_name;
				$auth_info['admin_user_id'] = $admin_user_info->admin_user_id;
				$auth_info['admin_type'] = $admin_user_info->admin_type;
				$auth_info['login'] = TRUE;
				$auth_info['top_menu'] = $root_path;
				$auth_info['sub_menu'] = $CI->uri->segment(2);
				$auth_info['current_page'] = $CI->uri->segment(3);
				//$auth_info['auth_list'] = $auth_list;

				$CI->load->vars($auth_info);

				// TODO 지출/방문 통계를 위한 권한 임시작업
				if ($admin_user_info->admin_user_id == 'tmonplus') {
					if (!($root_path == 'report' && $CI->uri->segment(2) == 'pay_visit' && ($CI->uri->segment(3) == 'index' || $CI->uri->segment(3) == 'statistic'))
							&& !($root_path == 'common' && $CI->uri->segment(2) == 'login' && $CI->uri->segment(3) == 'logout_action')) {
						$CI->load->helper('url');
						redirect('http://'.$_SERVER['HTTP_HOST'].'/report/pay_visit/index/');
					}
				}
			}
		}
	}
}
?>
