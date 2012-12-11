<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class App extends CI_Controller {
	const APP_OS_IOS = "iOS";
	const APP_OS_ANDROIDOS = "androidOS";
	const APP_OS_WEBOS = "webOS";
	const APP_OS_BLACKBERRY = "BlackBerry";
	const APP_OS_RIM_TABLET = "RimTablet";

	/**
	 * tmonplus 설치 페이지 링크
	 */
	public function tmonplus_store() {
		$os_detection = $this->os_detection();
		switch($os_detection) {
			case App::APP_OS_IOS:
				$link = "http://itun.es/kr/bBjpI.i";
				break;
			case App::APP_OS_ANDROIDOS:
				$link = "market://details?id=com.ticketmonster.tmonplus";
				break;
			default:
				$link = "https://play.google.com/store/apps/details?id=com.ticketmonster.tmonplus";
				break;
		}

// 		echo $_SERVER["HTTP_USER_AGENT"] . "<br />";
// 		echo $link;
		header('Location: ' . $link);
	}

	/**
	 * app os detection
	 * @return string|NULL
	 */
	private function os_detection() {
		$user_agent = strtoupper($_SERVER["HTTP_USER_AGENT"]);

		$iPod = strpos($user_agent, "IPOD") !== false;
		$iPhone = strpos($user_agent, "IPHONE") !== false;
		$iPad = strpos($user_agent, "IPAD") !== false;

		if(strpos($user_agent, "ANDROID") !== false) {
			$Android = (strpos($user_agent, "MOBILE") !== false);
			$AndroidTablet = true;
		} else {
			$Android = false;
			$AndroidTablet = false;
		}
/*
		if(strpos($user_agent, "Android") && strpos($user_agent, "mobile")) {
			$Android = true;
		} elseif(strpos($user_agent, "Android")) {
			$Android = false;
			$AndroidTablet = true;
		} else {
			$Android = false;
			$AndroidTablet = false;
		}
*/
		$webOS = (strpos($user_agent,"WEBOS") !== false);
		$BlackBerry = (strpos($user_agent,"BLACKBERRY") !== false);
		$RimTablet= (strpos($user_agent,"RIM TABLET") !== false);

		if($iPod || $iPhone || $iPad) {
			return App::APP_OS_IOS;
		} elseif($Android || $AndroidTablet) {
			return App::APP_OS_ANDROIDOS;
		} elseif($webOS) {
			return App::APP_OS_WEBOS;
		} elseif($BlackBerry) {
			return App::APP_OS_BLACKBERRY;
		} elseif($RimTablet) {
			return App::APP_OS_RIM_TABLET;
		}

		return null;
	}
}