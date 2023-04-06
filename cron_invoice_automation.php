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

$todayDate = date('Y-m-d');
$lastDate = date('Y-m-26');
$edate = date('Y-m-25');
if($todayDate == $lastDate){
	$sqls = "SELECT id, vet_user_id, lab_id, order_number FROM ci_orders WHERE `order_type` != '2' AND `is_confirmed` = '4' AND `is_draft` = '0' AND `shipping_date` <= '". $edate ."' AND `unit_price` != '' AND `unit_price` > 0";
	$result = $db->query($sqls);
	if($result->num_rows > 0){
		$user_id = 0;
		while($row = $result->fetch_assoc()){
			if($row['lab_id'] > 0){
				$sqluk = "SELECT managed_by_id FROM `ci_users` WHERE id = '". $row['lab_id'] ."'";
				$responuk = $db->query($sqluk);
				$resultuk = $responuk->fetch_assoc();
				if(isset($resultuk['managed_by_id']) && !empty($resultuk['managed_by_id'])){
					$zoneby = explode(",",$resultuk['managed_by_id']);
				}else{
					$zoneby = array();
				}
				$user_id = $row['lab_id'];
			}else{
				$sqluk = "SELECT managed_by_id FROM `ci_users` WHERE id = '". $row['vet_user_id'] ."'";
				$responuk = $db->query($sqluk);
				$resultuk = $responuk->fetch_assoc();
				if(isset($resultuk['managed_by_id']) && !empty($resultuk['managed_by_id'])){
					$zoneby = explode(",",$resultuk['managed_by_id']);
				}else{
					$zoneby = array();
				}
				$user_id = $row['vet_user_id'];
			}
			if(empty($zoneby) || in_array("1", $zoneby)){
				$sqlPro = "INSERT INTO ci_orders_xml(order_id,order_number,user_id,invoice_date,invoice_by,status) VALUES ('". $row['id'] ."','". $row['order_number'] ."','". $user_id ."','". date('Y-m-d') ."','0','0')";
				$db->query($sqlPro);

				$sqld1p = "UPDATE ci_orders SET is_confirmed = '0', is_invoiced = '1' WHERE id = '". $row['id'] ."'";
				$db->query($sqld1p);

				$sqld2p = "INSERT INTO ci_order_history(order_id,text,created_by,created_at) VALUES ('". $row['id'] ."','Invoiced','0','". date("Y-m-d H:i:s") ."')";
				$db->query($sqld2p);
			}
		}

		$sql1s = "SELECT user_id FROM ci_orders_xml WHERE `status` = '0' GROUP BY user_id ORDER BY user_id ASC";
		$result1s = $db->query($sql1s);
		if($result1s->num_rows > 0){
			$output  = '<?xml version="1.0" encoding="utf-8"?>';
			$output .= '<Company xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema">';
				$output .= '<Invoices>'; $type1 = 0; $type2 = 0; $type3 = 0; $t1=0; $t2=0; $t3=0;
				while($rowusr = $result1s->fetch_assoc()){
					$sql2s = "SELECT order_id FROM ci_orders_xml WHERE `status` = '0' AND `user_id` = '". $rowusr['user_id'] ."' ORDER BY order_id ASC";
					$result2s = $db->query($sql2s);
					$invoiceIdArr = array();
					while($rowodr = $result2s->fetch_assoc()){
						$invoiceIdArr[] = $rowodr['order_id'];
					}
					$invoiceIds = !empty($invoiceIdArr)?implode(",",$invoiceIdArr):0;
					$sql3s = "SELECT id, vet_user_id, branch_id, lab_id, lab_branch_id, name, email, phone_number, unit_price, order_type, shipping_cost, order_discount, sub_order_type, order_can_send_to, order_number, reference_number, comment, delivery_practice_id, allergens, product_code_selection, single_double_selection, order_date, batch_number, pet_owner_id, pet_id, plc_selection FROM ci_orders WHERE `is_draft` = '0' AND `id` IN(". $invoiceIds .") AND `unit_price` != '' AND `unit_price` > 0 ORDER BY order_type DESC";
					$result3s = $db->query($sql3s);
					if($result3s->num_rows > 0){
						$sql3as = "SELECT COUNT(id) as totalOrder,order_type FROM ci_orders WHERE `is_draft` = '0' AND `id` IN(". $invoiceIds .") AND `unit_price` != '' AND `unit_price` > 0 GROUP BY order_type";
						$result3as = $db->query($sql3as);
						while($rowtyp = $result3as->fetch_assoc()){
							if($rowtyp['order_type'] == 1){
								$type1 = $rowtyp['totalOrder'];
							}elseif($rowtyp['order_type'] == 2){
								$type2 = $rowtyp['totalOrder'];
							}elseif($rowtyp['order_type'] == 3){
								$type3 = $rowtyp['totalOrder'];
							}
						}
						$Itemqty = ""; $unitPrice = ""; $order_discount = 0; $nominalCode = ""; $company = ""; $vatApplicable = ''; $shippingPrice1 = 0; $shippingPrice2 = 0; $shippingPrice3 = 0; $userId = 0; $t1=0; $t2=0; $t3=0; $t1ID = ""; $t2ID = ""; $t3ID = "";
						while($rowinv = $result3s->fetch_assoc()){
							$nameArr = explode(" ",$rowinv['name']);
							if(!empty($nameArr) && count($nameArr) == 3){
								$fname = !empty($nameArr[0])?$nameArr[0]:'';
								$mname = !empty($nameArr[1])?$nameArr[1]:'';
								$sname = !empty($nameArr[2])?$nameArr[2]:'';
							}elseif(!empty($nameArr) && count($nameArr) == 2){
								$fname = !empty($nameArr[0])?$nameArr[0]:'';
								$mname = !empty($nameArr[1])?$nameArr[1]:'';
								$sname = '';
							}elseif(!empty($nameArr) && count($nameArr) == 1){
								$fname = !empty($nameArr[0])?$nameArr[0]:'';
								$mname = '';
								$sname = '';
							}else{
								$fname = '';
								$mname = '';
								$sname = '';
							}

							if($rowinv['order_can_send_to'] == '1'){
								$userId = $rowinv['delivery_practice_id'];
								$sql4s = "SELECT column_field,column_name FROM ci_user_details WHERE `user_id` = '". $rowinv['delivery_practice_id'] ."' AND column_name IN('address_2','address_3','account_ref','add_1','add_2','add_3','add_4')";
								$result4s = $db->query($sql4s);
								$adrDetails = array();
								while($rowadr = $result4s->fetch_assoc()){
									$adrDetails[$rowadr['column_name']] = $rowadr['column_field'];
								}
								$address_1 = !empty($adrDetails['add_1'])?$adrDetails['add_1']:'';
								$address_2 = !empty($adrDetails['add_2'])?$adrDetails['add_2']:'';
								$address_3 = !empty($adrDetails['add_3'])?$adrDetails['add_3']:'';
								$town = !empty($adrDetails['add_4'])?$adrDetails['add_4']:'';
								$postcode = !empty($adrDetails['address_3'])?$adrDetails['address_3']:'';
								$sql41s = "SELECT country FROM ci_users WHERE `id` = '". $rowinv['delivery_practice_id'] ."'";
								$result41s = $db->query($sql41s);
								$rowct = $result41s->fetch_assoc();
								if($rowct['country'] == 1){ $country = 'Netherlands'; }else{ $country = 'Ireland'; }
							}elseif($rowinv['order_can_send_to'] == '0'){
								if($rowinv['lab_id'] > 0){
									$userId = $rowinv['lab_id'];
									$sql4s = "SELECT * FROM ci_user_details WHERE `user_id` = '". $rowinv['lab_id'] ."' AND column_name IN('address_1','address_2','address_3','post_code', 'town_city')";
									$result4s = $db->query($sql4s);
									$adrDetails = array();
									while($rowadr = $result4s->fetch_assoc()){
										$adrDetails[$rowadr['column_name']] = $rowadr['column_field'];
									}
									$address_1 = !empty($adrDetails['address_1'])?$adrDetails['address_1']:'';
									$address_2 = !empty($adrDetails['address_2'])?$adrDetails['address_2']:'';
									$address_3 = !empty($adrDetails['address_3'])?$adrDetails['address_3']:'';
									$town = !empty($adrDetails['town_city'])?$adrDetails['town_city']:'';
									$postcode = !empty($adrDetails['post_code'])?$adrDetails['post_code']:'';
									$sql41s = "SELECT country FROM ci_users WHERE `id` = '". $rowinv['lab_id'] ."'";
									$result41s = $db->query($sql41s);
									$rowct = $result41s->fetch_assoc();
									if($rowct['country'] == 1){ $country = 'Netherlands'; }else{ $country = 'Ireland'; }
								}else{
									$userId = $rowinv['vet_user_id'];
									$sql4s = "SELECT address, address1, address2, postcode FROM ci_branches WHERE `vet_user_id` = '". $rowinv['vet_user_id'] ."'";
									$result4s = $db->query($sql4s);
									if($result4s->num_rows > 0){
										$adrDetails = $result4s->fetch_assoc();
										$address_1 = !empty($adrDetails['address'])?$adrDetails['address']:'';
										$address_2 = !empty($adrDetails['address1'])?$adrDetails['address1']:'';
										$address_3 = !empty($adrDetails['address2'])?$adrDetails['address2']:'';
										$town = !empty($adrDetails['address1'])?$adrDetails['address1']:'';
										$postcode = !empty($adrDetails['postcode'])?$adrDetails['postcode']:'';
									}else{
										$sql41s = "SELECT * FROM ci_user_details WHERE `user_id` = '". $rowinv['vet_user_id'] ."' AND column_name IN('address_1','address_2','address_3','account_ref','add_1','add_2','add_3','add_4')";
										$result41s = $db->query($sql41s);
										$adrDetails = array();
										while($rowadr = $result41s->fetch_assoc()){
											$adrDetails[$rowadr['column_name']] = $rowadr['column_field'];
										}
										$address_1 = !empty($adrDetails['add_1'])?$adrDetails['add_1']:'';
										$address_2 = !empty($adrDetails['add_2'])?$adrDetails['add_2']:'';
										$address_3 = !empty($adrDetails['add_3'])?$adrDetails['add_3']:'';
										$town = !empty($adrDetails['add_4'])?$adrDetails['add_4']:'';
										$postcode = !empty($adrDetails['address_3'])?$adrDetails['address_3']:'';
									}
									$sql41s = "SELECT country FROM ci_users WHERE `id` = '". $rowinv['vet_user_id'] ."'";
									$result41s = $db->query($sql41s);
									$rowct = $result41s->fetch_assoc();
									if($rowct['country'] == 1){ $country = 'Netherlands'; }else{ $country = 'Ireland'; }
								}
							}

							if($rowinv['lab_id'] > 0){
								$sql5s = "SELECT * FROM ci_user_details WHERE `user_id` = '". $rowinv['lab_id'] ."' AND column_name IN('account_ref','vat_applicable')";
								$result5s = $db->query($sql5s);
								$actDetails = array();
								while($rowact = $result5s->fetch_assoc()){
									$actDetails[$rowact['column_name']] = $rowact['column_field'];
								}
								$account_ref = !empty($actDetails['account_ref'])?$actDetails['account_ref']:'';
								$vatApplicable = !empty($actDetails['vat_applicable'])?$actDetails['vat_applicable']:'0';
								$sql51s = "SELECT name,last_name FROM ci_users WHERE `id` = '". $rowinv['lab_id'] ."'";
								$result51s = $db->query($sql51s);
								$compInfo = $result51s->fetch_assoc();
								if($compInfo['name'] != "" && $compInfo['last_name'] != ''){
								$company = $compInfo['name'] .' '. $compInfo['last_name'];
								}elseif($compInfo['name'] != "" && $compInfo['last_name'] == ''){
								$company = $compInfo['name'];
								}elseif($compInfo['name'] == "" && $compInfo['last_name'] != ''){
								$company = $compInfo['last_name'];
								}
							}else{
								$sql5s = "SELECT * FROM ci_user_details WHERE `user_id` = '". $rowinv['vet_user_id'] ."' AND column_name IN('account_ref','vat_applicable')";
								$result5s = $db->query($sql5s);
								$actDetails = array();
								while($rowact = $result5s->fetch_assoc()){
									$actDetails[$rowact['column_name']] = $rowact['column_field'];
								}
								$account_ref = !empty($actDetails['account_ref'])?$actDetails['account_ref']:'';
								$vatApplicable = !empty($actDetails['vat_applicable'])?$actDetails['vat_applicable']:'0';
								if($account_ref == ''){
									$sql52s = "SELECT customer_number FROM ci_branches WHERE `vet_user_id` = '". $rowinv['vet_user_id'] ."'";
									$result52s = $db->query($sql52s);
									if($result52s->num_rows > 0){
										$actsDetails = $result52s->fetch_assoc();
										$account_ref = !empty($actsDetails['customer_number'])?$actsDetails['customer_number']:'';
									}
								}
								$sql51s = "SELECT name,last_name FROM ci_users WHERE `id` = '". $rowinv['vet_user_id'] ."'";
								$result51s = $db->query($sql51s);
								$compInfo = $result51s->fetch_assoc();
								if($compInfo['name'] != "" && $compInfo['last_name'] != ''){
								$company = $compInfo['name'] .' '. $compInfo['last_name'];
								}elseif($compInfo['name'] != "" && $compInfo['last_name'] == ''){
								$company = $compInfo['name'];
								}elseif($compInfo['name'] == "" && $compInfo['last_name'] != ''){
								$company = $compInfo['last_name'];
								}
							}

							$selected_allergen = json_decode($rowinv['allergens']);
							$total_allergen = ($rowinv['allergens'] != '')?count(json_decode($rowinv['allergens'])):0;
							if($rowinv['lab_id'] != 0){
								$practice_lab = $rowinv['lab_id'];
							}else{
								$practice_lab = $rowinv['vet_user_id'];
							}

							if($rowinv['order_date'] != "0000-00-00" && $rowinv['order_date'] != "" && $rowinv['order_date'] != NULL){
								$orderDate =  'Order date '. date("d/m/Y",strtotime($rowinv['order_date']));
								$orderDate2 =  '- Order date '. date("d/m/Y",strtotime($rowinv['order_date']));
							}else{
								$orderDate =  ''; $orderDate2 =  '';
							}

							if($rowinv['plc_selection'] == 1){
								$orderNo =  'Order Number '. $rowinv['order_number'];
							}elseif($rowinv['plc_selection'] == 2){
								$orderNo =  'Order Number '. $rowinv['reference_number'];
							}

							if($rowinv['pet_owner_id'] > 0){
								$sql6s = "SELECT name,last_name FROM ci_users WHERE `id` = '". $rowinv['pet_owner_id'] ."'";
								$result6s = $db->query($sql6s);
								$petownInfo = $result6s->fetch_assoc();
								if($petownInfo['name'] != "" && $petownInfo['last_name'] != ''){
								$petowner = $petownInfo['name'] .' '. $petownInfo['last_name'];
								}elseif($petownInfo['name'] != "" && $petownInfo['last_name'] == ''){
								$petowner = $petownInfo['name'];
								}elseif($petownInfo['name'] == "" && $petownInfo['last_name'] != ''){
								$petowner = $petownInfo['last_name'];
								}
							}else{
								$petowner = '';
							}

							if($rowinv['pet_id'] > 0){
								$sql7s = "SELECT name FROM ci_pets WHERE `id` = '". $rowinv['pet_id'] ."'";
								$result7s = $db->query($sql7s);
								$petInfo = $result7s->fetch_assoc();
								$petName = $petInfo['name'];
							}else{
								$petName = '';
							}

							if($userId > 0){
								$sql8s = "SELECT name,last_name FROM ci_users WHERE `id` = '". $userId ."'";
								$result8s = $db->query($sql8s);
								if($result8s->num_rows > 0){
									$sendInfo = $result8s->fetch_assoc();
									if($sendInfo['name'] != "" && $sendInfo['last_name'] != ''){
									$send_to = '- '.$sendInfo['name'] .' '. $sendInfo['last_name'];
									}elseif($sendInfo['name'] != "" && $sendInfo['last_name'] == ''){
									$send_to = '- '.$sendInfo['name'];
									}elseif($sendInfo['name'] == "" && $sendInfo['last_name'] != ''){
									$send_to = '- '.$sendInfo['last_name'];
									}
								}else{
									$send_to = '';
								}
							}else{
								$send_to = '';
							}

							$comment1 = htmlspecialchars($petName).' '.htmlspecialchars($petowner).' '.$orderDate2;
							$comment2 = $orderNo.' '.htmlspecialchars($send_to);
							if($account_ref != '' && htmlspecialchars($company) != '' && $total_allergen > 0 && $rowinv['unit_price'] != '' && $rowinv['unit_price'] > 0){
								$zinkID = getZynkId();
								$zinkID = $zinkID+1;
								if($rowinv['order_type'] == '3'){
									if($t3 == 0){
									$sqlzup = "UPDATE ci_zynkids SET zynkid = '".$zinkID."' WHERE id = '1'";
									$db->query($sqlzup);
									$output .= '<Invoice>
										<Id>'. $zinkID .'</Id>
										<AccountReference>'. $account_ref .'</AccountReference>
										<CustomerOrderNumber>'. $rowinv['order_number'] .'</CustomerOrderNumber>
										<TakenBy>Website</TakenBy>
										<InvoiceDeliveryAddress>
											<Title>Mr</Title>
											<Forename>'.$fname.'</Forename>
											<Middlename>'.$mname.'</Middlename>
											<Surname>'.$sname.'</Surname>
											<Suffix>Jr.</Suffix>
											<Company>'. htmlspecialchars($company) .'</Company>
											<Address1>'. htmlspecialchars($address_1) .'</Address1>
											<Address2>'. htmlspecialchars($address_2) .'</Address2>
											<Address3>'. htmlspecialchars($address_3) .'</Address3>
											<Town>'. $town .'</Town>
											<Postcode>'. $postcode .'</Postcode>
											<County>'. $country .'</County>
											<Telephone>'. $rowinv['phone_number'] .'</Telephone>
										</InvoiceDeliveryAddress>
										<InvoiceItems>';
									}
									$skin_test_Ncode = skin_test_price($practice_lab);
									$nominalCode = $skin_test_Ncode[0]['nominal_code'];
									$single_price = $skin_test_Ncode[0]['uk_price'];
									$single_insect_price = $skin_test_Ncode[1]['uk_price'];
									$single_Pcode = $skin_test_Ncode[0]['sage_code'];
									$single_insect_Pcode = $skin_test_Ncode[1]['sage_code'];
									$shippingPrice3 += $rowinv['shipping_cost'];

									$selected_allergen_ids = implode(",", $selected_allergen);
									$insects_allergen = insect_allergen($selected_allergen_ids);
									$single_allergen = $total_allergen - $insects_allergen;
									$single_discount = get_discount("14", $practice_lab);
									$single_order_discount = 0;
									if(!empty($single_discount)){
										$single_order_discount = $single_discount['uk_discount'];
										$single_order_discount = sprintf("%.2f", $single_order_discount);
									}
									if($single_allergen > 0){
										$itemName = 'Artuvetrin Test';
										$output .= '<Item>
											<Sku>'.$single_Pcode.'</Sku>
											<Name>'.$itemName.'</Name>
											<Description>'.$orderDate.'</Description>
											<Comments>'.$comment2.'</Comments>
											<QtyOrdered>'.$single_allergen.'</QtyOrdered>
											<UnitPrice>'. $single_price .'</UnitPrice>
											<UnitDiscountPercentage>'. $single_order_discount .'</UnitDiscountPercentage>
											<NominalCode>'. $nominalCode .'</NominalCode>
											<TaxCode>'.$vatApplicable.'</TaxCode>
											<Department>1</Department>
										</Item>';
									}

									if($insects_allergen > 0){
										$insects_order_discount = 0;
										$insects_discount = get_discount("15", $practice_lab);
										if(!empty($insects_discount)){
											$insects_order_discount = $insects_discount['uk_discount'];
											$insects_order_discount = sprintf("%.2f", $insects_order_discount);
										}
										$itemName = 'Artuvetrin Test - Insect';
										$output .= '<Item>
											<Sku>'.$single_insect_Pcode.'</Sku>
											<Name>'.$itemName.'</Name>
											<Description>'.$orderDate.'</Description>
											<Comments>'.$comment2.'</Comments>
											<QtyOrdered>'.$insects_allergen.'</QtyOrdered>
											<UnitPrice>'. $single_insect_price .'</UnitPrice>
											<UnitDiscountPercentage>'.$insects_order_discount.'</UnitDiscountPercentage>
											<NominalCode>'. $nominalCode .'</NominalCode>
											<TaxCode>'.$vatApplicable.'</TaxCode>
											<Department>1</Department>
										</Item>';
									}

									$t3++;
									if($type3 == $t3){
										$output .= '</InvoiceItems>
											<Carriage>
												<Sku>150</Sku>
												<QtyOrdered>1</QtyOrdered>
												<UnitPrice>'. $shippingPrice3 .'</UnitPrice>
												<NominalCode>4905</NominalCode>
												<TaxCode>'.$vatApplicable.'</TaxCode>
												<Department>1</Department>
											</Carriage>
											<Notes1>'. htmlspecialchars($rowinv['comment']) .'</Notes1>
											<Notes2></Notes2>
											<Notes3></Notes3>
										</Invoice>';
									}
								}elseif($rowinv['order_type'] == '2'){
									if($t2 == 0){
									$sqlzup = "UPDATE ci_zynkids SET zynkid = '".$zinkID."' WHERE id = '1'";
									$db->query($sqlzup);
									$output .= '<Invoice>
										<Id>'. $zinkID .'</Id>
										<AccountReference>'. $account_ref .'</AccountReference>
										<CustomerOrderNumber>'. $rowinv['order_number'] .'</CustomerOrderNumber>
										<TakenBy>Website</TakenBy>
										<InvoiceDeliveryAddress>
											<Title>Mr</Title>
											<Forename>'.$fname.'</Forename>
											<Middlename>'.$mname.'</Middlename>
											<Surname>'.$sname.'</Surname>
											<Suffix>Jr.</Suffix>
											<Company>'. htmlspecialchars($company) .'</Company>
											<Address1>'. htmlspecialchars($address_1) .'</Address1>
											<Address2>'. htmlspecialchars($address_2) .'</Address2>
											<Address3>'. htmlspecialchars($address_3) .'</Address3>
											<Town>'. $town .'</Town>
											<Postcode>'. $postcode .'</Postcode>
											<County>'. $country .'</County>
											<Telephone>'. $rowinv['phone_number'] .'</Telephone>
										</InvoiceDeliveryAddress>
										<InvoiceItems>';
									}
									$itemName = 'Serum Testing';
									$shippingPrice2 += $rowinv['shipping_cost'];
									$serum_discount = get_discount($rowinv['product_code_selection'], $practice_lab);
									if (!empty($serum_discount)) {
										$order_discount = $serum_discount['uk_discount'];
										$order_discount = sprintf("%.2f", $order_discount);
									}
									$serum_test_Ncode = serum_test_price($rowinv['product_code_selection'], $practice_lab);
									$nominalCode = $serum_test_Ncode[0]['nominal_code'];
									$serum_unitPrice = $serum_test_Ncode[0]['uk_price'];
									$serum_Pcode = $serum_test_Ncode[0]['sage_code'];
									$output .= '<Item>
										<Sku>'.$serum_Pcode.'</Sku>
										<Name>'.$itemName.'</Name>
										<Description>'.$orderDate.'</Description>
										<Comments>'.$comment2.'</Comments>
										<QtyOrdered>'.$total_allergen.'</QtyOrdered>
										<UnitPrice>'. $serum_unitPrice .'</UnitPrice>
										<UnitDiscountPercentage>'.$order_discount.'</UnitDiscountPercentage>
										<NominalCode>'. $nominalCode .'</NominalCode>
										<TaxCode>'.$vatApplicable.'</TaxCode>
										<Department>1</Department>
									</Item>';
									$t2++;
									if($type2 == $t2){
										$output .= '</InvoiceItems>
											<Carriage>
												<Sku>150</Sku>
												<QtyOrdered>1</QtyOrdered>
												<UnitPrice>'. $shippingPrice2 .'</UnitPrice>
												<NominalCode>4905</NominalCode>
												<TaxCode>'.$vatApplicable.'</TaxCode>
												<Department>1</Department>
											</Carriage>
											<Notes1>'. htmlspecialchars($rowinv['comment']) .'</Notes1>
											<Notes2></Notes2>
											<Notes3></Notes3>
										</Invoice>';
									}
								}elseif($rowinv['order_type'] == '1'){
									if($t1 == 0){
									$sqlzup = "UPDATE ci_zynkids SET zynkid = '".$zinkID."' WHERE id = '1'";
									$db->query($sqlzup);
									$output .= '<Invoice>
										<Id>'. $zinkID .'</Id>
										<AccountReference>'. $account_ref .'</AccountReference>
										<CustomerOrderNumber>'. $rowinv['order_number'] .'</CustomerOrderNumber>
										<TakenBy>Website</TakenBy>
										<InvoiceDeliveryAddress>
											<Title>Mr</Title>
											<Forename>'.$fname.'</Forename>
											<Middlename>'.$mname.'</Middlename>
											<Surname>'.$sname.'</Surname>
											<Suffix>Jr.</Suffix>
											<Company>'. htmlspecialchars($company) .'</Company>
											<Address1>'. htmlspecialchars($address_1) .'</Address1>
											<Address2>'. htmlspecialchars($address_2) .'</Address2>
											<Address3>'. htmlspecialchars($address_3) .'</Address3>
											<Town>'. $town .'</Town>
											<Postcode>'. $postcode .'</Postcode>
											<County>'. $country .'</County>
											<Telephone>'. $rowinv['phone_number'] .'</Telephone>
										</InvoiceDeliveryAddress>
										<InvoiceItems>';
										
									}

									if($rowinv['sub_order_type'] == '1'){
										$shippingPrice1 += $rowinv['shipping_cost'];

										$artuvetrin_test_Ncode = artuvetrin_test_price($practice_lab);
										if ($total_allergen <= 4) {
											$itemName = 'Artuvetrin Therapy';
											$artuvetrin_discount = get_discount("16", $practice_lab);
											if (!empty($artuvetrin_discount)) {
												$order_discount = $artuvetrin_discount['uk_discount'];
												$order_discount = sprintf("%.2f", $order_discount);
											}
											$nominalCode = $artuvetrin_test_Ncode[0]['nominal_code'];
											$artuvetrin_unitPrice = $artuvetrin_test_Ncode[0]['uk_price'];
											$artuvetrin_Pcode = $artuvetrin_test_Ncode[0]['sage_code'];
											$output .= '<Item>
												<Sku>'.$artuvetrin_Pcode.'</Sku>
												<Name>'.$itemName.'</Name>
												<Description>'.$comment1.'</Description>
												<Comments>'.$comment2.'</Comments>
												<QtyOrdered>1</QtyOrdered>
												<UnitPrice>'. $artuvetrin_unitPrice .'</UnitPrice>
												<UnitDiscountPercentage>'.$order_discount.'</UnitDiscountPercentage>
												<NominalCode>'. $nominalCode .'</NominalCode>
												<TaxCode>'.$vatApplicable.'</TaxCode>
												<Department>1</Department>
											</Item>';
										} elseif ($total_allergen > 4 && $total_allergen <= 8) {
											$itemName = 'Artuvetrin Therapy Forte';
											$artuvetrin_discount = get_discount("17", $practice_lab);
											if (!empty($artuvetrin_discount)) {
												$order_discount = $artuvetrin_discount['uk_discount'];
												$order_discount = sprintf("%.2f", $order_discount);
											}
											$nominalCode = $artuvetrin_test_Ncode[1]['nominal_code'];
											$artuvetrin_unitPrice = $artuvetrin_test_Ncode[1]['uk_price'];
											$artuvetrin_Pcode = $artuvetrin_test_Ncode[1]['sage_code'];
											$output .= '<Item>
												<Sku>'.$artuvetrin_Pcode.'</Sku>
												<Name>'.$itemName.'</Name>
												<Description>'.$comment1.'</Description>
												<Comments>'.$comment2.'</Comments>
												<QtyOrdered>1</QtyOrdered>
												<UnitPrice>'. $artuvetrin_unitPrice .'</UnitPrice>
												<UnitDiscountPercentage>'.$order_discount.'</UnitDiscountPercentage>
												<NominalCode>'. $nominalCode .'</NominalCode>
												<TaxCode>'.$vatApplicable.'</TaxCode>
												<Department>1</Department>
											</Item>';
										} elseif ($total_allergen > 8) {
											$final_price = 0.00;
											$first_range_price = 0.00;
											$order_first_discount = 0.00;
											$order_second_discount = 0.00;
											$quotients = ($total_allergen / 8);
											$quotient = ((int)($total_allergen / 8));
											$remainder = (int)(fmod($total_allergen, 8));

											$artuvetrin_second_discount = get_discount("17", $practice_lab);
											$_quotients = $quotients - $quotient;
											$is_update=1;
											if (!empty($artuvetrin_second_discount)) {
												if ($_quotients > 0.50) {
													$quotient++;
													$is_update=0;
													$order_second_discount = $artuvetrin_second_discount['uk_discount'];
													$order_second_discount = sprintf("%.2f", $order_second_discount);
												} else {
													$order_second_discount = $artuvetrin_second_discount['uk_discount'];
													$order_second_discount = sprintf("%.2f", $order_second_discount);
												}
											}
											$nominalCode = $artuvetrin_test_Ncode[1]['nominal_code'];
											$artuvetrin_unitPrice = $artuvetrin_test_Ncode[1]['uk_price'];
											$artuvetrin_PcodeF = $artuvetrin_test_Ncode[1]['sage_code'];
											if ($_quotients > 0.50) {
												if($is_update){
													$quotient++;
												}
											}
											$output .= '<Item>
												<Sku>'.$artuvetrin_PcodeF.'</Sku>
												<Name>Artuvetrin Therapy Forte</Name>
												<Description>'.$comment1.'</Description>
												<Comments>'.$comment2.'</Comments>
												<QtyOrdered>'.$quotient.'</QtyOrdered>
												<UnitPrice>'. $artuvetrin_unitPrice .'</UnitPrice>
												<UnitDiscountPercentage>'.$order_second_discount.'</UnitDiscountPercentage>
												<NominalCode>'. $nominalCode .'</NominalCode>
												<TaxCode>'.$vatApplicable.'</TaxCode>
												<Department>1</Department>
											</Item>';
											if($remainder > 0){
												$artuvetrin_first_discount = get_discount("16",$practice_lab);
												if( !empty($artuvetrin_first_discount) ){
													if($_quotients <= 0.50 && $_quotients != 0) {
														$order_first_discount = $artuvetrin_first_discount['uk_discount'];
														$order_first_discount = sprintf("%.2f", $order_first_discount);
													}
												}
											}
											if($_quotients <= 0.50 && $_quotients != 0) {
												$nominalCode = $artuvetrin_test_Ncode[0]['nominal_code'];
												$artuvetrin_unitPrice = $artuvetrin_test_Ncode[0]['uk_price'];
												$artuvetrin_Pcode = $artuvetrin_test_Ncode[0]['sage_code'];
												$output .= '<Item>
													<Sku>'.$artuvetrin_Pcode.'</Sku>
													<Name>Artuvetrin Therapy</Name>
													<Description>'.$comment1.'</Description>
													<Comments>'.$comment2.'</Comments>
													<QtyOrdered>1</QtyOrdered>
													<UnitPrice>'. $artuvetrin_unitPrice .'</UnitPrice>
													<UnitDiscountPercentage>'.$order_first_discount.'</UnitDiscountPercentage>
													<NominalCode>'. $nominalCode .'</NominalCode>
													<TaxCode>'.$vatApplicable.'</TaxCode>
													<Department>1</Department>
												</Item>';
											}
											$order_discount = $order_first_discount + $order_second_discount;
										}
									}

									if($rowinv['sub_order_type'] == '2'){
										$itemName = 'Immunotherapy';
										$shippingPrice1 += $rowinv['shipping_cost'];

										$selected_allergen_ids = implode(",", $selected_allergen);
										$culicoides_allergen = culicoides_allergen($selected_allergen_ids);
										$slit_test_Ncode = slit_test_price($practice_lab);
										$nominalCode = $slit_test_Ncode[0]['nominal_code'];
										$single_price = $slit_test_Ncode[0]['uk_price'];
										$single_Pcode = $slit_test_Ncode[0]['sage_code'];
										$double_price = $slit_test_Ncode[1]['uk_price'];
										$double_Pcode = $slit_test_Ncode[1]['sage_code'];
										$single_with_culicoides = $slit_test_Ncode[2]['uk_price'];
										$single_with_culicoides_Pcode = $slit_test_Ncode[2]['sage_code'];
										$double_with_culicoides = $slit_test_Ncode[3]['uk_price'];
										$double_with_culicoides_Pcode = $slit_test_Ncode[3]['sage_code'];
										$single_allergen = $total_allergen - $culicoides_allergen;
										if($rowinv['single_double_selection'] == '1' && $culicoides_allergen == 0){
											$slit_discount = get_discount("18", $practice_lab);
											if (!empty($slit_discount)) {
												$order_discount = $slit_discount['uk_discount'];
												$order_discount = sprintf("%.2f", $order_discount);
											}
											$output .= '<Item>
												<Sku>'.$single_Pcode.'</Sku>
												<Name>Sublingual Single</Name>
												<Description>'.$comment1.'</Description>
												<Comments>'.$comment2.'</Comments>
												<QtyOrdered>'.$total_allergen.'</QtyOrdered>
												<UnitPrice>'. $single_price .'</UnitPrice>
												<UnitDiscountPercentage>'.$order_discount.'</UnitDiscountPercentage>
												<NominalCode>'. $nominalCode .'</NominalCode>
												<TaxCode>'.$vatApplicable.'</TaxCode>
												<Department>1</Department>
											</Item>';
										}elseif($rowinv['single_double_selection'] == '2' && $culicoides_allergen == 0){
											$slit_discount = get_discount("19", $practice_lab);
											if (!empty($slit_discount)) {
												$order_discount = $slit_discount['uk_discount'];
												$order_discount = sprintf("%.2f", $order_discount);
											}
											$output .= '<Item>
												<Sku>'.$double_Pcode.'</Sku>
												<Name>Sublingual Double</Name>
												<Description>'.$comment1.'</Description>
												<Comments>'.$comment2.'</Comments>
												<QtyOrdered>'.$total_allergen.'</QtyOrdered>
												<UnitPrice>'. $double_price .'</UnitPrice>
												<UnitDiscountPercentage>'.$order_discount.'</UnitDiscountPercentage>
												<NominalCode>'. $nominalCode .'</NominalCode>
												<TaxCode>'.$vatApplicable.'</TaxCode>
												<Department>1</Department>
											</Item>';
										}elseif($rowinv['single_double_selection'] == '1' && $culicoides_allergen > 0){
											$slit_discount = get_discount("20", $practice_lab);
											if (!empty($slit_discount)) {
												$order_discount = $slit_discount['uk_discount'];
												$order_discount = sprintf("%.2f", $order_discount);
											}
											$culicoidesPrices = $single_price + $single_with_culicoides;
											$culicoidesQty = $single_allergen + $culicoides_allergen;
											$output .= '<Item>
												<Sku>'.$single_with_culicoides_Pcode.'</Sku>
												<Name>Sublingual Single with culicoides</Name>
												<Description>'.$comment1.'</Description>
												<Comments>'.$comment2.'</Comments>
												<QtyOrdered>'.$culicoidesQty.'</QtyOrdered>
												<UnitPrice>'. $culicoidesPrices .'</UnitPrice>
												<UnitDiscountPercentage>'.$order_discount.'</UnitDiscountPercentage>
												<NominalCode>'. $nominalCode .'</NominalCode>
												<TaxCode>'.$vatApplicable.'</TaxCode>
												<Department>1</Department>
											</Item>';
										}elseif($rowinv['single_double_selection'] == '2' && $culicoides_allergen > 0){
											$slit_discount = get_discount("21", $practice_lab);
											if (!empty($slit_discount)) {
												$order_discount = $slit_discount['uk_discount'];
												$order_discount = sprintf("%.2f", $order_discount);
											}
											$sdPrices = $double_price + $double_with_culicoides;
											$sdQty = $single_allergen + $culicoides_allergen;
											$output .= '<Item>
												<Sku>'.$double_with_culicoides_Pcode.'</Sku>
												<Name>Sublingual Double with culicoides</Name>
												<Description>'.$comment1.'</Description>
												<Comments>'.$comment2.'</Comments>
												<QtyOrdered>'.$sdQty.'</QtyOrdered>
												<UnitPrice>'. $sdPrices .'</UnitPrice>
												<UnitDiscountPercentage>'.$order_discount.'</UnitDiscountPercentage>
												<NominalCode>'. $nominalCode .'</NominalCode>
												<TaxCode>'.$vatApplicable.'</TaxCode>
												<Department>1</Department>
											</Item>';
										}
									}
									$t1++;
									if($type1 == $t1){
										$output .= '</InvoiceItems>
											<Carriage>
												<Sku>150</Sku>
												<QtyOrdered>1</QtyOrdered>
												<UnitPrice>'. $shippingPrice1 .'</UnitPrice>
												<NominalCode>4905</NominalCode>
												<TaxCode>'.$vatApplicable.'</TaxCode>
												<Department>1</Department>
											</Carriage>
											<Notes1>'. htmlspecialchars($rowinv['comment']) .'</Notes1>
											<Notes2></Notes2>
											<Notes3></Notes3>
										</Invoice>';
									}
								}
							}else{
								if($rowinv['order_type'] == 1){ $type1--; }elseif($rowinv['order_type'] == 2){ $type2--; }elseif($rowinv['order_type'] == 3){ $type3--; }
								$sql1up = "DELETE FROM ci_orders_xml WHERE order_id = '". $rowinv['id'] ."'";
								$db->query($sql1up);
								
								$sql2up = "UPDATE ci_orders SET is_confirmed = '4', is_invoiced = '0' WHERE id = '". $rowinv['id'] ."'";
								$db->query($sql2up);

								$sql3up = "DELETE FROM ci_order_history WHERE order_id = '". $rowinv['id'] ."' AND text LIKE 'Invoiced'";
								$db->query($sql3up);
							}
						}
					}
				}
			$output .= '</Invoices>';
			$output .= '</Company>';
			$file_name = fopen("uploaded_files/invoice_xml/invoice_import.xml", "w") or die("Unable to open file!");
			fwrite($file_name, $output);
			fclose($file_name);
		}
	}
	echo 'Cron Run Successfully!!!. <br>';
	echo '<b>'. $result->num_rows .'</b> Order added into XML.';
}else{
	echo 'This cron valid for run on '. date('Y-m-01', strtotime(date('Y-m')." +1 month")) .' Only.';
}

