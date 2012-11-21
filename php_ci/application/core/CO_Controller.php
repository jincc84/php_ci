<?php
require_once APPPATH.'config/redis_config/red_cfg.php';

define("NHN_MAP_API_KEY", "7914392a39f1c395b94f458c5b7a017f");
define("WHOIS_API_KEY", "2012102610523456410511");
define("POST_API_KEY", "d713adfe361be7f831352689246046");


/**
 *
 * Parameter 클래스
 *
 * @author bolman
 *
 */
class Params {
    private $self = array();

    public function __construct($params) {
        $this->self = $params;
    }

    public function __get($key) {
        return $this->self && array_key_exists($key, $this->self) ? $this->self[$key] : '';
    }

    public function __set($key, $value) {
    	$this->self[$key] = $value;
    }

    public function values() {
    	return $this->self;
    }

    public function get($key) {
    	return $this->self && array_key_exists($key, $this->self) ? $this->self[$key] : '';
    }

    public function key_exists($key) {
    	return array_key_exists($key, $this->self);
    }
}

/**
 *
 * Base Controller
 *
 * @author bolman
 *
 */
class CO_Controller extends CI_Controller {
    private $attribute = array();

    public $ftp_config = array(
    		'hostname' => FTP_HOST,
    		'username' => FTP_USER,
    		'password' => FTP_PWD
    );

    /**
     * load model
     *
     * @param unknown_type $model
     */
    public function load_model($model) {
        $this->load->model($model);
    }

    /**
     * load library
     *
     * @param unknown_type $library
     */
    public function load_library($library) {
        $this->load->library($library);
    }

    /**
     * load helper
     *
     * @param unknown_type $helper
     */
    public function load_helper($helper) {
    	$this->load->helper($helper);
    }

    /**
     * set pagination
     *
     * @param integer $total_count 전체 갯수
     * @param integer $per_page 페이지당 갯수
     */
    public function set_pagination($base_url, $total_count, $cur_page, $per_page = 10) {
        $this->load_library('pagination');

        $config = array(
        	'base_url' => $base_url,
            'total_rows' => $total_count,
            'per_page' => $per_page,
        	'cur_page' => $cur_page
        );

        $this->pagination->initialize($config);

        $this->set_attribute('pagination', $this->pagination->create_links());

        return $this->pagination;
    }

    /**
     * view에 내려줄 attribute 세팅
     *
     * @param unknown_type $key
     * @param unknown_type $value
     */
    public function set_attribute($key, $value) {
        $this->attribute[$key] = $value;
    }

    /**
     * 파라미터 설정
     *
     * @param unknown_type $type
     * @return Params
     */
    public function get_params($type = 'get') {
        $params = ($type === 'get') ? $this->input->get() : $this->input->post();
        return new Params($params);
    }

    /**
     * validate form fields
     *
     * @param unknown_type $page
     */
    public function validate_fields($page) {
    	$this->load_library('form_validation');
    	$this->form_validation->set_error_delimiters('<p class="help-inline error">','</p>');
    	return $this->form_validation->run($page);
    }

    /**
     * view 설정 (일반)
     *
     * @param unknown_type $view
     * @param unknown_type $layout
     */
    public function set_view($view, $layout = 'layout/layout_main') {
    	$this->layout->set_layout($layout);
    	$this->set_attribute('image_host', IMAGE_HOST);
        $this->layout->view($view, $this->attribute);
    }

	/**
     * view 설정 (팝업)
     *
     * @param unknown_type $view
     */
    public function set_popup_view($view, $is_narrow_width = true) {
        $this->set_attribute('is_narrow_width', $is_narrow_width);

    	$this->set_view($view, 'common/popup_layout');
    }

    /**
     * View 설정 (alert 용)
     *
     * @param unknown_type $msg
     * @param unknown_type $link
     * @param unknown_type $type
     */
    public function set_alert_view($msg, $link, $type) {
    	$this->set_attribute('message', $msg);
    	$this->set_attribute('link', $link);

    	$this->set_view('common/alert_and_'.$type, 'common/empty_layout');
    }

    public function add_admin_action_history($action, $detail = '', $admin_id = null) {
    	/*
        if (!$admin_id) {
            $this->load->library('encrypt');
            $admin_id = $this->encrypt->decode(get_cookie('session_id'));
        }

        if (!$admin_id) {
            return;
        }

        if ($detail) {
            $detail = var_export($detail, TRUE);
        }

        $this->load_model('admin/admin_history_model');
        $this->admin_history_model->insert($admin_id, $action, $detail);
        */
    }

    public function get_search_condition($params, $target) {
    	if (!isset($params) || !is_array($target) || $params->values() == false) return array();

    	$aParams = $params->values();
    	$condition = array();

    	foreach ($target as $key => $val) {
    		if (array_key_exists($key, $aParams)) {
    			if (!empty($aParams[$key])) $condition[$val] = $aParams[$key];
    		} else if (array_key_exists($val, $aParams)) {
    			if (!empty($aParams[$val])) $condition[$val] = $aParams[$val];
    		}
    	}

    	return $condition;
    }

