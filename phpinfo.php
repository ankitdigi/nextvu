<?php
date_default_timezone_set("Europe/London");
define('DB_HOST', 'shareddb-e.mvps.stackdb.net'); 
define('DB_USERNAME', 'readonly_user'); 
define('DB_PASSWORD', "o'f67=64'[£G'"); 
define('DB_NAME', 'order-management-323134992a');
$db = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
if ($db->connect_errno) {
	printf("Connect failed: %s\n", $db->connect_error);
	exit();
}

$edate = date('Y-m-29');
$sqls = "SELECT id, vet_user_id, lab_id, order_number FROM ci_orders WHERE `order_type` != '2' AND `is_confirmed` = '4' AND `is_draft` = '0' AND `shipping_date` <= '". $edate ."' AND `unit_price` != '' AND `unit_price` > 0";
$result = $db->query($sqls);
if($result->num_rows > 0){
	while($row = $result->fetch_assoc()){
		echo '<pre>';
		print_r($row);
	}
}
echo 'd';
exit;
?>