function getZynkId(){
	$db = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
	$sqlu = "SELECT zynkid FROM ci_zynkids WHERE `id` = '1'";
	$resultu = $db->query($sqlu);
	$rowu = $resultu->fetch_assoc();
	return $rowu['zynkid'];
}

function practiceLabCountry($practice_id){
	$db = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
	$sqls = "SELECT user.id,country.name FROM ci_users AS user LEFT JOIN ci_countries AS country ON country.id = user.country WHERE user.id = '". $practice_id ."'";
	$results = $db->query($sqls);
	if($results->num_rows > 0){
		return $results->fetch_assoc();
	}else{
		return array();
	}
}

function skin_test_price($practice_lab){
	$db = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
	$practiceLab = practiceLabCountry($practice_lab);
	if($practiceLab['name'] == 'Ireland' || $practiceLab['name'] == 'ireland'){
		$sqls = "SELECT id,name,roi_price AS uk_price,sage_code,nominal_code FROM ci_price WHERE id IN(14,15)";
	}else{
		$sqls = "SELECT id,name,uk_price,sage_code,nominal_code FROM ci_price WHERE id IN(14,15)";
	}
	$results = $db->query($sqls);
	if($results->num_rows > 0){
		return $results->fetch_all(MYSQLI_ASSOC);
	}else{
		return array();
	}
}

