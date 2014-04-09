<?php
require_once 'config.php';
require_once 'mirror-client.php';
require_once 'google-api-php-client/src/Google_Client.php';
require_once 'google-api-php-client/src/contrib/Google_MirrorService.php';
require_once 'util.php';
require_once 'auth.php';

if (!isset($_SESSION['userid']) || get_credentials($_SESSION['userid']) == null) {
  	header('Location: ' . $base_url . '/oauth2callback.php');
  	exit;
} else {
	verify_credentials(get_credentials($_SESSION['userid']));
	storeCredentials($_SESSION['userid'], get_credentials($_SESSION['userid']));
	echo(get_credentials($_SESSION['userid']));
}

?>