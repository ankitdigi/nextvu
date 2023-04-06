<?php
date_default_timezone_set("Europe/London");
define('DB_HOST', 'localhost'); 
define('DB_USERNAME', 'root'); 
define('DB_PASSWORD', ''); 
define('DB_NAME', 'order-management');
$db = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
if ($db->connect_errno) {
	printf("Connect failed: %s\n", $db->connect_error);
	exit();
}

$sqlUpdt2 = 'UPDATE ci_orders_xml SET status = "1" WHERE status = "0"';
mysqli_query($db, $sqlUpdt2);

unlink("uploaded_files/invoice_xml/invoice_import.xml");
$output = '<?xml version="1.0" encoding="utf-8"?>';
$output .= '<Company xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema">';
	$output .= '<Invoices>';
	$output .= '</Invoices>';
$output .= '</Company>';
file_put_contents("uploaded_files/invoice_xml/invoice_import.xml",$output,FILE_APPEND);

echo 'Cron Run Successfully!!!. <br>';
exit;