function serum_test_price($product_code_id,$practice_lab){
	$db = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
	$practiceLab = practiceLabCountry($practice_lab);
	if($practiceLab['name']=='Ireland' || $practiceLab['name']=='ireland'){
		$sqls = "SELECT id,name,roi_price AS uk_price,sage_code,nominal_code FROM ci_price WHERE id = '".$product_code_id."'";
	}else{
		$sqls = "SELECT id,name,uk_price,sage_code,nominal_code FROM ci_price WHERE id = '".$product_code_id."'";
	}
	$results = $db->query($sqls);
	if($results->num_rows > 0){
		return $results->fetch_all(MYSQLI_ASSOC);
	}else{
		return array();
	}
}

function artuvetrin_test_price($practice_lab){
	$db = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
	$practiceLab = practiceLabCountry($practice_lab);
	if($practiceLab['name'] == 'Ireland' || $practiceLab['name'] == 'ireland'){
		$sqls = "SELECT id,name,roi_price AS uk_price,sage_code,nominal_code FROM ci_price WHERE id IN(16,17)";
	}else{
		$sqls = "SELECT id,name,uk_price,sage_code,nominal_code FROM ci_price WHERE id IN(16,17)";
	}
	$results = $db->query($sqls);
	if($results->num_rows > 0){
		return $results->fetch_all(MYSQLI_ASSOC);
	}else{
		return array();
	}
}

