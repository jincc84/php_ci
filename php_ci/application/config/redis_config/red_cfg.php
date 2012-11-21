<?php
define("UPLOAD_PATH", "/test_upload");

switch(ENVIRONMENT)
{
	case 'local':
	case 'development':
	case 'testing':
	case 'production':
		$IMAGE_HOST = 'http://image.test.com';

		$FTP_HOST = '';
		$FTP_USER = '';
		$FTP_PWD = '';

		$REDIS_SERVER = '';
		$API_HOST = '';
	break;
	default:
		exit('The application environment is not set correctly.');
}

define('REDIS_SERVER', $REDIS_SERVER);
define('API_HOST', $API_HOST);
define('IMAGE_HOST', $IMAGE_HOST);
define('FTP_HOST', $FTP_HOST);
define('FTP_USER', $FTP_USER);
define('FTP_PWD', $FTP_PWD);
?>
