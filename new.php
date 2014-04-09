<?php
require_once 'config.php';
require_once 'mirror-client.php';
require_once 'google-api-php-client/src/Google_Client.php';
require_once 'google-api-php-client/src/contrib/Google_MirrorService.php';
require_once 'util.php';
require_once 'auth.php';

include "../SASbulletin/simple_html_dom.php";
$link = "../SASbulletin/bulletin.html";
$htm = file_get_html($link);
$div = $htm->find('#contentdiv',0)->outertext;
$arr = explode('<hr />', $div);

for ($i = 0; $i < count($arr); $i++) {
	$arr[$i] = strip_tags($arr[$i]);
	$arr[$i] = str_replace('&nbsp;', '', $arr[$i]);
}

define("HOST", "166.62.8.6");
define("USER", "kartikyeGlass");
define("PASSWORD", "Myturtle1!"); 
define("DATABASE", "kartikyeGlass");

$handle = mysqli_connect(HOST, USER, PASSWORD, DATABASE) or die("could not connect");

$sql = "SELECT * FROM users";
$result = mysqli_query($handle, $sql);

while ($row = mysqli_fetch_array($result)) {

	$client = get_google_api_client();
	$userid = $row['userid'];
	verify_credentials(getStoredCredentials($userid));
	$client->setAccessToken(getStoredCredentials($userid));

	// Authenticate if we're not already

	$int = rand(0, 99999999);
	// A glass service for interacting with the Mirror API
	$mirror_service = new Google_MirrorService($client);
	for ($i = count($arr); $i > 1; $i--) {
		echo($arr[$i]);
		$menu_items = array();
		
		// A couple of built in menu items
		$menu_item = new Google_MenuItem();
		$menu_item->setAction("DELETE");
		array_push($menu_items, $menu_item);
		    
		$new_timeline_item = new Google_TimelineItem();
		$new_timeline_item->setText($arr[$i]);
		$new_timeline_item->setBundleId($int);
	
		$new_timeline_item->setMenuItems($menu_items);
	
		$notification = new Google_NotificationConfig();
		$notification->setLevel("DEFAULT");
		$new_timeline_item->setNotification($notification);
	
		insert_timeline_item($mirror_service, $new_timeline_item, null, null);
	}
}	
?>