function slit_test_price($practice_lab){
	$db = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
	$practiceLab = practiceLabCountry($practice_lab);
	if($practiceLab['name'] == 'Ireland' || $practiceLab['name'] == 'ireland'){
		$sqls = "SELECT id,name,roi_price AS uk_price,sage_code,nominal_code FROM ci_price WHERE id IN(18,19,20,21)";
	}else{
		$sqls = "SELECT id,name,uk_price,sage_code,nominal_code FROM ci_price WHERE id IN(18,19,20,21)";
	}
	$results = $db->query($sqls);
	if($results->num_rows > 0){
		return $results->fetch_all(MYSQLI_ASSOC);
	}else{
		return array();
	}
}

function getShippingCostbyUser($id, $practice_id) {
	$db = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
	$sqls = "SELECT id,uk_discount,roi_discount FROM ci_user_shipping WHERE shipping_id = '".$id."' AND practice_id = '".$practice_id."'";
	$results = $db->query($sqls);
	if($results->num_rows > 0){
		return $results->fetch_assoc();
	}else{
		return array();
	}
}

function getDefaultShippingCost($id) {
	$db = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
	$sqls = "SELECT id,uk_price,roi_price FROM ci_shipping_price WHERE id = '".$id."'";
	$results = $db->query($sqls);
	if($results->num_rows > 0){
		return $results->fetch_assoc();
	}else{
		return array();
	}
}