    public function get_order_condition($params, $target) {
    	if (!isset($params) || !is_array($target)) return array();

    	$aParams = $params->values();
    	$condition = array();

    	foreach ($target as $key => $val) {
    		$order = "";

    		if (array_key_exists($key, $aParams)) {
    			$order = $this->_normalize_order_condition($aParams[$key]);
    		} else if (array_key_exists($val, $aParams)) {
    			$order = $this->_normalize_order_condition($aParams[$val]);
			}

    		if ($order != '') $condition[$val] = $order;
    	}

    	return $condition;
    }

    private function _normalize_order_condition($cond) {
    	$condition = strtolower($cond);

    	if ($condition == 'desc') return 'desc';
    	if ($condition == 'asc') return 'asc';

    	return '';
    }

//     /**
//      * URL 을 호출하여 얻은 XML data를 json -> array 형식 변경을 통해 반환
//      * @param string $url
//      * @return array
//      */
//     public function get_url_to_parse_array($url, $post_params = false) {
//     	$ch = curl_init();
//     	curl_setopt($ch, CURLOPT_URL, $url);
//     	if($post_params) {
//     		curl_setopt($ch, CURLOPT_POST, 1);
//     		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_params);
//     	}
//     	curl_setopt($ch, CURLOPT_FAILONERROR,1);
//     	curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
//     	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
//     	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.11 (KHTML, like Gecko) Chrome/23.0.1271.64 Safari/537.11");
//     	$result = curl_exec($ch);
//     	curl_close($ch);

//     	$json = json_encode(new SimpleXMLElement($result));
//     	$array = json_decode($json, true);

//     	return $array;
//     }

    /**
     * stream_context_create, file_get_contents 을 이용한 url parse
     * @param string $url
     * @return string
     */
    public function get_url_to_contents($url, $params = false) {
    	$opts = array(
    			"http" => array(
    					"method" => "GET",
    					"header" => "Accept-language:ko"
    					)
    			);

    	if($params) {
    		$url .= "?" . http_build_query($params);
    	}

    	$context = stream_context_create($opts);
    	$fp = file_get_contents($url, false, $context);
    	$xml = simplexml_load_string($fp, "SimpleXmlElement", LIBXML_NOCDATA);

    	$json = json_encode($xml);
    	$array = json_decode($json, true);

    	return $array;
    }

    /**
     * NHN Map API를 이용하여 맵 주소 노티에 필요한 정보 추출
     * @param unknown $address
     * @return json
     */
    function get_address_location($address) {
    	$address = str_replace(" ", "", $address);
    	$url = "http://openapi.map.naver.com/api/geocode.php?key=" . NHN_MAP_API_KEY . "&encoding=utf-8&coord=latlng&query=" . $address;
    	$url_result = $this->get_url_to_contents($url);

    	$error_code = isset($url_result["error_code"]) ? $url_result["error_code"] : false;
    	if(!$error_code) {
    		$total_result_count = $url_result["total"];
    		if($total_result_count == 1) {
    			$coord = $url_result["item"]["point"];
    		} else {
    			$coord = $url_result["item"][0]["point"];
    		}
    	}

    	$result = array(
    			"error_code" => $error_code,
    			"coord" => isset($coord) ? $coord : ""
    	);

    	return $result;
    }

    /**
     * whois API 를 호출하여 사용자 주소를 가져온다.
     * @param string $ip : "xxx.xxx.xxx.xxx"
     * @return string address
     */
    function get_ip_targeting_address($ip) {
    	$url = "http://whois.kisa.or.kr/openapi/whois.jsp?query=" . $ip . "&key=" . WHOIS_API_KEY;
    	$url_result = $this->get_url_to_contents($url);

    	$address = "";
    	if(isset($url_result["korean"]["user"]["netInfo"]["addr"])) {
    		$address = $url_result["korean"]["user"]["netInfo"]["addr"];
    	}

    	return $address;
    }

    /**
     * 두 위치 간의 직선 거리를 구한다.
     * @param array $location1 위치1의 경도(x), 위도(y)
     * @param array $location2 위치2의 경도(x), 위도(y)
     * @param string $unit 반환 단위(meter / kilometer / mile)
     * @return number 거리(단위 : m)
     */
    function get_distance($location1, $location2, $unit = "meter") {
    	if(!(isset($location1["x"]) && isset($location1["y"]) && isset($location2["x"]) && isset($location2["y"]))) {
    		return 0;
    	}

    	$distance_lng = $location1["x"] - $location2["x"];
    	$distance = sin(deg2rad($location1["y"])) * sin(deg2rad($location2["y"])) +
    	cos(deg2rad($location1["y"])) * cos(deg2rad($location2["y"])) * cos(deg2rad($distance_lng));
    	$distance = acos($distance);
    	$distance = rad2deg($distance);
    	$meters = $distance * 60 * 1.1515 * 1609.344;
    	$result = $meters;
    	switch($unit) {
    		case "mile":
    			$result /= 1609.344;
    			break;
    		case "kilometer":
    			$result /= 1000;
    			break;
    	}

    	echo "distance: " . $result . " " . $unit;

    	return $result;
    }
}
?>