function get_discount($id, $practice_id) {
	$db = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
	$sqls = "SELECT id,uk_discount FROM ci_discount WHERE product_id = '".$id."' AND practice_id = '".$practice_id."'";
	$results = $db->query($sqls);
	if($results->num_rows > 0){
		return $results->fetch_assoc();
	}else{
		return array();
	}
}

function insect_allergen($allergen_ids){
	$db = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
	$sqls = "SELECT id FROM ci_allergens WHERE parent_id = '0' AND name = 'Insects'";
	$results = $db->query($sqls);
	$result = $results->fetch_assoc();

	$sql1s = "SELECT id,name FROM ci_allergens WHERE parent_id = '".$result['id']."' AND id IN(".$allergen_ids.")";
	$result1s = $db->query($sql1s);
	return $result1s->num_rows;
}

function culicoides_allergen($allergen_ids){
	$db = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
	$sqls = "SELECT id FROM ci_allergens WHERE parent_id = '0' AND name = 'Culicoides'";
	$results = $db->query($sqls);
	$result = $results->fetch_assoc();

	$sql1s = "SELECT id,name FROM ci_allergens WHERE parent_id = '".$result['id']."' AND id IN(".$allergen_ids.")";
	$result1s = $db->query($sql1s);
	return $result1s->num_rows;
}